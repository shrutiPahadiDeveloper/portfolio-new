<?php
require_once __DIR__ . '/logger.php';
logger_log('portfolio', 'INFO', 'Portfolio view', [
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
    <title>Portfolio - Shruti Sharma Portfolio</title>
    <meta property="og:title" content="Portfolio - Shruti Sharma Portfolio" />
    <meta property="og:image" content="shruti.jpg" />
    <meta property="og:description" content="Content Creator | Himachali Soul | Nature Lover" />
    <meta property="og:url" content="https://shrutipahadi.com/portfolio.php" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet" />
    <style>
      :root{--bg:#0b0c10;--bg-soft:#0e1016;--panel:#10131a;--text:#e8ecf1;--muted:#a6b0c0;--primary:#7c3aed;--accent:#22d3ee;--container:1200px}
      html.light{--bg:#f7f8fb;--bg-soft:#fff;--panel:#fff;--text:#0f172a;--muted:#53607a}
      *{box-sizing:border-box}body{margin:0;font-family:'Inter',system-ui;color:var(--text);background:
        radial-gradient(1200px 600px at 85% -10%, rgba(124,58,237,0.15), transparent 60%),
        radial-gradient(900px 500px at -10% 20%, rgba(34,211,238,0.12), transparent 60%),
        linear-gradient(180deg, var(--bg), var(--bg-soft))}
      a{color:inherit;text-decoration:none}.container{max-width:var(--container);margin:0 auto;padding:0 clamp(18px,6vw,28px)}
      header{position:sticky;top:0;z-index:50;backdrop-filter:blur(10px);background:rgba(16,19,26,.6);border-bottom:1px solid rgba(255,255,255,.06)}
      .nav{height:68px;display:flex;align-items:center;justify-content:space-between}.brand{display:inline-flex;align-items:center;gap:10px;font-weight:800}
      .logo{width:38px;height:38px;border-radius:12px;background:linear-gradient(135deg,var(--primary),var(--accent))}
      .nav-links{display:flex;align-items:center;gap:22px}.nav-links a{color:var(--muted);font-weight:700}.nav-links a.active,.nav-links a:hover{color:var(--text)}
      h1{font-family:'Playfair Display',serif}
      .grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:18px;margin-top:22px}
      .card{background:var(--panel);border:1px solid rgba(255,255,255,.12);border-radius:16px;overflow:hidden}
      .card img{width:100%;height:180px;object-fit:cover}
      .info{padding:14px}.muted{color:var(--muted)}
      .btn{display:inline-flex;align-items:center;justify-content:center;height:40px;padding:0 14px;border-radius:12px;background:var(--primary);color:#fff;font-weight:800;border:0;cursor:pointer}
      footer{padding:40px 0;color:var(--muted);border-top:1px solid rgba(255,255,255,.08);margin-top:40px}
    </style>
  </head>
  <body>
    <header>
      <div class="container nav">
        <a href="index.php" class="brand"><span class="logo"></span><span>Shruti Sharma</span></a>
        <nav class="nav-links" aria-label="Primary">
          <a href="index.php">Home</a>
          <a href="about.php">About</a>
          <a href="portfolio.php" class="active">Portfolio</a>
          <a href="videos.php">Videos</a>
          <a href="contact.php">Contact</a>
        </nav>
      </div>
    </header>
    <main>
      <section class="container" id="portfolio">
        <h1>Recent Work</h1>
        <div class="grid">
          <div class="card">
            <img src="vlog_thumbnail.jpg" alt="Himachali Travel Vlogs">
            <div class="info">
              <h3>Himachali Travel Vlogs</h3>
              <p class="muted">Villages, temples, fairs, trails, and local traditions.</p>
              <a class="btn" href="https://www.youtube.com/@shrutisharma__00" target="_blank">Watch on YouTube</a>
            </div>
          </div>
          <div class="card">
            <img src="cultural_reel.jpg" alt="Cultural & Lifestyle Reels">
            <div class="info">
              <h3>Cultural & Lifestyle Reels</h3>
              <p class="muted">Festivals, pahadi cuisine, routines, spiritual thoughts.</p>
              <a class="btn" href="https://www.instagram.com/shrutipahari_007" target="_blank">See on Instagram</a>
            </div>
          </div>
          <div class="card">
            <img src="nature_shots.jpg" alt="Nature Photography">
            <div class="info">
              <h3>Nature Photography</h3>
              <p class="muted">Peaceful hills, trees, rivers, open skies.</p>
              <a class="btn" href="https://www.instagram.com/shrutipahari_007" target="_blank">Instagram Gallery</a>
            </div>
          </div>
          <div class="card">
            <img src="poetry_reading.jpg" alt="Poetry & Storytelling">
            <div class="info">
              <h3>Poetry & Storytelling</h3>
              <p class="muted">Poems and life stories for peace and connection.</p>
              <a class="btn" href="https://www.instagram.com/shrutipahari_007" target="_blank">Watch Reels</a>
            </div>
          </div>
          <div class="card">
            <img src="cooking_series.jpg" alt="Local Cooking Series">
            <div class="info">
              <h3>Local Cooking Series</h3>
              <p class="muted">Authentic pahadi recipes with local vegetables.</p>
              <a class="btn" href="https://www.youtube.com/@shrutisharma__00" target="_blank">Watch Now</a>
            </div>
          </div>
          <div class="card">
            <img src="spiritual_walks.jpg" alt="Spiritual Walks & Reflections">
            <div class="info">
              <h3>Spiritual Walks & Reflections</h3>
              <p class="muted">Temple visits and Bhagavad Gita learnings.</p>
              <a class="btn" href="https://www.instagram.com/shrutipahari_007" target="_blank">Explore More</a>
            </div>
          </div>
        </div>
      </section>
    </main>
    <footer>
      <div class="container" style="display:flex;align-items:center;justify-content:space-between;gap:16px;flex-wrap:wrap">
        <div class="brand"><span class="logo"></span><span>Shruti Sharma</span></div>
        <p class="muted">© <?php echo date('Y'); ?> Shruti Sharma. All rights reserved.</p>
        <div style="display:flex;gap:10px"><a class="btn" href="videos.php" style="height:36px;padding:0 12px">Videos</a><a class="btn" href="contact.php" style="height:36px;padding:0 12px">Contact</a></div>
      </div>
    </footer>
  </body>
</html>