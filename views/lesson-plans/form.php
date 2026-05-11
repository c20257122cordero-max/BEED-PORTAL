<?php

declare(strict_types=1);

/**
 * Lesson Plan Planner — create / edit form view
 *
 * Variables provided by LessonPlanController::create(), store(), edit(), update():
 *   $lessonPlan (array|null) – null for create, populated row for edit
 *   $objectives (array)      – objective rows, each with 'sort_order' and 'objective_text'
 *   $errors     (array)      – validation errors keyed by field name
 *   $old        (array)      – previous POST values for repopulation after failure
 *
 * Field repopulation priority:
 *   1. $old['field']        – validation failure (most recent user input)
 *   2. $lessonPlan['field'] – edit mode (stored value)
 *   3. ''                   – create mode / no prior input
 *
 * Requirements: 5.1, 5.3, 5.4, 5.7
 */

$isEdit    = $lessonPlan !== null;
$pageTitle = $isEdit
    ? 'Edit Lesson Plan — BEED Student Portal'
    : 'New Lesson Plan — BEED Student Portal';

$formAction = $isEdit
    ? url('/lesson-plans/' . (int) $lessonPlan['id'])
    : url('/lesson-plans');

/**
 * Helper: return the repopulated value for a scalar field.
 *
 * @param string $field  Field name.
 * @return string        HTML-escaped value.
 */
$val = static function (string $field) use ($old, $lessonPlan): string {
    if (isset($old[$field])) {
        return htmlspecialchars((string) $old[$field], ENT_QUOTES, 'UTF-8');
    }
    if (isset($lessonPlan[$field])) {
        return htmlspecialchars((string) $lessonPlan[$field], ENT_QUOTES, 'UTF-8');
    }
    return '';
};

/**
 * Helper: return an inline error <p> if the field has an error, else ''.
 *
 * @param string $field  Field name.
 * @return string        HTML string.
 */
$err = static function (string $field) use ($errors): string {
    if (!empty($errors[$field])) {
        return '<p class="mt-1 text-sm text-red-600">'
            . htmlspecialchars((string) $errors[$field], ENT_QUOTES, 'UTF-8')
            . '</p>';
    }
    return '';
};

/**
 * Helper: return border colour class based on whether the field has an error.
 *
 * @param string $field  Field name.
 * @return string        Tailwind border class.
 */
$borderClass = static function (string $field) use ($errors): string {
    return !empty($errors[$field])
        ? 'border-red-400 focus:ring-red-500 focus:border-red-500'
        : 'border-gray-300 focus:ring-blue-500 focus:border-blue-500';
};

// Determine the initial objectives list to render.
// If $old contains objectives (validation failure), use those; otherwise use $objectives.
$initialObjectives = [];
if (!empty($old['objectives']) && is_array($old['objectives'])) {
    foreach ($old['objectives'] as $o) {
        $initialObjectives[] = [
            'sort_order'     => (int) ($o['sort_order'] ?? 0),
            'objective_text' => (string) ($o['objective_text'] ?? ''),
        ];
    }
} elseif (!empty($objectives)) {
    $initialObjectives = $objectives;
}

ob_start();
?>

<!-- Page header -->
<div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">
            <?= $isEdit ? 'Edit Lesson Plan' : 'New Lesson Plan' ?>
        </h1>
        <p class="mt-1 text-sm text-gray-500">
            <?= $isEdit
                ? 'Update your lesson plan.'
                : 'Create a new lesson plan.' ?>
        </p>
    </div>

    <!-- Action links (My Templates / Export / Cancel) -->
    <div class="flex items-center gap-3 flex-shrink-0 flex-wrap justify-end">
        <button type="button" id="save-as-template-btn"
            class="inline-flex items-center gap-1.5 px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium rounded-lg shadow-sm transition-colors focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/></svg>
            Save as Template
        </button>
        <a href="<?= url('/templates') ?>"
           class="inline-flex items-center gap-1.5 px-4 py-2 bg-purple-100 hover:bg-purple-200 text-purple-700 text-sm font-medium rounded-lg shadow-sm transition-colors focus:outline-none focus:ring-2 focus:ring-purple-400 focus:ring-offset-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            My Templates
        </a>
        <?php if ($isEdit): ?>
            <a href="<?= url('/lesson-plans/' . (int) $lessonPlan['id'] . '/export') ?>"
               class="inline-flex items-center gap-1.5 px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 text-sm font-medium rounded-lg shadow-sm transition-colors focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-offset-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
                Export
            </a>
        <?php endif; ?>

        <a href="<?= url('/lesson-plans') ?>"
           class="inline-flex items-center gap-1.5 px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 text-sm font-medium rounded-lg shadow-sm transition-colors focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-offset-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Cancel
        </a>
    </div>
</div>

<!-- General error banner -->
<?php if (!empty($errors['general'])): ?>
    <div class="mb-6 rounded-lg bg-red-50 border border-red-200 px-4 py-3" role="alert">
        <p class="text-sm text-red-600">
            <?= htmlspecialchars((string) $errors['general'], ENT_QUOTES, 'UTF-8') ?>
        </p>
    </div>
<?php endif; ?>

