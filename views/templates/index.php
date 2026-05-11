<?php
declare(strict_types=1);

$pageTitle = 'My Templates – BEED Student Portal';
ob_start();
?>

<div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">My Lesson Plan Templates</h1>
        <p class="mt-1 text-sm text-gray-500">Save reusable templates to speed up creating new lesson plans.</p>
    </div>
    <a href="<?= url('/templates/create') ?>"
       class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg shadow-sm transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        New Template
    </a>
</div>

<?php if ($success): ?>
    <div class="mb-5 rounded-lg bg-green-50 border border-green-200 px-4 py-3 flex items-center gap-2">
        <svg class="w-5 h-5 text-green-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        <p class="text-sm text-green-700 font-medium">Template saved successfully.</p>
    </div>
<?php endif; ?>

<?php if (empty($templates)): ?>
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 px-6 py-16 text-center">
        <svg class="mx-auto w-12 h-12 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
        </svg>
        <p class="text-gray-500 text-sm mb-1">No templates yet.</p>
        <p class="text-gray-400 text-xs mb-4">Create a template or save an existing lesson plan as a template.</p>
        <a href="<?= url('/templates/create') ?>" class="inline-flex items-center gap-1.5 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
            Create your first template →
        </a>
    </div>
<?php else: ?>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
        <?php foreach ($templates as $tpl): ?>
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 flex flex-col overflow-hidden hover:shadow-md transition-shadow">
                <!-- Card header -->
                <div class="px-5 py-4 border-b border-gray-100 bg-gradient-to-r from-blue-50 to-indigo-50">
                    <h3 class="text-sm font-semibold text-gray-800 truncate"><?= htmlspecialchars($tpl['name']) ?></h3>
                    <?php if (!empty($tpl['description'])): ?>
                        <p class="text-xs text-gray-500 mt-0.5 line-clamp-2"><?= htmlspecialchars($tpl['description']) ?></p>
                    <?php endif; ?>
                </div>
                <!-- Card meta -->
                <div class="px-5 py-3 flex-1 space-y-1">
                    <?php if (!empty($tpl['subject_tpl'])): ?>
                        <p class="text-xs text-gray-600"><span class="font-medium">Subject:</span> <?= htmlspecialchars($tpl['subject_tpl']) ?></p>
                    <?php endif; ?>
                    <?php if (!empty($tpl['grade_level_tpl'])): ?>
                        <p class="text-xs text-gray-600"><span class="font-medium">Grade:</span> <?= htmlspecialchars($tpl['grade_level_tpl']) ?></p>
                    <?php endif; ?>
                    <?php if (!empty($tpl['learning_competency_tpl'])): ?>
                        <p class="text-xs text-gray-500 truncate"><span class="font-medium">Competency:</span> <?= htmlspecialchars($tpl['learning_competency_tpl']) ?></p>
                    <?php endif; ?>
                    <p class="text-xs text-gray-400 pt-1">Saved: <?= htmlspecialchars(date('M j, Y', strtotime($tpl['created_at']))) ?></p>
                </div>
                <!-- Card actions -->
                <div class="px-5 py-3 border-t border-gray-100 bg-gray-50 flex flex-wrap gap-2">
                    <a href="<?= url('/lesson-plans/create?template=' . (int)$tpl['id']) ?>"
                       class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-md transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        Use Template
                    </a>
                    <a href="<?= url('/templates/' . (int)$tpl['id'] . '/edit') ?>"
                       class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-blue-700 bg-blue-50 hover:bg-blue-100 rounded-md transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500">
                        Edit
                    </a>
                    <form method="POST" action="<?= url('/templates/' . (int)$tpl['id'] . '/delete') ?>">
                        <button type="submit"
                            onclick="return confirm('Delete template \'<?= htmlspecialchars(addslashes($tpl['name'])) ?>\'?')"
                            class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-white bg-red-600 hover:bg-red-700 rounded-md transition-colors focus:outline-none focus:ring-2 focus:ring-red-500">
                            Delete
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
