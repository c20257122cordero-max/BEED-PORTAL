<?php

/**
 * index.php — Front Controller / Router
 *
 * Parses $_SERVER['REQUEST_URI'], strips the query string, and dispatches
 * to the matching controller action. Returns a 404 page for unmatched routes.
 */

declare(strict_types=1);

// ── Autoloader ────────────────────────────────────────────────────────────────
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/src/helpers.php';

// ── Base path ─────────────────────────────────────────────────────────────────
// APP_BASE is the subfolder prefix (e.g. '/DEMO MAKER AND LESSON PLAN MAKER').
// It is empty when the app runs at the document root.
define('APP_BASE', rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\'));

// ── Helpers ───────────────────────────────────────────────────────────────────

/**
 * Render a simple 404 page and exit.
 */
function send404(): never
{
    http_response_code(404);
    echo '<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8">'
        . '<title>404 Not Found</title>'
        . '<meta name="viewport" content="width=device-width, initial-scale=1">'
        . '</head><body style="font-family:sans-serif;text-align:center;padding:4rem">'
        . '<h1>404 — Page Not Found</h1>'
        . '<p>The page you are looking for does not exist.</p>'
        . '<a href="' . url('/dashboard') . '">Go to Dashboard</a>'
        . '</body></html>';
    exit;
}

// ── Parse the request ─────────────────────────────────────────────────────────
$method = strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');

// Strip query string and normalise trailing slash (except root "/")
$uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);

// Strip the subfolder base path so the router works both at the document root
// and when the app lives in a subdirectory (e.g. /DEMO MAKER AND LESSON PLAN MAKER/).
// REQUEST_URI is URL-encoded; SCRIPT_NAME has literal spaces — decode before comparing.
$uri = urldecode($uri);
$scriptDir = APP_BASE;
if ($scriptDir !== '' && str_starts_with($uri, $scriptDir)) {
    $uri = substr($uri, strlen($scriptDir));
}

$uri = '/' . trim($uri, '/');
if ($uri === '/') {
    // Redirect bare root to /dashboard
    header('Location: ' . APP_BASE . '/dashboard');
    exit;
}

// ── Routing table ─────────────────────────────────────────────────────────────
//
// Each entry: [ HTTP_METHOD, regex_pattern, controller_class, action, param_names[] ]
//
// Parameterised segments like {id} are captured as named groups.

