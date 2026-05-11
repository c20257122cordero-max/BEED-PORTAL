<?php
declare(strict_types=1);

/**
 * Profile — show / edit view
 *
 * Variables provided by ProfileController::show():
 *   $student (array|null) – current student row (id, full_name, email,
 *                           school_name, section, year_level,
 *                           cooperating_teacher)
 *   $errors  (array)      – validation errors (currently unused; reserved)
 *   $success (bool)       – true when ?saved=1 is present in the URL
 */

$pageTitle = 'My Profile – BEED Student Portal';

ob_start();
?>

<!-- Page header -->
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">My Profile</h1>
    <p class="mt-1 text-sm text-gray-500">Update your school and practicum information.</p>
</div>

<!-- Success banner -->
<?php if ($success): ?>
    <div class="mb-6 rounded-lg bg-green-50 border border-green-200 px-4 py-3 flex items-center gap-2" role="alert">
        <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
        </svg>
        <p class="text-sm text-green-700 font-medium">Profile saved successfully.</p>
    </div>
<?php endif; ?>

<!-- General error banner -->
<?php if (!empty($errors['general'])): ?>
    <div class="mb-6 rounded-lg bg-red-50 border border-red-200 px-4 py-3" role="alert">
        <p class="text-sm text-red-600">
            <?= htmlspecialchars((string) $errors['general'], ENT_QUOTES, 'UTF-8') ?>
        </p>
    </div>
<?php endif; ?>

<form method="POST" action="<?= url('/profile') ?>" novalidate>

    <div class="space-y-6 max-w-2xl">

        <!-- Account info (read-only) -->
        <section class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h2 class="text-base font-semibold text-gray-700 mb-5">Account Information</h2>

            <div class="grid grid-cols-1 gap-5">

                <!-- Full Name (read-only) -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Full Name
                    </label>
                    <p class="px-3 py-2 rounded-lg bg-gray-50 border border-gray-200 text-sm text-gray-800">
                        <?= htmlspecialchars($student['full_name'] ?? '', ENT_QUOTES, 'UTF-8') ?>
                    </p>
                </div>

                <!-- Email (read-only) -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Email Address
                    </label>
                    <p class="px-3 py-2 rounded-lg bg-gray-50 border border-gray-200 text-sm text-gray-800">
                        <?= htmlspecialchars($student['email'] ?? '', ENT_QUOTES, 'UTF-8') ?>
                    </p>
                </div>

            </div>
        </section>

        <!-- Practicum / School info (editable) -->
        <section class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h2 class="text-base font-semibold text-gray-700 mb-5">School &amp; Practicum Details</h2>

            <div class="grid grid-cols-1 gap-5">

                <!-- School Name -->
                <div>
                    <label for="school_name" class="block text-sm font-medium text-gray-700 mb-1">
                        School Name
                    </label>
                    <input
                        type="text"
                        id="school_name"
                        name="school_name"
                        value="<?= htmlspecialchars($student['school_name'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                        autocomplete="organization"
                        class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-900 placeholder-gray-400 shadow-sm
                               focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                        placeholder="e.g. Mabini Elementary School"
                    >
                </div>

                <!-- Section -->
                <div>
                    <label for="section" class="block text-sm font-medium text-gray-700 mb-1">
                        Section
                    </label>
                    <input
                        type="text"
                        id="section"
                        name="section"
                        value="<?= htmlspecialchars($student['section'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                        autocomplete="off"
                        class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-900 placeholder-gray-400 shadow-sm
                               focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                        placeholder="e.g. Grade 3 – Sampaguita"
                    >
                </div>

                <!-- Year Level -->
                <div>
                    <label for="year_level" class="block text-sm font-medium text-gray-700 mb-1">
                        Year Level
                    </label>
                    <select
                        id="year_level"
                        name="year_level"
                        class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-900 shadow-sm bg-white
                               focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                    >
                        <option value="">— Select year level —</option>
                        <?php
                        $yearLevels = ['1st Year', '2nd Year', '3rd Year', '4th Year'];
                        $currentYearLevel = $student['year_level'] ?? '';
                        foreach ($yearLevels as $yl):
                        ?>
                            <option value="<?= htmlspecialchars($yl) ?>"
                                <?= $currentYearLevel === $yl ? 'selected' : '' ?>>
                                <?= htmlspecialchars($yl) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Cooperating Teacher -->
                <div>
                    <label for="cooperating_teacher" class="block text-sm font-medium text-gray-700 mb-1">
                        Cooperating Teacher
                    </label>
                    <input
                        type="text"
                        id="cooperating_teacher"
                        name="cooperating_teacher"
                        value="<?= htmlspecialchars($student['cooperating_teacher'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                        autocomplete="off"
                        class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-900 placeholder-gray-400 shadow-sm
                               focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                        placeholder="e.g. Mrs. Maria Santos"
                    >
                </div>

            </div>
        </section>

        <!-- Save button -->
        <div class="flex justify-end">
            <button
                type="submit"
                class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg shadow-sm transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                Save Profile
            </button>
        </div>

    </div>

</form>

<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/main.php';
