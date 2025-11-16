<?php
require_once __DIR__ . '/logger.php';
logger_log('about', 'INFO', 'About view', [
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
    <title>About - Shruti Sharma Portfolio</title>
    <meta property="og:title" content="About - Shruti Sharma Portfolio" />
    <meta property="og:image" content="shruti.jpg" />
    <meta property="og:description" content="Content Creator | Himachali Soul | Nature Lover" />
    <meta property="og:url" content="https://shrutipahadi.com/about.php" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet" />
    <style>
      :root{--bg:#0b0c10;--bg-soft:#0e1016;--panel:#10131a;--text:#e8ecf1;--muted:#a6b0c0;--primary:#7c3aed;--primary-strong:#6d28d9;--accent:#22d3ee;--ring:rgba(124,58,237,.45);--shadow:0 14px 38px rgba(0,0,0,.35);--radius:16px;--radius-lg:24px;--container:1200px}
      html.light{--bg:#f7f8fb;--bg-soft:#fff;--panel:#fff;--text:#0f172a;--muted:#53607a;--primary:#6d28d9;--primary-strong:#5b21b6;--accent:#0891b2;--ring:rgba(93,63,211,.35);--shadow:0 14px 26px rgba(2,6,23,.08)}
      *{box-sizing:border-box}body{margin:0;font-family:'Inter',system-ui; color:var(--text);background:
        radial-gradient(1200px 600px at 85% -10%, rgba(124,58,237,0.15), transparent 60%),
        radial-gradient(900px 500px at -10% 20%, rgba(34,211,238,0.12), transparent 60%),
        linear-gradient(180deg, var(--bg), var(--bg-soft))}
      a{color:inherit;text-decoration:none}.container{max-width:var(--container);margin:0 auto;padding:0 clamp(18px,6vw,28px)}
      header{position:sticky;top:0;z-index:50;backdrop-filter:blur(10px);background:rgba(16,19,26,.6);border-bottom:1px solid rgba(255,255,255,.06)}
      .nav{height:68px;display:flex;align-items:center;justify-content:space-between}
      .brand{display:inline-flex;align-items:center;gap:10px;font-weight:800}
      .logo{width:38px;height:38px;border-radius:12px;background:linear-gradient(135deg,var(--primary),var(--accent))}
      .nav-links{display:flex;align-items:center;gap:22px}.nav-links a{color:var(--muted);font-weight:700}.nav-links a.active,.nav-links a:hover{color:var(--text)}
      .btn{display:inline-flex;align-items:center;justify-content:center;gap:10px;height:46px;padding:0 18px;border-radius:12px;background:var(--primary);color:#fff;font-weight:800;border:0;cursor:pointer}
      h1,h2,h3{font-family:'Playfair Display',serif}.reveal{opacity:0;transform:translateY(12px);transition:opacity .6s ease,transform .6s ease}.reveal.show{opacity:1;transform:none}
      .grid{display:grid;grid-template-columns:1fr 1fr;gap:28px;margin-top:28px}
      .panel{background:var(--panel);border:1px solid rgba(255,255,255,.12);border-radius:16px;padding:18px}
      ul{margin:0;padding-left:18px}.skills{display:flex;flex-wrap:wrap;gap:10px;margin-top:10px}
      .skill{padding:8px 12px;border-radius:999px;border:1px solid rgba(255,255,255,.14)}
      footer{padding:40px 0;color:var(--muted);border-top:1px solid rgba(255,255,255,.08);margin-top:40px}
      @media(max-width:980px){.grid{grid-template-columns:1fr}.portrait-wrap{aspect-ratio:3/4}}
    </style>
  </head>
  <body>
    <header>
      <div class="container nav">
        <a href="index.php" class="brand"><span class="logo"></span><span>Shruti Sharma</span></a>
        <nav class="nav-links" aria-label="Primary">
          <a href="index.php">Home</a>
          <a href="about.php" class="active">About</a>
          <a href="portfolio.php">Portfolio</a>
          <a href="videos.php">Videos</a>
          <a href="contact.php">Contact</a>
        </nav>
      </div>
    </header>
    <main>
      <section class="container" id="about">
        <div class="reveal"><h1>About me</h1></div>
        <div class="grid">
          <div class="reveal">
            <div class="panel">
              <img src="shruti.jpg" alt="Shruti Sharma">
            </div>
          </div>
          <div class="reveal" style="transition-delay:.1s">
            <div class="panel">
              <p>I'm a proud Pahadi from Himachal Pradesh, deeply connected to my roots and the beauty of the mountains. I love travelling, photography, modeling and content creation.</p>
              <p>A smile is the best accessory — stories told with simplicity and heart have the power to heal and inspire.</p>
              <h3 style="margin-top:12px">My Values</h3>
              <ul>
                <li>Spirituality & Simplicity</li>
                <li>Respect for Nature & Elders</li>
                <li>Authenticity & Positivity</li>
                <li>Cultural Pride</li>
              </ul>
              <h3 style="margin-top:12px">Skills</h3>
              <div class="skills">
                <span class="skill">Content Creation</span><span class="skill">Vlogging</span><span class="skill">Photography</span><span class="skill">Video Editing</span>
                <span class="skill">Script Writing</span><span class="skill">Voice Over</span><span class="skill">Public Speaking</span><span class="skill">Social Media</span>
                <span class="skill">Storytelling</span><span class="skill">Personal Branding</span><span class="skill">Spiritual Presentation</span><span class="skill">Reels</span>
              </div>
            </div>
          </div>
        </div>
        <div class="grid" style="margin-top:24px">
          <div class="reveal">
            <div class="panel">
              <h3>Education</h3>
              <div>
                <p><strong>2018 - 2021</strong><br/>BA — Himachal Pradesh University (HPU)</p>
                <p><strong>2016 - 2018</strong><br/>Senior Secondary — Govt. Girls School, Himachal</p>
              </div>
            </div>
          </div>
          <div class="reveal" style="transition-delay:.1s">
            <div class="panel">
              <h3>Experience</h3>
              <p><strong>2021 - Present</strong><br/>Independent Content Creator & Travel Vlogger</p>
              <p><strong>2022 - Present</strong><br/>Speaker & Host — Cultural & Social Events</p>
              <p><strong>2023</strong><br/>Social Media Collaborator — Various Brands</p>
              <a href="contact.php" class="btn" style="margin-top:10px">Contact me</a>
            </div>
          </div>
        </div>
      </section>
    </main>
    <footer>
      <div class="container" style="display:flex;align-items:center;justify-content:space-between;gap:16px;flex-wrap:wrap">
        <div class="brand"><span class="logo"></span><span>Shruti Sharma</span></div>
        <p class="muted">© <?php echo date('Y'); ?> Shruti Sharma. All rights reserved.</p>
        <div style="display:flex;gap:10px"><a class="btn" href="portfolio.php" style="height:36px;padding:0 12px">Portfolio</a><a class="btn" href="contact.php" style="height:36px;padding:0 12px">Contact</a></div>
      </div>
    </footer>
    <script>
      const observer=new IntersectionObserver((entries)=>{entries.forEach((e)=>{if(e.isIntersecting){e.target.classList.add('show');observer.unobserve(e.target);}})},{threshold:.12});
      document.querySelectorAll('.reveal').forEach((el)=>observer.observe(el));
    </script>
  </body>
</html>