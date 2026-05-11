<?php
declare(strict_types=1);

/**
 * Demo Maker — index view
 *
 * Variables provided by DemoController::index():
 *   $demos   (array)   – all demos for the authenticated student
 *   $search  (string)  – current search term (from ?q=)
 *   $status  (string)  – current status filter (from ?status=)
 *
 * Requirements: 4.1, 4.2, 4.3, 4.4
 */

$pageTitle = 'My Demos – BEED Student Portal';

/**
 * Return a Tailwind badge HTML string for a given status value.
 */
function demoBadge(string $status): string
{
    $map = [
        'draft'      => ['bg-gray-100 text-gray-600',   'Draft'],
        'for_review' => ['bg-yellow-100 text-yellow-700', 'For Review'],
        'submitted'  => ['bg-green-100 text-green-700',  'Submitted'],
    ];
    [$cls, $label] = $map[$status] ?? ['bg-gray-100 text-gray-500', htmlspecialchars($status)];
    return '<span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium ' . $cls . '">'
        . htmlspecialchars($label) . '</span>';
}

ob_start();
?>

<!-- Page header -->
<div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">My Demos</h1>
        <p class="mt-1 text-sm text-gray-500">Manage your teaching demonstration plans.</p>
    </div>

    <a href="<?= url('/demos/create') ?>"
       class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg shadow-sm transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>
        New Demo
    </a>
</div>

<!-- Search + Status filter bar -->
<form method="GET" action="<?= url('/demos') ?>" class="mb-6">
    <div class="flex flex-wrap gap-2">
        <label for="demo-search" class="sr-only">Search demos</label>
        <input
            id="demo-search"
            type="search"
            name="q"
            value="<?= htmlspecialchars($search) ?>"
            placeholder="Search by title or subject…"
            class="flex-1 min-w-0 px-4 py-2 border border-gray-300 rounded-lg text-sm text-gray-800 placeholder-gray-400 bg-white shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
        >

        <!-- Status filter -->
        <select
            name="status"
            class="px-3 py-2 border border-gray-300 rounded-lg text-sm text-gray-800 bg-white shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
            aria-label="Filter by status"
        >
            <option value="" <?= $status === '' ? 'selected' : '' ?>>All Statuses</option>
            <option value="draft"      <?= $status === 'draft'      ? 'selected' : '' ?>>Draft</option>
            <option value="for_review" <?= $status === 'for_review' ? 'selected' : '' ?>>For Review</option>
            <option value="submitted"  <?= $status === 'submitted'  ? 'selected' : '' ?>>Submitted</option>
        </select>

        <button
            type="submit"
            class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg shadow-sm transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
            Search
        </button>
        <?php if ($search !== '' || $status !== ''): ?>
            <a href="<?= url('/demos') ?>"
               class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 text-sm font-medium rounded-lg shadow-sm transition-colors focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-offset-2">
                Clear
            </a>
        <?php endif; ?>
    </div>
</form>

