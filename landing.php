<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>BEED Portal &mdash; Plan Smart. Teach Better.</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          fontFamily: { sans: ['Inter','ui-sans-serif','system-ui'] },
          colors: {
            brand: { 50:'#eff6ff',100:'#dbeafe',600:'#2563eb',700:'#1d4ed8',800:'#1e40af',900:'#1e3a8a' }
          }
        }
      }
    }
  </script>
  <style>
    /* ── Base ─────────────────────────────────────────────── */
    html { scroll-behavior: smooth; }
    body { font-family: 'Inter', sans-serif; }



    /* ── Video BG ─────────────────────────────────────────── */
    /* ── Ken Burns animated hero image ────────────────────── */
    @keyframes kenBurns {
      0%   { transform: scale(1.0) translate(0%, 0%); }
      25%  { transform: scale(1.06) translate(-1.5%, -1%); }
      50%  { transform: scale(1.1)  translate(1%,  -2%); }
      75%  { transform: scale(1.06) translate(2%,  0.5%); }
      100% { transform: scale(1.0)  translate(0%,  0%); }
    }
    @keyframes aurora {
      0%   { background-position: 0%   50%; }
      50%  { background-position: 100% 50%; }
      100% { background-position: 0%   50%; }
    }
    #hero-bg-img {
      animation: kenBurns 22s ease-in-out infinite;
      transform-origin: center center;
    }
    #hero-aurora {
      background: linear-gradient(-45deg,
        rgba(49,46,129,.65),
        rgba(67,56,202,.55),
        rgba(30,58,138,.70),
        rgba(79,70,229,.50),
        rgba(17,24,68,.75));
      background-size: 400% 400%;
      animation: aurora 14s ease infinite;
    }

    /* ── Nav ──────────────────────────────────────────────── */
    .nav-link { position: relative; }
    .nav-link::after {
      content:''; position:absolute; bottom:-2px; left:0;
      width:0; height:2px; background:#818cf8;
      transition: width .25s ease;
    }
    .nav-link:hover::after { width:100%; }
    nav.scrolled {
      background: rgba(15,10,50,0.92) !important;
      backdrop-filter: blur(16px);
      box-shadow: 0 4px 30px rgba(0,0,0,0.3);
    }

    /* ── Gradient text ────────────────────────────────────── */
    .grad-text {
      background: linear-gradient(135deg,#fbbf24 0%,#f97316 50%,#fbbf24 100%);
      background-size: 200% auto;
      -webkit-background-clip: text; -webkit-text-fill-color: transparent;
      background-clip: text;
      animation: shimmer 3s linear infinite;
    }
    @keyframes shimmer { to { background-position: 200% center; } }

    /* ── Floating shapes (parallax layers) ───────────────── */
    .parallax-shape { transition: transform .12s ease-out; will-change: transform; }

    /* ── Float keyframe ───────────────────────────────────── */
    @keyframes floatY {
      0%,100%{ transform: translateY(0); }
      50%    { transform: translateY(-20px); }
    }
    @keyframes floatY2 {
      0%,100%{ transform: translateY(0) rotate(0deg); }
      50%    { transform: translateY(-14px) rotate(6deg); }
    }
    .float-a { animation: floatY 6s ease-in-out infinite; }
    .float-b { animation: floatY 8s ease-in-out infinite reverse; }
    .float-c { animation: floatY2 10s ease-in-out infinite; }

    /* ── Hero reveal ──────────────────────────────────────── */
    @keyframes fadeUp {
      from { opacity:0; transform:translateY(32px); }
      to   { opacity:1; transform:translateY(0); }
    }
    .fade-up { animation: fadeUp .8s ease both; }
    .d1 { animation-delay:.1s; } .d2 { animation-delay:.25s; }
    .d3 { animation-delay:.4s;  } .d4 { animation-delay:.55s; }
    .d5 { animation-delay:.7s;  }

    /* ── Typewriter ───────────────────────────────────────── */
    #typewriter::after {
      content:'|'; animation: blink .7s step-end infinite;
      color: #fbbf24; margin-left:2px;
    }
    @keyframes blink { 0%,100%{opacity:1;} 50%{opacity:0;} }

    /* ── Scroll reveal ────────────────────────────────────── */
    .reveal {
      opacity:0; transform:translateY(36px);
      transition: opacity .7s ease, transform .7s ease;
    }
    .reveal.left  { transform:translateX(-36px); }
    .reveal.right { transform:translateX(36px); }
    .reveal.scale { transform:scale(.93); }
    .reveal.visible { opacity:1; transform:none; }
    .s1{transition-delay:.1s;} .s2{transition-delay:.2s;} .s3{transition-delay:.3s;}

    /* ── 3-D card tilt ────────────────────────────────────── */
    .tilt-card {
      transform-style: preserve-3d;
      transition: transform .08s linear, box-shadow .25s ease;
      will-change: transform;
    }
    .tilt-card:hover { box-shadow: 0 28px 55px rgba(30,64,175,.14); }
    .tilt-inner { transform: translateZ(16px); }

    /* ── Gradient card borders ────────────────────────────── */
    .card-glow::before {
      content:''; position:absolute; inset:0; border-radius:inherit;
      padding:1.5px;
      background: linear-gradient(135deg,rgba(99,102,241,.4),rgba(59,130,246,.1),rgba(99,102,241,.4));
      -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
      mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
      -webkit-mask-composite: destination-out; mask-composite: exclude;
      pointer-events:none;
    }

    /* ── Section badge ────────────────────────────────────── */
    .section-badge {
      display:inline-flex; align-items:center; gap:6px;
      padding:4px 14px; border-radius:99px;
      font-size:.7rem; font-weight:700; letter-spacing:.1em; text-transform:uppercase;
    }

    /* ── CTA button glow ──────────────────────────────────── */
    .btn-primary {
      background: linear-gradient(135deg,#fbbf24,#f59e0b);
      transition: transform .2s ease, box-shadow .2s ease;
    }
    .btn-primary:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 28px rgba(251,191,36,.45);
    }
    .btn-ghost {
      border: 2px solid rgba(255,255,255,.5);
      transition: transform .2s ease, background .2s ease, border-color .2s ease;
    }
    .btn-ghost:hover {
      background: rgba(255,255,255,.12);
      border-color: #fff;
      transform: translateY(-2px);
    }

    /* ── Stats bar ────────────────────────────────────────── */
    .stat-pill {
      background: rgba(255,255,255,.08);
      border: 1px solid rgba(255,255,255,.15);
      backdrop-filter: blur(8px);
    }

    /* ── Number counter ───────────────────────────────────── */
    .counter { font-variant-numeric: tabular-nums; }

    /* ── Gradient section divider ─────────────────────────── */
    .wave-divider { width:100%; overflow:hidden; line-height:0; }
    .wave-divider svg { display:block; }

    /* ── Feature icon ─────────────────────────────────────── */
    .feat-icon {
      width:56px; height:56px; border-radius:16px;
      display:flex; align-items:center; justify-content:center;
      margin-bottom:20px;
      box-shadow: 0 8px 20px -4px var(--icon-shadow,rgba(99,102,241,.4));
    }

    /* ── Star fill ────────────────────────────────────────── */
    .star { color:#fbbf24; }

    /* ── Scrollbar ────────────────────────────────────────── */
    ::-webkit-scrollbar { width:5px; }
    ::-webkit-scrollbar-track { background:transparent; }
    ::-webkit-scrollbar-thumb { background:#4338ca; border-radius:99px; }

    /* ── Mobile menu ──────────────────────────────────────── */
    #mobile-menu {
      max-height: 0; overflow: hidden;
      transition: max-height .4s ease;
    }
    #mobile-menu.open { max-height: 480px; }
  </style>
</head>
<body class="text-gray-800 antialiased bg-white">

<!-- ══════════════════════════════════════════════════════════
     STICKY NAV
══════════════════════════════════════════════════════════ -->
<nav id="main-nav" class="fixed top-0 left-0 right-0 z-50 transition-all duration-300"
     style="background:rgba(15,10,50,0.6); backdrop-filter:blur(12px);">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex items-center justify-between h-16">

      <!-- Logo -->
      <a href="#home" class="flex items-center gap-2.5 text-white font-extrabold text-lg tracking-tight flex-shrink-0">
        <div class="w-9 h-9 bg-gradient-to-br from-indigo-500 to-blue-600 rounded-xl flex items-center justify-center shadow-lg shadow-indigo-500/30">
          <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
          </svg>
        </div>
        BEED <span class="text-indigo-400 ml-1">Portal</span>
      </a>

      <!-- Desktop nav -->
      <div class="hidden md:flex items-center gap-6 text-sm font-medium text-indigo-200">
        <a href="#home"     class="nav-link hover:text-white transition-colors">Home</a>
        <a href="#services" class="nav-link hover:text-white transition-colors">Services</a>
        <a href="#how"      class="nav-link hover:text-white transition-colors">How It Works</a>
        <a href="#events"   class="nav-link hover:text-white transition-colors">Events</a>
        <a href="#contact"  class="nav-link hover:text-white transition-colors">Contact</a>
        <div class="flex items-center gap-2 ml-2">
          <a href="/login"
             class="px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-semibold rounded-lg shadow-sm transition-all hover:shadow-indigo-500/30 hover:shadow-md hover:-translate-y-0.5">
            Sign In
          </a>
          <a href="/register"
             class="px-4 py-2 border border-white/30 text-white hover:bg-white/10 text-sm font-semibold rounded-lg transition-all hover:-translate-y-0.5">
            Register
          </a>
        </div>
      </div>

      <!-- Hamburger -->
      <button id="hamburger-btn" onclick="toggleMenu()" aria-label="Toggle menu" aria-expanded="false"
        class="md:hidden p-2 rounded-lg text-indigo-200 hover:text-white hover:bg-white/10 transition-colors">
        <svg id="icon-open"  class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
        </svg>
        <svg id="icon-close" class="w-6 h-6 hidden" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
        </svg>
      </button>
    </div>
  </div>

  <!-- Mobile menu -->
  <div id="mobile-menu" class="md:hidden border-t border-white/10" style="background:rgba(15,10,50,0.95);">
    <div class="px-4 py-4 flex flex-col gap-1 text-sm font-medium text-indigo-200">
      <a href="#home"     onclick="closeMenu()" class="hover:text-white hover:bg-white/10 rounded-lg px-3 py-2.5 transition-colors">Home</a>
      <a href="#services" onclick="closeMenu()" class="hover:text-white hover:bg-white/10 rounded-lg px-3 py-2.5 transition-colors">Services</a>
      <a href="#how"      onclick="closeMenu()" class="hover:text-white hover:bg-white/10 rounded-lg px-3 py-2.5 transition-colors">How It Works</a>
      <a href="#events"   onclick="closeMenu()" class="hover:text-white hover:bg-white/10 rounded-lg px-3 py-2.5 transition-colors">Events</a>
      <a href="#contact"  onclick="closeMenu()" class="hover:text-white hover:bg-white/10 rounded-lg px-3 py-2.5 transition-colors">Contact</a>
      <div class="flex gap-2 mt-3 pt-3 border-t border-white/10">
        <a href="/login"
           class="flex-1 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white font-semibold rounded-lg text-center transition-colors text-sm">
          Sign In
        </a>
        <a href="/register"
           class="flex-1 py-2.5 border border-white/30 text-white hover:bg-white/10 font-semibold rounded-lg text-center transition-colors text-sm">
          Register
        </a>
      </div>
    </div>
  </div>
</nav>

<!-- ══════════════════════════════════════════════════════════
     HERO — full-screen video loop + mouse parallax
══════════════════════════════════════════════════════════ -->
<section id="home" class="relative min-h-screen flex items-center overflow-hidden">

  <!-- ── Layer 1: Classroom photo (Ken Burns slow zoom/pan) ─── -->
  <!-- Photo: unsplash.com/photos/1580582932707 | CC0 free use -->
  <div class="absolute inset-0 overflow-hidden">
    <img
      id="hero-bg-img"
      src="https://images.unsplash.com/photo-1580582932707-520aed937b7b?auto=format&fit=crop&w=1920&q=80"
      alt=""
      aria-hidden="true"
      class="absolute inset-0 w-full h-full object-cover"
      loading="eager"
    />
  </div>

  <!-- ── Layer 2: Aurora animated colour gradient ────────── -->
  <div id="hero-aurora" class="absolute inset-0"></div>

  <!-- ── Layer 3: Bottom vignette (darkens edges for depth) ── -->
  <div class="absolute inset-0 pointer-events-none"
       style="background:radial-gradient(ellipse at 50% 40%, transparent 30%, rgba(5,4,30,.55) 100%);"></div>

  <!-- ── 4. Particle canvas ────────────────────────────────── -->
  <canvas id="hero-canvas" class="absolute inset-0 w-full h-full opacity-50 pointer-events-none"></canvas>

  <!-- ── 5. Mouse-parallax floating shapes ────────────────── -->
  <div class="absolute inset-0 pointer-events-none overflow-hidden" id="parallax-layer">

    <!-- Shape A — large indigo orb -->
    <div class="parallax-shape float-a" data-speed="0.02"
         style="position:absolute; top:-60px; left:-60px; width:360px; height:360px;
                background:radial-gradient(circle, rgba(99,102,241,.25), transparent 70%);
                border-radius:50%;">
    </div>

    <!-- Shape B — right orb -->
    <div class="parallax-shape float-b" data-speed="0.035"
         style="position:absolute; top:25%; right:-80px; width:300px; height:300px;
                background:radial-gradient(circle, rgba(59,130,246,.2), transparent 70%);
                border-radius:50%;">
    </div>

    <!-- Shape C — bottom orb -->
    <div class="parallax-shape float-c" data-speed="0.025"
         style="position:absolute; bottom:-40px; left:20%; width:280px; height:280px;
                background:radial-gradient(circle, rgba(139,92,246,.18), transparent 70%);
                border-radius:50%;">
    </div>

    <!-- Floating icon — Book -->
    <div class="parallax-shape" data-speed="0.05"
         style="position:absolute; top:18%; left:8%;">
      <div class="w-14 h-14 sm:w-16 sm:h-16 rounded-2xl flex items-center justify-center float-a shadow-lg shadow-indigo-500/30"
           style="background:rgba(99,102,241,.2); border:1px solid rgba(255,255,255,.15); backdrop-filter:blur(4px);">
        <svg class="w-7 h-7 text-indigo-300" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
        </svg>
      </div>
    </div>

    <!-- Floating icon — Pencil -->
    <div class="parallax-shape" data-speed="0.045"
         style="position:absolute; top:60%; right:9%;">
      <div class="w-14 h-14 rounded-2xl flex items-center justify-center float-b shadow-lg shadow-amber-500/20"
           style="background:rgba(251,191,36,.15); border:1px solid rgba(255,255,255,.15); backdrop-filter:blur(4px);">
        <svg class="w-7 h-7 text-amber-300" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
        </svg>
      </div>
    </div>

    <!-- Floating icon — Star -->
    <div class="parallax-shape" data-speed="0.06"
         style="position:absolute; bottom:22%; left:12%;">
      <div class="w-12 h-12 rounded-xl flex items-center justify-center float-c shadow-lg shadow-pink-500/20"
           style="background:rgba(236,72,153,.15); border:1px solid rgba(255,255,255,.15); backdrop-filter:blur(4px);">
        <svg class="w-6 h-6 text-pink-300" fill="currentColor" viewBox="0 0 20 20">
          <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
        </svg>
      </div>
    </div>

    <!-- Floating icon — Graduation cap -->
    <div class="parallax-shape" data-speed="0.03"
         style="position:absolute; top:12%; right:18%;">
      <div class="w-14 h-14 rounded-2xl flex items-center justify-center float-a shadow-lg shadow-teal-500/20"
           style="background:rgba(20,184,166,.15); border:1px solid rgba(255,255,255,.15); backdrop-filter:blur(4px); animation-delay:2s;">
        <svg class="w-7 h-7 text-teal-300" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M12 14l9-5-9-5-9 5 9 5z"/>
          <path stroke-linecap="round" stroke-linejoin="round" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/>
        </svg>
      </div>
    </div>

    <!-- Floating icon — Lightbulb -->
    <div class="parallax-shape" data-speed="0.055"
         style="position:absolute; bottom:30%; right:6%;">
      <div class="w-12 h-12 rounded-xl flex items-center justify-center float-b shadow-lg shadow-yellow-500/20"
           style="background:rgba(234,179,8,.15); border:1px solid rgba(255,255,255,.15); backdrop-filter:blur(4px); animation-delay:4s;">
        <svg class="w-6 h-6 text-yellow-300" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
        </svg>
      </div>
    </div>

    <!-- Dot-grid pattern -->
    <svg class="absolute inset-0 w-full h-full opacity-10" xmlns="http://www.w3.org/2000/svg">
      <defs>
        <pattern id="hero-dots" x="0" y="0" width="28" height="28" patternUnits="userSpaceOnUse">
          <circle cx="2" cy="2" r="1.2" fill="white"/>
        </pattern>
      </defs>
      <rect width="100%" height="100%" fill="url(#hero-dots)"/>
    </svg>
  </div>

  <!-- ── 6. Hero content ────────────────────────────────────── -->
  <div class="relative w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-28 pb-20 text-center">

    <!-- Badge -->
    <div class="fade-up d1 inline-flex items-center gap-2 mb-7 px-4 py-2 rounded-full text-xs font-bold tracking-widest uppercase text-indigo-200"
         style="background:rgba(99,102,241,.18); border:1px solid rgba(99,102,241,.4); backdrop-filter:blur(6px);">
      <span class="w-2 h-2 bg-amber-400 rounded-full animate-pulse flex-shrink-0"></span>
      BEED Student Portal &bull; DepEd Aligned
    </div>

    <!-- Headline -->
    <h1 class="fade-up d2 text-5xl sm:text-6xl md:text-7xl lg:text-8xl font-black text-white leading-[1.05] tracking-tight mb-6">
      Plan Smart.<br>
      <span class="grad-text">Teach Better.</span>
    </h1>

    <!-- Typewriter subtitle -->
    <p class="fade-up d3 text-lg sm:text-xl text-indigo-200 max-w-2xl mx-auto mb-3 leading-relaxed">
      The DepEd-ready teaching toolkit built for BEED students.
    </p>
    <p class="fade-up d3 text-base sm:text-lg text-indigo-300 max-w-xl mx-auto mb-10">
      <span id="typewriter"></span>
    </p>

    <!-- CTA buttons -->
    <div class="fade-up d4 flex flex-col sm:flex-row gap-4 justify-center mb-16">
      <a href="/register"
         class="btn-primary group inline-flex items-center justify-center gap-2.5 px-8 py-4 text-blue-900 font-bold rounded-2xl shadow-xl text-base">
        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
        </svg>
        Get Started Free
        <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
        </svg>
      </a>
      <a href="#services"
         class="btn-ghost inline-flex items-center justify-center gap-2 px-8 py-4 text-white font-semibold rounded-2xl text-base">
        See How It Works
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
        </svg>
      </a>
    </div>

    <!-- Feature trust badges (replaces raw stat pills) -->
    <div class="fade-up d5 flex flex-wrap justify-center gap-3">
      <?php
      $badges = [
          ["✅", "DepEd K-12 Aligned"],
          ["📚", "7 Subjects Covered"],
          ["⚡", "Plans in Minutes"],
          ["🎓", "Grades K–6 Ready"],
      ];
      foreach ($badges as [$icon, $label]): ?>
      <div class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-semibold text-white"
           style="background:rgba(255,255,255,.1); border:1px solid rgba(255,255,255,.18); backdrop-filter:blur(8px);">
        <span><?= $icon ?></span>
        <span><?= $label ?></span>
      </div>
      <?php endforeach;
      ?>
    </div>
  </div>

  <!-- Scroll cue -->
  <div class="absolute bottom-8 left-1/2 -translate-x-1/2 flex flex-col items-center gap-2 text-indigo-300 fade-up d5">
    <span class="text-xs font-medium tracking-widest uppercase">Scroll</span>
    <div class="w-5 h-9 border-2 border-indigo-400/50 rounded-full flex items-start justify-center pt-1">
      <div class="w-1 h-2.5 bg-indigo-400 rounded-full animate-bounce"></div>
    </div>
  </div>
</section>

<!-- Wave divider -->
<div class="wave-divider" style="background:linear-gradient(135deg,rgba(15,10,60,.88),rgba(10,30,80,.88));">
  <svg viewBox="0 0 1440 80" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none">
    <path fill="#f8fafc" d="M0,64 C360,0 1080,80 1440,32 L1440,80 L0,80 Z"/>
  </svg>
</div>

<!-- ══════════════════════════════════════════════════════════
     SERVICES SECTION
══════════════════════════════════════════════════════════ -->
<section id="services" class="bg-slate-50 py-24">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

    <div class="text-center mb-16 reveal">
      <span class="section-badge bg-indigo-100 text-indigo-700 mb-4">
        <span class="w-1.5 h-1.5 bg-indigo-500 rounded-full"></span>
        What We Offer
      </span>
      <h2 class="text-3xl sm:text-4xl font-black text-gray-900 mb-4 mt-3">Everything BEED Students Need</h2>
      <p class="text-gray-500 max-w-xl mx-auto text-base leading-relaxed">
        Purpose-built tools that match exactly what you need during field study and practicum — no fluff, just results.
      </p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">

      <!-- Card 1: Demo Maker -->
      <div class="tilt-card relative bg-white rounded-3xl p-8 shadow-sm border border-slate-100 flex flex-col overflow-hidden card-glow reveal s1">
        <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-blue-500 to-indigo-500"></div>
        <div class="feat-icon bg-gradient-to-br from-blue-500 to-indigo-600" style="--icon-shadow:rgba(99,102,241,.4);">
          <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
          </svg>
        </div>
        <div class="tilt-inner flex flex-col flex-1">
          <h3 class="text-xl font-bold text-gray-900 mb-3">Demo Maker</h3>
          <p class="text-gray-500 leading-relaxed flex-1 text-sm">
            Build step-by-step teaching demonstration plans with subject-specific guided templates. Structure your lessons from motivation to evaluation with ease — exactly the way cooperating teachers expect.
          </p>
          <div class="mt-6 flex flex-wrap gap-2">
            <span class="px-3 py-1 bg-blue-50 text-blue-700 text-xs font-semibold rounded-full">4A's Template</span>
            <span class="px-3 py-1 bg-blue-50 text-blue-700 text-xs font-semibold rounded-full">5E's Template</span>
            <span class="px-3 py-1 bg-blue-50 text-blue-700 text-xs font-semibold rounded-full">Auto-fill</span>
          </div>
        </div>
      </div>

      <!-- Card 2: Lesson Plan Planner (featured) -->
      <div class="tilt-card relative bg-gradient-to-br from-indigo-600 to-blue-700 rounded-3xl p-8 shadow-xl shadow-indigo-300/30 flex flex-col overflow-hidden reveal s2 -mt-0 md:-mt-4">
        <div class="absolute top-4 right-4">
          <span class="px-3 py-1 bg-amber-400 text-amber-900 text-xs font-black rounded-full shadow">⭐ Most Used</span>
        </div>
        <div class="feat-icon" style="background:rgba(255,255,255,.2); --icon-shadow:rgba(255,255,255,.1); border:1px solid rgba(255,255,255,.2);">
          <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
          </svg>
        </div>
        <div class="tilt-inner flex flex-col flex-1">
          <h3 class="text-xl font-bold text-white mb-3">Lesson Plan Planner</h3>
          <p class="text-blue-100 leading-relaxed flex-1 text-sm">
            Create complete DepEd-aligned Detailed Lesson Plans in minutes. Every required section is guided — Subject Matter, Procedure A–F, Evaluation, and Assignment — so nothing gets missed.
          </p>
          <div class="mt-6 flex flex-wrap gap-2">
            <span class="px-3 py-1 bg-white/20 text-white text-xs font-semibold rounded-full">DLP Format</span>
            <span class="px-3 py-1 bg-white/20 text-white text-xs font-semibold rounded-full">7 Subjects</span>
            <span class="px-3 py-1 bg-white/20 text-white text-xs font-semibold rounded-full">Print Ready</span>
          </div>
        </div>
      </div>

      <!-- Card 3: Smart Templates -->
      <div class="tilt-card relative bg-white rounded-3xl p-8 shadow-sm border border-slate-100 flex flex-col overflow-hidden card-glow reveal s3">
        <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-teal-400 to-emerald-500"></div>
        <div class="feat-icon bg-gradient-to-br from-teal-500 to-emerald-600" style="--icon-shadow:rgba(20,184,166,.4);">
          <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
          </svg>
        </div>
        <div class="tilt-inner flex flex-col flex-1">
          <h3 class="text-xl font-bold text-gray-900 mb-3">Smart Templates</h3>
          <p class="text-gray-500 leading-relaxed flex-1 text-sm">
            Save your best plans as personal templates and reuse them instantly for any subject or grade level. Build a library that grows with your teaching career and impresses every supervisor.
          </p>
          <div class="mt-6 flex flex-wrap gap-2">
            <span class="px-3 py-1 bg-teal-50 text-teal-700 text-xs font-semibold rounded-full">Save & Reuse</span>
            <span class="px-3 py-1 bg-teal-50 text-teal-700 text-xs font-semibold rounded-full">Export PDF</span>
            <span class="px-3 py-1 bg-teal-50 text-teal-700 text-xs font-semibold rounded-full">Duplicate</span>
          </div>
        </div>
      </div>

    </div>
  </div>
</section>

<!-- ══════════════════════════════════════════════════════════
     HOW IT WORKS
══════════════════════════════════════════════════════════ -->
<section id="how" class="bg-white py-24">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

    <div class="text-center mb-16 reveal">
      <span class="section-badge bg-blue-100 text-blue-700 mb-4">
        <span class="w-1.5 h-1.5 bg-blue-500 rounded-full"></span>
        Simple Process
      </span>
      <h2 class="text-3xl sm:text-4xl font-black text-gray-900 mb-4 mt-3">Ready in 3 Easy Steps</h2>
      <p class="text-gray-500 max-w-xl mx-auto text-base">From sign-up to a polished, print-ready lesson plan in minutes.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 relative">
      <!-- Connector line -->
      <div class="hidden md:block absolute top-14 left-[calc(16.67%+32px)] right-[calc(16.67%+32px)] h-0.5 bg-gradient-to-r from-indigo-300 via-blue-400 to-teal-300 z-0"></div>

      <?php
      $steps = [
          [
              "1",
              "blue",
              "Register Free",
              'Create your student account in seconds. No credit card needed — just your email and you\'re ready to go.',
              "M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z",
          ],
          [
              "2",
              "indigo",
              "Choose a Template",
              "Pick from subject-specific templates or start from scratch. Every template follows the official DepEd format for DLPs.",
              "M4 6h16M4 10h16M4 14h10",
          ],
          [
              "3",
              "teal",
              "Export & Impress",
              "Print your DepEd-formatted plan and submit with confidence. Your cooperating teacher will be impressed.",
              "M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z",
          ],
      ];
      $stepColors = [
          "blue" => "from-blue-500 to-indigo-600",
          "indigo" => "from-indigo-500 to-violet-600",
          "teal" => "from-teal-500 to-emerald-600",
      ];
      $stepBg = [
          "blue" => "bg-blue-50 border-blue-100",
          "indigo" => "bg-indigo-50 border-indigo-100",
          "teal" => "bg-teal-50 border-teal-100",
      ];
      foreach ($steps as $i => [$num, $color, $title, $desc, $icon]): ?>
      <div class="relative z-10 flex flex-col items-center text-center p-8 rounded-3xl border <?= $stepBg[
          $color
      ] ?> reveal s<?= $i + 1 ?>">
        <div class="w-16 h-16 bg-gradient-to-br <?= $stepColors[
            $color
        ] ?> rounded-2xl flex items-center justify-center mb-5 shadow-lg text-2xl font-black text-white">
          <?= $num ?>
        </div>
        <div class="w-11 h-11 bg-white rounded-xl flex items-center justify-center mb-4 shadow-sm">
          <svg class="w-5 h-5 text-<?= $color ?>-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="<?= $icon ?>"/>
          </svg>
        </div>
        <h3 class="text-lg font-bold text-gray-900 mb-2"><?= $title ?></h3>
        <p class="text-gray-500 text-sm leading-relaxed"><?= $desc ?></p>
      </div>
      <?php endforeach;
      ?>
    </div>
  </div>
