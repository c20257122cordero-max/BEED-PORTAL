<?php
declare(strict_types=1);

/**
 * Lesson Plan Planner — printable export view (DepEd-formatted)
 *
 * Variables provided by LessonPlanController::export():
 *   $lessonPlan (array)      – all lesson plan fields
 *   $objectives (array)      – objective rows ordered by sort_order
 *   $student    (array|null) – student profile (full_name, school_name,
 *                              section, year_level, cooperating_teacher)
 */

/**
 * Helper: render a labelled section only when the value is non-empty.
 */
function renderLpSection(string $label, string $value): void
{
    if (trim($value) === '') {
        return;
    }
    echo '<div class="mb-6">';
    echo '<h2 class="text-sm font-semibold uppercase tracking-widest text-gray-500 border-b border-gray-200 pb-1 mb-2">'
        . htmlspecialchars($label) . '</h2>';
    echo '<p class="text-gray-800 whitespace-pre-wrap leading-relaxed">'
        . htmlspecialchars($value) . '</p>';
    echo '</div>';
}

$quarterLabels = [1 => '1st Quarter', 2 => '2nd Quarter', 3 => '3rd Quarter', 4 => '4th Quarter'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($lessonPlan['title']) ?> — Lesson Plan Export</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            .print\:hidden { display: none !important; }
            body { font-size: 12pt; }
            @page { size: A4; margin: 15mm 20mm; }
        }
    </style>