<!-- Lesson Plan form -->
<form method="POST" action="<?= htmlspecialchars($formAction, ENT_QUOTES, 'UTF-8') ?>" novalidate>

    <div class="space-y-8">

        <!-- Section 1: Basic Information -->
        <section class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h2 class="text-base font-semibold text-gray-700 mb-5">Basic Information</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <!-- Lesson Title (required) -->
                <div class="md:col-span-2">
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-1">
                        Lesson Title
                        <span class="text-red-500 ml-0.5" aria-hidden="true">*</span>
                    </label>
                    <input
                        type="text"
                        id="title"
                        name="title"
                        value="<?= $val('title') ?>"
                        required
                        autocomplete="off"
                        class="w-full rounded-lg border px-3 py-2 text-sm text-gray-900 placeholder-gray-400 shadow-sm
                               focus:outline-none focus:ring-2 transition
                               <?= $borderClass('title') ?>"
                        placeholder="e.g. Introduction to Fractions"
                        aria-describedby="title-error"
                    >
                    <span id="title-error"><?= $err('title') ?></span>
                </div>

                <!-- Subject -->
                <div>
                    <label for="subject" class="block text-sm font-medium text-gray-700 mb-1">
                        Subject
                    </label>
                    <select
                        id="subject"
                        name="subject"
                        class="w-full rounded-lg border px-3 py-2 text-sm text-gray-900 shadow-sm
                               focus:outline-none focus:ring-2 transition bg-white
                               <?= $borderClass('subject') ?>"
                        aria-describedby="subject-error"
                    >
                        <option value="">— Select a subject —</option>
                        <?php
                        $subjects = ['Mathematics','English','Filipino','Science','Araling Panlipunan','MAPEH','Edukasyon sa Pagpapakatao (EsP)','Mother Tongue','Technology and Livelihood Education (TLE)'];
                        $currentSubject = $old['subject'] ?? ($lessonPlan['subject'] ?? '');
                        foreach ($subjects as $s):
                        ?>
                            <option value="<?= htmlspecialchars($s) ?>" <?= $currentSubject === $s ? 'selected' : '' ?>>
                                <?= htmlspecialchars($s) ?>
                            </option>
                        <?php endforeach; ?>
                        <option value="Other" <?= (!in_array($currentSubject, $subjects) && $currentSubject !== '') ? 'selected' : '' ?>>Other</option>
                    </select>
                    <span id="subject-error"><?= $err('subject') ?></span>
                </div>

                <!-- Grade Level -->
                <div>
                    <label for="grade_level" class="block text-sm font-medium text-gray-700 mb-1">
                        Grade Level
                    </label>
                    <select
                        id="grade_level"
                        name="grade_level"
                        class="w-full rounded-lg border px-3 py-2 text-sm text-gray-900 shadow-sm
                               focus:outline-none focus:ring-2 transition bg-white
                               <?= $borderClass('grade_level') ?>"
                        aria-describedby="grade_level-error"
                    >
                        <option value="">— Select grade level —</option>
                        <?php
                        $grades = ['Kindergarten','Grade 1','Grade 2','Grade 3','Grade 4','Grade 5','Grade 6'];
                        $currentGrade = $old['grade_level'] ?? ($lessonPlan['grade_level'] ?? '');
                        foreach ($grades as $g):
                        ?>
                            <option value="<?= htmlspecialchars($g) ?>" <?= $currentGrade === $g ? 'selected' : '' ?>>
                                <?= htmlspecialchars($g) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <span id="grade_level-error"><?= $err('grade_level') ?></span>
                </div>

                <!-- Quarter -->
                <div>
                    <label for="quarter" class="block text-sm font-medium text-gray-700 mb-1">
                        Quarter
                    </label>
                    <select
                        id="quarter"
                        name="quarter"
                        class="w-full rounded-lg border px-3 py-2 text-sm text-gray-900 shadow-sm
                               focus:outline-none focus:ring-2 transition bg-white
                               border-gray-300 focus:ring-blue-500 focus:border-blue-500"
                    >
                        <option value="">— Select quarter —</option>
                        <?php
                        $quarters = [1 => '1st Quarter', 2 => '2nd Quarter', 3 => '3rd Quarter', 4 => '4th Quarter'];
                        $currentQuarter = (string)($old['quarter'] ?? ($lessonPlan['quarter'] ?? ''));
                        foreach ($quarters as $qval => $qlabel):
                        ?>
                            <option value="<?= $qval ?>" <?= $currentQuarter === (string)$qval ? 'selected' : '' ?>>
                                <?= htmlspecialchars($qlabel) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Week -->
                <div>
                    <label for="week" class="block text-sm font-medium text-gray-700 mb-1">
                        Week
                    </label>
                    <select
                        id="week"
                        name="week"
                        class="w-full rounded-lg border px-3 py-2 text-sm text-gray-900 shadow-sm
                               focus:outline-none focus:ring-2 transition bg-white
                               border-gray-300 focus:ring-blue-500 focus:border-blue-500"
                    >
                        <option value="">— Select week —</option>
                        <?php
                        $currentWeek = (string)($old['week'] ?? ($lessonPlan['week'] ?? ''));
                        for ($w = 1; $w <= 10; $w++):
                        ?>
                            <option value="<?= $w ?>" <?= $currentWeek === (string)$w ? 'selected' : '' ?>>
                                Week <?= $w ?>
                            </option>
                        <?php endfor; ?>
                    </select>
                </div>

                <!-- Status -->
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-1">
                        Status
                    </label>
                    <select
                        id="status"
                        name="status"
                        class="w-full rounded-lg border px-3 py-2 text-sm text-gray-900 shadow-sm
                               focus:outline-none focus:ring-2 transition bg-white
                               border-gray-300 focus:ring-blue-500 focus:border-blue-500"
                    >
                        <?php
                        $statuses = ['draft' => 'Draft', 'for_review' => 'For Review', 'submitted' => 'Submitted'];
                        $currentStatus = $old['status'] ?? ($lessonPlan['status'] ?? 'draft');
                        foreach ($statuses as $sval => $slabel):
                        ?>
                            <option value="<?= $sval ?>" <?= $currentStatus === $sval ? 'selected' : '' ?>>
                                <?= htmlspecialchars($slabel) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Date -->
                <div>
                    <label for="date" class="block text-sm font-medium text-gray-700 mb-1">
                        Date
                    </label>
                    <input
                        type="date"
                        id="date"
                        name="date"
                        value="<?= $val('date') ?>"
                        class="w-full rounded-lg border px-3 py-2 text-sm text-gray-900 shadow-sm
                               focus:outline-none focus:ring-2 transition
                               <?= $borderClass('date') ?>"
                        aria-describedby="date-error"
                    >
                    <span id="date-error"><?= $err('date') ?></span>
                </div>

                <!-- Time Allotment -->
                <div>
                    <label for="time_allotment_minutes" class="block text-sm font-medium text-gray-700 mb-1">
                        Time Allotment (minutes)
                    </label>
                    <select
                        id="time_allotment_minutes"
                        name="time_allotment_minutes"
                        class="w-full rounded-lg border px-3 py-2 text-sm text-gray-900 shadow-sm
                               focus:outline-none focus:ring-2 transition bg-white
                               <?= $borderClass('time_allotment_minutes') ?>"
                        aria-describedby="time_allotment_minutes-error"
                    >
                        <option value="">— Select duration —</option>
                        <?php
                        $durations = [20 => '20 minutes', 30 => '30 minutes', 40 => '40 minutes', 45 => '45 minutes', 50 => '50 minutes', 60 => '60 minutes (1 hour)', 90 => '90 minutes (1.5 hours)', 120 => '120 minutes (2 hours)'];
                        $currentDuration = (string)($old['time_allotment_minutes'] ?? ($lessonPlan['time_allotment_minutes'] ?? ''));
                        foreach ($durations as $dval => $dlabel):
                        ?>
                            <option value="<?= $dval ?>" <?= $currentDuration === (string)$dval ? 'selected' : '' ?>>
                                <?= htmlspecialchars($dlabel) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <span id="time_allotment_minutes-error"><?= $err('time_allotment_minutes') ?></span>
                </div>

                <!-- Learning Competency (required) -->
                <div class="md:col-span-2">
                    <label for="learning_competency" class="block text-sm font-medium text-gray-700 mb-1">
                        Learning Competency
                        <span class="text-red-500 ml-0.5" aria-hidden="true">*</span>
                        <span class="text-xs text-gray-400 font-normal ml-1">(include reference code)</span>
                    </label>
                    <input
                        type="text"
                        id="learning_competency"
                        name="learning_competency"
                        value="<?= $val('learning_competency') ?>"
                        required
                        autocomplete="off"
                        class="w-full rounded-lg border px-3 py-2 text-sm text-gray-900 placeholder-gray-400 shadow-sm
                               focus:outline-none focus:ring-2 transition
                               <?= $borderClass('learning_competency') ?>"
                        placeholder="e.g. Identifies fractions less than one with denominators 2, 3, 4, 5, 6, 8, and 10 (M3NS-Ib-68.1)"
                        aria-describedby="learning_competency-error"
                    >
                    <span id="learning_competency-error"><?= $err('learning_competency') ?></span>
                </div>

            </div>
        </section>

        <!-- Section 2: Learning Objectives (dynamic list) -->
        <section class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-3">
                <h2 class="text-base font-semibold text-gray-700">Learning Objectives</h2>
                <button type="button" id="load-objectives-template-btn"
                    class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-purple-600 hover:bg-purple-700 text-white text-xs font-medium rounded-md shadow-sm transition-colors focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h10"/></svg>
                    Load Template
                </button>
            </div>

            <div class="flex items-center justify-between mb-3">
                <p class="text-sm text-gray-500">Add one objective per row.</p>
                <button
                    type="button"
                    id="add-objective-btn"
                    class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-medium rounded-md shadow-sm transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Add Objective
                </button>
            </div>

            <!-- Objective rows container -->
            <div id="objectives-container" class="space-y-3">
                <?php if (empty($initialObjectives)): ?>
                    <p id="objectives-empty-hint" class="text-sm text-gray-400 italic">
                        No objectives yet. Click "Add Objective" to begin.
                    </p>
                <?php else: ?>
                    <?php foreach ($initialObjectives as $idx => $obj): ?>
                        <div class="objective-row flex items-center gap-3">
                            <span class="objective-number-badge flex-shrink-0 inline-flex items-center justify-center w-7 h-7 rounded-full bg-blue-100 text-blue-700 text-xs font-bold">
                                <?= $idx + 1 ?>
                            </span>
                            <input type="hidden"
                                   class="objective-sort-input"
                                   name="objectives[<?= $idx ?>][sort_order]"
                                   value="<?= $idx + 1 ?>">
                            <input
                                type="text"
                                name="objectives[<?= $idx ?>][objective_text]"
                                value="<?= htmlspecialchars((string) $obj['objective_text'], ENT_QUOTES, 'UTF-8') ?>"
                                class="flex-1 rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-900 placeholder-gray-400 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                                placeholder="Describe objective <?= $idx + 1 ?> — e.g. Identify fractions as equal parts of a whole"
                                aria-label="Objective <?= $idx + 1 ?>"
                            >
                            <button
                                type="button"
                                class="remove-objective-btn flex-shrink-0 inline-flex items-center justify-center w-7 h-7 rounded-md text-red-500 hover:bg-red-50 hover:text-red-700 transition-colors focus:outline-none focus:ring-2 focus:ring-red-400"
                                aria-label="Remove objective <?= $idx + 1 ?>">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </section>

        <!-- Section 3: Subject Matter -->
        <section class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-5">
                <h2 class="text-base font-semibold text-gray-700">Subject Matter</h2>
                <button type="button" id="load-subject-matter-template-btn"
                    class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-teal-600 hover:bg-teal-700 text-white text-xs font-medium rounded-md shadow-sm transition-colors focus:outline-none focus:ring-2 focus:ring-teal-500 focus:ring-offset-2">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h10"/></svg>
                    Load Template
                </button>
            </div>

            <div class="grid grid-cols-1 gap-6">

                <!-- Topic -->
                <div>
                    <label for="subject_matter_topic" class="block text-sm font-medium text-gray-700 mb-1">
                        Topic
                    </label>
                    <input
                        type="text"
                        id="subject_matter_topic"
                        name="subject_matter_topic"
                        value="<?= $val('subject_matter_topic') ?>"
                        autocomplete="off"
                        class="w-full rounded-lg border px-3 py-2 text-sm text-gray-900 placeholder-gray-400 shadow-sm
                               focus:outline-none focus:ring-2 transition
                               <?= $borderClass('subject_matter_topic') ?>"
                        placeholder="e.g. Fractions less than one with denominators 2, 3, 4, 5, 6, 8, and 10"
                        aria-describedby="subject_matter_topic-error"
                    >
                    <span id="subject_matter_topic-error"><?= $err('subject_matter_topic') ?></span>
                </div>

                <!-- References -->
                <div>
                    <label for="subject_matter_references" class="block text-sm font-medium text-gray-700 mb-1">
                        References
                    </label>
                    <textarea
                        id="subject_matter_references"
                        name="subject_matter_references"
                        rows="3"
                        class="w-full rounded-lg border px-3 py-2 text-sm text-gray-900 placeholder-gray-400 shadow-sm
                               focus:outline-none focus:ring-2 transition resize-y
                               <?= $borderClass('subject_matter_references') ?>"
                        placeholder="e.g.&#10;- Mathematics 3 Learner's Material, pp. 68–72&#10;- K to 12 Curriculum Guide in Mathematics (Grade 3)&#10;- Teacher's Guide in Mathematics 3, pp. 45–50"
                        aria-describedby="subject_matter_references-error"
                    ><?= $val('subject_matter_references') ?></textarea>
                    <span id="subject_matter_references-error"><?= $err('subject_matter_references') ?></span>
                </div>

                <!-- Materials -->
                <div>
                    <label for="subject_matter_materials" class="block text-sm font-medium text-gray-700 mb-1">
                        Materials
                    </label>
                    <textarea
                        id="subject_matter_materials"
                        name="subject_matter_materials"
                        rows="3"
                        class="w-full rounded-lg border px-3 py-2 text-sm text-gray-900 placeholder-gray-400 shadow-sm
                               focus:outline-none focus:ring-2 transition resize-y
                               <?= $borderClass('subject_matter_materials') ?>"
                        placeholder="e.g.&#10;- Fraction strips and cut-outs&#10;- Number line chart (1 per group)&#10;- Colored chalk / markers&#10;- Activity worksheets"
                        aria-describedby="subject_matter_materials-error"
                    ><?= $val('subject_matter_materials') ?></textarea>
                    <span id="subject_matter_materials-error"><?= $err('subject_matter_materials') ?></span>
                </div>

            </div>
        </section>

        <!-- Section 4: Procedure -->
        <section class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-5">
                <h2 class="text-base font-semibold text-gray-700">Procedure</h2>
                <button type="button" id="load-procedure-template-btn"
                    class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-purple-600 hover:bg-purple-700 text-white text-xs font-medium rounded-md shadow-sm transition-colors focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h10"/>
                    </svg>
                    Load Template
                </button>
            </div>

            <div class="grid grid-cols-1 gap-6">

                <!-- Review / Drill -->
                <div>
                    <label for="proc_review_drill" class="block text-sm font-medium text-gray-700 mb-1">
                        A. Review / Drill
                    </label>
                    <textarea
                        id="proc_review_drill"
                        name="proc_review_drill"
                        rows="4"
                        class="w-full rounded-lg border px-3 py-2 text-sm text-gray-900 placeholder-gray-400 shadow-sm
                               focus:outline-none focus:ring-2 transition resize-y
                               <?= $borderClass('proc_review_drill') ?>"
                        placeholder="e.g. Flash cards: Show fraction cards (1/2, 1/3, 1/4). Ask students to identify the fraction and clap the number of equal parts. Review: 'What do we call equal parts of a whole?'"
                        aria-describedby="proc_review_drill-error"
                    ><?= $val('proc_review_drill') ?></textarea>
                    <span id="proc_review_drill-error"><?= $err('proc_review_drill') ?></span>
                </div>

                <!-- Motivation -->
                <div>
                    <label for="proc_motivation" class="block text-sm font-medium text-gray-700 mb-1">
                        B. Motivation
                    </label>
                    <textarea
                        id="proc_motivation"
                        name="proc_motivation"
                        rows="4"
                        class="w-full rounded-lg border px-3 py-2 text-sm text-gray-900 placeholder-gray-400 shadow-sm
                               focus:outline-none focus:ring-2 transition resize-y
                               <?= $borderClass('proc_motivation') ?>"
                        placeholder="e.g. Show a picture of a birthday cake cut into 8 equal slices. Ask: 'If you eat 3 slices, what part of the cake did you eat?' Let students share their answers and lead them to the concept of fractions."
                        aria-describedby="proc_motivation-error"
                    ><?= $val('proc_motivation') ?></textarea>
                    <span id="proc_motivation-error"><?= $err('proc_motivation') ?></span>
                </div>

                <!-- Presentation -->
                <div>
                    <label for="proc_presentation" class="block text-sm font-medium text-gray-700 mb-1">
                        C. Presentation
                    </label>
                    <textarea
                        id="proc_presentation"
                        name="proc_presentation"
                        rows="4"
                        class="w-full rounded-lg border px-3 py-2 text-sm text-gray-900 placeholder-gray-400 shadow-sm
                               focus:outline-none focus:ring-2 transition resize-y
                               <?= $borderClass('proc_presentation') ?>"
                        placeholder="e.g. Using fraction strips, show 1/2, 1/3, and 1/4. Explain: the bottom number (denominator) = total equal parts; the top number (numerator) = parts being considered. Write examples on the board and have students read them aloud."
                        aria-describedby="proc_presentation-error"
                    ><?= $val('proc_presentation') ?></textarea>
                    <span id="proc_presentation-error"><?= $err('proc_presentation') ?></span>
                </div>

                <!-- Discussion -->
                <div>
                    <label for="proc_discussion" class="block text-sm font-medium text-gray-700 mb-1">
                        D. Discussion
                    </label>
                    <textarea
                        id="proc_discussion"
                        name="proc_discussion"
                        rows="4"
                        class="w-full rounded-lg border px-3 py-2 text-sm text-gray-900 placeholder-gray-400 shadow-sm
                               focus:outline-none focus:ring-2 transition resize-y
                               <?= $borderClass('proc_discussion') ?>"
                        placeholder="e.g. Ask: 'What does the numerator tell us? What does the denominator tell us?' Call on students to shade fractions on the board. Discuss: 'Can 2/4 and 1/2 be the same? Why?'"
                        aria-describedby="proc_discussion-error"
                    ><?= $val('proc_discussion') ?></textarea>
                    <span id="proc_discussion-error"><?= $err('proc_discussion') ?></span>
                </div>

                <!-- Generalization -->
                <div>
                    <label for="proc_generalization" class="block text-sm font-medium text-gray-700 mb-1">
                        E. Generalization
                    </label>
                    <textarea
                        id="proc_generalization"
                        name="proc_generalization"
                        rows="3"
                        class="w-full rounded-lg border px-3 py-2 text-sm text-gray-900 placeholder-gray-400 shadow-sm
                               focus:outline-none focus:ring-2 transition resize-y
                               <?= $borderClass('proc_generalization') ?>"
                        placeholder="e.g. Ask: 'What is a fraction?' Guide students to state: A fraction names equal parts of a whole. The numerator tells how many parts are taken; the denominator tells how many equal parts in all."
                        aria-describedby="proc_generalization-error"
                    ><?= $val('proc_generalization') ?></textarea>
                    <span id="proc_generalization-error"><?= $err('proc_generalization') ?></span>
                </div>

                <!-- Application -->
                <div>
                    <label for="proc_application" class="block text-sm font-medium text-gray-700 mb-1">
                        F. Application
                    </label>
                    <textarea
                        id="proc_application"
                        name="proc_application"
                        rows="3"
                        class="w-full rounded-lg border px-3 py-2 text-sm text-gray-900 placeholder-gray-400 shadow-sm
                               focus:outline-none focus:ring-2 transition resize-y
                               <?= $borderClass('proc_application') ?>"
                        placeholder="e.g. Group work: Give each group a set of fraction cards. Students sort the cards from smallest to largest and paste them on a number line. Each group presents their output to the class."
                        aria-describedby="proc_application-error"
                    ><?= $val('proc_application') ?></textarea>
                    <span id="proc_application-error"><?= $err('proc_application') ?></span>
                </div>

            </div>
        </section>

        <!-- Section 5: Evaluation & Assignment -->
        <section class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-5">
                <h2 class="text-base font-semibold text-gray-700">Evaluation &amp; Assignment</h2>
                <button type="button" id="load-eval-template-btn"
                    class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-orange-600 hover:bg-orange-700 text-white text-xs font-medium rounded-md shadow-sm transition-colors focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h10"/></svg>
                    Load Template
                </button>
            </div>

            <div class="grid grid-cols-1 gap-6">

                <!-- Evaluation -->
                <div>
                    <label for="evaluation" class="block text-sm font-medium text-gray-700 mb-1">
                        Evaluation
                    </label>
                    <textarea
                        id="evaluation"
                        name="evaluation"
                        rows="4"
                        class="w-full rounded-lg border px-3 py-2 text-sm text-gray-900 placeholder-gray-400 shadow-sm
                               focus:outline-none focus:ring-2 transition resize-y
                               <?= $borderClass('evaluation') ?>"
                        placeholder="e.g. Written test: Shade the correct fraction in 5 diagrams (2 pts each = 10 pts). Identify the numerator and denominator in 5 fractions (1 pt each = 5 pts). Total: 15 points. Mastery: 12/15."
                        aria-describedby="evaluation-error"
                    ><?= $val('evaluation') ?></textarea>
                    <span id="evaluation-error"><?= $err('evaluation') ?></span>
                </div>

                <!-- Assignment / Agreement -->
                <div>
                    <label for="assignment" class="block text-sm font-medium text-gray-700 mb-1">
                        Assignment / Agreement
                    </label>
                    <textarea
                        id="assignment"
                        name="assignment"
                        rows="3"
                        class="w-full rounded-lg border px-3 py-2 text-sm text-gray-900 placeholder-gray-400 shadow-sm
                               focus:outline-none focus:ring-2 transition resize-y
                               <?= $borderClass('assignment') ?>"
                        placeholder="e.g. Study pp. 68–72 of your Math 3 Learner's Material. Draw 3 objects divided into equal parts and write the fraction for the shaded portion. Be ready to share tomorrow."
                        aria-describedby="assignment-error"
                    ><?= $val('assignment') ?></textarea>
                    <span id="assignment-error"><?= $err('assignment') ?></span>
                </div>

            </div>
        </section>

        <!-- Completeness Indicator -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
            <div class="flex items-center justify-between mb-2">
                <span class="text-sm font-medium text-gray-700">Form Completeness</span>
                <span id="lp-completeness-pct" class="text-sm font-semibold text-blue-600">0%</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2.5">
                <div id="lp-completeness-bar" class="bg-blue-600 h-2.5 rounded-full transition-all duration-300" style="width:0%"></div>
            </div>
            <div id="lp-completeness-checklist" class="mt-3 grid grid-cols-2 sm:grid-cols-3 gap-1.5 text-xs text-gray-500"></div>
        </div>

        <!-- Form actions -->
        <div class="flex flex-col sm:flex-row items-center justify-end gap-3">
            <a href="<?= url('/lesson-plans') ?>"
               class="w-full sm:w-auto inline-flex items-center justify-center gap-1.5 px-5 py-2.5 bg-gray-200 hover:bg-gray-300 text-gray-700 text-sm font-medium rounded-lg shadow-sm transition-colors focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-offset-2">
                Cancel
            </a>
            <button
                type="submit"
                class="w-full sm:w-auto inline-flex items-center justify-center gap-1.5 px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg shadow-sm transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M5 13l4 4L19 7" />
                </svg>
                <?= $isEdit ? 'Save Changes' : 'Create Lesson Plan' ?>
            </button>
        </div>

    </div><!-- /space-y-8 -->