<!-- Demo list -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">

    <?php if (empty($demos)): ?>
        <div class="px-6 py-16 text-center">
            <svg class="mx-auto w-12 h-12 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                      d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            <?php if ($search !== '' || $status !== ''): ?>
                <p class="text-gray-500 text-sm">No demos match your filters.</p>
                <a href="<?= url('/demos') ?>" class="mt-3 inline-block text-sm text-blue-600 hover:text-blue-800 font-medium">Clear filters</a>
            <?php else: ?>
                <p class="text-gray-500 text-sm">You haven't created any demos yet.</p>
                <a href="<?= url('/demos/create') ?>" class="mt-3 inline-block text-sm text-blue-600 hover:text-blue-800 font-medium">Create your first demo →</a>
            <?php endif; ?>
        </div>

    <?php else: ?>

        <!-- Desktop table -->
        <div class="hidden sm:block overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Title</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Subject</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Grade</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Qtr / Wk</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Modified</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    <?php foreach ($demos as $demo): ?>
                        <tr class="hover:bg-blue-50 transition-colors">
                            <td class="px-6 py-4 text-sm font-medium text-gray-800 max-w-xs truncate">
                                <?= htmlspecialchars($demo['title']) ?>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600 whitespace-nowrap">
                                <?= htmlspecialchars($demo['subject'] ?? '') ?>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600 whitespace-nowrap">
                                <?= htmlspecialchars($demo['grade_level'] ?? '') ?>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                                <?php if (!empty($demo['quarter'])): ?>
                                    Q<?= (int) $demo['quarter'] ?>
                                    <?php if (!empty($demo['week'])): ?>
                                        / W<?= (int) $demo['week'] ?>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <span class="text-gray-300">—</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <?= demoBadge($demo['status'] ?? 'draft') ?>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                                <?= htmlspecialchars($demo['updated_at']) ?>
                            </td>
                            <td class="px-6 py-4 text-right whitespace-nowrap">
                                <div class="inline-flex items-center gap-2">
                                    <!-- Edit -->
                                    <a href="<?= url('/demos/' . (int) $demo['id'] . '/edit') ?>"
                                       class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-blue-700 bg-blue-50 hover:bg-blue-100 rounded-md transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                        Edit
                                    </a>

                                    <!-- Duplicate -->
                                    <form method="POST" action="<?= url('/demos/' . (int) $demo['id'] . '/duplicate') ?>">
                                        <button
                                            type="submit"
                                            class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-purple-700 bg-purple-50 hover:bg-purple-100 rounded-md transition-colors focus:outline-none focus:ring-2 focus:ring-purple-500">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                            </svg>
                                            Duplicate
                                        </button>
                                    </form>

                                    <!-- Delete -->
                                    <form method="POST" action="<?= url('/demos/' . (int) $demo['id'] . '/delete') ?>">
                                        <button
                                            type="submit"
                                            onclick="return confirm('Are you sure you want to delete \"<?= htmlspecialchars(addslashes($demo['title']), ENT_QUOTES) ?>\"? This action cannot be undone.')"
                                            class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-white bg-red-600 hover:bg-red-700 rounded-md transition-colors focus:outline-none focus:ring-2 focus:ring-red-500">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Mobile card list -->
        <ul class="sm:hidden divide-y divide-gray-100">
            <?php foreach ($demos as $demo): ?>
                <li class="px-4 py-4">
                    <div class="flex items-start justify-between gap-3">
                        <div class="min-w-0 flex-1">
                            <p class="text-sm font-semibold text-gray-800 truncate">
                                <?= htmlspecialchars($demo['title']) ?>
                            </p>
                            <p class="mt-0.5 text-xs text-gray-500">
                                <?= htmlspecialchars($demo['subject'] ?? '') ?>
                                &middot;
                                Grade <?= htmlspecialchars($demo['grade_level'] ?? '') ?>
                                <?php if (!empty($demo['quarter'])): ?>
                                    &middot; Q<?= (int) $demo['quarter'] ?>
                                    <?php if (!empty($demo['week'])): ?>
                                        W<?= (int) $demo['week'] ?>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </p>
                            <div class="mt-1">
                                <?= demoBadge($demo['status'] ?? 'draft') ?>
                            </div>
                            <p class="mt-0.5 text-xs text-gray-400">
                                Modified: <?= htmlspecialchars($demo['updated_at']) ?>
                            </p>
                        </div>
                        <div class="flex flex-col gap-2 flex-shrink-0">
                            <a href="<?= url('/demos/' . (int) $demo['id'] . '/edit') ?>"
                               class="inline-flex items-center justify-center gap-1 px-3 py-1.5 text-xs font-medium text-blue-700 bg-blue-50 hover:bg-blue-100 rounded-md transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500">
                                Edit
                            </a>
                            <form method="POST" action="<?= url('/demos/' . (int) $demo['id'] . '/duplicate') ?>">
                                <button type="submit"
                                        class="w-full inline-flex items-center justify-center gap-1 px-3 py-1.5 text-xs font-medium text-purple-700 bg-purple-50 hover:bg-purple-100 rounded-md transition-colors focus:outline-none focus:ring-2 focus:ring-purple-500">
                                    Duplicate
                                </button>
                            </form>
                            <form method="POST" action="<?= url('/demos/' . (int) $demo['id'] . '/delete') ?>">
                                <button
                                    type="submit"
                                    onclick="return confirm('Are you sure you want to delete \"<?= htmlspecialchars(addslashes($demo['title']), ENT_QUOTES) ?>\"? This action cannot be undone.')"
                                    class="w-full inline-flex items-center justify-center gap-1 px-3 py-1.5 text-xs font-medium text-white bg-red-600 hover:bg-red-700 rounded-md transition-colors focus:outline-none focus:ring-2 focus:ring-red-500">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>

    <?php endif; ?>

</div>

<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/main.php';
