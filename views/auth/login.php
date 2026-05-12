<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Sign In — BEED Portal</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    html, body { height: 100%; font-family: 'Inter', sans-serif; overflow: hidden; }

    /* Ken Burns */
    @keyframes kenBurns {
      0%   { transform: scale(1.0) translate(0%,0%); }
      25%  { transform: scale(1.06) translate(-1.5%,-1%); }
      50%  { transform: scale(1.1)  translate(1%,-2%); }
      75%  { transform: scale(1.06) translate(2%,0.5%); }
      100% { transform: scale(1.0) translate(0%,0%); }
    }
    #bg-img { animation: kenBurns 22s ease-in-out infinite; transform-origin: center; }

    /* Aurora */
    @keyframes aurora {
      0%,100% { background-position: 0% 50%; }
      50%      { background-position: 100% 50%; }
    }
    #aurora {
      background: linear-gradient(-45deg,rgba(49,46,129,.72),rgba(67,56,202,.62),rgba(30,58,138,.78),rgba(79,70,229,.58),rgba(17,24,68,.82));
      background-size: 400% 400%;
      animation: aurora 14s ease infinite;
    }

    /* Float */
    @keyframes floatY  { 0%,100%{transform:translateY(0)} 50%{transform:translateY(-18px)} }
    @keyframes floatY2 { 0%,100%{transform:translateY(0) rotate(0deg)} 50%{transform:translateY(-12px) rotate(5deg)} }
    .fa { animation: floatY  6s ease-in-out infinite; }
    .fb { animation: floatY  8s ease-in-out infinite reverse; }
    .fc { animation: floatY2 10s ease-in-out infinite; }
    .parallax { transition: translate .12s ease-out; will-change: transform; }

    /* Fade up */
    @keyframes fadeUp { from{opacity:0;transform:translateY(20px)} to{opacity:1;transform:translateY(0)} }
    .fu  { animation: fadeUp .65s ease both; }
    .d1  { animation-delay:.08s } .d2 { animation-delay:.18s }
    .d3  { animation-delay:.28s } .d4 { animation-delay:.38s }

    /* Right panel */
    .panel {
      position: fixed;
      top: 0; right: 0;
      height: 100%;
      width: 420px;
      background: rgba(8,6,40,.86);
      backdrop-filter: blur(28px);
      -webkit-backdrop-filter: blur(28px);
      border-left: 1px solid rgba(129,140,248,.22);
      display: flex;
      flex-direction: column;
      justify-content: center;
      padding: 40px 40px;
      overflow-y: auto;
      z-index: 20;
    }
    @media (max-width: 640px) {
      .panel { width: 100%; border-left: none; padding: 32px 24px; }
    }

    /* Logo shimmer ring */
    @keyframes spin { to { transform: rotate(360deg); } }
    .logo-ring {
      position: absolute; inset: -6px; border-radius: 26px;
      border: 1.5px solid transparent;
      background: linear-gradient(135deg,rgba(99,102,241,.6),rgba(251,191,36,.4),rgba(99,102,241,.6)) border-box;
      -webkit-mask: linear-gradient(#fff 0 0) padding-box, linear-gradient(#fff 0 0);
      mask: linear-gradient(#fff 0 0) padding-box, linear-gradient(#fff 0 0);
      -webkit-mask-composite: destination-out; mask-composite: exclude;
    }

    /* Inputs */
    .inp {
      width: 100%;
      background: rgba(255,255,255,.06);
      border: 1px solid rgba(255,255,255,.14);
      border-radius: 12px;
      padding: 11px 15px;
      font-size: .875rem;
      color: #fff;
      outline: none;
      transition: border-color .2s, background .2s, box-shadow .2s;
    }
    .inp::placeholder { color: rgba(255,255,255,.3); }
    .inp:focus {
      border-color: rgba(129,140,248,.7);
      background: rgba(255,255,255,.09);
      box-shadow: 0 0 0 3px rgba(99,102,241,.18);
    }

    /* Submit */
    .btn-sub {
      width: 100%;
      background: linear-gradient(135deg,#4f46e5,#6366f1);
      border-radius: 12px;
      padding: 12px;
      color: #fff;
      font-weight: 700;
      font-size: .9rem;
      display: flex; align-items: center; justify-content: center; gap: 8px;
      transition: transform .2s, box-shadow .2s;
      border: none;
      cursor: pointer;
    }
    .btn-sub:hover { transform: translateY(-2px); box-shadow: 0 8px 22px rgba(99,102,241,.45); }

    /* Scrollbar */
    ::-webkit-scrollbar { width: 4px; }
    ::-webkit-scrollbar-thumb { background: #4338ca; border-radius: 99px; }

    /* Separator */
    .sep { border: none; border-top: 1px solid rgba(255,255,255,.1); margin: 24px 0; }
  </style>
</head>
<body style="background:#07071a;">

<!-- ── FULL-SCREEN BACKGROUND (left side) ─────────────────────── -->
<div class="fixed inset-0 overflow-hidden" style="z-index:0;">
  <!-- Layer 1: Ken Burns classroom photo -->
  <div class="absolute inset-0 overflow-hidden">
    <img id="bg-img"
      src="https://images.unsplash.com/photo-1580582932707-520aed937b7b?auto=format&fit=crop&w=1920&q=80"
      alt="" aria-hidden="true"
      class="absolute inset-0 w-full h-full object-cover">
  </div>
  <!-- Layer 2: aurora -->
  <div id="aurora" class="absolute inset-0"></div>
  <!-- Layer 3: vignette (stronger on right to blend into panel) -->
  <div class="absolute inset-0" style="background:linear-gradient(to right, rgba(5,4,30,.1) 0%, rgba(5,4,30,.5) 65%, rgba(8,6,40,.86) 100%);"></div>
  <!-- Layer 4: particles -->
  <canvas id="bg-canvas" class="absolute inset-0 w-full h-full" style="opacity:.35;"></canvas>
</div>

<!-- ── FLOATING SHAPES (left side, visible behind panel) ─────── -->
<div class="fixed inset-0 pointer-events-none overflow-hidden" style="z-index:1;">
  <div class="parallax fa" data-speed="0.025" style="position:absolute;top:-50px;left:-50px;width:320px;height:320px;border-radius:50%;background:radial-gradient(circle,rgba(99,102,241,.22),transparent 70%);"></div>
  <div class="parallax fb" data-speed="0.035" style="position:absolute;bottom:10%;left:10%;width:240px;height:240px;border-radius:50%;background:radial-gradient(circle,rgba(59,130,246,.18),transparent 70%);"></div>

  <!-- Book icon -->
  <div class="parallax" data-speed="0.05" style="position:absolute;top:16%;left:6%;">
    <div class="fa w-14 h-14 rounded-2xl flex items-center justify-center" style="background:rgba(99,102,241,.18);border:1px solid rgba(255,255,255,.12);backdrop-filter:blur(4px);">
      <svg class="w-7 h-7 text-indigo-300" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
    </div>
  </div>
  <!-- Pencil -->
  <div class="parallax" data-speed="0.045" style="position:absolute;bottom:22%;left:8%;">
    <div class="fb w-12 h-12 rounded-xl flex items-center justify-center" style="background:rgba(251,191,36,.14);border:1px solid rgba(255,255,255,.12);backdrop-filter:blur(4px);">
      <svg class="w-6 h-6 text-amber-300" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
    </div>
  </div>
  <!-- Star -->
  <div class="parallax" data-speed="0.06" style="position:absolute;top:52%;left:4%;">
    <div class="fc w-11 h-11 rounded-xl flex items-center justify-center" style="background:rgba(236,72,153,.14);border:1px solid rgba(255,255,255,.12);backdrop-filter:blur(4px);">
      <svg class="w-5 h-5 text-pink-300" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
    </div>
  </div>
  <!-- Dots grid -->
  <svg class="absolute inset-0 w-full h-full" style="opacity:.07;" xmlns="http://www.w3.org/2000/svg">
    <defs><pattern id="dots" width="28" height="28" patternUnits="userSpaceOnUse"><circle cx="2" cy="2" r="1.2" fill="white"/></pattern></defs>
    <rect width="100%" height="100%" fill="url(#dots)"/>
  </svg>
</div>

<!-- ── RIGHT PANEL ─────────────────────────────────────────────── -->
<div class="panel fu">

  <!-- ═══ BEED SVG LOGO ══════════════════════════════════════ -->
  <div class="flex flex-col items-center mb-2 fu d1">
    <div class="relative">
      <div class="logo-ring"></div>
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 72 72" width="72" height="72" fill="none">
        <defs>
          <linearGradient id="bg1" x1="0" y1="0" x2="72" y2="72" gradientUnits="userSpaceOnUse">
            <stop stop-color="#3730a3"/>
            <stop offset="1" stop-color="#1e1b4b"/>
          </linearGradient>
        </defs>
        <!-- Badge background -->
        <rect width="72" height="72" rx="18" fill="url(#bg1)"/>
        <!-- Open book pages left -->
        <path d="M13 28 Q13 52 36 52 Q36 52 36 28 Q24 23 13 28 Z" fill="white" fill-opacity=".92"/>
        <!-- Open book pages right -->
        <path d="M59 28 Q59 52 36 52 Q36 52 36 28 Q48 23 59 28 Z" fill="white" fill-opacity=".78"/>
        <!-- Book spine line -->
        <line x1="36" y1="28" x2="36" y2="52" stroke="#3730a3" stroke-width="1.5" stroke-opacity=".6"/>
        <!-- Book text lines left -->
        <line x1="19" y1="34" x2="33" y2="34" stroke="#4f46e5" stroke-width="1.2" stroke-opacity=".45"/>
        <line x1="19" y1="38" x2="33" y2="38" stroke="#4f46e5" stroke-width="1.2" stroke-opacity=".45"/>
        <line x1="19" y1="42" x2="30" y2="42" stroke="#4f46e5" stroke-width="1.2" stroke-opacity=".45"/>
        <!-- Book text lines right -->
        <line x1="39" y1="34" x2="53" y2="34" stroke="#4f46e5" stroke-width="1.2" stroke-opacity=".35"/>
        <line x1="39" y1="38" x2="53" y2="38" stroke="#4f46e5" stroke-width="1.2" stroke-opacity=".35"/>
        <line x1="39" y1="42" x2="50" y2="42" stroke="#4f46e5" stroke-width="1.2" stroke-opacity=".35"/>
        <!-- Graduation cap -->
        <polygon points="36,11 55,20 36,29 17,20" fill="#fbbf24"/>
        <polygon points="36,29 55,20 55,23 36,32 17,23 17,20" fill="#f59e0b"/>
        <!-- Tassel cord -->
        <line x1="55" y1="20" x2="55" y2="30" stroke="#fbbf24" stroke-width="2.5" stroke-linecap="round"/>
        <circle cx="55" cy="32" r="2.8" fill="#fbbf24"/>
        <!-- Cap shine -->
        <ellipse cx="32" cy="18" rx="6" ry="2" fill="white" fill-opacity=".18" transform="rotate(-15,32,18)"/>
      </svg>
    </div>
    <div class="text-center mt-3">
      <p class="text-[22px] font-black text-white tracking-tight leading-none">
        BEED <span style="color:#818cf8;">Portal</span>
      </p>
      <p class="text-[11px] text-indigo-400 mt-1 tracking-widest uppercase font-medium">Plan Smart · Teach Better</p>
    </div>
  </div>

  <hr class="sep fu d2">

  <!-- Form heading -->
  <div class="mb-6 fu d2">
    <h2 class="text-xl font-black text-white">Welcome back 👋</h2>
    <p class="text-xs text-indigo-300 mt-1">Sign in to your student account</p>
  </div>

  <!-- Error banner -->
  <?php if (!empty($error)): ?>
  <div class="mb-5 flex items-start gap-3 rounded-xl px-4 py-3 fu" style="background:rgba(239,68,68,.12);border:1px solid rgba(239,68,68,.28);">
    <svg class="w-4 h-4 text-red-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
    <p class="text-xs text-red-300"><?= htmlspecialchars(
        $error,
        ENT_QUOTES,
        "UTF-8",
    ) ?></p>
  </div>
  <?php endif; ?>

  <!-- Form -->
  <form method="POST" action="<?= url(
      "/login",
  ) ?>" novalidate class="space-y-4 fu d3">
    <div>
      <label for="email" class="block text-[11px] font-bold text-indigo-300 uppercase tracking-wider mb-1.5">Email Address</label>
      <input type="email" id="email" name="email"
        value="<?= htmlspecialchars(
            $_POST["email"] ?? "",
            ENT_QUOTES,
            "UTF-8",
        ) ?>"
        required autocomplete="email"
        class="inp" placeholder="you@example.com">
    </div>
    <div>
      <label for="password" class="block text-[11px] font-bold text-indigo-300 uppercase tracking-wider mb-1.5">Password</label>
      <input type="password" id="password" name="password"
        required autocomplete="current-password"
        class="inp" placeholder="••••••••">
    </div>
    <button type="submit" class="btn-sub mt-1">
      <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
      Sign In
    </button>
  </form>

  <p class="mt-5 text-center text-xs text-indigo-400 fu d4">
    Don't have an account?
    <a href="<?= url(
        "/register",
    ) ?>" class="text-amber-400 hover:text-amber-300 font-bold transition-colors">Create one →</a>
  </p>
  <p class="mt-2 text-center fu d4">
    <a href="/landing.php" class="text-[11px] text-indigo-600 hover:text-indigo-400 transition-colors">← Back to Home</a>
  </p>

</div><!-- /panel -->

<script>
(function(){
  'use strict';

  // Mouse parallax
  var shapes = document.querySelectorAll('.parallax');
  var mx = 0, my = 0;
  document.addEventListener('mousemove', function(e){
    mx = (e.clientX/window.innerWidth  - 0.5)*2;
    my = (e.clientY/window.innerHeight - 0.5)*2;
  });
  (function para(){
    shapes.forEach(function(el){
      var s = parseFloat(el.getAttribute('data-speed')||'0.04');
      el.style.translate = (mx*s*65)+'px '+(my*s*50)+'px';
    });
    requestAnimationFrame(para);
  })();

  // Particle canvas
  var canvas = document.getElementById('bg-canvas');
  if(canvas){
    var ctx=canvas.getContext('2d'), pts=[], N=40, mouse={x:-999,y:-999};
    function resize(){ canvas.width=canvas.offsetWidth; canvas.height=canvas.offsetHeight; }
    resize(); window.addEventListener('resize',resize);
    document.addEventListener('mousemove',function(e){ var r=canvas.getBoundingClientRect(); mouse.x=e.clientX-r.left; mouse.y=e.clientY-r.top; });
    for(var i=0;i<N;i++) pts.push({x:Math.random()*window.innerWidth,y:Math.random()*window.innerHeight,vx:(Math.random()-.5)*.3,vy:(Math.random()-.5)*.3,r:Math.random()*1.6+.5,a:Math.random()*.4+.15});
    (function draw(){
      ctx.clearRect(0,0,canvas.width,canvas.height);
      for(var a=0;a<pts.length;a++) for(var b=a+1;b<pts.length;b++){
        var ddx=pts[a].x-pts[b].x,ddy=pts[a].y-pts[b].y,d=Math.sqrt(ddx*ddx+ddy*ddy);
        if(d<120){ctx.beginPath();ctx.moveTo(pts[a].x,pts[a].y);ctx.lineTo(pts[b].x,pts[b].y);ctx.strokeStyle='rgba(165,180,252,'+((1-d/120)*.18)+')';ctx.lineWidth=.7;ctx.stroke();}
      }
      pts.forEach(function(p){
        p.x+=p.vx;p.y+=p.vy;
        var ddx=p.x-mouse.x,ddy=p.y-mouse.y,d=Math.sqrt(ddx*ddx+ddy*ddy);
        if(d<70){p.x+=ddx/d*1.1;p.y+=ddy/d*1.1;}
        if(p.x<0||p.x>canvas.width)p.vx*=-1;
        if(p.y<0||p.y>canvas.height)p.vy*=-1;
        ctx.beginPath();ctx.arc(p.x,p.y,p.r,0,Math.PI*2);ctx.fillStyle='rgba(199,210,254,'+p.a+')';ctx.fill();
      });
      requestAnimationFrame(draw);
    })();
  }
})();
</script>
</body>
</html>
