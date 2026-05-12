<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In — BEED Student Portal</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @keyframes fadeInUp { from { opacity:0; transform:translateY(24px); } to { opacity:1; transform:translateY(0); } }
        @keyframes fadeInLeft { from { opacity:0; transform:translateX(-24px); } to { opacity:1; transform:translateX(0); } }
        @keyframes float { 0%,100%{transform:translateY(0)} 50%{transform:translateY(-14px)} }
        @keyframes shimmer { 0%{background-position:-200% 0} 100%{background-position:200% 0} }
        .fade-up { animation: fadeInUp 0.7s ease both; }
        .fade-left { animation: fadeInLeft 0.7s ease both; }
        .delay-1 { animation-delay: 0.1s; }
        .delay-2 { animation-delay: 0.2s; }
        .delay-3 { animation-delay: 0.3s; }
        .delay-4 { animation-delay: 0.4s; }
        .delay-5 { animation-delay: 0.5s; }
        .float-shape { animation: float 6s ease-in-out infinite; }
        .float-shape-2 { animation: float 8s ease-in-out infinite reverse; }
        input:focus { transition: box-shadow 0.2s ease, border-color 0.2s ease; }
        .btn-glow:hover { box-shadow: 0 0 20px rgba(59,130,246,0.5); }
    </style>
</head>
<body class="min-h-screen bg-slate-50 flex">

    <!-- Left panel — branding (hidden on mobile) -->
    <div class="hidden lg:flex lg:w-1/2 bg-gradient-to-br from-blue-800 via-blue-700 to-indigo-700 flex-col justify-between p-12 text-white relative overflow-hidden">
        <!-- Floating decorative shapes -->
        <div class="float-shape absolute top-16 right-16 w-32 h-32 bg-white/10 rounded-full pointer-events-none"></div>
        <div class="float-shape-2 absolute bottom-32 left-8 w-20 h-20 bg-white/10 rounded-full pointer-events-none"></div>
        <div class="float-shape absolute top-1/2 right-8 w-12 h-12 bg-white/10 rounded-xl pointer-events-none"></div>
        <div>
            <div class="flex items-center gap-3 mb-12">
                <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                </div>
                <span class="text-xl font-bold tracking-tight">BEED Portal</span>
            </div>
            <h1 class="text-4xl font-bold leading-tight mb-4">Your teaching toolkit,<br>all in one place.</h1>
            <p class="text-blue-200 text-lg leading-relaxed">Create structured demo plans and detailed lesson plans for your practicum — faster and smarter.</p>
        </div>
        <div class="space-y-4">
            <?php foreach ([['Demo Maker','Build step-by-step teaching demonstration plans'],['Lesson Planner','Create DepEd-aligned detailed lesson plans'],['Templates','Save and reuse your best lesson structures']] as [$feat, $desc]): ?>
            <div class="flex items-start gap-3">
                <div class="w-6 h-6 rounded-full bg-white/20 flex items-center justify-center flex-shrink-0 mt-0.5">
                    <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                </div>
                <div>
                    <p class="text-sm font-semibold text-white"><?= $feat ?></p>
                    <p class="text-xs text-blue-300"><?= $desc ?></p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Right panel — form -->
    <div class="flex-1 flex items-center justify-center px-6 py-12">
        <div class="w-full max-w-md">

            <!-- Mobile logo -->
            <div class="lg:hidden flex items-center gap-2 mb-8">
                <div class="w-8 h-8 bg-blue-700 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                </div>
                <span class="text-lg font-bold text-gray-800">BEED Portal</span>
            </div>

            <div class="mb-8 fade-up">
                <h2 class="text-2xl font-bold text-gray-900">Welcome back</h2>
                <p class="mt-1 text-sm text-gray-500">Sign in to your student account</p>
            </div>

            <?php if (!empty($error)): ?>
                <div class="mb-5 flex items-start gap-3 rounded-xl bg-red-50 border border-red-200 px-4 py-3">
                    <svg class="w-5 h-5 text-red-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <p class="text-sm text-red-700"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></p>
                </div>
            <?php endif; ?>

            <form method="POST" action="<?= url('/login') ?>" novalidate class="space-y-5 fade-up delay-2">
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1.5">Email Address</label>
                    <input type="email" id="email" name="email"
                        value="<?= htmlspecialchars($_POST['email'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                        required autocomplete="email"
                        class="w-full rounded-xl border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        placeholder="you@example.com">
                </div>
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1.5">Password</label>
                    <input type="password" id="password" name="password"
                        required autocomplete="current-password"
                        class="w-full rounded-xl border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        placeholder="••••••••">
                </div>
                <button type="submit"
                    class="w-full rounded-xl bg-blue-700 hover:bg-blue-800 active:bg-blue-900 text-white font-semibold text-sm py-3 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 btn-glow transition-all duration-200">
                    Sign In
                </button>
            </form>

            <p class="mt-6 text-center text-sm text-gray-500">
                Don't have an account?
                <a href="<?= url('/register') ?>" class="text-blue-700 hover:text-blue-800 font-semibold">Create one →</a>
            </p>
            <p class="mt-3 text-center">
                <a href="/DEMO%20MAKER%20AND%20LESSON%20PLAN%20MAKER/landing.php" class="text-xs text-gray-400 hover:text-blue-600 transition-colors">← Back to Home</a>
            </p>
        </div>
    </div>

</body>
</html>
