<?php
declare(strict_types=1);

$isEdit    = $template !== null;
$pageTitle = $isEdit ? 'Edit Template — BEED Student Portal' : 'New Template — BEED Student Portal';
$formAction = $isEdit ? url('/templates/' . (int)$template['id']) : url('/templates');

$val = static function (string $field) use ($old, $template): string {
    if (isset($old[$field])) return htmlspecialchars((string)$old[$field], ENT_QUOTES, 'UTF-8');
    if (isset($template[$field])) return htmlspecialchars((string)$template[$field], ENT_QUOTES, 'UTF-8');
    return '';
};

$err = static function (string $field) use ($errors): string {
    if (!empty($errors[$field])) {
        return '<p class="mt-1 text-sm text-red-600">' . htmlspecialchars((string)$errors[$field], ENT_QUOTES, 'UTF-8') . '</p>';
    }
    return '';
};

// Parse saved objectives JSON
$savedObjectives = [];
$objJson = $template['objectives_tpl'] ?? ($old['objectives_json'] ?? null);
if ($objJson) {
    $decoded = json_decode($objJson, true);
    if (is_array($decoded)) $savedObjectives = $decoded;
}

ob_start();
?>

<div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold text-gray-800"><?= $isEdit ? 'Edit Template' : 'New Template' ?></h1>
        <p class="mt-1 text-sm text-gray-500">Templates let you quickly fill a new lesson plan with reusable content.</p>
    </div>
    <a href="<?= url('/templates') ?>"
       class="inline-flex items-center gap-1.5 px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 text-sm font-medium rounded-lg shadow-sm transition-colors focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-offset-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        Cancel
    </a>
</div>

<?php if (!empty($errors['general'])): ?>
    <div class="mb-6 rounded-lg bg-red-50 border border-red-200 px-4 py-3"><p class="text-sm text-red-600"><?= htmlspecialchars((string)$errors['general'], ENT_QUOTES, 'UTF-8') ?></p></div>
<?php endif; ?>