</section>

<!-- ══════════════════════════════════════════════════════════
     TEACHER APPRECIATION BANNER
══════════════════════════════════════════════════════════ -->
<section class="py-20 relative overflow-hidden" style="background:linear-gradient(135deg,#312e81 0%,#3730a3 50%,#4338ca 100%);">
  <div class="absolute inset-0 opacity-10">
    <svg class="w-full h-full" xmlns="http://www.w3.org/2000/svg">
      <defs><pattern id="grid" width="40" height="40" patternUnits="userSpaceOnUse">
        <path d="M 40 0 L 0 0 0 40" fill="none" stroke="white" stroke-width="0.5"/>
      </pattern></defs>
      <rect width="100%" height="100%" fill="url(#grid)"/>
    </svg>
  </div>
  <div class="relative max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
    <div class="reveal scale">
      <div class="text-5xl mb-4">👩‍🏫</div>
      <h2 class="text-3xl sm:text-4xl font-black text-white mb-4">
        Made with love for <span class="grad-text">Filipino teachers</span>
      </h2>
      <p class="text-indigo-200 text-lg max-w-2xl mx-auto leading-relaxed mb-8">
        Every field, every template, every button — designed around the real needs of BEED students preparing for their practicum teaching career.
      </p>
      <div class="flex flex-wrap justify-center gap-4 text-sm text-indigo-200">
        <?php
        $perks = [
            "✅ DepEd K-12 Aligned",
            "✅ All 7 Core Subjects",
            "✅ Grades K–6",
            "✅ Print-Ready Format",
            '✅ 4A\'s & 5E\'s Templates',
            "✅ Save & Reuse Plans",
        ];
        foreach ($perks as $perk): ?>
          <span class="px-4 py-2 rounded-full font-medium" style="background:rgba(255,255,255,.1); border:1px solid rgba(255,255,255,.2);"><?= $perk ?></span>
        <?php endforeach;
        ?>
      </div>
    </div>
  </div>