</form>

<!-- ── Learning Objectives Template Modal ────────────────────────────────── -->
<div id="objectives-template-modal"
     class="fixed inset-0 z-50 hidden items-center justify-center p-4"
     role="dialog" aria-modal="true" aria-labelledby="obj-modal-title">
    <div id="obj-modal-backdrop" class="absolute inset-0 bg-black/50"></div>
    <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-xl max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200 sticky top-0 bg-white rounded-t-2xl">
            <div>
                <h3 id="obj-modal-title" class="text-base font-semibold text-gray-800">Load Learning Objectives Template</h3>
                <p class="text-xs text-gray-500 mt-0.5">Choose a subject to load 3 ready-made objectives. You can edit them after loading.</p>
            </div>
            <button type="button" id="close-obj-modal-btn" class="inline-flex items-center justify-center w-8 h-8 rounded-md text-gray-400 hover:bg-gray-100 hover:text-gray-600 transition-colors focus:outline-none focus:ring-2 focus:ring-gray-400">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <div class="p-6 space-y-3">
            <?php
            $objTemplates = [
                ['key'=>'math-obj',    'color'=>'blue',   'label'=>'M',  'title'=>'Mathematics (Fractions)',         'desc'=>'Identify, represent, and compare fractions'],
                ['key'=>'english-obj', 'color'=>'green',  'label'=>'E',  'title'=>'English (Reading Comprehension)', 'desc'=>'Identify story elements, infer meaning, retell'],
                ['key'=>'filipino-obj','color'=>'yellow', 'label'=>'F',  'title'=>'Filipino (Pagbabasa)',            'desc'=>'Tukuyin ang mga tauhan, tagpuan, at aral'],
                ['key'=>'science-obj', 'color'=>'teal',   'label'=>'S',  'title'=>'Science (Living Things)',         'desc'=>'Classify, describe, and compare living things'],
                ['key'=>'ap-obj',      'color'=>'orange', 'label'=>'AP', 'title'=>'Araling Panlipunan (Komunidad)',  'desc'=>'Tukuyin, ilarawan, at ihambing ang komunidad'],
                ['key'=>'mapeh-obj',   'color'=>'pink',   'label'=>'MP', 'title'=>'MAPEH (Music)',                   'desc'=>'Identify, perform, and appreciate music elements'],
                ['key'=>'esp-obj',     'color'=>'indigo', 'label'=>'EP', 'title'=>'EsP (Pagpapahalaga)',             'desc'=>'Tukuyin, ipakita, at pahalagahan ang birtud'],
            ];
            foreach ($objTemplates as $t):
            ?>
            <div class="border border-gray-200 rounded-xl overflow-hidden">
                <button type="button" data-obj-template="<?= $t['key'] ?>"
                    class="w-full flex items-center justify-between px-4 py-3 bg-<?= $t['color'] ?>-50 hover:bg-<?= $t['color'] ?>-100 transition-colors text-left">
                    <div class="flex items-center gap-3">
                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-<?= $t['color'] ?>-600 text-white text-xs font-bold"><?= $t['label'] ?></span>
                        <div>
                            <p class="text-sm font-semibold text-gray-800"><?= $t['title'] ?></p>
                            <p class="text-xs text-gray-500"><?= $t['desc'] ?></p>
                        </div>
                    </div>
                    <svg class="w-4 h-4 text-<?= $t['color'] ?>-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </button>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<!-- ── Subject Matter Template Modal ─────────────────────────────────────── -->
