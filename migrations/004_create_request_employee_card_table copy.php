<?php
require_once __DIR__ . '/../config/database.php';

$sql = "
CREATE TABLE IF NOT EXISTS request_employee_card (
    id int NOT NULL AUTO_INCREMENT PRIMARY KEY,
    employee_card_id int,
    reason TEXT,
    status ENUM('PENDING','APPROVED','REJECTED') DEFAULT 'PENDING',
    admin_id INTEGER,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (admin_id) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (employee_card_id) REFERENCES employee_card(id) ON DELETE CASCADE
);
";

try {
    $pdo->exec($sql);
    echo "Table 'request_employee_card' created.\n";
} catch (PDOException $e) {
    echo "Migration failed: " . $e->getMessage() . "\n";
}
