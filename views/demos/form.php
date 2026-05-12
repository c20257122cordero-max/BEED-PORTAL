<?php

declare(strict_types=1);

/**
 * Demo Maker — create / edit form view
 *
 * Variables provided by DemoController::create(), store(), edit(), update():
 *   $demo   (array|null) – null for create, populated row for edit
 *   $steps  (array)      – step rows, each with 'step_number' and 'description'
 *   $errors (array)      – validation errors keyed by field name
 *   $old    (array)      – previous POST values for repopulation after failure
 *
 * Field repopulation priority:
 *   1. $old['field']   – validation failure (most recent user input)
 *   2. $demo['field']  – edit mode (stored value)
 *   3. ''              – create mode / no prior input
 *
 * Requirements: 3.1, 3.3, 3.4, 3.7
 */

$isEdit = $demo !== null;
$pageTitle = $isEdit
    ? "Edit Demo — BEED Student Portal"
    : "New Demo — BEED Student Portal";

$formAction = $isEdit ? url("/demos/" . (int) $demo["id"]) : url("/demos");

/**
 * Helper: return the repopulated value for a scalar field.
 *
 * @param string $field  Field name.
 * @return string        HTML-escaped value.
 */
$val = static function (string $field) use ($old, $demo): string {
    if (isset($old[$field])) {
        return htmlspecialchars((string) $old[$field], ENT_QUOTES, "UTF-8");
    }
    if (isset($demo[$field])) {
        return htmlspecialchars((string) $demo[$field], ENT_QUOTES, "UTF-8");
    }
    return "";
};

/**
 * Helper: return an inline error <p> if the field has an error, else ''.
 *
 * @param string $field  Field name.
 * @return string        HTML string.
 */
$err = static function (string $field) use ($errors): string {
    if (!empty($errors[$field])) {
        return '<p class="mt-1 text-sm text-red-600">' .
            htmlspecialchars((string) $errors[$field], ENT_QUOTES, "UTF-8") .
            "</p>";
    }
    return "";
};

/**
 * Helper: return border colour class based on whether the field has an error.
 *
 * @param string $field  Field name.
 * @return string        Tailwind border class.
 */
$borderClass = static function (string $field) use ($errors): string {
    return !empty($errors[$field])
        ? "border-red-400 focus:ring-red-500 focus:border-red-500"
        : "border-gray-300 focus:ring-blue-500 focus:border-blue-500";
};

// Determine the initial step list to render.
// If $old contains steps (validation failure), use those; otherwise use $steps.
$initialSteps = [];
if (!empty($old["steps"]) && is_array($old["steps"])) {
    foreach ($old["steps"] as $s) {
        $initialSteps[] = [
            "step_number" => (int) ($s["step_number"] ?? 0),
            "description" => (string) ($s["description"] ?? ""),
        ];
    }
} elseif (!empty($steps)) {
    $initialSteps = $steps;
}

ob_start();
?>

<!-- Page header -->
<div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">
            <?= $isEdit ? "Edit Demo" : "New Demo" ?>
        </h1>
        <p class="mt-1 text-sm text-gray-500">
            <?= $isEdit
                ? "Update your teaching demonstration plan."
                : "Create a new teaching demonstration plan." ?>
        </p>
    </div>

    <!-- Action links (Save as Template / My Templates / Export / Cancel) -->
    <div class="flex items-center gap-3 flex-shrink-0 flex-wrap justify-end">
        <button type="button" id="demo-save-as-template-btn"
            class="inline-flex items-center gap-1.5 px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium rounded-lg shadow-sm transition-colors focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/></svg>
            Save as Template
        </button>

        <a href="<?= url("/demo-templates") ?>"
           class="inline-flex items-center gap-1.5 px-4 py-2 bg-purple-100 hover:bg-purple-200 text-purple-700 text-sm font-medium rounded-lg shadow-sm transition-colors focus:outline-none focus:ring-2 focus:ring-purple-400 focus:ring-offset-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            My Templates
        </a>

        <?php if ($isEdit): ?>
            <a href="<?= url("/demos/" . (int) $demo["id"] . "/export") ?>"
               class="inline-flex items-center gap-1.5 px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 text-sm font-medium rounded-lg shadow-sm transition-colors focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-offset-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
                Export
            </a>
        <?php endif; ?>

        <a href="<?= url("/demos") ?>"
           class="inline-flex items-center gap-1.5 px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 text-sm font-medium rounded-lg shadow-sm transition-colors focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-offset-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Cancel
        </a>
    </div>
</div>

<!-- General error banner -->
<?php if (!empty($errors["general"])): ?>
    <div class="mb-6 rounded-lg bg-red-50 border border-red-200 px-4 py-3" role="alert">
        <p class="text-sm text-red-600">
            <?= htmlspecialchars(
                (string) $errors["general"],
                ENT_QUOTES,
                "UTF-8",
            ) ?>
        </p>
    </div>
<?php endif; ?>

<!-- Demo form -->
<form method="POST" action="<?= htmlspecialchars(
    $formAction,
    ENT_QUOTES,
    "UTF-8",
) ?>" novalidate>

    <div class="space-y-8">

        <!-- ── Section 1: Basic Information ─────────────────────────────── -->
        <section class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h2 class="text-base font-semibold text-gray-700 mb-5">Basic Information</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <!-- Demo Title (required) -->
                <div class="md:col-span-2">
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-1">
                        Demo Title
                        <span class="text-red-500 ml-0.5" aria-hidden="true">*</span>
                    </label>
                    <input
                        type="text"
                        id="title"
                        name="title"
                        value="<?= $val("title") ?>"
                        required
                        autocomplete="off"
                        class="w-full rounded-lg border px-3 py-2 text-sm text-gray-900 placeholder-gray-400 shadow-sm
                               focus:outline-none focus:ring-2 transition
                               <?= $borderClass("title") ?>"
                        placeholder="e.g. Introduction to Fractions"
                        aria-describedby="title-error"
                    >
                    <span id="title-error"><?= $err("title") ?></span>
                    <p class="mt-1 text-xs text-slate-400">Write a clear, specific title that describes the topic of your demonstration. Include the subject and concept (e.g. "Identifying Nouns  Grade 3 English").</p>
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
                               <?= $borderClass("subject") ?>"
                        aria-describedby="subject-error"
                    >
                        <option value="">— Select a subject —</option>
                        <?php
                        $subjects = [
                            "Mathematics",
                            "English",
                            "Filipino",
                            "Science",
                            "Araling Panlipunan",
                            "MAPEH",
                            "Edukasyon sa Pagpapakatao (EsP)",
                            "Mother Tongue",
                            "Technology and Livelihood Education (TLE)",
                        ];
                        $currentSubject =
                            $old["subject"] ?? ($demo["subject"] ?? "");
                        foreach ($subjects as $s): ?>
                            <option value="<?= htmlspecialchars(
                                $s,
                            ) ?>" <?= $currentSubject === $s
    ? "selected"
    : "" ?>>
                                <?= htmlspecialchars($s) ?>
                            </option>
                        <?php endforeach;
                        ?>
                        <option value="Other" <?= !in_array(
                            $currentSubject,
                            $subjects,
                        ) && $currentSubject !== ""
                            ? "selected"
                            : "" ?>>Other</option>
                    </select>
                    <span id="subject-error"><?= $err("subject") ?></span>
                    <p class="mt-1 text-xs text-slate-400">Select the learning area this demo belongs to (e.g. Mathematics, English, Science).</p>
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
                               <?= $borderClass("grade_level") ?>"
                        aria-describedby="grade_level-error"
                    >
                        <option value="">— Select grade level —</option>
                        <?php
                        $grades = [
                            "Kindergarten",
                            "Grade 1",
                            "Grade 2",
                            "Grade 3",
                            "Grade 4",
                            "Grade 5",
                            "Grade 6",
                        ];
                        $currentGrade =
                            $old["grade_level"] ?? ($demo["grade_level"] ?? "");
                        foreach ($grades as $g): ?>
                            <option value="<?= htmlspecialchars(
                                $g,
                            ) ?>" <?= $currentGrade === $g ? "selected" : "" ?>>
                                <?= htmlspecialchars($g) ?>
                            </option>
                        <?php endforeach;
                        ?>
                    </select>
                    <span id="grade_level-error"><?= $err(
                        "grade_level",
                    ) ?></span>
                    <p class="mt-1 text-xs text-slate-400">Choose the grade level of the class you will be teaching this demonstration to.</p>
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
                        $quarters = [
                            1 => "1st Quarter",
                            2 => "2nd Quarter",
                            3 => "3rd Quarter",
                            4 => "4th Quarter",
                        ];
                        $currentQuarter =
                            (string) ($old["quarter"] ??
                                ($demo["quarter"] ?? ""));
                        foreach ($quarters as $qval => $qlabel): ?>
                            <option value="<?= $qval ?>" <?= $currentQuarter ===
(string) $qval
    ? "selected"
    : "" ?>>
                                <?= htmlspecialchars($qlabel) ?>
                            </option>
                        <?php endforeach;
                        ?>
                    </select>
                    <p class="mt-1 text-xs text-slate-400">Indicate which quarter of the school year this demo falls under (Q1Q4 per the DepEd school calendar).</p>
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
                        $currentWeek =
                            (string) ($old["week"] ?? ($demo["week"] ?? ""));
                        for ($w = 1; $w <= 10; $w++): ?>
                            <option value="<?= $w ?>" <?= $currentWeek ===
(string) $w
    ? "selected"
    : "" ?>>
                                Week <?= $w ?>
                            </option>
                        <?php endfor;
                        ?>
                    </select>
                    <p class="mt-1 text-xs text-slate-400">Select the specific week within the quarter when this demonstration will be conducted.</p>
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
                        $statuses = [
                            "draft" => "Draft",
                            "for_review" => "For Review",
                            "submitted" => "Submitted",
                        ];
                        $currentStatus =
                            $old["status"] ?? ($demo["status"] ?? "draft");
                        foreach ($statuses as $sval => $slabel): ?>
                            <option value="<?= $sval ?>" <?= $currentStatus ===
$sval
    ? "selected"
    : "" ?>>
                                <?= htmlspecialchars($slabel) ?>
                            </option>
                        <?php endforeach;
                        ?>
                    </select>
                </div>

                <!-- Duration -->
                <div>
                    <label for="duration_minutes" class="block text-sm font-medium text-gray-700 mb-1">
                        Duration (minutes)
                    </label>
                    <select
                        id="duration_minutes"
                        name="duration_minutes"
                        class="w-full rounded-lg border px-3 py-2 text-sm text-gray-900 shadow-sm
                               focus:outline-none focus:ring-2 transition bg-white
                               <?= $borderClass("duration_minutes") ?>"
                        aria-describedby="duration_minutes-error"
                    >
                        <option value="">— Select duration —</option>
                        <?php
                        $durations = [
                            20 => "20 minutes",
                            30 => "30 minutes",
                            40 => "40 minutes",
                            45 => "45 minutes",
                            50 => "50 minutes",
                            60 => "60 minutes (1 hour)",
                            90 => "90 minutes (1.5 hours)",
                            120 => "120 minutes (2 hours)",
                        ];
                        $currentDuration =
                            (string) ($old["duration_minutes"] ??
                                ($demo["duration_minutes"] ?? ""));
                        foreach ($durations as $durVal => $durLabel): ?>
                            <option value="<?= $durVal ?>" <?= $currentDuration ===