<div id="subject-matter-modal"
     class="fixed inset-0 z-50 hidden items-center justify-center p-4"
     role="dialog" aria-modal="true" aria-labelledby="sm-modal-title">
    <div id="sm-modal-backdrop" class="absolute inset-0 bg-black/50"></div>
    <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-xl max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200 sticky top-0 bg-white rounded-t-2xl">
            <div>
                <h3 id="sm-modal-title" class="text-base font-semibold text-gray-800">Load Subject Matter Template</h3>
                <p class="text-xs text-gray-500 mt-0.5">Fills Topic, References, and Materials. Edit after loading.</p>
            </div>
            <button type="button" id="close-sm-modal-btn" class="inline-flex items-center justify-center w-8 h-8 rounded-md text-gray-400 hover:bg-gray-100 hover:text-gray-600 transition-colors focus:outline-none focus:ring-2 focus:ring-gray-400">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <div class="p-6 space-y-3">
            <?php
            $smTemplates = [
                ['key'=>'math-sm',    'color'=>'blue',   'label'=>'M',  'title'=>'Mathematics — Fractions (Grade 3)',         'desc'=>'Topic, LM references, fraction manipulatives'],
                ['key'=>'english-sm', 'color'=>'green',  'label'=>'E',  'title'=>'English — Reading Comprehension (Grade 4)', 'desc'=>'Topic, textbook references, reading materials'],
                ['key'=>'filipino-sm','color'=>'yellow', 'label'=>'F',  'title'=>'Filipino — Pagbabasa (Grade 3)',            'desc'=>'Paksa, sanggunian, kagamitan'],
                ['key'=>'science-sm', 'color'=>'teal',   'label'=>'S',  'title'=>'Science — Living Things (Grade 3)',         'desc'=>'Topic, science references, observation materials'],
                ['key'=>'ap-sm',      'color'=>'orange', 'label'=>'AP', 'title'=>'Araling Panlipunan — Komunidad (Grade 3)',  'desc'=>'Paksa, sanggunian, mapa at larawan'],
                ['key'=>'mapeh-sm',   'color'=>'pink',   'label'=>'MP', 'title'=>'MAPEH — Music (Grade 4)',                   'desc'=>'Topic, music references, instruments/recordings'],
                ['key'=>'esp-sm',     'color'=>'indigo', 'label'=>'EP', 'title'=>'EsP — Pagpapahalaga (Grade 5)',             'desc'=>'Paksa, sanggunian, sitwasyon cards'],
            ];
            foreach ($smTemplates as $t):
            ?>
            <div class="border border-gray-200 rounded-xl overflow-hidden">
                <button type="button" data-sm-template="<?= $t['key'] ?>"
                    class="w-full flex items-center justify-between px-4 py-3 bg-<?= $t['color'] ?>-50 hover:bg-<?= $t['color'] ?>-100 transition-colors text-left">
                    <div class="flex items-center gap-3">
                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-<?= $t['color'] ?>-600 text-white text-xs font-bold"><?= $t['label'] ?></span>
                        <div>
                            <p class="text-sm font-semibold text-gray-800"><?= $t['title'] ?></p>
                            <p class="text-xs text-gray-500"><?= $t['desc'] ?></p>
                        </div>
                    </div>
                    <svg class="w-4 h-4 text-<?= $t['color'] ?>-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </button>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<!-- ── Objectives + Subject Matter Template JS ───────────────────────────── -->
<script>
(function () {
    'use strict';

    // ── Objectives template data ──────────────────────────────────────────────
    var objTemplates = {
        'math-obj': [
            'Identify fractions as equal parts of a whole with denominators 2, 3, 4, 5, 6, 8, and 10.',
            'Represent fractions using models, fraction strips, and number lines.',
            'Compare fractions with the same denominator using the symbols <, >, and =.'
        ],
        'english-obj': [
            'Identify the characters, setting, problem, and solution in a story.',
            'Infer the meaning of unfamiliar words using context clues.',
            'Retell the story in sequence using key details from the text.'
        ],
        'filipino-obj': [
            'Natutukoy ang mga tauhan, tagpuan, suliranin, at solusyon ng kwento.',
            'Naipapaliwanag ang kahulugan ng mga salita gamit ang konteksto.',
            'Naipapahayag ang aral na natutuhan mula sa kwento sa sariling salita.'
        ],
        'science-obj': [
            'Classify objects as living or non-living based on their characteristics.',
            'Describe the basic needs of living things (food, water, air, shelter).',
            'Compare the characteristics of plants and animals as living things.'
        ],
        'ap-obj': [
            'Natutukoy ang mga uri ng komunidad (lungsod, bayan, baryo) at ang kanilang katangian.',
            'Nailarawan ang mga pasilidad at serbisyong makikita sa bawat uri ng komunidad.',
            'Naipapakita ang pagpapahalaga sa sariling komunidad sa pamamagitan ng mga gawain.'
        ],
        'mapeh-obj': [
            'Identify the elements of music: melody, rhythm, and dynamics in a given song.',
            'Perform the song with correct pitch, rhythm, and appropriate dynamics.',
            'Appreciate the cultural significance of Filipino folk songs.'
        ],
        'esp-obj': [
            'Natutukoy ang kahulugan at kahalagahan ng pagpapahalaga na tatalakayin.',
            'Naipapakita ang pagpapahalaga sa pamamagitan ng mga kongkretong halimbawa sa paaralan at tahanan.',
            'Naipapahayag ang personal na pangako na isasabuhay ang pagpapahalaga sa araw-araw na buhay.'
        ]
    };

    // ── Subject Matter template data ──────────────────────────────────────────
    var smTemplates = {
        'math-sm': {
            topic:      'Fractions less than one with denominators 2, 3, 4, 5, 6, 8, and 10',
            references: '- Mathematics 3 Learner\'s Material (LM), pp. 68–72\n- K to 12 Curriculum Guide in Mathematics (Grade 3), p. 15\n- Teacher\'s Guide in Mathematics 3, pp. 45–50',
            materials:  '- Fraction strips and cut-outs (1 set per group)\n- Number line chart (0 to 1)\n- Colored chalk / whiteboard markers\n- Activity worksheets\n- Flashcards with fraction symbols'
        },
        'english-sm': {
            topic:      'Reading Comprehension: Story Elements (Characters, Setting, Problem, Solution)',
            references: '- English 4 Learner\'s Material, Unit 2, pp. 45–52\n- K to 12 Curriculum Guide in English (Grade 4)\n- Teacher\'s Guide in English 4, pp. 30–36',
            materials:  '- Short story text (printed copies, 1 per student)\n- Graphic organizer worksheet (story map)\n- Vocabulary cards\n- Whiteboard and markers\n- Picture cards related to the story'
        },
        'filipino-sm': {
            topic:      'Pagbabasa ng Maikling Kwento: Mga Bahagi ng Kwento',
            references: '- Filipino 3 Kagamitan ng Mag-aaral, pp. 55–62\n- K to 12 Gabay sa Kurikulum sa Filipino (Baitang 3)\n- Gabay ng Guro sa Filipino 3, pp. 40–48',
            materials:  '- Kopya ng maikling kwento (1 bawat mag-aaral)\n- Graphic organizer (tauhan, tagpuan, suliranin, solusyon)\n- Mga larawan na may kaugnayan sa kwento\n- Pisara at tisa / whiteboard markers\n- Flashcard ng mga salita'
        },
        'science-sm': {
            topic:      'Characteristics of Living and Non-Living Things',
            references: '- Science 3 Learner\'s Material, pp. 12–18\n- K to 12 Curriculum Guide in Science (Grade 3)\n- Teacher\'s Guide in Science 3, pp. 8–14',
            materials:  '- Small potted plant and a rock (for demonstration)\n- Picture cards of living and non-living things (10 cards per group)\n- Observation chart / worksheet\n- Magnifying glass (optional)\n- Whiteboard and markers'
        },
        'ap-sm': {
            topic:      'Mga Uri ng Komunidad: Lungsod, Bayan, at Baryo',
            references: '- Araling Panlipunan 3 Kagamitan ng Mag-aaral, pp. 20–28\n- K to 12 Gabay sa Kurikulum sa Araling Panlipunan (Baitang 3)\n- Gabay ng Guro sa AP 3, pp. 15–22',
            materials:  '- Mapa ng komunidad (1 bawat pangkat)\n- Mga larawan ng lungsod, bayan, at baryo\n- Graphic organizer worksheet\n- Pisara at tisa / whiteboard markers\n- Mga larawan ng pasilidad sa komunidad'
        },
        'mapeh-sm': {
            topic:      'Elements of Music: Melody, Rhythm, and Dynamics',
            references: '- MAPEH 4 Learner\'s Material (Music), pp. 8–15\n- K to 12 Curriculum Guide in MAPEH (Grade 4)\n- Teacher\'s Guide in MAPEH 4, pp. 5–12',
            materials:  '- Audio recording of a Filipino folk song\n- Song lyrics (printed, 1 per student)\n- Simple percussion instruments (tambourine, claves, or improvised)\n- Musical notation chart\n- Whiteboard and markers'
        },
        'esp-sm': {
            topic:      'Pagpapahalaga: Kahulugan, Kahalagahan, at Paraan ng Pagpapakita',
            references: '- EsP 5 Kagamitan ng Mag-aaral, pp. 30–38\n- K to 12 Gabay sa Kurikulum sa EsP (Baitang 5)\n- Gabay ng Guro sa EsP 5, pp. 25–32',
            materials:  '- Mga sitwasyon cards (5 sitwasyon)\n- Graphic organizer worksheet\n- Mga larawan na nagpapakita ng mabuting pagpapahalaga\n- Pisara at tisa / whiteboard markers\n- Pangako card (1 bawat mag-aaral)'
        }
    };

    // ── Helper: modal open/close factory ─────────────────────────────────────
    function makeModal(modalId, backdropId, openBtnId, closeBtnId) {
        var modal    = document.getElementById(modalId);
        var backdrop = document.getElementById(backdropId);
        var openBtn  = document.getElementById(openBtnId);
        var closeBtn = document.getElementById(closeBtnId);
        function open()  { if (modal) { modal.classList.remove('hidden'); modal.classList.add('flex'); document.body.style.overflow = 'hidden'; } }
        function close() { if (modal) { modal.classList.add('hidden'); modal.classList.remove('flex'); document.body.style.overflow = ''; } }
        if (openBtn)  openBtn.addEventListener('click', open);
        if (closeBtn) closeBtn.addEventListener('click', close);
        if (backdrop) backdrop.addEventListener('click', close);
        return close;
    }

    var closeObjModal = makeModal('objectives-template-modal', 'obj-modal-backdrop', 'load-objectives-template-btn', 'close-obj-modal-btn');
    var closeSmModal  = makeModal('subject-matter-modal',      'sm-modal-backdrop',  'load-subject-matter-template-btn', 'close-sm-modal-btn');

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') { closeObjModal(); closeSmModal(); }
    });

    // ── Helper: add objective row ─────────────────────────────────────────────
    function addObjectiveWithText(text) {
        var container = document.getElementById('objectives-container');
        var emptyHint = document.getElementById('objectives-empty-hint');
        if (!container) return;
        if (emptyHint) emptyHint.style.display = 'none';
        var idx = container.querySelectorAll('.objective-row').length;
        var num = idx + 1;
        var row = document.createElement('div');
        row.className = 'objective-row flex items-center gap-3';
        row.innerHTML =
            '<span class="objective-number-badge flex-shrink-0 inline-flex items-center justify-center w-7 h-7 rounded-full bg-blue-100 text-blue-700 text-xs font-bold">' + num + '</span>'
            + '<input type="hidden" class="objective-sort-input" name="objectives[' + idx + '][sort_order]" value="' + num + '">'
            + '<input type="text" name="objectives[' + idx + '][objective_text]" class="flex-1 rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-900 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition" aria-label="Objective ' + num + '">'
            + '<button type="button" class="remove-objective-btn flex-shrink-0 inline-flex items-center justify-center w-7 h-7 rounded-md text-red-500 hover:bg-red-50 hover:text-red-700 transition-colors focus:outline-none focus:ring-2 focus:ring-red-400" aria-label="Remove objective ' + num + '"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>';
        container.appendChild(row);
        var input = row.querySelector('input[type="text"]');
        if (input) input.value = text;
    }

    // ── Apply objectives template ─────────────────────────────────────────────
    document.querySelectorAll('[data-obj-template]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var key  = btn.getAttribute('data-obj-template');
            var objs = objTemplates[key];
            if (!objs) return;

            var container = document.getElementById('objectives-container');
            var hasContent = container && container.querySelectorAll('.objective-row').length > 0;
            if (hasContent && !confirm('This will replace existing objectives. Continue?')) return;

            // Clear existing
            if (container) container.querySelectorAll('.objective-row').forEach(function (r) { r.remove(); });
            var hint = document.getElementById('objectives-empty-hint');
            if (hint) hint.style.display = 'none';

            objs.forEach(function (text) { addObjectiveWithText(text); });
            closeObjModal();
        });
    });

    // ── Apply subject matter template ─────────────────────────────────────────
    document.querySelectorAll('[data-sm-template]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var key = btn.getAttribute('data-sm-template');
            var tpl = smTemplates[key];
            if (!tpl) return;

            var topicEl = document.getElementById('subject_matter_topic');
            var refEl   = document.getElementById('subject_matter_references');
            var matEl   = document.getElementById('subject_matter_materials');

            var hasContent = [topicEl, refEl, matEl].some(function (f) { return f && f.value.trim() !== ''; });
            if (hasContent && !confirm('This will replace existing subject matter content. Continue?')) return;

            if (topicEl) topicEl.value = tpl.topic;
            if (refEl)   refEl.value   = tpl.references;
            if (matEl)   matEl.value   = tpl.materials;

            closeSmModal();
        });
    });

})();
</script>

