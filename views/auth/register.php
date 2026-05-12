<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Create Account — BEED Portal</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    html, body { height: 100%; font-family: 'Inter', sans-serif; }

    @keyframes kenBurns {
      0%   { transform: scale(1.0) translate(0%,0%); }
      25%  { transform: scale(1.06) translate(-1.5%,-1%); }
      50%  { transform: scale(1.1)  translate(1%,-2%); }
      75%  { transform: scale(1.06) translate(2%,0.5%); }
      100% { transform: scale(1.0) translate(0%,0%); }
    }
    #bg-img { animation: kenBurns 22s ease-in-out infinite; transform-origin: center; }

    @keyframes aurora {
      0%,100% { background-position: 0% 50%; }
      50%      { background-position: 100% 50%; }
    }
    #aurora {
      background: linear-gradient(-45deg,rgba(49,46,129,.72),rgba(67,56,202,.62),rgba(30,58,138,.78),rgba(79,70,229,.58),rgba(17,24,68,.82));
      background-size: 400% 400%;
      animation: aurora 14s ease infinite;
    }

    @keyframes floatY  { 0%,100%{transform:translateY(0)} 50%{transform:translateY(-18px)} }
    @keyframes floatY2 { 0%,100%{transform:translateY(0) rotate(0deg)} 50%{transform:translateY(-12px) rotate(5deg)} }
    .fa { animation: floatY  6s ease-in-out infinite; }
    .fb { animation: floatY  8s ease-in-out infinite reverse; }
    .fc { animation: floatY2 10s ease-in-out infinite; }
    .parallax { transition: translate .12s ease-out; will-change: transform; }

    @keyframes fadeUp { from{opacity:0;transform:translateY(20px)} to{opacity:1;transform:translateY(0)} }
    .fu  { animation: fadeUp .65s ease both; }
    .d1  { animation-delay:.08s } .d2 { animation-delay:.18s }
    .d3  { animation-delay:.28s } .d4 { animation-delay:.38s }

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
      padding: 32px 40px;
      overflow-y: auto;
      z-index: 20;
    }
    @media (max-width: 640px) {
      .panel { width: 100%; border-left: none; padding: 32px 24px; }
    }

    .logo-ring {
      position: absolute; inset: -6px; border-radius: 26px;
      border: 1.5px solid transparent;
      background: linear-gradient(135deg,rgba(99,102,241,.6),rgba(251,191,36,.4),rgba(99,102,241,.6)) border-box;
      -webkit-mask: linear-gradient(#fff 0 0) padding-box, linear-gradient(#fff 0 0);
      mask: linear-gradient(#fff 0 0) padding-box, linear-gradient(#fff 0 0);
      -webkit-mask-composite: destination-out; mask-composite: exclude;
    }

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
    .inp.err { border-color: rgba(248,113,113,.6); }

    .btn-sub {
      width: 100%;
      background: linear-gradient(135deg,#fbbf24,#f59e0b);
      border-radius: 12px;
      padding: 12px;
      color: #1e1b4b;
      font-weight: 800;
      font-size: .9rem;
      display: flex; align-items: center; justify-content: center; gap: 8px;
      transition: transform .2s, box-shadow .2s;
      border: none;
      cursor: pointer;
    }
    .btn-sub:hover { transform: translateY(-2px); box-shadow: 0 8px 22px rgba(251,191,36,.4); }

    ::-webkit-scrollbar { width: 4px; }
    ::-webkit-scrollbar-thumb { background: #4338ca; border-radius: 99px; }
    .sep { border: none; border-top: 1px solid rgba(255,255,255,.1); margin: 20px 0; }
  </style>
</head>
<body style="background:#07071a;">

<!-- BACKGROUND -->
<div class="fixed inset-0 overflow-hidden" style="z-index:0;">
  <div class="absolute inset-0 overflow-hidden">
    <img id="bg-img"
      src="https://images.unsplash.com/photo-1580582932707-520aed937b7b?auto=format&fit=crop&w=1920&q=80"
      alt="" aria-hidden="true"
      class="absolute inset-0 w-full h-full object-cover">
  </div>
  <div id="aurora" class="absolute inset-0"></div>
  <div class="absolute inset-0" style="background:linear-gradient(to right, rgba(5,4,30,.1) 0%, rgba(5,4,30,.5) 65%, rgba(8,6,40,.86) 100%);"></div>
  <canvas id="bg-canvas" class="absolute inset-0 w-full h-full" style="opacity:.35;"></canvas>
</div>

<!-- FLOATING SHAPES -->
<div class="fixed inset-0 pointer-events-none overflow-hidden" style="z-index:1;">
  <div class="parallax fa" data-speed="0.025" style="position:absolute;top:-50px;right:450px;width:320px;height:320px;border-radius:50%;background:radial-gradient(circle,rgba(99,102,241,.22),transparent 70%);"></div>
  <div class="parallax fb" data-speed="0.04"  style="position:absolute;bottom:5%;left:8%;width:260px;height:260px;border-radius:50%;background:radial-gradient(circle,rgba(59,130,246,.18),transparent 70%);"></div>

  <div class="parallax" data-speed="0.05" style="position:absolute;top:14%;left:7%;">
    <div class="fa w-14 h-14 rounded-2xl flex items-center justify-center" style="background:rgba(20,184,166,.16);border:1px solid rgba(255,255,255,.12);backdrop-filter:blur(4px);">
      <svg class="w-7 h-7 text-teal-300" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 14l9-5-9-5-9 5 9 5z"/><path stroke-linecap="round" stroke-linejoin="round" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/></svg>
    </div>
  </div>
  <div class="parallax" data-speed="0.055" style="position:absolute;bottom:24%;left:5%;">
    <div class="fb w-12 h-12 rounded-xl flex items-center justify-center" style="background:rgba(234,179,8,.14);border:1px solid rgba(255,255,255,.12);backdrop-filter:blur(4px);">
      <svg class="w-6 h-6 text-yellow-300" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>
    </div>
  </div>
  <div class="parallax" data-speed="0.04" style="position:absolute;top:55%;left:3%;">
    <div class="fc w-11 h-11 rounded-xl flex items-center justify-center" style="background:rgba(236,72,153,.14);border:1px solid rgba(255,255,255,.12);backdrop-filter:blur(4px);">
      <svg class="w-5 h-5 text-pink-300" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
    </div>
  </div>
  <svg class="absolute inset-0 w-full h-full" style="opacity:.07;" xmlns="http://www.w3.org/2000/svg">
    <defs><pattern id="dots" width="28" height="28" patternUnits="userSpaceOnUse"><circle cx="2" cy="2" r="1.2" fill="white"/></pattern></defs>
    <rect width="100%" height="100%" fill="url(#dots)"/>
  </svg>
</div>

<!-- RIGHT PANEL -->
<div class="panel fu">

  <!-- BEED SVG LOGO -->
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
        <rect width="72" height="72" rx="18" fill="url(#bg1)"/>
        <path d="M13 28 Q13 52 36 52 Q36 52 36 28 Q24 23 13 28 Z" fill="white" fill-opacity=".92"/>
        <path d="M59 28 Q59 52 36 52 Q36 52 36 28 Q48 23 59 28 Z" fill="white" fill-opacity=".78"/>
        <line x1="36" y1="28" x2="36" y2="52" stroke="#3730a3" stroke-width="1.5" stroke-opacity=".6"/>
        <line x1="19" y1="34" x2="33" y2="34" stroke="#4f46e5" stroke-width="1.2" stroke-opacity=".45"/>
        <line x1="19" y1="38" x2="33" y2="38" stroke="#4f46e5" stroke-width="1.2" stroke-opacity=".45"/>
        <line x1="19" y1="42" x2="30" y2="42" stroke="#4f46e5" stroke-width="1.2" stroke-opacity=".45"/>
        <line x1="39" y1="34" x2="53" y2="34" stroke="#4f46e5" stroke-width="1.2" stroke-opacity=".35"/>
        <line x1="39" y1="38" x2="53" y2="38" stroke="#4f46e5" stroke-width="1.2" stroke-opacity=".35"/>
        <line x1="39" y1="42" x2="50" y2="42" stroke="#4f46e5" stroke-width="1.2" stroke-opacity=".35"/>
        <polygon points="36,11 55,20 36,29 17,20" fill="#fbbf24"/>
        <polygon points="36,29 55,20 55,23 36,32 17,23 17,20" fill="#f59e0b"/>
        <line x1="55" y1="20" x2="55" y2="30" stroke="#fbbf24" stroke-width="2.5" stroke-linecap="round"/>
        <circle cx="55" cy="32" r="2.8" fill="#fbbf24"/>
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

  <div class="mb-5 fu d2">
    <h2 class="text-xl font-black text-white">Create your account 🎓</h2>
    <p class="text-xs text-indigo-300 mt-1">Free for all BEED students</p>
  </div>

  <?php if (!empty($errors["general"])): ?>
  <div class="mb-4 flex items-start gap-3 rounded-xl px-4 py-3 fu" style="background:rgba(239,68,68,.12);border:1px solid rgba(239,68,68,.28);">
    <svg class="w-4 h-4 text-red-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
    <p class="text-xs text-red-300"><?= htmlspecialchars(
        $errors["general"],
        ENT_QUOTES,
        "UTF-8",
    ) ?></p>
  </div>
  <?php endif; ?>

  <form method="POST" action="<?= url(
      "/register",
  ) ?>" novalidate class="space-y-3.5 fu d3">
    <div>
      <label for="full_name" class="block text-[11px] font-bold text-indigo-300 uppercase tracking-wider mb-1.5">Full Name</label>
      <input type="text" id="full_name" name="full_name"
        value="<?= htmlspecialchars(
            $old["full_name"] ?? "",
            ENT_QUOTES,
            "UTF-8",
        ) ?>"
        required autocomplete="name"
        class="inp <?= !empty($errors["full_name"]) ? "err" : "" ?>"
        placeholder="Juan dela Cruz">
      <?php if (!empty($errors["full_name"])): ?>
        <p class="mt-1 text-[11px] text-red-400"><?= htmlspecialchars(
            $errors["full_name"],
            ENT_QUOTES,
            "UTF-8",
        ) ?></p>
      <?php endif; ?>
    </div>
    <div>
      <label for="email" class="block text-[11px] font-bold text-indigo-300 uppercase tracking-wider mb-1.5">Email Address</label>
      <input type="email" id="email" name="email"
        value="<?= htmlspecialchars(
            $old["email"] ?? "",
            ENT_QUOTES,
            "UTF-8",
        ) ?>"
        required autocomplete="email"
        class="inp <?= !empty($errors["email"]) ? "err" : "" ?>"
        placeholder="you@example.com">
      <?php if (!empty($errors["email"])): ?>
        <p class="mt-1 text-[11px] text-red-400"><?= htmlspecialchars(
            $errors["email"],
            ENT_QUOTES,
            "UTF-8",
        ) ?></p>
      <?php endif; ?>
    </div>
    <div>
      <label for="password" class="block text-[11px] font-bold text-indigo-300 uppercase tracking-wider mb-1.5">Password</label>
      <input type="password" id="password" name="password"
        required autocomplete="new-password"
        class="inp <?= !empty($errors["password"]) ? "err" : "" ?>"
        placeholder="At least 8 characters">
      <?php if (!empty($errors["password"])): ?>
        <p class="mt-1 text-[11px] text-red-400"><?= htmlspecialchars(
            $errors["password"],
            ENT_QUOTES,
            "UTF-8",
        ) ?></p>
      <?php endif; ?>
    </div>
    <button type="submit" class="btn-sub">
      <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
      Create Account — It's Free
    </button>
  </form>

  <p class="mt-4 text-center text-xs text-indigo-400 fu d4">
    Already have an account?
    <a href="<?= url(
        "/login",
    ) ?>" class="text-amber-400 hover:text-amber-300 font-bold transition-colors">Sign in →</a>
  </p>
  <p class="mt-2 text-center fu d4">
    <a href="/landing.php" class="text-[11px] text-indigo-600 hover:text-indigo-400 transition-colors">← Back to Home</a>
  </p>

