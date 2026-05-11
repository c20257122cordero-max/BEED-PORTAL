<?php
declare(strict_types=1);

$pageTitle = 'Dashboard – BEED Student Portal';

ob_start();
?>

<!-- Page header -->
<div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">
            Good <?= (date('H') < 12) ? 'morning' : ((date('H') < 17) ? 'afternoon' : 'evening') ?>,
            <?= htmlspecialchars(explode(' ', $studentName)[0]) ?>! 👋
        </h1>
        <p class="mt-1 text-sm text-slate-500">Here's an overview of your teaching materials.</p>
    </div>
    <div class="flex items-center gap-3">
        <a href="<?= url('/demos/create') ?>"
           class="inline-flex items-center gap-2 px-4 py-2 bg-blue-700 hover:bg-blue-800 text-white text-sm font-medium rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            New Demo
        </a>
        <a href="<?= url('/lesson-plans/create') ?>"
           class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            New Lesson Plan
        </a>
    </div>
</div>

<!-- Stats row -->
<div class="grid grid-cols-2 gap-4 mb-8">
    <?php
    $totalDemos = count($recentDemos);
    $totalPlans = count($recentLessonPlans);
    $stats = [
        ['Recent Demos',     $totalDemos, 'bg-blue-50',   'text-blue-700',   'border-blue-100',   'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'],
        ['Recent Plans',     $totalPlans, 'bg-indigo-50', 'text-indigo-700', 'border-indigo-100', 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2'],
    ];
    foreach ($stats as [$label, $count, $bg, $text, $border, $icon]):
    ?>
    <div class="<?= $bg ?> border <?= $border ?> rounded-2xl p-5">
        <div class="flex items-center justify-between mb-3">
            <p class="text-xs font-medium text-slate-500 uppercase tracking-wide"><?= $label ?></p>
            <div class="w-8 h-8 <?= $bg ?> rounded-lg flex items-center justify-center">
                <svg class="w-4 h-4 <?= $text ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="<?= $icon ?>"/>
                </svg>
            </div>
        </div>
        <p class="text-3xl font-bold <?= $text ?>"><?= $count ?></p>
    </div>
    <?php endforeach; ?>
</div>

<!-- Quick actions -->
<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
    <?php
    $quickActions = [
        [url('/demos/create'),        'New Demo',         'Create a teaching demonstration plan',    'bg-blue-700',   'M12 4v16m8-8H4'],
        [url('/lesson-plans/create'), 'New Lesson Plan',  'Build a detailed DepEd lesson plan',      'bg-indigo-600', 'M12 4v16m8-8H4'],
        [url('/templates'),           'My Templates',     'Browse and apply your saved templates',   'bg-purple-600', 'M4 6h16M4 10h16M4 14h10'],
    ];
    foreach ($quickActions as [$href, $title, $desc, $color, $icon]):
    ?>
    <a href="<?= $href ?>" class="group flex items-center gap-4 bg-white border border-slate-200 rounded-2xl p-5 hover:shadow-md hover:border-slate-300 transition-all">
        <div class="w-10 h-10 <?= $color ?> rounded-xl flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="<?= $icon ?>"/>
            </svg>
        </div>
        <div class="min-w-0">
            <p class="text-sm font-semibold text-gray-800 group-hover:text-blue-700"><?= $title ?></p>
            <p class="text-xs text-slate-500 truncate"><?= $desc ?></p>
        </div>
        <svg class="w-4 h-4 text-slate-300 group-hover:text-blue-500 ml-auto flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
    </a>
    <?php endforeach; ?>
</div>

<!-- Recent items grid -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

    <!-- Recent Demos -->
    <section>
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100">
                <div class="flex items-center gap-2">
                    <div class="w-6 h-6 bg-blue-100 rounded-md flex items-center justify-center">
                        <svg class="w-3.5 h-3.5 text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                    <h2 class="text-sm font-semibold text-gray-800">Recent Demos</h2>
                </div>
                <a href="<?= url('/demos') ?>" class="text-xs font-medium text-blue-600 hover:text-blue-800">View all →</a>
            </div>
            <ul class="divide-y divide-slate-100">
                <?php if (empty($recentDemos)): ?>
                    <li class="px-6 py-10 text-center">
                        <svg class="mx-auto w-10 h-10 text-slate-200 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        <p class="text-sm text-slate-400">No demos yet.</p>
                        <a href="<?= url('/demos/create') ?>" class="mt-2 inline-block text-xs text-blue-600 hover:text-blue-800 font-medium">Create your first demo →</a>
                    </li>
                <?php else: ?>
                    <?php foreach ($recentDemos as $demo):
                        $statusMap = ['draft'=>['bg-slate-100 text-slate-600','Draft'],'for_review'=>['bg-amber-100 text-amber-700','For Review'],'submitted'=>['bg-green-100 text-green-700','Submitted']];
                        [$sCls,$sLabel] = $statusMap[$demo['status']??'draft'] ?? ['bg-slate-100 text-slate-500','—'];
                    ?>
                    <li>
                        <a href="<?= url('/demos/' . (int)$demo['id'] . '/edit') ?>"
                           class="flex items-center gap-4 px-6 py-3.5 hover:bg-slate-50 group">
                            <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0 group-hover:bg-blue-200 transition-colors">
                                <svg class="w-4 h-4 text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="text-sm font-medium text-gray-800 group-hover:text-blue-700 truncate"><?= htmlspecialchars($demo['title']) ?></p>
                                <p class="text-xs text-slate-400 mt-0.5"><?= htmlspecialchars($demo['subject'] ?: '—') ?> · Grade <?= htmlspecialchars($demo['grade_level'] ?: '—') ?></p>
                            </div>
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium <?= $sCls ?> flex-shrink-0"><?= $sLabel ?></span>
                        </a>
                    </li>
                    <?php endforeach; ?>
                <?php endif; ?>
            </ul>
            <div class="px-6 py-3 bg-slate-50 border-t border-slate-100">
                <a href="<?= url('/demos/create') ?>" class="text-xs font-medium text-blue-600 hover:text-blue-800">+ New Demo</a>
            </div>
        </div>
    </section>

    <!-- Recent Lesson Plans -->
    <section>
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100">
                <div class="flex items-center gap-2">
                    <div class="w-6 h-6 bg-indigo-100 rounded-md flex items-center justify-center">
                        <svg class="w-3.5 h-3.5 text-indigo-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    </div>
                    <h2 class="text-sm font-semibold text-gray-800">Recent Lesson Plans</h2>
                </div>
                <a href="<?= url('/lesson-plans') ?>" class="text-xs font-medium text-blue-600 hover:text-blue-800">View all →</a>
            </div>
            <ul class="divide-y divide-slate-100">
                <?php if (empty($recentLessonPlans)): ?>
                    <li class="px-6 py-10 text-center">
                        <svg class="mx-auto w-10 h-10 text-slate-200 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                        <p class="text-sm text-slate-400">No lesson plans yet.</p>
                        <a href="<?= url('/lesson-plans/create') ?>" class="mt-2 inline-block text-xs text-blue-600 hover:text-blue-800 font-medium">Create your first lesson plan →</a>
                    </li>
                <?php else: ?>
                    <?php foreach ($recentLessonPlans as $plan):
                        $statusMap2 = ['draft'=>['bg-slate-100 text-slate-600','Draft'],'for_review'=>['bg-amber-100 text-amber-700','For Review'],'submitted'=>['bg-green-100 text-green-700','Submitted']];
                        [$sCls2,$sLabel2] = $statusMap2[$plan['status']??'draft'] ?? ['bg-slate-100 text-slate-500','—'];
                    ?>
                    <li>
                        <a href="<?= url('/lesson-plans/' . (int)$plan['id'] . '/edit') ?>"
                           class="flex items-center gap-4 px-6 py-3.5 hover:bg-slate-50 group">
                            <div class="w-8 h-8 bg-indigo-100 rounded-lg flex items-center justify-center flex-shrink-0 group-hover:bg-indigo-200 transition-colors">
                                <svg class="w-4 h-4 text-indigo-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="text-sm font-medium text-gray-800 group-hover:text-blue-700 truncate"><?= htmlspecialchars($plan['title']) ?></p>
                                <p class="text-xs text-slate-400 mt-0.5"><?= htmlspecialchars($plan['subject'] ?: '—') ?> · Grade <?= htmlspecialchars($plan['grade_level'] ?: '—') ?></p>
                            </div>
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium <?= $sCls2 ?> flex-shrink-0"><?= $sLabel2 ?></span>
                        </a>
                    </li>
                    <?php endforeach; ?>
                <?php endif; ?>
            </ul>
            <div class="px-6 py-3 bg-slate-50 border-t border-slate-100">
                <a href="<?= url('/lesson-plans/create') ?>" class="text-xs font-medium text-blue-600 hover:text-blue-800">+ New Lesson Plan</a>
            </div>
        </div>
    </section>

</div>

<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/main.php';