<!-- ── Procedure Template Modal ──────────────────────────────────────────── -->
<div id="procedure-template-modal"
     class="fixed inset-0 z-50 hidden items-center justify-center p-4"
     role="dialog" aria-modal="true" aria-labelledby="modal-title">

    <!-- Backdrop -->
    <div id="modal-backdrop" class="absolute inset-0 bg-black/50"></div>

    <!-- Panel -->
    <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">

        <!-- Header -->
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200 sticky top-0 bg-white rounded-t-2xl">
            <div>
                <h3 id="modal-title" class="text-base font-semibold text-gray-800">Load Procedure Template</h3>
                <p class="text-xs text-gray-500 mt-0.5">Choose a template to fill all procedure fields. Existing content will be replaced.</p>
            </div>
            <button type="button" id="close-modal-btn"
                class="inline-flex items-center justify-center w-8 h-8 rounded-md text-gray-400 hover:bg-gray-100 hover:text-gray-600 transition-colors focus:outline-none focus:ring-2 focus:ring-gray-400">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <!-- Template list -->
        <div class="p-6 space-y-3" id="template-list">

            <!-- Mathematics -->
            <div class="template-card border border-gray-200 rounded-xl overflow-hidden">
                <button type="button" data-template="math"
                    class="w-full flex items-center justify-between px-4 py-3 bg-blue-50 hover:bg-blue-100 transition-colors text-left">
                    <div class="flex items-center gap-3">
                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-blue-600 text-white text-xs font-bold">M</span>
                        <div>
                            <p class="text-sm font-semibold text-gray-800">Mathematics — Fractions (Grade 3)</p>
                            <p class="text-xs text-gray-500">Review drill → Motivation → Presentation → Discussion → Generalization → Application</p>
                        </div>
                    </div>
                    <svg class="w-4 h-4 text-blue-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </button>
            </div>

            <!-- English -->
            <div class="template-card border border-gray-200 rounded-xl overflow-hidden">
                <button type="button" data-template="english"
                    class="w-full flex items-center justify-between px-4 py-3 bg-green-50 hover:bg-green-100 transition-colors text-left">
                    <div class="flex items-center gap-3">
                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-green-600 text-white text-xs font-bold">E</span>
                        <div>
                            <p class="text-sm font-semibold text-gray-800">English — Reading Comprehension (Grade 4)</p>
                            <p class="text-xs text-gray-500">Vocabulary drill → Story motivation → Text presentation → Discussion → Generalization → Application</p>
                        </div>
                    </div>
                    <svg class="w-4 h-4 text-green-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </button>
            </div>

            <!-- Filipino -->
            <div class="template-card border border-gray-200 rounded-xl overflow-hidden">
                <button type="button" data-template="filipino"
                    class="w-full flex items-center justify-between px-4 py-3 bg-yellow-50 hover:bg-yellow-100 transition-colors text-left">
                    <div class="flex items-center gap-3">
                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-yellow-600 text-white text-xs font-bold">F</span>
                        <div>
                            <p class="text-sm font-semibold text-gray-800">Filipino — Pagbabasa at Pagsulat (Grade 3)</p>
                            <p class="text-xs text-gray-500">Baybay-salita → Pagganyak → Pagtatanghal → Talakayan → Pangkalahatang Ideya → Aplikasyon</p>
                        </div>
                    </div>
                    <svg class="w-4 h-4 text-yellow-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </button>
            </div>

            <!-- Science -->
            <div class="template-card border border-gray-200 rounded-xl overflow-hidden">
                <button type="button" data-template="science"
                    class="w-full flex items-center justify-between px-4 py-3 bg-teal-50 hover:bg-teal-100 transition-colors text-left">
                    <div class="flex items-center gap-3">
                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-teal-600 text-white text-xs font-bold">S</span>
                        <div>
                            <p class="text-sm font-semibold text-gray-800">Science — Living Things (Grade 3)</p>
                            <p class="text-xs text-gray-500">Concept review → Curiosity motivation → Observation presentation → Discussion → Generalization → Application</p>
                        </div>
                    </div>
                    <svg class="w-4 h-4 text-teal-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </button>
            </div>

            <!-- Araling Panlipunan -->
            <div class="template-card border border-gray-200 rounded-xl overflow-hidden">
                <button type="button" data-template="ap"
                    class="w-full flex items-center justify-between px-4 py-3 bg-orange-50 hover:bg-orange-100 transition-colors text-left">
                    <div class="flex items-center gap-3">
                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-orange-600 text-white text-xs font-bold">AP</span>
                        <div>
                            <p class="text-sm font-semibold text-gray-800">Araling Panlipunan — Komunidad (Grade 3)</p>
                            <p class="text-xs text-gray-500">Pagsusuri → Pagganyak → Pagtatanghal → Talakayan → Pangkalahatang Ideya → Aplikasyon</p>
                        </div>
                    </div>
                    <svg class="w-4 h-4 text-orange-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </button>
            </div>

            <!-- MAPEH -->
            <div class="template-card border border-gray-200 rounded-xl overflow-hidden">
                <button type="button" data-template="mapeh"
                    class="w-full flex items-center justify-between px-4 py-3 bg-pink-50 hover:bg-pink-100 transition-colors text-left">
                    <div class="flex items-center gap-3">
                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-pink-600 text-white text-xs font-bold">MP</span>
                        <div>
                            <p class="text-sm font-semibold text-gray-800">MAPEH — Music (Grade 4)</p>
                            <p class="text-xs text-gray-500">Rhythm drill → Song motivation → Concept presentation → Discussion → Generalization → Performance application</p>
                        </div>
                    </div>
                    <svg class="w-4 h-4 text-pink-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </button>
            </div>

            <!-- EsP -->
            <div class="template-card border border-gray-200 rounded-xl overflow-hidden">
                <button type="button" data-template="esp"
                    class="w-full flex items-center justify-between px-4 py-3 bg-indigo-50 hover:bg-indigo-100 transition-colors text-left">
                    <div class="flex items-center gap-3">
                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-indigo-600 text-white text-xs font-bold">EP</span>
                        <div>
                            <p class="text-sm font-semibold text-gray-800">EsP — Pagpapahalaga (Grade 5)</p>
                            <p class="text-xs text-gray-500">Sitwasyon review → Kwento motivation → Pagtatanghal → Talakayan → Pangkalahatang Ideya → Aplikasyon</p>
                        </div>
                    </div>
                    <svg class="w-4 h-4 text-indigo-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </button>
            </div>

            <!-- Generic / Blank structure -->
            <div class="template-card border border-gray-200 rounded-xl overflow-hidden">
                <button type="button" data-template="generic"
                    class="w-full flex items-center justify-between px-4 py-3 bg-gray-50 hover:bg-gray-100 transition-colors text-left">
                    <div class="flex items-center gap-3">
                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-gray-600 text-white text-xs font-bold">G</span>
                        <div>
                            <p class="text-sm font-semibold text-gray-800">Generic DepEd Template (Any Subject)</p>
                            <p class="text-xs text-gray-500">Standard DepEd DLP procedure structure with guiding prompts for each section</p>
                        </div>
                    </div>
                    <svg class="w-4 h-4 text-gray-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </button>
            </div>

        </div><!-- /template-list -->
    </div><!-- /panel -->
</div><!-- /modal -->

