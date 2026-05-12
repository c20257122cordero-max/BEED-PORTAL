<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>BEED Student Portal — Plan Smart. Teach Better.</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    html { scroll-behavior: smooth; }
    /* Hamburger animation */
    #menu-toggle:checked ~ #mobile-menu { display: block; }
  </style>
</head>
<body class="font-sans text-gray-800 antialiased">

  <!-- ===== STICKY NAV ===== -->
  <nav class="sticky top-0 z-50 bg-white shadow-md">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex items-center justify-between h-16">

        <!-- Logo -->
        <a href="#home" class="flex items-center gap-2 text-blue-700 font-bold text-xl tracking-tight">
          <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round"
              d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
          </svg>
          BEED Portal
        </a>

        <!-- Desktop nav links -->
        <div class="hidden md:flex items-center gap-6 text-sm font-medium text-gray-600">
          <a href="#home"     class="hover:text-blue-700 transition-colors">Home</a>
          <a href="#services" class="hover:text-blue-700 transition-colors">Services</a>
          <a href="#events"   class="hover:text-blue-700 transition-colors">Events</a>
          <a href="#contact"  class="hover:text-blue-700 transition-colors">Contact Us</a>
          <a href="/DEMO%20MAKER%20AND%20LESSON%20PLAN%20MAKER/login"
             class="ml-2 px-4 py-2 bg-blue-700 hover:bg-blue-800 text-white rounded-lg transition-colors shadow-sm">
            Login
          </a>
        </div>

        <!-- Mobile hamburger -->
        <button id="hamburger-btn" onclick="toggleMenu()"
          class="md:hidden p-2 rounded-md text-gray-600 hover:text-blue-700 hover:bg-gray-100 transition-colors"
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
    <div id="mobile-menu" class="hidden md:hidden bg-white border-t border-gray-100 px-4 pb-4">
      <div class="flex flex-col gap-3 pt-3 text-sm font-medium text-gray-600">
        <a href="#home"     onclick="closeMenu()" class="hover:text-blue-700 transition-colors py-1">Home</a>
        <a href="#services" onclick="closeMenu()" class="hover:text-blue-700 transition-colors py-1">Services</a>
        <a href="#events"   onclick="closeMenu()" class="hover:text-blue-700 transition-colors py-1">Events</a>
        <a href="#contact"  onclick="closeMenu()" class="hover:text-blue-700 transition-colors py-1">Contact Us</a>
        <a href="/DEMO%20MAKER%20AND%20LESSON%20PLAN%20MAKER/login"
           class="mt-1 px-4 py-2 bg-blue-700 hover:bg-blue-800 text-white rounded-lg text-center transition-colors">
          Login
        </a>
      </div>
    </div>
  </nav>

  <!-- ===== HERO SECTION ===== -->
  <section id="home" class="relative bg-gradient-to-br from-blue-800 to-indigo-700 text-white overflow-hidden">
    <!-- Decorative blobs -->
    <div class="absolute inset-0 opacity-10 pointer-events-none">
      <div class="absolute -top-24 -left-24 w-96 h-96 bg-white rounded-full"></div>
      <div class="absolute -bottom-32 -right-32 w-[32rem] h-[32rem] bg-white rounded-full"></div>
    </div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-28 md:py-40 text-center">
      <span class="inline-block mb-4 px-3 py-1 bg-white/20 text-white text-xs font-semibold rounded-full tracking-widest uppercase">
        BEED Student Portal
      </span>
      <h1 class="text-4xl sm:text-5xl md:text-6xl font-extrabold leading-tight mb-6 drop-shadow-sm">
        Plan Smart.<br class="hidden sm:block" /> Teach Better.
      </h1>
      <p class="text-lg sm:text-xl text-blue-100 max-w-2xl mx-auto mb-10">
        The DepEd-ready teaching toolkit for BEED students. Build demo plans, craft detailed lesson plans, and manage your teaching practice — all in one place.
      </p>
      <div class="flex flex-col sm:flex-row gap-4 justify-center">
        <a href="/DEMO%20MAKER%20AND%20LESSON%20PLAN%20MAKER/login"
           class="px-8 py-3 bg-white text-blue-800 font-semibold rounded-lg shadow-lg hover:bg-blue-50 transition-colors text-base">
          Get Started
        </a>
        <a href="#services"
           class="px-8 py-3 border-2 border-white text-white font-semibold rounded-lg hover:bg-white/10 transition-colors text-base">
          Learn More
        </a>
      </div>
    </div>
  </section>

  <!-- ===== SERVICES SECTION ===== -->
  <section id="services" class="bg-white py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="text-center mb-14">
        <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-3">Our Services</h2>
        <p class="text-gray-500 max-w-xl mx-auto">Everything you need to prepare, plan, and deliver outstanding lessons.</p>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-3 gap-8">

        <!-- Card 1: Demo Maker -->
        <div class="group bg-white border border-gray-100 rounded-2xl p-8 shadow-sm hover:shadow-lg transition-shadow text-center">
          <div class="inline-flex items-center justify-center w-14 h-14 bg-blue-100 text-blue-700 rounded-xl mb-5 group-hover:bg-blue-700 group-hover:text-white transition-colors">
            <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round"
                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
          </div>
          <h3 class="text-xl font-bold text-gray-900 mb-3">Demo Maker</h3>
          <p class="text-gray-500 leading-relaxed">
            Create structured teaching demonstration plans with step-by-step procedures tailored to DepEd standards.
          </p>
        </div>

        <!-- Card 2: Lesson Plan Planner -->
        <div class="group bg-white border border-gray-100 rounded-2xl p-8 shadow-sm hover:shadow-lg transition-shadow text-center">
          <div class="inline-flex items-center justify-center w-14 h-14 bg-indigo-100 text-indigo-700 rounded-xl mb-5 group-hover:bg-indigo-700 group-hover:text-white transition-colors">
            <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round"
                d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
            </svg>
          </div>
          <h3 class="text-xl font-bold text-gray-900 mb-3">Lesson Plan Planner</h3>
          <p class="text-gray-500 leading-relaxed">
            Build complete DepEd-aligned Detailed Lesson Plans in minutes with guided fields and smart formatting.
          </p>
        </div>

        <!-- Card 3: Templates -->
        <div class="group bg-white border border-gray-100 rounded-2xl p-8 shadow-sm hover:shadow-lg transition-shadow text-center">
          <div class="inline-flex items-center justify-center w-14 h-14 bg-teal-100 text-teal-700 rounded-xl mb-5 group-hover:bg-teal-700 group-hover:text-white transition-colors">
            <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round"
                d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
            </svg>
          </div>
          <h3 class="text-xl font-bold text-gray-900 mb-3">Templates</h3>
          <p class="text-gray-500 leading-relaxed">
            Save and reuse your best lesson structures for future classes. Build a personal library of ready-to-use plans.
          </p>
        </div>

      </div>
    </div>
  </section>

  <!-- ===== EVENTS SECTION ===== -->
  <section id="events" class="bg-gray-50 py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="text-center mb-14">
        <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-3">Upcoming Events</h2>
        <p class="text-gray-500 max-w-xl mx-auto">Stay updated with the latest activities and workshops for BEED students.</p>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-3 gap-8">

        <!-- Event 1 -->
        <div class="bg-white rounded-2xl shadow-sm hover:shadow-lg transition-shadow overflow-hidden flex flex-col">
          <div class="bg-gradient-to-r from-blue-700 to-blue-800 px-6 py-4 flex items-center gap-4">
            <div class="text-center bg-white/20 rounded-xl px-4 py-2 min-w-[56px]">
              <span class="block text-2xl font-extrabold text-white leading-none">20</span>
              <span class="block text-xs font-semibold text-blue-100 uppercase tracking-wide">May</span>
            </div>
            <span class="text-blue-100 text-sm font-medium">2026</span>
          </div>
          <div class="p-6 flex flex-col flex-1">
            <h3 class="text-lg font-bold text-gray-900 mb-2">BEED Practicum Orientation</h3>
            <p class="text-gray-500 text-sm leading-relaxed flex-1">
              Orientation for all BEED students on practicum requirements and schedules.
            </p>
            <a href="#" class="mt-5 inline-block text-sm font-semibold text-blue-700 hover:text-blue-900 transition-colors">
              Learn More &rarr;
            </a>
          </div>
        </div>

        <!-- Event 2 -->
        <div class="bg-white rounded-2xl shadow-sm hover:shadow-lg transition-shadow overflow-hidden flex flex-col">
          <div class="bg-gradient-to-r from-indigo-600 to-indigo-700 px-6 py-4 flex items-center gap-4">
            <div class="text-center bg-white/20 rounded-xl px-4 py-2 min-w-[56px]">
              <span class="block text-2xl font-extrabold text-white leading-none">5</span>
              <span class="block text-xs font-semibold text-indigo-100 uppercase tracking-wide">Jun</span>
            </div>
            <span class="text-indigo-100 text-sm font-medium">2026</span>
          </div>
          <div class="p-6 flex flex-col flex-1">
            <h3 class="text-lg font-bold text-gray-900 mb-2">Demo Teaching Workshop</h3>
            <p class="text-gray-500 text-sm leading-relaxed flex-1">
              Hands-on workshop on creating effective teaching demonstration plans.
            </p>
            <a href="#" class="mt-5 inline-block text-sm font-semibold text-blue-700 hover:text-blue-900 transition-colors">
              Learn More &rarr;
            </a>
          </div>
        </div>

        <!-- Event 3 -->
        <div class="bg-white rounded-2xl shadow-sm hover:shadow-lg transition-shadow overflow-hidden flex flex-col">
          <div class="bg-gradient-to-r from-teal-600 to-teal-700 px-6 py-4 flex items-center gap-4">
            <div class="text-center bg-white/20 rounded-xl px-4 py-2 min-w-[56px]">
              <span class="block text-2xl font-extrabold text-white leading-none">15</span>
              <span class="block text-xs font-semibold text-teal-100 uppercase tracking-wide">Jun</span>
            </div>
            <span class="text-teal-100 text-sm font-medium">2026</span>
          </div>
          <div class="p-6 flex flex-col flex-1">
            <h3 class="text-lg font-bold text-gray-900 mb-2">Lesson Plan Writing Seminar</h3>
            <p class="text-gray-500 text-sm leading-relaxed flex-1">
              Learn how to write DepEd-compliant Detailed Lesson Plans from experienced educators.
            </p>
            <a href="#" class="mt-5 inline-block text-sm font-semibold text-blue-700 hover:text-blue-900 transition-colors">
              Learn More &rarr;
            </a>
          </div>
        </div>

      </div>
    </div>
  </section>

  <!-- ===== CONTACT SECTION ===== -->
  <section id="contact" class="bg-white py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="text-center mb-14">
        <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-3">Contact Us</h2>
        <p class="text-gray-500 max-w-xl mx-auto">Have questions or need support? Reach out and we'll get back to you.</p>
      </div>

      <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-start">

        <!-- Contact Info -->
        <div class="space-y-8">
          <div>
            <h3 class="text-xl font-bold text-gray-900 mb-6">Get in Touch</h3>
            <p class="text-gray-500 leading-relaxed mb-8">
              We're here to help BEED students make the most of the portal. Whether you have a technical issue or a general inquiry, don't hesitate to reach out.
            </p>
          </div>

          <div class="flex items-start gap-4">
            <div class="flex-shrink-0 w-10 h-10 bg-blue-100 text-blue-700 rounded-lg flex items-center justify-center">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                  d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
              </svg>
            </div>
            <div>
              <p class="text-sm font-semibold text-gray-700">Email</p>
              <p class="text-gray-500 text-sm">beedportal@pnc.edu.ph</p>
            </div>
          </div>

          <div class="flex items-start gap-4">
            <div class="flex-shrink-0 w-10 h-10 bg-blue-100 text-blue-700 rounded-lg flex items-center justify-center">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                  d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
              </svg>
            </div>
            <div>
              <p class="text-sm font-semibold text-gray-700">Phone</p>
              <p class="text-gray-500 text-sm">(049) 562-0000</p>
            </div>
          </div>

          <div class="flex items-start gap-4">
            <div class="flex-shrink-0 w-10 h-10 bg-blue-100 text-blue-700 rounded-lg flex items-center justify-center">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                  d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
              </svg>
            </div>
            <div>
              <p class="text-sm font-semibold text-gray-700">Address</p>
              <p class="text-gray-500 text-sm">Pamantasan ng Cabuyao<br />Katapatan Subd., Banay-Banay, Cabuyao City, Laguna</p>
            </div>
          </div>
        </div>

        <!-- Contact Form -->
        <div class="bg-gray-50 rounded-2xl p-8 shadow-sm">
          <form onsubmit="return false;" class="space-y-5">
            <div>
              <label for="contact-name" class="block text-sm font-semibold text-gray-700 mb-1">Name</label>
              <input type="text" id="contact-name" name="name" placeholder="Your full name"
                class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white transition" />
            </div>
            <div>
              <label for="contact-email" class="block text-sm font-semibold text-gray-700 mb-1">Email</label>
              <input type="email" id="contact-email" name="email" placeholder="you@example.com"
                class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white transition" />
            </div>
            <div>
              <label for="contact-message" class="block text-sm font-semibold text-gray-700 mb-1">Message</label>
              <textarea id="contact-message" name="message" rows="5" placeholder="How can we help you?"
                class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white transition resize-none"></textarea>
            </div>
            <button type="submit"
              class="w-full py-3 bg-blue-700 hover:bg-blue-800 text-white font-semibold rounded-lg transition-colors shadow-sm text-sm">
              Send Message
            </button>
          </form>
        </div>

      </div>
    </div>
  </section>

  <!-- ===== FOOTER ===== -->
  <footer class="bg-gray-900 text-gray-400 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex flex-col md:flex-row items-center justify-between gap-6">

        <!-- Brand -->
        <div class="text-center md:text-left">
          <p class="text-white font-bold text-lg">BEED Portal &copy; 2026</p>
          <p class="text-sm mt-1 text-gray-500">Plan Smart. Teach Better.</p>
        </div>

        <!-- Nav links -->
        <nav class="flex flex-wrap justify-center gap-5 text-sm">
          <a href="#home"     class="hover:text-white transition-colors">Home</a>
          <a href="#services" class="hover:text-white transition-colors">Services</a>
          <a href="#events"   class="hover:text-white transition-colors">Events</a>
          <a href="#contact"  class="hover:text-white transition-colors">Contact Us</a>
          <a href="/DEMO%20MAKER%20AND%20LESSON%20PLAN%20MAKER/login"
             class="hover:text-white transition-colors">Login</a>
        </nav>

      </div>
      <div class="mt-8 border-t border-gray-800 pt-6 text-center text-xs text-gray-600">
        Built for BEED students. Powered by dedication to quality education.
      </div>
    </div>
  </footer>

  <!-- ===== MOBILE MENU SCRIPT ===== -->
  <script>
    function toggleMenu() {
      var menu = document.getElementById('mobile-menu');
      var btn  = document.getElementById('hamburger-btn');
      var open = document.getElementById('icon-open');
      var close = document.getElementById('icon-close');
      var isHidden = menu.classList.contains('hidden');
      menu.classList.toggle('hidden', !isHidden);
      open.classList.toggle('hidden', isHidden);
      close.classList.toggle('hidden', !isHidden);
      btn.setAttribute('aria-expanded', isHidden ? 'true' : 'false');
    }

    function closeMenu() {
      var menu = document.getElementById('mobile-menu');
      var open = document.getElementById('icon-open');
      var close = document.getElementById('icon-close');
      menu.classList.add('hidden');
      open.classList.remove('hidden');
      close.classList.add('hidden');
      document.getElementById('hamburger-btn').setAttribute('aria-expanded', 'false');
    }

    // Close mobile menu on resize to desktop
    window.addEventListener('resize', function () {
      if (window.innerWidth >= 768) closeMenu();
    });
  </script>

</body>
</html>
