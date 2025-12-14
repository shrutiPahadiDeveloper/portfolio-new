<?php
require_once __DIR__ . '/logger.php';
logger_log('about', 'INFO', 'About view', [
  'ip' => $_SERVER['REMOTE_ADDR'] ?? null,
  'ua' => $_SERVER['HTTP_USER_AGENT'] ?? null
]);
$raw = isset($_GET['layout']) ? strtolower($_GET['layout']) : '';
$layout = in_array($raw, ['secondery','secondary','modern'], true) ? 'modern' : 'legacy';
$siteName = 'Shruti Sharma';
$personName = 'Shruti Sharma';
$heroTagline = 'Pahadi Girl from Himachal Pradesh — traveler, photographer and content creator rooted in nature and culture.';
$quote = '“Perform your duty with devotion and detachment — Bhagavad Gita”';
$sectionTitle = 'about me';
$bio1 = "I'm a proud Pahadi from Himachal Pradesh, deeply connected to my roots and the beauty of the mountains 🌄. I love travelling, capturing moments through photography 📸, and expressing emotions through modeling and content creation.";
$bio2 = "For me, a smile is the best accessory 😊 — and I believe stories told with simplicity and heart have the power to heal and inspire.";
$values = ['Spirituality & Simplicity','Respect for Nature & Elders','Authenticity & Positivity','Cultural Pride'];
$skills = ['Content Creation','Vlogging','Photography','Video Editing','Script Writing','Voice Over','Public Speaking','Social Media Management','Storytelling','Personal Branding','Spiritual Presentation','Instagram Reels','YouTube Shorts','Content & Media','Personal Development','Cultural Contribution','Basic English Speaking'];
$education = [
  ['date' => '2018 - 2021', 'title' => 'Bachelor of Arts', 'institution' => 'Himachal Pradesh University (HPU)', 'text' => 'I completed my Bachelor of Arts from HPU, where I developed an interest in cultural studies, literature, and communication — which laid the foundation for my journey into content creation and storytelling.'],
  ['date' => '2016 - 2018', 'title' => 'Senior Secondary (12th)', 'institution' => 'Govt. Girls School, Himachal', 'text' => 'Completed my schooling with a focus on arts and languages. This period inspired me to embrace and preserve our pahadi culture through creative mediums.']
];
$experience = [
  ['date' => '2025', 'title' => 'Invited Cultural Contributor – Rashtrapati Nilayam, Hyderabad', 'text' => 'Invited to Rashtrapati Nilayam, Hyderabad, one of the official Presidential Estates, as part of national-level cultural engagement and heritage celebrations. Associated with events reflecting India’s diverse traditions, public cultural participation, and artistic expression under the President of India’s cultural initiatives, including large-scale festivals such as Bharatiya Kala Mahotsav.'],
  ['date' => '2024', 'title' => 'Special Invitee & Award Recipient – Winter Fest, Rashtrapati Niwas, Shimla (Mashobra)', 'text' => 'Invited to the exclusive Winter Fest 2024 hosted at Rashtrapati Niwas, Mashobra, a heritage residence of the Hon’ble President of India. Recognized and awarded for cultural representation and contribution, celebrating Himachal Pradesh’s rich traditions, harmony, and heritage. Participated in a prestigious cultural gathering highlighting Himachali art, values, and spirit at a national level.'],
  ['date' => '2023', 'title' => 'Social Media Collaborator - Various Brands', 'text' => 'Collaborated with local and spiritual brands to create meaningful content that aligns with values of simplicity, awareness, and authenticity.'],
  ['date' => '2022 - Present', 'title' => 'Speaker & Host - Cultural & Social Events', 'text' => 'Invited to speak and host local events across Himachal, promoting awareness, positivity, and connection to roots among youth and communities.'],
  ['date' => '2021 - Present', 'title' => 'Independent Content Creator & Travel Vlogger', 'text' => 'Creating digital content centered around pahadi culture, travel, spirituality, and lifestyle. Built a strong social media presence with relatable and inspiring content across YouTube, Instagram & LinkedIn.']
];
$portraitMain = 'shruti.jpeg';
$portraitMobile = 'shruti.jpg';
$ogImage = 'shruti.jpg';
if ($layout === 'legacy') {
  ?>
  <!DOCTYPE html>
  <html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/svg+xml" href="logos/favicon.svg">
    <link rel="alternate icon" href="logos/favicon.ico" type="image/x-icon">
    <title>About - <?php echo htmlspecialchars($siteName); ?> Portfolio</title>
    <link rel="stylesheet" href="font.css">
    <link rel="stylesheet" href="style.css">
    <meta property="og:title" content="About - <?php echo htmlspecialchars($siteName); ?> Portfolio">
    <meta property="og:image" content="<?php echo htmlspecialchars($ogImage); ?>">
    <meta property="og:description" content="Content Creator | Himachali Soul | Nature Lover">
    <meta property="og:url" content="https://shrutipahadi.com/about">
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
                  <li><a href="about.php" class="nav-item active">About</a></li>
                  <li><a href="portfolio.php" class="nav-item">Portfolio</a></li>
                  <li><a href="videos.php" class="nav-item">Videos</a></li>
                  <li><a href="contact.php" class="nav-item">Contact</a></li>
                </ul>
              </div>
            </nav>
          </div>
        </div>
      </header>
      <section class="about-section sec-padding active" id="about">
        <div class="container">
          <div class="row"><div class="section-title"><h2><?php echo htmlspecialchars($sectionTitle); ?></h2></div></div>
          <div class="row">
            <div class="about-img"><div class="img-box"><img src="<?php echo htmlspecialchars($portraitMobile); ?>" alt="about image"></div></div>
            <div class="about-text">
              <p><?php echo htmlspecialchars($bio1); ?></p>
              <p><?php echo htmlspecialchars($bio2); ?></p>
              <h3>My Values</h3>
              <ul>
                <?php foreach ($values as $v) { echo '<li>'.htmlspecialchars($v).'</li>'; } ?>
              </ul>
              <h3>Skills</h3>
              <div class="skills">
                <?php foreach ($skills as $s) { echo '<div class="skill-item">'.htmlspecialchars($s).'</div>'; } ?>
              </div>
              <div class="about-tabs">
              <button type="button" class="tab-item active" data-target="#experience">Experience</button>  
              <!-- <button type="button" class="tab-item" data-target="#education">Education</button> -->
              </div>
              <div class="tab-content active" id="experience">
                <div class="timeline">
                  <?php foreach ($experience as $x) { echo '<div class="timeline-item"><span class="date">'.htmlspecialchars($x['date']).'</span><h4>'.htmlspecialchars($x['title']).'</h4><p>'.htmlspecialchars($x['text']).'</p></div>'; } ?>
                </div>
              </div>
              <div class="tab-content" id="education">
                <div class="timeline">
                  <?php foreach ($education as $e) { echo '<div class="timeline-item"><span class="date">'.htmlspecialchars($e['date']).'</span><h4>'.htmlspecialchars($e['title']).' - <span>'.htmlspecialchars($e['institution']).'</span></h4><p>'.htmlspecialchars($e['text']).'</p></div>'; } ?>
                </div>
              </div>
              <a href="contact.php" class="btn">Contact me</a>
            </div>
          </div>
        </div>
      </section>
    </div>
    <script src="script.js"></script>
  <script src="banner.js" defer></script>
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
    <link rel="icon" type="image/svg+xml" href="logos/favicon.svg" />
    <link rel="alternate icon" href="logos/favicon.ico" type="image/x-icon" />
    <title>About - Shruti Sharma Portfolio</title>
    <meta name="description" content="About Shruti Sharma — Pahadi Girl | Nature & Culture Lover" />
    <meta property="og:title" content="About - Shruti Sharma Portfolio" />
    <meta property="og:image" content="<?php echo htmlspecialchars($ogImage); ?>" />
    <meta property="og:description" content="Content Creator | Himachali Soul | Nature Lover" />
    <meta property="og:url" content="https://shrutipahadi.com/about.php" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet" />
    <style>
      :root{--bg:#0b0c10;--bg-soft:#0e1016;--panel:#10131a;--text:#e8ecf1;--muted:#a6b0c0;--primary:#7c3aed;--primary-strong:#6d28d9;--accent:#22d3ee;--ring:rgba(124,58,237,.45);--shadow:0 14px 38px rgba(0,0,0,.35);--radius:16px;--radius-lg:24px;--container:1200px}
      html.light{--bg:#f7f8fb;--bg-soft:#fff;--panel:#fff;--text:#0f172a;--muted:#53607a;--primary:#6d28d9;--primary-strong:#5b21b6;--accent:#0891b2;--ring:rgba(93,63,211,.35);--shadow:0 14px 26px rgba(2,6,23,.08)}
      *{box-sizing:border-box}
      body{margin:0;font-family:'Inter',system-ui,-apple-system,Segoe UI,Roboto,Helvetica,Arial;color:var(--text);background:
        radial-gradient(1200px 600px at 85% -10%, rgba(124,58,237,0.15), transparent 60%),
        radial-gradient(900px 500px at -10% 20%, rgba(34,211,238,0.12), transparent 60%),
        linear-gradient(180deg, var(--bg), var(--bg-soft));-webkit-font-smoothing:antialiased;-moz-osx-font-smoothing:grayscale;overflow-x:hidden}
      a{color:inherit;text-decoration:none}
      img{max-width:100%;display:block}
      .container{max-width:var(--container);margin:0 auto;padding:0 clamp(18px,6vw,28px)}
      .page-loader{position:fixed;inset:0;display:grid;place-items:center;background:var(--bg);z-index:100;transition:opacity .4s ease,visibility .4s ease}
      .page-loader.hidden{opacity:0;visibility:hidden}
      .dots{display:inline-flex;gap:10px}
      .dots span{width:10px;height:10px;border-radius:999px;background:var(--text);opacity:.4;animation:bounce 1s infinite ease-in-out}
      .dots span:nth-child(2){animation-delay:.15s}
      .dots span:nth-child(3){animation-delay:.3s}
      @keyframes bounce{0%,80%,100% {transform:scale(0);opacity:.3}40%{transform:scale(1);opacity:1}}
      header{position:sticky;top:0;z-index:50;backdrop-filter:saturate(180%) blur(10px);background:rgba(16,19,26,.6);border-bottom:1px solid rgba(255,255,255,.06)}
      html.light header{background:rgba(255,255,255,.7);border-bottom:1px solid rgba(2,6,23,.08)}
      .nav{height:68px;display:flex;align-items:center;justify-content:space-between}
      .brand{display:inline-flex;align-items:center;gap:10px;font-weight:800;letter-spacing:.2px}
      .logo{width:38px;height:38px;border-radius:12px;background:linear-gradient(135deg,var(--primary),var(--accent));box-shadow:inset 0 0 0 1px rgba(255,255,255,.15),0 8px 18px rgba(124,58,237,.25);  color: #fff; font-size: 20px; font-weight: 700; line-height: 38px; text-align: center; }
      .nav-links{display:flex;align-items:center;gap:22px}
      .nav-links a{color:var(--muted);font-weight:700}
      .nav-links a.active,.nav-links a:hover{color:var(--text)}
      .nav-cta{display:flex;align-items:center;gap:10px}
      .nav-toggle{display:none;width:44px;height:44px;border-radius:12px;border:1px solid rgba(255,255,255,.14);background:transparent;color:var(--text)}
      html.light .nav-toggle{border-color:rgba(2,6,23,.14)}
      .theme-toggle{position:relative;padding-left: 10px;width:44px;height:44px;border-radius:12px;border:1px solid rgba(255,255,255,.14);background:linear-gradient(180deg, rgba(16,19,26,.5), rgba(11,12,16,.6));color:var(--text);cursor:pointer}
      .theme-toggle:focus{outline:2px solid var(--ring);outline-offset:2px}
      html.light .theme-toggle{border-color:rgba(2,6,23,.14);background:linear-gradient(180deg, rgba(255,255,255,.75), rgba(247,248,251,.9))}
      .btn{display:inline-flex;align-items:center;justify-content:center;gap:10px;height:46px;padding:0 18px;border-radius:12px;background:var(--primary);color:#fff;font-weight:800;border:0;cursor:pointer;box-shadow:0 10px 22px rgba(124,58,237,.25);transition:transform .12s ease, box-shadow .12s ease, background .24s ease}
      .btn:hover{transform:translateY(-1px);box-shadow:0 14px 28px rgba(124,58,237,.32);background:var(--primary-strong)}
      .btn.secondary{background:transparent;color:var(--text);border:1px solid rgba(255,255,255,.14);box-shadow:none}
      .btn.secondary:hover{background:rgba(255,255,255,.06)}
      html.light .btn.secondary{border-color:rgba(2,6,23,.14)}
      html.light .btn.secondary:hover{background:rgba(2,6,23,.06)}
      .hero{padding:84px 0 36px;position:relative;overflow:clip}
      .hero-grid{display:grid;grid-template-columns:1.05fr .95fr;align-items:center;gap:40px}
      .eyebrow{display:inline-flex;align-items:center;gap:8px;padding:6px 10px;border-radius:999px;font-weight:700;letter-spacing:.2px;color:var(--accent);background:rgba(34,211,238,.14)}
      h1{font-family:'Playfair Display',serif;font-size:clamp(42px,5.6vw,72px);line-height:1.05;margin:12px 0 8px}
      .hero p{color:var(--muted);font-size:clamp(16px,2.2vw,18px);max-width:60ch;margin:0}
      .cta-row{display:flex;gap:12px;flex-wrap:wrap;margin-top:22px}
      .tagline{font-style:italic;margin-top:8px;margin-bottom:18px;color:var(--muted)}
      .hero-copy{padding-inline:clamp(18px,7vw,28px)}
      .visual{position:relative;display:grid;place-items:center}
      .portrait-wrap{position:relative;padding:16px;border-radius:var(--radius-lg);background:linear-gradient(180deg, rgba(124,58,237,.2), rgba(34,211,238,.2));border:1px solid rgba(255,255,255,.12);box-shadow:var(--shadow);overflow:hidden;aspect-ratio:16/9;display:grid;width:100%;max-width:740px;margin:0 auto}
      .portrait{position:relative;border-radius:20px;overflow:hidden;width:100%;height:100%;transform:translateZ(0)}
      .portrait img{width:100%;height:100%;object-fit:cover;object-position:center;filter:saturate(1.05) contrast(1.02)}
      .glow{position:absolute;inset:-20%;background:radial-gradient(600px 250px at 80% 20%, rgba(255,255,255,.25), transparent 60%);mix-blend-mode:overlay;pointer-events:none}
      .orb{position:absolute;width:70px;height:70px;border-radius:999px;background:radial-gradient(circle at 30% 30%, rgba(255,255,255,.8), rgba(255,255,255,0) 60%), linear-gradient(135deg, var(--primary), var(--accent));filter:blur(.2px);opacity:.85;animation:float 8s ease-in-out infinite;box-shadow:0 12px 30px rgba(124,58,237,.35)}
      .orb.o1{top:-16px;right:-16px;animation-delay:.1s}
      .orb.o2{bottom:-36px;left:-30px;width:90px;height:90px;animation-delay:.6s}
      .orb.o3{bottom:16px;right:-20px;width:56px;height:56px;animation-delay:.3s}
      @keyframes float{0%,100%{transform:translateY(0) translateX(0)}50%{transform:translateY(-12px) translateX(6px)}}
      .reveal{opacity:0;transform:translateY(12px);transition:opacity .6s ease, transform .6s ease}
      .reveal.show{opacity:1;transform:none}
      .details-grid{display:grid;grid-template-columns:1fr 1fr;gap:18px}
      footer{padding:40px 0;color:var(--muted);border-top:1px solid rgba(255,255,255,.08);margin-top:40px}
      .footer-row{display:flex;align-items:center;justify-content:space-between;gap:16px;flex-wrap:wrap}
      .mobile-menu{position:fixed;inset:68px 0 0 0;background:rgba(10,12,16,.94);backdrop-filter:blur(12px);display:none;flex-direction:column;padding:20px;gap:12px;border-top:1px solid rgba(255,255,255,.06);z-index:60}
      .mobile-menu.open{display:flex}
      .mobile-menu a{padding:12px 10px;border-radius:10px;color:var(--text);font-weight:700}
      .mobile-menu a + a{border-top:1px solid rgba(255,255,255,.08)}
      @media(max-width:980px){.nav-links{display:none}.nav-toggle{display:inline-grid;place-items:center}.hero{padding:0px 0 28px}.hero-grid{display:flex;flex-direction:column-reverse;gap:35px;margin-top:60px}.portrait-wrap{aspect-ratio:3/4;max-width:92vw;padding:14px;max-height:64vh}.portrait{height:100%;max-height:100%;aspect-ratio:auto}.details-grid{grid-template-columns:1fr}}
      @media(min-width:981px){.hero-copy{padding-inline:0}}
      @media(max-width:768px){h1{font-size:clamp(34px,7vw,44px)}.btn{height:42px;padding:0 16px}.portrait-wrap{padding:12px;border-radius:18px}.portrait{border-radius:16px;aspect-ratio:3/4}}
      @media(max-width:640px){.nav-cta .btn.secondary{display:none}}
      @media(max-width:480px){.nav{height:60px}.logo{width:32px;height:32px;border-radius:10px}.btn{height:40px;padding:0 14px}.cta-row{gap:10px}.mobile-menu{inset:60px 0 0 0}}
    </style>
  </head>
  <body>
    <div class="page-loader" id="loader" aria-live="polite" aria-label="Loading"><div class="dots" aria-hidden="true"><span></span><span></span><span></span></div></div>
    <header>
      <div class="container nav">
        <a href="index.php" class="brand" aria-label="Home"><span class="logo" aria-hidden="true">S</span><span>Shruti Sharma</span></a>
        <nav class="nav-links" aria-label="Primary">
          <a href="index.php">Home</a>
          <a href="about.php" class="active">About</a>
          <a href="portfolio.php">Portfolio</a>
          <a href="videos.php">Videos</a>
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
        <a href="about.php" class="active">About</a>
        <a href="portfolio.php">Portfolio</a>
        <a href="videos.php">Videos</a>
        <a href="contact.php">Contact</a>
      </nav>
    </header>
    <main>
      <section class="hero container" id="about-hero">
        <div class="hero-grid">
          <div class="hero-copy">
            <span class="eyebrow reveal">About</span>
            <h1 class="reveal" style="transition-delay:.05s"><?php echo htmlspecialchars($personName); ?></h1>
            <p class="reveal" style="transition-delay:.1s"><?php echo htmlspecialchars($heroTagline); ?></p>
            <p class="tagline reveal" style="transition-delay:.15s"><?php echo htmlspecialchars($quote); ?></p>
            <div class="cta-row reveal" style="transition-delay:.2s">
              <a href="portfolio.php" class="btn">Watch My Work</a>
              <a href="contact.php" class="btn secondary">Contact Me</a>
            </div>
          </div>
          <div class="visual reveal" style="transition-delay:.25s">
            <div class="portrait-wrap"><div class="portrait"><picture><source media="(max-width:980px)" srcset="<?php echo htmlspecialchars($portraitMobile); ?>" /><img src="<?php echo htmlspecialchars($portraitMain); ?>" alt="<?php echo htmlspecialchars($personName); ?>" /></picture><span class="glow"></span></div><span class="orb o1"></span><span class="orb o2"></span><span class="orb o3"></span></div>
          </div>
        </div>
      </section>
      <section class="container" id="about-details" style="margin-top:8px">
        <div class="reveal" style="transition-delay:.05s">
          <div style="background:var(--panel);border:1px solid rgb(7 62 215 / 23%);border-radius:16px;padding:18px;margin-bottom:18px">
            <h2 style="font-family:'Playfair Display',serif;margin:0 0 8px">About</h2>
            <p class="muted" style="margin:0 0 10px"><?php echo htmlspecialchars($bio1); ?></p>
            <p class="muted" style="margin:0"><?php echo htmlspecialchars($bio2); ?></p>
          </div>
          <div class="details-grid">
            <div style="background:var(--panel);border:1px solid rgb(7 62 215 / 23%);border-radius:16px;padding:18px">
              <h2 style="font-family:'Playfair Display',serif;margin:0 0 8px">Values</h2>
              <ul style="margin:0;padding-left:18px"><?php foreach ($values as $v) { echo '<li>'.htmlspecialchars($v).'</li>'; } ?></ul>
              <h2 style="font-family:'Playfair Display',serif;margin:16px 0 8px">Skills</h2>
              <div style="display:flex;flex-wrap:wrap;gap:10px"><?php foreach ($skills as $s) { echo '<span class="btn secondary" style="height:32px;padding:0 12px">'.htmlspecialchars($s).'</span>'; } ?></div>
            </div>
            <div style="background:var(--panel);border:1px solid rgb(7 62 215 / 23%);border-radius:16px;padding:18px">
              <h2 style="font-family:'Playfair Display',serif;margin:0 0 8px">Journey</h2>
              <div>
                <?php foreach ($education as $e) { echo '<p><strong>'.htmlspecialchars($e['date']).'</strong><br/>'.htmlspecialchars($e['title']).' — '.htmlspecialchars($e['institution']).'<br/><span class="muted">'.htmlspecialchars($e['text']).'</span></p>'; } ?>
                <?php foreach ($experience as $x) { echo '<p><strong>'.htmlspecialchars($x['date']).'</strong><br/>'.htmlspecialchars($x['title']).'<br/><span class="muted">'.htmlspecialchars($x['text']).'</span></p>'; } ?>
              </div>
              <a href="contact.php" class="btn" style="margin-top:10px">Work With Me</a>
            </div>
          </div>
        </div>
      </section>
    </main>
    <footer>
      <div class="container footer-row">
        <div class="brand"><span class="logo" aria-hidden="true">S</span><span>Shruti Sharma</span></div>
        <p class="muted">© <?php echo date('Y'); ?> Shruti Sharma. All rights reserved.</p>
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
    </script>
    <script src="banner.js" defer></script>
  </body>
</html>