<!-- ── Procedure Template JavaScript ─────────────────────────────────────── -->
<script>
(function () {
    'use strict';

    // ── Template data ────────────────────────────────────────────────────────
    var templates = {
        math: {
            review:       'Flash card drill: Show fraction cards (1/2, 1/3, 1/4, 2/3, 3/4). Call on students to read each fraction aloud and identify the numerator and denominator. Ask: "What do we call the equal parts of a whole?"',
            motivation:   'Show a picture of a pizza cut into 8 equal slices. Ask: "If you eat 3 slices, what part of the pizza did you eat?" Let students share their answers. Guide them to see that they are describing a fraction.',
            presentation: 'Using fraction strips, show 1/2, 1/3, and 1/4 on the board. Explain:\n- The denominator (bottom number) = total equal parts the whole is divided into.\n- The numerator (top number) = number of parts being considered.\nWrite several examples on the board (e.g., 2/5, 3/8) and have students read them aloud.',
            discussion:   'Ask guiding questions:\n1. "What does the numerator tell us?"\n2. "What does the denominator tell us?"\n3. "If a pizza is cut into 4 slices and you eat 2, what fraction did you eat?"\n4. "Can 2/4 and 1/2 be the same amount? Why?"\nCall on volunteers to shade fractions on the board.',
            generalization: 'Ask: "What is a fraction?" Guide students to state the generalization:\nA fraction represents equal parts of a whole. The numerator tells how many parts are taken; the denominator tells how many equal parts the whole is divided into.',
            application:  'Group Activity: Give each group a set of fraction cards and a number line (0 to 1). Students arrange the fraction cards from smallest to largest and paste them on the number line. Each group presents their work and explains their arrangement.'
        },
        english: {
            review:       'Vocabulary drill: Flash 5 vocabulary words from the previous lesson. Students give the meaning and use each word in a sentence. Review: "What are the parts of a story we discussed last time?"',
            motivation:   'Show the cover of a short story or picture book. Ask: "What do you think this story is about? What clues does the cover give you?" Let students predict the story. Tell them they will read to find out if their predictions are correct.',
            presentation: 'Read the story aloud while students follow along. Pause at key moments to check understanding. Point out:\n- Setting: Where and when the story takes place.\n- Characters: Who the story is about.\n- Problem and Solution: What challenge the characters face and how it is resolved.',
            discussion:   'Ask comprehension questions:\n1. "Who are the main characters?"\n2. "Where does the story take place?"\n3. "What was the problem in the story?"\n4. "How was the problem solved?"\n5. "What lesson did you learn from the story?"\nHave students support their answers with details from the text.',
            generalization: 'Ask: "What do good readers do when they read a story?" Guide students to state:\nGood readers identify the characters, setting, problem, and solution. They use details from the text to support their understanding.',
            application:  'Individual Activity: Students answer a short comprehension worksheet with 5 questions about the story. Then they write 2–3 sentences describing their favorite part and why they liked it.'
        },
        filipino: {
            review:       'Baybay-salita: Ipakita ang 5 salita mula sa nakaraang aralin. Basahin ng mga mag-aaral nang sabay-sabay. Tanungin: "Ano ang kahulugan ng mga salitang ito?"',
            motivation:   'Magpakita ng larawan na may kaugnayan sa paksa. Tanungin: "Ano ang nakikita ninyo sa larawan? Ano ang nararamdaman ng taong nasa larawan?" Hayaan ang mga mag-aaral na ibahagi ang kanilang mga sagot.',
            presentation: 'Basahin ang maikling kwento o talata nang malakas. Ituro ang:\n- Mga tauhan at kanilang katangian.\n- Tagpuan ng kwento.\n- Suliranin at solusyon.\nIsulat ang mga pangunahing ideya sa pisara.',
            discussion:   'Magtanong ng mga gabay na tanong:\n1. "Sino ang mga tauhan sa kwento?"\n2. "Saan at kailan naganap ang kwento?"\n3. "Ano ang suliranin ng pangunahing tauhan?"\n4. "Paano nalutas ang suliranin?"\n5. "Ano ang aral na natutuhan ninyo?"',
            generalization: 'Tanungin: "Ano ang natutuhan natin ngayon?" Gabayan ang mga mag-aaral na sabihin:\nAng isang kwento ay may mga tauhan, tagpuan, suliranin, at solusyon. Ang pag-unawa sa mga bahaging ito ay tumutulong sa atin na maunawaan ang mensahe ng kwento.',
            application:  'Pangkatang Gawain: Ang bawat pangkat ay gagawa ng maikling buod ng kwento gamit ang graphic organizer (tauhan, tagpuan, suliranin, solusyon). Ipapakita ng bawat pangkat ang kanilang gawa sa klase.'
        },
        science: {
            review:       'Concept review: Show pictures of living and non-living things. Students classify each as living or non-living and give one reason for their answer. Ask: "What are the characteristics of living things we learned before?"',
            motivation:   'Bring a small potted plant and a rock to class. Ask: "How are these two objects different? What can the plant do that the rock cannot?" Let students observe and share their observations. Tell them they will learn more about what makes something alive.',
            presentation: 'Present the characteristics of living things using pictures and real objects:\n1. Living things grow and develop.\n2. Living things need food, water, and air.\n3. Living things respond to their environment.\n4. Living things reproduce.\nGive examples for each characteristic.',
            discussion:   'Ask guiding questions:\n1. "Can you give an example of a living thing that grows?"\n2. "What do plants need to survive?"\n3. "How does a plant respond to sunlight?"\n4. "What would happen to a plant if it had no water?"\nHave students compare a plant and an animal using the characteristics.',
            generalization: 'Ask: "What makes something a living thing?" Guide students to state:\nLiving things share common characteristics: they grow, need food/water/air, respond to their environment, and can reproduce. Non-living things do not have these characteristics.',
            application:  'Activity: Students sort a set of picture cards into two groups — Living Things and Non-Living Things. They write one sentence explaining why each item belongs in its group. Share and discuss as a class.'
        },
        ap: {
            review:       'Pagsusuri ng nakaraang aralin: Ipakita ang mapa ng komunidad. Tanungin ang mga mag-aaral na tukuyin ang mga lugar na napag-aralan. Itanong: "Ano ang mga lugar na makikita sa ating komunidad?"',
            motivation:   'Magpakita ng larawan ng iba\'t ibang uri ng komunidad (lungsod, bayan, baryo). Tanungin: "Saan kayo nakatira? Ano ang mga katangian ng inyong komunidad?" Hayaan ang mga mag-aaral na ibahagi ang kanilang karanasan.',
            presentation: 'Ipaliwanag ang mga uri ng komunidad:\n1. Lungsod — maraming tao, mataas na gusali, maraming sasakyan.\n2. Bayan — katamtamang bilang ng tao, may palengke at paaralan.\n3. Baryo — kaunting tao, tahimik, malapit sa kalikasan.\nGamitin ang mga larawan at mapa bilang visual aids.',
            discussion:   'Magtanong ng mga gabay na tanong:\n1. "Ano ang pagkakaiba ng lungsod at baryo?"\n2. "Ano ang mga pasilidad na makikita sa lungsod?"\n3. "Bakit mahalaga ang komunidad sa ating buhay?"\n4. "Paano tayo makakatulong sa ating komunidad?"\nHayaan ang mga mag-aaral na ibahagi ang kanilang mga sagot.',
            generalization: 'Tanungin: "Ano ang komunidad?" Gabayan ang mga mag-aaral na sabihin:\nAng komunidad ay isang lugar kung saan nakatira ang mga tao. Mayroon itong iba\'t ibang uri tulad ng lungsod, bayan, at baryo. Ang bawat komunidad ay may sariling katangian at pasilidad.',
            application:  'Pangkatang Gawain: Ang bawat pangkat ay gagawa ng poster ng kanilang komunidad. Isasama nila ang mga lugar, pasilidad, at tao sa kanilang komunidad. Ipapakita ng bawat pangkat ang kanilang poster at ipapaliwanag ang mga nilalaman nito.'
        },
        mapeh: {
            review:       'Rhythm drill: Clap the rhythm patterns from the previous lesson. Students echo-clap each pattern. Ask: "What are the note values we learned? How many beats does a quarter note get?"',
            motivation:   'Play a short recording of a familiar Filipino folk song. Ask: "Have you heard this song before? What do you feel when you listen to it?" Let students share their reactions. Tell them they will learn to sing and understand this song.',
            presentation: 'Teach the song line by line:\n1. Sing the first phrase; students echo.\n2. Sing the second phrase; students echo.\n3. Combine both phrases; sing together.\nPoint out the melody, rhythm, and dynamics (loud/soft parts). Show the musical notation on the board.',
            discussion:   'Ask guiding questions:\n1. "What is the mood of the song — happy, sad, or lively?"\n2. "Which part of the song is sung loudly? Softly?"\n3. "What is the song about?"\n4. "How does the rhythm make you feel like moving?"\nHave students identify the beat by tapping on their desks.',
            generalization: 'Ask: "What did we learn about this song?" Guide students to state:\nMusic has melody, rhythm, and dynamics. The melody is the tune; the rhythm is the pattern of beats; dynamics tell us how loud or soft to sing. These elements work together to express feelings.',
            application:  'Performance Activity: Students sing the song as a class with proper dynamics. Then small groups perform the song with simple body movements or percussion instruments (clapping, tapping). Class evaluates each performance using a simple rubric.'
        },
        esp: {
            review:       'Sitwasyon review: Magbigay ng maikling sitwasyon mula sa nakaraang aralin. Tanungin ang mga mag-aaral kung paano nila haharapin ang sitwasyon. Itanong: "Ano ang tamang pagpapahalaga ang ipinakita sa nakaraang aralin?"',
            motivation:   'Magkwento ng isang maikling kwento tungkol sa isang batang nagpakita ng mabuting pagpapahalaga. Tanungin: "Ano ang ginawa ng bata sa kwento? Paano kayo makakaramdam kung kayo ang nasa sitwasyong iyon?" Hayaan ang mga mag-aaral na ibahagi ang kanilang mga nararamdaman.',
            presentation: 'Ipaliwanag ang pagpapahalaga na tatalakayin:\n1. Kahulugan ng pagpapahalaga.\n2. Mga halimbawa ng pagpapakita ng pagpapahalaga sa paaralan, tahanan, at komunidad.\n3. Mga benepisyo ng pagpapakita ng mabuting pagpapahalaga.\nGamitin ang mga larawan at kwento bilang halimbawa.',
            discussion:   'Magtanong ng mga gabay na tanong:\n1. "Bakit mahalaga ang pagpapakita ng mabuting pagpapahalaga?"\n2. "Paano ninyo ipinakita ang pagpapahalaga sa inyong pamilya?"\n3. "Ano ang mangyayari kung hindi tayo nagpapakita ng mabuting pagpapahalaga?"\n4. "Paano tayo magiging mas mabuting miyembro ng komunidad?"',
            generalization: 'Tanungin: "Ano ang natutuhan natin ngayon?" Gabayan ang mga mag-aaral na sabihin:\nAng pagpapakita ng mabuting pagpapahalaga ay nagpapalakas ng ating ugnayan sa iba. Ito ay nagpapakita ng ating pagmamahal at respeto sa ating kapwa.',
            application:  'Indibidwal na Gawain: Ang bawat mag-aaral ay susulat ng isang pangako kung paano nila ipapakita ang pagpapahalaga sa kanilang pamilya, kaklase, at komunidad. Ibabahagi nila ang kanilang pangako sa klase.'
        },
        generic: {
            review:       'Review previous lesson:\n- Ask 3–5 questions about the previous topic.\n- Use flashcards, oral recitation, or a short quiz.\n- Connect the review to today\'s new lesson.\nAsk: "What did we learn last time? How does it connect to what we will learn today?"',
            motivation:   'Motivate students by:\n- Showing a picture, video clip, or real object related to the topic.\n- Asking a thought-provoking question or presenting a problem.\n- Sharing a short story or scenario.\nAsk: "What do you already know about [topic]? What do you want to find out?"',
            presentation: 'Present the new lesson:\n1. State the learning objectives clearly.\n2. Introduce key concepts using visual aids, demonstrations, or examples.\n3. Explain step by step, checking for understanding at each stage.\n4. Use the board to write key terms, definitions, and examples.\n5. Ask comprehension check questions throughout.',
            discussion:   'Guide class discussion:\n1. Ask higher-order thinking questions about the lesson.\n2. Call on different students to share their answers.\n3. Encourage students to explain their reasoning.\n4. Correct misconceptions gently and clearly.\n5. Connect the lesson to real-life situations students can relate to.',
            generalization: 'Lead students to form the generalization:\n- Ask: "What did we learn today? What is the main idea?"\n- Have students state the concept/rule/principle in their own words.\n- Write the generalization on the board.\n- Have the class read it together.',
            application:  'Apply the learning:\n- Individual or group activity that requires students to use the new knowledge.\n- Could be a worksheet, problem set, creative task, or performance activity.\n- Circulate and provide feedback.\n- Have selected students share their work with the class.'
        }
    };

    // ── DOM references ────────────────────────────────────────────────────────
    var modal       = document.getElementById('procedure-template-modal');
    var backdrop    = document.getElementById('modal-backdrop');
    var openBtn     = document.getElementById('load-procedure-template-btn');
    var closeBtn    = document.getElementById('close-modal-btn');

    var fields = {
        review:         document.getElementById('proc_review_drill'),
        motivation:     document.getElementById('proc_motivation'),
        presentation:   document.getElementById('proc_presentation'),
        discussion:     document.getElementById('proc_discussion'),
        generalization: document.getElementById('proc_generalization'),
        application:    document.getElementById('proc_application')
    };

    // ── Open / close ──────────────────────────────────────────────────────────
    function openModal() {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden';
    }

    function closeModal() {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.style.overflow = '';
    }

    openBtn.addEventListener('click', openModal);
    closeBtn.addEventListener('click', closeModal);
    backdrop.addEventListener('click', closeModal);
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') closeModal();
    });

    // ── Apply template ────────────────────────────────────────────────────────
    function applyTemplate(key) {
        var tpl = templates[key];
        if (!tpl) return;

        // Check if any field already has content
        var hasContent = Object.values(fields).some(function (f) {
            return f && f.value.trim() !== '';
        });

        if (hasContent && !confirm('This will replace existing procedure content. Continue?')) {
            return;
        }

        Object.keys(fields).forEach(function (k) {
            if (fields[k] && tpl[k] !== undefined) {
                fields[k].value = tpl[k];
            }
        });

        closeModal();

        // Scroll to procedure section
        var procSection = document.getElementById('proc_review_drill');
        if (procSection) {
            procSection.closest('section').scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    }

    // Attach click handlers to all template buttons
    document.querySelectorAll('[data-template]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            applyTemplate(btn.getAttribute('data-template'));
        });
    });

})();
</script>

