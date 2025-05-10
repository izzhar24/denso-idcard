<?php
require_once __DIR__ . '/../config/database.php';

$sql = "
CREATE TABLE IF NOT EXISTS employees (
    id int NOT NULL AUTO_INCREMENT PRIMARY KEY,
    npk VARCHAR(20) UNIQUE NOT NULL,
    name VARCHAR(100) NOT NULL,
    nickname VARCHAR(17) NOT NULL,
    company VARCHAR(100),
    plant VARCHAR(50),
    kd_bu VARCHAR(10),
    nm_bu VARCHAR(50),
    status_karyawan ENUM('TETAP','KONTRAK') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
";

try {
    $pdo->exec($sql);
    echo "Table 'employees' created.\n";
} catch (PDOException $e) {
    echo "Migration failed: " . $e->getMessage() . "\n";
}
