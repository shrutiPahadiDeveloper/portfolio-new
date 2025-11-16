<?php
require_once __DIR__ . '/logger.php';
logger_log('homepage', 'INFO', 'Homepage view', [
  'ip' => $_SERVER['REMOTE_ADDR'] ?? null,
  'ua' => $_SERVER['HTTP_USER_AGENT'] ?? null
]);
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <link rel="icon" href="logos/favicon.ico" type="image/x-icon" />
    <title>Shruti Sharma Portfolio</title>
    <meta name="description" content="Content Creator | Pahadi Girl | Nature & Culture Lover" />
    <meta property="og:title" content="Shruti Sharma Portfolio" />
    <meta property="og:image" content="shruti.jpg" />
    <meta property="og:description" content="Content Creator | Himachali Soul | Nature Lover" />
    <meta property="og:url" content="https://shrutipahadi.com" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet" />
    <style>
      :root {
        --bg: #0b0c10;
        --bg-soft: #0e1016;
        --panel: #10131a;
        --text: #e8ecf1;
        --muted: #a6b0c0;
        --primary: #7c3aed; /* violet */
        --primary-strong: #6d28d9;
        --accent: #22d3ee; /* cyan */
        --ring: rgba(124, 58, 237, 0.45);
        --shadow: 0 14px 38px rgba(0,0,0,0.35);
        --radius: 16px;
        --radius-lg: 24px;
        --container: 1200px;
      }

      html.light {
        --bg: #f7f8fb;
        --bg-soft: #ffffff;
        --panel: #ffffff;
        --text: #0f172a;
        --muted: #53607a;
        --primary: #6d28d9;
        --primary-strong: #5b21b6;
        --accent: #0891b2;
        --ring: rgba(93, 63, 211, 0.35);
        --shadow: 0 14px 26px rgba(2,6,23,0.08);
      }

      * { box-sizing: border-box; }
      /* html, body { min-height: 100%; } */
      body {
        margin: 0;
        font-family: 'Inter', system-ui, -apple-system, Segoe UI, Roboto, Helvetica, Arial;
        color: var(--text);
        background: radial-gradient(1200px 600px at 85% -10%, rgba(124,58,237,0.15), transparent 60%),
                    radial-gradient(900px 500px at -10% 20%, rgba(34,211,238,0.12), transparent 60%),
                    linear-gradient(180deg, var(--bg), var(--bg-soft));
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
        overflow-x: hidden;
      }

      a { color: inherit; text-decoration: none; }
      img { max-width: 100%; display: block; }
      .container { max-width: var(--container); margin: 0 auto; padding: 0 clamp(18px, 6vw, 28px); }

      /* Page loader */
      .page-loader { position: fixed; inset: 0; display: grid; place-items: center; background: var(--bg); z-index: 100; transition: opacity .4s ease, visibility .4s ease; }
      .page-loader.hidden { opacity: 0; visibility: hidden; }
      .dots { display: inline-flex; gap: 10px; }
      .dots span { width: 10px; height: 10px; border-radius: 999px; background: var(--text); opacity: .4; animation: bounce 1s infinite ease-in-out; }
      .dots span:nth-child(2) { animation-delay: .15s; }
      .dots span:nth-child(3) { animation-delay: .3s; }
      @keyframes bounce { 0%, 80%, 100% { transform: scale(0); opacity: .3 } 40% { transform: scale(1); opacity: 1 } }

      /* Header */
      header { position: sticky; top: 0; z-index: 50; backdrop-filter: saturate(180%) blur(10px); background: rgba(16,19,26,0.6); border-bottom: 1px solid rgba(255,255,255,0.06); }
      html.light header { background: rgba(255,255,255,0.7); border-bottom: 1px solid rgba(2,6,23,0.08); }
      .nav { height: 68px; display: flex; align-items: center; justify-content: space-between; }
      .brand { display: inline-flex; align-items: center; gap: 10px; font-weight: 800; letter-spacing: .2px; }
      .logo { width: 38px; height: 38px; border-radius: 12px; background: linear-gradient(135deg, var(--primary), var(--accent)); box-shadow: inset 0 0 0 1px rgba(255,255,255,0.15), 0 8px 18px rgba(124,58,237,0.25); }
      .nav-links { display: flex; align-items: center; gap: 22px; }
      .nav-links a { color: var(--muted); font-weight: 600; }
      .nav-links a.active, .nav-links a:hover { color: var(--text); }
      .nav-cta { display: flex; align-items: center; gap: 10px; }
      .nav-toggle { display: none; width: 44px; height: 44px; border-radius: 12px; border: 1px solid rgba(255,255,255,0.14); background: transparent; color: var(--text); }
      html.light .nav-toggle { border-color: rgba(2,6,23,0.14); }

      .theme-toggle { position: relative; width: 44px; height: 44px; border-radius: 12px; border: 1px solid rgba(255,255,255,0.14); background: linear-gradient(180deg, rgba(16,19,26,0.5), rgba(11,12,16,0.6)); color: var(--text); cursor: pointer; }
      .theme-toggle:focus { outline: 2px solid var(--ring); outline-offset: 2px; }
      html.light .theme-toggle { border-color: rgba(2,6,23,0.14); background: linear-gradient(180deg, rgba(255,255,255,0.75), rgba(247,248,251,0.9)); }

      /* Buttons */
      .btn { display: inline-flex; align-items: center; justify-content: center; gap: 10px; height: 46px; padding: 0 18px; border-radius: 12px; background: var(--primary); color: #fff; font-weight: 700; border: 0; cursor: pointer; box-shadow: 0 10px 22px rgba(124,58,237,0.25); transition: transform .12s ease, box-shadow .12s ease, background .24s ease; }
      .btn:hover { transform: translateY(-1px); box-shadow: 0 14px 28px rgba(124,58,237,0.32); background: var(--primary-strong); }
      .btn.secondary { background: transparent; color: var(--text); border: 1px solid rgba(255,255,255,0.14); box-shadow: none; }
      .btn.secondary:hover { background: rgba(255,255,255,0.06); }
      html.light .btn.secondary { border-color: rgba(2,6,23,0.14); }
      html.light .btn.secondary:hover { background: rgba(2,6,23,0.06); }

      /* Hero */
      .hero { padding: 84px 0 36px; position: relative; overflow: clip; }
      .hero-grid { display: grid; grid-template-columns: 1.05fr .95fr; align-items: center; gap: 40px; }
      .eyebrow { display: inline-flex; align-items: center; gap: 8px; padding: 6px 10px; border-radius: 999px; font-weight: 700; letter-spacing: .2px; color: var(--accent); background: rgba(34,211,238,0.14); }
      h1 { font-family: 'Playfair Display', serif; font-size: clamp(42px, 5.6vw, 72px); line-height: 1.05; margin: 12px 0 8px; }
      .hero p { color: var(--muted); font-size: clamp(16px, 2.2vw, 18px); max-width: 60ch; margin: 0; }
      .cta-row { display: flex; gap: 12px; flex-wrap: wrap; margin-top: 22px; }
      .tagline { font-style: italic; margin-top: 8px; margin-bottom: 18px; color: var(--muted); }

      /* Hero visual */
      .visual { position: relative; display: grid; place-items: center; }
      .portrait-wrap { position: relative; padding: 16px; border-radius: var(--radius-lg); background: linear-gradient(180deg, rgba(124,58,237,0.2), rgba(34,211,238,0.2)); border: 1px solid rgba(255,255,255,0.12); box-shadow: var(--shadow); overflow: hidden; aspect-ratio: 16/9; display: grid; width: 100%; max-width: 740px; margin: 0 auto; }
      .portrait { position: relative; border-radius: 20px; overflow: hidden; width: 100%; height: 100%; transform: translateZ(0); }
      .portrait img { width: 100%; height: 100%; object-fit: cover; object-position: center; filter: saturate(1.05) contrast(1.02); }
      .glow { position: absolute; inset: -20%; background: radial-gradient(600px 250px at 80% 20%, rgba(255,255,255,0.25), transparent 60%); mix-blend-mode: overlay; pointer-events: none; }
      .orb { position: absolute; width: 70px; height: 70px; border-radius: 999px; background: radial-gradient(circle at 30% 30%, rgba(255,255,255,0.8), rgba(255,255,255,0) 60%), linear-gradient(135deg, var(--primary), var(--accent)); filter: blur(0.2px); opacity: .85; animation: float 8s ease-in-out infinite; box-shadow: 0 12px 30px rgba(124,58,237,0.35); }
      .orb.o1 { top: -16px; right: -16px; animation-delay: .1s; }
      .orb.o2 { bottom: -14px; left: -10px; width: 90px; height: 90px; animation-delay: .6s; }
      .orb.o3 { bottom: 16px; right: -20px; width: 56px; height: 56px; animation-delay: .3s; }
      @keyframes float { 0%, 100% { transform: translateY(0) translateX(0); } 50% { transform: translateY(-12px) translateX(6px); } }

      /* Reveal */
      .reveal { opacity: 0; transform: translateY(12px); transition: opacity .6s ease, transform .6s ease; }
      .reveal.show { opacity: 1; transform: none; }

      /* Footer */
      footer { padding: 40px 0; color: var(--muted); border-top: 1px solid rgba(255,255,255,0.08); margin-top: 40px; }
      .footer-row { display: flex; align-items: center; justify-content: space-between; gap: 16px; flex-wrap: wrap; }

      /* Responsive */
      @media (max-width: 1100px) { .container { padding: 0 clamp(18px, 6vw, 24px); } }
      @media (max-width: 980px) { .nav-links { display: none; } .nav-toggle { display: inline-grid; place-items: center; } .hero { padding: 64px 0 28px; } .hero-grid { grid-template-columns: 1fr; gap: 28px; margin-top: 60px;} }
      @media (max-width: 768px) { h1 { font-size: clamp(34px, 7vw, 44px); } .btn { height: 42px; padding: 0 16px; } .portrait-wrap { padding: 12px; border-radius: 18px; } .portrait { border-radius: 16px; aspect-ratio: 3/4; } }
      @media (max-width: 640px) { .nav-cta .btn.secondary { display: none; } }
      @media (max-width: 420px) { .brand span:last-child { display: none; } }
      @media (max-width: 400px) { .container { padding: 0 16px; } }
      @media (max-width: 480px) { .nav { height: 60px; } .logo { width: 32px; height: 32px; border-radius: 10px; } .btn { height: 40px; padding: 0 14px; } .cta-row { gap: 10px; } }

      /* Mobile menu */
      .mobile-menu { position: fixed; inset: 68px 0 0 0; background: rgba(10,12,16,.94); backdrop-filter: blur(12px); display: none; flex-direction: column; padding: 20px; gap: 12px; border-top: 1px solid rgba(255,255,255,0.06); z-index: 60; }
      .mobile-menu.open { display: flex; }
      .mobile-menu a { padding: 12px 10px; border-radius: 10px; color: var(--muted); font-weight: 600; }
      .mobile-menu a:hover { color: var(--text); background: rgba(255,255,255,0.06); }
      html.light .mobile-menu { background: rgba(255,255,255,.92); border-top-color: rgba(2,6,23,0.08); }
      html.light .mobile-menu a:hover { background: rgba(2,6,23,0.06); }
      @media (max-width: 480px) { .mobile-menu { inset: 60px 0 0 0; } }

      /* Reduce motion */
      @media (prefers-reduced-motion: reduce) {
        .reveal { transition: none; }
        .orb { animation: none; }
      }
    </style>
    <style>
      :root { --panel: #0f1218; --surface: #121622; --muted: #9aa6b2; --shadow: 0 18px 50px rgba(0,0,0,0.35); }
      html.light { --bg-soft: #fbfcff; --surface: #ffffff; --shadow: 0 18px 36px rgba(2,6,23,0.08); }
      header { backdrop-filter: saturate(180%) blur(12px); background: color-mix(in oklab, var(--surface) 75%, transparent); box-shadow: 0 10px 26px rgba(0,0,0,0.14); }
      html.light header { background: rgba(255,255,255,0.85); box-shadow: 0 8px 20px rgba(2,6,23,0.06); }
      .nav-links a { font-weight: 700; position: relative; }
      .nav-links a::after { content: ""; position: absolute; left: 0; right: 0; bottom: -6px; height: 2px; background: currentColor; opacity: 0; transform: scaleX(.6); transition: opacity .15s ease, transform .15s ease; }
      .nav-links a:hover::after, .nav-links a.active::after { opacity: .9; transform: scaleX(1); }
      .btn { font-weight: 800; box-shadow: 0 12px 28px rgba(124,58,237,0.28); }
      .btn:hover { box-shadow: 0 16px 32px rgba(124,58,237,0.34); }
      .portrait-wrap { background: linear-gradient(180deg, color-mix(in oklab, var(--primary) 24%, transparent), color-mix(in oklab, var(--accent) 24%, transparent)); }
      .hero { padding: clamp(40px, 6vh, 84px) 0 clamp(20px, 4vh, 36px); }
      /* .portrait { max-height: min(60vh, 520px); height: auto; } */
      .metrics { display: grid; grid-template-columns: repeat(3,minmax(0,1fr)); gap: 12px; margin-top: 16px; }
      .metric { background: var(--panel); border: 1px solid rgba(255,255,255,0.12); border-radius: 14px; padding: 12px; }
      html.light .metric { border-color: rgba(2,6,23,0.10); }
      .metric strong { display:block; font-size: 20px; }
      .metric span { font-size: 12px; color: var(--muted); font-weight: 600; }
      .mobile-menu { background:
          radial-gradient(900px 500px at -10% 20%, color-mix(in oklab, var(--accent) 10%, transparent), transparent 60%),
          radial-gradient(1200px 600px at 85% -10%, color-mix(in oklab, var(--primary) 10%, transparent), transparent 60%),
          linear-gradient(180deg, color-mix(in oklab, var(--panel) 96%, transparent), color-mix(in oklab, var(--bg-soft) 98%, transparent)); }
      .mobile-menu a { color: var(--text); font-weight: 700; }
      .mobile-menu a + a { border-top: 1px solid rgba(255,255,255,0.08); }
      html.light .mobile-menu a + a { border-top-color: rgba(2,6,23,0.08); }
    </style>
    <style>
      @media (max-height: 820px) and (min-width: 980px) {
        .hero { padding: 48px 0 22px; }
        h1 { font-size: clamp(38px, 4.6vw, 60px); }
        .cta-row { margin-top: 18px; }
        /* .portrait { max-height: 52vh; } */
        .metrics { grid-template-columns: 1fr 1fr; }
      }
      @media (max-height: 700px) and (min-width: 980px) {
        .hero { padding: 36px 0 18px; }
        h1 { font-size: clamp(34px, 4vw, 52px); }
        /* .portrait { max-height: 46vh; } */
      }
    </style>
    <style>
      @media (min-width: 980px) and (max-width: 1220px) {
        .container { padding: 0 32px; }
        .hero-grid { grid-template-columns: 1fr 1fr; gap: 28px; }
        .visual { padding: 0 16px; }
        .portrait-wrap { max-width: 760px; }
      }
    </style>
    <style>
      @media (max-width: 980px) {
        .hero-grid { display: flex; flex-direction: column-reverse; gap: 35px; margin-top: 60px;}
        .visual { order: 2; }
        .portrait-wrap { aspect-ratio: 3/4; max-width: 92vw; padding: 14px; max-height: 64vh; }
        .portrait {
    height: 100%;          /* override height:auto */
    max-height: 100%;      /* override max-height */
    aspect-ratio: auto;    /* let the wrapper control the ratio */
  }
        .portrait img { object-position: center; }
        .hero { padding: 40px 0 24px; }
        .visual { padding: 0 14px; }
      }
    </style>
    <style>
      @media (max-width: 560px) {
        .container { padding: 0 22px; }
        .metrics { grid-template-columns: 1fr 1fr; }
      }
      @media (max-width: 420px) {
        .container { padding: 0 24px; }
        .portrait-wrap { aspect-ratio: 3/4; max-width: 90vw; padding: 12px; }
                .portrait {
    height: 100%;          /* override height:auto */
    max-height: 100%;      /* override max-height */
    aspect-ratio: auto;    /* let the wrapper control the ratio */
  }
      }
    </style>
  </head>
  <body>
    <!-- Loader -->
    <div class="page-loader" id="loader" aria-live="polite" aria-label="Loading">
      <div class="dots" aria-hidden="true"><span></span><span></span><span></span></div>
    </div>

    <!-- Header -->
    <header>
      <div class="container nav">
        <a href="index.php" class="brand" aria-label="Home">
          <span class="logo" aria-hidden="true"></span>
          <span>Shruti Sharma</span>
        </a>
        <nav class="nav-links" aria-label="Primary">
          <a href="index.php" class="active">Home</a>
          <a href="about.html">About</a>
          <a href="portfolio.html">Portfolio</a>
          <a href="contact.html">Contact</a>
        </nav>
        <div class="nav-cta">
          <a class="btn secondary" href="portfolio.html">Watch Work</a>
          <button class="theme-toggle" id="themeToggle" aria-label="Toggle theme">
            <svg id="sun" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="4"></circle><path d="M12 2v2M12 20v2M4.93 4.93l1.41 1.41M17.66 17.66l1.41 1.41M2 12h2M20 12h2M4.93 19.07l1.41-1.41M17.66 6.34l1.41-1.41"></path></svg>
            <svg id="moon" style="display:none" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path></svg>
          </button>
          <button class="nav-toggle" id="navToggle" aria-label="Toggle menu">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="3" x2="21" y1="6" y2="6"/><line x1="3" x2="21" y1="12" y2="12"/><line x1="3" x2="21" y1="18" y2="18"/></svg>
          </button>
        </div>
      </div>
      <nav class="mobile-menu" id="mobileMenu" aria-label="Mobile">
        <a href="index.php" class="active">Home</a>
        <a href="about.html">About</a>
        <a href="portfolio.html">Portfolio</a>
        <a href="contact.html">Contact</a>
      </nav>
    </header>

    <!-- Main -->
    <main>
      <!-- Home / Hero -->
      <section class="hero container" id="home">
        <div class="hero-grid">
          <div>
            <span class="eyebrow reveal">Namaste 🙏 I'm</span>
            <h1 class="reveal" style="transition-delay: .05s">Shruti Sharma</h1>
            <p class="reveal" style="transition-delay: .1s">Content Creator | Pahadi Girl | Nature & Culture Lover</p>
            <p class="tagline reveal" style="transition-delay: .15s">“Perform your duty with devotion and detachment — Bhagavad Gita”</p>
            <div class="cta-row reveal" style="transition-delay: .2s">
              <a href="about.html" class="btn">More About Me</a>
              <a href="portfolio.html" class="btn secondary">Watch My Work</a>
            </div>
            <div class="metrics reveal" style="transition-delay:.25s">
              <div class="metric"><strong>100+</strong><span>Videos created</span></div>
              <div class="metric"><strong>10k+</strong><span>Community</span></div>
              <div class="metric"><strong>4.9★</strong><span>Audience feedback</span></div>
            </div>
          </div>
          <div class="visual reveal" style="transition-delay: .25s">
            <div class="portrait-wrap">
              <div class="portrait">
                <picture>
                  <source media="(max-width: 980px)" srcset="shruti.jpg" />
                  <img src="shruti.jpeg" alt="Shruti Sharma smiling outdoors" />
                </picture>
                <span class="glow"></span>
              </div>
              <span class="orb o1"></span>
              <span class="orb o2"></span>
              <span class="orb o3"></span>
            </div>
          </div>
        </div>
      </section>
    </main>

    <!-- Footer -->
    <footer>
      <div class="container footer-row">
        <div class="brand">
          <span class="logo" aria-hidden="true"></span>
          <span>Shruti Sharma</span>
        </div>
        <p class="muted">© <?php echo date('Y'); ?> Shruti Sharma. All rights reserved.</p>
        <div style="display:flex; gap: 10px;">
          <a class="btn secondary" href="portfolio.html" style="height:36px; padding: 0 12px;">Portfolio</a>
          <a class="btn" href="contact.html" style="height:36px; padding: 0 12px;">Contact</a>
        </div>
      </div>
    </footer>

    <script>
      // Loader hide after DOM ready or timeout fallback
      const loader = document.getElementById('loader');
      const hideLoader = () => loader && loader.classList.add('hidden');
      window.addEventListener('load', hideLoader);
      setTimeout(hideLoader, 1200);

      // Mobile nav toggle
      const navToggle = document.getElementById('navToggle');
      const mobileMenu = document.getElementById('mobileMenu');
      navToggle?.addEventListener('click', () => {
        mobileMenu?.classList.toggle('open');
      });

      // Reveal on scroll
      const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
          if (entry.isIntersecting) {
            entry.target.classList.add('show');
            observer.unobserve(entry.target);
          }
        });
      }, { threshold: 0.12 });
      document.querySelectorAll('.reveal').forEach((el) => observer.observe(el));

      // Theme toggle with persistence
      (function initTheme() {
        const stored = localStorage.getItem('theme');
        const prefersLight = window.matchMedia && window.matchMedia('(prefers-color-scheme: light)').matches;
        const html = document.documentElement;
        const current = stored || (prefersLight ? 'light' : 'dark');
        html.classList.toggle('light', current === 'light');
        updateToggleIcon(current);
      })();

      function updateToggleIcon(theme) {
        const showSun = theme === 'light';
        const sun = document.getElementById('sun');
        const moon = document.getElementById('moon');
        if (sun && moon) {
          sun.style.display = showSun ? 'block' : 'none';
          moon.style.display = showSun ? 'none' : 'block';
        }
      }

      document.getElementById('themeToggle')?.addEventListener('click', () => {
        const html = document.documentElement;
        const isLight = html.classList.toggle('light');
        const theme = isLight ? 'light' : 'dark';
        localStorage.setItem('theme', theme);
        updateToggleIcon(theme);
      });
    </script>
  </body>
</html>