</section>

<!-- testimonials removed -->
<div style="display:none"><div class="grid grid-cols-1 md:grid-cols-3 gap-8">
      <?php
      $testimonials = [
          [
              "M",
              "Maria S.",
              "3rd Year BEED",
              "blue",
              '"The BEED Portal saved me hours during practicum. The templates are exactly what cooperating teachers expect — and my demo got a perfect score!"',
          ],
          [
              "J",
              "Juan D.",
              "4th Year BEED",
              "indigo",
              '"I used to spend 3 hours writing one lesson plan. Now it takes me 20 minutes. This tool is a genuine game changer for every BEED student."',
          ],
          [
              "A",
              "Ana R.",
              "3rd Year BEED",
              "teal",
              '"The export feature prints exactly in the DepEd format my cooperating teacher requires. No more reformatting, no more stress!"',
          ],
      ];
      $tColors = [
          "blue" => [
              "text-blue-700",
              "bg-blue-100",
              "from-blue-500 to-blue-700",
          ],
          "indigo" => [
              "text-indigo-700",
              "bg-indigo-100",
              "from-indigo-500 to-indigo-700",
          ],
          "teal" => [
              "text-teal-700",
              "bg-teal-100",
              "from-teal-500 to-teal-700",
          ],
      ];
      foreach ($testimonials as $i => [$initial, $name, $year, $color, $quote]):
          [$textCls, $bgCls, $gradCls] = $tColors[$color]; ?>
      <div class="tilt-card bg-white rounded-3xl p-8 shadow-sm border border-slate-100 flex flex-col reveal s<?= $i +
          1 ?>">
        <!-- Stars -->
        <div class="flex gap-1 mb-5">
          <?php for ($s = 0; $s < 5; $s++): ?>
          <svg class="w-5 h-5 star" fill="currentColor" viewBox="0 0 20 20">
            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
          </svg>
          <?php endfor; ?>
        </div>
        <!-- Quote icon -->
        <svg class="w-8 h-8 <?= $textCls ?> opacity-30 mb-3" fill="currentColor" viewBox="0 0 24 24">
          <path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z"/>
        </svg>
        <p class="text-gray-700 leading-relaxed flex-1 text-sm italic"><?= $quote ?></p>
        <div class="mt-6 flex items-center gap-3">
          <div class="w-11 h-11 bg-gradient-to-br <?= $gradCls ?> rounded-full flex items-center justify-center text-white font-bold text-sm shadow-md">
            <?= $initial ?>
          </div>
          <div>
            <p class="text-sm font-bold text-gray-900"><?= $name ?></p>
            <p class="text-xs text-gray-500"><?= $year ?></p>
          </div>
        </div>
      </div>
      <?php
      endforeach;
      ?>
    </div></div></div>