(string) $durVal
    ? "selected"
    : "" ?>>
                                <?= htmlspecialchars($durLabel) ?>
                            </option>
                        <?php endforeach;
                        ?>
                    </select>
                    <span id="duration_minutes-error"><?= $err(
                        "duration_minutes",
                    ) ?></span>
                    <p class="mt-1 text-xs text-slate-400">Enter the total time allotted for this demonstration (e.g. 40 minutes for a standard class period, 60 minutes for a double period).</p>
                </div>

            </div>
        </section>

        <!-- ── Section 2: Objectives & Materials ─────────────────────────── -->
        <section class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-3">
                <h2 class="text-base font-semibold text-gray-700">Objectives &amp; Materials</h2>
                <button type="button" id="load-obj-mat-template-btn"
                    class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-teal-600 hover:bg-teal-700 text-white text-xs font-medium rounded-lg shadow-sm transition-colors focus:outline-none focus:ring-2 focus:ring-teal-500 focus:ring-offset-2">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h10"/></svg>
                    Load Template
                </button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <!-- Learning Objectives (required) -->
                <div class="md:col-span-2">
                    <label for="learning_objectives" class="block text-sm font-medium text-gray-700 mb-1">
                        Learning Objectives
                        <span class="text-red-500 ml-0.5" aria-hidden="true">*</span>
                    </label>
                    <textarea
                        id="learning_objectives"
                        name="learning_objectives"
                        rows="4"
                        required
                        class="w-full rounded-lg border px-3 py-2 text-sm text-gray-900 placeholder-gray-400 shadow-sm
                               focus:outline-none focus:ring-2 transition resize-y
                               <?= $borderClass("learning_objectives") ?>"
                        placeholder="e.g.&#10;1. Identify fractions as parts of a whole.&#10;2. Represent fractions using models and number lines.&#10;3. Compare fractions with the same denominator."
                        aria-describedby="learning_objectives-error"
                    ><?= $val("learning_objectives") ?></textarea>
                    <span id="learning_objectives-error"><?= $err(
                        "learning_objectives",
                    ) ?></span>
                </div>

                <!-- Materials Needed -->
                <div class="md:col-span-2">
                    <label for="materials_needed" class="block text-sm font-medium text-gray-700 mb-1">
                        Materials Needed
                    </label>
                    <textarea
                        id="materials_needed"
                        name="materials_needed"
                        rows="3"
                        class="w-full rounded-lg border px-3 py-2 text-sm text-gray-900 placeholder-gray-400 shadow-sm
                               focus:outline-none focus:ring-2 transition resize-y
                               <?= $borderClass("materials_needed") ?>"
                        placeholder="e.g.&#10;- Fraction strips / cut-outs&#10;- Number line chart&#10;- Whiteboard and markers&#10;- Activity worksheets"
                        aria-describedby="materials_needed-error"
                    ><?= $val("materials_needed") ?></textarea>
                    <span id="materials_needed-error"><?= $err(
                        "materials_needed",
                    ) ?></span>
                </div>

            </div>
        </section>

        <!-- ── Section 3: Lesson Flow ─────────────────────────────────────── -->
        <section class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-5">
                <h2 class="text-base font-semibold text-gray-700">Lesson Flow</h2>
                <button type="button" id="load-demo-template-btn"
                    class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-purple-600 hover:bg-purple-700 text-white text-xs font-medium rounded-md shadow-sm transition-colors focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h10"/>
                    </svg>
                    Load Template
                </button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <!-- Introduction / Motivation -->
                <div class="md:col-span-2">
                    <label for="introduction" class="block text-sm font-medium text-gray-700 mb-1">
                        Introduction / Motivation
                    </label>
                    <textarea
                        id="introduction"
                        name="introduction"
                        rows="4"
                        class="w-full rounded-lg border px-3 py-2 text-sm text-gray-900 placeholder-gray-400 shadow-sm
                               focus:outline-none focus:ring-2 transition resize-y
                               <?= $borderClass("introduction") ?>"
                        placeholder="e.g. Show a pizza cut into equal slices. Ask: 'If I eat 1 out of 4 slices, what part of the pizza did I eat?' Lead students to discover the concept of fractions through the familiar context of sharing food."
                        aria-describedby="introduction-error"
                    ><?= $val("introduction") ?></textarea>
                    <span id="introduction-error"><?= $err(
                        "introduction",
                    ) ?></span>
                </div>

                <!-- Lesson Proper — dynamic step list (Requirement 3.7) -->
                <div class="md:col-span-2">
                    <div class="flex flex-wrap items-center justify-between gap-2 mb-3">
                        <label class="block text-sm font-medium text-gray-700">
                            Lesson Proper
                            <span class="text-xs text-gray-400 font-normal ml-1">(step-by-step procedure)</span>
                        </label>
                        <div class="flex flex-wrap items-center gap-2">
                            <!-- 4A's template button -->
                            <button
                                type="button"
                                id="load-4as-btn"
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-purple-600 hover:bg-purple-700 text-white text-xs font-medium rounded-md shadow-sm transition-colors focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2">
                                Load 4A's Template
                            </button>
                            <!-- 5E's template button -->
                            <button
                                type="button"
                                id="load-5es-btn"
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-teal-600 hover:bg-teal-700 text-white text-xs font-medium rounded-md shadow-sm transition-colors focus:outline-none focus:ring-2 focus:ring-teal-500 focus:ring-offset-2">
                                Load 5E's Template
                            </button>
                            <button
                                type="button"
                                id="add-step-btn"
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-medium rounded-md shadow-sm transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                                Add Step
                            </button>
                        </div>
                    </div>

                    <!-- Step rows container -->
                    <div id="steps-container" class="space-y-3">
                        <?php if (empty($initialSteps)): ?>
                            <!-- Empty state hint -->
                            <p id="steps-empty-hint" class="text-sm text-gray-400 italic">
                                No steps yet. Click "Add Step" to begin building the lesson procedure.
                            </p>
                        <?php else: ?>
                            <?php foreach ($initialSteps as $idx => $step): ?>
                                <div class="step-row flex items-start gap-3 p-3 bg-gray-50 rounded-lg border border-gray-200">
                                    <!-- Step number badge -->
                                    <span class="step-number-badge flex-shrink-0 inline-flex items-center justify-center w-7 h-7 rounded-full bg-blue-100 text-blue-700 text-xs font-bold mt-1">
                                        <?= (int) $step["step_number"] ?>
                                    </span>

                                    <!-- Hidden step_number input -->
                                    <input
                                        type="hidden"
                                        name="steps[<?= $idx ?>][step_number]"
                                        value="<?= (int) $step[
                                            "step_number"
                                        ] ?>"
                                        class="step-number-input"
                                    >

                                    <!-- Description textarea -->
                                    <textarea
                                        name="steps[<?= $idx ?>][description]"
                                        rows="2"
                                        class="flex-1 rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-900 placeholder-gray-400 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition resize-y"
                                        placeholder="Describe step <?= (int) $step[
                                            "step_number"
                                        ] ?>…"
                                        aria-label="Step <?= (int) $step[
                                            "step_number"
                                        ] ?> description"
                                    ><?= htmlspecialchars(
                                        (string) $step["description"],
                                        ENT_QUOTES,
                                        "UTF-8",
                                    ) ?></textarea>

                                    <!-- Remove button -->
                                    <button
                                        type="button"
                                        class="remove-step-btn flex-shrink-0 inline-flex items-center justify-center w-7 h-7 rounded-md text-red-500 hover:bg-red-50 hover:text-red-700 transition-colors focus:outline-none focus:ring-2 focus:ring-red-400 mt-1"
                                        aria-label="Remove step <?= (int) $step[
                                            "step_number"
                                        ] ?>">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Generalization -->
                <div class="md:col-span-2">
                    <label for="generalization" class="block text-sm font-medium text-gray-700 mb-1">
                        Generalization
                    </label>
                    <textarea
                        id="generalization"
                        name="generalization"
                        rows="3"
                        class="w-full rounded-lg border px-3 py-2 text-sm text-gray-900 placeholder-gray-400 shadow-sm
                               focus:outline-none focus:ring-2 transition resize-y
                               <?= $borderClass("generalization") ?>"
                        placeholder="e.g. Ask: 'What is a fraction?' Guide students to conclude: A fraction represents equal parts of a whole. The bottom number (denominator) tells how many equal parts, and the top number (numerator) tells how many parts we are talking about."
                        aria-describedby="generalization-error"
                    ><?= $val("generalization") ?></textarea>
                    <span id="generalization-error"><?= $err(
                        "generalization",
                    ) ?></span>
                </div>

                <!-- Application / Practice Activity -->
                <div class="md:col-span-2">
                    <label for="application" class="block text-sm font-medium text-gray-700 mb-1">
                        Application / Practice Activity
                    </label>
                    <textarea
                        id="application"
                        name="application"
                        rows="3"
                        class="w-full rounded-lg border px-3 py-2 text-sm text-gray-900 placeholder-gray-400 shadow-sm
                               focus:outline-none focus:ring-2 transition resize-y
                               <?= $borderClass("application") ?>"
                        placeholder="e.g. Group Activity: Give each group a set of fraction cards. Students sort the cards from smallest to largest and paste them on a number line. Each group presents their work to the class."
                        aria-describedby="application-error"
                    ><?= $val("application") ?></textarea>
                    <span id="application-error"><?= $err(
                        "application",
                    ) ?></span>
                </div>

                <!-- Assessment / Evaluation -->
                <div class="md:col-span-2">
                    <label for="assessment" class="block text-sm font-medium text-gray-700 mb-1">
                        Assessment / Evaluation
                    </label>
                    <textarea
                        id="assessment"
                        name="assessment"
                        rows="3"
                        class="w-full rounded-lg border px-3 py-2 text-sm text-gray-900 placeholder-gray-400 shadow-sm
                               focus:outline-none focus:ring-2 transition resize-y
                               <?= $borderClass("assessment") ?>"
                        placeholder="e.g. Individual written quiz: Identify and shade the correct fraction in 5 diagrams. Scoring: 2 points each = 10 points total. Mastery level: 8/10."
                        aria-describedby="assessment-error"
                    ><?= $val("assessment") ?></textarea>
                    <span id="assessment-error"><?= $err("assessment") ?></span>
                </div>

            </div>
        </section>

        <!-- ── Completeness Indicator ────────────────────────────────────── -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
            <div class="flex items-center justify-between mb-2">
                <span class="text-sm font-medium text-gray-700">Form Completeness</span>
                <span id="completeness-pct" class="text-sm font-semibold text-blue-600">0%</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2.5">
                <div id="completeness-bar" class="bg-blue-600 h-2.5 rounded-full transition-all duration-300" style="width:0%"></div>
            </div>
            <div id="completeness-checklist" class="mt-3 grid grid-cols-2 sm:grid-cols-3 gap-1.5 text-xs text-gray-500"></div>
        </div>

        <!-- ── Form actions ───────────────────────────────────────────────── -->
        <div class="flex flex-col sm:flex-row items-center justify-end gap-3">
            <a href="<?= url("/demos") ?>"
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
                <?= $isEdit ? "Save Changes" : "Create Demo" ?>
            </button>
        </div>

    </div><!-- /space-y-8 -->

</form>