<form method="POST" action="<?= htmlspecialchars($formAction, ENT_QUOTES, 'UTF-8') ?>" novalidate>

    <div class="space-y-8">

        <!-- Template Identity -->
        <section class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h2 class="text-base font-semibold text-gray-700 mb-5">Template Identity</h2>
            <div class="grid grid-cols-1 gap-5">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Template Name <span class="text-red-500">*</span></label>
                    <input type="text" id="name" name="name" value="<?= $val('name') ?>" required autocomplete="off"
                        class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-900 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                        placeholder="e.g. Grade 3 Math — Fractions Template">
                    <?= $err('name') ?>
                </div>
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description <span class="text-xs text-gray-400">(optional)</span></label>
                    <input type="text" id="description" name="description" value="<?= $val('description') ?>" autocomplete="off"
                        class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-900 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                        placeholder="e.g. Standard fractions lesson for Grade 3 Math, Q2">
                </div>
            </div>
        </section>

        <!-- Basic Info -->
        <section class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h2 class="text-base font-semibold text-gray-700 mb-5">Basic Information</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label for="subject_tpl" class="block text-sm font-medium text-gray-700 mb-1">Subject</label>
                    <select id="subject_tpl" name="subject_tpl" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-900 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white transition">
                        <option value="">— Select —</option>
                        <?php foreach (['Mathematics','English','Filipino','Science','Araling Panlipunan','MAPEH','Edukasyon sa Pagpapakatao (EsP)','Mother Tongue','Technology and Livelihood Education (TLE)'] as $s): ?>
                            <option value="<?= htmlspecialchars($s) ?>" <?= ($val('subject_tpl') === htmlspecialchars($s)) ? 'selected' : '' ?>><?= htmlspecialchars($s) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label for="grade_level_tpl" class="block text-sm font-medium text-gray-700 mb-1">Grade Level</label>
                    <select id="grade_level_tpl" name="grade_level_tpl" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-900 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white transition">
                        <option value="">— Select —</option>
                        <?php foreach (['Kindergarten','Grade 1','Grade 2','Grade 3','Grade 4','Grade 5','Grade 6'] as $g): ?>
                            <option value="<?= htmlspecialchars($g) ?>" <?= ($val('grade_level_tpl') === htmlspecialchars($g)) ? 'selected' : '' ?>><?= htmlspecialchars($g) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label for="time_allotment_tpl" class="block text-sm font-medium text-gray-700 mb-1">Time Allotment</label>
                    <select id="time_allotment_tpl" name="time_allotment_tpl" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-900 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white transition">
                        <option value="">— Select —</option>
                        <?php foreach ([20=>'20 min',30=>'30 min',40=>'40 min',45=>'45 min',50=>'50 min',60=>'60 min (1 hr)',90=>'90 min',120=>'120 min'] as $v=>$l): ?>
                            <option value="<?= $v ?>" <?= ($val('time_allotment_tpl') == $v) ? 'selected' : '' ?>><?= $l ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="md:col-span-2">
                    <label for="learning_competency_tpl" class="block text-sm font-medium text-gray-700 mb-1">Learning Competency</label>
                    <input type="text" id="learning_competency_tpl" name="learning_competency_tpl" value="<?= $val('learning_competency_tpl') ?>" autocomplete="off"
                        class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-900 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                        placeholder="e.g. Identifies fractions less than one (M3NS-Ib-68.1)">
                </div>
            </div>
        </section>

        <!-- Learning Objectives -->
        <section class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-3">
                <h2 class="text-base font-semibold text-gray-700">Learning Objectives</h2>
                <button type="button" id="tpl-add-obj-btn" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-medium rounded-md shadow-sm transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Add Objective
                </button>
            </div>
            <div id="tpl-objectives-container" class="space-y-3">
                <?php if (empty($savedObjectives)): ?>
                    <p id="tpl-obj-empty-hint" class="text-sm text-gray-400 italic">No objectives yet. Click "Add Objective" to begin.</p>
                <?php else: ?>
                    <?php foreach ($savedObjectives as $idx => $objText): ?>
                        <div class="tpl-obj-row flex items-center gap-3">
                            <span class="tpl-obj-badge flex-shrink-0 inline-flex items-center justify-center w-7 h-7 rounded-full bg-blue-100 text-blue-700 text-xs font-bold"><?= $idx + 1 ?></span>
                            <input type="hidden" class="tpl-obj-sort" name="objectives[<?= $idx ?>][sort_order]" value="<?= $idx + 1 ?>">
                            <input type="text" name="objectives[<?= $idx ?>][objective_text]" value="<?= htmlspecialchars($objText, ENT_QUOTES, 'UTF-8') ?>"
                                class="flex-1 rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-900 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                                aria-label="Objective <?= $idx + 1 ?>">
                            <button type="button" class="tpl-obj-remove flex-shrink-0 inline-flex items-center justify-center w-7 h-7 rounded-md text-red-500 hover:bg-red-50 hover:text-red-700 transition-colors focus:outline-none focus:ring-2 focus:ring-red-400" aria-label="Remove">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </section>

        <!-- Subject Matter -->
        <section class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h2 class="text-base font-semibold text-gray-700 mb-5">Subject Matter</h2>
            <div class="grid grid-cols-1 gap-5">
                <div>
                    <label for="subject_matter_topic_tpl" class="block text-sm font-medium text-gray-700 mb-1">Topic</label>
                    <input type="text" id="subject_matter_topic_tpl" name="subject_matter_topic_tpl" value="<?= $val('subject_matter_topic_tpl') ?>" autocomplete="off"
                        class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-900 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                        placeholder="e.g. Fractions less than one">
                </div>
                <div>
                    <label for="subject_matter_references_tpl" class="block text-sm font-medium text-gray-700 mb-1">References</label>
                    <textarea id="subject_matter_references_tpl" name="subject_matter_references_tpl" rows="3"
                        class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-900 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition resize-y"
                        placeholder="e.g. Math 3 LM pp. 68–72"><?= $val('subject_matter_references_tpl') ?></textarea>
                </div>
                <div>
                    <label for="subject_matter_materials_tpl" class="block text-sm font-medium text-gray-700 mb-1">Materials</label>
                    <textarea id="subject_matter_materials_tpl" name="subject_matter_materials_tpl" rows="3"
                        class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-900 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition resize-y"
                        placeholder="e.g. Fraction strips, number line chart"><?= $val('subject_matter_materials_tpl') ?></textarea>
                </div>
            </div>
        </section>

        <!-- Procedure -->
        <section class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h2 class="text-base font-semibold text-gray-700 mb-5">Procedure</h2>
            <div class="grid grid-cols-1 gap-5">
                <?php
                $procFields = [
                    'proc_review_drill_tpl'   => 'A. Review / Drill',
                    'proc_motivation_tpl'     => 'B. Motivation',
                    'proc_presentation_tpl'   => 'C. Presentation',
                    'proc_discussion_tpl'     => 'D. Discussion',
                    'proc_generalization_tpl' => 'E. Generalization',
                    'proc_application_tpl'    => 'F. Application',
                ];
                foreach ($procFields as $fieldName => $label):
                ?>
                <div>
                    <label for="<?= $fieldName ?>" class="block text-sm font-medium text-gray-700 mb-1"><?= $label ?></label>
                    <textarea id="<?= $fieldName ?>" name="<?= $fieldName ?>" rows="3"
                        class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-900 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition resize-y"
                        placeholder="Template content for <?= $label ?>..."><?= $val($fieldName) ?></textarea>
                </div>
                <?php endforeach; ?>
            </div>
        </section>

        <!-- Evaluation & Assignment -->
        <section class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h2 class="text-base font-semibold text-gray-700 mb-5">Evaluation &amp; Assignment</h2>
            <div class="grid grid-cols-1 gap-5">
                <div>
                    <label for="evaluation_tpl" class="block text-sm font-medium text-gray-700 mb-1">Evaluation</label>
                    <textarea id="evaluation_tpl" name="evaluation_tpl" rows="3"
                        class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-900 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition resize-y"
                        placeholder="e.g. 10-item written quiz, 1 pt each = 10 pts. Mastery: 8/10."><?= $val('evaluation_tpl') ?></textarea>
                </div>
                <div>
                    <label for="assignment_tpl" class="block text-sm font-medium text-gray-700 mb-1">Assignment / Agreement</label>
                    <textarea id="assignment_tpl" name="assignment_tpl" rows="3"
                        class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-900 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition resize-y"
                        placeholder="e.g. Study pp. 68–72. Draw 3 objects divided into equal parts."><?= $val('assignment_tpl') ?></textarea>
                </div>
            </div>
        </section>

        <!-- Actions -->
        <div class="flex flex-col sm:flex-row items-center justify-end gap-3">
            <a href="<?= url('/templates') ?>" class="w-full sm:w-auto inline-flex items-center justify-center gap-1.5 px-5 py-2.5 bg-gray-200 hover:bg-gray-300 text-gray-700 text-sm font-medium rounded-lg shadow-sm transition-colors focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-offset-2">Cancel</a>
            <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center gap-1.5 px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg shadow-sm transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <?= $isEdit ? 'Save Changes' : 'Save Template' ?>
            </button>
        </div>

    </div>
