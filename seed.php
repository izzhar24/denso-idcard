<?php

require_once __DIR__ . '/app/Core/helpers.php';
require_once __DIR__ . '/config/database.php';
loadEnv();

$pdo = getPDO();

$users = [
    ['admin', 'admin@mail.com', 'admin', 'admin'],
    ['user', 'user@mail.com', 'user', 'user']
];

$stmt = $pdo->prepare("
    INSERT INTO users (name, email, password, role) 
    VALUES (?, ?, ?, ?)
");

foreach ($users as $user) {
    $user[2] = password_hash($user[2], PASSWORD_BCRYPT);
    $stmt->execute($user);
}
echo "✅ Seeded Users\n";


// Seed Employees
$employees = [
    ['DENSO INDONESIA', 'BEKASI', '5402', 'TQM', '2090761', 'AJI WIBOWO', 'TETAP', 'AJI WIBOWO'],
    ['HAMADEN INDONESIA', 'BEKASI', '5402', 'TQM', '2110677', 'YUDI SUSANTO', 'KONTRAK', 'YUDI SUSANTO'],
    ['DENSO INDONESIA', 'BEKASI', '3222', 'HR-BEKASI', '2160472', 'HANA SARASWATI', 'TETAP', 'HANA SARASWA'],
    ['DENSO INDONESIA', 'FAJAR', '3222', 'GA-BEKASI', '2160817', 'AGUS ALY', 'TETAP', 'AGUS ALY'],
    ['DENSO INDONESIA', 'BEKASI', '3222', 'GA-BEKASI', '2131366', 'RENO WIDODO', 'TETAP', 'RENO WIDODO'],
    ['DENSO INDONESIA', 'BEKASI', '3222', 'TQM', '2180021', 'TIA RAHMAWATI', 'TETAP', 'TIA RAHMAWATI'],
    ['HAMADEN INDONESIA', 'BEKASI', '3222', 'TQM', '2170021', 'SUGIYARTO', 'KONTRAK', 'SUGIYARTO'],
    ['DENSO INDONESIA', 'FAJAR', '3222', 'GA-BEKASI', '2160821', 'INDRA WIJAYA', 'TETAP', 'INDRA WIJAYA'],
    ['DENSO INDONESIA', 'BEKASI', '3222', 'GA-BEKASI', '2131321', 'ARDIANTO', 'TETAP', 'ARDIANTO'],
    ['DENSO INDONESIA', 'BEKASI', '3222', 'GA-BEKASI', '2131331', 'DODY DWI PRASETYO', 'TETAP', 'DODY DWI PRASE'],
    ['DENSO INDONESIA', 'BEKASI', '5402', 'TQM', '2090821', 'AGUS WAHYU', 'TETAP', 'AGUS WAHYU'],
    ['HAMADEN INDONESIA', 'BEKASI', '5402', 'TQM', '2110821', 'LINDA SARI', 'KONTRAK', 'LINDA SARI'],
    ['DENSO INDONESIA', 'BEKASI', '3222', 'TQM', '2180121', 'RIZAL RACHMAN', 'TETAP', 'RIZAL RACHMAN'],
    ['DENSO INDONESIA', 'FAJAR', '3222', 'GA-BEKASI', '2160822', 'RIZA EKA PRASETYO', 'TETAP', 'RIZA EKA PRASE'],
    ['DENSO INDONESIA', 'BEKASI', '3222', 'GA-BEKASI', '2131322', 'RICO WIBISANA', 'TETAP', 'RICO WIBISANA']
];

$stmt = $pdo->prepare("
    INSERT INTO employees (company, plant, kd_bu, nm_bu, npk, name, status_karyawan, nickname)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?)
");

foreach ($employees as $emp) {
    $stmt->execute($emp);
}
echo "✅ Seeded employees\n";

// Optional: Seed dummy templates 
$stmtReq = $pdo->prepare("
    INSERT INTO templates  (image_path)
    VALUES (?)
");

$stmtReq->execute(['card-templates/template1.png']);
$stmtReq->execute(['card-templates/template2.png']);

echo "✅ Seeded templates \n";

// Optional: Seed dummy employee_card (misalnya semua ambil foto 1x)
$stmtCard = $pdo->prepare("
    INSERT INTO employee_card (employee_id, template_id,selected_photo_path)
    VALUES (?, ?, ?)
");

for ($i = 1; $i <= 5; $i++) {
    $stmtCard->execute([$i, 1, "uploads/employee/blm-ada-foto.jpg"]);
}
echo "✅ Seeded employee_card\n";

// Optional: Seed dummy request_employee_card
$stmtReq = $pdo->prepare("
    INSERT INTO request_employee_card (employee_card_id, reason)
    VALUES (?, ?)
");

$stmtReq->execute([2, 'Foto buram, mohon cetak ulang.']);
$stmtReq->execute([3, 'Kartu hilang.']);

echo "✅ Seeded request_employee_card\n";
