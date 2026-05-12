<?php
declare(strict_types=1);
$pageTitle = 'My Demo Templates – BEED Student Portal';
ob_start();
?>

<div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">My Demo Templates</h1>
        <p class="mt-1 text-sm text-slate-500">Save reusable templates to speed up creating new demos.</p>
    </div>
    <a href="<?= url('/demo-templates/create') ?>"
       class="inline-flex items-center gap-2 px-4 py-2.5 bg-blue-700 hover:bg-blue-800 text-white text-sm font-medium rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        New Template
    </a>
</div>

<!-- Built-in Subject Templates -->
<div class="mb-8">
    <div class="flex items-center gap-3 mb-4">
        <div class="w-6 h-6 bg-violet-100 rounded-md flex items-center justify-center">
            <svg class="w-3.5 h-3.5 text-violet-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
        </div>
        <h2 class="text-sm font-semibold text-gray-700">Built-in Subject Templates</h2>
        <span class="text-xs text-slate-400 hidden sm:inline">— Click to create a new demo pre-filled with subject-specific content</span>
    </div>
    <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-4 gap-3">
        <?php
        $builtinDemoTemplates = [
            ['key'=>'math',    'color'=>'blue',   'label'=>'M',  'grade'=>'Grade 3', 'title'=>'Mathematics',         'desc'=>'Fractions — 4A\'s Method'],
            ['key'=>'english', 'color'=>'green',  'label'=>'E',  'grade'=>'Grade 4', 'title'=>'English',             'desc'=>'Reading Comp. — 5E\'s'],
            ['key'=>'filipino','color'=>'yellow', 'label'=>'F',  'grade'=>'Grade 3', 'title'=>'Filipino',            'desc'=>'Pagbabasa — 4A\'s'],
            ['key'=>'science', 'color'=>'teal',   'label'=>'S',  'grade'=>'Grade 3', 'title'=>'Science',             'desc'=>'Living Things — 5E\'s'],
            ['key'=>'ap',      'color'=>'orange', 'label'=>'AP', 'grade'=>'Grade 3', 'title'=>'Araling Panlipunan',  'desc'=>'Komunidad — 4A\'s'],
            ['key'=>'mapeh',   'color'=>'pink',   'label'=>'MP', 'grade'=>'Grade 4', 'title'=>'MAPEH',               'desc'=>'Music — 5E\'s'],
            ['key'=>'esp',     'color'=>'indigo', 'label'=>'EP', 'grade'=>'Grade 5', 'title'=>'EsP',                 'desc'=>'Pagpapahalaga — 4A\'s'],
            ['key'=>'generic', 'color'=>'gray',   'label'=>'G',  'grade'=>'Any',     'title'=>'Generic DepEd',       'desc'=>'Standard Demo Structure'],
        ];
        foreach ($builtinDemoTemplates as $bt):
        ?>
        <a href="<?= url('/demos/create?builtin_demo=' . $bt['key']) ?>"
           class="group bg-white border border-slate-200 rounded-xl overflow-hidden hover:shadow-md hover:border-slate-300 transition-all flex flex-col">
            <div class="h-1 bg-<?= $bt['color'] ?>-500"></div>
            <div class="p-3 flex items-start gap-2.5 flex-1">
                <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-<?= $bt['color'] ?>-100 text-<?= $bt['color'] ?>-700 text-xs font-bold flex-shrink-0 group-hover:bg-<?= $bt['color'] ?>-600 group-hover:text-white transition-colors">
                    <?= $bt['label'] ?>
                </span>
                <div class="min-w-0">
                    <p class="text-xs font-semibold text-gray-800 leading-snug"><?= htmlspecialchars($bt['title']) ?></p>
                    <p class="text-xs text-slate-400 mt-0.5"><?= htmlspecialchars($bt['desc']) ?></p>
                    <p class="text-xs text-slate-400"><?= htmlspecialchars($bt['grade']) ?></p>
                </div>
            </div>
            <div class="px-3 py-1.5 bg-slate-50 border-t border-slate-100">
                <span class="text-xs font-medium text-<?= $bt['color'] ?>-600">Use →</span>
            </div>
        </a>
        <?php endforeach; ?>
    </div>