<!-- ── Evaluation & Assignment Template Modal ────────────────────────────── -->
<div id="eval-template-modal" class="fixed inset-0 z-50 hidden items-center justify-center p-4" role="dialog" aria-modal="true" aria-labelledby="eval-modal-title">
    <div id="eval-modal-backdrop" class="absolute inset-0 bg-black/50"></div>
    <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-xl max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200 sticky top-0 bg-white rounded-t-2xl">
            <div>
                <h3 id="eval-modal-title" class="text-base font-semibold text-gray-800">Load Evaluation &amp; Assignment Template</h3>
                <p class="text-xs text-gray-500 mt-0.5">Fills both Evaluation and Assignment fields. Edit after loading.</p>
            </div>
            <button type="button" id="close-eval-modal-btn" class="inline-flex items-center justify-center w-8 h-8 rounded-md text-gray-400 hover:bg-gray-100 hover:text-gray-600 transition-colors focus:outline-none focus:ring-2 focus:ring-gray-400">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <div class="p-6 space-y-3">
            <?php
            $evalTemplates = [
                ['key'=>'math-eval',    'color'=>'blue',   'label'=>'M',  'title'=>'Mathematics',         'eval'=>'Written test: Shade the correct fraction in 5 diagrams (2 pts each = 10 pts). Identify the numerator and denominator in 5 fractions (1 pt each = 5 pts). Total: 15 points. Mastery: 12/15.', 'assign'=>'Study pp. 68–72 of your Math 3 Learner\'s Material. Draw 3 objects divided into equal parts and write the fraction for the shaded portion. Be ready to share tomorrow.'],
                ['key'=>'english-eval', 'color'=>'green',  'label'=>'E',  'title'=>'English',             'eval'=>'Reading comprehension test: Answer 10 questions about the story (1 pt each = 10 pts). Write a 3-sentence summary (5 pts). Total: 15 points. Mastery: 12/15.', 'assign'=>'Read the story again at home. Write 3 sentences about what you would do if you were the main character. Bring your written work tomorrow.'],
                ['key'=>'filipino-eval','color'=>'yellow', 'label'=>'F',  'title'=>'Filipino',            'eval'=>'Sagutan ang 10 tanong tungkol sa kwento (1 puntos bawat isa = 10 puntos). Sumulat ng 3-pangungusap na buod (5 puntos). Kabuuan: 15 puntos. Mastery: 12/15.', 'assign'=>'Basahin muli ang kwento sa bahay. Gumawa ng maikling buod (3 pangungusap). Dalhin bukas.'],
                ['key'=>'science-eval', 'color'=>'teal',   'label'=>'S',  'title'=>'Science',             'eval'=>'Written test: Classify 10 pictures as living or non-living (1 pt each = 10 pts). Give 2 characteristics of living things (2 pts each = 4 pts). Total: 14 points. Mastery: 11/14.', 'assign'=>'Look around your home. List 5 living things and 5 non-living things you see. Draw and label each one. Bring your work tomorrow.'],
                ['key'=>'ap-eval',      'color'=>'orange', 'label'=>'AP', 'title'=>'Araling Panlipunan',  'eval'=>'Pasalitang pagtatasa: Tukuyin ang uri ng komunidad sa 5 larawan (2 puntos bawat isa = 10 puntos). Isulat ang 3 katangian ng inyong komunidad (5 puntos). Kabuuan: 15 puntos. Mastery: 12/15.', 'assign'=>'Gumawa ng simpleng mapa ng inyong komunidad. Markahan ang 5 mahahalagang lugar. Dalhin bukas.'],
                ['key'=>'mapeh-eval',   'color'=>'pink',   'label'=>'MP', 'title'=>'MAPEH',               'eval'=>'Performance assessment: Sing the song with correct pitch and rhythm (10 pts). Identify the melody, rhythm, and dynamics of the song (5 pts). Total: 15 points. Mastery: 12/15.', 'assign'=>'Practice singing the song at home. Identify one other Filipino folk song and be ready to share its title and what it is about.'],
                ['key'=>'esp-eval',     'color'=>'indigo', 'label'=>'EP', 'title'=>'EsP',                 'eval'=>'Pasalitang pagtatasa: Ibahagi ang isang sitwasyon kung saan ipinakita mo ang pagpapahalaga (5 puntos). Nakasulat: Sagutin ang 5 tanong (2 puntos bawat isa = 10 puntos). Kabuuan: 15 puntos. Mastery: 12/15.', 'assign'=>'Sumulat ng isang pangako kung paano mo ipapakita ang pagpapahalaga sa iyong pamilya, kaklase, at komunidad. Dalhin bukas.'],
                ['key'=>'generic-eval', 'color'=>'gray',   'label'=>'G',  'title'=>'Generic (Any Subject)','eval'=>'Written/oral assessment: [Describe the assessment method, number of items, point value per item, total points, and mastery level]. Example: 10-item quiz, 1 pt each = 10 pts. Mastery: 8/10.', 'assign'=>'Assignment: [Describe the homework or agreement. Include what students should do, materials needed, and when it is due.]'],
            ];
            foreach ($evalTemplates as $t):
            ?>
            <div class="border border-gray-200 rounded-xl overflow-hidden">
                <button type="button"
                    data-eval="<?= htmlspecialchars($t['eval'], ENT_QUOTES) ?>"
                    data-assign="<?= htmlspecialchars($t['assign'], ENT_QUOTES) ?>"
                    class="eval-tpl-btn w-full flex items-center justify-between px-4 py-3 bg-<?= $t['color'] ?>-50 hover:bg-<?= $t['color'] ?>-100 transition-colors text-left">
                    <div class="flex items-center gap-3">
                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-<?= $t['color'] ?>-600 text-white text-xs font-bold"><?= $t['label'] ?></span>
                        <p class="text-sm font-semibold text-gray-800"><?= $t['title'] ?></p>
                    </div>
                    <svg class="w-4 h-4 text-<?= $t['color'] ?>-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </button>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<!-- ── Save as Template Modal ────────────────────────────────────────────── -->
<div id="save-template-modal" class="fixed inset-0 z-50 hidden items-center justify-center p-4" role="dialog" aria-modal="true" aria-labelledby="save-tpl-title">
    <div id="save-tpl-backdrop" class="absolute inset-0 bg-black/50"></div>
    <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md">
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
            <h3 id="save-tpl-title" class="text-base font-semibold text-gray-800">Save as Template</h3>
            <button type="button" id="close-save-tpl-btn" class="inline-flex items-center justify-center w-8 h-8 rounded-md text-gray-400 hover:bg-gray-100 hover:text-gray-600 transition-colors focus:outline-none focus:ring-2 focus:ring-gray-400">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <div class="px-6 py-5">
            <p class="text-sm text-gray-500 mb-4">Give this template a name so you can reuse it for future lesson plans.</p>
            <label for="template-name-input" class="block text-sm font-medium text-gray-700 mb-1">Template Name <span class="text-red-500">*</span></label>
            <input type="text" id="template-name-input" autocomplete="off"
                class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-900 shadow-sm focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition"
                placeholder="e.g. Grade 3 Math — Fractions">
            <p id="save-tpl-error" class="mt-1 text-sm text-red-600 hidden">Template name is required.</p>
        </div>
        <div class="px-6 pb-5 flex gap-3 justify-end">
            <button type="button" id="cancel-save-tpl-btn" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 text-sm font-medium rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-gray-400">Cancel</button>
            <button type="button" id="confirm-save-tpl-btn" class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-purple-500">
                Save Template
            </button>
        </div>
    </div>
</div>

<!-- Toast notification -->
<div id="tpl-toast" class="fixed bottom-6 right-6 z-50 hidden">
    <div class="flex items-center gap-3 bg-green-600 text-white px-5 py-3 rounded-xl shadow-lg text-sm font-medium">
        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        <span id="tpl-toast-msg">Template saved!</span>
    </div>
</div>

