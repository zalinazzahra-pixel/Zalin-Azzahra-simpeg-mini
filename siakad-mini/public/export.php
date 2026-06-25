<?php

require_once '../src/Auth.php';
require_once '../config/database.php';

Auth::check();

header('Content-Type: text/csv');
header(
    'Content-Disposition: attachment; filename=\"dosen.csv\"'
);

$output = fopen('php://output', 'w');

fputcsv($output, [
    'NIDN',
    'Nama',
    'Email',
    'Program Studi'
]);

$pdo = Database::getConnection();

$stmt = $pdo->query(
    "SELECT * FROM dosen
     WHERE deleted_at IS NULL"
);

while ($row = $stmt->fetch()) {

    fputcsv($output, [

        $row['nidn'],
        $row['nama'],
        $row['email'],
        $row['program_studi']

    ]);
}

fclose($output);
exit;