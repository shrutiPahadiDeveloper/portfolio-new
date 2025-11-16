<?php
require_once __DIR__ . '/logger.php';
$accept = strtolower($_SERVER['HTTP_ACCEPT'] ?? '');
$serve_json = isset($_GET['api']) || strpos($accept, 'application/json') !== false;
if (!$serve_json) {
    header('Content-Type: text/html; charset=utf-8');
    logger_log('video', 'INFO', 'Videos page view', [
        'ip' => $_SERVER['REMOTE_ADDR'] ?? null,
        'ua' => $_SERVER['HTTP_USER_AGENT'] ?? null
    ]);
    $layout = isset($_GET['layout']) ? strtolower($_GET['layout']) : 'modern';
    $siteName = 'Shruti Sharma';
    $pageTitle = 'Videos';
    $pageIntro = 'Discover my latest content and YouTube Shorts';
    if ($layout === 'legacy') {
      ?>
      <!DOCTYPE html>
      <html lang="en">
      <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="icon" href="logos/favicon.ico" type="image/x-icon">
        <title><?php echo htmlspecialchars($pageTitle); ?> - <?php echo htmlspecialchars($siteName); ?> Portfolio</title>
        <link rel="stylesheet" href="font.css">
        <link rel="stylesheet" href="style.css">
        <link rel="stylesheet" href="videos-responsive.css">
        <meta property="og:title" content="Videos - <?php echo htmlspecialchars($siteName); ?> Portfolio">
        <meta property="og:image" content="shruti.jpg">
        <meta property="og:description" content="Latest videos from <?php echo htmlspecialchars($siteName); ?> - Content Creator | Himachali Soul">
        <meta property="og:url" content="https://shrutipahadi.com/videos.php">
      </head>
      <body>
        <div class="page-loader"><div></div><div></div><div></div></div>
        <div class="overlay"></div>
        <div class="main">
          <header class="header">
            <div class="container">
              <div class="row flex-end">
                <button type="button" class="nav-toggler"><span></span></button>
                <nav class="nav">
                  <div class="nav-inner">
                    <ul>
                      <li><a href="index.php" class="nav-item">Home</a></li>
                      <li><a href="about.php" class="nav-item">About</a></li>
                      <li><a href="portfolio.php" class="nav-item">Portfolio</a></li>
                      <li><a href="videos.php?layout=legacy" class="nav-item active">Videos</a></li>
                      <li><a href="contact.php" class="nav-item">Contact</a></li>
                    </ul>
                  </div>
                </nav>
              </div>
            </div>
          </header>
          <section class="videos-section align-items-center active" id="videos">
            <div class="container">
              <div class="row">
                <div class="section-title">
                  <h2>Latest Videos</h2>
                  <p><?php echo htmlspecialchars($pageIntro); ?></p>
                </div>
                <div id="loadingState" class="loading-container">
                  <div class="loading-spinner"></div>
                  <p>Loading videos...</p>
                </div>
                <div id="errorState" class="error-container hidden">
                  <div class="error-icon">⚠️</div>
                  <h3>Oops! Something went wrong</h3>
                  <p>Failed to load videos. Please try refreshing the page.</p>
                  <button class="btn" onclick="location.reload()">Try Again</button>
                </div>
                <div id="videosContent" class="videos-content hidden">
                  <div class="video-tabs">
                    <div class="tab-container">
                      <button class="tab-button active" data-tab="longs"><span class="tab-icon">📺</span><span class="tab-text">Videos</span><span class="tab-count" id="longsCount">0</span></button>
                      <button class="tab-button" data-tab="shorts"><span class="tab-icon">📱</span><span class="tab-text">Shorts</span><span class="tab-count" id="shortsCount">0</span></button>
                      <div class="tab-indicator"></div>
                    </div>
                  </div>
                  <div class="tab-content">
                    <div class="tab-panel active" id="longsPanel"><div class="video-gallery" id="longsGallery"></div></div>
                    <div class="tab-panel" id="shortsPanel"><div class="shorts-gallery" id="shortsGallery"></div></div>
                  </div>
                </div>
              </div>
            </div>
          </section>
        </div>
        <div class="video-modal-overlay" id="videoModal" role="dialog" aria-modal="true">
          <div class="video-modal-content">
            <button class="video-modal-close" id="modalClose" aria-label="Close video"><span>✕</span></button>
            <div class="video-modal-iframe-container"><iframe id="modalIframe" class="video-modal-iframe" src="" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe></div>
          </div>
        </div>
        <style>
          .videos-section{padding:80px 0}
          .loading-container{text-align:center;padding:60px 20px}
          .loading-spinner{width:50px;height:50px;border:3px solid var(--white-alpha-25);border-top:3px solid var(--main-color);border-radius:50%;animation:spin 1s linear infinite;margin:0 auto 20px}
          @keyframes spin{0%{transform:rotate(0)}100%{transform:rotate(360deg)}}
          .error-container{text-align:center;padding:60px 20px}
          .error-icon{font-size:48px;margin-bottom:20px}
          .error-container h3{color:var(--main-color);margin-bottom:10px}
          .video-tabs{margin-bottom:40px}
          .tab-container{position:relative;display:flex;background:var(--white-alpha-25);border:1px solid var(--white-alpha-40);border-radius:50px;padding:6px;max-width:400px;margin:0 auto;backdrop-filter:var(--backdrop-filter-blur)}
          .tab-button{position:relative;flex:1;background:none;border:none;padding:12px 20px;border-radius:40px;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:8px;font-family:'Poppins',sans-serif;font-size:14px;font-weight:500;color:var(--blue-dark);transition:all .3s cubic-bezier(.4,0,.2,1);z-index:2}
          .tab-button:hover{color:var(--main-color);transform:translateY(-1px)}
          .tab-button.active{color:var(--white);transform:translateY(-2px)}
          .tab-icon{font-size:18px;transition:transform .3s ease}
          .tab-button:hover .tab-icon{transform:scale(1.1)}
          .tab-button.active .tab-icon{transform:scale(1.2)}
          .tab-text{font-weight:600;padding-top:5px;line-height:0}
          .tab-count{background:rgba(255,255,255,.2);color:inherit;padding:2px 8px;border-radius:12px;margin-top:5px;font-size:12px;font-weight:600;min-width:20px;text-align:center;transition:all .3s ease}
          .tab-button.active .tab-count{background:rgba(255,255,255,.3)}
          .tab-indicator{position:absolute;top:6px;left:6px;width:calc(50% - 6px);height:calc(100% - 12px);background:linear-gradient(135deg,var(--main-color),#ff6b9d);border-radius:40px;transition:transform .4s cubic-bezier(.4,0,.2,1);z-index:1;box-shadow:0 4px 15px rgba(224,47,107,.3)}
          .tab-indicator.shorts{transform:translateX(100%)}
          .tab-content{position:relative;min-height:400px}
          .tab-panel{display:none;opacity:0;transform:translateY(20px);transition:all .4s cubic-bezier(.4,0,.2,1)}
          .tab-panel.active{display:block;opacity:1;transform:translateY(0)}
          .tab-panel.fade-out{opacity:0;transform:translateY(-20px)}
          .video-gallery{display:grid;grid-template-columns:repeat(auto-fill,minmax(320px,1fr));gap:25px;margin:0 auto;max-width:1200px}
          .video-item{background:var(--white-alpha-25);border:1px solid var(--white-alpha-40);border-radius:20px;overflow:hidden;backdrop-filter:var(--backdrop-filter-blur);transition:all .3s ease;cursor:pointer}
          .video-item:hover{transform:translateY(-8px);box-shadow:0 15px 35px rgba(0,0,0,.1);border-color:var(--main-color)}
          .thumbnail-container{position:relative;overflow:hidden}
          .video-thumbnail{width:100%;height:200px;object-fit:cover;display:block;transition:transform .3s ease}
          .video-item:hover .video-thumbnail{transform:scale(1.05)}
          .play-button{position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);width:70px;height:70px;background:rgba(255,0,0,.9);border-radius:50%;display:flex;align-items:center;justify-content:center;opacity:.9;transition:all .3s ease;box-shadow:0 4px 15px rgba(0,0,0,.3)}
          .play-button::after{content:'';width:0;height:0;border-left:18px solid var(--white);border-top:12px solid transparent;border-bottom:12px solid transparent;margin-left:6px}
          .video-item:hover .play-button{opacity:1;transform:translate(-50%,-50%) scale(1.1)}
          .video-duration{position:absolute;bottom:12px;right:12px;background:rgba(0,0,0,.8);color:var(--white);padding:4px 8px;border-radius:6px;font-size:12px;font-weight:500}
          .video-info{padding:20px}
          .video-title{font-size:16px;font-weight:600;color:var(--blue-dark);margin-bottom:10px;line-height:1.4;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden}
          .video-date{font-size:14px;color:var(--main-color);font-weight:500}
          .shorts-gallery{display:grid;grid-template-columns:repeat(auto-fill,minmax(180px,1fr));gap:20px;max-width:1200px;margin:0 auto}
          .short-item{background:var(--white-alpha-25);border:1px solid var(--white-alpha-40);border-radius:20px;overflow:hidden;backdrop-filter:var(--backdrop-filter-blur);transition:all .3s ease;cursor:pointer;aspect-ratio:9/16;position:relative}
          .short-item:hover{transform:translateY(-8px);box-shadow:0 15px 35px rgba(0,0,0,.1);border-color:var(--main-color)}
          .short-thumbnail-container{position:relative;width:100%;height:100%}
          .short-thumbnail{width:100%;height:100%;object-fit:cover;display:block;transition:transform .3s ease}
          .short-item:hover .short-thumbnail{transform:scale(1.05)}
          .short-play-button{position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);width:60px;height:60px;background:rgba(255,0,0,.9);border-radius:50%;display:flex;align-items:center;justify-content:center;opacity:.9;transition:all .3s ease;box-shadow:0 4px 15px rgba(0,0,0,.3)}
          .short-play-button::after{content:'';width:0;height:0;border-left:15px solid var(--white);border-top:10px solid transparent;border-bottom:10px solid transparent;margin-left:5px}
          .short-item:hover .short-play-button{opacity:1;transform:translate(-50%,-50%) scale(1.1)}
          .shorts-badge{position:absolute;top:12px;left:12px;background:rgba(255,0,0,.9);color:var(--white);padding:6px 12px;border-radius:15px;font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:.5px}
          .short-info{position:absolute;bottom:0;left:0;right:0;background:linear-gradient(transparent,rgba(0,0,0,.8));padding:30px 20px 20px}
          .short-title{font-size:14px;font-weight:600;color:var(--white);margin-bottom:8px;line-height:1.3;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden}
          .short-date{font-size:12px;color:rgba(255,255,255,.8);font-weight:500}
          .video-modal-overlay{position:fixed;inset:0;background:rgba(0,0,0,.9);display:none;align-items:center;justify-content:center;z-index:1000;backdrop-filter:blur(5px)}
          .video-modal-overlay.open{display:flex;animation:fadeIn .3s ease-in-out}
          @keyframes fadeIn{0%{opacity:0}100%{opacity:1}}
          .video-modal-content{position:relative;width:min(95vw,1000px);aspect-ratio:16/9;background:var(--white);border-radius:20px;overflow:hidden;box-shadow:0 20px 60px rgba(0,0,0,.5)}
          .video-modal-close{position:absolute;top:15px;right:15px;background:rgba(0,0,0,.7);color:var(--white);border:none;width:40px;height:40px;border-radius:50%;cursor:pointer;z-index:2;display:flex;align-items:center;justify-content:center;font-size:18px;transition:all .3s ease}
          .video-modal-close:hover{background:rgba(0,0,0,.9);transform:scale(1.1)}
          .video-modal-iframe-container{width:100%;height:100%;background:#000}
          .video-modal-iframe{width:100%;height:100%;border:0}
          .hidden{display:none !important}
        </style>
        <script src="script.js"></script>
        <script>
        class VideosManager {
          constructor(){this.loadingState=document.getElementById('loadingState');this.errorState=document.getElementById('errorState');this.videosContent=document.getElementById('videosContent');this.longsGallery=document.getElementById('longsGallery');this.shortsGallery=document.getElementById('shortsGallery');this.modal=document.getElementById('videoModal');this.modalIframe=document.getElementById('modalIframe');this.qvid=new URLSearchParams(window.location.search).get('qvid');this.init();}
          async init(){try{this.showLoading();const cache=await this.loadCachedVideos();this.renderVideos(cache);this.hideLoading();this.showContent();}catch(error){console.error('Failed to load videos:',error);this.hideLoading();this.showError();}}
          async loadCachedVideos(){const response=await fetch('videos.php?api=1',{cache:'no-cache',headers:{Accept:'application/json'}});if(!response.ok){throw new Error(`HTTP ${response.status}: ${response.statusText}`);}return await response.json();}
          formatDuration(seconds){if(!seconds)return'0:00';const hours=Math.floor(seconds/3600);const minutes=Math.floor((seconds%3600)/60);const secs=Math.floor(seconds%60);if(hours>0){return `${hours}:${String(minutes).padStart(2,'0')}:${String(secs).padStart(2,'0')}`;}return `${minutes}:${String(secs).padStart(2,'0')}`;}
          formatDate(dateString){const date=new Date(dateString);return date.toLocaleDateString('en-US',{year:'numeric',month:'short',day:'numeric'});}
          openModal(videoId,isShort=false){const embedUrl=`https://www.youtube.com/embed/${videoId}?autoplay=1&rel=0`;this.modalIframe.src=embedUrl;this.modal.classList.add('open');document.body.style.overflow='hidden';this.modal.setAttribute('aria-hidden','false');document.querySelector('.video-modal-close').focus();}
          closeModal(){this.modalIframe.src='';this.modal.classList.remove('open');document.body.style.overflow='';this.modal.setAttribute('aria-hidden','true');}
          renderVideos(cache){let videos=Array.isArray(cache.videos)?cache.videos:[];if(this.qvid){const match=videos.find(v=>v.id===this.qvid);if(match){videos=[match];}}
            const shorts=[];const longs=[];videos.forEach(video=>{if((video.durationSeconds||0)<=160){shorts.push(video);}else{longs.push(video);}});this.updateTabCounts(longs,shorts);this.renderLongVideos(longs);this.renderShorts(shorts);if(longs.length>0){this.switchTab('longs');}else if(shorts.length>0){this.switchTab('shorts');}}
          renderLongVideos(videos){this.longsGallery.innerHTML='';videos.forEach(video=>{const videoElement=this.createLongVideoElement(video);this.longsGallery.appendChild(videoElement);});}
          renderShorts(videos){this.shortsGallery.innerHTML='';videos.forEach(video=>{const shortElement=this.createShortElement(video);this.shortsGallery.appendChild(shortElement);});}
          createLongVideoElement(video){const element=document.createElement('div');element.className='video-item';element.setAttribute('data-video-id',video.id);element.innerHTML=`<div class="thumbnail-container"><img src="${video.thumbnailUrl}" alt="${video.title}" class="video-thumbnail" loading="lazy" /><div class="play-button" aria-label="Play video"></div><div class="video-duration">${this.formatDuration(video.durationSeconds)}</div></div><div class="video-info"><div class="video-title">${video.title}</div><div class="video-date">Published: ${this.formatDate(video.publishedAt)}</div></div>`;element.addEventListener('click',()=>this.openModal(video.id,false));element.addEventListener('keydown',(e)=>{if(e.key==='Enter'||e.key===' '){e.preventDefault();this.openModal(video.id,false);}});return element;}
          createShortElement(video){const element=document.createElement('div');element.className='short-item';element.setAttribute('data-video-id',video.id);element.innerHTML=`<div class="short-thumbnail-container"><img src="${video.thumbnailUrl}" alt="${video.title}" class="short-thumbnail" loading="lazy" /><div class="short-play-button" aria-label="Play short"></div><div class="shorts-badge">Shorts</div><div class="video-duration">${this.formatDuration(video.durationSeconds)}</div><div class="short-info"><div class="short-title">${video.title.replace(/#shorts/ig,'')}</div><div class="short-date">${this.formatDate(video.publishedAt)}</div></div></div>`;element.addEventListener('click',()=>this.openModal(video.id,true));element.addEventListener('keydown',(e)=>{if(e.key==='Enter'||e.key===' '){e.preventDefault();this.openModal(video.id,true);}});return element;}
          updateTabCounts(longs,shorts){document.getElementById('longsCount').textContent=longs.length;document.getElementById('shortsCount').textContent=shorts.length;}
          switchTab(tabName){const tabs=document.querySelectorAll('.tab-button');const panels=document.querySelectorAll('.tab-panel');const indicator=document.querySelector('.tab-indicator');tabs.forEach(tab=>tab.classList.remove('active'));panels.forEach(panel=>{panel.classList.remove('active');panel.classList.add('fade-out');});const activeTab=document.querySelector(`[data-tab="${tabName}"]`);const activePanel=document.getElementById(`${tabName}Panel`);if(activeTab&&activePanel){activeTab.classList.add('active');if(tabName==='shorts'){indicator.classList.add('shorts');}else{indicator.classList.remove('shorts');}setTimeout(()=>{activePanel.classList.remove('fade-out');activePanel.classList.add('active');},150);}}
          showLoading(){this.loadingState.classList.remove('hidden');this.errorState.classList.add('hidden');this.videosContent.classList.add('hidden');}
          hideLoading(){this.loadingState.classList.add('hidden');}
          showError(){this.errorState.classList.remove('hidden');}
          showContent(){this.videosContent.classList.remove('hidden');}
        }
        document.addEventListener('DOMContentLoaded',()=>{const videosManager=new VideosManager();document.querySelectorAll('.tab-button').forEach(button=>{button.addEventListener('click',()=>{const tabName=button.getAttribute('data-tab');videosManager.switchTab(tabName);});});document.getElementById('modalClose').addEventListener('click',()=>{videosManager.closeModal();});document.getElementById('videoModal').addEventListener('click',(e)=>{if(e.target.id==='videoModal'){videosManager.closeModal();}});document.addEventListener('keydown',(e)=>{if(e.key==='Escape'&&videosManager.modal.classList.contains('open')){videosManager.closeModal();}});});
        </script>
      </body>
      </html>
      <?php
      exit;
    }
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
      <meta charset="UTF-8" />
      <meta name="viewport" content="width=device-width, initial-scale=1" />
      <meta http-equiv="X-UA-Compatible" content="IE=edge" />
      <link rel="icon" href="logos/favicon.ico" type="image/x-icon" />
      <title>Videos - Shruti Sharma Portfolio</title>
      <link rel="preconnect" href="https://fonts.googleapis.com" />
      <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
      <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet" />
      <style>
        :root{--bg:#0b0c10;--bg-soft:#0e1016;--panel:#10131a;--text:#e8ecf1;--muted:#a6b0c0;--primary:#7c3aed;--primary-strong:#6d28d9;--accent:#22d3ee;--container:1200px}
        html.light{--bg:#f7f8fb;--bg-soft:#fff;--panel:#fff;--text:#0f172a;--muted:#53607a}
        *{box-sizing:border-box}
        body{margin:0;font-family:'Inter',system-ui,-apple-system,Segoe UI,Roboto,Helvetica,Arial;color:var(--text);background:
          radial-gradient(1200px 600px at 85% -10%, rgba(124,58,237,0.15), transparent 60%),
          radial-gradient(900px 500px at -10% 20%, rgba(34,211,238,0.12), transparent 60%),
          linear-gradient(180deg, var(--bg), var(--bg-soft));-webkit-font-smoothing:antialiased;-moz-osx-font-smoothing:grayscale;overflow-x:hidden}
        a{color:inherit;text-decoration:none}img{max-width:100%;display:block}
        .container{max-width:var(--container);margin:0 auto;padding:0 clamp(18px,6vw,28px)}
        .page-loader{position:fixed;inset:0;display:grid;place-items:center;background:var(--bg);z-index:100;transition:opacity .4s ease,visibility .4s ease}
        .page-loader.hidden{opacity:0;visibility:hidden}
        .dots{display:inline-flex;gap:10px}
        .dots span{width:10px;height:10px;border-radius:999px;background:var(--text);opacity:.4;animation:bounce 1s infinite ease-in-out}
        .dots span:nth-child(2){animation-delay:.15s}
        .dots span:nth-child(3){animation-delay:.3s}
        @keyframes bounce{0%,80%,100%{transform:scale(0);opacity:.3}40%{transform:scale(1);opacity:1}}
        header{position:sticky;top:0;z-index:50;backdrop-filter:saturate(180%) blur(10px);background:rgba(16,19,26,.6);border-bottom:1px solid rgba(255,255,255,.06)}
        html.light header{background:rgba(255,255,255,.7);border-bottom:1px solid rgba(2,6,23,.08)}
        .nav{height:68px;display:flex;align-items:center;justify-content:space-between}
        .brand{display:inline-flex;align-items:center;gap:10px;font-weight:800}
        .logo{width:38px;height:38px;border-radius:12px;background:linear-gradient(135deg,var(--primary),var(--accent));box-shadow:inset 0 0 0 1px rgba(255,255,255,.15),0 8px 18px rgba(124,58,237,.25);  color: #fff; font-size: 20px; font-weight: 700; line-height: 38px; text-align: center; }
        .nav-links{display:flex;align-items:center;gap:22px}
        .nav-links a{color:var(--muted);font-weight:700}
        .nav-links a.active,.nav-links a:hover{color:var(--text)}
        .nav-cta{display:flex;align-items:center;gap:10px}
        .nav-toggle{display:none;width:44px;height:44px;border-radius:12px;border:1px solid rgba(255,255,255,.14);background:transparent;color:var(--text)}
        html.light .nav-toggle{border-color:rgba(2,6,23,.14)}
        .theme-toggle{position:relative; padding-left:10px; width:44px;height:44px;border-radius:12px;border:1px solid rgba(255,255,255,.14);background:linear-gradient(180deg, rgba(16,19,26,.5), rgba(11,12,16,.6));color:var(--text);cursor:pointer}
        .theme-toggle:focus{outline:2px solid rgba(124,58,237,.45);outline-offset:2px}
        html.light .theme-toggle{border-color:rgba(2,6,23,.14);background:linear-gradient(180deg, rgba(255,255,255,.75), rgba(247,248,251,.9))}
        .reveal{opacity:0;transform:translateY(12px);transition:opacity .6s ease, transform .6s ease}
        .reveal.show{opacity:1;transform:none}
        main{padding:48px 0}
        h1{font-family:'Playfair Display',serif;font-size:42px;line-height:1.1;margin:0 0 10px}
        .grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:16px;margin-top:22px}
        .card{background:var(--panel);border:1px solid rgb(19 31 88 / 40%);border-radius:14px;overflow:hidden}
        .card img{width:100%;height:170px;object-fit:cover}
        .card .info{padding:12px}
        .muted{color:var(--muted)}
        .tab-container{position:relative;display:flex;background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.14);border-radius:999px;padding:6px;max-width:520px;margin:16px auto 0;backdrop-filter:blur(8px)}
        html.light .tab-container{background:rgba(2,6,23,.06);border-color:rgba(2,6,23,.14)}
        .tab-button{position:relative;flex:1;background:none;border:none;padding:10px 16px;border-radius:999px;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:8px;font-weight:800;color:var(--muted);transition:all .24s ease;z-index:2}
        .tab-button.active{color:#fff}
        html.light .tab-button.active{color:var(--text)}
        .tab-indicator{position:absolute;top:6px;left:6px;width:calc(33.33% - 6px);height:calc(100% - 12px);background:linear-gradient(135deg,var(--primary),var(--accent));border-radius:999px;transition:transform .3s ease;z-index:1;box-shadow:0 6px 18px rgba(124,58,237,.28)}
        .tab-indicator.videos{transform:translateX(100%)}
        .tab-indicator.shorts{transform:translateX(200%)}
        .tabs-content{margin-top:18px}
        .tab-panel{display:none}
        .tab-panel.active{display:block}
        .shorts-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(180px,1fr));gap:16px;margin-top:22px}
        .short-card{position:relative;background:var(--panel);border:1px solid rgba(255,255,255,.14);border-radius:18px;overflow:hidden;aspect-ratio:9/16;cursor:pointer;transition:transform .2s ease, box-shadow .2s ease}
        .short-card:hover{transform:translateY(-4px);box-shadow:0 12px 30px rgba(0,0,0,.2);border-color:var(--primary)}
        .short-thumb{width:100%;height:100%;object-fit:cover;display:block}
        .short-play{position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);width:60px;height:60px;background:rgba(255,0,0,.9);border-radius:50%;display:flex;align-items:center;justify-content:center;box-shadow:0 4px 15px rgba(0,0,0,.3)}
        .short-play::after{content:'';width:0;height:0;border-left:15px solid #fff;border-top:10px solid transparent;border-bottom:10px solid transparent;margin-left:5px}
        .short-badge{position:absolute;top:10px;left:10px;background:rgba(255,0,0,.9);color:#fff;padding:6px 10px;border-radius:12px;font-size:11px;font-weight:800}
        .short-info{position:absolute;left:0;right:0;bottom:0;background:linear-gradient(transparent, rgba(0,0,0,.8));padding:16px}
        .short-title{color:#fff;font-weight:700;font-size:14px;line-height:1.3;margin:0 0 6px}
        .short-date{color:rgba(255,255,255,.85);font-size:12px;font-weight:600}
        .progress-wrap{position:fixed;top:0;left:0;right:0;height:3px;background:transparent;z-index:70;pointer-events:none}
        .progress{position:absolute;left:0;top:0;height:3px;width:0;background:linear-gradient(90deg,var(--accent),var(--primary));box-shadow:0 0 8px rgba(34,211,238,.5)}
        .progress-wrap.hidden .progress{opacity:0}
        .shimmer{background:linear-gradient(90deg, rgba(255,255,255,.05), rgba(255,255,255,.12), rgba(255,255,255,.05));background-size:200px 100%;animation:shimmer 1.2s infinite ease-in-out;border-radius:10px}
        html.light .shimmer{background:linear-gradient(90deg, rgba(2,6,23,.06), rgba(2,6,23,.12), rgba(2,6,23,.06))}
        @keyframes shimmer{0%{background-position:-200px 0}100%{background-position:200px 0}}
        .card.skeleton{border:1px solid rgba(255,255,255,.08)}
        html.light .card.skeleton{border-color:rgba(2,6,23,.08)}
        .mobile-menu{position:fixed;inset:68px 0 0 0;background:rgba(10,12,16,.94);backdrop-filter:blur(12px);display:none;flex-direction:column;padding:20px;gap:12px;border-top:1px solid rgba(255,255,255,.06);z-index:60}
        .mobile-menu.open{display:flex}
        .mobile-menu a{padding:12px 10px;border-radius:10px;color:var(--text);font-weight:700}
        .mobile-menu a + a{border-top:1px solid rgba(255,255,255,.08)}
        @media(max-width:980px){.nav-links{display:none}.nav-toggle{display:inline-grid;place-items:center}}
        @media(max-width:480px){.nav{height:60px}.logo{width:32px;height:32px;border-radius:10px}.mobile-menu{inset:60px 0 0 0}}
      </style>
    </head>
    <body>
      <div class="progress-wrap hidden" id="progressWrap"><div class="progress" id="progressBar"></div></div>
      <div class="page-loader" id="loader" aria-live="polite" aria-label="Loading"><div class="dots" aria-hidden="true"><span></span><span></span><span></span></div></div>
      <header>
        <div class="container nav">
          <a href="index.php" class="brand" aria-label="Home"><span class="logo" aria-hidden="true">S</span><span>Shruti Sharma</span></a>
          <nav class="nav-links" aria-label="Primary">
            <a href="index.php">Home</a>
            <a href="about.php">About</a>
            <a href="portfolio.php">Portfolio</a>
            <a href="videos.php" class="active">Videos</a>
            <a href="contact.php">Contact</a>
          </nav>
          <div class="nav-cta">
            <a class="btn secondary" href="portfolio.php">Watch Work</a>
            <button class="theme-toggle" id="themeToggle" aria-label="Toggle theme">
              <svg id="sun" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="4"></circle><path d="M12 2v2M12 20v2M4.93 4.93l1.41 1.41M17.66 17.66l1.41 1.41M2 12h2M20 12h2M4.93 19.07l1.41-1.41M17.66 6.34l1.41-1.41"></path></svg>
              <svg id="moon" style="display:none" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path></svg>
            </button>
            <button class="nav-toggle" id="navToggle" aria-label="Toggle menu"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="3" x2="21" y1="6" y2="6"/><line x1="3" x2="21" y1="12" y2="12"/><line x1="3" x2="21" y1="18" y2="18"/></svg></button>
          </div>
        </div>
        <nav class="mobile-menu" id="mobileMenu" aria-label="Mobile">
          <a href="index.php">Home</a>
          <a href="about.php">About</a>
          <a href="portfolio.php">Portfolio</a>
          <a href="videos.php" class="active">Videos</a>
          <a href="contact.php">Contact</a>
        </nav>
      </header>
      <main>
        <div class="container">
          <h1 class="reveal">Latest Videos</h1>
          <p class="muted reveal" style="transition-delay:.05s">Discover my latest content and YouTube Shorts</p>
          <div class="tab-container reveal" style="transition-delay:.08s">
            <button class="tab-button active" data-tab="all">All</button>
            <button class="tab-button" data-tab="videos">Videos</button>
            <button class="tab-button" data-tab="shorts">Shorts</button>
            <div class="tab-indicator" id="tabIndicator"></div>
          </div>
          <div class="tabs-content">
            <div class="tab-panel active" id="panel-all"><div id="allGrid" class="grid"></div></div>
            <div class="tab-panel" id="panel-videos"><div id="videosGrid" class="grid"></div></div>
            <div class="tab-panel" id="panel-shorts"><div id="shortsGrid" class="shorts-grid"></div></div>
          </div>
        </div>
      </main>
      <footer class="container" style="padding:180px 0;color:var(--muted);border-top:1px solid rgba(255,255,255,.08)">
        <div style="display:flex;align-items:center;justify-content:space-between;gap:16px;flex-wrap:wrap">
          <div class="brand"><span class="logo" aria-hidden="true">S</span><span>Shruti Sharma</span></div>
          <p>© <?php echo date('Y'); ?> Shruti Sharma. All rights reserved.</p>
          <div style="display:flex;gap:10px"><a class="btn secondary" href="portfolio.php" style="height:36px;padding:0 12px">Portfolio</a><a class="btn" href="contact.php" style="height:36px;padding:0 12px">Contact</a></div>
        </div>
      </footer>
      <script>
        const loader=document.getElementById('loader');
        const hideLoader=()=>loader&&loader.classList.add('hidden');
        window.addEventListener('load',hideLoader);setTimeout(hideLoader,1200);
        const navToggle=document.getElementById('navToggle');
        const mobileMenu=document.getElementById('mobileMenu');
        navToggle&&navToggle.addEventListener('click',()=>{mobileMenu&&mobileMenu.classList.toggle('open')});
        const observer=new IntersectionObserver((entries)=>{entries.forEach((entry)=>{if(entry.isIntersecting){entry.target.classList.add('show');observer.unobserve(entry.target);}})},{threshold:0.12});
        document.querySelectorAll('.reveal').forEach((el)=>observer.observe(el));
        (function(){const stored=localStorage.getItem('theme');const prefersLight=window.matchMedia&&window.matchMedia('(prefers-color-scheme: light)').matches;const html=document.documentElement;const current=stored||(prefersLight?'light':'dark');html.classList.toggle('light',current==='light');const sun=document.getElementById('sun');const moon=document.getElementById('moon');if(sun&&moon){const showSun=current==='light';sun.style.display=showSun?'block':'none';moon.style.display=showSun?'none':'block';}})();
        document.getElementById('themeToggle')&&document.getElementById('themeToggle').addEventListener('click',()=>{const html=document.documentElement;const isLight=html.classList.toggle('light');const theme=isLight?'light':'dark';localStorage.setItem('theme',theme);const sun=document.getElementById('sun');const moon=document.getElementById('moon');if(sun&&moon){const showSun=isLight;sun.style.display=showSun?'block':'none';moon.style.display=showSun?'none':'block';}});
        const progressWrap=document.getElementById('progressWrap');
        const progressBar=document.getElementById('progressBar');
        function showProgress(){progressWrap.classList.remove('hidden');progressBar.style.width='0%';}
        function updateProgress(v){progressBar.style.width=Math.max(0,Math.min(100,Math.round(v*100)))+'%';}
        function finishProgress(){updateProgress(1);setTimeout(()=>{progressWrap.classList.add('hidden');progressBar.style.width='0%';},500);}
        function addSkeletons(gridEl,n){for(let i=0;i<n;i++){const el=document.createElement('div');el.className='card skeleton';el.innerHTML=`<div class="shimmer" style="height:170px"></div><div class="info"><div class="shimmer" style="height:16px;width:70%"></div><div class="shimmer" style="height:12px;width:40%;margin-top:8px"></div></div>`;gridEl.appendChild(el);}}
        function clearSkeletons(){document.querySelectorAll('.card.skeleton,.short-card.skeleton').forEach(e=>e.remove());}
        const CHUNK=12;let allItems=[];let longItems=[];let shortItems=[];let currentTab='all';let rendered=0;let sentinel=document.createElement('div');
        function gridEl(){if(currentTab==='all') return document.getElementById('allGrid');if(currentTab==='videos') return document.getElementById('videosGrid');return document.getElementById('shortsGrid');}
        function totalItems(){if(currentTab==='all') return allItems.length;if(currentTab==='videos') return longItems.length;return shortItems.length;}
        function itemAt(i){if(currentTab==='all') return allItems[i];if(currentTab==='videos') return longItems[i];return shortItems[i];}
        function createGridCard(v){const el=document.createElement('div');el.className='card';el.innerHTML=`<img loading="lazy" src="${v.thumbnailUrl}" alt="${v.title}"><div class="info"><div style="font-weight:700">${v.title}</div><div class="muted">${new Date(v.publishedAt).toLocaleDateString('en-US',{year:'numeric',month:'short',day:'numeric'})}</div></div>`;el.onclick=()=>window.open('https://www.youtube.com/watch?v='+v.id,'_blank');return el;}
        function createShortCard(v){const el=document.createElement('div');el.className='short-card';el.innerHTML=`<img class="short-thumb" loading="lazy" src="${v.thumbnailUrl}" alt="${v.title}"><div class="short-play" aria-label="Play"></div><div class="short-badge">SHORTS</div><div class="short-info"><div class="short-title">${v.title.replace(/#shorts/ig,'')}</div><div class="short-date">${new Date(v.publishedAt).toLocaleDateString('en-US',{year:'numeric',month:'short',day:'numeric'})}</div></div>`;el.onclick=()=>window.open('https://www.youtube.com/watch?v='+v.id,'_blank');return el;}
        function renderChunk(){const grid=gridEl();if(rendered===0){grid.innerHTML='';}if(rendered>=totalItems()){finishProgress();loadMoreObserver.disconnect();return;}const end=Math.min(rendered+CHUNK,totalItems());for(let i=rendered;i<end;i++){const v=itemAt(i);grid.appendChild(currentTab==='shorts'?createShortCard(v):createGridCard(v));}rendered=end;updateProgress(totalItems()?rendered/totalItems():1);}
        const loadMoreObserver=new IntersectionObserver((entries)=>{if(entries[0].isIntersecting){renderChunk();}},{rootMargin:'600px'});
        function switchTab(tab){if(currentTab===tab) return;currentTab=tab;rendered=0;document.querySelectorAll('.tab-button').forEach(b=>b.classList.remove('active'));document.querySelector(`[data-tab="${tab}"]`).classList.add('active');document.querySelectorAll('.tab-panel').forEach(p=>p.classList.remove('active'));document.getElementById(`panel-${tab}`).classList.add('active');const ind=document.getElementById('tabIndicator');ind.classList.remove('videos','shorts');if(tab==='videos') ind.classList.add('videos');else if(tab==='shorts') ind.classList.add('shorts');loadMoreObserver.disconnect();sentinel.remove();const grid=gridEl();grid.innerHTML='';showProgress();if(tab==='shorts'){addSkeletons(grid,0);}else{addSkeletons(grid,8);}renderChunk();grid.after(sentinel);loadMoreObserver.observe(sentinel);}
        async function loadVideos(){showProgress();const initialGrid=document.getElementById('allGrid');addSkeletons(initialGrid,8);const res=await fetch('videos.php?api=1',{headers:{Accept:'application/json'}});clearSkeletons();if(!res.ok){finishProgress();return;}const data=await res.json();const list=Array.isArray(data.videos)?data.videos:[];const params=new URLSearchParams(location.search);const qvid=params.get('qvid');let match=null;if(qvid){match=list.find(v=>v.id===qvid);}if(match){allItems=[match];longItems=((match.durationSeconds||0)>160)?[match]:[];shortItems=((match.durationSeconds||0)<=160)?[match]:[];}else{allItems=list.slice();longItems=list.filter(v=>((v.durationSeconds||0)>160));shortItems=list.filter(v=>((v.durationSeconds||0)<=160));}document.querySelectorAll('.tab-button').forEach(btn=>btn.addEventListener('click',()=>switchTab(btn.dataset.tab)));const initialTab=match?(longItems.length?'videos':'shorts'):'all';document.getElementById(`panel-${initialTab}`).classList.add('active');currentTab=initialTab;rendered=0;loadMoreObserver.disconnect();try{sentinel.remove();}catch{}const grid=gridEl();grid.innerHTML='';updateProgress(0.1);renderChunk();grid.after(sentinel);loadMoreObserver.observe(sentinel);} 
        loadVideos();
      </script>
    </body>
    </html>
    <?php
    exit;
}

// ==== CONFIG ====
$YOUTUBE_API_KEY = 'AIzaSyAVaWhY-20hhe2XHTSGWzbxVLqUsyhFb7k';
$CHANNEL_ID = 'UCsfxqDhPpjHQBjymEFK5r9Q';

// ==== POLICY ====
$INITIAL_FETCH_LIMIT = 500;
$PER_DAY_LIMIT_MULTIPLIER = 3;
$MAX_FETCH_LIMIT = 200;

// ==== FILE PATHS ====
$CACHE_FILE = __DIR__ . '/videos.json';
$SEARCH_CACHE_FILE = __DIR__ . '/videos_search.json';

header('Content-Type: application/json; charset=utf-8');

function http_get_json($url){$ch=curl_init();curl_setopt($ch,CURLOPT_URL,$url);curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);curl_setopt($ch,CURLOPT_TIMEOUT,20);$response=curl_exec($ch);$httpCode=curl_getinfo($ch,CURLINFO_HTTP_CODE);$err=curl_error($ch);curl_close($ch);if($response===false||$httpCode>=400){$preview='';if(is_string($response)&&strlen($response)>0){$preview=substr($response,0,500);}logger_log('video','ERROR','HTTP request failed',['url'=>$url,'status'=>$httpCode,'error'=>$err,'responseBodyPreview'=>$preview]);throw new Exception('HTTP request failed: '.($err?:('status '.$httpCode)));}$data=json_decode($response,true);if($data===null){$preview=is_string($response)?substr($response,0,500):'';logger_log('video','ERROR','Failed to decode JSON response',['url'=>$url,'responseBodyPreview'=>$preview]);throw new Exception('Failed to decode JSON response');}return $data;}
function iso_duration_to_seconds($iso){$pattern='/PT(?:(\d+)H)?(?:(\d+)M)?(?:(\d+)S)?/';if(preg_match($pattern,$iso,$matches)){$hours=isset($matches[1])?(int)$matches[1]:0;$minutes=isset($matches[2])?(int)$matches[2]:0;$seconds=isset($matches[3])?(int)$matches[3]:0;return $hours*3600+$minutes*60+$seconds;}return 0;}
function load_cache($file){if(!file_exists($file)){return ['lastUpdatedEpoch'=>0,'videos'=>[]];}$raw=file_get_contents($file);$data=json_decode($raw,true);if(!is_array($data)){return ['lastUpdatedEpoch'=>0,'videos'=>[]];}if(!isset($data['videos'])||!is_array($data['videos'])){$data['videos']=[];}if(!isset($data['lastUpdatedEpoch'])){$data['lastUpdatedEpoch']=0;}return $data;}
function save_cache($file,$data){file_put_contents($file,json_encode($data,JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES));}
function save_search_cache($file,$searchResponse){$payload=is_array($searchResponse)?$searchResponse:[];file_put_contents($file,json_encode($payload,JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES));}
function compute_fetch_limit_smart($lastUpdatedEpoch,$isInitial,$initialLimit,$perDayMultiplier,$maxLimit,$apiKey,$channelId,$knownIdsSet){if($isInitial)return $initialLimit;$now=time();$ageSeconds=$now-(int)$lastUpdatedEpoch;$ageDays=floor($ageSeconds/(24*3600));if($ageDays>0){$dynamicLimit=$ageDays*$perDayMultiplier;return min($dynamicLimit,$maxLimit);}try{logger_log('video','INFO','Performing quick check for new videos within 24h',['ageSeconds'=>$ageSeconds,'ageHours'=>round($ageSeconds/3600,1)]);$url='https://www.googleapis.com/youtube/v3/search'.'?key='.urlencode($apiKey).'&channelId='.urlencode($channelId).'&part=snippet,id&order=date&maxResults=1';$data=http_get_json($url);if(isset($data['items'])&&!empty($data['items'])){$newestItem=$data['items'][0];if(isset($newestItem['id']['kind'])&&$newestItem['id']['kind']==='youtube#video'){$newestVideoId=$newestItem['id']['videoId']??null;if($newestVideoId&&!isset($knownIdsSet[$newestVideoId])){logger_log('video','INFO','Found new video within 24h',['newVideoId'=>$newestVideoId,'ageHours'=>round($ageSeconds/3600,1)]);return 1;}else{logger_log('video','INFO','No new videos found within 24h',['newestVideoId'=>$newestVideoId,'isKnown'=>isset($knownIdsSet[$newestVideoId]),'ageHours'=>round($ageSeconds/3600,1)]);return 0;}}}logger_log('video','INFO','Quick check completed, no new videos found',['ageHours'=>round($ageSeconds/3600,1)]);return 0;}catch(Exception $ex){logger_exception('video',$ex,'WARNING',['context'=>'quick_check_within_24h','ageHours'=>round($ageSeconds/3600,1)]);return 0;}}
function fetch_latest_video_ids($apiKey,$channelId,$limit,$knownIdsSet){$collected=[];$rawItems=[];$meta=null;if($limit<=0)return ['ids'=>$collected,'rawItems'=>$rawItems,'searchMeta'=>$meta];$pageToken='';$perPage=50;while(count($collected)<$limit){$remaining=$limit-count($collected);$maxResults=$remaining<$perPage?$remaining:$perPage;$url='https://www.googleapis.com/youtube/v3/search'.'?key='.urlencode($apiKey).'&channelId='.urlencode($channelId).'&part=snippet,id&order=date&maxResults='.$maxResults.($pageToken?'&pageToken='.urlencode($pageToken):'');$data=http_get_json($url);if($meta===null){$meta=['kind'=>'youtube#searchListResponse','etag'=>$data['etag']??null,'nextPageToken'=>$data['nextPageToken']??null,'regionCode'=>$data['regionCode']??null,'pageInfo'=>$data['pageInfo']??null,];}else{$meta['nextPageToken']=$data['nextPageToken']??null;}if(!isset($data['items'])||empty($data['items'])){break;}foreach($data['items'] as $item){if(isset($item['id']['kind'])&&$item['id']['kind']==='youtube#video'){$vid=$item['id']['videoId']??null;if($vid&&!isset($knownIdsSet[$vid])){$collected[]=['id'=>$vid,'snippet'=>$item['snippet']??null];$knownIdsSet[$vid]=true;}$rawItems[]=$item;}}if(!isset($data['nextPageToken'])){break;}$pageToken=$data['nextPageToken'];}return ['ids'=>$collected,'rawItems'=>$rawItems,'searchMeta'=>$meta];}
function fetch_videos_details($apiKey,$videoIds){$details=[];$chunkSize=50;for($i=0;$i<count($videoIds);$i+=$chunkSize){$chunk=array_slice($videoIds,$i,$chunkSize);$url='https://www.googleapis.com/youtube/v3/videos'.'?key='.urlencode($apiKey).'&id='.urlencode(implode(',',$chunk)).'&part=snippet,contentDetails';$data=http_get_json($url);if(isset($data['items'])){foreach($data['items'] as $item){$details[]=$item;}}}return $details;}
function fetch_search_page_items($apiKey,$channelId,$maxResults=50){if($maxResults<=0)$maxResults=50;if($maxResults>50)$maxResults=50;$url='https://www.googleapis.com/youtube/v3/search'.'?key='.urlencode($apiKey).'&channelId='.urlencode($channelId).'&part=snippet,id&order=date&maxResults='.$maxResults;$data=http_get_json($url);return isset($data['items'])&&is_array($data['items'])?$data['items']:[];}
function enrich_search_items(array $items):array{$out=[];foreach($items as $item){$snippet=isset($item['snippet'])&&is_array($item['snippet'])?$item['snippet']:[];$enriched=$item;if(!isset($enriched['channelId'])&&isset($snippet['channelId'])){$enriched['channelId']=$snippet['channelId'];}if(!isset($enriched['publishTime'])&&isset($snippet['publishTime'])){$enriched['publishTime']=$snippet['publishTime'];}if(!isset($enriched['publishTime'])&&isset($snippet['publishedAt'])){$enriched['publishTime']=$snippet['publishedAt'];}if(!isset($enriched['liveBroadcastContent'])&&isset($snippet['liveBroadcastContent'])){$enriched['liveBroadcastContent']=$snippet['liveBroadcastContent'];}if(!isset($enriched['thumbnails'])&&isset($snippet['thumbnails'])){$enriched['thumbnails']=$snippet['thumbnails'];}$out[]=$enriched;}return $out;}
function to_cached_video_record($item){$id=$item['id']??'';$snippet=$item['snippet']??[];$contentDetails=$item['contentDetails']??[];$durationIso=$contentDetails['duration']??'PT0S';$durationSeconds=iso_duration_to_seconds($durationIso);$thumbs=$snippet['thumbnails']??[];$thumb=$thumbs['high']['url']??($thumbs['medium']['url']??($thumbs['default']['url']??''));return ['id'=>$id,'title'=>$snippet['title']??'','description'=>$snippet['description']??'','publishedAt'=>$snippet['publishedAt']??'','thumbnailUrl'=>$thumb,'durationIso'=>$durationIso,'durationSeconds'=>$durationSeconds];}
try{logger_log('video','INFO','videos.php request start',['method'=>$_SERVER['REQUEST_METHOD']??'CLI','ip'=>$_SERVER['REMOTE_ADDR']??null,'ua'=>$_SERVER['HTTP_USER_AGENT']??null]);$cache=load_cache($CACHE_FILE);$videos=$cache['videos'];$lastUpdatedEpoch=(int)$cache['lastUpdatedEpoch'];$knownIdsSet=[];foreach($videos as $v){if(isset($v['id'])){$knownIdsSet[$v['id']]=true;}}$isInitial=(count($videos)===0);$fetchLimit=compute_fetch_limit_smart($lastUpdatedEpoch,$isInitial,$INITIAL_FETCH_LIMIT,$PER_DAY_LIMIT_MULTIPLIER,$MAX_FETCH_LIMIT,$YOUTUBE_API_KEY,$CHANNEL_ID,$knownIdsSet);logger_log('video','INFO','Computed fetch limit',['isInitial'=>$isInitial,'lastUpdatedEpoch'=>$lastUpdatedEpoch,'ageDays'=>$isInitial?0:floor((time()-$lastUpdatedEpoch)/(24*3600)),'fetchLimit'=>$fetchLimit,'perDayMultiplier'=>$PER_DAY_LIMIT_MULTIPLIER]);if($fetchLimit>0){try{logger_log('video','INFO','Fetching latest video IDs',['limit'=>$fetchLimit]);$latestResult=fetch_latest_video_ids($YOUTUBE_API_KEY,$CHANNEL_ID,$fetchLimit,$knownIdsSet);$latest=$latestResult['ids'];$rawSearchItems=enrich_search_items($latestResult['rawItems']);$searchMeta=$latestResult['searchMeta']??null;$ids=array_map(function($x){return $x['id'];},$latest);logger_log('video','INFO','Collected latest video IDs',['count'=>count($ids),'rawSearchItems'=>count($rawSearchItems)]);if(!empty($ids)){logger_log('video','INFO','Fetching details for video IDs',['count'=>count($ids)]);$details=fetch_videos_details($YOUTUBE_API_KEY,$ids);$newRecords=[];$searchMap=[];foreach($rawSearchItems as $si){$vId=$si['id']['videoId']??null;if($vId){$searchMap[$vId]=$si;}}foreach($details as $item){$record=to_cached_video_record($item);$merge=$searchMap[$record['id']]??null;if(is_array($merge)){if(isset($merge['kind']))$record['kind']=$merge['kind'];if(isset($merge['etag']))$record['etag']=$merge['etag'];if(isset($merge['id']))$record['idObject']=$merge['id'];if(isset($merge['channelId']))$record['channelId']=$merge['channelId'];if(isset($merge['publishTime']))$record['publishTime']=$merge['publishTime'];if(isset($merge['liveBroadcastContent']))$record['liveBroadcastContent']=$merge['liveBroadcastContent'];if(isset($merge['thumbnails']))$record['thumbnails']=$merge['thumbnails'];if(isset($merge['channelTitle']))$record['channelTitle']=$merge['channelTitle'];}$newRecords[]=$record;}logger_log('video','INFO','Fetched details and normalized records',['newRecords'=>count($newRecords)]);$idToVideo=[];foreach($videos as $v){$idToVideo[$v['id']]=$v;}foreach($newRecords as $nr){$idToVideo[$nr['id']]=$nr;}$videos=array_values($idToVideo);usort($videos,function($a,$b){return strcmp($b['publishedAt'],$a['publishedAt']);});$cache['videos']=$videos;$cache['lastUpdatedEpoch']=time();if(is_array($searchMeta)){$cache['kind']=$searchMeta['kind']??'youtube#searchListResponse';if(isset($searchMeta['etag']))$cache['etag']=$searchMeta['etag'];if(array_key_exists('nextPageToken',$searchMeta))$cache['nextPageToken']=$searchMeta['nextPageToken'];if(isset($searchMeta['regionCode']))$cache['regionCode']=$searchMeta['regionCode'];if(isset($searchMeta['pageInfo']))$cache['pageInfo']=$searchMeta['pageInfo'];}unset($cache['lastSearchItems'],$cache['searchResponse']);save_cache($CACHE_FILE,$cache);logger_log('video','INFO','Cache updated',['totalVideos'=>count($videos)]);}else{if($isInitial){$cache['videos']=$videos;$cache['lastUpdatedEpoch']=time();save_cache($CACHE_FILE,$cache);}logger_log('video','INFO','No new video IDs found');}}catch(Exception $updateEx){logger_exception('video',$updateEx);}}$served=load_cache($CACHE_FILE);logger_log('video','INFO','Serving cache',['totalVideos'=>is_array($served['videos']??null)?count($served['videos']):0]);echo json_encode(['lastUpdatedEpoch'=>$served['lastUpdatedEpoch']??0,'videos'=>$served['videos']??[]],JSON_UNESCAPED_SLASHES);
}catch(Exception $ex){http_response_code(500);logger_exception('video',$ex);echo json_encode(['error'=>true,'message'=>$ex->getMessage()]);}
?>