<!-- ── Generate Template Modal ───────────────────────────────────────────── -->
<div id="generate-template-modal"
     class="fixed inset-0 z-50 hidden items-center justify-center p-4"
     role="dialog" aria-modal="true" aria-labelledby="gen-modal-title">
    <div id="gen-modal-backdrop" class="absolute inset-0 bg-black/50"></div>
    <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-lg max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200 sticky top-0 bg-white rounded-t-2xl">
            <div>
                <h3 id="gen-modal-title" class="text-base font-semibold text-gray-800">Generate Demo Template</h3>
                <p class="text-xs text-gray-500 mt-0.5">Select a subject and grade level to auto-fill all demo fields.</p>
            </div>
            <button type="button" id="close-gen-modal-btn" class="inline-flex items-center justify-center w-8 h-8 rounded-md text-gray-400 hover:bg-gray-100 hover:text-gray-600 transition-colors focus:outline-none focus:ring-2 focus:ring-gray-400">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <div class="p-6 space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Subject</label>
                <select id="gen-subject" class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm text-gray-900 bg-white shadow-sm focus:outline-none focus:ring-2 focus:ring-violet-500">
                    <option value="">— Select a subject —</option>
                    <option value="Mathematics">Mathematics</option>
                    <option value="English">English</option>
                    <option value="Filipino">Filipino</option>
                    <option value="Science">Science</option>
                    <option value="Araling Panlipunan">Araling Panlipunan</option>
                    <option value="MAPEH">MAPEH</option>
                    <option value="Edukasyon sa Pagpapakatao (EsP)">Edukasyon sa Pagpapakatao (EsP)</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Grade Level</label>
                <select id="gen-grade" class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm text-gray-900 bg-white shadow-sm focus:outline-none focus:ring-2 focus:ring-violet-500">
                    <option value="">— Select grade level —</option>
                    <option value="Kindergarten">Kindergarten</option>
                    <option value="Grade 1">Grade 1</option>
                    <option value="Grade 2">Grade 2</option>
                    <option value="Grade 3">Grade 3</option>
                    <option value="Grade 4">Grade 4</option>
                    <option value="Grade 5">Grade 5</option>
                    <option value="Grade 6">Grade 6</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Topic / Lesson Focus <span class="text-xs text-gray-400">(optional)</span></label>
                <input type="text" id="gen-topic" placeholder="e.g. Fractions, Nouns, Living Things…"
                    class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm text-gray-900 shadow-sm focus:outline-none focus:ring-2 focus:ring-violet-500">
            </div>
            <p id="gen-error" class="text-sm text-red-600 hidden">Please select both a subject and grade level.</p>
        </div>
        <div class="px-6 pb-5 flex gap-3 justify-end border-t border-gray-100 pt-4">
            <button type="button" id="cancel-gen-btn" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 text-sm font-medium rounded-xl transition-colors">Cancel</button>
            <button type="button" id="confirm-gen-btn"
                class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-violet-600 to-indigo-600 hover:from-violet-700 hover:to-indigo-700 text-white text-sm font-medium rounded-xl transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                Generate &amp; Fill
            </button>
        </div>
    </div>
</div>

