<?php
declare(strict_types=1);

/**
 * Demo Maker — printable export view (DepEd-formatted)
 *
 * Variables provided by DemoController::export():
 *   $demo    (array)      – all demo fields
 *   $steps   (array)      – step rows ordered by step_number
 *   $student (array|null) – student profile (full_name, school_name, section,
 *                           year_level, cooperating_teacher)
 */

/**
 * Helper: render a section only when the value is non-empty.
 */
function renderSection(string $label, string $value): void
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
    <title><?= htmlspecialchars($demo['title']) ?> — Teaching Demo Export</title>
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
            <a href="<?= url('/demos/' . (int) $demo['id'] . '/edit') ?>"
               class="inline-flex items-center gap-1.5 text-sm font-medium text-blue-600 hover:text-blue-800 transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 rounded">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Back to Demo
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

                <!-- School / Republic header -->
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
                        Teaching Demonstration Plan
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
                        <dd class="text-gray-800"><?= htmlspecialchars($demo['subject'] ?? '') ?></dd>
                    </div>

                    <div class="flex gap-2">
                        <dt class="font-semibold text-gray-500 whitespace-nowrap">Grade &amp; Section:</dt>
                        <dd class="text-gray-800">
                            <?= htmlspecialchars($demo['grade_level'] ?? '') ?>
                            <?php if (!empty($student['section'])): ?>
                                &ndash; <?= htmlspecialchars($student['section']) ?>
                            <?php endif; ?>
                        </dd>
                    </div>

                    <div class="flex gap-2">
                        <dt class="font-semibold text-gray-500 whitespace-nowrap">Quarter:</dt>
                        <dd class="text-gray-800">
                            <?= !empty($demo['quarter'])
                                ? htmlspecialchars($quarterLabels[(int)$demo['quarter']] ?? 'Q' . $demo['quarter'])
                                : '—' ?>
                        </dd>
                    </div>

                    <div class="flex gap-2">
                        <dt class="font-semibold text-gray-500 whitespace-nowrap">Week:</dt>
                        <dd class="text-gray-800">
                            <?= !empty($demo['week']) ? 'Week ' . (int) $demo['week'] : '—' ?>
                        </dd>
                    </div>

                    <div class="flex gap-2">
                        <dt class="font-semibold text-gray-500 whitespace-nowrap">Duration:</dt>
                        <dd class="text-gray-800">
                            <?= !empty($demo['duration_minutes'])
                                ? htmlspecialchars((string) $demo['duration_minutes']) . ' minutes'
                                : '—' ?>
                        </dd>
                    </div>

                    <div class="flex gap-2 col-span-2">
                        <dt class="font-semibold text-gray-500 whitespace-nowrap">Date:</dt>
                        <dd class="text-gray-800">
                            <?= !empty($demo['updated_at'])
                                ? htmlspecialchars(date('F j, Y', strtotime($demo['updated_at'])))
                                : '___________________' ?>
                        </dd>
                    </div>

                </dl>

                <!-- Title -->
                <h1 class="text-2xl font-bold text-gray-900 leading-tight mt-6 text-center">
                    <?= htmlspecialchars($demo['title']) ?>
                </h1>

            </header>

            <!-- Body sections -->
            <?php renderSection('Learning Objectives', $demo['learning_objectives'] ?? ''); ?>
            <?php renderSection('Materials Needed',    $demo['materials_needed']    ?? ''); ?>
            <?php renderSection('Introduction / Motivation', $demo['introduction']  ?? ''); ?>

            <!-- Lesson Proper -->
            <?php if (!empty($steps)): ?>
                <div class="mb-6">
                    <h2 class="text-sm font-semibold uppercase tracking-widest text-gray-500 border-b border-gray-200 pb-1 mb-3">
                        Lesson Proper
                    </h2>
                    <ol class="space-y-4">
                        <?php foreach ($steps as $step): ?>
                            <li class="flex gap-4">
                                <span class="flex-shrink-0 w-7 h-7 rounded-full bg-blue-100 text-blue-700 text-xs font-bold flex items-center justify-center print:bg-gray-200 print:text-gray-800">
                                    <?= (int) $step['step_number'] ?>
                                </span>
                                <p class="text-gray-800 whitespace-pre-wrap leading-relaxed pt-0.5">
                                    <?= htmlspecialchars($step['description']) ?>
                                </p>
                            </li>
                        <?php endforeach; ?>
                    </ol>
                </div>
            <?php endif; ?>

            <?php renderSection('Generalization',          $demo['generalization'] ?? ''); ?>
            <?php renderSection('Application / Practice',  $demo['application']    ?? ''); ?>
            <?php renderSection('Assessment / Evaluation', $demo['assessment']     ?? ''); ?>

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
