<?php
require_once __DIR__ . '/logger.php';
// videos.php - Caches YouTube channel videos into videos.json and serves JSON to the client
// Initial bulk fetch up to 500 videos, then incremental updates based on lastUpdatedEpoch

// ==== CONFIG - Replace with your actual API key and channel ID ====
$YOUTUBE_API_KEY = 'AIzaSyAVaWhY-20hhe2XHTSGWzbxVLqUsyhFb7k';
$CHANNEL_ID = 'UCsfxqDhPpjHQBjymEFK5r9Q';

// ==== POLICY ====
$INITIAL_FETCH_LIMIT = 500;           // One-time bulk cache size
$PER_DAY_LIMIT_MULTIPLIER = 3;        // Videos per day since last update (e.g., 3 videos per day)
$MAX_FETCH_LIMIT = 200;               // Safety cap to prevent excessive API calls

// ==== FILE PATHS ====
$CACHE_FILE = __DIR__ . '/videos.json';
$SEARCH_CACHE_FILE = __DIR__ . '/videos_search.json';

header('Content-Type: application/json; charset=utf-8');

// ---- Helpers ----
function http_get_json($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 20);
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $err = curl_error($ch);
    curl_close($ch);
    if ($response === false || $httpCode >= 400) {
        $preview = '';
        if (is_string($response) && strlen($response) > 0) {
            $preview = substr($response, 0, 500);
        }
        logger_log('video', 'ERROR', 'HTTP request failed', [ 'url' => $url, 'status' => $httpCode, 'error' => $err, 'responseBodyPreview' => $preview ]);
        throw new Exception('HTTP request failed: ' . ($err ?: ('status ' . $httpCode)));
    }
    $data = json_decode($response, true);
    if ($data === null) {
        $preview = is_string($response) ? substr($response, 0, 500) : '';
        logger_log('video', 'ERROR', 'Failed to decode JSON response', [ 'url' => $url, 'responseBodyPreview' => $preview ]);
        throw new Exception('Failed to decode JSON response');
    }
    return $data;
}

function iso_duration_to_seconds($iso) {
    $pattern = '/PT(?:(\d+)H)?(?:(\d+)M)?(?:(\d+)S)?/';
    if (preg_match($pattern, $iso, $matches)) {
        $hours = isset($matches[1]) ? (int)$matches[1] : 0;
        $minutes = isset($matches[2]) ? (int)$matches[2] : 0;
        $seconds = isset($matches[3]) ? (int)$matches[3] : 0;
        return $hours * 3600 + $minutes * 60 + $seconds;
    }
    return 0;
}

function load_cache($file) {
    if (!file_exists($file)) {
        return [
            'lastUpdatedEpoch' => 0,
            'videos' => []
        ];
    }
    $raw = file_get_contents($file);
    $data = json_decode($raw, true);
    if (!is_array($data)) {
        return [ 'lastUpdatedEpoch' => 0, 'videos' => [] ];
    }
    if (!isset($data['videos']) || !is_array($data['videos'])) {
        $data['videos'] = [];
    }
    if (!isset($data['lastUpdatedEpoch'])) {
        $data['lastUpdatedEpoch'] = 0;
    }
    return $data;
}

function save_cache($file, $data) {
    file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
}