</div>

<div class="border-t border-slate-200 mb-6 pt-2">
    <h2 class="text-sm font-semibold text-gray-700">My Saved Templates</h2>
</div>

<?php if ($success): ?>
    <div class="mb-5 flex items-center gap-3 rounded-xl bg-green-50 border border-green-200 px-4 py-3">
        <svg class="w-5 h-5 text-green-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        <p class="text-sm text-green-700 font-medium">Template saved successfully.</p>
    </div>
<?php endif; ?>

<?php if (empty($templates)): ?>
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm px-6 py-16 text-center">
        <div class="w-16 h-16 bg-blue-50 rounded-2xl flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
        </div>
        <p class="text-gray-600 font-medium mb-1">No demo templates yet.</p>
        <p class="text-sm text-slate-400 mb-4">Create a template or save an existing demo as a template.</p>
        <a href="<?= url('/demo-templates/create') ?>" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-700 hover:bg-blue-800 text-white text-sm font-medium rounded-xl transition-colors">
            Create your first template →
        </a>
    </div>
<?php else: ?>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
        <?php foreach ($templates as $tpl): ?>
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm hover:shadow-md hover:border-slate-300 transition-all flex flex-col overflow-hidden">
            <div class="h-1 bg-gradient-to-r from-blue-500 to-indigo-500"></div>
            <div class="px-5 py-4 flex-1">
                <h3 class="text-sm font-semibold text-gray-800 truncate mb-1"><?= htmlspecialchars($tpl['name']) ?></h3>
                <?php if (!empty($tpl['description'])): ?>
                    <p class="text-xs text-slate-500 mb-2 line-clamp-2"><?= htmlspecialchars($tpl['description']) ?></p>
                <?php endif; ?>
                <div class="space-y-1">
                    <?php if (!empty($tpl['subject_tpl'])): ?>
                        <p class="text-xs text-slate-500"><span class="font-medium">Subject:</span> <?= htmlspecialchars($tpl['subject_tpl']) ?></p>
                    <?php endif; ?>
                    <?php if (!empty($tpl['grade_level_tpl'])): ?>
                        <p class="text-xs text-slate-500"><span class="font-medium">Grade:</span> <?= htmlspecialchars($tpl['grade_level_tpl']) ?></p>
                    <?php endif; ?>
                    <p class="text-xs text-slate-400">Saved: <?= date('M j, Y', strtotime($tpl['created_at'])) ?></p>
                </div>
            </div>
            <div class="px-5 py-3 bg-slate-50 border-t border-slate-100 flex flex-wrap gap-2">
                <a href="<?= url('/demos/create?demo_template=' . (int)$tpl['id']) ?>"
                   class="flex-1 inline-flex items-center justify-center gap-1 px-3 py-1.5 text-xs font-medium text-white bg-blue-700 hover:bg-blue-800 rounded-lg transition-colors">
                    Use Template
                </a>
                <a href="<?= url('/demo-templates/' . (int)$tpl['id'] . '/edit') ?>"
                   class="inline-flex items-center justify-center w-8 h-8 text-slate-500 hover:text-slate-700 hover:bg-slate-200 rounded-lg transition-colors" title="Edit">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                </a>
                <form method="POST" action="<?= url('/demo-templates/' . (int)$tpl['id'] . '/delete') ?>">
                    <button type="submit" onclick="return confirm('Delete template \'<?= htmlspecialchars(addslashes($tpl['name'])) ?>\'?')"
                        class="inline-flex items-center justify-center w-8 h-8 text-red-500 hover:text-red-700 hover:bg-red-100 rounded-lg transition-colors" title="Delete">
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
