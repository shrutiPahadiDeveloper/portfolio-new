<?php
// Simple file-based logger with daily rotation and categories.
// Usage: logger_log('video', 'INFO', 'Starting fetch', ['limit' => 50]);

if (!function_exists('logger_log')) {
    function logger_log(string $category, string $level, string $message, array $context = []): void {
        try {
            $baseDir = __DIR__ . '/logs';

            if (!is_dir($baseDir)) {
                @mkdir($baseDir, 0755, true);
            }

            $safeCategory = preg_replace('/[^a-zA-Z0-9_-]/', '_', strtolower($category));
            $date = date('Y-m-d');
            $filePath = $baseDir . '/' . $date . '-' . $safeCategory . '.log';

            $timestamp = date('Y-m-d H:i:s');
            $upperLevel = strtoupper($level);
            $redactedContext = logger__redact_context($context);
            $contextJson = empty($redactedContext) ? '' : (' ' . json_encode($redactedContext, JSON_UNESCAPED_SLASHES));

            $line = "[$timestamp] [$upperLevel] [$safeCategory] $message$contextJson\n";

            @file_put_contents($filePath, $line, FILE_APPEND | LOCK_EX);
        } catch (Throwable $t) {
            // Swallow logging errors; never break the app due to logging
        }
    }
}

if (!function_exists('logger_exception')) {
    function logger_exception(string $category, Throwable $ex, string $level = 'ERROR', array $context = []): void {
        $ctx = $context;
        $ctx['exception'] = [
            'type' => get_class($ex),
            'message' => $ex->getMessage(),
            'code' => $ex->getCode(),
            'file' => $ex->getFile(),
            'line' => $ex->getLine(),
        ];
        logger_log($category, $level, 'Unhandled exception', $ctx);
    }
}

if (!function_exists('logger__redact_context')) {
    function logger__redact_context(array $context): array {
        $redactKeys = ['apikey', 'api_key', 'authorization', 'password', 'secret', 'token', 'g-recaptcha-response'];
        $out = [];
        foreach ($context as $key => $val) {
            $lower = strtolower((string)$key);
            if (in_array($lower, $redactKeys, true)) {
                $out[$key] = '***REDACTED***';
            } else if (is_array($val)) {
                $out[$key] = logger__redact_context($val);
            } else if (is_object($val)) {
                $out[$key] = '[object]';
            } else {
                $out[$key] = $val;
            }
        }
        return $out;
    }
}

