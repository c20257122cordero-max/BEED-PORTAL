<?php
declare(strict_types=1);
$isEdit     = $template !== null;
$pageTitle  = $isEdit ? 'Edit Demo Template' : 'New Demo Template';
$formAction = $isEdit ? url('/demo-templates/' . (int)$template['id']) : url('/demo-templates');

$val = static function (string $f) use ($old, $template): string {
    if (isset($old[$f]))      return htmlspecialchars((string)$old[$f], ENT_QUOTES, 'UTF-8');
    if (isset($template[$f])) return htmlspecialchars((string)$template[$f], ENT_QUOTES, 'UTF-8');
    return '';
};

$savedSteps = [];
$stepsJson  = $template['steps_tpl'] ?? null;
if ($stepsJson) { $dec = json_decode($stepsJson, true); if (is_array($dec)) $savedSteps = $dec; }

ob_start();
?>

<div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold text-gray-900"><?= $isEdit ? 'Edit Demo Template' : 'New Demo Template' ?></h1>
        <p class="mt-1 text-sm text-slate-500">Templates let you quickly fill a new demo with reusable content.</p>
    </div>
    <a href="<?= url('/demo-templates') ?>" class="inline-flex items-center gap-1.5 px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 text-sm font-medium rounded-xl shadow-sm transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        Cancel
    </a>
</div>

<form method="POST" action="<?= htmlspecialchars($formAction, ENT_QUOTES, 'UTF-8') ?>" novalidate>
<div class="space-y-6">

    <!-- Identity -->
    <section class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
        <h2 class="text-sm font-semibold text-gray-700 mb-4">Template Identity</h2>
        <div class="grid grid-cols-1 gap-4">
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1.5">Template Name <span class="text-red-500">*</span></label>
                <input type="text" id="name" name="name" value="<?= $val('name') ?>" required autocomplete="off"
                    class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm text-gray-900 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    placeholder="e.g. Grade 3 Math — Fractions Demo">
                <?php if (!empty($errors['name'])): ?><p class="mt-1 text-xs text-red-600"><?= htmlspecialchars($errors['name']) ?></p><?php endif; ?>
            </div>
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 mb-1.5">Description <span class="text-xs text-slate-400">(optional)</span></label>
                <input type="text" id="description" name="description" value="<?= $val('description') ?>" autocomplete="off"
                    class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm text-gray-900 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    placeholder="e.g. Standard fractions demo for Grade 3">
            </div>
        </div>
    </section>

    <!-- Basic Info -->
    <section class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
        <h2 class="text-sm font-semibold text-gray-700 mb-4">Basic Information</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label for="subject_tpl" class="block text-sm font-medium text-gray-700 mb-1.5">Subject</label>
                <select id="subject_tpl" name="subject_tpl" class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm text-gray-900 bg-white shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">— Select —</option>
                    <?php foreach (['Mathematics','English','Filipino','Science','Araling Panlipunan','MAPEH','Edukasyon sa Pagpapakatao (EsP)','Mother Tongue','Technology and Livelihood Education (TLE)'] as $s): ?>
                        <option value="<?= htmlspecialchars($s) ?>" <?= $val('subject_tpl') === htmlspecialchars($s) ? 'selected' : '' ?>><?= htmlspecialchars($s) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label for="grade_level_tpl" class="block text-sm font-medium text-gray-700 mb-1.5">Grade Level</label>
                <select id="grade_level_tpl" name="grade_level_tpl" class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm text-gray-900 bg-white shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">— Select —</option>
                    <?php foreach (['Kindergarten','Grade 1','Grade 2','Grade 3','Grade 4','Grade 5','Grade 6'] as $g): ?>
                        <option value="<?= htmlspecialchars($g) ?>" <?= $val('grade_level_tpl') === htmlspecialchars($g) ? 'selected' : '' ?>><?= htmlspecialchars($g) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label for="duration_minutes_tpl" class="block text-sm font-medium text-gray-700 mb-1.5">Duration</label>
                <select id="duration_minutes_tpl" name="duration_minutes_tpl" class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm text-gray-900 bg-white shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">— Select —</option>
                    <?php foreach ([20=>'20 min',30=>'30 min',40=>'40 min',45=>'45 min',50=>'50 min',60=>'60 min (1 hr)',90=>'90 min',120=>'120 min'] as $v=>$l): ?>
                        <option value="<?= $v ?>" <?= $val('duration_minutes_tpl') == $v ? 'selected' : '' ?>><?= $l ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
    </section>

    <!-- Objectives & Materials -->
    <section class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
        <h2 class="text-sm font-semibold text-gray-700 mb-4">Objectives &amp; Materials</h2>
        <div class="space-y-4">
            <div>
                <label for="learning_objectives_tpl" class="block text-sm font-medium text-gray-700 mb-1.5">Learning Objectives</label>
                <textarea id="learning_objectives_tpl" name="learning_objectives_tpl" rows="3"
                    class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm text-gray-900 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 resize-y"
                    placeholder="e.g. 1. Identify fractions as parts of a whole."><?= $val('learning_objectives_tpl') ?></textarea>
            </div>
            <div>
                <label for="materials_needed_tpl" class="block text-sm font-medium text-gray-700 mb-1.5">Materials Needed</label>
                <textarea id="materials_needed_tpl" name="materials_needed_tpl" rows="3"
                    class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm text-gray-900 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 resize-y"
                    placeholder="e.g. Fraction strips, number line chart"><?= $val('materials_needed_tpl') ?></textarea>
            </div>
        </div>
    </section>

    <!-- Lesson Flow -->
    <section class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
        <h2 class="text-sm font-semibold text-gray-700 mb-4">Lesson Flow</h2>
        <div class="space-y-4">
            <?php foreach (['introduction_tpl'=>'Introduction / Motivation','generalization_tpl'=>'Generalization','application_tpl'=>'Application / Practice','assessment_tpl'=>'Assessment / Evaluation'] as $fn=>$fl): ?>
            <div>
                <label for="<?= $fn ?>" class="block text-sm font-medium text-gray-700 mb-1.5"><?= $fl ?></label>
                <textarea id="<?= $fn ?>" name="<?= $fn ?>" rows="3"
                    class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm text-gray-900 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 resize-y"
                    placeholder="Template content for <?= $fl ?>..."><?= $val($fn) ?></textarea>
            </div>
            <?php endforeach; ?>

            <!-- Steps -->
            <div>
                <div class="flex items-center justify-between mb-2">
                    <label class="block text-sm font-medium text-gray-700">Lesson Proper Steps</label>
                    <button type="button" id="tpl-add-step-btn" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-700 hover:bg-blue-800 text-white text-xs font-medium rounded-lg shadow-sm">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        Add Step
                    </button>
                </div>
                <div id="tpl-steps-container" class="space-y-3">
                    <?php if (empty($savedSteps)): ?>
                        <p id="tpl-steps-hint" class="text-sm text-slate-400 italic">No steps yet. Click "Add Step" to begin.</p>
                    <?php else: ?>
                        <?php foreach ($savedSteps as $idx => $stepText): ?>
                        <div class="tpl-step-row flex items-start gap-3 p-3 bg-slate-50 rounded-xl border border-slate-200">
                            <span class="tpl-step-badge flex-shrink-0 inline-flex items-center justify-center w-7 h-7 rounded-full bg-blue-100 text-blue-700 text-xs font-bold mt-1"><?= $idx+1 ?></span>
                            <input type="hidden" class="tpl-step-num" name="steps[<?= $idx ?>][step_number]" value="<?= $idx+1 ?>">
                            <textarea name="steps[<?= $idx ?>][description]" rows="2"
                                class="flex-1 rounded-xl border border-slate-300 px-3 py-2 text-sm text-gray-900 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 resize-y"
                                aria-label="Step <?= $idx+1 ?>"><?= htmlspecialchars($stepText, ENT_QUOTES, 'UTF-8') ?></textarea>
                            <button type="button" class="tpl-step-remove flex-shrink-0 inline-flex items-center justify-center w-7 h-7 rounded-lg text-red-500 hover:bg-red-100 hover:text-red-700 transition-colors mt-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>

    <!-- Actions -->
    <div class="flex flex-col sm:flex-row items-center justify-end gap-3">
        <a href="<?= url('/demo-templates') ?>" class="w-full sm:w-auto inline-flex items-center justify-center px-5 py-2.5 bg-slate-200 hover:bg-slate-300 text-slate-700 text-sm font-medium rounded-xl shadow-sm transition-colors">Cancel</a>
        <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center gap-1.5 px-5 py-2.5 bg-blue-700 hover:bg-blue-800 text-white text-sm font-medium rounded-xl shadow-sm transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            <?= $isEdit ? 'Save Changes' : 'Save Template' ?>
        </button>
    </div>