<!-- ── Generate Template JavaScript ──────────────────────────────────────── -->
<script>
(function () {
    'use strict';

    // ── Template data by subject ──────────────────────────────────────────────
    var genTemplates = {
        'Mathematics': {
            titleFn:    function(g, t) { return (t || 'Introduction to Fractions') + ' — ' + g + ' Mathematics'; },
            objectives: function(t) { return '1. Identify ' + (t||'fractions') + ' as equal parts of a whole.\n2. Represent ' + (t||'fractions') + ' using models and number lines.\n3. Compare and order ' + (t||'fractions') + ' with the same denominator.'; },
            materials:  '- Fraction strips and cut-outs\n- Number line chart (0 to 1)\n- Colored chalk / whiteboard markers\n- Activity worksheets\n- Flashcards',
            intro:      'Show a pizza cut into equal slices. Ask: "If you eat 3 out of 8 slices, what part of the pizza did you eat?" Let students share their answers. Guide them to discover the concept of fractions through the familiar context of sharing food.',
            steps: [
                '[Activity] Have students work in groups with fraction strips. Each group identifies and names the equal parts (1/2, 1/3, 1/4). Groups record their findings on a chart.',
                '[Analysis] Guide students to analyze: What did you notice about the fractions? How does the denominator change the size of each part?',
                '[Abstraction] Lead students to state the rule: A fraction has a numerator (top) and denominator (bottom). The denominator tells the total equal parts; the numerator tells how many parts are taken.',
                '[Application] Students solve 5 fraction problems on the board and in their notebooks. Call on volunteers to explain their answers.'
            ],
            generalization: 'Ask: "What is a fraction?" Guide students to conclude: A fraction represents equal parts of a whole. The numerator tells how many parts are taken; the denominator tells how many equal parts the whole is divided into.',
            application:    'Group Activity: Give each group a set of fraction cards and a number line (0 to 1). Students arrange the fraction cards from smallest to largest and paste them on the number line. Each group presents their work.',
            assessment:     'Written quiz: Shade the correct fraction in 5 diagrams (2 pts each = 10 pts). Identify the numerator and denominator in 5 fractions (1 pt each = 5 pts). Total: 15 points. Mastery: 12/15.'
        },
        'English': {
            titleFn:    function(g, t) { return (t || 'Reading Comprehension') + ' — ' + g + ' English'; },
            objectives: function(t) { return '1. Identify the characters, setting, problem, and solution in a story.\n2. Infer the meaning of unfamiliar words using context clues.\n3. Retell the story in sequence using key details from the text.'; },
            materials:  '- Short story text (printed copies, 1 per student)\n- Graphic organizer worksheet (story map)\n- Vocabulary cards\n- Whiteboard and markers\n- Picture cards related to the story',
            intro:      'Show the cover of a short story. Ask: "What do you think this story is about? What clues does the cover give you?" Let students predict the story. Tell them they will read to find out if their predictions are correct.',
            steps: [
                '[Engage] Show the story cover and ask prediction questions. Play a short audio clip or show a related picture to build interest and activate prior knowledge.',
                '[Explore] Read the story aloud while students follow along. Students underline unfamiliar words and note key events on a graphic organizer.',
                '[Explain] Discuss the story elements: characters, setting, problem, solution. Clarify vocabulary. Students share their graphic organizers with a partner.',
                '[Elaborate] Students connect the story to their own experiences. Ask: "Has something like this ever happened to you? What would you have done differently?"',
                '[Evaluate] Students answer comprehension questions and write a brief summary of the story in 3–4 sentences.'
            ],
            generalization: 'Ask: "What do good readers do when they read a story?" Guide students to state: Good readers identify the characters, setting, problem, and solution. They use details from the text to support their understanding.',
            application:    'Individual Activity: Students answer a short comprehension worksheet with 5 questions about the story. Then they write 2–3 sentences describing their favorite part and why they liked it.',
            assessment:     'Reading comprehension test: Answer 10 questions about the story (1 pt each = 10 pts). Write a 3-sentence summary (5 pts). Total: 15 points. Mastery: 12/15.'
        },
        'Filipino': {
            titleFn:    function(g, t) { return (t || 'Pagbabasa ng Maikling Kwento') + ' — ' + g + ' Filipino'; },
            objectives: function(t) { return '1. Natutukoy ang mga tauhan, tagpuan, suliranin, at solusyon ng kwento.\n2. Naipapaliwanag ang kahulugan ng mga salita gamit ang konteksto.\n3. Naipapahayag ang aral na natutuhan mula sa kwento sa sariling salita.'; },
            materials:  '- Kopya ng maikling kwento (1 bawat mag-aaral)\n- Graphic organizer (tauhan, tagpuan, suliranin, solusyon)\n- Mga larawan na may kaugnayan sa kwento\n- Pisara at tisa / whiteboard markers\n- Flashcard ng mga salita',
            intro:      'Magpakita ng larawan na may kaugnayan sa paksa. Tanungin: "Ano ang nakikita ninyo sa larawan? Ano ang nararamdaman ng taong nasa larawan?" Hayaan ang mga mag-aaral na ibahagi ang kanilang mga sagot.',
            steps: [
                '[Aktibidad] Basahin ang maikling kwento nang malakas. Ang mga mag-aaral ay susundan ang pagbabasa at magtatala ng mga pangunahing pangyayari.',
                '[Pagsusuri] Gabayan ang mga mag-aaral na suriin ang kwento: Sino ang mga tauhan? Saan naganap ang kwento? Ano ang suliranin? Paano ito nalutas?',
                '[Abstraksiyon] Ipaliwanag ang mga bahagi ng kwento: tauhan, tagpuan, suliranin, at solusyon. Isulat ang mga ito sa pisara at basahin ng klase nang sabay-sabay.',
                '[Aplikasyon] Ang mga mag-aaral ay susulat ng maikling buod ng kwento gamit ang graphic organizer. Ibabahagi nila ang kanilang gawa sa kanilang katabi.'
            ],
            generalization: 'Tanungin: "Ano ang natutuhan natin ngayon?" Gabayan ang mga mag-aaral na sabihin: Ang isang kwento ay may mga tauhan, tagpuan, suliranin, at solusyon. Ang pag-unawa sa mga bahaging ito ay tumutulong sa atin na maunawaan ang mensahe ng kwento.',
            application:    'Pangkatang Gawain: Ang bawat pangkat ay gagawa ng maikling buod ng kwento gamit ang graphic organizer. Ipapakita ng bawat pangkat ang kanilang gawa sa klase.',
            assessment:     'Sagutan ang 10 tanong tungkol sa kwento (1 puntos bawat isa = 10 puntos). Sumulat ng 3-pangungusap na buod (5 puntos). Kabuuan: 15 puntos. Mastery: 12/15.'
        },
        'Science': {
            titleFn:    function(g, t) { return (t || 'Living and Non-Living Things') + ' — ' + g + ' Science'; },
            objectives: function(t) { return '1. Classify objects as living or non-living based on their characteristics.\n2. Describe the basic needs of living things (food, water, air, shelter).\n3. Compare the characteristics of plants and animals as living things.'; },
            materials:  '- Small potted plant and a rock (for demonstration)\n- Picture cards of living and non-living things (10 cards per group)\n- Observation chart / worksheet\n- Magnifying glass (optional)\n- Whiteboard and markers',
            intro:      'Bring a small potted plant and a rock to class. Ask: "How are these two objects different? What can the plant do that the rock cannot?" Let students observe and share their observations. Tell them they will learn more about what makes something alive.',
            steps: [
                '[Engage] Present the plant and rock. Ask students to observe and list 3 differences. Introduce the question: "What makes something alive?"',
                '[Explore] Students observe 4 objects (plant, rock, insect picture, toy). They record observations in a table: Does it grow? Does it need food? Does it move on its own?',
                '[Explain] Discuss findings. Introduce the 4 characteristics of living things. Correct misconceptions (e.g., fire moves but is not alive).',
                '[Elaborate] Students think of 3 more examples of living things and 3 non-living things not shown in class. They explain their choices.',
                '[Evaluate] Students complete a classification activity and answer: "Why is a seed considered a living thing even though it does not move?"'
            ],
            generalization: 'Ask: "What makes something a living thing?" Guide students to state: Living things share common characteristics — they grow, need food/water/air, respond to their environment, and can reproduce. Non-living things do not have these characteristics.',
            application:    'Activity: Students sort a set of picture cards into two groups — Living Things and Non-Living Things. They write one sentence explaining why each item belongs in its group. Share and discuss as a class.',
            assessment:     'Written test: Classify 10 pictures as living or non-living (1 pt each = 10 pts). Give 2 characteristics of living things (2 pts each = 4 pts). Total: 14 points. Mastery: 11/14.'
        },
        'Araling Panlipunan': {
            titleFn:    function(g, t) { return (t || 'Mga Uri ng Komunidad') + ' — ' + g + ' Araling Panlipunan'; },
            objectives: function(t) { return '1. Natutukoy ang mga uri ng komunidad (lungsod, bayan, baryo) at ang kanilang katangian.\n2. Nailarawan ang mga pasilidad at serbisyong makikita sa bawat uri ng komunidad.\n3. Naipapakita ang pagpapahalaga sa sariling komunidad sa pamamagitan ng mga gawain.'; },
            materials:  '- Mapa ng komunidad (1 bawat pangkat)\n- Mga larawan ng lungsod, bayan, at baryo\n- Graphic organizer worksheet\n- Pisara at tisa / whiteboard markers\n- Mga larawan ng pasilidad sa komunidad',
            intro:      'Magpakita ng larawan ng iba\'t ibang uri ng komunidad (lungsod, bayan, baryo). Tanungin: "Saan kayo nakatira? Ano ang mga katangian ng inyong komunidad?" Hayaan ang mga mag-aaral na ibahagi ang kanilang karanasan.',
            steps: [
                '[Pagsusuri] Ipakita ang mapa ng komunidad. Tanungin ang mga mag-aaral na tukuyin ang mga lugar na napag-aralan.',
                '[Pagganyak] Magpakita ng larawan ng iba\'t ibang uri ng komunidad. Tanungin: "Ano ang pagkakaiba ng lungsod at baryo?"',
                '[Pagtatanghal] Ipaliwanag ang mga uri ng komunidad: Lungsod, Bayan, at Baryo. Gamitin ang mga larawan at mapa bilang visual aids.',
                '[Talakayan] Magtanong ng mga gabay na tanong tungkol sa mga pasilidad at serbisyo sa bawat uri ng komunidad.',
                '[Aplikasyon] Ang bawat pangkat ay gagawa ng poster ng kanilang komunidad. Isasama nila ang mga lugar, pasilidad, at tao.'
            ],
            generalization: 'Tanungin: "Ano ang komunidad?" Gabayan ang mga mag-aaral na sabihin: Ang komunidad ay isang lugar kung saan nakatira ang mga tao. Mayroon itong iba\'t ibang uri tulad ng lungsod, bayan, at baryo.',
            application:    'Pangkatang Gawain: Ang bawat pangkat ay gagawa ng poster ng kanilang komunidad. Ipapakita ng bawat pangkat ang kanilang poster at ipapaliwanag ang mga nilalaman nito.',
            assessment:     'Pasalitang pagtatasa: Tukuyin ang uri ng komunidad sa 5 larawan (2 puntos bawat isa = 10 puntos). Isulat ang 3 katangian ng inyong komunidad (5 puntos). Kabuuan: 15 puntos. Mastery: 12/15.'
        },
        'MAPEH': {
            titleFn:    function(g, t) { return (t || 'Elements of Music') + ' — ' + g + ' MAPEH'; },
            objectives: function(t) { return '1. Identify the elements of music: melody, rhythm, and dynamics in a given song.\n2. Perform the song with correct pitch, rhythm, and appropriate dynamics.\n3. Appreciate the cultural significance of Filipino folk songs.'; },
            materials:  '- Audio recording of a Filipino folk song\n- Song lyrics (printed, 1 per student)\n- Simple percussion instruments (tambourine, claves, or improvised)\n- Musical notation chart\n- Whiteboard and markers',
            intro:      'Play a short recording of a familiar Filipino folk song. Ask: "Have you heard this song before? What do you feel when you listen to it?" Let students share their reactions. Tell them they will learn to sing and understand this song.',
            steps: [
                '[Engage] Play the song and ask students to identify the mood: happy, sad, or lively.',
                '[Explore] Teach the song line by line: Sing the first phrase; students echo. Sing the second phrase; students echo. Combine both phrases; sing together.',
                '[Explain] Point out the melody, rhythm, and dynamics (loud/soft parts). Show the musical notation on the board.',
                '[Elaborate] Students identify the beat by tapping on their desks while singing.',
                '[Evaluate] Students sing the song as a class with proper dynamics. Small groups perform with simple body movements or percussion instruments.'
            ],
            generalization: 'Ask: "What did we learn about this song?" Guide students to state: Music has melody, rhythm, and dynamics. The melody is the tune; the rhythm is the pattern of beats; dynamics tell us how loud or soft to sing.',
            application:    'Performance Activity: Students sing the song as a class with proper dynamics. Then small groups perform the song with simple body movements or percussion instruments. Class evaluates each performance using a simple rubric.',
            assessment:     'Performance assessment: Sing the song with correct pitch and rhythm (10 pts). Identify the melody, rhythm, and dynamics of the song (5 pts). Total: 15 points. Mastery: 12/15.'
        },
        'Edukasyon sa Pagpapakatao (EsP)': {
            titleFn:    function(g, t) { return (t || 'Pagpapahalaga at Mabuting Asal') + ' — ' + g + ' EsP'; },
            objectives: function(t) { return '1. Natutukoy ang kahulugan at kahalagahan ng pagpapahalaga na tatalakayin.\n2. Naipapakita ang pagpapahalaga sa pamamagitan ng mga kongkretong halimbawa sa paaralan at tahanan.\n3. Naipapahayag ang personal na pangako na isasabuhay ang pagpapahalaga sa araw-araw na buhay.'; },
            materials:  '- Mga sitwasyon cards (5 sitwasyon)\n- Graphic organizer worksheet\n- Mga larawan na nagpapakita ng mabuting pagpapahalaga\n- Pisara at tisa / whiteboard markers\n- Pangako card (1 bawat mag-aaral)',
            intro:      'Magkwento ng isang maikling kwento tungkol sa isang batang nagpakita ng mabuting pagpapahalaga. Tanungin: "Ano ang ginawa ng bata sa kwento? Paano kayo makakaramdam kung kayo ang nasa sitwasyong iyon?"',
            steps: [
                '[Sitwasyon] Magbigay ng maikling sitwasyon. Tanungin ang mga mag-aaral kung paano nila haharapin ang sitwasyon.',
                '[Pagsusuri] Gabayan ang mga mag-aaral na suriin ang sitwasyon: Ano ang tamang gawin? Bakit mahalaga ang pagpapakita ng mabuting pagpapahalaga?',
                '[Pagtatanghal] Ipaliwanag ang pagpapahalaga na tatalakayin: kahulugan, kahalagahan, at mga paraan ng pagpapakita.',
                '[Talakayan] Magtanong ng mga gabay na tanong: "Bakit mahalaga ang pagpapakita ng mabuting pagpapahalaga? Paano ninyo ito ipinakita sa inyong pamilya?"',
                '[Aplikasyon] Ang bawat mag-aaral ay susulat ng isang pangako kung paano nila ipapakita ang pagpapahalaga sa kanilang pamilya, kaklase, at komunidad.'
            ],
            generalization: 'Tanungin: "Ano ang natutuhan natin ngayon?" Gabayan ang mga mag-aaral na sabihin: Ang pagpapakita ng mabuting pagpapahalaga ay nagpapalakas ng ating ugnayan sa iba. Ito ay nagpapakita ng ating pagmamahal at respeto sa ating kapwa.',
            application:    'Indibidwal na Gawain: Ang bawat mag-aaral ay susulat ng isang pangako kung paano nila ipapakita ang pagpapahalaga sa kanilang pamilya, kaklase, at komunidad. Ibabahagi nila ang kanilang pangako sa klase.',
            assessment:     'Pasalitang pagtatasa: Ibahagi ang isang sitwasyon kung saan ipinakita mo ang pagpapahalaga (5 puntos). Nakasulat: Sagutin ang 5 tanong (2 puntos bawat isa = 10 puntos). Kabuuan: 15 puntos. Mastery: 12/15.'
        }
    };

    // ── Modal controls ────────────────────────────────────────────────────────
    var modal      = document.getElementById('generate-template-modal');
    var backdrop   = document.getElementById('gen-modal-backdrop');
    var openBtn    = document.getElementById('generate-demo-template-btn');
    var closeBtn   = document.getElementById('close-gen-modal-btn');
    var cancelBtn  = document.getElementById('cancel-gen-btn');
    var confirmBtn = document.getElementById('confirm-gen-btn');
    var subjSel    = document.getElementById('gen-subject');
    var gradeSel   = document.getElementById('gen-grade');
    var topicInput = document.getElementById('gen-topic');
    var errEl      = document.getElementById('gen-error');

    function openModal()  { modal.classList.remove('hidden'); modal.classList.add('flex'); document.body.style.overflow = 'hidden'; }
    function closeModal() { modal.classList.add('hidden'); modal.classList.remove('flex'); document.body.style.overflow = ''; if (errEl) errEl.classList.add('hidden'); }

    if (openBtn)   openBtn.addEventListener('click', openModal);
    if (closeBtn)  closeBtn.addEventListener('click', closeModal);
    if (cancelBtn) cancelBtn.addEventListener('click', closeModal);
    if (backdrop)  backdrop.addEventListener('click', closeModal);
    document.addEventListener('keydown', function(e) { if (e.key === 'Escape') closeModal(); });

    // Pre-fill selects from current form values
    if (openBtn) {
        openBtn.addEventListener('click', function() {
            var formSubj  = document.getElementById('subject');
            var formGrade = document.getElementById('grade_level');
            if (formSubj  && formSubj.value  && subjSel)  subjSel.value  = formSubj.value;
            if (formGrade && formGrade.value && gradeSel) gradeSel.value = formGrade.value;
        });
    }

    // ── Generate & Fill ───────────────────────────────────────────────────────
    if (confirmBtn) {
        confirmBtn.addEventListener('click', function () {
            var subj  = subjSel  ? subjSel.value.trim()  : '';
            var grade = gradeSel ? gradeSel.value.trim() : '';
            var topic = topicInput ? topicInput.value.trim() : '';

            if (!subj || !grade) { if (errEl) errEl.classList.remove('hidden'); return; }
            if (errEl) errEl.classList.add('hidden');

            var tpl = genTemplates[subj];
            if (!tpl) { alert('No template available for ' + subj + '. Please fill in manually.'); closeModal(); return; }

            var hasContent = false;
            ['title','learning_objectives','materials_needed','introduction','generalization','application','assessment'].forEach(function(id) {
                var el = document.getElementById(id);
                if (el && el.value.trim() !== '') hasContent = true;
            });

            if (hasContent && !confirm('This will replace existing form content. Continue?')) return;

            // Fill basic fields
            function setField(id, val) { var el = document.getElementById(id); if (el) el.value = val; }
            function setSelect(id, val) {
                var el = document.getElementById(id);
                if (!el) return;
                for (var i = 0; i < el.options.length; i++) {
                    if (el.options[i].value === val) { el.selectedIndex = i; break; }
                }
            }

            setField('title',               tpl.titleFn(grade, topic));
            setSelect('subject',            subj);
            setSelect('grade_level',        grade);
            setField('learning_objectives', tpl.objectives(topic));
            setField('materials_needed',    tpl.materials);
            setField('introduction',        tpl.intro);
            setField('generalization',      tpl.generalization);
            setField('application',         tpl.application);
            setField('assessment',          tpl.assessment);

            // Fill steps
            var container = document.getElementById('steps-container');
            var hint      = document.getElementById('steps-empty-hint');
            if (container && tpl.steps) {
                container.querySelectorAll('.step-row').forEach(function(r) { r.remove(); });
                if (hint) hint.style.display = 'none';
                tpl.steps.forEach(function(desc, idx) {
                    var num = idx + 1;
                    var row = document.createElement('div');
                    row.className = 'step-row flex items-start gap-3 p-3 bg-gray-50 rounded-lg border border-gray-200';
                    row.innerHTML =
                        '<span class="step-number-badge flex-shrink-0 inline-flex items-center justify-center w-7 h-7 rounded-full bg-blue-100 text-blue-700 text-xs font-bold mt-1">' + num + '</span>'
                        + '<input type="hidden" class="step-number-input" name="steps[' + idx + '][step_number]" value="' + num + '">'
                        + '<textarea name="steps[' + idx + '][description]" rows="2" class="flex-1 rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-900 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition resize-y" aria-label="Step ' + num + ' description"></textarea>'
                        + '<button type="button" class="remove-step-btn flex-shrink-0 inline-flex items-center justify-center w-7 h-7 rounded-md text-red-500 hover:bg-red-50 hover:text-red-700 transition-colors focus:outline-none focus:ring-2 focus:ring-red-400 mt-1"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>';
                    container.appendChild(row);
                    var ta = row.querySelector('textarea');
                    if (ta) ta.value = desc;
                });
            }

            closeModal();

            // Scroll to top of form
            var titleEl = document.getElementById('title');
            if (titleEl) titleEl.scrollIntoView({ behavior: 'smooth', block: 'center' });
        });
    }

})();
</script>