</div>

<script>
(function(){
  'use strict';
  var shapes = document.querySelectorAll('.parallax');
  var mx=0, my=0;
  document.addEventListener('mousemove',function(e){ mx=(e.clientX/window.innerWidth-.5)*2; my=(e.clientY/window.innerHeight-.5)*2; });
  (function para(){ shapes.forEach(function(el){ var s=parseFloat(el.getAttribute('data-speed')||'0.04'); el.style.translate=(mx*s*65)+'px '+(my*s*50)+'px'; }); requestAnimationFrame(para); })();

  var canvas=document.getElementById('bg-canvas');
  if(canvas){
    var ctx=canvas.getContext('2d'),pts=[],N=40,mouse={x:-999,y:-999};
    function resize(){ canvas.width=canvas.offsetWidth; canvas.height=canvas.offsetHeight; }
    resize(); window.addEventListener('resize',resize);
    document.addEventListener('mousemove',function(e){ var r=canvas.getBoundingClientRect(); mouse.x=e.clientX-r.left; mouse.y=e.clientY-r.top; });
    for(var i=0;i<N;i++) pts.push({x:Math.random()*window.innerWidth,y:Math.random()*window.innerHeight,vx:(Math.random()-.5)*.3,vy:(Math.random()-.5)*.3,r:Math.random()*1.6+.5,a:Math.random()*.4+.15});
    (function draw(){
      ctx.clearRect(0,0,canvas.width,canvas.height);
      for(var a=0;a<pts.length;a++) for(var b=a+1;b<pts.length;b++){var ddx=pts[a].x-pts[b].x,ddy=pts[a].y-pts[b].y,d=Math.sqrt(ddx*ddx+ddy*ddy);if(d<120){ctx.beginPath();ctx.moveTo(pts[a].x,pts[a].y);ctx.lineTo(pts[b].x,pts[b].y);ctx.strokeStyle='rgba(165,180,252,'+((1-d/120)*.18)+')';ctx.lineWidth=.7;ctx.stroke();}}
      pts.forEach(function(p){ p.x+=p.vx;p.y+=p.vy; var ddx=p.x-mouse.x,ddy=p.y-mouse.y,d=Math.sqrt(ddx*ddx+ddy*ddy); if(d<70){p.x+=ddx/d*1.1;p.y+=ddy/d*1.1;} if(p.x<0||p.x>canvas.width)p.vx*=-1; if(p.y<0||p.y>canvas.height)p.vy*=-1; ctx.beginPath();ctx.arc(p.x,p.y,p.r,0,Math.PI*2);ctx.fillStyle='rgba(199,210,254,'+p.a+')';ctx.fill(); });
      requestAnimationFrame(draw);
    })();
  }
})();
</script>
</body>
</html>