</div>
</form>

<script>
(function(){
    var container = document.getElementById('tpl-steps-container');
    var addBtn    = document.getElementById('tpl-add-step-btn');
    var hint      = document.getElementById('tpl-steps-hint');
    function count(){ return container.querySelectorAll('.tpl-step-row').length; }
    function reindex(){
        container.querySelectorAll('.tpl-step-row').forEach(function(row,idx){
            var num=idx+1;
            var badge=row.querySelector('.tpl-step-badge');
            var numIn=row.querySelector('.tpl-step-num');
            var ta=row.querySelector('textarea');
            if(badge) badge.textContent=num;
            if(numIn){numIn.name='steps['+idx+'][step_number]';numIn.value=num;}
            if(ta) ta.name='steps['+idx+'][description]';
        });
    }
    addBtn.addEventListener('click',function(){
        if(hint) hint.style.display='none';
        var idx=count(); var num=idx+1;
        var row=document.createElement('div');
        row.className='tpl-step-row flex items-start gap-3 p-3 bg-slate-50 rounded-xl border border-slate-200';
        row.innerHTML='<span class="tpl-step-badge flex-shrink-0 inline-flex items-center justify-center w-7 h-7 rounded-full bg-blue-100 text-blue-700 text-xs font-bold mt-1">'+num+'</span>'
            +'<input type="hidden" class="tpl-step-num" name="steps['+idx+'][step_number]" value="'+num+'">'
            +'<textarea name="steps['+idx+'][description]" rows="2" class="flex-1 rounded-xl border border-slate-300 px-3 py-2 text-sm text-gray-900 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 resize-y" aria-label="Step '+num+'"></textarea>'
            +'<button type="button" class="tpl-step-remove flex-shrink-0 inline-flex items-center justify-center w-7 h-7 rounded-lg text-red-500 hover:bg-red-100 hover:text-red-700 transition-colors mt-1"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>';
        container.appendChild(row);
        row.querySelector('textarea').focus();
    });
    container.addEventListener('click',function(e){
        var btn=e.target.closest('.tpl-step-remove');
        if(btn){btn.closest('.tpl-step-row').remove();reindex();if(count()===0&&hint)hint.style.display='';}
    });
    if(count()>0&&hint) hint.style.display='none';
})();
</script>

<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/main.php';
