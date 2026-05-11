<?php
declare(strict_types=1);

$pageTitle = 'My Lesson Plans – BEED Student Portal';

$statusMap = [
    'draft'      => ['bg-slate-100 text-slate-600',   'Draft'],
    'for_review' => ['bg-amber-100 text-amber-700',   'For Review'],
    'submitted'  => ['bg-green-100 text-green-700',   'Submitted'],
];

ob_start();
?>

<!-- Page header -->
<div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">My Lesson Plans</h1>
        <p class="mt-1 text-sm text-slate-500">
            <?= count($lessonPlans) ?> lesson plan<?= count($lessonPlans) !== 1 ? 's' : '' ?>
            <?= ($search !== '' || $status !== '') ? '(filtered)' : '' ?>
        </p>
    </div>
    <a href="<?= url('/lesson-plans/create') ?>"
       class="inline-flex items-center gap-2 px-4 py-2.5 bg-blue-700 hover:bg-blue-800 text-white text-sm font-medium rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        New Lesson Plan
    </a>
</div>

<!-- Search + filter bar -->
<form method="GET" action="<?= url('/lesson-plans') ?>" class="mb-6">
    <div class="flex flex-wrap gap-2">
        <div class="flex-1 min-w-0 relative">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            <input type="search" name="q" value="<?= htmlspecialchars($search) ?>"
                placeholder="Search by title, subject, or competency…"
                class="w-full pl-9 pr-4 py-2.5 border border-slate-300 rounded-xl text-sm text-gray-800 placeholder-slate-400 bg-white shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
        </div>
        <select name="status"
            class="px-3 py-2.5 border border-slate-300 rounded-xl text-sm text-gray-800 bg-white shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            <option value="" <?= $status === '' ? 'selected' : '' ?>>All Statuses</option>
            <option value="draft"      <?= $status === 'draft'      ? 'selected' : '' ?>>Draft</option>
            <option value="for_review" <?= $status === 'for_review' ? 'selected' : '' ?>>For Review</option>
            <option value="submitted"  <?= $status === 'submitted'  ? 'selected' : '' ?>>Submitted</option>
        </select>
        <button type="submit" class="px-4 py-2.5 bg-blue-700 hover:bg-blue-800 text-white text-sm font-medium rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
            Search
        </button>
        <?php if ($search !== '' || $status !== ''): ?>
            <a href="<?= url('/lesson-plans') ?>" class="px-4 py-2.5 bg-slate-200 hover:bg-slate-300 text-slate-700 text-sm font-medium rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-slate-400 focus:ring-offset-2">
                Clear
            </a>
        <?php endif; ?>
    </div>
</form>

<?php if (empty($lessonPlans)): ?>
    <!-- Empty state -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm px-6 py-16 text-center">
        <div class="w-16 h-16 bg-indigo-50 rounded-2xl flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
        </div>
        <?php if ($search !== '' || $status !== ''): ?>
            <p class="text-gray-600 font-medium mb-1">No lesson plans match your filters.</p>
            <p class="text-sm text-slate-400 mb-4">Try adjusting your search or clearing the filters.</p>
            <a href="<?= url('/lesson-plans') ?>" class="inline-flex items-center gap-1.5 px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-medium rounded-xl transition-colors">Clear filters</a>
        <?php else: ?>
            <p class="text-gray-600 font-medium mb-1">No lesson plans yet.</p>
            <p class="text-sm text-slate-400 mb-4">Create your first lesson plan to get started.</p>
            <a href="<?= url('/lesson-plans/create') ?>" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-700 hover:bg-blue-800 text-white text-sm font-medium rounded-xl transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Create Lesson Plan
            </a>
        <?php endif; ?>
    </div>

<?php else: ?>

    <!-- Card grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
        <?php foreach ($lessonPlans as $lp):
            [$sCls, $sLabel] = $statusMap[$lp['status'] ?? 'draft'] ?? ['bg-slate-100 text-slate-500', $lp['status'] ?? ''];
        ?>
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm hover:shadow-md hover:border-slate-300 transition-all flex flex-col overflow-hidden">

            <!-- Card top accent -->
            <div class="h-1 bg-gradient-to-r from-indigo-500 to-blue-500"></div>

            <!-- Card body -->
            <div class="p-5 flex-1">
                <div class="flex items-start justify-between gap-3 mb-3">
                    <h3 class="text-sm font-semibold text-gray-900 leading-snug line-clamp-2 flex-1">
                        <?= htmlspecialchars($lp['title']) ?>
                    </h3>
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium <?= $sCls ?> flex-shrink-0">
                        <?= $sLabel ?>
                    </span>
                </div>

                <div class="space-y-1.5">
                    <?php if (!empty($lp['subject']) || !empty($lp['grade_level'])): ?>
                    <div class="flex items-center gap-1.5 text-xs text-slate-500">
                        <svg class="w-3.5 h-3.5 text-slate-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                        <span><?= htmlspecialchars($lp['subject'] ?: '—') ?></span>
                        <?php if (!empty($lp['grade_level'])): ?>
                            <span class="text-slate-300">·</span>
                            <span><?= htmlspecialchars($lp['grade_level']) ?></span>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>

                    <?php if (!empty($lp['quarter']) || !empty($lp['date'])): ?>
                    <div class="flex items-center gap-1.5 text-xs text-slate-500">
                        <svg class="w-3.5 h-3.5 text-slate-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        <?php if (!empty($lp['quarter'])): ?>
                            <span>Q<?= (int)$lp['quarter'] ?><?= !empty($lp['week']) ? ' · W' . (int)$lp['week'] : '' ?></span>
                            <?php if (!empty($lp['date'])): ?><span class="text-slate-300">·</span><?php endif; ?>
                        <?php endif; ?>
                        <?php if (!empty($lp['date'])): ?>
                            <span><?= htmlspecialchars($lp['date']) ?></span>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>

                    <div class="flex items-center gap-1.5 text-xs text-slate-400">
                        <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>Updated <?= htmlspecialchars(date('M j, Y', strtotime($lp['updated_at']))) ?></span>
                    </div>
                </div>
            </div>

            <!-- Card actions -->
            <div class="px-5 py-3 bg-slate-50 border-t border-slate-100 flex items-center gap-2">
                <a href="<?= url('/lesson-plans/' . (int)$lp['id'] . '/edit') ?>"
                   class="flex-1 inline-flex items-center justify-center gap-1.5 px-3 py-1.5 text-xs font-medium text-blue-700 bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    Edit
                </a>
                <a href="<?= url('/lesson-plans/' . (int)$lp['id'] . '/export') ?>"
                   class="inline-flex items-center justify-center w-8 h-8 text-slate-500 hover:text-slate-700 hover:bg-slate-200 rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-slate-400"
                   title="Export">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                </a>
                <form method="POST" action="<?= url('/lesson-plans/' . (int)$lp['id'] . '/duplicate') ?>">
                    <button type="submit"
                        class="inline-flex items-center justify-center w-8 h-8 text-purple-600 hover:text-purple-800 hover:bg-purple-100 rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-purple-400"
                        title="Duplicate">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                    </button>
                </form>
                <form method="POST" action="<?= url('/lesson-plans/' . (int)$lp['id'] . '/delete') ?>">
                    <button type="submit"
                        onclick="return confirm('Delete \'<?= htmlspecialchars(addslashes($lp['title']), ENT_QUOTES) ?>\'?')"
                        class="inline-flex items-center justify-center w-8 h-8 text-red-500 hover:text-red-700 hover:bg-red-100 rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-red-400"
                        title="Delete">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    </button>
                </form>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

<?php endif; ?>

<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/main.php';
