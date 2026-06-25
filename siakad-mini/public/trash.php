<?php

require_once '../src/Auth.php';
require_once '../config/database.php';
require_once '../src/Helper.php';

Auth::check();
Auth::requireAdmin();

$pdo = Database::getConnection();

$stmt = $pdo->query(
    "SELECT * FROM dosen
     WHERE deleted_at IS NOT NULL"
);

$data = $stmt->fetchAll();
?>

<h2>Data Sampah</h2>

<a href="index.php">Kembali</a>

<table border="1" cellpadding="10">

    <tr>
        <th>NIDN</th>
        <th>Nama</th>
        <th>Aksi</th>
    </tr>

    <?php foreach ($data as $row): ?>

    <tr>

        <td><?= Helper::e($row['nidn']) ?></td>

        <td><?= Helper::e($row['nama']) ?></td>

        <td>
            <a href="restore.php?id=<?= $row['id'] ?>">
                Restore
            </a>
        </td>

    </tr>

    <?php endforeach; ?>

</table>