<!-- ── Objectives & Materials Template Modal ─────────────────────────────── -->
<div id="obj-mat-modal"
     class="fixed inset-0 z-50 hidden items-center justify-center p-4"
     role="dialog" aria-modal="true" aria-labelledby="obj-mat-modal-title">
    <div id="obj-mat-backdrop" class="absolute inset-0 bg-black/50"></div>
    <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-xl max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200 sticky top-0 bg-white rounded-t-2xl">
            <div>
                <h3 id="obj-mat-modal-title" class="text-base font-semibold text-gray-800">Load Objectives &amp; Materials Template</h3>
                <p class="text-xs text-gray-500 mt-0.5">Fills Learning Objectives and Materials Needed. Edit after loading.</p>
            </div>
            <button type="button" id="close-obj-mat-modal-btn" class="inline-flex items-center justify-center w-8 h-8 rounded-md text-gray-400 hover:bg-gray-100 hover:text-gray-600 transition-colors focus:outline-none focus:ring-2 focus:ring-gray-400">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <div class="p-6 space-y-3">
            <?php
            $objMatTemplates = [
                [
                    "key" => "math-om",
                    "color" => "blue",
                    "label" => "M",
                    "title" => "Mathematics — Fractions",
                    "obj" =>
                        "1. Identify fractions as equal parts of a whole with denominators 2, 3, 4, 5, 6, 8, and 10.\n2. Represent fractions using models, fraction strips, and number lines.\n3. Compare fractions with the same denominator using the symbols <, >, and =.",
                    "mat" =>
                        "- Fraction strips and cut-outs (1 set per group)\n- Number line chart (0 to 1)\n- Colored chalk / whiteboard markers\n- Activity worksheets\n- Flashcards with fraction symbols",
                ],
                [
                    "key" => "english-om",
                    "color" => "green",
                    "label" => "E",
                    "title" => "English — Reading Comprehension",
                    "obj" =>
                        "1. Identify the characters, setting, problem, and solution in a story.\n2. Infer the meaning of unfamiliar words using context clues.\n3. Retell the story in sequence using key details from the text.",
                    "mat" =>
                        "- Short story text (printed copies, 1 per student)\n- Graphic organizer worksheet (story map)\n- Vocabulary cards\n- Whiteboard and markers\n- Picture cards related to the story",
                ],
                [
                    "key" => "filipino-om",
                    "color" => "yellow",
                    "label" => "F",
                    "title" => "Filipino — Pagbabasa",
                    "obj" =>
                        "1. Natutukoy ang mga tauhan, tagpuan, suliranin, at solusyon ng kwento.\n2. Naipapaliwanag ang kahulugan ng mga salita gamit ang konteksto.\n3. Naipapahayag ang aral na natutuhan mula sa kwento sa sariling salita.",
                    "mat" =>
                        "- Kopya ng maikling kwento (1 bawat mag-aaral)\n- Graphic organizer (tauhan, tagpuan, suliranin, solusyon)\n- Mga larawan na may kaugnayan sa kwento\n- Pisara at tisa / whiteboard markers\n- Flashcard ng mga salita",
                ],
                [
                    "key" => "science-om",
                    "color" => "teal",
                    "label" => "S",
                    "title" => "Science — Living Things",
                    "obj" =>
                        "1. Classify objects as living or non-living based on their characteristics.\n2. Describe the basic needs of living things (food, water, air, shelter).\n3. Compare the characteristics of plants and animals as living things.",
                    "mat" =>
                        "- Small potted plant and a rock (for demonstration)\n- Picture cards of living and non-living things (10 cards per group)\n- Observation chart / worksheet\n- Magnifying glass (optional)\n- Whiteboard and markers",
                ],
                [
                    "key" => "ap-om",
                    "color" => "orange",
                    "label" => "AP",
                    "title" => "Araling Panlipunan — Komunidad",
                    "obj" =>
                        "1. Natutukoy ang mga uri ng komunidad (lungsod, bayan, baryo) at ang kanilang katangian.\n2. Nailarawan ang mga pasilidad at serbisyong makikita sa bawat uri ng komunidad.\n3. Naipapakita ang pagpapahalaga sa sariling komunidad sa pamamagitan ng mga gawain.",
                    "mat" =>
                        "- Mapa ng komunidad (1 bawat pangkat)\n- Mga larawan ng lungsod, bayan, at baryo\n- Graphic organizer worksheet\n- Pisara at tisa / whiteboard markers\n- Mga larawan ng pasilidad sa komunidad",
                ],
                [
                    "key" => "mapeh-om",
                    "color" => "pink",
                    "label" => "MP",
                    "title" => "MAPEH — Music",
                    "obj" =>
                        "1. Identify the elements of music: melody, rhythm, and dynamics in a given song.\n2. Perform the song with correct pitch, rhythm, and appropriate dynamics.\n3. Appreciate the cultural significance of Filipino folk songs.",
                    "mat" =>
                        "- Audio recording of a Filipino folk song\n- Song lyrics (printed, 1 per student)\n- Simple percussion instruments (tambourine, claves, or improvised)\n- Musical notation chart\n- Whiteboard and markers",
                ],
                [
                    "key" => "esp-om",
                    "color" => "indigo",
                    "label" => "EP",
                    "title" => "EsP — Pagpapahalaga",
                    "obj" =>
                        "1. Natutukoy ang kahulugan at kahalagahan ng pagpapahalaga na tatalakayin.\n2. Naipapakita ang pagpapahalaga sa pamamagitan ng mga kongkretong halimbawa sa paaralan at tahanan.\n3. Naipapahayag ang personal na pangako na isasabuhay ang pagpapahalaga sa araw-araw na buhay.",
                    "mat" =>
                        "- Mga sitwasyon cards (5 sitwasyon)\n- Graphic organizer worksheet\n- Mga larawan na nagpapakita ng mabuting pagpapahalaga\n- Pisara at tisa / whiteboard markers\n- Pangako card (1 bawat mag-aaral)",
                ],
            ];
            foreach ($objMatTemplates as $t): ?>
            <div class="border border-gray-200 rounded-xl overflow-hidden">
                <button type="button"
                    data-obj="<?= htmlspecialchars($t["obj"], ENT_QUOTES) ?>"
                    data-mat="<?= htmlspecialchars($t["mat"], ENT_QUOTES) ?>"
                    class="obj-mat-tpl-btn w-full flex items-center justify-between px-4 py-3 bg-<?= $t[
                        "color"
                    ] ?>-50 hover:bg-<?= $t[
    "color"
] ?>-100 transition-colors text-left">
                    <div class="flex items-center gap-3">
                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-<?= $t[
                            "color"
                        ] ?>-600 text-white text-xs font-bold"><?= $t[
    "label"
] ?></span>
                        <p class="text-sm font-semibold text-gray-800"><?= htmlspecialchars(
                            $t["title"],
                        ) ?></p>
                    </div>
                    <svg class="w-4 h-4 text-<?= $t[
                        "color"
                    ] ?>-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </button>
            </div>
            <?php endforeach;
            ?>
        </div>
    </div>
</div>

<!-- ── Objectives & Materials Template JS ────────────────────────────────── -->
<script>
(function () {
    'use strict';
    var modal    = document.getElementById('obj-mat-modal');
    var backdrop = document.getElementById('obj-mat-backdrop');
    var openBtn  = document.getElementById('load-obj-mat-template-btn');
    var closeBtn = document.getElementById('close-obj-mat-modal-btn');

    function open()  { modal.classList.remove('hidden'); modal.classList.add('flex'); document.body.style.overflow = 'hidden'; }
    function close() { modal.classList.add('hidden'); modal.classList.remove('flex'); document.body.style.overflow = ''; }

    if (openBtn)  openBtn.addEventListener('click', open);
    if (closeBtn) closeBtn.addEventListener('click', close);
    if (backdrop) backdrop.addEventListener('click', close);
    document.addEventListener('keydown', function(e) { if (e.key === 'Escape') close(); });

    document.querySelectorAll('.obj-mat-tpl-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            var objEl = document.getElementById('learning_objectives');
            var matEl = document.getElementById('materials_needed');
            var hasContent = (objEl && objEl.value.trim() !== '') || (matEl && matEl.value.trim() !== '');
            if (hasContent && !confirm('This will replace existing objectives and materials. Continue?')) return;
            if (objEl) objEl.value = btn.getAttribute('data-obj');
            if (matEl) matEl.value = btn.getAttribute('data-mat');
            close();
        });
    });
})();
</script>

<!-- ── Save as Template Modal ────────────────────────────────────────────── -->
<div id="demo-save-tpl-modal" class="fixed inset-0 z-50 hidden items-center justify-center p-4" role="dialog" aria-modal="true">
    <div id="demo-save-tpl-backdrop" class="absolute inset-0 bg-black/50"></div>
    <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md">
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
            <h3 class="text-base font-semibold text-gray-800">Save Demo as Template</h3>
            <button type="button" id="close-demo-save-tpl-btn" class="inline-flex items-center justify-center w-8 h-8 rounded-md text-gray-400 hover:bg-gray-100 hover:text-gray-600 transition-colors focus:outline-none focus:ring-2 focus:ring-gray-400">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <div class="px-6 py-5">
            <p class="text-sm text-gray-500 mb-4">Give this template a name so you can reuse it for future demos.</p>
            <label for="demo-tpl-name-input" class="block text-sm font-medium text-gray-700 mb-1">Template Name <span class="text-red-500">*</span></label>
            <input type="text" id="demo-tpl-name-input" autocomplete="off"
                class="w-full rounded-xl border border-gray-300 px-3 py-2.5 text-sm text-gray-900 shadow-sm focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                placeholder="e.g. Grade 3 Math — Fractions Demo">
            <p id="demo-tpl-error" class="mt-1 text-sm text-red-600 hidden">Template name is required.</p>
        </div>
        <div class="px-6 pb-5 flex gap-3 justify-end">
            <button type="button" id="cancel-demo-save-tpl-btn" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 text-sm font-medium rounded-xl transition-colors">Cancel</button>
            <button type="button" id="confirm-demo-save-tpl-btn" class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium rounded-xl transition-colors">Save Template</button>
        </div>
    </div>
