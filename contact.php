<?php
require_once __DIR__ . '/logger.php';
require_once __DIR__ . '/config.php';
logger_log('contact', 'INFO', 'Contact view', [
  'ip' => $_SERVER['REMOTE_ADDR'] ?? null,
  'ua' => $_SERVER['HTTP_USER_AGENT'] ?? null
]);
$raw = isset($_GET['layout']) ? strtolower($_GET['layout']) : '';
$layout = in_array($raw, ['secondery','secondary','modern'], true) ? 'modern' : 'legacy';
$siteName = 'Shruti Sharma';
$ogImage = 'shruti.jpg';
$pageTitle = 'Contact - '.$siteName.' Portfolio';
$sectionTitle = 'Contact Us';
$email = 'ask@shrutipahari.com';
$phone = '+91 98 **** ****';
$social = [
  ['href' => 'https://www.facebook.com/shruti.pahadi/', 'label' => 'Facebook'],
  ['href' => 'https://www.youtube.com/@pahadi...007', 'label' => 'YouTube'],
  ['href' => 'https://www.instagram.com/shrutipahari_007/', 'label' => 'Instagram']
];
if ($layout === 'legacy') {
  ?>
  <!DOCTYPE html>
  <html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="logos/favicon.ico" type="image/x-icon">
    <title><?php echo htmlspecialchars($pageTitle); ?></title>
    <link rel="stylesheet" href="font.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    <meta property="og:title" content="<?php echo htmlspecialchars($pageTitle); ?>">
    <meta property="og:image" content="<?php echo htmlspecialchars($ogImage); ?>">
    <meta property="og:description" content="Content Creator | Himachali Soul | Nature Lover">
    <meta property="og:url" content="https://shrutipahadi.com/contact">
    <?php if(defined('RECAPTCHA_ENABLED') && RECAPTCHA_ENABLED): ?><script src="https://www.google.com/recaptcha/api.js" async defer></script><?php endif; ?>
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
                  <li><a href="videos.php" class="nav-item">Videos</a></li>
                  <li><a href="contact.php" class="nav-item active">Contact</a></li>
                </ul>
              </div>
            </nav>
          </div>
        </div>
      </header>
      <section class="contact-section sec-padding active" id="contact">
        <div class="container">
          <div class="row">
            <div class="section-title"><h2><?php echo htmlspecialchars($sectionTitle); ?></h2></div>
          </div>
          <div class="row">
            <div class="contact-form">
              <form class="contact-form-message" id="contactForm" onsubmit="return false;">
                <div class="row">
                  <input type="text" name="honeypot" style="display:none;">
                  <div class="input-group">
                    <input type="text" placeholder="Name" class="input-control" name="name" required>
                  </div>
                  <div class="input-group">
                    <input type="email" placeholder="Email" class="input-control" name="email" required>
                  </div>
                  <div class="input-group">
                    <input type="text" placeholder="Subject" class="input-control" name="subject" required>
                  </div>
                  <div class="input-group">
                    <textarea placeholder="Message" class="input-control" name="message" required></textarea>
                  </div>
                  <?php if(defined('RECAPTCHA_ENABLED') && RECAPTCHA_ENABLED): ?>
                  <div class="input-group">
                    <div class="g-recaptcha" data-sitekey="<?php echo RECAPTCHA_SITE_KEY; ?>"></div>
                  </div>
                  <?php endif; ?>
                  <div class="submit-btn">
                    <button type="submit" class="btn">Send Message</button>
                  </div>
                </div>
              </form>
            </div>
            <div class="contact-info">
              <div class="contact-info-item"><h3>Email</h3><p><?php echo htmlspecialchars($email); ?></p></div>
              <div class="contact-info-item"><h3>Phone</h3><p><?php echo htmlspecialchars($phone); ?></p></div>
              <div class="contact-info-item">
                <h3>Social Handles</h3>
                <div class="social-links">
                  <a href="https://www.facebook.com/shruti.pahadi/" target="_blank"><i class="fab fa-facebook-f"></i></a>
                  <a href="https://www.youtube.com/@pahadi...007" target="_blank"><i class="fab fa-youtube"></i></a>
                  <a href="https://www.instagram.com/shrutipahari_007/" target="_blank"><i class="fab fa-instagram"></i></a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
    </div>
    <script src="script.js"></script>
    <script>
      document.getElementById('contactForm').addEventListener('submit',function(e){e.preventDefault();});
      const recaptchaEnabled = <?php echo (defined('RECAPTCHA_ENABLED') && RECAPTCHA_ENABLED) ? 'true' : 'false'; ?>;
      document.querySelector('#contactForm button[type="submit"]').addEventListener('click',function(e){
        const form=document.getElementById('contactForm');
        const submitBtn=this;
        const token=(recaptchaEnabled && window.grecaptcha)?grecaptcha.getResponse():''; 
        if(recaptchaEnabled && !token){
          const warn=document.createElement('div');warn.style.backgroundColor='#f8d7da';warn.style.color='#721c24';warn.style.padding='10px';warn.style.margin='20px 0';warn.innerHTML='<h2>reCAPTCHA Verification Failed</h2><p>Please complete the reCAPTCHA verification.</p>';
          const formEl=document.querySelector('.contact-form');if(formEl){formEl.insertBefore(warn,formEl.firstChild);setTimeout(()=>{warn.remove();},5000);}
          return;
        }
        submitBtn.disabled=true;submitBtn.textContent='Sending...';
        const formData=new FormData(form);
        if(token && !formData.has('g-recaptcha-response')){formData.append('g-recaptcha-response',token);}
        fetch('contactusapi.php',{method:'POST',body:formData})
          .then(response=>response.text())
          .then(data=>{
            document.querySelector('.contact-form').insertAdjacentHTML('afterbegin',data);
            if(data.includes('Thank you for contacting us')){
              form.reset();
              if(recaptchaEnabled && window.grecaptcha){grecaptcha.reset();}
            }
            setTimeout(()=>{
              const message=document.querySelector('.contact-form div[style*="background-color"]');
              if(message){message.remove();}
            },5000);
          })
          .catch(error=>{
            const errorDiv=document.createElement('div');errorDiv.style.backgroundColor='#f8d7da';errorDiv.style.color='#721c24';errorDiv.style.padding='10px';errorDiv.style.margin='20px 0';errorDiv.innerHTML='<h2>Oops! Something went wrong.</h2><p>Sorry, we were unable to send your message. Please try again later.</p>';
            const formEl=document.querySelector('.contact-form');if(formEl){formEl.insertBefore(errorDiv,formEl.firstChild);setTimeout(()=>{errorDiv.remove();},5000);}
          })
          .finally(()=>{submitBtn.disabled=false;submitBtn.textContent='Send Message';});
      });
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
    <title><?php echo htmlspecialchars($pageTitle); ?></title>
    <meta name="description" content="Reach out for collaborations, projects, and inquiries" />
    <meta property="og:title" content="<?php echo htmlspecialchars($pageTitle); ?>" />
    <meta property="og:image" content="<?php echo htmlspecialchars($ogImage); ?>" />
    <meta property="og:description" content="Content Creator | Himachali Soul | Nature Lover" />
    <meta property="og:url" content="https://shrutipahadi.com/contact" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
    <?php if(defined('RECAPTCHA_ENABLED') && RECAPTCHA_ENABLED): ?><script src="https://www.google.com/recaptcha/api.js" async defer></script><?php endif; ?>
    <style>
      :root{--bg:#0b0c10;--bg-soft:#0e1016;--panel:#10131a;--text:#e8ecf1;--muted:#a6b0c0;--primary:#7c3aed;--primary-strong:#6d28d9;--accent:#22d3ee;--ring:rgba(124,58,237,.45);--shadow:0 14px 38px rgba(0,0,0,.35);--radius:16px;--radius-lg:24px;--container:1100px}
      html.light{--bg:#f7f8fb;--bg-soft:#ffffff;--panel:#ffffff;--text:#0f172a;--muted:#53607a;--primary:#6d28d9;--primary-strong:#5b21b6;--accent:#0891b2;--ring:rgba(93,63,211,.35);--shadow:0 14px 26px rgba(2,6,23,.08)}
      *{box-sizing:border-box}
      body{margin:0;font-family:'Inter',system-ui,-apple-system,Segoe UI,Roboto,Helvetica,Arial;color:var(--text);background:radial-gradient(1200px 600px at 85% -10%,rgba(124,58,237,.15),transparent 60%),radial-gradient(900px 500px at -10% 20%,rgba(34,211,238,.12),transparent 60%),linear-gradient(180deg,var(--bg),var(--bg-soft));-webkit-font-smoothing:antialiased;-moz-osx-font-smoothing:grayscale;overflow-x:hidden}
      a{color:inherit;text-decoration:none}
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
      .logo{width:38px;height:38px;border-radius:12px;background:linear-gradient(135deg,var(--primary),var(--accent));box-shadow:inset 0 0 0 1px rgba(255,255,255,.15),0 8px 18px rgba(124,58,237,.25); color: #fff; font-size: 20px; font-weight: 700; line-height: 38px; text-align: center;}
      .nav-links{display:flex;align-items:center;gap:22px}
      .nav-links a{color:var(--muted);font-weight:600}
      .nav-links a.active,.nav-links a:hover{color:var(--text)}
      .nav-cta{display:flex;align-items:center;gap:10px}
      .nav-toggle{display:none;width:44px;height:44px;border-radius:12px;border:1px solid rgba(255,255,255,.14);background:transparent;color:var(--text)}
      html.light .nav-toggle{border-color:rgba(2,6,23,.14)}
      .theme-toggle{position:relative;padding-left:10px;width:44px;height:44px;border-radius:12px;border:1px solid rgba(255,255,255,.14);background:linear-gradient(180deg,rgba(16,19,26,.5),rgba(11,12,16,.6));color:var(--text);cursor:pointer}
      html.light .theme-toggle{border-color:rgba(2,6,23,.14);background:linear-gradient(180deg,rgba(255,255,255,.75),rgba(247,248,251,.9))}
      .btn{display:inline-flex;align-items:center;justify-content:center;gap:10px;height:46px;padding:0 18px;border-radius:12px;background:var(--primary);color:#fff;font-weight:700;border:0;cursor:pointer;box-shadow:0 10px 22px rgba(124,58,237,.25);transition:transform .12s ease,box-shadow .12s ease,background .24s ease}
      .btn:hover{transform:translateY(-1px);box-shadow:0 14px 28px rgba(124,58,237,.32);background:var(--primary-strong)}
      .btn.secondary{background:transparent;color:var(--text);border:1px solid rgba(255,255,255,.14);box-shadow:none}
      .btn.secondary:hover{background:rgba(255,255,255,.06)}
      html.light .btn.secondary{border-color:rgba(2,6,23,.14)}
      html.light .btn.secondary:hover{background:rgba(2,6,23,.06)}
      .contact{padding:64px 0 24px}
      .contact-grid{display:grid;grid-template-columns:1.05fr .95fr;gap:26px;align-items:start}
      @media(max-width:980px){.nav-links{display:none}.nav-toggle{display:inline-grid;place-items:center}.contact-grid{grid-template-columns:1fr;gap:18px;margin-top:24px}}
      .panel{background:var(--panel);border:1px solid rgba(255,255,255,.12);border-radius:16px;box-shadow:var(--shadow);padding:18px}
      html.light .panel{border-color:rgba(2,6,23,.10)}
      .input-group{display:flex;flex-direction:column;gap:6px;margin-bottom:12px}
      .input-control{width:100%;height:44px;border-radius:12px;border:1px solid rgba(255,255,255,.14);background:transparent;color:var(--text);padding:0 12px;font-weight:600}
      .input-control::placeholder{color:var(--muted)}
      textarea.input-control{min-height:120px;padding:12px}
      html.light .input-control{border-color:rgba(2,6,23,.14)}
      .submit-row{display:flex;justify-content:flex-start}
      .reveal{opacity:0;transform:translateY(12px);transition:opacity .6s ease,transform .6s ease}
      .reveal.show{opacity:1;transform:none}
      .mobile-menu{position:fixed;inset:68px 0 0 0;background:rgba(10,12,16,.94);backdrop-filter:blur(12px);display:none;flex-direction:column;padding:20px;gap:12px;border-top:1px solid rgba(255,255,255,.06);z-index:60}
      .mobile-menu.open{display:flex}
      .mobile-menu a{padding:12px 10px;border-radius:10px;color:var(--text);font-weight:700}
      .mobile-menu a + a{border-top:1px solid rgba(255,255,255,.08)}
      html.light .mobile-menu{background:rgba(255,255,255,.92);border-top-color:rgba(2,6,23,.08)}
      html.light .mobile-menu a + a{border-top-color:rgba(2,6,23,.08)}
      footer{padding:40px 0;color:var(--muted);border-top:1px solid rgba(255,255,255,.08);margin-top:40px}
      .footer-row{display:flex;align-items:center;justify-content:space-between;gap:16px;flex-wrap:wrap}
    </style>
  </head>
  <body>
    <div class="page-loader" id="loader"><div class="dots"><span></span><span></span><span></span></div></div>
    <header>
      <div class="container nav">
        <a href="index.php" class="brand"><span class="logo">S</span><span><?php echo htmlspecialchars($siteName); ?></span></a>
        <nav class="nav-links"><a href="index.php">Home</a><a href="about.php">About</a><a href="portfolio.php">Portfolio</a><a href="videos.php">Videos</a><a href="contact.php" class="active">Contact</a></nav>
        <div class="nav-cta"><a class="btn secondary" href="portfolio.php">Watch Work</a><button class="theme-toggle" id="themeToggle"><svg id="sun" xmlns="http://www.w3.org/200/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="4"></circle><path d="M12 2v2M12 20v2M4.93 4.93l1.41 1.41M17.66 17.66l1.41 1.41M2 12h2M20 12h2M4.93 19.07l1.41-1.41M17.66 6.34l1.41-1.41"></path></svg><svg id="moon" style="display:none" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path></svg></button><button class="nav-toggle" id="navToggle"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="3" x2="21" y1="6" y2="6"/><line x1="3" x2="21" y1="12" y2="12"/><line x1="3" x2="21" y1="18" y2="18"/></svg></button></div>
      </div>
      <nav class="mobile-menu" id="mobileMenu"><a href="index.php">Home</a><a href="about.php">About</a><a href="portfolio.php">Portfolio</a><a href="videos.php">Videos</a><a href="contact.php" class="active">Contact</a></nav>
    </header>
    <main>
      <section class="contact container">
        <h1 class="reveal"><?php echo htmlspecialchars($sectionTitle); ?></h1>
        <div class="contact-grid">
          <div class="panel reveal" style="transition-delay:.05s">
            <form id="contactForm" onsubmit="return false;">
              <input type="text" name="honeypot" style="display:none;">
              <div class="input-group"><input type="text" placeholder="Name" class="input-control" name="name" required></div>
              <div class="input-group"><input type="email" placeholder="Email" class="input-control" name="email" required></div>
              <div class="input-group"><input type="text" placeholder="Subject" class="input-control" name="subject" required></div>
              <div class="input-group"><textarea placeholder="Message" class="input-control" name="message" required></textarea></div>
              <?php if(defined('RECAPTCHA_ENABLED') && RECAPTCHA_ENABLED): ?><div class="input-group"><div class="g-recaptcha" data-sitekey="<?php echo RECAPTCHA_SITE_KEY; ?>"></div></div><?php endif; ?>
              <div class="submit-row"><button type="submit" class="btn">Send Message</button></div>
            </form>
          </div>
          <div class="panel reveal" style="transition-delay:.12s">
            <div class="input-group"><strong>Email</strong><span style="color:var(--muted)"><?php echo htmlspecialchars($email); ?></span></div>
            <div class="input-group"><strong>Phone</strong><span style="color:var(--muted)"><?php echo htmlspecialchars($phone); ?></span></div>
            <div class="input-group"><strong>Social</strong>
              <div style="display:flex;gap:10px;margin-top:6px">
                <a class="btn secondary" href="https://www.facebook.com/shruti.pahadi/" target="_blank">Facebook</a>
                <a class="btn secondary" href="https://www.youtube.com/@pahadi...007" target="_blank">YouTube</a>
                <a class="btn secondary" href="https://www.instagram.com/shrutipahari_007/" target="_blank">Instagram</a>
              </div>
            </div>
          </div>
        </div>
      </section>
    </main>
    <footer>
      <div class="container footer-row"><div class="brand"><span class="logo">S</span><span><?php echo htmlspecialchars($siteName); ?></span></div><p class="muted">© <?php echo date('Y'); ?> <?php echo htmlspecialchars($siteName); ?>. All rights reserved.</p><div style="display:flex;gap:10px"><a class="btn secondary" href="portfolio.php" style="height:36px;padding:0 12px">Portfolio</a><a class="btn" href="contact.php" style="height:36px;padding:0 12px">Contact</a></div></div>
    </footer>
    <script>
      const loader=document.getElementById('loader');const hideLoader=()=>loader&&loader.classList.add('hidden');window.addEventListener('load',hideLoader);setTimeout(hideLoader,1200);
      const navToggle=document.getElementById('navToggle');const mobileMenu=document.getElementById('mobileMenu');navToggle&&navToggle.addEventListener('click',()=>{mobileMenu&&mobileMenu.classList.toggle('open')});
      const observer=new IntersectionObserver((entries)=>{entries.forEach((entry)=>{if(entry.isIntersecting){entry.target.classList.add('show');observer.unobserve(entry.target)}})},{threshold:.12});document.querySelectorAll('.reveal').forEach((el)=>observer.observe(el));
      (function(){const stored=localStorage.getItem('theme');const prefersLight=window.matchMedia&&window.matchMedia('(prefers-color-scheme: light)').matches;const html=document.documentElement;const current=stored||(prefersLight?'light':'dark');html.classList.toggle('light',current==='light');const showSun=current==='light';const sun=document.getElementById('sun');const moon=document.getElementById('moon');if(sun&&moon){sun.style.display=showSun?'block':'none';moon.style.display=showSun?'none':'block'}})();
      document.getElementById('themeToggle')&&document.getElementById('themeToggle').addEventListener('click',()=>{const html=document.documentElement;const isLight=html.classList.toggle('light');const theme=isLight?'light':'dark';localStorage.setItem('theme',theme);const sun=document.getElementById('sun');const moon=document.getElementById('moon');if(sun&&moon){sun.style.display=isLight?'block':'none';moon.style.display=isLight?'none':'block'}});
      document.getElementById('contactForm').addEventListener('submit',function(e){e.preventDefault()});
      const recaptchaEnabled=<?php echo (defined('RECAPTCHA_ENABLED') && RECAPTCHA_ENABLED) ? 'true' : 'false'; ?>;
      document.querySelector('#contactForm button[type="submit"]').addEventListener('click',function(e){
        const form=document.getElementById('contactForm');
        const submitBtn=this;
        const token=(recaptchaEnabled && window.grecaptcha)?grecaptcha.getResponse():''; 
        if(recaptchaEnabled && !token){
          const warn=document.createElement('div');warn.style.backgroundColor='#f8d7da';warn.style.color='#721c24';warn.style.padding='10px';warn.style.margin='20px 0';warn.innerHTML='<h2>reCAPTCHA Verification Failed</h2><p>Please complete the reCAPTCHA verification.</p>';
          const container=document.querySelector('.contact.container');if(container){container.insertBefore(warn,container.firstChild);setTimeout(()=>{warn.remove()},5000);}
          return;
        }
        submitBtn.disabled=true;submitBtn.textContent='Sending...';
        const formData=new FormData(form);
        if(token && !formData.has('g-recaptcha-response')){formData.append('g-recaptcha-response',token);}
        fetch('contactusapi.php',{method:'POST',body:formData})
          .then(response=>response.text())
          .then(data=>{
            document.querySelector('.contact.container').insertAdjacentHTML('afterbegin',data);
            if(data.includes('Thank you for contacting us')){
              form.reset();
              if(recaptchaEnabled && window.grecaptcha){grecaptcha.reset();}
            }
            setTimeout(()=>{
              const message=document.querySelector('.contact.container div[style*="background-color"]');
              if(message){message.remove()}
            },5000)
          })
          .catch(error=>{
            const errorDiv=document.createElement('div');errorDiv.style.backgroundColor='#f8d7da';errorDiv.style.color='#721c24';errorDiv.style.padding='10px';errorDiv.style.margin='20px 0';errorDiv.innerHTML='<h2>Oops! Something went wrong.</h2><p>Sorry, we were unable to send your message. Please try again later.</p>';
            const container=document.querySelector('.contact.container');if(container){container.insertBefore(errorDiv,container.firstChild);setTimeout(()=>{errorDiv.remove()},5000)}
          })
          .finally(()=>{submitBtn.disabled=false;submitBtn.textContent='Send Message'})
      });
    </script>
  </body>
</html>