</head>
<body class="bg-gray-50 text-gray-900 antialiased">

    <!-- Top navigation bar (hidden on print) -->
    <nav class="print:hidden bg-white border-b border-gray-200 shadow-sm sticky top-0 z-10">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 py-3 flex items-center justify-between gap-4">
            <a href="<?= url('/lesson-plans/' . (int) $lessonPlan['id'] . '/edit') ?>"
               class="inline-flex items-center gap-1.5 text-sm font-medium text-blue-600 hover:text-blue-800 transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 rounded">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Back to Lesson Plan
            </a>
            <button
                onclick="window.print()"
                class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg shadow-sm transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
                Print
            </button>
        </div>
    </nav>

    <!-- Document -->
    <main class="max-w-4xl mx-auto px-4 sm:px-6 py-10 print:py-0 print:px-0 print:max-w-none">

        <article class="bg-white rounded-2xl shadow-sm border border-gray-200 px-8 py-10 print:shadow-none print:border-0 print:rounded-none print:px-0">

            <!-- DepEd formal header -->
            <header class="mb-8 pb-6 border-b-2 border-gray-800">

                <div class="text-center mb-4">
                    <p class="text-xs font-semibold uppercase tracking-widest text-gray-500">
                        Republic of the Philippines &bull; Department of Education
                    </p>
                    <?php if (!empty($student['school_name'])): ?>
                        <p class="text-base font-bold text-gray-800 mt-1">
                            <?= htmlspecialchars($student['school_name']) ?>
                        </p>
                    <?php endif; ?>
                    <p class="text-sm font-semibold uppercase tracking-wide text-gray-600 mt-2">
                        Detailed Lesson Plan (DLP)
                    </p>
                </div>

                <!-- Meta grid -->
                <dl class="grid grid-cols-2 gap-x-8 gap-y-2 text-sm mt-4">

                    <div class="flex gap-2">
                        <dt class="font-semibold text-gray-500 whitespace-nowrap">Teacher:</dt>
                        <dd class="text-gray-800"><?= htmlspecialchars($student['full_name'] ?? '') ?></dd>
                    </div>

                    <div class="flex gap-2">
                        <dt class="font-semibold text-gray-500 whitespace-nowrap">Subject:</dt>
                        <dd class="text-gray-800"><?= htmlspecialchars($lessonPlan['subject'] ?? '') ?></dd>
                    </div>

                    <div class="flex gap-2">
                        <dt class="font-semibold text-gray-500 whitespace-nowrap">Grade &amp; Section:</dt>
                        <dd class="text-gray-800">
                            <?= htmlspecialchars($lessonPlan['grade_level'] ?? '') ?>
                            <?php if (!empty($student['section'])): ?>
                                &ndash; <?= htmlspecialchars($student['section']) ?>
                            <?php endif; ?>
                        </dd>
                    </div>

                    <div class="flex gap-2">
                        <dt class="font-semibold text-gray-500 whitespace-nowrap">Quarter:</dt>
                        <dd class="text-gray-800">
                            <?= !empty($lessonPlan['quarter'])
                                ? htmlspecialchars($quarterLabels[(int)$lessonPlan['quarter']] ?? 'Q' . $lessonPlan['quarter'])
                                : '—' ?>
                        </dd>
                    </div>

                    <div class="flex gap-2">
                        <dt class="font-semibold text-gray-500 whitespace-nowrap">Week:</dt>
                        <dd class="text-gray-800">
                            <?= !empty($lessonPlan['week']) ? 'Week ' . (int) $lessonPlan['week'] : '—' ?>
                        </dd>
                    </div>

                    <div class="flex gap-2">
                        <dt class="font-semibold text-gray-500 whitespace-nowrap">Date:</dt>
                        <dd class="text-gray-800">
                            <?= !empty($lessonPlan['date'])
                                ? htmlspecialchars(date('F j, Y', strtotime($lessonPlan['date'])))
                                : '___________________' ?>
                        </dd>
                    </div>

                    <div class="flex gap-2">
                        <dt class="font-semibold text-gray-500 whitespace-nowrap">Time Allotment:</dt>
                        <dd class="text-gray-800">
                            <?= !empty($lessonPlan['time_allotment_minutes'])
                                ? htmlspecialchars((string) $lessonPlan['time_allotment_minutes']) . ' minutes'
                                : '—' ?>
                        </dd>
                    </div>

                </dl>

                <!-- Title -->
                <h1 class="text-2xl font-bold text-gray-900 leading-tight mt-6 text-center">
                    <?= htmlspecialchars($lessonPlan['title']) ?>
                </h1>

            </header>

            <!-- Body sections -->

            <!-- Learning Competency -->
            <?php renderLpSection('Learning Competency', $lessonPlan['learning_competency'] ?? ''); ?>

            <!-- Learning Objectives -->
            <?php if (!empty($objectives)): ?>
                <div class="mb-6">
                    <h2 class="text-sm font-semibold uppercase tracking-widest text-gray-500 border-b border-gray-200 pb-1 mb-3">
                        Learning Objectives
                    </h2>
                    <ol class="space-y-2 list-decimal list-inside">
                        <?php foreach ($objectives as $objective): ?>
                            <li class="text-gray-800 leading-relaxed">
                                <?= htmlspecialchars($objective['objective_text']) ?>
                            </li>
                        <?php endforeach; ?>
                    </ol>
                </div>
            <?php endif; ?>

            <!-- Subject Matter -->
            <?php if (
                !empty($lessonPlan['subject_matter_topic'])
                || !empty($lessonPlan['subject_matter_references'])
                || !empty($lessonPlan['subject_matter_materials'])
            ): ?>
                <div class="mb-6">
                    <h2 class="text-sm font-semibold uppercase tracking-widest text-gray-500 border-b border-gray-200 pb-1 mb-3">
                        Subject Matter
                    </h2>
                    <dl class="space-y-3">
                        <?php if (!empty($lessonPlan['subject_matter_topic'])): ?>
                            <div>
                                <dt class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-0.5">Topic</dt>
                                <dd class="text-gray-800 whitespace-pre-wrap leading-relaxed">
                                    <?= htmlspecialchars($lessonPlan['subject_matter_topic']) ?>
                                </dd>
                            </div>
                        <?php endif; ?>
                        <?php if (!empty($lessonPlan['subject_matter_references'])): ?>
                            <div>
                                <dt class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-0.5">References</dt>
                                <dd class="text-gray-800 whitespace-pre-wrap leading-relaxed">
                                    <?= htmlspecialchars($lessonPlan['subject_matter_references']) ?>
                                </dd>
                            </div>
                        <?php endif; ?>
                        <?php if (!empty($lessonPlan['subject_matter_materials'])): ?>
                            <div>
                                <dt class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-0.5">Materials</dt>
                                <dd class="text-gray-800 whitespace-pre-wrap leading-relaxed">
                                    <?= htmlspecialchars($lessonPlan['subject_matter_materials']) ?>
                                </dd>
                            </div>
                        <?php endif; ?>
                    </dl>
                </div>
            <?php endif; ?>

            <!-- Procedure -->
            <?php if (
                !empty($lessonPlan['proc_review_drill'])
                || !empty($lessonPlan['proc_motivation'])
                || !empty($lessonPlan['proc_presentation'])
                || !empty($lessonPlan['proc_discussion'])
                || !empty($lessonPlan['proc_generalization'])
                || !empty($lessonPlan['proc_application'])
            ): ?>
                <div class="mb-6">
                    <h2 class="text-sm font-semibold uppercase tracking-widest text-gray-500 border-b border-gray-200 pb-1 mb-3">
                        Procedure
                    </h2>
                    <div class="space-y-4">
                        <?php if (!empty($lessonPlan['proc_review_drill'])): ?>
                            <div>
                                <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">A. Review / Drill</h3>
                                <p class="text-gray-800 whitespace-pre-wrap leading-relaxed">
                                    <?= htmlspecialchars($lessonPlan['proc_review_drill']) ?>
                                </p>
                            </div>
                        <?php endif; ?>
                        <?php if (!empty($lessonPlan['proc_motivation'])): ?>
                            <div>
                                <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">B. Motivation</h3>
                                <p class="text-gray-800 whitespace-pre-wrap leading-relaxed">
                                    <?= htmlspecialchars($lessonPlan['proc_motivation']) ?>
                                </p>
                            </div>
                        <?php endif; ?>
                        <?php if (!empty($lessonPlan['proc_presentation'])): ?>
                            <div>
                                <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">C. Presentation</h3>
                                <p class="text-gray-800 whitespace-pre-wrap leading-relaxed">
                                    <?= htmlspecialchars($lessonPlan['proc_presentation']) ?>
                                </p>
                            </div>
                        <?php endif; ?>
                        <?php if (!empty($lessonPlan['proc_discussion'])): ?>
                            <div>
                                <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">D. Discussion</h3>
                                <p class="text-gray-800 whitespace-pre-wrap leading-relaxed">
                                    <?= htmlspecialchars($lessonPlan['proc_discussion']) ?>
                                </p>
                            </div>
                        <?php endif; ?>
                        <?php if (!empty($lessonPlan['proc_generalization'])): ?>
                            <div>
                                <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">E. Generalization</h3>
                                <p class="text-gray-800 whitespace-pre-wrap leading-relaxed">
                                    <?= htmlspecialchars($lessonPlan['proc_generalization']) ?>
                                </p>
                            </div>
                        <?php endif; ?>
                        <?php if (!empty($lessonPlan['proc_application'])): ?>
                            <div>
                                <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">F. Application</h3>
                                <p class="text-gray-800 whitespace-pre-wrap leading-relaxed">
                                    <?= htmlspecialchars($lessonPlan['proc_application']) ?>
                                </p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Evaluation -->
            <?php renderLpSection('Evaluation', $lessonPlan['evaluation'] ?? ''); ?>

            <!-- Assignment / Agreement -->
            <?php renderLpSection('Assignment / Agreement', $lessonPlan['assignment'] ?? ''); ?>

            <!-- Signature lines -->
            <div class="mt-12 pt-8 border-t border-gray-200 grid grid-cols-2 gap-12">

                <div>
                    <p class="text-sm text-gray-600 mb-8">Prepared by:</p>
                    <div class="border-b border-gray-800 mb-1"></div>
                    <p class="text-sm font-semibold text-gray-800">
                        <?= htmlspecialchars(strtoupper($student['full_name'] ?? '')) ?>
                    </p>
                    <p class="text-xs text-gray-500">Student Teacher</p>
                </div>

                <div>
                    <p class="text-sm text-gray-600 mb-8">Checked by:</p>
                    <div class="border-b border-gray-800 mb-1"></div>
                    <p class="text-sm font-semibold text-gray-800">
                        <?= htmlspecialchars(strtoupper($student['cooperating_teacher'] ?? '')) ?>
                    </p>
                    <p class="text-xs text-gray-500">Cooperating Teacher</p>
                </div>

            </div>

        </article>

    </main>

</body>
</html>