</div>

<!-- Toast -->
<div id="demo-tpl-toast" class="fixed bottom-6 right-6 z-50 hidden">
    <div class="flex items-center gap-3 bg-green-600 text-white px-5 py-3 rounded-xl shadow-lg text-sm font-medium">
        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        <span id="demo-tpl-toast-msg">Template saved!</span>
    </div>
</div>

<!-- ── Save as Template + Auto-load JS ───────────────────────────────────── -->
<script>
(function () {
    'use strict';

    var modal       = document.getElementById('demo-save-tpl-modal');
    var backdrop    = document.getElementById('demo-save-tpl-backdrop');
    var openBtn     = document.getElementById('demo-save-as-template-btn');
    var closeBtn    = document.getElementById('close-demo-save-tpl-btn');
    var cancelBtn   = document.getElementById('cancel-demo-save-tpl-btn');
    var confirmBtn  = document.getElementById('confirm-demo-save-tpl-btn');
    var nameInput   = document.getElementById('demo-tpl-name-input');
    var errEl       = document.getElementById('demo-tpl-error');
    var toast       = document.getElementById('demo-tpl-toast');
    var toastMsg    = document.getElementById('demo-tpl-toast-msg');

    function openModal()  { modal.classList.remove('hidden'); modal.classList.add('flex'); document.body.style.overflow='hidden'; if(nameInput) nameInput.focus(); }
    function closeModal() { modal.classList.add('hidden'); modal.classList.remove('flex'); document.body.style.overflow=''; if(errEl) errEl.classList.add('hidden'); }

    if (openBtn)   openBtn.addEventListener('click', openModal);
    if (closeBtn)  closeBtn.addEventListener('click', closeModal);
    if (cancelBtn) cancelBtn.addEventListener('click', closeModal);
    if (backdrop)  backdrop.addEventListener('click', closeModal);
    document.addEventListener('keydown', function(e){ if(e.key==='Escape') closeModal(); });

    function showToast(msg) {
        if (!toast || !toastMsg) return;
        toastMsg.textContent = msg;
        toast.classList.remove('hidden');
        setTimeout(function(){ toast.classList.add('hidden'); }, 3500);
    }

    if (confirmBtn) {
        confirmBtn.addEventListener('click', function () {
            var name = nameInput ? nameInput.value.trim() : '';
            if (!name) { if(errEl) errEl.classList.remove('hidden'); return; }
            if (errEl) errEl.classList.add('hidden');

            var form = document.querySelector('form[method="POST"]');
            if (!form) return;
            var fd = new FormData(form);
            fd.set('template_name', name);

            confirmBtn.disabled = true;
            confirmBtn.textContent = 'Saving…';

            fetch('<?= url('/demo-templates/save-from-demo') ?>', { method: 'POST', body: fd })
                .then(function(r){ return r.json(); })
                .then(function(data){
                    confirmBtn.disabled = false;
                    confirmBtn.textContent = 'Save Template';
                    if (data.success) {
                        closeModal();
                        if (nameInput) nameInput.value = '';
                        showToast('Template "' + data.name + '" saved!');
                    } else {
                        alert('Error: ' + (data.error || 'Could not save template.'));
                    }
                })
                .catch(function(){
                    confirmBtn.disabled = false;
                    confirmBtn.textContent = 'Save Template';
                    alert('Network error. Please try again.');
                });
        });
    }

    // Auto-load template from URL ?demo_template=ID or ?builtin_demo=KEY
    (function autoLoad() {
        var params     = new URLSearchParams(window.location.search);
        var tplId      = params.get('demo_template');
        var builtinKey = params.get('builtin_demo');

        if (builtinKey && typeof demoTemplates !== 'undefined' && demoTemplates[builtinKey]) {
            var tpl = demoTemplates[builtinKey];
            function setFld(id,val){var el=document.getElementById(id);if(el&&val)el.value=val;}
            setFld('introduction',tpl.intro);
            setFld('generalization',tpl.generalization);
            setFld('application',tpl.application);
            setFld('assessment',tpl.assessment);
            if(tpl.steps){
                var c=document.getElementById('steps-container');
                var h=document.getElementById('steps-empty-hint');
                if(c){
                    c.querySelectorAll('.step-row').forEach(function(r){r.remove();});
                    if(h)h.style.display='none';
                    tpl.steps.forEach(function(s,idx){
                        var num=idx+1;var row=document.createElement('div');
                        row.className='step-row flex items-start gap-3 p-3 bg-gray-50 rounded-lg border border-gray-200';
                        row.innerHTML='<span class=step-number-badge>'+num+'</span><input type=hidden class=step-number-input name=steps['+idx+'][step_number] value='+num+'><textarea name=steps['+idx+'][description] rows=2 class=flex-1 rounded-lg border border-gray-300 px-3 py-2 text-sm></textarea><button type=button class=remove-step-btn>X</button>';
                        c.appendChild(row);
                        var ta=row.querySelector('textarea');if(ta)ta.value=s.desc;
                    });
                }
            }
            showToast('Built-in template loaded!');
            return;
        }

        if (!tplId) return;

        fetch('<?= url('/demo-templates/') ?>' + encodeURIComponent(tplId) + '/apply')
            .then(function(r){ return r.json(); })
            .then(function(data){
                if (!data.success || !data.template) return;
                var t = data.template;

                function setField(id, val) { var el=document.getElementById(id); if(el&&val) el.value=val; }
                function setSelect(id, val) {
                    var el=document.getElementById(id);
                    if(!el||!val) return;
                    for(var i=0;i<el.options.length;i++){
                        if(el.options[i].value===String(val)){el.selectedIndex=i;break;}
                    }
                }

                setSelect('subject',           t.subject_tpl);
                setSelect('grade_level',        t.grade_level_tpl);
                setSelect('duration_minutes',   t.duration_minutes_tpl);
                setField('learning_objectives', t.learning_objectives_tpl);
                setField('materials_needed',    t.materials_needed_tpl);
                setField('introduction',        t.introduction_tpl);
                setField('generalization',      t.generalization_tpl);
                setField('application',         t.application_tpl);
                setField('assessment',          t.assessment_tpl);

                // Load steps
                if (t.steps_tpl) {
                    try {
                        var steps = JSON.parse(t.steps_tpl);
                        if (Array.isArray(steps) && steps.length > 0) {
                            var container = document.getElementById('steps-container');
                            var hint      = document.getElementById('steps-empty-hint');
                            if (container) {
                                container.querySelectorAll('.step-row').forEach(function(r){ r.remove(); });
                                if (hint) hint.style.display = 'none';
                                steps.forEach(function(desc, idx) {
                                    var num = idx + 1;
                                    var row = document.createElement('div');
                                    row.className = 'step-row flex items-start gap-3 p-3 bg-gray-50 rounded-lg border border-gray-200';
                                    row.innerHTML =
                                        '<span class="step-number-badge flex-shrink-0 inline-flex items-center justify-center w-7 h-7 rounded-full bg-blue-100 text-blue-700 text-xs font-bold mt-1">'+num+'</span>'
                                        +'<input type="hidden" class="step-number-input" name="steps['+idx+'][step_number]" value="'+num+'">'
                                        +'<textarea name="steps['+idx+'][description]" rows="2" class="flex-1 rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-900 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition resize-y" aria-label="Step '+num+' description"></textarea>'
                                        +'<button type="button" class="remove-step-btn flex-shrink-0 inline-flex items-center justify-center w-7 h-7 rounded-md text-red-500 hover:bg-red-50 hover:text-red-700 transition-colors focus:outline-none focus:ring-2 focus:ring-red-400 mt-1"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>';
                                    container.appendChild(row);
                                    var ta = row.querySelector('textarea');
                                    if (ta) ta.value = desc;
                                });
                            }
                        }
                    } catch(e) {}
                }

                showToast('Template "' + (t.name || '') + '" loaded!');
            })
            .catch(function(){});
    })();

})();
</script>