<!-- ══════════════════════════════════════════════════════════
     EVENTS
══════════════════════════════════════════════════════════ -->
<section id="events" class="bg-white py-24">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

    <div class="text-center mb-16 reveal">
      <span class="section-badge bg-rose-100 text-rose-700 mb-4">
        <span class="w-1.5 h-1.5 bg-rose-500 rounded-full"></span>
        Mark Your Calendar
      </span>
      <h2 class="text-3xl sm:text-4xl font-black text-gray-900 mb-4 mt-3">Upcoming Events</h2>
      <p class="text-gray-500 max-w-xl mx-auto text-base">Stay updated with the latest activities and workshops for BEED students.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
      <?php
      $events = [
          [
              "20",
              "May",
              "BEED Practicum Orientation",
              "Orientation for all BEED students on practicum requirements, schedules, and expectations from cooperating schools.",
              "from-blue-700 to-blue-800",
              "blue",
          ],
          [
              "5",
              "Jun",
              "Demo Teaching Workshop",
              "Hands-on workshop on creating effective teaching demonstration plans and delivering engaging classroom lessons.",
              "from-indigo-600 to-indigo-700",
              "indigo",
          ],
          [
              "15",
              "Jun",
              "Lesson Plan Writing Seminar",
              "Learn how to write DepEd-compliant Detailed Lesson Plans from experienced educators and master teachers.",
              "from-teal-600 to-teal-700",
              "teal",
          ],
      ];
      foreach (
          $events
          as $i => [$day, $month, $title, $desc, $grad, $color]
      ): ?>
      <div class="tilt-card bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden flex flex-col reveal s<?= $i +
          1 ?>">
        <div class="bg-gradient-to-r <?= $grad ?> px-6 py-5 flex items-center gap-4">
          <div class="text-center bg-white/20 backdrop-blur-sm rounded-2xl px-4 py-3 min-w-[64px] border border-white/20">
            <span class="block text-3xl font-black text-white leading-none"><?= $day ?></span>
            <span class="block text-xs font-bold text-<?= $color ?>-100 uppercase tracking-widest mt-1"><?= $month ?></span>
          </div>
          <div>
            <span class="text-<?= $color ?>-100 text-sm font-semibold">2026</span>
            <div class="flex items-center gap-1.5 mt-1">
              <span class="w-2 h-2 bg-amber-400 rounded-full animate-pulse"></span>
              <span class="text-xs text-<?= $color ?>-200 font-medium">Upcoming</span>
            </div>
          </div>
        </div>
        <div class="p-6 flex flex-col flex-1">
          <h3 class="text-lg font-bold text-gray-900 mb-2"><?= $title ?></h3>
          <p class="text-gray-500 text-sm leading-relaxed flex-1"><?= $desc ?></p>
          <a href="#" class="mt-5 inline-flex items-center gap-1.5 text-sm font-semibold text-<?= $color ?>-600 hover:text-<?= $color ?>-800 transition-colors group">
            Learn More
            <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
            </svg>
          </a>
        </div>
      </div>
      <?php endforeach;
      ?>
    </div>
  </div>
