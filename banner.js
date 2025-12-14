(() => {
  const now = new Date();
  const m = now.getMonth(); // 0=Jan, 11=Dec
  const d = now.getDate();
  let event = null;
  if (m === 11 && d >= 14 && d <= 16) {
    event = { id: 'birthday', title: 'Happy Birthday', emoji: '🎂', note: 'Celebrating 14 Dec' };
  } else if (m === 11 && d === 24) {
    event = { id: 'christmas_eve', title: 'Christmas Eve', emoji: '🕯️', note: '24 Dec' };
  } else if (m === 11 && (d === 23 || d === 25)) {
    event = { id: 'christmas', title: 'Merry Christmas', emoji: '✨', note: d === 23 ? '23 Dec' : '25 Dec' };
  } else if (m === 0 && d === 1) {
    event = { id: 'newyear', title: 'Happy New Year', emoji: '✨', note: '1 Jan' };
  }
  if (!event) return;
  try {
    const key = `themeBannerDismissed_${event.id}`;
    if (sessionStorage.getItem(key) === '1') return;
  } catch {}
  const style = document.createElement('style');
  style.textContent = `
    .theme-banner{position:fixed;left:50%;top:14px;transform:translateX(-50%);z-index:1000;
      background: linear-gradient(135deg, rgba(124,58,237,.9), rgba(34,211,238,.85));
      color:#fff;border:1px solid rgba(255,255,255,.28);box-shadow:0 14px 36px rgba(0,0,0,.25);
      border-radius:18px;padding:10px 16px;display:flex;align-items:center;gap:10px;
      backdrop-filter: blur(6px);animation: slideIn .5s ease-out}
    .theme-banner .emoji{font-size:22px}
    .theme-banner .text{font-weight:800;letter-spacing:.2px}
    .theme-banner .note{opacity:.9;font-weight:600;margin-left:6px}
    .theme-banner .close{margin-left:12px;background:transparent;border:0;color:#fff;
      font-size:16px;cursor:pointer;opacity:.9}
    @keyframes slideIn{0%{opacity:0;transform:translate(-50%,-10px)}100%{opacity:1;transform:translate(-50%,0)}}
    @media (max-width:640px){.theme-banner{left:8px;right:8px;transform:none}}
    .event-corner{position:fixed;z-index:900;pointer-events:auto;opacity:.95;filter: drop-shadow(0 4px 10px rgba(0,0,0,.25));transition:transform .2s ease}
    .event-corner canvas{display:block}
    .corner-tl{top:8px;left:8px}
    .corner-tr{top:8px;right:8px}
    .corner-bl{bottom:8px;left:8px}
    .corner-br{bottom:8px;right:8px}
    .lottie-corner{position:fixed;z-index:900;pointer-events:auto;opacity:.88;filter: drop-shadow(0 3px 8px rgba(0,0,0,.22));transition:opacity .18s ease}
    .lottie-br{bottom:calc(10px + env(safe-area-inset-bottom, 0px));right:calc(10px + env(safe-area-inset-right, 0px))}
    .lottie-bl{bottom:calc(10px + env(safe-area-inset-bottom, 0px));left:calc(10px + env(safe-area-inset-left, 0px))}
    .lottie-tr{top:calc(10px + env(safe-area-inset-top, 0px));right:calc(10px + env(safe-area-inset-right, 0px))}
    .lottie-tl{top:calc(10px + env(safe-area-inset-top, 0px));left:calc(10px + env(safe-area-inset-left, 0px))}
    .lottie-corner:hover{opacity:.95}
    .event-dialog{position:fixed;inset:0;z-index:1100;background:rgba(10,12,16,.45);backdrop-filter:blur(10px);display:none;align-items:center;justify-content:center}
    .event-dialog.open{display:flex}
    .event-card{background:linear-gradient(135deg, rgba(124,58,237,.95), rgba(34,211,238,.92));color:#fff;border:1px solid rgba(255,255,255,.25);box-shadow:0 20px 60px rgba(0,0,0,.4);border-radius:18px;max-width:640px;width:min(92vw,640px);padding:18px 18px 14px}
    .event-card-header{display:flex;align-items:center;justify-content:space-between;gap:10px;margin-bottom:8px}
    .event-title{display:flex;align-items:center;gap:10px;font-weight:900;letter-spacing:.2px}
    .event-body{opacity:.95;font-weight:600}
    .event-close{background:rgba(0,0,0,.3);color:#fff;border:0;width:36px;height:36px;border-radius:12px;cursor:pointer}
  `;
  const banner = document.createElement('div');
  banner.className = 'theme-banner';
  banner.innerHTML = `
    <span class="emoji">${event.emoji}</span>
    <span class="text">${event.title}</span>
    <span class="note">${event.note}</span>
    <button class="close" aria-label="Close">✕</button>
  `;
  banner.querySelector('.close')?.addEventListener('click', () => {
    try { sessionStorage.setItem(`themeBannerDismissed_${event.id}`, '1'); } catch {}
    banner.remove();
    document.querySelectorAll('.event-corner,.lottie-corner').forEach(el => el.remove());
  });
  document.addEventListener('DOMContentLoaded', () => {
    document.body.appendChild(style);
    if (isTestAll()) {
      if (!event) event = { id:'preview', title:'Events Preview', emoji:'✨', note:'Showing all animations' };
    }
    if (isTestAll()) {
      setupAllCorners();
    } else {
      setupCorner(event);
    }
  });
  function isTestAll(){
    try {
      const params = new URLSearchParams(window.location.search);
      return params.get('events') === 'all' || params.get('test_events') === '1';
    } catch { return false; }
  }
  function setupCorner(ev){
    const script = document.createElement('script');
    script.src = 'https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js';
    script.onload = ()=>createLottieCorner(ev);
    script.onerror = ()=>createCanvasCorner(ev);
    document.head.appendChild(script);
    // Fallback if lottie fails to initialize quickly
    setTimeout(()=>{ if (!window.customElements?.get?.('lottie-player')) createCanvasCorner(ev); }, 1500);
  }
  function setupAllCorners(){
    const script = document.createElement('script');
    script.src = 'https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js';
    script.onload = ()=>{
      const evs = [
        { id:'birthday', title:'Happy Birthday', emoji:'🎂', note:'14–16 Dec' },
        { id:'christmas_eve', title:'Christmas Eve', emoji:'🕯️', note:'24 Dec' },
        { id:'christmas', title:'Merry Christmas', emoji:'�', note:'25 Dec' },
        { id:'newyear', title:'Happy New Year', emoji:'✨', note:'1 Jan' }
      ];
      const positions = ['br','bl','tr','tl'];
      evs.forEach((ev,i)=>createLottieCornerAt(ev, positions[i] || 'br'));
    };
    script.onerror = ()=>{
      const evs = [
        { id:'birthday', title:'Happy Birthday', emoji:'🎂', note:'14–16 Dec' },
        { id:'christmas_eve', title:'Christmas Eve', emoji:'🕯️', note:'24 Dec' },
        { id:'christmas', title:'Merry Christmas', emoji:'�', note:'25 Dec' },
        { id:'newyear', title:'Happy New Year', emoji:'✨', note:'1 Jan' }
      ];
      const positions = ['br','bl','tr','tl'];
      evs.forEach((ev,i)=>createCanvasCornerAt(ev, positions[i] || 'br'));
    };
    document.head.appendChild(script);
    setTimeout(()=>{ if (!window.customElements?.get?.('lottie-player')) {
      const evs = [
        { id:'birthday', title:'Happy Birthday', emoji:'🎂', note:'14–16 Dec' },
        { id:'christmas_eve', title:'Christmas Eve', emoji:'🕯️', note:'24 Dec' },
        { id:'christmas', title:'Merry Christmas', emoji:'�', note:'25 Dec' },
        { id:'newyear', title:'Happy New Year', emoji:'✨', note:'1 Jan' }
      ];
      const positions = ['br','bl','tr','tl'];
      evs.forEach((ev,i)=>createCanvasCornerAt(ev, positions[i] || 'br'));
    } }, 1500);
  }
  function lottieUrlForEvent(id){
    switch(id){
      case 'birthday': return 'anim/Happy Birthday Greeting.json';
      case 'christmas_eve': return 'anim/Christmas Sleigh.json';
      case 'christmas': return 'anim/Christmas Tree.json';
      case 'newyear': return 'anim/Christmas Sleigh.json';
      default: return 'anim/Christmas Tree.json';
    }
  }
  function cornerSize(mult=1){
    const w = window.innerWidth || 360;
    const h = window.innerHeight || 640;
    const base = Math.round((w + h) / 36); // responsive base
    const clamped = Math.max(72, Math.min(132, base));
    return Math.round(clamped * mult);
  }
  function createLottieCorner(ev){
    const src = lottieUrlForEvent(ev.id);
    const player = document.createElement('lottie-player');
    player.setAttribute('src', src);
    player.setAttribute('background','transparent');
    player.setAttribute('speed','1');
    player.setAttribute('loop','');
    player.setAttribute('autoplay','');
    player.className = 'lottie-corner lottie-br';
    const size = cornerSize(1);
    player.style.width = size+'px';
    player.style.height = size+'px';
    player.title = ev.title;
    player.addEventListener('click', ()=>openEventDialog(ev));
    document.body.appendChild(player);
  }
  function createLottieCornerAt(ev, pos){
    const src = lottieUrlForEvent(ev.id);
    const player = document.createElement('lottie-player');
    player.setAttribute('src', src);
    player.setAttribute('background','transparent');
    player.setAttribute('speed','1');
    player.setAttribute('loop','');
    player.setAttribute('autoplay','');
    player.className = `lottie-corner lottie-${pos}`;
    const size = cornerSize(0.9);
    player.style.width = size+'px';
    player.style.height = size+'px';
    player.title = ev.title;
    player.addEventListener('click', ()=>openEventDialog(ev));
    document.body.appendChild(player);
  }
  function createCanvasCorner(ev){
    const size = cornerSize(1);
    const dpr = Math.min(2, window.devicePixelRatio || 1);
    const palette = pickPalette(ev.id);
    const wrap = document.createElement('div');
    wrap.className = 'event-corner corner-br';
    wrap.style.bottom = '10px';
    wrap.style.right = '10px';
    const cvs = document.createElement('canvas');
    cvs.width = Math.floor(size*dpr);
    cvs.height = Math.floor(size*dpr);
    cvs.style.width = `${size}px`;
    cvs.style.height = `${size}px`;
    wrap.appendChild(cvs);
    wrap.title = ev.title;
    wrap.addEventListener('click', ()=>openEventDialog(ev));
    document.body.appendChild(wrap);
    startConfetti(cvs, palette);
  }
  function createCanvasCornerAt(ev, pos){
    const size = cornerSize(0.9);
    const dpr = Math.min(2, window.devicePixelRatio || 1);
    const palette = pickPalette(ev.id);
    const wrap = document.createElement('div');
    wrap.className = `event-corner corner-${pos}`;
    const cvs = document.createElement('canvas');
    cvs.width = Math.floor(size*dpr);
    cvs.height = Math.floor(size*dpr);
    cvs.style.width = `${size}px`;
    cvs.style.height = `${size}px`;
    wrap.appendChild(cvs);
    wrap.title = ev.title;
    wrap.addEventListener('click', ()=>openEventDialog(ev));
    document.body.appendChild(wrap);
    startConfetti(cvs, palette);
  }
  function openEventDialog(ev){
    const year = new Date().getFullYear();
    const dialog = document.createElement('div');
    dialog.className = 'event-dialog';
    const emoji = ev.emoji || '✨';
    const message = eventDialogMessage(ev, year);
    dialog.innerHTML = `
      <div class="event-card">
        <div class="event-card-header">
          <div class="event-title"><span style="font-size:22px">${emoji}</span><span>${ev.title}</span></div>
          <button class="event-close" aria-label="Close">✕</button>
        </div>
        <div class="event-body">
          <p style="margin:0 0 8px">${message}</p>
          <p style="margin:0;opacity:.9">${ev.note}</p>
        </div>
      </div>
    `;
    document.body.appendChild(dialog);
    setTimeout(()=>dialog.classList.add('open'),10);
    const close = ()=>{ dialog.classList.remove('open'); setTimeout(()=>dialog.remove(),150); };
    dialog.querySelector('.event-close')?.addEventListener('click', close);
    dialog.addEventListener('click', (e)=>{ if (e.target === dialog) close(); });
    document.addEventListener('keydown', function onKey(e){ if(e.key==='Escape'){ close(); document.removeEventListener('keydown', onKey);} });
  }
  function eventDialogMessage(ev, year){
    switch(ev.id){
      case 'birthday': return 'Warm wishes and joyful celebrations! May this birthday bring peace, creativity, and beautiful journeys ahead.';
      case 'christmas_eve': return 'The warmth of Christmas Eve brings light and hope. May your home be filled with peace and joy.';
      case 'christmas': return 'Wishing joy, harmony, and blessings this Christmas season. May peace and love fill every home.';
      case 'newyear': return `Happy New Year ${year}! May the year be filled with growth, positivity, and soulful adventures.`;
      default: return 'Celebrating this special moment with gratitude and happiness.';
    }
  }
  function createCornerAnimations(ev){
    const size = Math.min(180, Math.max(120, Math.floor((window.innerWidth + window.innerHeight)/20)));
    const dpr = Math.min(2, window.devicePixelRatio || 1);
    const palette = pickPalette(ev.id);
    ['tl','tr','bl','br'].forEach(pos=>{
      const wrap = document.createElement('div');
      wrap.className = `event-corner corner-${pos}`;
      const cvs = document.createElement('canvas');
      cvs.width = Math.floor(size*dpr);
      cvs.height = Math.floor(size*dpr);
      cvs.style.width = `${size}px`;
      cvs.style.height = `${size}px`;
      wrap.appendChild(cvs);
      document.body.appendChild(wrap);
      startConfetti(cvs, palette);
    });
    window.addEventListener('resize', ()=>{
      document.querySelectorAll('.event-corner canvas').forEach(c=>{
        const s = Math.min(180, Math.max(120, Math.floor((window.innerWidth + window.innerHeight)/20)));
        const d = Math.min(2, window.devicePixelRatio || 1);
        c.width = Math.floor(s*d);
        c.height = Math.floor(s*d);
        c.style.width = `${s}px`;
        c.style.height = `${s}px`;
      });
    });
  }
  function pickPalette(id){
    switch(id){
      case 'christmas_eve': return ['#ef4444','#16a34a','#ffffff','#9ca3af'];
      case 'christmas': return ['#e11d48','#16a34a','#ffffff','#9ca3af'];
      case 'birthday': return ['#f59e0b','#ef4444','#8b5cf6','#f472b6'];
      case 'newyear': return ['#fbbf24','#22d3ee','#7c3aed','#ffffff'];
      default: return ['#e02f6b','#18293c','#ffa500','#aef1ee'];
    }
  }
  function startConfetti(canvas, colors){
    const ctx = canvas.getContext('2d');
    const w = canvas.width, h = canvas.height;
    const N = 26;
    const parts = new Array(N).fill(0).map(()=>spawn(w,h,colors));
    let raf;
    function tick(){
      ctx.clearRect(0,0,w,h);
      parts.forEach(p=>{
        p.x += p.vx;
        p.y += p.vy;
        p.ry += p.rspd;
        p.vy += 0.02 * (h/160);
        if(p.y > h + 10) Object.assign(p, spawn(w,h,colors));
        drawParticle(ctx,p);
      });
      raf = requestAnimationFrame(tick);
    }
    tick();
    canvas._stop = ()=>cancelAnimationFrame(raf);
  }
  function spawn(w,h,colors){
    const size = rand(4,10);
    return {
      x: rand(-10,w+10),
      y: rand(-h*0.2,-10),
      vx: rand(-0.6,0.6),
      vy: rand(0.6,1.6),
      rs: rand(0,360),
      rspd: rand(-2,2)*0.02,
      ry: 0,
      color: colors[Math.floor(Math.random()*colors.length)],
      shape: ['rect','circle','tri'][Math.floor(Math.random()*3)],
      size
    };
  }
  function drawParticle(ctx,p){
    ctx.save();
    ctx.translate(p.x,p.y);
    ctx.rotate((p.rs + p.ry)*Math.PI/180);
    ctx.fillStyle = p.color;
    switch(p.shape){
      case 'rect':
        ctx.fillRect(-p.size/2,-p.size/2,p.size,p.size);
        break;
      case 'circle':
        ctx.beginPath();
        ctx.arc(0,0,p.size/2,0,Math.PI*2);
        ctx.fill();
        break;
      case 'tri':
        ctx.beginPath();
        ctx.moveTo(0,-p.size/2);
        ctx.lineTo(p.size/2,p.size/2);
        ctx.lineTo(-p.size/2,p.size/2);
        ctx.closePath();
        ctx.fill();
        break;
    }
    ctx.restore();
  }
  function rand(min,max){return Math.random()*(max-min)+min;}
})();
