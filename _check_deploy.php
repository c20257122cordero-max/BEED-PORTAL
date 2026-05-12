<?php
// BEED Portal  Deployment Diagnostic
// DELETE THIS FILE after fixing the issue!
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo '<style>body{font-family:sans-serif;padding:2rem;max-width:800px;margin:0 auto}
.ok{color:#16a34a;font-weight:bold}.err{color:#dc2626;font-weight:bold}
.warn{color:#d97706;font-weight:bold}h2{margin-top:1.5rem;border-bottom:1px solid #e5e7eb;padding-bottom:.5rem}</style>';
echo '<h1>BEED Portal  Deployment Check</h1>';

// 1. PHP version
echo '<h2>1. PHP Version</h2>';
$v = phpversion();
echo phpversion() >= '8.0' ? "<p class=ok> PHP $v</p>" : "<p class=err> PHP $v  need 8.0+</p>";

// 2. PDO extension
echo '<h2>2. PDO MySQL Extension</h2>';
echo extension_loaded('pdo_mysql') ? "<p class=ok> pdo_mysql loaded</p>" : "<p class=err> pdo_mysql NOT loaded</p>";

// 3. Autoloader
echo '<h2>3. Composer Autoloader</h2>';
if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    echo "<p class=ok> vendor/autoload.php found</p>";
} else {
    echo "<p class=err> vendor/autoload.php NOT found  upload the vendor/ folder</p>";
}

// 4. Database connection
echo '<h2>4. Database Connection</h2>';
require_once __DIR__ . '/config/database.php';
try {
    $db = Database::getConnection();
    echo "<p class=ok> Database connected successfully</p>";
    
    // 5. Tables
    echo '<h2>5. Database Tables</h2>';
    $tables = $db->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
    $required = ['students','demos','demo_steps','lesson_plans','lesson_objectives','lesson_plan_templates','demo_templates'];
    foreach ($required as $t) {
        if (in_array($t, $tables)) {
            echo "<p class=ok> Table: $t</p>";
        } else {
            echo "<p class=err> Missing table: $t  import sql/schema.sql</p>";
        }
    }
} catch (Exception $e) {
    echo "<p class=err> DB Error: " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<p class=warn> Check config/database.php credentials</p>";
}

// 6. .htaccess
echo '<h2>6. .htaccess / mod_rewrite</h2>';
echo file_exists(__DIR__ . '/.htaccess') ? "<p class=ok> .htaccess found</p>" : "<p class=err> .htaccess missing</p>";

// 7. Session
echo '<h2>7. Sessions</h2>';
session_start();
$_SESSION['test'] = 'ok';
echo isset($_SESSION['test']) ? "<p class=ok> Sessions working</p>" : "<p class=err> Sessions not working</p>";

echo '<h2>Done</h2><p style="color:#6b7280">Delete this file (check.php) after fixing issues.</p>';