</section>

<!-- ══════════════════════════════════════════════════════════
     CONTACT
══════════════════════════════════════════════════════════ -->
<section id="contact" class="bg-slate-50 py-24">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

    <div class="text-center mb-16 reveal">
      <span class="section-badge bg-indigo-100 text-indigo-700 mb-4">
        <span class="w-1.5 h-1.5 bg-indigo-500 rounded-full"></span>
        Get In Touch
      </span>
      <h2 class="text-3xl sm:text-4xl font-black text-gray-900 mb-4 mt-3">Contact Us</h2>
      <p class="text-gray-500 max-w-xl mx-auto text-base">Have questions or need support? We're here for you.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-start">

      <!-- Contact info -->
      <div class="reveal left">
        <h3 class="text-xl font-bold text-gray-900 mb-2">We'd love to hear from you</h3>
        <p class="text-gray-500 text-sm leading-relaxed mb-8">Whether it's a technical issue, a feature request, or just want to say hi — reach out. We're teachers and developers who care about your practicum success.</p>

        <div class="space-y-4">
          <?php
          $contacts = [
              [
                  "Email",
                  "myrhotipagad@gmail.com",
                  "blue",
                  "from-blue-600 to-blue-800",
                  "M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z",
              ],
              [
                  "Phone",
                  "09972905180",
                  "indigo",
                  "from-indigo-500 to-indigo-700",
                  "M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z",
              ],
              [
                  "Location",
                  "Victorias City, Negros Occidental",
                  "teal",
                  "from-teal-500 to-teal-700",
                  "M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z M15 11a3 3 0 11-6 0 3 3 0 016 0z",
              ],
          ];
          $bgMap = [
              "blue" => "bg-blue-50 border-blue-100",
              "indigo" => "bg-indigo-50 border-indigo-100",
              "teal" => "bg-teal-50 border-teal-100",
          ];
          foreach ($contacts as [$label, $value, $color, $grad, $path]): ?>
          <div class="flex items-center gap-4 p-4 <?= $bgMap[
              $color
          ] ?> rounded-2xl border">
            <div class="flex-shrink-0 w-12 h-12 bg-gradient-to-br <?= $grad ?> rounded-2xl flex items-center justify-center shadow-md">
              <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="<?= $path ?>"/>
              </svg>
            </div>
            <div>
              <p class="text-xs font-bold text-gray-400 uppercase tracking-widest"><?= $label ?></p>
              <p class="text-gray-800 font-semibold text-sm mt-0.5"><?= $value ?></p>
            </div>
          </div>
          <?php endforeach;
          ?>
        </div>
      </div>

      <!-- Contact form -->
      <div class="reveal right">
        <div class="bg-white rounded-3xl p-8 shadow-sm border border-slate-100">
          <h3 class="text-lg font-bold text-gray-900 mb-6">Send us a message</h3>
          <form onsubmit="return false;" class="space-y-5">
            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-1.5">Full Name</label>
              <input type="text" placeholder="e.g. Maria Santos"
                class="w-full px-4 py-3 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent bg-slate-50 transition shadow-sm"/>
            </div>
            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-1.5">Email Address</label>
              <input type="email" placeholder="you@example.com"
                class="w-full px-4 py-3 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent bg-slate-50 transition shadow-sm"/>
            </div>
            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-1.5">Message</label>
              <textarea rows="4" placeholder="How can we help you?"
                class="w-full px-4 py-3 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent bg-slate-50 transition shadow-sm resize-none"></textarea>
            </div>
            <button type="submit"
              class="w-full py-3.5 bg-gradient-to-r from-indigo-600 to-blue-600 hover:from-indigo-700 hover:to-blue-700 text-white font-bold rounded-xl transition-all shadow-md shadow-indigo-200 text-sm flex items-center justify-center gap-2 hover:-translate-y-0.5">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
              </svg>
              Send Message
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ══════════════════════════════════════════════════════════
     CTA BANNER