$routes = [
    // Auth
    ['GET',  '#^/login$#',                                  'AuthController',        'showLogin',    []],
    ['POST', '#^/login$#',                                  'AuthController',        'login',        []],
    ['GET',  '#^/register$#',                               'AuthController',        'showRegister', []],
    ['POST', '#^/register$#',                               'AuthController',        'register',     []],
    ['POST', '#^/logout$#',                                 'AuthController',        'logout',       []],

    // Dashboard
    ['GET',  '#^/dashboard$#',                              'DashboardController',   'index',        []],

    // Profile
    ['GET',  '#^/profile$#',                                'ProfileController',     'show',         []],
    ['POST', '#^/profile$#',                                'ProfileController',     'update',       []],

    // Demos — specific routes before parameterised ones
    ['GET',  '#^/demos$#',                                  'DemoController',        'index',        []],
    ['GET',  '#^/demos/create$#',                           'DemoController',        'create',       []],
    ['POST', '#^/demos$#',                                  'DemoController',        'store',        []],
    ['GET',  '#^/demos/(?P<id>\d+)/edit$#',                 'DemoController',        'edit',         ['id']],
    ['POST', '#^/demos/(?P<id>\d+)$#',                      'DemoController',        'update',       ['id']],
    ['POST', '#^/demos/(?P<id>\d+)/delete$#',               'DemoController',        'delete',       ['id']],
    ['GET',  '#^/demos/(?P<id>\d+)/export$#',               'DemoController',        'export',       ['id']],
    ['POST', '#^/demos/(?P<id>\d+)/duplicate$#',            'DemoController',        'duplicate',    ['id']],

    // Lesson Plans — specific routes before parameterised ones
    ['GET',  '#^/lesson-plans$#',                           'LessonPlanController',  'index',        []],
    ['GET',  '#^/lesson-plans/create$#',                    'LessonPlanController',  'create',       []],
    ['POST', '#^/lesson-plans$#',                           'LessonPlanController',  'store',        []],
    ['GET',  '#^/lesson-plans/(?P<id>\d+)/edit$#',          'LessonPlanController',  'edit',         ['id']],
    ['POST', '#^/lesson-plans/(?P<id>\d+)$#',               'LessonPlanController',  'update',       ['id']],
    ['POST', '#^/lesson-plans/(?P<id>\d+)/delete$#',        'LessonPlanController',  'delete',       ['id']],
    ['GET',  '#^/lesson-plans/(?P<id>\d+)/export$#',        'LessonPlanController',  'export',       ['id']],
    ['POST', '#^/lesson-plans/(?P<id>\d+)/duplicate$#',     'LessonPlanController',  'duplicate',    ['id']],

    // Demo Templates
    ['GET',  '#^/demo-templates$#',                              'DemoTemplateController', 'index',        []],
    ['GET',  '#^/demo-templates/create$#',                       'DemoTemplateController', 'create',       []],
    ['POST', '#^/demo-templates$#',                              'DemoTemplateController', 'store',        []],
    ['POST', '#^/demo-templates/save-from-demo$#',               'DemoTemplateController', 'saveFromDemo', []],
    ['GET',  '#^/demo-templates/(?P<id>\d+)/edit$#',             'DemoTemplateController', 'edit',         ['id']],
    ['POST', '#^/demo-templates/(?P<id>\d+)$#',                  'DemoTemplateController', 'update',       ['id']],
    ['POST', '#^/demo-templates/(?P<id>\d+)/delete$#',           'DemoTemplateController', 'delete',       ['id']],
    ['GET',  '#^/demo-templates/(?P<id>\d+)/apply$#',            'DemoTemplateController', 'apply',        ['id']],

    // Templates — specific routes before parameterised ones
    ['GET',  '#^/templates$#',                              'TemplateController',    'index',        []],
    ['GET',  '#^/templates/create$#',                       'TemplateController',    'create',       []],
    ['POST', '#^/templates$#',                              'TemplateController',    'store',        []],
    ['POST', '#^/templates/save-from-plan$#',               'TemplateController',    'saveFromPlan', []],
    ['GET',  '#^/templates/(?P<id>\d+)/edit$#',             'TemplateController',    'edit',         ['id']],
    ['POST', '#^/templates/(?P<id>\d+)$#',                  'TemplateController',    'update',       ['id']],
    ['POST', '#^/templates/(?P<id>\d+)/delete$#',           'TemplateController',    'delete',       ['id']],
    ['GET',  '#^/templates/(?P<id>\d+)/apply$#',            'TemplateController',    'apply',        ['id']],
];

// ── Dispatch ──────────────────────────────────────────────────────────────────
foreach ($routes as [$routeMethod, $pattern, $controllerClass, $action, $paramNames]) {
    if ($method !== $routeMethod) {
        continue;
    }

    if (!preg_match($pattern, $uri, $matches)) {
        continue;
    }

    // Resolve controller file path (controllers live in src/controllers/)
    $controllerFile = __DIR__ . '/src/controllers/' . $controllerClass . '.php';

    if (!file_exists($controllerFile)) {
        // Controller not yet implemented — treat as 404 during development
        send404();
    }

    require_once $controllerFile;

    if (!class_exists($controllerClass)) {
        send404();
    }

    $controller = new $controllerClass();

    if (!method_exists($controller, $action)) {
        send404();
    }

    // Build ordered argument list from named capture groups
    $args = [];
    foreach ($paramNames as $name) {
        $args[] = isset($matches[$name]) ? (int) $matches[$name] : null;
    }

    $controller->$action(...$args);
    exit;
}

// No route matched
send404();
