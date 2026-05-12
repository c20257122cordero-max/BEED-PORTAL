<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account — BEED Student Portal</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-slate-50 flex">

    <!-- Left panel -->
    <div class="hidden lg:flex lg:w-1/2 bg-gradient-to-br from-blue-800 via-blue-700 to-indigo-700 flex-col justify-between p-12 text-white">
        <div>
            <div class="flex items-center gap-3 mb-12">
                <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                </div>
                <span class="text-xl font-bold tracking-tight">BEED Portal</span>
            </div>
            <h1 class="text-4xl font-bold leading-tight mb-4">Start your teaching journey today.</h1>
            <p class="text-blue-200 text-lg leading-relaxed">Join BEED students who use the portal to prepare professional demo plans and lesson plans for their practicum.</p>
        </div>
        <div class="bg-white/10 rounded-2xl p-6 border border-white/20">
            <p class="text-sm text-blue-100 italic">"The BEED Portal helped me organize all my lesson plans in one place. The templates saved me so much time!"</p>
            <p class="mt-3 text-xs text-blue-300 font-medium">— BEED Student, 3rd Year</p>
        </div>
    </div>

    <!-- Right panel -->
    <div class="flex-1 flex items-center justify-center px-6 py-12">
        <div class="w-full max-w-md">

            <div class="lg:hidden flex items-center gap-2 mb-8">
                <div class="w-8 h-8 bg-blue-700 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                </div>
                <span class="text-lg font-bold text-gray-800">BEED Portal</span>
            </div>

            <div class="mb-8">
                <h2 class="text-2xl font-bold text-gray-900">Create your account</h2>
                <p class="mt-1 text-sm text-gray-500">Free for all BEED students</p>
            </div>

            <?php if (!empty($errors['general'])): ?>
                <div class="mb-5 flex items-start gap-3 rounded-xl bg-red-50 border border-red-200 px-4 py-3">
                    <svg class="w-5 h-5 text-red-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <p class="text-sm text-red-700"><?= htmlspecialchars($errors['general'], ENT_QUOTES, 'UTF-8') ?></p>
                </div>
            <?php endif; ?>

            <form method="POST" action="<?= url('/register') ?>" novalidate class="space-y-5">
                <div>
                    <label for="full_name" class="block text-sm font-medium text-gray-700 mb-1.5">Full Name</label>
                    <input type="text" id="full_name" name="full_name"
                        value="<?= htmlspecialchars($old['full_name'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                        required autocomplete="name"
                        class="w-full rounded-xl border <?= !empty($errors['full_name']) ? 'border-red-400 bg-red-50' : 'border-gray-300 bg-white' ?> px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        placeholder="Juan dela Cruz">
                    <?php if (!empty($errors['full_name'])): ?>
                        <p class="mt-1.5 text-xs text-red-600 flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <?= htmlspecialchars($errors['full_name'], ENT_QUOTES, 'UTF-8') ?>
                        </p>
                    <?php endif; ?>
                </div>
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1.5">Email Address</label>
                    <input type="email" id="email" name="email"
                        value="<?= htmlspecialchars($old['email'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                        required autocomplete="email"
                        class="w-full rounded-xl border <?= !empty($errors['email']) ? 'border-red-400 bg-red-50' : 'border-gray-300 bg-white' ?> px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        placeholder="you@example.com">
                    <?php if (!empty($errors['email'])): ?>
                        <p class="mt-1.5 text-xs text-red-600 flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <?= htmlspecialchars($errors['email'], ENT_QUOTES, 'UTF-8') ?>
                        </p>
                    <?php endif; ?>
                </div>
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1.5">Password</label>
                    <input type="password" id="password" name="password"
                        required autocomplete="new-password"
                        class="w-full rounded-xl border <?= !empty($errors['password']) ? 'border-red-400 bg-red-50' : 'border-gray-300 bg-white' ?> px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        placeholder="At least 8 characters">
                    <?php if (!empty($errors['password'])): ?>
                        <p class="mt-1.5 text-xs text-red-600 flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <?= htmlspecialchars($errors['password'], ENT_QUOTES, 'UTF-8') ?>
                        </p>
                    <?php endif; ?>
                </div>
                <button type="submit"
                    class="w-full rounded-xl bg-blue-700 hover:bg-blue-800 active:bg-blue-900 text-white font-semibold text-sm py-3 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                    Create Account
                </button>
            </form>

            <p class="mt-6 text-center text-sm text-gray-500">
                Already have an account?
                <a href="<?= url('/login') ?>" class="text-blue-700 hover:text-blue-800 font-semibold">Sign in →</a>
            </p>
            <p class="mt-3 text-center">
                <a href="/DEMO%20MAKER%20AND%20LESSON%20PLAN%20MAKER/landing.php" class="text-xs text-gray-400 hover:text-blue-600 transition-colors">← Back to Home</a>
            </p>
        </div>
    </div>

</body>
</html>