══════════════════════════════════════════════════════════ -->
<section class="py-20 relative overflow-hidden" style="background:linear-gradient(135deg,#1e1b4b 0%,#312e81 50%,#1e3a8a 100%);">
  <div class="absolute inset-0 pointer-events-none">
    <div style="position:absolute;top:-60px;right:-60px;width:320px;height:320px;border-radius:50%;background:radial-gradient(circle,rgba(99,102,241,.3),transparent 70%);"></div>
    <div style="position:absolute;bottom:-40px;left:-40px;width:280px;height:280px;border-radius:50%;background:radial-gradient(circle,rgba(59,130,246,.2),transparent 70%);"></div>
  </div>
  <div class="relative max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center reveal scale">
    <h2 class="text-3xl sm:text-4xl md:text-5xl font-black text-white mb-4">
      Ready to <span class="grad-text">Ace Your Practicum?</span>
    </h2>
    <p class="text-indigo-200 text-lg mb-10 max-w-2xl mx-auto">
      Join BEED students who are creating professional demo plans and lesson plans in minutes — not hours.
    </p>
    <div class="flex flex-col sm:flex-row gap-4 justify-center">
      <a href="/register"
         class="btn-primary inline-flex items-center justify-center gap-2.5 px-10 py-4 text-blue-900 font-black rounded-2xl shadow-xl text-base">
        Create Free Account
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
        </svg>
      </a>
      <a href="/login"
         class="btn-ghost inline-flex items-center justify-center gap-2 px-10 py-4 text-white font-semibold rounded-2xl text-base">
        Sign In
      </a>
    </div>
  </div>