</form>

<script>
(function () {
    'use strict';
    var container = document.getElementById('tpl-objectives-container');
    var addBtn    = document.getElementById('tpl-add-obj-btn');
    var emptyHint = document.getElementById('tpl-obj-empty-hint');

    function count() { return container.querySelectorAll('.tpl-obj-row').length; }

    function reindex() {
        container.querySelectorAll('.tpl-obj-row').forEach(function (row, idx) {
            var num = idx + 1;
            var badge = row.querySelector('.tpl-obj-badge');
            var sort  = row.querySelector('.tpl-obj-sort');
            var input = row.querySelector('input[type="text"]');
            var btn   = row.querySelector('.tpl-obj-remove');
            if (badge) badge.textContent = num;
            if (sort)  { sort.name = 'objectives[' + idx + '][sort_order]'; sort.value = num; }
            if (input) { input.name = 'objectives[' + idx + '][objective_text]'; input.setAttribute('aria-label', 'Objective ' + num); }
            if (btn)   btn.setAttribute('aria-label', 'Remove objective ' + num);
        });
    }

    function addRow() {
        if (emptyHint) emptyHint.style.display = 'none';
        var idx = count(); var num = idx + 1;
        var row = document.createElement('div');
        row.className = 'tpl-obj-row flex items-center gap-3';
        row.innerHTML =
            '<span class="tpl-obj-badge flex-shrink-0 inline-flex items-center justify-center w-7 h-7 rounded-full bg-blue-100 text-blue-700 text-xs font-bold">' + num + '</span>'
            + '<input type="hidden" class="tpl-obj-sort" name="objectives[' + idx + '][sort_order]" value="' + num + '">'
            + '<input type="text" name="objectives[' + idx + '][objective_text]" class="flex-1 rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-900 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition" aria-label="Objective ' + num + '" placeholder="Objective ' + num + '">'
            + '<button type="button" class="tpl-obj-remove flex-shrink-0 inline-flex items-center justify-center w-7 h-7 rounded-md text-red-500 hover:bg-red-50 hover:text-red-700 transition-colors focus:outline-none focus:ring-2 focus:ring-red-400" aria-label="Remove"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>';
        container.appendChild(row);
        row.querySelector('input[type="text"]').focus();
    }

    addBtn.addEventListener('click', addRow);
    container.addEventListener('click', function (e) {
        var btn = e.target.closest('.tpl-obj-remove');
        if (btn) { btn.closest('.tpl-obj-row').remove(); reindex(); if (count() === 0 && emptyHint) emptyHint.style.display = ''; }
    });
    if (count() > 0 && emptyHint) emptyHint.style.display = 'none';
})();
</script>

<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/main.php';
