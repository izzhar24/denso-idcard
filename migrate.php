
<?php

require_once __DIR__ . '/app/Core/helpers.php';
require_once __DIR__ . '/config/database.php';
loadEnv();

$pdo = getPDO();

function dropAllTables($pdo) {
    $pdo->exec('SET FOREIGN_KEY_CHECKS = 0');
    $stmt = $pdo->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);

    foreach ($tables as $table) {
        $pdo->exec("DROP TABLE IF EXISTS `$table`");
        echo "Dropped: $table\n";
    }
    $pdo->exec('SET FOREIGN_KEY_CHECKS = 1');
}

function runMigrations($pdo) {
    $files = glob(__DIR__ . '/migrations/*.php');
    sort($files);

    foreach ($files as $file) {
        require_once $file;
        $migrationFunction = basename($file, '.php');
        if (function_exists($migrationFunction)) {
            $migrationFunction($pdo);
            echo "Migrated: $migrationFunction\n";
        }
    }
}

// === CLI Logic ===
$command = $argv[1] ?? null;

if ($command === '--refresh') {
    echo "Refreshing database...\n";
    dropAllTables($pdo);
    runMigrations($pdo);
    echo "Done.\n";
} else {
    echo "Usage:\n";
    echo "  php migrate.php --refresh\n";
}