</section>

<!-- ══════════════════════════════════════════════════════════
     FOOTER
══════════════════════════════════════════════════════════ -->
<footer style="background:#07071a;">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

    <!-- Top row -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-8 mb-10">

      <!-- Brand -->
      <div class="flex items-center gap-3">
        <div class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-blue-600 rounded-2xl flex items-center justify-center shadow-lg shadow-indigo-500/30">
          <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
          </svg>
        </div>
        <div>
          <p class="text-white font-extrabold text-lg leading-none">BEED Portal</p>
          <p class="text-gray-500 text-xs mt-0.5">Plan Smart. Teach Better.</p>
        </div>
      </div>

      <!-- Nav links -->
      <nav class="flex flex-wrap gap-x-7 gap-y-2 text-sm text-gray-500">
        <a href="#home"     class="hover:text-white transition-colors">Home</a>
        <a href="#services" class="hover:text-white transition-colors">Services</a>
        <a href="#how"      class="hover:text-white transition-colors">How It Works</a>
        <a href="#events"   class="hover:text-white transition-colors">Events</a>
        <a href="#contact"  class="hover:text-white transition-colors">Contact</a>
      </nav>

      <!-- Auth buttons -->
      <div class="flex items-center gap-3">
        <a href="/login"
           class="px-5 py-2 bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-semibold rounded-xl transition-all hover:-translate-y-0.5 shadow-sm">
          Sign In
        </a>
        <a href="/register"
           class="px-5 py-2 border border-gray-700 hover:border-indigo-500 text-gray-400 hover:text-white text-sm font-semibold rounded-xl transition-all hover:-translate-y-0.5">
          Register
        </a>
      </div>
    </div>

    <!-- Divider -->
    <div class="border-t border-white/5 pt-6 flex flex-col sm:flex-row items-center justify-between gap-3">
      <p class="text-xs text-gray-600">BEED Portal &copy; 2026 &mdash; Built for BEED Students</p>
      <p class="text-xs text-gray-700">Made with <span class="text-indigo-500">♥</span> for Filipino teachers</p>
    </div>
  </div>
</footer>

<!-- ══════════════════════════════════════════════════════════
     SCRIPTS
══════════════════════════════════════════════════════════ -->
<script>
// ═══════════════════════════════════════════════════════════
//  MOBILE MENU
// ═══════════════════════════════════════════════════════════
function toggleMenu() {
  var menu  = document.getElementById('mobile-menu');
  var btn   = document.getElementById('hamburger-btn');
  var open  = document.getElementById('icon-open');
  var close = document.getElementById('icon-close');
  var isOpen = menu.classList.contains('open');
  menu.classList.toggle('open', !isOpen);
  open.classList.toggle('hidden', !isOpen);
  close.classList.toggle('hidden', isOpen);
  btn.setAttribute('aria-expanded', isOpen ? 'false' : 'true');
}
function closeMenu() {
  var menu  = document.getElementById('mobile-menu');
  var open  = document.getElementById('icon-open');
  var close = document.getElementById('icon-close');
  menu.classList.remove('open');
  open.classList.remove('hidden');
  close.classList.add('hidden');
  document.getElementById('hamburger-btn').setAttribute('aria-expanded','false');
}
window.addEventListener('resize', function() {
  if (window.innerWidth >= 768) closeMenu();
});

