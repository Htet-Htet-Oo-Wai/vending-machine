<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use App\Providers\DBConnection;

$migrationDir = __DIR__;
$migrationFiles = scandir($migrationDir);
foreach ($migrationFiles as $file) {
    if ($file === basename(__FILE__) || pathinfo($file, PATHINFO_EXTENSION) !== 'php') {
        continue;
    }
    $pdo = DBConnection::getInstance()->getPDO();
    $query = include $migrationDir . '/' . $file;
    try {
        $pdo->exec($query);
        echo "Migrated: $file\n";
    } catch (PDOException $e) {
        echo "Error in $file: " . $e->getMessage() . "\n";
    }
}
