<?php

require_once '../src/Auth.php';
require_once '../config/database.php';
require_once '../src/Helper.php';

Auth::check();

$pdo = Database::getConnection();

$prodi = $pdo->query(
    "SELECT program_studi,
            COUNT(*) total
     FROM dosen
     WHERE deleted_at IS NULL
     GROUP BY program_studi"
)->fetchAll();

$status = $pdo->query(
    "SELECT status,
            COUNT(*) total
     FROM dosen
     WHERE deleted_at IS NULL
     GROUP BY status"
)->fetchAll();
?>

<h2>Dashboard</h2>

<h3>Jumlah Dosen per Prodi</h3>

<?php foreach ($prodi as $p): ?>

<p>
    <?= Helper::e($p['program_studi']) ?>
    :
    <?= Helper::e($p['total']) ?>
</p>

<?php endforeach; ?>

<h3>Status Dosen</h3>

<?php foreach ($status as $s): ?>

<p>
    <?= Helper::e($s['status']) ?>
    :
    <?= Helper::e($s['total']) ?>
</p>

<?php endforeach; ?>