(function () {
  'use strict';

  // ═══════════════════════════════════════════════════════════
  //  MOUSE PARALLAX — hero floating shapes
  // ═══════════════════════════════════════════════════════════
  var shapes = document.querySelectorAll('.parallax-shape');
  var hero   = document.getElementById('home');
  var mouseX = 0, mouseY = 0;
  var heroW  = 0, heroH = 0;

  function updateHeroSize() {
    if (hero) { heroW = hero.offsetWidth; heroH = hero.offsetHeight; }
  }
  updateHeroSize();
  window.addEventListener('resize', updateHeroSize);

  document.addEventListener('mousemove', function (e) {
    if (!hero) return;
    var rect = hero.getBoundingClientRect();
    // Normalise -1 … +1 relative to hero centre
    mouseX = ((e.clientX - rect.left) / rect.width  - 0.5) * 2;
    mouseY = ((e.clientY - rect.top)  / rect.height - 0.5) * 2;
  });

  function animateParallax() {
    shapes.forEach(function (el) {
      var speed = parseFloat(el.getAttribute('data-speed') || '0.04');
      var tx = mouseX * speed * 80;
      var ty = mouseY * speed * 60;
      // Combine with any existing float animation via a CSS custom property
      el.style.setProperty('--px', tx + 'px');
      el.style.setProperty('--py', ty + 'px');
      el.style.translate = tx + 'px ' + ty + 'px';
    });
    requestAnimationFrame(animateParallax);
  }
  animateParallax();

  // ═══════════════════════════════════════════════════════════
  //  CANVAS PARTICLE NETWORK
  // ═══════════════════════════════════════════════════════════
  var canvas = document.getElementById('hero-canvas');
  if (canvas) {
    var ctx = canvas.getContext('2d');
    var particles = [];
    var PARTICLE_COUNT = 55;
    var mouse = { x: -9999, y: -9999 };

    function resizeCanvas() {
      canvas.width  = canvas.offsetWidth;
      canvas.height = canvas.offsetHeight;
    }
    resizeCanvas();
    window.addEventListener('resize', resizeCanvas);

    document.addEventListener('mousemove', function (e) {
      var r = canvas.getBoundingClientRect();
      mouse.x = e.clientX - r.left;
      mouse.y = e.clientY - r.top;
    });

    function Particle() {
      this.reset();
    }
    Particle.prototype.reset = function () {
      this.x  = Math.random() * canvas.width;
      this.y  = Math.random() * canvas.height;
      this.r  = Math.random() * 1.8 + 0.6;
      this.vx = (Math.random() - 0.5) * 0.35;
      this.vy = (Math.random() - 0.5) * 0.35;
      this.alpha = Math.random() * 0.5 + 0.2;
    };
    Particle.prototype.update = function () {
      this.x += this.vx;
      this.y += this.vy;
      // Mouse repulsion
      var dx = this.x - mouse.x;
      var dy = this.y - mouse.y;
      var dist = Math.sqrt(dx * dx + dy * dy);
      if (dist < 80) {
        this.x += dx / dist * 1.2;
        this.y += dy / dist * 1.2;
      }
      if (this.x < 0 || this.x > canvas.width)  this.vx *= -1;
      if (this.y < 0 || this.y > canvas.height) this.vy *= -1;
    };

    for (var i = 0; i < PARTICLE_COUNT; i++) particles.push(new Particle());

    function drawParticles() {
      ctx.clearRect(0, 0, canvas.width, canvas.height);
      // Draw connections
      for (var a = 0; a < particles.length; a++) {
        for (var b = a + 1; b < particles.length; b++) {
          var dx = particles[a].x - particles[b].x;
          var dy = particles[a].y - particles[b].y;
          var d  = Math.sqrt(dx * dx + dy * dy);
          if (d < 130) {
            ctx.beginPath();
            ctx.moveTo(particles[a].x, particles[a].y);
            ctx.lineTo(particles[b].x, particles[b].y);
            ctx.strokeStyle = 'rgba(165,180,252,' + ((1 - d / 130) * 0.25) + ')';
            ctx.lineWidth = 0.8;
            ctx.stroke();
          }
        }
      }
      // Draw dots
      particles.forEach(function (p) {
        p.update();
        ctx.beginPath();
        ctx.arc(p.x, p.y, p.r, 0, Math.PI * 2);
        ctx.fillStyle = 'rgba(199,210,254,' + p.alpha + ')';
        ctx.fill();
      });
      requestAnimationFrame(drawParticles);
    }
    drawParticles();
  }

  // ═══════════════════════════════════════════════════════════
  //  TYPEWRITER
  // ═══════════════════════════════════════════════════════════
  var tw = document.getElementById('typewriter');
  if (tw) {
    var phrases = [
      'Create demo plans in minutes.',
      'Write DepEd-aligned lesson plans.',
      'Save & reuse your best templates.',
      'Ace your practicum with confidence.',
      'Built for BEED students like you.'
    ];
    var pIdx = 0, cIdx = 0, deleting = false;
    function typeStep() {
      var phrase = phrases[pIdx];
      if (!deleting) {
        tw.textContent = phrase.slice(0, ++cIdx);
        if (cIdx === phrase.length) { deleting = true; setTimeout(typeStep, 1800); return; }
        setTimeout(typeStep, 48);
      } else {
        tw.textContent = phrase.slice(0, --cIdx);
        if (cIdx === 0) { deleting = false; pIdx = (pIdx + 1) % phrases.length; setTimeout(typeStep, 300); return; }
        setTimeout(typeStep, 22);
      }
    }
    setTimeout(typeStep, 1000);
  }

  // ═══════════════════════════════════════════════════════════
  //  3-D CARD TILT
  // ═══════════════════════════════════════════════════════════
  document.querySelectorAll('.tilt-card').forEach(function (card) {
    card.addEventListener('mousemove', function (e) {
      var r   = card.getBoundingClientRect();
      var x   = e.clientX - r.left;
      var y   = e.clientY - r.top;
      var cx  = r.width  / 2;
      var cy  = r.height / 2;
      var rotX = ((y - cy) / cy) * -7;
      var rotY = ((x - cx) / cx) *  7;
      card.style.transform = 'perspective(900px) rotateX(' + rotX + 'deg) rotateY(' + rotY + 'deg) translateY(-5px) scale(1.01)';
    });
    card.addEventListener('mouseleave', function () {
      card.style.transform = '';
    });
  });

  // ═══════════════════════════════════════════════════════════
  //  SCROLL REVEAL — Intersection Observer
  // ═══════════════════════════════════════════════════════════
  var revealObserver = new IntersectionObserver(function (entries) {
    entries.forEach(function (entry) {
      if (entry.isIntersecting) {
        entry.target.classList.add('visible');
        revealObserver.unobserve(entry.target);
      }
    });
  }, { threshold: 0.12 });

  document.querySelectorAll('.reveal').forEach(function (el) {
    revealObserver.observe(el);
  });

  // ═══════════════════════════════════════════════════════════
  //  ANIMATED NUMBER COUNTERS
  // ═══════════════════════════════════════════════════════════
  var counters = document.querySelectorAll('.counter');
  var counted  = false;
  var counterObserver = new IntersectionObserver(function (entries) {
    if (counted) return;
    entries.forEach(function (entry) {
      if (entry.isIntersecting) {
        counted = true;
        counters.forEach(function (el) {
          var target = parseInt(el.getAttribute('data-target'), 10);
          var start  = 0;
          var dur    = 1800;
          var step   = dur / 60;
          var inc    = target / (dur / (1000 / 60));
          var timer  = setInterval(function () {
            start += inc;
            if (start >= target) { el.textContent = target + '+'; clearInterval(timer); }
            else { el.textContent = Math.floor(start); }
          }, step);
        });
        counterObserver.disconnect();
      }
    });
  }, { threshold: 0.4 });
  if (counters.length) counterObserver.observe(counters[0].closest('.stat-pill') || counters[0]);

  // ═══════════════════════════════════════════════════════════
  //  NAV — glass effect on scroll
  // ═══════════════════════════════════════════════════════════
  var nav = document.getElementById('main-nav');
  window.addEventListener('scroll', function () {
    if (!nav) return;
    if (window.scrollY > 40) {
      nav.classList.add('scrolled');
    } else {
      nav.classList.remove('scrolled');
    }
  }, { passive: true });

  // ═══════════════════════════════════════════════════════════
  //  SMOOTH SCROLL for anchor links
  // ═══════════════════════════════════════════════════════════
  document.querySelectorAll('a[href^="#"]').forEach(function (link) {
    link.addEventListener('click', function (e) {
      var id = link.getAttribute('href').slice(1);
      var target = document.getElementById(id);
      if (target) {
        e.preventDefault();
        target.scrollIntoView({ behavior: 'smooth', block: 'start' });
        closeMenu();
      }
    });
  });

})();
</script>
</body>
</html>