<!-- ── Eval/Assignment + Save as Template + Auto-load JS ─────────────────── -->
<script>
(function () {
    'use strict';

    // ── Evaluation & Assignment template modal ────────────────────────────────
    var evalModal    = document.getElementById('eval-template-modal');
    var evalBackdrop = document.getElementById('eval-modal-backdrop');
    var evalOpenBtn  = document.getElementById('load-eval-template-btn');
    var evalCloseBtn = document.getElementById('close-eval-modal-btn');

    function openEvalModal()  { evalModal.classList.remove('hidden'); evalModal.classList.add('flex'); document.body.style.overflow = 'hidden'; }
    function closeEvalModal() { evalModal.classList.add('hidden'); evalModal.classList.remove('flex'); document.body.style.overflow = ''; }

    if (evalOpenBtn)  evalOpenBtn.addEventListener('click', openEvalModal);
    if (evalCloseBtn) evalCloseBtn.addEventListener('click', closeEvalModal);
    if (evalBackdrop) evalBackdrop.addEventListener('click', closeEvalModal);

    document.querySelectorAll('.eval-tpl-btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var evalEl   = document.getElementById('evaluation');
            var assignEl = document.getElementById('assignment');
            var hasContent = (evalEl && evalEl.value.trim() !== '') || (assignEl && assignEl.value.trim() !== '');
            if (hasContent && !confirm('This will replace existing evaluation and assignment content. Continue?')) return;
            if (evalEl)   evalEl.value   = btn.getAttribute('data-eval');
            if (assignEl) assignEl.value = btn.getAttribute('data-assign');
            closeEvalModal();
        });
    });

    // ── Save as Template modal ────────────────────────────────────────────────
    var saveTplModal   = document.getElementById('save-template-modal');
    var saveTplBackdrop= document.getElementById('save-tpl-backdrop');
    var saveBtn        = document.getElementById('save-as-template-btn');
    var closeSaveBtn   = document.getElementById('close-save-tpl-btn');
    var cancelSaveBtn  = document.getElementById('cancel-save-tpl-btn');
    var confirmSaveBtn = document.getElementById('confirm-save-tpl-btn');
    var nameInput      = document.getElementById('template-name-input');
    var saveError      = document.getElementById('save-tpl-error');
    var toast          = document.getElementById('tpl-toast');
    var toastMsg       = document.getElementById('tpl-toast-msg');

    function openSaveModal()  { saveTplModal.classList.remove('hidden'); saveTplModal.classList.add('flex'); document.body.style.overflow = 'hidden'; if (nameInput) nameInput.focus(); }
    function closeSaveModal() { saveTplModal.classList.add('hidden'); saveTplModal.classList.remove('flex'); document.body.style.overflow = ''; if (saveError) saveError.classList.add('hidden'); }

    if (saveBtn)       saveBtn.addEventListener('click', openSaveModal);
    if (closeSaveBtn)  closeSaveBtn.addEventListener('click', closeSaveModal);
    if (cancelSaveBtn) cancelSaveBtn.addEventListener('click', closeSaveModal);
    if (saveTplBackdrop) saveTplBackdrop.addEventListener('click', closeSaveModal);

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') { closeEvalModal(); closeSaveModal(); }
    });

    function showToast(msg) {
        if (!toast || !toastMsg) return;
        toastMsg.textContent = msg;
        toast.classList.remove('hidden');
        setTimeout(function () { toast.classList.add('hidden'); }, 3500);
    }

    if (confirmSaveBtn) {
        confirmSaveBtn.addEventListener('click', function () {
            var name = nameInput ? nameInput.value.trim() : '';
            if (!name) { if (saveError) saveError.classList.remove('hidden'); return; }
            if (saveError) saveError.classList.add('hidden');

            // Collect form data
            var form = document.querySelector('form[method="POST"]');
            if (!form) return;
            var formData = new FormData(form);
            formData.set('template_name', name);

            confirmSaveBtn.disabled = true;
            confirmSaveBtn.textContent = 'Saving…';

            fetch('<?= url('/templates/save-from-plan') ?>', {
                method: 'POST',
                body: formData
            })
            .then(function (r) { return r.json(); })
            .then(function (data) {
                confirmSaveBtn.disabled = false;
                confirmSaveBtn.textContent = 'Save Template';
                if (data.success) {
                    closeSaveModal();
                    if (nameInput) nameInput.value = '';
                    showToast('Template "' + data.name + '" saved!');
                } else {
                    alert('Error: ' + (data.error || 'Could not save template.'));
                }
            })
            .catch(function () {
                confirmSaveBtn.disabled = false;
                confirmSaveBtn.textContent = 'Save Template';
                alert('Network error. Please try again.');
            });
        });
    }

    // ── Auto-load template from URL ?template=ID ─────────────────────────────
    (function autoLoadTemplate() {
        var params = new URLSearchParams(window.location.search);
        var tplId  = params.get('template');
        if (!tplId) return;

        fetch('<?= url('/templates/') ?>' + encodeURIComponent(tplId) + '/apply')
            .then(function (r) { return r.json(); })
            .then(function (data) {
                if (!data.success || !data.template) return;
                var t = data.template;

                // Helper to set field value
                function setField(id, val) {
                    var el = document.getElementById(id);
                    if (el && val) el.value = val;
                }
                function setSelect(id, val) {
                    var el = document.getElementById(id);
                    if (el && val) {
                        for (var i = 0; i < el.options.length; i++) {
                            if (el.options[i].value === String(val)) { el.selectedIndex = i; break; }
                        }
                    }
                }

                setSelect('subject',              t.subject_tpl);
                setSelect('grade_level',          t.grade_level_tpl);
                setSelect('time_allotment_minutes', t.time_allotment_tpl);
                setField('learning_competency',   t.learning_competency_tpl);
                setField('subject_matter_topic',  t.subject_matter_topic_tpl);
                setField('subject_matter_references', t.subject_matter_references_tpl);
                setField('subject_matter_materials',  t.subject_matter_materials_tpl);
                setField('proc_review_drill',     t.proc_review_drill_tpl);
                setField('proc_motivation',       t.proc_motivation_tpl);
                setField('proc_presentation',     t.proc_presentation_tpl);
                setField('proc_discussion',       t.proc_discussion_tpl);
                setField('proc_generalization',   t.proc_generalization_tpl);
                setField('proc_application',      t.proc_application_tpl);
                setField('evaluation',            t.evaluation_tpl);
                setField('assignment',            t.assignment_tpl);

                // Load objectives
                if (t.objectives_tpl) {
                    try {
                        var objs = JSON.parse(t.objectives_tpl);
                        if (Array.isArray(objs) && objs.length > 0) {
                            var container = document.getElementById('objectives-container');
                            var hint      = document.getElementById('objectives-empty-hint');
                            if (container) {
                                container.querySelectorAll('.objective-row').forEach(function (r) { r.remove(); });
                                if (hint) hint.style.display = 'none';
                                objs.forEach(function (text, idx) {
                                    var num = idx + 1;
                                    var row = document.createElement('div');
                                    row.className = 'objective-row flex items-center gap-3';
                                    row.innerHTML =
                                        '<span class="objective-number-badge flex-shrink-0 inline-flex items-center justify-center w-7 h-7 rounded-full bg-blue-100 text-blue-700 text-xs font-bold">' + num + '</span>'
                                        + '<input type="hidden" class="objective-sort-input" name="objectives[' + idx + '][sort_order]" value="' + num + '">'
                                        + '<input type="text" name="objectives[' + idx + '][objective_text]" class="flex-1 rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-900 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition" aria-label="Objective ' + num + '">'
                                        + '<button type="button" class="remove-objective-btn flex-shrink-0 inline-flex items-center justify-center w-7 h-7 rounded-md text-red-500 hover:bg-red-50 hover:text-red-700 transition-colors focus:outline-none focus:ring-2 focus:ring-red-400" aria-label="Remove"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>';
                                    container.appendChild(row);
                                    var input = row.querySelector('input[type="text"]');
                                    if (input) input.value = text;
                                });
                            }
                        }
                    } catch (e) {}
                }

                showToast('Template "' + (t.name || '') + '" loaded!');
            })
            .catch(function () {});
    })();

})();
</script>

<!-- Word count + Completeness JS for Lesson Plan form -->
<script>
(function () {
    'use strict';

    // ── Dynamic objectives list ───────────────────────────────────────────────
    var container  = document.getElementById('objectives-container');
    var addBtn     = document.getElementById('add-objective-btn');
    var emptyHint  = document.getElementById('objectives-empty-hint');

    function objectiveCount() { return container.querySelectorAll('.objective-row').length; }

    function reindex() {
        container.querySelectorAll('.objective-row').forEach(function (row, idx) {
            var num       = idx + 1;
            var badge     = row.querySelector('.objective-number-badge');
            var sortInput = row.querySelector('.objective-sort-input');
            var textInput = row.querySelector('input[type="text"]');
            var removeBtn = row.querySelector('.remove-objective-btn');
            if (badge)     badge.textContent = num;
            if (sortInput) { sortInput.name = 'objectives[' + idx + '][sort_order]'; sortInput.value = num; }
            if (textInput) { textInput.name = 'objectives[' + idx + '][objective_text]'; textInput.placeholder = 'Describe objective ' + num + '\u2026'; textInput.setAttribute('aria-label', 'Objective ' + num); }
            if (removeBtn) removeBtn.setAttribute('aria-label', 'Remove objective ' + num);
        });
    }

    function toggleEmptyHint() { if (emptyHint) emptyHint.style.display = objectiveCount() === 0 ? '' : 'none'; }

    function addObjective() {
        if (emptyHint) emptyHint.style.display = 'none';
        var idx = objectiveCount(); var num = idx + 1;
        var row = document.createElement('div');
        row.className = 'objective-row flex items-center gap-3';
        row.innerHTML =
            '<span class="objective-number-badge flex-shrink-0 inline-flex items-center justify-center w-7 h-7 rounded-full bg-blue-100 text-blue-700 text-xs font-bold">' + num + '</span>'
            + '<input type="hidden" class="objective-sort-input" name="objectives[' + idx + '][sort_order]" value="' + num + '">'
            + '<input type="text" name="objectives[' + idx + '][objective_text]" class="flex-1 rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-900 placeholder-gray-400 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition" placeholder="Describe objective ' + num + '\u2026" aria-label="Objective ' + num + '">'
            + '<button type="button" class="remove-objective-btn flex-shrink-0 inline-flex items-center justify-center w-7 h-7 rounded-md text-red-500 hover:bg-red-50 hover:text-red-700 transition-colors focus:outline-none focus:ring-2 focus:ring-red-400" aria-label="Remove objective ' + num + '"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>';
        container.appendChild(row);
        var input = row.querySelector('input[type="text"]');
        if (input) input.focus();
    }

    function removeObjective(btn) {
        var row = btn.closest('.objective-row');
        if (row) { row.remove(); reindex(); toggleEmptyHint(); }
    }

    addBtn.addEventListener('click', addObjective);
    container.addEventListener('click', function (e) {
        var btn = e.target.closest('.remove-objective-btn');
        if (btn) removeObjective(btn);
    });
    toggleEmptyHint();

    // ── Word count on all textareas ───────────────────────────────────────────
    document.querySelectorAll('textarea').forEach(function (ta) {
        var hint = document.createElement('p');
        hint.className = 'mt-1 text-xs text-gray-400 text-right word-count-hint';
        hint.textContent = '0 words';
        ta.parentNode.insertBefore(hint, ta.nextSibling);
        function updateCount() {
            var words = ta.value.trim() === '' ? 0 : ta.value.trim().split(/\s+/).length;
            hint.textContent = words + (words === 1 ? ' word' : ' words');
        }
        ta.addEventListener('input', updateCount);
        updateCount();
    });

    // Completeness indicator
    var fields = [
        { id: 'title',                label: 'Title',               required: true  },
        { id: 'subject',              label: 'Subject',             required: false },
        { id: 'grade_level',          label: 'Grade Level',         required: false },
        { id: 'learning_competency',  label: 'Competency',          required: true  },
        { id: 'subject_matter_topic', label: 'Topic',               required: false },
        { id: 'proc_review_drill',    label: 'Review/Drill',        required: false },
        { id: 'proc_motivation',      label: 'Motivation',          required: false },
        { id: 'proc_presentation',    label: 'Presentation',        required: false },
        { id: 'proc_discussion',      label: 'Discussion',          required: false },
        { id: 'proc_generalization',  label: 'Generalization',      required: false },
        { id: 'proc_application',     label: 'Application',         required: false },
        { id: 'evaluation',           label: 'Evaluation',          required: false },
    ];

    var bar       = document.getElementById('lp-completeness-bar');
    var pct       = document.getElementById('lp-completeness-pct');
    var checklist = document.getElementById('lp-completeness-checklist');

    function update() {
        if (!bar || !pct || !checklist) return;
        var filled = 0;
        var html   = '';
        fields.forEach(function (f) {
            var el   = document.getElementById(f.id);
            var done = el && el.value.trim() !== '';
            if (done) filled++;
            var color = done ? 'text-green-600' : (f.required ? 'text-red-500' : 'text-gray-400');
            var icon  = done ? '✓' : (f.required ? '✗' : '○');
            html += '<span class="' + color + '">' + icon + ' ' + f.label + '</span>';
        });
        var percent = Math.round((filled / fields.length) * 100);
        bar.style.width = percent + '%';
        bar.className   = 'h-2.5 rounded-full transition-all duration-300 ' + (percent === 100 ? 'bg-green-500' : percent >= 60 ? 'bg-blue-600' : 'bg-yellow-500');
        pct.textContent = percent + '%';
        pct.className   = 'text-sm font-semibold ' + (percent === 100 ? 'text-green-600' : 'text-blue-600');
        checklist.innerHTML = html;
    }

    fields.forEach(function (f) {
        var el = document.getElementById(f.id);
        if (el) { el.addEventListener('input', update); el.addEventListener('change', update); }
    });

    update();
})();
</script>

<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/main.php';