<!-- ── Demo Lesson Flow Template Modal ──────────────────────────────────── -->
<div id="demo-template-modal"
     class="fixed inset-0 z-50 hidden items-center justify-center p-4"
     role="dialog" aria-modal="true" aria-labelledby="demo-modal-title">
    <div id="demo-modal-backdrop" class="absolute inset-0 bg-black/50"></div>
    <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200 sticky top-0 bg-white rounded-t-2xl">
            <div>
                <h3 id="demo-modal-title" class="text-base font-semibold text-gray-800">Load Lesson Flow Template</h3>
                <p class="text-xs text-gray-500 mt-0.5">Fills Introduction, Generalization, Application, and Assessment. Steps use 4A's or 5E's structure.</p>
            </div>
            <button type="button" id="close-demo-modal-btn"
                class="inline-flex items-center justify-center w-8 h-8 rounded-md text-gray-400 hover:bg-gray-100 hover:text-gray-600 transition-colors focus:outline-none focus:ring-2 focus:ring-gray-400">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <div class="p-6 space-y-3">
            <div class="border border-gray-200 rounded-xl overflow-hidden">
                <button type="button" data-demo-template="math" class="w-full flex items-center justify-between px-4 py-3 bg-blue-50 hover:bg-blue-100 transition-colors text-left">
                    <div class="flex items-center gap-3">
                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-blue-600 text-white text-xs font-bold">M</span>
                        <div><p class="text-sm font-semibold text-gray-800">Mathematics — Fractions (4A's)</p><p class="text-xs text-gray-500">Activity → Analysis → Abstraction → Application</p></div>
                    </div>
                    <svg class="w-4 h-4 text-blue-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </button>
            </div>
            <div class="border border-gray-200 rounded-xl overflow-hidden">
                <button type="button" data-demo-template="english" class="w-full flex items-center justify-between px-4 py-3 bg-green-50 hover:bg-green-100 transition-colors text-left">
                    <div class="flex items-center gap-3">
                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-green-600 text-white text-xs font-bold">E</span>
                        <div><p class="text-sm font-semibold text-gray-800">English — Reading Comprehension (5E's)</p><p class="text-xs text-gray-500">Engage → Explore → Explain → Elaborate → Evaluate</p></div>
                    </div>
                    <svg class="w-4 h-4 text-green-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </button>
            </div>
            <div class="border border-gray-200 rounded-xl overflow-hidden">
                <button type="button" data-demo-template="science" class="w-full flex items-center justify-between px-4 py-3 bg-teal-50 hover:bg-teal-100 transition-colors text-left">
                    <div class="flex items-center gap-3">
                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-teal-600 text-white text-xs font-bold">S</span>
                        <div><p class="text-sm font-semibold text-gray-800">Science — Living Things (5E's)</p><p class="text-xs text-gray-500">Engage → Explore → Explain → Elaborate → Evaluate</p></div>
                    </div>
                    <svg class="w-4 h-4 text-teal-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </button>
            </div>
            <div class="border border-gray-200 rounded-xl overflow-hidden">
                <button type="button" data-demo-template="filipino" class="w-full flex items-center justify-between px-4 py-3 bg-yellow-50 hover:bg-yellow-100 transition-colors text-left">
                    <div class="flex items-center gap-3">
                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-yellow-600 text-white text-xs font-bold">F</span>
                        <div><p class="text-sm font-semibold text-gray-800">Filipino — Pagbabasa (4A's)</p><p class="text-xs text-gray-500">Aktibidad → Pagsusuri → Abstraksiyon → Aplikasyon</p></div>
                    </div>
                    <svg class="w-4 h-4 text-yellow-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </button>
            </div>
            <div class="border border-gray-200 rounded-xl overflow-hidden">
                <button type="button" data-demo-template="generic" class="w-full flex items-center justify-between px-4 py-3 bg-gray-50 hover:bg-gray-100 transition-colors text-left">
                    <div class="flex items-center gap-3">
                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-gray-600 text-white text-xs font-bold">G</span>
                        <div><p class="text-sm font-semibold text-gray-800">Generic DepEd Demo Template</p><p class="text-xs text-gray-500">Standard structure with guiding prompts for all sections</p></div>
                    </div>
                    <svg class="w-4 h-4 text-gray-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- ── Dynamic step list JavaScript (Requirement 3.7) ──────────────────────── -->
<script>
(function () {
    'use strict';

    var container  = document.getElementById('steps-container');
    var addBtn     = document.getElementById('add-step-btn');
    var emptyHint  = document.getElementById('steps-empty-hint');

    /**
     * Return the current number of step rows in the container.
     */
    function stepCount() {
        return container.querySelectorAll('.step-row').length;
    }

    /**
     * Re-index all step rows so that:
     *   - The badge shows the correct step number (1-based).
     *   - The hidden input value matches the badge.
     *   - The name attributes use the correct 0-based array index.
     *   - The textarea placeholder and aria-label are updated.
     */
    function reindex() {
        var rows = container.querySelectorAll('.step-row');
        rows.forEach(function (row, idx) {
            var stepNum    = idx + 1;
            var badge      = row.querySelector('.step-number-badge');
            var numInput   = row.querySelector('.step-number-input');
            var textarea   = row.querySelector('textarea');
            var removeBtn  = row.querySelector('.remove-step-btn');

            if (badge)     badge.textContent = stepNum;
            if (numInput) {
                numInput.name  = 'steps[' + idx + '][step_number]';
                numInput.value = stepNum;
            }
            if (textarea) {
                textarea.name        = 'steps[' + idx + '][description]';
                textarea.placeholder = 'Describe step ' + stepNum + '\u2026';
                textarea.setAttribute('aria-label', 'Step ' + stepNum + ' description');
            }
            if (removeBtn) {
                removeBtn.setAttribute('aria-label', 'Remove step ' + stepNum);
            }
        });
    }

    /**
     * Show or hide the empty-state hint paragraph.
     */
    function toggleEmptyHint() {
        if (!emptyHint) return;
        if (stepCount() === 0) {
            emptyHint.style.display = '';
        } else {
            emptyHint.style.display = 'none';
        }
    }

    /**
     * Append a new blank step row to the container.
     */
    function addStep() {
        // Hide the empty hint as soon as the first step is added.
        if (emptyHint) emptyHint.style.display = 'none';

        var idx     = stepCount();
        var stepNum = idx + 1;

        var row = document.createElement('div');
        row.className = 'step-row flex items-start gap-3 p-3 bg-gray-50 rounded-lg border border-gray-200';

        row.innerHTML =
            '<span class="step-number-badge flex-shrink-0 inline-flex items-center justify-center w-7 h-7 rounded-full bg-blue-100 text-blue-700 text-xs font-bold mt-1">'
            + stepNum
            + '</span>'
            + '<input type="hidden" class="step-number-input" name="steps[' + idx + '][step_number]" value="' + stepNum + '">'
            + '<textarea'
            + ' name="steps[' + idx + '][description]"'
            + ' rows="2"'
            + ' class="flex-1 rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-900 placeholder-gray-400 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition resize-y"'
            + ' placeholder="Describe step ' + stepNum + '\u2026"'
            + ' aria-label="Step ' + stepNum + ' description"'
            + '></textarea>'
            + '<button type="button"'
            + ' class="remove-step-btn flex-shrink-0 inline-flex items-center justify-center w-7 h-7 rounded-md text-red-500 hover:bg-red-50 hover:text-red-700 transition-colors focus:outline-none focus:ring-2 focus:ring-red-400 mt-1"'
            + ' aria-label="Remove step ' + stepNum + '">'
            + '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">'
            + '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />'
            + '</svg>'
            + '</button>';

        container.appendChild(row);

        // Focus the new textarea for immediate input.
        var ta = row.querySelector('textarea');
        if (ta) ta.focus();
    }

    /**
     * Remove the step row that contains the clicked remove button,
     * then re-index the remaining rows.
     *
     * @param {HTMLElement} btn  The remove button that was clicked.
     */
    function removeStep(btn) {
        var row = btn.closest('.step-row');
        if (row) {
            row.remove();
            reindex();
            toggleEmptyHint();
        }
    }

    /**
     * Clear all existing step rows from the container.
     */
    function clearSteps() {
        var rows = container.querySelectorAll('.step-row');
        rows.forEach(function (row) { row.remove(); });
        toggleEmptyHint();
    }

    /**
     * Add a step row pre-filled with the given step number and description.
     */
    function addStepWithContent(num, desc) {
        if (emptyHint) emptyHint.style.display = 'none';

        var idx = stepCount();

        var row = document.createElement('div');
        row.className = 'step-row flex items-start gap-3 p-3 bg-gray-50 rounded-lg border border-gray-200';

        row.innerHTML =
            '<span class="step-number-badge flex-shrink-0 inline-flex items-center justify-center w-7 h-7 rounded-full bg-blue-100 text-blue-700 text-xs font-bold mt-1">'
            + num
            + '</span>'
            + '<input type="hidden" class="step-number-input" name="steps[' + idx + '][step_number]" value="' + num + '">'
            + '<textarea'
            + ' name="steps[' + idx + '][description]"'
            + ' rows="3"'
            + ' class="flex-1 rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-900 placeholder-gray-400 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition resize-y"'
            + ' aria-label="Step ' + num + ' description"'
            + '></textarea>'
            + '<button type="button"'
            + ' class="remove-step-btn flex-shrink-0 inline-flex items-center justify-center w-7 h-7 rounded-md text-red-500 hover:bg-red-50 hover:text-red-700 transition-colors focus:outline-none focus:ring-2 focus:ring-red-400 mt-1"'
            + ' aria-label="Remove step ' + num + '">'
            + '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">'
            + '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />'
            + '</svg>'
            + '</button>';

        container.appendChild(row);

        // Set textarea value safely (avoids XSS via innerHTML)
        var ta = row.querySelector('textarea');
        if (ta) ta.value = desc;
    }

    /**
     * Load the 4A's lesson template.
     */
    function load4AsTemplate() {
        if (stepCount() > 0 && !confirm('This will replace existing steps. Continue?')) return;
        clearSteps();
        var templates = [
            {num: 1, label: 'Activity',    desc: 'Conduct an activity to introduce the concept. Example: Have students work in groups to explore fraction strips and identify equal parts.'},
            {num: 2, label: 'Analysis',    desc: 'Guide students to analyze the activity results. Ask: What did you notice? What patterns did you find? How are the parts related to the whole?'},
            {num: 3, label: 'Abstraction', desc: 'Lead students to form the concept/generalization. State the rule or definition: A fraction represents equal parts of a whole. The numerator tells how many parts; the denominator tells the total equal parts.'},
            {num: 4, label: 'Application', desc: 'Apply the concept through exercises. Have students solve problems independently or in pairs using the concept learned.'}
        ];
        templates.forEach(function (t) { addStepWithContent(t.num, '[' + t.label + '] ' + t.desc); });
    }

    /**
     * Load the 5E's lesson template.
     */
    function load5EsTemplate() {
        if (stepCount() > 0 && !confirm('This will replace existing steps. Continue?')) return;
        clearSteps();
        var templates = [
            {num: 1, label: 'Engage',    desc: 'Capture students\' interest and activate prior knowledge. Present a real-world problem or question that connects to the lesson topic.'},
            {num: 2, label: 'Explore',   desc: 'Allow students to investigate the concept through hands-on activities. Students work in groups to discover patterns and relationships.'},
            {num: 3, label: 'Explain',   desc: 'Students share their findings and the teacher clarifies concepts. Introduce formal vocabulary and definitions. Correct any misconceptions.'},
            {num: 4, label: 'Elaborate', desc: 'Extend understanding by applying the concept to new situations. Students solve more complex problems or connect the concept to other topics.'},
            {num: 5, label: 'Evaluate',  desc: 'Assess student understanding through formative assessment. Use exit tickets, short quizzes, or performance tasks to check mastery.'}
        ];
        templates.forEach(function (t) { addStepWithContent(t.num, '[' + t.label + '] ' + t.desc); });
    }

    // ── Event listeners ──────────────────────────────────────────────────────

    // "Add Step" button
    addBtn.addEventListener('click', addStep);

    // Template buttons
    var btn4As = document.getElementById('load-4as-btn');
    var btn5Es = document.getElementById('load-5es-btn');
    if (btn4As) btn4As.addEventListener('click', load4AsTemplate);
    if (btn5Es) btn5Es.addEventListener('click', load5EsTemplate);

    // Delegated click handler for all "Remove" buttons (including those
    // rendered server-side for existing steps).
    container.addEventListener('click', function (e) {
        var btn = e.target.closest('.remove-step-btn');
        if (btn) removeStep(btn);
    });

    // Initialise the empty-hint visibility based on server-rendered rows.
    toggleEmptyHint();

})();
</script>

<!-- ── Demo Template Modal + Completeness + Word Count JS ──────────────────── -->
<script>
(function () {
    'use strict';

    // ── Demo lesson flow templates ────────────────────────────────────────────
    var demoTemplates = {
        math: {
            intro:          'Show a pizza cut into 8 equal slices. Ask: "If you eat 3 slices, what part of the pizza did you eat?" Let students share answers. Guide them to discover the concept of fractions through the familiar context of sharing food.',
            generalization: 'Ask: "What is a fraction?" Guide students to conclude: A fraction represents equal parts of a whole. The bottom number (denominator) tells how many equal parts, and the top number (numerator) tells how many parts we are talking about.',
            application:    'Group Activity: Give each group a set of fraction cards and a number line (0 to 1). Students arrange the fraction cards from smallest to largest and paste them on the number line. Each group presents their work and explains their arrangement.',
            assessment:     'Individual written quiz: Identify and shade the correct fraction in 5 diagrams (2 pts each = 10 pts). Identify the numerator and denominator in 5 fractions (1 pt each = 5 pts). Total: 15 points. Mastery: 12/15.',
            steps: [
                {num:1, desc:'[Activity] Have students work in groups with fraction strips. Each group identifies and names the equal parts (1/2, 1/3, 1/4). Groups record their findings on a chart.'},
                {num:2, desc:'[Analysis] Guide students to analyze: What did you notice about the fractions? How does the denominator change the size of each part? What happens when the numerator increases?'},
                {num:3, desc:'[Abstraction] Lead students to state the rule: A fraction has a numerator (top) and denominator (bottom). The denominator tells the total equal parts; the numerator tells how many parts are taken.'},
                {num:4, desc:'[Application] Students solve 5 fraction problems on the board and in their notebooks. Call on volunteers to explain their answers to the class.'}
            ]
        },
        english: {
            intro:          'Show the cover of a short story. Ask: "What do you think this story is about? What clues does the cover give you?" Let students predict the story. Tell them they will read to find out if their predictions are correct.',
            generalization: 'Ask: "What do good readers do when they read a story?" Guide students to state: Good readers identify the characters, setting, problem, and solution. They use details from the text to support their understanding.',
            application:    'Individual Activity: Students answer a short comprehension worksheet with 5 questions about the story. Then they write 2–3 sentences describing their favorite part and why they liked it.',
            assessment:     'Oral recitation: Call on 5 students to retell the story in their own words. Written: Students answer 5 comprehension questions (2 pts each = 10 pts). Mastery: 8/10.',
            steps: [
                {num:1, desc:'[Engage] Show the story cover and ask prediction questions. Play a short audio clip or show a related picture to build interest and activate prior knowledge.'},
                {num:2, desc:'[Explore] Read the story aloud while students follow along. Students underline unfamiliar words and note key events on a graphic organizer.'},
                {num:3, desc:'[Explain] Discuss the story elements: characters, setting, problem, solution. Clarify vocabulary. Students share their graphic organizers with a partner.'},
                {num:4, desc:'[Elaborate] Students connect the story to their own experiences. Ask: "Has something like this ever happened to you? What would you have done differently?"'},
                {num:5, desc:'[Evaluate] Students answer comprehension questions and write a brief summary of the story in 3–4 sentences.'}
            ]
        },
        science: {
            intro:          'Bring a small potted plant and a rock to class. Ask: "How are these two objects different? What can the plant do that the rock cannot?" Let students observe and share their observations.',
            generalization: 'Ask: "What makes something a living thing?" Guide students to state: Living things share common characteristics — they grow, need food/water/air, respond to their environment, and can reproduce. Non-living things do not have these characteristics.',
            application:    'Activity: Students sort a set of picture cards into two groups — Living Things and Non-Living Things. They write one sentence explaining why each item belongs in its group. Share and discuss as a class.',
            assessment:     'Written test: Classify 10 pictures as living or non-living (1 pt each = 10 pts). Give 2 characteristics of living things (2 pts each = 4 pts). Total: 14 points. Mastery: 11/14.',
            steps: [
                {num:1, desc:'[Engage] Present the plant and rock. Ask students to observe and list 3 differences. Introduce the question: "What makes something alive?"'},
                {num:2, desc:'[Explore] Students observe 4 objects (plant, rock, insect picture, toy). They record observations in a table: Does it grow? Does it need food? Does it move on its own?'},
                {num:3, desc:'[Explain] Discuss findings. Introduce the 4 characteristics of living things. Correct misconceptions (e.g., fire moves but is not alive).'},
                {num:4, desc:'[Elaborate] Students think of 3 more examples of living things and 3 non-living things not shown in class. They explain their choices.'},
                {num:5, desc:'[Evaluate] Students complete a classification activity and answer: "Why is a seed considered a living thing even though it does not move?"'}
            ]
        },
        filipino: {
            intro:          'Magpakita ng larawan na may kaugnayan sa paksa. Tanungin: "Ano ang nakikita ninyo sa larawan? Ano ang nararamdaman ng taong nasa larawan?" Hayaan ang mga mag-aaral na ibahagi ang kanilang mga sagot.',
            generalization: 'Tanungin: "Ano ang natutuhan natin ngayon?" Gabayan ang mga mag-aaral na sabihin: Ang isang kwento ay may mga tauhan, tagpuan, suliranin, at solusyon. Ang pag-unawa sa mga bahaging ito ay tumutulong sa atin na maunawaan ang mensahe ng kwento.',
            application:    'Pangkatang Gawain: Ang bawat pangkat ay gagawa ng maikling buod ng kwento gamit ang graphic organizer (tauhan, tagpuan, suliranin, solusyon). Ipapakita ng bawat pangkat ang kanilang gawa sa klase.',
            assessment:     'Pasalitang pagtatasa: Tatlong mag-aaral ang magkukwento muli ng maikling kwento. Nakasulat: Sagutin ang 5 tanong tungkol sa kwento (2 puntos bawat isa = 10 puntos). Mastery: 8/10.',
            steps: [
                {num:1, desc:'[Aktibidad] Basahin ang maikling kwento nang malakas. Ang mga mag-aaral ay susundan ang pagbabasa at magtatala ng mga pangunahing pangyayari.'},
                {num:2, desc:'[Pagsusuri] Gabayan ang mga mag-aaral na suriin ang kwento: Sino ang mga tauhan? Saan naganap ang kwento? Ano ang suliranin? Paano ito nalutas?'},
                {num:3, desc:'[Abstraksiyon] Ipaliwanag ang mga bahagi ng kwento: tauhan, tagpuan, suliranin, at solusyon. Isulat ang mga ito sa pisara at basahin ng klase nang sabay-sabay.'},
                {num:4, desc:'[Aplikasyon] Ang mga mag-aaral ay susulat ng maikling buod ng kwento gamit ang graphic organizer. Ibabahagi nila ang kanilang gawa sa kanilang katabi.'}
            ]
        },
        generic: {
            intro:          'Motivate students by showing a picture, video clip, or real object related to the topic. Ask a thought-provoking question: "What do you already know about [topic]? What do you want to find out?" Let students share their prior knowledge.',
            generalization: 'Lead students to form the generalization. Ask: "What did we learn today? What is the main idea?" Have students state the concept/rule/principle in their own words. Write the generalization on the board and have the class read it together.',
            application:    'Apply the learning through an individual or group activity that requires students to use the new knowledge. Could be a worksheet, problem set, creative task, or performance activity. Have selected students share their work with the class.',
            assessment:     'Assess student understanding through a short formative assessment (exit ticket, quiz, or performance task). Check for mastery of the learning objectives. Provide feedback and address common errors.',
            steps: [
                {num:1, desc:'[Introduction] Present the new concept using visual aids, demonstrations, or examples. State the learning objectives clearly. Check prior knowledge.'},
                {num:2, desc:'[Development] Explain the concept step by step. Use the board to write key terms and examples. Ask comprehension check questions throughout.'},
                {num:3, desc:'[Guided Practice] Work through 2–3 examples together as a class. Call on students to participate. Correct errors immediately and gently.'},
                {num:4, desc:'[Independent Practice] Students work individually or in pairs on practice problems. Circulate and provide feedback. Note common errors for class discussion.'}
            ]
        }
    };

    // ── Modal open/close ──────────────────────────────────────────────────────
    var modal    = document.getElementById('demo-template-modal');
    var backdrop = document.getElementById('demo-modal-backdrop');
    var openBtn  = document.getElementById('load-demo-template-btn');
    var closeBtn = document.getElementById('close-demo-modal-btn');

    function openModal()  { modal.classList.remove('hidden'); modal.classList.add('flex'); document.body.style.overflow = 'hidden'; }
    function closeModal() { modal.classList.add('hidden'); modal.classList.remove('flex'); document.body.style.overflow = ''; }

    if (openBtn)  openBtn.addEventListener('click', openModal);
    if (closeBtn) closeBtn.addEventListener('click', closeModal);
    if (backdrop) backdrop.addEventListener('click', closeModal);
    document.addEventListener('keydown', function (e) { if (e.key === 'Escape') closeModal(); });

    // ── Apply demo template ───────────────────────────────────────────────────
    document.querySelectorAll('[data-demo-template]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var key = btn.getAttribute('data-demo-template');
            var tpl = demoTemplates[key];
            if (!tpl) return;

            var introEl  = document.getElementById('introduction');
            var genEl    = document.getElementById('generalization');
            var appEl    = document.getElementById('application');
            var assEl    = document.getElementById('assessment');

            var hasContent = [introEl, genEl, appEl, assEl].some(function (f) {
                return f && f.value.trim() !== '';
            });

            if (hasContent && !confirm('This will replace existing lesson flow content. Continue?')) return;

            if (introEl) introEl.value = tpl.intro;
            if (genEl)   genEl.value   = tpl.generalization;
            if (appEl)   appEl.value   = tpl.application;
            if (assEl)   assEl.value   = tpl.assessment;

            // Load steps
            var container = document.getElementById('steps-container');
            var emptyHint = document.getElementById('steps-empty-hint');
            if (container) {
                container.querySelectorAll('.step-row').forEach(function (r) { r.remove(); });
                if (emptyHint) emptyHint.style.display = 'none';
                tpl.steps.forEach(function (s) {
                    var idx = container.querySelectorAll('.step-row').length;
                    var row = document.createElement('div');
                    row.className = 'step-row flex items-start gap-3 p-3 bg-gray-50 rounded-lg border border-gray-200';
                    row.innerHTML =
                        '<span class="step-number-badge flex-shrink-0 inline-flex items-center justify-center w-7 h-7 rounded-full bg-blue-100 text-blue-700 text-xs font-bold mt-1">' + s.num + '</span>'
                        + '<input type="hidden" class="step-number-input" name="steps[' + idx + '][step_number]" value="' + s.num + '">'
                        + '<textarea name="steps[' + idx + '][description]" rows="3" class="flex-1 rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-900 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition resize-y" aria-label="Step ' + s.num + ' description"></textarea>'
                        + '<button type="button" class="remove-step-btn flex-shrink-0 inline-flex items-center justify-center w-7 h-7 rounded-md text-red-500 hover:bg-red-50 hover:text-red-700 transition-colors focus:outline-none focus:ring-2 focus:ring-red-400 mt-1" aria-label="Remove step ' + s.num + '"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>';
                    container.appendChild(row);
                    var ta = row.querySelector('textarea');
                    if (ta) ta.value = s.desc;
                });
            }

            closeModal();
            updateCompleteness();
        });
    });

    // ── Word count on textareas ───────────────────────────────────────────────
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

    // ── Completeness indicator ────────────────────────────────────────────────
    var completenessFields = [
        { id: 'title',               label: 'Title',               required: true  },
        { id: 'subject',             label: 'Subject',             required: false },
        { id: 'grade_level',         label: 'Grade Level',         required: false },
        { id: 'learning_objectives', label: 'Learning Objectives', required: true  },
        { id: 'materials_needed',    label: 'Materials',           required: false },
        { id: 'introduction',        label: 'Introduction',        required: false },
        { id: 'generalization',      label: 'Generalization',      required: false },
        { id: 'application',         label: 'Application',         required: false },
        { id: 'assessment',          label: 'Assessment',          required: false },
    ];

    var bar       = document.getElementById('completeness-bar');
    var pct       = document.getElementById('completeness-pct');
    var checklist = document.getElementById('completeness-checklist');

    function updateCompleteness() {
        if (!bar || !pct || !checklist) return;
        var filled = 0;
        var html   = '';
        completenessFields.forEach(function (f) {
            var el   = document.getElementById(f.id);
            var done = el && el.value.trim() !== '';
            if (done) filled++;
            var color = done ? 'text-green-600' : (f.required ? 'text-red-500' : 'text-gray-400');
            var icon  = done ? '✓' : (f.required ? '✗' : '○');
            html += '<span class="' + color + '">' + icon + ' ' + f.label + '</span>';
        });
        var percent = Math.round((filled / completenessFields.length) * 100);
        bar.style.width = percent + '%';
        bar.className   = 'h-2.5 rounded-full transition-all duration-300 ' + (percent === 100 ? 'bg-green-500' : percent >= 60 ? 'bg-blue-600' : 'bg-yellow-500');
        pct.textContent = percent + '%';
        pct.className   = 'text-sm font-semibold ' + (percent === 100 ? 'text-green-600' : 'text-blue-600');
        checklist.innerHTML = html;
    }

    completenessFields.forEach(function (f) {
        var el = document.getElementById(f.id);
        if (el) el.addEventListener('input', updateCompleteness);
        if (el) el.addEventListener('change', updateCompleteness);
    });

    updateCompleteness();

})();
</script>

<?php
$content = ob_get_clean();
require __DIR__ . "/../layouts/main.php";

