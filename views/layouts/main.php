<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle ?? 'BEED Student Portal') ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Custom scrollbar */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: #f1f5f9; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
        /* Smooth transitions */
        * { transition-property: color, background-color, border-color, box-shadow; transition-duration: 150ms; }
        /* Active nav link */
        .nav-link-active { background-color: rgba(255,255,255,0.15) !important; }
    </style>
</head>
<body class="bg-slate-50 min-h-screen font-sans antialiased">

    <!-- Top Navigation Bar -->
    <nav class="bg-gradient-to-r from-blue-800 to-blue-700 text-white shadow-lg sticky top-0 z-40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">

                <!-- Brand -->
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                    </div>
                    <a href="<?= url('/dashboard') ?>" class="text-lg font-bold tracking-tight hover:text-blue-100 transition-colors">
                        BEED Portal
                    </a>
                </div>

                <!-- Desktop Nav -->
                <div class="hidden md:flex items-center gap-1">
                    <?php
                    $currentPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
                    $currentPath = urldecode($currentPath);
                    $base = defined('APP_BASE') ? APP_BASE : '';
                    $relPath = $base !== '' && str_starts_with($currentPath, $base) ? substr($currentPath, strlen($base)) : $currentPath;

                    $navLinks = [
                        '/dashboard'    => ['Dashboard', 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
                        '/demos'        => ['Demo Maker', 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'],
                        '/lesson-plans' => ['Lesson Plans', 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2'],
                        '/templates'    => ['LP Templates', 'M4 6h16M4 10h16M4 14h10'],
                        '/demo-templates' => ['Demo Templates', 'M4 6h16M4 10h16M4 14h10'],
                    ];
                    foreach ($navLinks as $path => [$label, $icon]):
                        $isActive = str_starts_with($relPath, $path);
                        $cls = $isActive ? 'nav-link-active text-white' : 'text-blue-100 hover:bg-white/10 hover:text-white';
                    ?>
                    <a href="<?= url($path) ?>"
                       class="flex items-center gap-1.5 px-3 py-2 rounded-lg text-sm font-medium <?= $cls ?>">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="<?= $icon ?>"/>
                        </svg>
                        <?= $label ?>
                    </a>
                    <?php endforeach; ?>
                </div>

                <!-- Desktop: User + Logout -->
                <div class="hidden md:flex items-center gap-3">
                    <?php if (!empty($_SESSION['student_name'])): ?>
                        <a href="<?= url('/profile') ?>" class="flex items-center gap-2 px-3 py-1.5 rounded-lg hover:bg-white/10 transition-colors">
                            <div class="w-7 h-7 rounded-full bg-white/20 flex items-center justify-center text-xs font-bold text-white flex-shrink-0">
                                <?= strtoupper(substr($_SESSION['student_name'], 0, 1)) ?>
                            </div>
                            <span class="text-sm text-blue-100 max-w-[120px] truncate">
                                <?= htmlspecialchars($_SESSION['student_name']) ?>
                            </span>
                        </a>
                    <?php endif; ?>
                    <form method="POST" action="<?= url('/logout') ?>">
                        <button type="submit"
                                class="flex items-center gap-1.5 px-3 py-1.5 bg-white/10 hover:bg-white/20 text-white rounded-lg text-sm font-medium border border-white/20">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                            Logout
                        </button>
                    </form>
                </div>

                <!-- Hamburger -->
                <button id="hamburger-btn" type="button" aria-label="Toggle navigation menu" aria-expanded="false" aria-controls="mobile-menu"
                        class="md:hidden inline-flex items-center justify-center p-2 rounded-lg hover:bg-white/10 focus:outline-none focus:ring-2 focus:ring-white/50">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div id="mobile-menu" class="hidden md:hidden border-t border-white/10 bg-blue-800/50 backdrop-blur-sm">
            <div class="px-4 py-3 space-y-1">
                <?php foreach ($navLinks as $path => [$label, $icon]):
                    $isActive = str_starts_with($relPath, $path);
                    $cls = $isActive ? 'bg-white/15 text-white' : 'text-blue-100 hover:bg-white/10 hover:text-white';
                ?>
                <a href="<?= url($path) ?>" class="flex items-center gap-2 px-3 py-2.5 rounded-lg text-sm font-medium <?= $cls ?>">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="<?= $icon ?>"/>
                    </svg>
                    <?= $label ?>
                </a>
                <?php endforeach; ?>
                <div class="pt-2 mt-2 border-t border-white/10 flex items-center justify-between">
                    <?php if (!empty($_SESSION['student_name'])): ?>
                        <a href="<?= url('/profile') ?>" class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-white/10 text-blue-100 text-sm">
                            <div class="w-6 h-6 rounded-full bg-white/20 flex items-center justify-center text-xs font-bold text-white">
                                <?= strtoupper(substr($_SESSION['student_name'], 0, 1)) ?>
                            </div>
                            <?= htmlspecialchars($_SESSION['student_name']) ?>
                        </a>
                    <?php endif; ?>
                    <form method="POST" action="<?= url('/logout') ?>">
                        <button type="submit" class="px-3 py-2 text-sm text-blue-100 hover:text-white hover:bg-white/10 rounded-lg">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <!-- Page Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <?= $content ?? '' ?>
    </main>

    <script>
        (function () {
            var btn  = document.getElementById('hamburger-btn');
            var menu = document.getElementById('mobile-menu');
            btn.addEventListener('click', function () {
                menu.classList.toggle('hidden');
                btn.setAttribute('aria-expanded', !menu.classList.contains('hidden') ? 'true' : 'false');
            });
        })();
    </script>
</body>
</html>
