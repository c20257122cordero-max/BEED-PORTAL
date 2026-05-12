<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>BEED Portal &mdash; Plan Smart. Teach Better.</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            brand: {
              50:  '#eff6ff',
              100: '#dbeafe',
              600: '#2563eb',
              700: '#1d4ed8',
              800: '#1e40af',
              900: '#1e3a8a',
            }
          },
          animation: {
            'float': 'float 6s ease-in-out infinite',
            'float-slow': 'float 9s ease-in-out infinite',
            'fade-up': 'fadeUp 0.7s ease-out both',
          },
          keyframes: {
            float: {
              '0%, 100%': { transform: 'translateY(0px)' },
              '50%':       { transform: 'translateY(-18px)' },
            },
            fadeUp: {
              '0%':   { opacity: '0', transform: 'translateY(28px)' },
              '100%': { opacity: '1', transform: 'translateY(0)' },
            }
          }
        }
      }
    }
  </script>
  <style>
    html { scroll-behavior: smooth; }
    .delay-100 { animation-delay: 0.1s; }
    .delay-200 { animation-delay: 0.2s; }
    .delay-300 { animation-delay: 0.3s; }
    .delay-400 { animation-delay: 0.4s; }
    .delay-500 { animation-delay: 0.5s; }
    .gradient-text {
      background: linear-gradient(135deg, #fbbf24, #f59e0b);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
    }
    .card-hover {
      transition: transform 0.25s ease, box-shadow 0.25s ease;
    }
    .card-hover:hover {
      transform: translateY(-6px);
      box-shadow: 0 20px 40px rgba(30, 64, 175, 0.12);
    }
    .nav-link {
      position: relative;
    }
    .nav-link::after {
      content: '';
      position: absolute;
      bottom: -2px;
      left: 0;
      width: 0;
      height: 2px;
      background: #1d4ed8;
      transition: width 0.25s ease;
    }
    .nav-link:hover::after { width: 100%; }
  </style>
</head>
<body class="font-sans text-gray-800 antialiased">

<!-- ===================================================
     STICKY NAV
=================================================== -->
<nav class="sticky top-0 z-50 bg-white/95 backdrop-blur-sm shadow-sm border-b border-gray-100">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex items-center justify-between h-16">

      <!-- Logo -->
      <a href="#home" class="flex items-center gap-2.5 text-blue-800 font-extrabold text-xl tracking-tight">
        <div class="w-9 h-9 bg-gradient-to-br from-blue-700 to-indigo-600 rounded-xl flex items-center justify-center shadow-md">
          <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round"
              d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
          </svg>
        </div>
        <span>BEED <span class="text-indigo-600">Portal</span></span>
      </a>

      <!-- Desktop nav -->
      <div class="hidden md:flex items-center gap-7 text-sm font-medium text-gray-600">
        <a href="#home"     class="nav-link hover:text-blue-800 transition-colors">Home</a>
        <a href="#services" class="nav-link hover:text-blue-800 transition-colors">Services</a>
        <a href="#events"   class="nav-link hover:text-blue-800 transition-colors">Events</a>
        <a href="#contact"  class="nav-link hover:text-blue-800 transition-colors">Contact Us</a>
        <div class="flex items-center gap-2 ml-2">
          <a href="/DEMO%20MAKER%20AND%20LESSON%20PLAN%20MAKER/login"
             class="px-4 py-2 bg-blue-700 hover:bg-blue-800 text-white text-sm font-semibold rounded-lg shadow-sm transition-colors">
            Login
          </a>
          <a href="/DEMO%20MAKER%20AND%20LESSON%20PLAN%20MAKER/register"
             class="px-4 py-2 border-2 border-blue-700 text-blue-700 hover:bg-blue-50 text-sm font-semibold rounded-lg transition-colors">
            Register
          </a>
        </div>
      </div>

      <!-- Hamburger -->
      <button id="hamburger-btn" onclick="toggleMenu()"
        class="md:hidden p-2 rounded-lg text-gray-600 hover:text-blue-700 hover:bg-blue-50 transition-colors"
        aria-label="Toggle menu" aria-expanded="false">
        <svg id="icon-open"  class="w-6 h-6 block" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
        </svg>
        <svg id="icon-close" class="w-6 h-6 hidden" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
        </svg>
      </button>
    </div>
  </div>

  <!-- Mobile menu -->
  <div id="mobile-menu" class="hidden md:hidden bg-white border-t border-gray-100 px-4 pb-5">
    <div class="flex flex-col gap-1 pt-3 text-sm font-medium text-gray-600">
      <a href="#home"     onclick="closeMenu()" class="hover:text-blue-700 hover:bg-blue-50 rounded-lg px-3 py-2.5 transition-colors">Home</a>
      <a href="#services" onclick="closeMenu()" class="hover:text-blue-700 hover:bg-blue-50 rounded-lg px-3 py-2.5 transition-colors">Services</a>
      <a href="#events"   onclick="closeMenu()" class="hover:text-blue-700 hover:bg-blue-50 rounded-lg px-3 py-2.5 transition-colors">Events</a>
      <a href="#contact"  onclick="closeMenu()" class="hover:text-blue-700 hover:bg-blue-50 rounded-lg px-3 py-2.5 transition-colors">Contact Us</a>
      <div class="flex gap-2 mt-2">
        <a href="/DEMO%20MAKER%20AND%20LESSON%20PLAN%20MAKER/login"
           class="flex-1 px-4 py-2.5 bg-blue-700 hover:bg-blue-800 text-white font-semibold rounded-lg text-center transition-colors text-sm">
          Login
        </a>
        <a href="/DEMO%20MAKER%20AND%20LESSON%20PLAN%20MAKER/register"
           class="flex-1 px-4 py-2.5 border-2 border-blue-700 text-blue-700 hover:bg-blue-50 font-semibold rounded-lg text-center transition-colors text-sm">
          Register
        </a>
      </div>
    </div>
  </div>
</nav>

<!-- ===================================================
     HERO SECTION
=================================================== -->
<section id="home" class="relative bg-gradient-to-br from-blue-800 via-blue-700 to-indigo-700 text-white overflow-hidden min-h-screen flex items-center">

  <!-- Decorative floating shapes -->
  <div class="absolute inset-0 pointer-events-none overflow-hidden">
    <div class="absolute -top-20 -left-20 w-80 h-80 bg-white/5 rounded-full animate-float"></div>
    <div class="absolute top-1/3 -right-16 w-64 h-64 bg-indigo-500/20 rounded-full animate-float-slow"></div>
    <div class="absolute bottom-10 left-1/4 w-48 h-48 bg-blue-400/10 rounded-full animate-float" style="animation-delay:3s"></div>
    <div class="absolute top-16 right-1/3 w-24 h-24 bg-amber-400/15 rounded-full animate-float-slow" style="animation-delay:1.5s"></div>
    <div class="absolute bottom-1/4 right-10 w-36 h-36 bg-white/5 rounded-full animate-float" style="animation-delay:4s"></div>
    <!-- Grid dots pattern -->
    <svg class="absolute inset-0 w-full h-full opacity-5" xmlns="http://www.w3.org/2000/svg">
      <defs>
        <pattern id="dots" x="0" y="0" width="30" height="30" patternUnits="userSpaceOnUse">
          <circle cx="2" cy="2" r="1.5" fill="white"/>
        </pattern>
      </defs>
      <rect width="100%" height="100%" fill="url(#dots)"/>
    </svg>
  </div>

  <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 md:py-32 text-center w-full">

    <!-- Badge -->
    <div class="inline-flex items-center gap-2 mb-6 px-4 py-1.5 bg-white/15 backdrop-blur-sm border border-white/20 rounded-full text-xs font-semibold tracking-widest uppercase text-blue-100 animate-fade-up">
      <span class="w-2 h-2 bg-amber-400 rounded-full animate-pulse"></span>
      BEED Student Portal &bull; DepEd Aligned
    </div>

    <!-- Heading -->
    <h1 class="text-5xl sm:text-6xl md:text-7xl font-extrabold leading-tight mb-6 animate-fade-up delay-100">
      Plan Smart.<br />
      <span class="gradient-text">Teach Better.</span>
    </h1>

    <!-- Subheading -->
    <p class="text-lg sm:text-xl text-blue-100 max-w-2xl mx-auto mb-10 leading-relaxed animate-fade-up delay-200">
      The DepEd-ready teaching toolkit built for BEED students. Create demo plans, write lesson plans, and ace your practicum.
    </p>

    <!-- CTA Buttons -->
    <div class="flex flex-col sm:flex-row gap-4 justify-center mb-14 animate-fade-up delay-300">
      <a href="/DEMO%20MAKER%20AND%20LESSON%20PLAN%20MAKER/login"
         class="group inline-flex items-center justify-center gap-2 px-8 py-3.5 bg-amber-400 hover:bg-amber-300 text-blue-900 font-bold rounded-xl shadow-lg hover:shadow-amber-400/30 transition-all text-base">
        Get Started Free
        <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
        </svg>
      </a>
      <a href="#services"
         class="inline-flex items-center justify-center gap-2 px-8 py-3.5 border-2 border-white/60 text-white font-semibold rounded-xl hover:bg-white/10 hover:border-white transition-all text-base">
        See How It Works
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
        </svg>
      </a>
    </div>

    <!-- Stat badges -->
    <div class="flex flex-wrap justify-center gap-4 animate-fade-up delay-400">
      <div class="flex items-center gap-2 bg-white/10 backdrop-blur-sm border border-white/20 rounded-xl px-5 py-3">
        <span class="text-2xl font-extrabold text-amber-300">500+</span>
        <span class="text-sm text-blue-100 font-medium">Plans Created</span>
      </div>
      <div class="flex items-center gap-2 bg-white/10 backdrop-blur-sm border border-white/20 rounded-xl px-5 py-3">
        <span class="text-2xl font-extrabold text-amber-300">7</span>
        <span class="text-sm text-blue-100 font-medium">Subjects Covered</span>
      </div>
      <div class="flex items-center gap-2 bg-white/10 backdrop-blur-sm border border-white/20 rounded-xl px-5 py-3">
        <span class="text-2xl font-extrabold text-amber-300">100%</span>
        <span class="text-sm text-blue-100 font-medium">DepEd Aligned</span>
      </div>
    </div>

  </div>
</section>

<!-- ===================================================
     FEATURES STRIP
=================================================== -->
<div class="bg-blue-800 border-t border-blue-700">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
    <div class="flex flex-wrap justify-center gap-x-10 gap-y-2 text-sm font-semibold text-blue-100">
      <span class="flex items-center gap-2">
        <svg class="w-4 h-4 text-amber-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
        Free to Use
      </span>
      <span class="flex items-center gap-2">
        <svg class="w-4 h-4 text-amber-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
        DepEd Format
      </span>
      <span class="flex items-center gap-2">
        <svg class="w-4 h-4 text-amber-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
        Print-Ready
      </span>
      <span class="flex items-center gap-2">
        <svg class="w-4 h-4 text-amber-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
        Mobile Friendly
      </span>
    </div>
  </div>
</div>

<!-- ===================================================
     SERVICES SECTION
=================================================== -->
<section id="services" class="bg-gray-50 py-24">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

    <!-- Heading -->
    <div class="text-center mb-16">
      <span class="inline-block mb-3 px-3 py-1 bg-blue-100 text-blue-700 text-xs font-bold rounded-full tracking-widest uppercase">What We Offer</span>
      <h2 class="text-3xl sm:text-4xl font-extrabold text-gray-900 mb-4">Everything You Need for Practicum</h2>
      <p class="text-gray-500 max-w-xl mx-auto text-base leading-relaxed">
        Purpose-built tools that match exactly what BEED students need during field study and practicum.
      </p>
    </div>

    <!-- Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">

      <!-- Card 1: Demo Maker -->
      <div class="card-hover bg-white rounded-2xl p-8 shadow-sm border border-gray-100 flex flex-col">
        <div class="inline-flex items-center justify-center w-14 h-14 bg-gradient-to-br from-blue-600 to-blue-800 rounded-2xl mb-6 shadow-lg shadow-blue-200">
          <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round"
              d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
          </svg>
        </div>
        <h3 class="text-xl font-bold text-gray-900 mb-3">Demo Maker</h3>
        <p class="text-gray-500 leading-relaxed flex-1 text-sm">
          Build step-by-step teaching demonstration plans with guided templates. Structure your lessons from motivation to evaluation with ease.
        </p>
        <a href="#" class="mt-6 inline-flex items-center gap-1 text-sm font-semibold text-blue-700 hover:text-blue-900 transition-colors group">
          Learn More
          <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
          </svg>
        </a>
      </div>

      <!-- Card 2: Lesson Plan Planner -->
      <div class="card-hover bg-white rounded-2xl p-8 shadow-sm border border-gray-100 flex flex-col relative overflow-hidden">
        <!-- Popular badge -->
        <div class="absolute top-4 right-4 px-2.5 py-1 bg-amber-400 text-amber-900 text-xs font-bold rounded-full">Most Used</div>
        <div class="inline-flex items-center justify-center w-14 h-14 bg-gradient-to-br from-indigo-500 to-indigo-700 rounded-2xl mb-6 shadow-lg shadow-indigo-200">
          <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round"
              d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
          </svg>
        </div>
        <h3 class="text-xl font-bold text-gray-900 mb-3">Lesson Plan Planner</h3>
        <p class="text-gray-500 leading-relaxed flex-1 text-sm">
          Create complete DepEd-aligned DLPs in minutes with all required sections. Every field is guided so nothing gets missed.
        </p>
        <a href="#" class="mt-6 inline-flex items-center gap-1 text-sm font-semibold text-indigo-600 hover:text-indigo-800 transition-colors group">
          Learn More
          <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
          </svg>
        </a>
      </div>

      <!-- Card 3: Smart Templates -->
      <div class="card-hover bg-white rounded-2xl p-8 shadow-sm border border-gray-100 flex flex-col">
        <div class="inline-flex items-center justify-center w-14 h-14 bg-gradient-to-br from-teal-500 to-teal-700 rounded-2xl mb-6 shadow-lg shadow-teal-200">
          <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round"
              d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
          </svg>
        </div>
        <h3 class="text-xl font-bold text-gray-900 mb-3">Smart Templates</h3>
        <p class="text-gray-500 leading-relaxed flex-1 text-sm">
          Save your best plans as templates and reuse them for any subject or grade. Build a personal library that grows with your teaching career.
        </p>
        <a href="#" class="mt-6 inline-flex items-center gap-1 text-sm font-semibold text-teal-600 hover:text-teal-800 transition-colors group">
          Learn More
          <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
          </svg>
        </a>
      </div>

    </div>
  </div>
</section>

<!-- ===================================================
     HOW IT WORKS
=================================================== -->
<section class="bg-white py-24">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

    <div class="text-center mb-16">
      <span class="inline-block mb-3 px-3 py-1 bg-indigo-100 text-indigo-700 text-xs font-bold rounded-full tracking-widest uppercase">Simple Process</span>
      <h2 class="text-3xl sm:text-4xl font-extrabold text-gray-900 mb-4">How It Works</h2>
      <p class="text-gray-500 max-w-xl mx-auto text-base">From sign-up to submission in three easy steps.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 relative">
      <!-- Connector line (desktop) -->
      <div class="hidden md:block absolute top-12 left-1/3 right-1/3 h-0.5 bg-gradient-to-r from-blue-200 via-indigo-300 to-blue-200 z-0"></div>

      <!-- Step 1 -->
      <div class="relative z-10 flex flex-col items-center text-center p-8 bg-blue-50 rounded-2xl border border-blue-100">
        <div class="w-16 h-16 bg-gradient-to-br from-blue-600 to-blue-800 rounded-2xl flex items-center justify-center mb-5 shadow-lg shadow-blue-200">
          <span class="text-2xl font-extrabold text-white">1</span>
        </div>
        <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center mb-4 shadow-sm border border-blue-100">
          <svg class="w-5 h-5 text-blue-700" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
          </svg>
        </div>
        <h3 class="text-lg font-bold text-gray-900 mb-2">Register for Free</h3>
        <p class="text-gray-500 text-sm leading-relaxed">Create your student account in seconds. No credit card, no hassle &mdash; just your email and you're in.</p>
      </div>

      <!-- Step 2 -->
      <div class="relative z-10 flex flex-col items-center text-center p-8 bg-indigo-50 rounded-2xl border border-indigo-100">
        <div class="w-16 h-16 bg-gradient-to-br from-indigo-500 to-indigo-700 rounded-2xl flex items-center justify-center mb-5 shadow-lg shadow-indigo-200">
          <span class="text-2xl font-extrabold text-white">2</span>
        </div>
        <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center mb-4 shadow-sm border border-indigo-100">
          <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 10h16M4 14h10"/>
          </svg>
        </div>
        <h3 class="text-lg font-bold text-gray-900 mb-2">Choose a Template</h3>
        <p class="text-gray-500 text-sm leading-relaxed">Pick from subject-specific templates or start from scratch. Every template follows the official DepEd format.</p>
      </div>

      <!-- Step 3 -->
      <div class="relative z-10 flex flex-col items-center text-center p-8 bg-teal-50 rounded-2xl border border-teal-100">
        <div class="w-16 h-16 bg-gradient-to-br from-teal-500 to-teal-700 rounded-2xl flex items-center justify-center mb-5 shadow-lg shadow-teal-200">
          <span class="text-2xl font-extrabold text-white">3</span>
        </div>
        <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center mb-4 shadow-sm border border-teal-100">
          <svg class="w-5 h-5 text-teal-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
          </svg>
        </div>
        <h3 class="text-lg font-bold text-gray-900 mb-2">Export &amp; Submit</h3>
        <p class="text-gray-500 text-sm leading-relaxed">Print your DepEd-formatted plan and submit with confidence. Your cooperating teacher will be impressed.</p>
      </div>

    </div>
  </div>
</section>

<!-- ===================================================
     TESTIMONIALS
=================================================== -->
<section class="bg-blue-50 py-24">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

    <div class="text-center mb-16">
      <span class="inline-block mb-3 px-3 py-1 bg-blue-100 text-blue-700 text-xs font-bold rounded-full tracking-widest uppercase">Student Reviews</span>
      <h2 class="text-3xl sm:text-4xl font-extrabold text-gray-900 mb-4">What BEED Students Say</h2>
      <p class="text-gray-500 max-w-xl mx-auto text-base">Real feedback from students who used the portal during their practicum.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">

      <!-- Quote 1 -->
      <div class="card-hover bg-white rounded-2xl p-8 shadow-sm border border-blue-100 flex flex-col">
        <!-- Stars -->
        <div class="flex gap-1 mb-5">
          <svg class="w-5 h-5 text-amber-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
          <svg class="w-5 h-5 text-amber-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
          <svg class="w-5 h-5 text-amber-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
          <svg class="w-5 h-5 text-amber-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
          <svg class="w-5 h-5 text-amber-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
        </div>
        <!-- Quote mark -->
        <svg class="w-8 h-8 text-blue-200 mb-3" fill="currentColor" viewBox="0 0 24 24">
          <path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z"/>
        </svg>
        <p class="text-gray-700 leading-relaxed flex-1 text-sm italic">
          "The BEED Portal saved me hours of work during practicum. The templates are exactly what we need!"
        </p>
        <div class="mt-6 flex items-center gap-3">
          <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-700 rounded-full flex items-center justify-center text-white font-bold text-sm">M</div>
          <div>
            <p class="text-sm font-bold text-gray-900">Maria S.</p>
            <p class="text-xs text-gray-500">3rd Year BEED</p>
          </div>
        </div>
      </div>

      <!-- Quote 2 -->
      <div class="card-hover bg-white rounded-2xl p-8 shadow-sm border border-blue-100 flex flex-col">
        <div class="flex gap-1 mb-5">
          <svg class="w-5 h-5 text-amber-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
          <svg class="w-5 h-5 text-amber-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
          <svg class="w-5 h-5 text-amber-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
          <svg class="w-5 h-5 text-amber-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
          <svg class="w-5 h-5 text-amber-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
        </div>
        <svg class="w-8 h-8 text-indigo-200 mb-3" fill="currentColor" viewBox="0 0 24 24">
          <path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z"/>
        </svg>
        <p class="text-gray-700 leading-relaxed flex-1 text-sm italic">
          "I used to spend 3 hours writing one lesson plan. Now it takes me 20 minutes. This tool is a game changer for BEED students."
        </p>
        <div class="mt-6 flex items-center gap-3">
          <div class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-indigo-700 rounded-full flex items-center justify-center text-white font-bold text-sm">J</div>
          <div>
            <p class="text-sm font-bold text-gray-900">Juan D.</p>
            <p class="text-xs text-gray-500">4th Year BEED</p>
          </div>
        </div>
      </div>

      <!-- Quote 3 -->
      <div class="card-hover bg-white rounded-2xl p-8 shadow-sm border border-blue-100 flex flex-col">
        <div class="flex gap-1 mb-5">
          <svg class="w-5 h-5 text-amber-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
          <svg class="w-5 h-5 text-amber-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
          <svg class="w-5 h-5 text-amber-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
          <svg class="w-5 h-5 text-amber-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
          <svg class="w-5 h-5 text-amber-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
        </div>
        <svg class="w-8 h-8 text-teal-200 mb-3" fill="currentColor" viewBox="0 0 24 24">
          <path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z"/>
        </svg>
        <p class="text-gray-700 leading-relaxed flex-1 text-sm italic">
          "The export feature is amazing &mdash; it prints exactly in the DepEd format my cooperating teacher requires. No more reformatting!"
        </p>
        <div class="mt-6 flex items-center gap-3">
          <div class="w-10 h-10 bg-gradient-to-br from-teal-500 to-teal-700 rounded-full flex items-center justify-center text-white font-bold text-sm">A</div>
          <div>
            <p class="text-sm font-bold text-gray-900">Ana R.</p>
            <p class="text-xs text-gray-500">3rd Year BEED</p>
          </div>
        </div>
      </div>

    </div>
  </div>
</section>

<!-- ===================================================
     EVENTS SECTION
=================================================== -->
<section id="events" class="bg-gray-50 py-24">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

    <div class="text-center mb-16">
      <span class="inline-block mb-3 px-3 py-1 bg-amber-100 text-amber-700 text-xs font-bold rounded-full tracking-widest uppercase">Mark Your Calendar</span>
      <h2 class="text-3xl sm:text-4xl font-extrabold text-gray-900 mb-4">Upcoming Events</h2>
      <p class="text-gray-500 max-w-xl mx-auto text-base">Stay updated with the latest activities and workshops for BEED students.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">

      <!-- Event 1 -->
      <div class="card-hover bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden flex flex-col">
        <div class="bg-gradient-to-r from-blue-700 to-blue-800 px-6 py-5 flex items-center gap-4">
          <div class="text-center bg-white/20 backdrop-blur-sm rounded-2xl px-4 py-3 min-w-[60px] border border-white/20">
            <span class="block text-3xl font-extrabold text-white leading-none">20</span>
            <span class="block text-xs font-bold text-blue-100 uppercase tracking-widest mt-1">May</span>
          </div>
          <div>
            <span class="text-blue-100 text-sm font-semibold">2026</span>
            <div class="flex items-center gap-1 mt-1">
              <span class="w-2 h-2 bg-amber-400 rounded-full animate-pulse"></span>
              <span class="text-xs text-blue-200">Upcoming</span>
            </div>
          </div>
        </div>
        <div class="p-6 flex flex-col flex-1">
          <h3 class="text-lg font-bold text-gray-900 mb-2">BEED Practicum Orientation</h3>
          <p class="text-gray-500 text-sm leading-relaxed flex-1">
            Orientation for all BEED students on practicum requirements, schedules, and expectations from cooperating schools.
          </p>
          <a href="#" class="mt-5 inline-flex items-center gap-1 text-sm font-semibold text-blue-700 hover:text-blue-900 transition-colors group">
            Learn More
            <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
            </svg>
          </a>
        </div>
      </div>

      <!-- Event 2 -->
      <div class="card-hover bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden flex flex-col">
        <div class="bg-gradient-to-r from-indigo-600 to-indigo-700 px-6 py-5 flex items-center gap-4">
          <div class="text-center bg-white/20 backdrop-blur-sm rounded-2xl px-4 py-3 min-w-[60px] border border-white/20">
            <span class="block text-3xl font-extrabold text-white leading-none">5</span>
            <span class="block text-xs font-bold text-indigo-100 uppercase tracking-widest mt-1">Jun</span>
          </div>
          <div>
            <span class="text-indigo-100 text-sm font-semibold">2026</span>
            <div class="flex items-center gap-1 mt-1">
              <span class="w-2 h-2 bg-amber-400 rounded-full animate-pulse"></span>
              <span class="text-xs text-indigo-200">Upcoming</span>
            </div>
          </div>
        </div>
        <div class="p-6 flex flex-col flex-1">
          <h3 class="text-lg font-bold text-gray-900 mb-2">Demo Teaching Workshop</h3>
          <p class="text-gray-500 text-sm leading-relaxed flex-1">
            Hands-on workshop on creating effective teaching demonstration plans and delivering engaging classroom lessons.
          </p>
          <a href="#" class="mt-5 inline-flex items-center gap-1 text-sm font-semibold text-indigo-600 hover:text-indigo-800 transition-colors group">
            Learn More
            <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
            </svg>
          </a>
        </div>
      </div>

      <!-- Event 3 -->
      <div class="card-hover bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden flex flex-col">
        <div class="bg-gradient-to-r from-teal-600 to-teal-700 px-6 py-5 flex items-center gap-4">
          <div class="text-center bg-white/20 backdrop-blur-sm rounded-2xl px-4 py-3 min-w-[60px] border border-white/20">
            <span class="block text-3xl font-extrabold text-white leading-none">15</span>
            <span class="block text-xs font-bold text-teal-100 uppercase tracking-widest mt-1">Jun</span>
          </div>
          <div>
            <span class="text-teal-100 text-sm font-semibold">2026</span>
            <div class="flex items-center gap-1 mt-1">
              <span class="w-2 h-2 bg-amber-400 rounded-full animate-pulse"></span>
              <span class="text-xs text-teal-200">Upcoming</span>
            </div>
          </div>
        </div>
        <div class="p-6 flex flex-col flex-1">
          <h3 class="text-lg font-bold text-gray-900 mb-2">Lesson Plan Writing Seminar</h3>
          <p class="text-gray-500 text-sm leading-relaxed flex-1">
            Learn how to write DepEd-compliant Detailed Lesson Plans from experienced educators and master teachers.
          </p>
          <a href="#" class="mt-5 inline-flex items-center gap-1 text-sm font-semibold text-teal-600 hover:text-teal-800 transition-colors group">
            Learn More
            <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
            </svg>
          </a>
        </div>
      </div>

    </div>
  </div>
</section>

<!-- ===================================================
     CONTACT SECTION
=================================================== -->
<section id="contact" class="bg-white py-24">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

    <div class="text-center mb-16">
      <span class="inline-block mb-3 px-3 py-1 bg-blue-100 text-blue-700 text-xs font-bold rounded-full tracking-widest uppercase">Get in Touch</span>
      <h2 class="text-3xl sm:text-4xl font-extrabold text-gray-900 mb-4">Contact Us</h2>
      <p class="text-gray-500 max-w-xl mx-auto text-base">Have questions or need support? We're here to help.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-start">

      <!-- Left: Contact Info -->
      <div class="space-y-6">
        <div>
          <h3 class="text-xl font-bold text-gray-900 mb-3">We'd love to hear from you</h3>
          <p class="text-gray-500 leading-relaxed text-sm">
            Whether you have a technical issue, a feature request, or just want to say hi &mdash; don't hesitate to reach out. We're a team of educators and developers who care about your practicum success.
          </p>
        </div>

        <!-- Contact items -->
        <div class="space-y-4">
          <div class="flex items-center gap-4 p-4 bg-blue-50 rounded-xl border border-blue-100">
            <div class="flex-shrink-0 w-11 h-11 bg-gradient-to-br from-blue-600 to-blue-800 rounded-xl flex items-center justify-center shadow-md shadow-blue-200">
              <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                  d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
              </svg>
            </div>
            <div>
              <p class="text-xs font-bold text-gray-500 uppercase tracking-widest">Email</p>
              <p class="text-gray-800 font-semibold text-sm">myrhotipagad@gmail.com</p>
            </div>
          </div>

          <div class="flex items-center gap-4 p-4 bg-indigo-50 rounded-xl border border-indigo-100">
            <div class="flex-shrink-0 w-11 h-11 bg-gradient-to-br from-indigo-500 to-indigo-700 rounded-xl flex items-center justify-center shadow-md shadow-indigo-200">
              <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                  d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
              </svg>
            </div>
            <div>
              <p class="text-xs font-bold text-gray-500 uppercase tracking-widest">Phone</p>
              <p class="text-gray-800 font-semibold text-sm">09972905180</p>
            </div>
          </div>

          <div class="flex items-center gap-4 p-4 bg-teal-50 rounded-xl border border-teal-100">
            <div class="flex-shrink-0 w-11 h-11 bg-gradient-to-br from-teal-500 to-teal-700 rounded-xl flex items-center justify-center shadow-md shadow-teal-200">
              <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                  d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
              </svg>
            </div>
            <div>
              <p class="text-xs font-bold text-gray-500 uppercase tracking-widest">Address</p>
              <p class="text-gray-800 font-semibold text-sm">Victorias City, Negros Occidental</p>
            </div>
          </div>
        </div>

        <!-- Map placeholder -->
        <div class="rounded-2xl overflow-hidden border border-gray-200 bg-gradient-to-br from-blue-50 to-indigo-50 h-44 flex flex-col items-center justify-center gap-2 shadow-sm">
          <span class="text-4xl">&#128205;</span>
          <p class="text-sm font-semibold text-gray-700">Victorias City, Negros Occidental</p>
          <p class="text-xs text-gray-400">Philippines</p>
        </div>
      </div>

      <!-- Right: Contact Form -->
      <div class="bg-gray-50 rounded-2xl p-8 shadow-sm border border-gray-100">
        <h3 class="text-lg font-bold text-gray-900 mb-6">Send us a message</h3>
        <form onsubmit="return false;" class="space-y-5">
          <div>
            <label for="contact-name" class="block text-sm font-semibold text-gray-700 mb-1.5">Full Name</label>
            <input type="text" id="contact-name" name="name" placeholder="e.g. Maria Santos"
              class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white transition shadow-sm" />
          </div>
          <div>
            <label for="contact-email" class="block text-sm font-semibold text-gray-700 mb-1.5">Email Address</label>
            <input type="email" id="contact-email" name="email" placeholder="you@example.com"
              class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white transition shadow-sm" />
          </div>
          <div>
            <label for="contact-message" class="block text-sm font-semibold text-gray-700 mb-1.5">Message</label>
            <textarea id="contact-message" name="message" rows="5" placeholder="How can we help you?"
              class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white transition shadow-sm resize-none"></textarea>
          </div>
          <button type="submit"
            class="w-full py-3.5 bg-gradient-to-r from-blue-700 to-indigo-600 hover:from-blue-800 hover:to-indigo-700 text-white font-bold rounded-xl transition-all shadow-md shadow-blue-200 text-sm flex items-center justify-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
            </svg>
            Send Message
          </button>
        </form>
      </div>

    </div>
  </div>
</section>

<!-- ===================================================
     FOOTER
=================================================== -->
<footer class="bg-gray-900 text-gray-400">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-10 mb-10">

      <!-- Brand -->
      <div>
        <a href="#home" class="flex items-center gap-2.5 text-white font-extrabold text-xl mb-3">
          <div class="w-9 h-9 bg-gradient-to-br from-blue-600 to-indigo-600 rounded-xl flex items-center justify-center">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round"
                d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
            </svg>
          </div>
          BEED Portal
        </a>
        <p class="text-sm text-gray-500 leading-relaxed">Plan Smart. Teach Better.<br/>The DepEd-ready toolkit for BEED students.</p>
      </div>

      <!-- Quick Links -->
      <div>
        <h4 class="text-white font-bold text-sm uppercase tracking-widest mb-4">Quick Links</h4>
        <nav class="flex flex-col gap-2 text-sm">
          <a href="#home"     class="hover:text-white transition-colors">Home</a>
          <a href="#services" class="hover:text-white transition-colors">Services</a>
          <a href="#events"   class="hover:text-white transition-colors">Events</a>
          <a href="#contact"  class="hover:text-white transition-colors">Contact Us</a>
        </nav>
      </div>

      <!-- Account -->
      <div>
        <h4 class="text-white font-bold text-sm uppercase tracking-widest mb-4">Account</h4>
        <div class="flex flex-col gap-3">
          <a href="/DEMO%20MAKER%20AND%20LESSON%20PLAN%20MAKER/login"
             class="inline-flex items-center justify-center px-5 py-2.5 bg-blue-700 hover:bg-blue-600 text-white text-sm font-semibold rounded-lg transition-colors">
            Login
          </a>
          <a href="/DEMO%20MAKER%20AND%20LESSON%20PLAN%20MAKER/register"
             class="inline-flex items-center justify-center px-5 py-2.5 border border-gray-600 hover:border-gray-400 text-gray-300 hover:text-white text-sm font-semibold rounded-lg transition-colors">
            Register for Free
          </a>
        </div>
      </div>

    </div>

    <div class="border-t border-gray-800 pt-6 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-gray-600">
      <span>BEED Portal &copy; 2026 &mdash; Built for BEED Students</span>
      <span>Powered by dedication to quality education.</span>
    </div>
  </div>
</footer>

<!-- ===================================================
     SCRIPTS
=================================================== -->
<script>
  function toggleMenu() {
    var menu  = document.getElementById('mobile-menu');
    var btn   = document.getElementById('hamburger-btn');
    var open  = document.getElementById('icon-open');
    var close = document.getElementById('icon-close');
    var isHidden = menu.classList.contains('hidden');
    menu.classList.toggle('hidden', !isHidden);
    open.classList.toggle('hidden', isHidden);
    close.classList.toggle('hidden', !isHidden);
    btn.setAttribute('aria-expanded', isHidden ? 'true' : 'false');
  }

  function closeMenu() {
    var menu  = document.getElementById('mobile-menu');
    var open  = document.getElementById('icon-open');
    var close = document.getElementById('icon-close');
    menu.classList.add('hidden');
    open.classList.remove('hidden');
    close.classList.add('hidden');
    document.getElementById('hamburger-btn').setAttribute('aria-expanded', 'false');
  }

  window.addEventListener('resize', function () {
    if (window.innerWidth >= 768) closeMenu();
  });
</script>

</body>
</html>