function save_search_cache($file, $searchResponse) {
    $payload = is_array($searchResponse) ? $searchResponse : [];
    file_put_contents($file, json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
}

function compute_fetch_limit_smart($lastUpdatedEpoch, $isInitial, $initialLimit, $perDayMultiplier, $maxLimit, $apiKey, $channelId, $knownIdsSet) {
    if ($isInitial) return $initialLimit;

    $now = time();
    $ageSeconds = $now - (int)$lastUpdatedEpoch;
    $ageDays = floor($ageSeconds / (24 * 3600));

    if ($ageDays > 0) {
        // More than 24h old: use normal days × multiplier logic
        $dynamicLimit = $ageDays * $perDayMultiplier;
        return min($dynamicLimit, $maxLimit);
    }

    // Less than 24h old: do quick check for new videos
    try {
        logger_log('video', 'INFO', 'Performing quick check for new videos within 24h', [
            'ageSeconds' => $ageSeconds,
            'ageHours' => round($ageSeconds / 3600, 1)
        ]);

        // Fetch just 1 newest video to check if it's new
        $url = 'https://www.googleapis.com/youtube/v3/search'
             . '?key=' . urlencode($apiKey)
             . '&channelId=' . urlencode($channelId)
             . '&part=snippet,id'
             . '&order=date'
             . '&maxResults=1';
        
        $data = http_get_json($url);
        
        if (isset($data['items']) && !empty($data['items'])) {
            $newestItem = $data['items'][0];
            if (isset($newestItem['id']['kind']) && $newestItem['id']['kind'] === 'youtube#video') {
                $newestVideoId = $newestItem['id']['videoId'] ?? null;
                
                if ($newestVideoId && !isset($knownIdsSet[$newestVideoId])) {
                    logger_log('video', 'INFO', 'Found new video within 24h', [
                        'newVideoId' => $newestVideoId,
                        'ageHours' => round($ageSeconds / 3600, 1)
                    ]);
                    return 1; // Fetch 1 new video
                } else {
                    logger_log('video', 'INFO', 'No new videos found within 24h', [
                        'newestVideoId' => $newestVideoId,
                        'isKnown' => isset($knownIdsSet[$newestVideoId]),
                        'ageHours' => round($ageSeconds / 3600, 1)
                    ]);
                    return 0; // No new videos
                }
            }
        }
        
        logger_log('video', 'INFO', 'Quick check completed, no new videos found', [
            'ageHours' => round($ageSeconds / 3600, 1)
        ]);
        return 0;
        
    } catch (Exception $ex) {
        // If quick check fails, log but don't break the app
        logger_exception('video', $ex, 'WARNING', [
            'context' => 'quick_check_within_24h',
            'ageHours' => round($ageSeconds / 3600, 1)
        ]);
        return 0; // Fail safe: don't fetch on API error
    }
}

// Fetch latest video IDs (by date) up to $limit, skipping already-known IDs
// Returns associative array:
//  - 'ids': [{id:string, snippet:array|null}, ...]
//  - 'rawItems': [searchResultItem, ...]
//  - 'searchMeta': { kind, etag, nextPageToken, regionCode, pageInfo }
function fetch_latest_video_ids($apiKey, $channelId, $limit, $knownIdsSet) {
    $collected = [];
    $rawItems = [];
    $meta = null;
    if ($limit <= 0) return [ 'ids' => $collected, 'rawItems' => $rawItems, 'searchMeta' => $meta ];

    $pageToken = '';
    $perPage = 50; // max allowed by API
    while (count($collected) < $limit) {
        $remaining = $limit - count($collected);
        $maxResults = $remaining < $perPage ? $remaining : $perPage;
        $url = 'https://www.googleapis.com/youtube/v3/search'
             . '?key=' . urlencode($apiKey)
             . '&channelId=' . urlencode($channelId)
             . '&part=snippet,id'
             . '&order=date'
             . '&maxResults=' . $maxResults
             . ($pageToken ? '&pageToken=' . urlencode($pageToken) : '');
        $data = http_get_json($url);
        // Capture search response metadata (from the first page; update nextPageToken as we go)
        if ($meta === null) {
            $meta = [
                'kind' => 'youtube#searchListResponse',
                'etag' => $data['etag'] ?? null,
                'nextPageToken' => $data['nextPageToken'] ?? null,
                'regionCode' => $data['regionCode'] ?? null,
                'pageInfo' => $data['pageInfo'] ?? null,
            ];
        } else {
            // Always keep the last seen nextPageToken from the most recent page
            $meta['nextPageToken'] = $data['nextPageToken'] ?? null;
        }
        if (!isset($data['items']) || empty($data['items'])) {
            break;
        }
        foreach ($data['items'] as $item) {
            if (isset($item['id']['kind']) && $item['id']['kind'] === 'youtube#video') {
                $vid = $item['id']['videoId'] ?? null;
                if ($vid && !isset($knownIdsSet[$vid])) {
                    $collected[] = [
                        'id' => $vid,
                        'snippet' => $item['snippet'] ?? null // keep minimal snippet to avoid second snippet fetch if needed
                    ];
                    // Mark as known to avoid duplicates within pagination loop
                    $knownIdsSet[$vid] = true;
                }
                // Save raw search item regardless so we can cache the proper YouTube shape
                $rawItems[] = $item;
            }
        }
        if (!isset($data['nextPageToken'])) {
            break;
        }
        $pageToken = $data['nextPageToken'];
    }
    return [ 'ids' => $collected, 'rawItems' => $rawItems, 'searchMeta' => $meta ];
}

// Fetch details for a list of video IDs (chunks of 50)
function fetch_videos_details($apiKey, $videoIds) {
    $details = [];
    $chunkSize = 50;
    for ($i = 0; $i < count($videoIds); $i += $chunkSize) {
        $chunk = array_slice($videoIds, $i, $chunkSize);
        $url = 'https://www.googleapis.com/youtube/v3/videos'
             . '?key=' . urlencode($apiKey)
             . '&id=' . urlencode(implode(',', $chunk))
             . '&part=snippet,contentDetails';
        $data = http_get_json($url);
        if (isset($data['items'])) {
            foreach ($data['items'] as $item) {
                $details[] = $item;
            }
        }
    }
    return $details;
}

// Fetch a single page of search items (raw YouTube search shape)
function fetch_search_page_items($apiKey, $channelId, $maxResults = 50) {
    if ($maxResults <= 0) $maxResults = 50;
    if ($maxResults > 50) $maxResults = 50;
    $url = 'https://www.googleapis.com/youtube/v3/search'
        . '?key=' . urlencode($apiKey)
        . '&channelId=' . urlencode($channelId)
        . '&part=snippet,id'
        . '&order=date'
        . '&maxResults=' . $maxResults;
    $data = http_get_json($url);
    return isset($data['items']) && is_array($data['items']) ? $data['items'] : [];
}

// Add convenient top-level fields to search items for client consumption
function enrich_search_items(array $items): array {
    $out = [];
    foreach ($items as $item) {
        $snippet = isset($item['snippet']) && is_array($item['snippet']) ? $item['snippet'] : [];
        $enriched = $item;
        if (!isset($enriched['channelId']) && isset($snippet['channelId'])) {
            $enriched['channelId'] = $snippet['channelId'];
        }
        if (!isset($enriched['publishTime']) && isset($snippet['publishTime'])) {
            $enriched['publishTime'] = $snippet['publishTime'];
        }
        if (!isset($enriched['publishTime']) && isset($snippet['publishedAt'])) {
            $enriched['publishTime'] = $snippet['publishedAt'];
        }
        if (!isset($enriched['liveBroadcastContent']) && isset($snippet['liveBroadcastContent'])) {
            $enriched['liveBroadcastContent'] = $snippet['liveBroadcastContent'];
        }
        if (!isset($enriched['thumbnails']) && isset($snippet['thumbnails'])) {
            $enriched['thumbnails'] = $snippet['thumbnails'];
        }
        $out[] = $enriched;
    }
    return $out;
}

// Normalize video record for caching and UI consumption
function to_cached_video_record($item) {
    $id = $item['id'] ?? '';
    $snippet = $item['snippet'] ?? [];
    $contentDetails = $item['contentDetails'] ?? [];
    $durationIso = $contentDetails['duration'] ?? 'PT0S';
    $durationSeconds = iso_duration_to_seconds($durationIso);

    $thumbs = $snippet['thumbnails'] ?? [];
    $thumb = $thumbs['high']['url'] ?? ($thumbs['medium']['url'] ?? ($thumbs['default']['url'] ?? ''));

    return [
        'id' => $id,
        'title' => $snippet['title'] ?? '',
        'description' => $snippet['description'] ?? '',
        'publishedAt' => $snippet['publishedAt'] ?? '',
        'thumbnailUrl' => $thumb,
        'durationIso' => $durationIso,
        'durationSeconds' => $durationSeconds
    ];
}

try {
    logger_log('video', 'INFO', 'videos.php request start', [
        'method' => $_SERVER['REQUEST_METHOD'] ?? 'CLI',
        'ip' => $_SERVER['REMOTE_ADDR'] ?? null,
        'ua' => $_SERVER['HTTP_USER_AGENT'] ?? null
    ]);
    // Load cache
    $cache = load_cache($CACHE_FILE);
    $videos = $cache['videos'];
    $lastUpdatedEpoch = (int)$cache['lastUpdatedEpoch'];

    // Build known ID set for fast checks
    $knownIdsSet = [];
    foreach ($videos as $v) {
        if (isset($v['id'])) {
            $knownIdsSet[$v['id']] = true;
        }
    }

    $isInitial = (count($videos) === 0);
    $fetchLimit = compute_fetch_limit_smart($lastUpdatedEpoch, $isInitial, $INITIAL_FETCH_LIMIT, $PER_DAY_LIMIT_MULTIPLIER, $MAX_FETCH_LIMIT, $YOUTUBE_API_KEY, $CHANNEL_ID, $knownIdsSet);
    logger_log('video', 'INFO', 'Computed fetch limit', [
        'isInitial' => $isInitial,
        'lastUpdatedEpoch' => $lastUpdatedEpoch,
        'ageDays' => $isInitial ? 0 : floor((time() - $lastUpdatedEpoch) / (24 * 3600)),
        'fetchLimit' => $fetchLimit,
        'perDayMultiplier' => $PER_DAY_LIMIT_MULTIPLIER
    ]);

    if ($fetchLimit > 0) {
        try {
            // Step 1: collect new/latest video IDs (skipping known)
            logger_log('video', 'INFO', 'Fetching latest video IDs', [ 'limit' => $fetchLimit ]);
            $latestResult = fetch_latest_video_ids($YOUTUBE_API_KEY, $CHANNEL_ID, $fetchLimit, $knownIdsSet);
            $latest = $latestResult['ids'];
            $rawSearchItems = enrich_search_items($latestResult['rawItems']);
            $searchMeta = $latestResult['searchMeta'] ?? null;
            $ids = array_map(function($x) { return $x['id']; }, $latest);
            logger_log('video', 'INFO', 'Collected latest video IDs', [ 'count' => count($ids), 'rawSearchItems' => count($rawSearchItems) ]);

            if (!empty($ids)) {
                // Step 2: fetch details for collected IDs
                logger_log('video', 'INFO', 'Fetching details for video IDs', [ 'count' => count($ids) ]);
                $details = fetch_videos_details($YOUTUBE_API_KEY, $ids);
                // Step 3: normalize and merge
                $newRecords = [];
                $searchMap = [];
                foreach ($rawSearchItems as $si) {
                    $vId = $si['id']['videoId'] ?? null;
                    if ($vId) {
                        $searchMap[$vId] = $si;
                    }
                }
                foreach ($details as $item) {
                    $record = to_cached_video_record($item);
                    $merge = $searchMap[$record['id']] ?? null;
                    if (is_array($merge)) {
                        // Merge selected YouTube search fields into the compact record
                        if (isset($merge['kind'])) $record['kind'] = $merge['kind'];
                        if (isset($merge['etag'])) $record['etag'] = $merge['etag'];
                        if (isset($merge['id'])) $record['idObject'] = $merge['id']; // keep original search id object without colliding with string id
                        if (isset($merge['channelId'])) $record['channelId'] = $merge['channelId'];
                        if (isset($merge['publishTime'])) $record['publishTime'] = $merge['publishTime'];
                        if (isset($merge['liveBroadcastContent'])) $record['liveBroadcastContent'] = $merge['liveBroadcastContent'];
                        if (isset($merge['thumbnails'])) $record['thumbnails'] = $merge['thumbnails'];
                        if (isset($merge['channelTitle'])) $record['channelTitle'] = $merge['channelTitle'];
                    }
                    $newRecords[] = $record;
                }
                logger_log('video', 'INFO', 'Fetched details and normalized records', [ 'newRecords' => count($newRecords) ]);
                // Merge, keeping unique by id
                $idToVideo = [];
                foreach ($videos as $v) { $idToVideo[$v['id']] = $v; }
                foreach ($newRecords as $nr) { $idToVideo[$nr['id']] = $nr; }
                $videos = array_values($idToVideo);

                // Sort by publishedAt desc
                usort($videos, function($a, $b) {
                    return strcmp($b['publishedAt'], $a['publishedAt']);
                });

                // Update cache: merged records only
                $cache['videos'] = $videos;
                $cache['lastUpdatedEpoch'] = time();
                // Persist search metadata just below lastUpdatedEpoch
                if (is_array($searchMeta)) {
                    $cache['kind'] = $searchMeta['kind'] ?? 'youtube#searchListResponse';
                    if (isset($searchMeta['etag'])) $cache['etag'] = $searchMeta['etag'];
                    if (array_key_exists('nextPageToken', $searchMeta)) $cache['nextPageToken'] = $searchMeta['nextPageToken'];
                    if (isset($searchMeta['regionCode'])) $cache['regionCode'] = $searchMeta['regionCode'];
                    if (isset($searchMeta['pageInfo'])) $cache['pageInfo'] = $searchMeta['pageInfo'];
                }
                // Ensure any old fields are removed
                unset($cache['lastSearchItems'], $cache['searchResponse']);
                save_cache($CACHE_FILE, $cache);
                logger_log('video', 'INFO', 'Cache updated', [ 'totalVideos' => count($videos) ]);
            } else {
                // No new IDs found; still touch lastUpdatedEpoch lightly to avoid hammering
                if ($isInitial) {
                    // Ensure at least write an empty structure once
                    $cache['videos'] = $videos;
                    $cache['lastUpdatedEpoch'] = time();
                    save_cache($CACHE_FILE, $cache);
                }
                logger_log('video', 'INFO', 'No new video IDs found');
            }
        } catch (Exception $updateEx) {
            // Log but continue to serve the existing cache with HTTP 200
            logger_exception('video', $updateEx);
        }
    }

    // Serve current cache, and also include a search-like shape for clients expecting raw YouTube search items
    $served = load_cache($CACHE_FILE);
    // Default: serve merged videos only
    logger_log('video', 'INFO', 'Serving cache', [ 'totalVideos' => is_array($served['videos'] ?? null) ? count($served['videos']) : 0 ]);
    echo json_encode([ 'lastUpdatedEpoch' => $served['lastUpdatedEpoch'] ?? 0, 'videos' => $served['videos'] ?? [] ], JSON_UNESCAPED_SLASHES);
} catch (Exception $ex) {
    http_response_code(500);
    logger_exception('video', $ex);
    echo json_encode([ 'error' => true, 'message' => $ex->getMessage() ]);
} 