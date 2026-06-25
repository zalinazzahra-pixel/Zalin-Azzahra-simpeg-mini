<?php
require_once '../src/Auth.php';
require_once '../src/DosenRepository.php';

Auth::check();

$repo = new DosenRepository();
$data = $repo->all();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Data Dosen</title>
</head>
<body>

<h2>Data Dosen</h2>

<a href="create.php">Tambah Dosen</a>
<a href="logout.php">Logout</a>

<table border="1" cellpadding="10">
    <tr>
        <th>NIDN</th>
        <th>Nama</th>
        <th>Email</th>
        <th>Program Studi</th>
        <th>Status</th>
        <th>Total MK</th>
        <th>Aksi</th>
    </tr>

    <?php foreach ($data as $row): ?>

    <tr>
        <td><?= htmlspecialchars($row['nidn'], ENT_QUOTES, 'UTF-8') ?></td>
        <td><?= htmlspecialchars($row['nama'], ENT_QUOTES, 'UTF-8') ?></td>
        <td><?= htmlspecialchars($row['email'], ENT_QUOTES, 'UTF-8') ?></td>
        <td><?= htmlspecialchars($row['program_studi'], ENT_QUOTES, 'UTF-8') ?></td>
        <td><?= htmlspecialchars($row['status'], ENT_QUOTES, 'UTF-8') ?></td>
        <td><?= htmlspecialchars($row['total_mk'], ENT_QUOTES, 'UTF-8') ?></td>

        <td>
            <a href="edit.php?id=<?= $row['id'] ?>">Edit</a>

            <?php if ($_SESSION['role'] === 'admin'): ?>
                <a href="delete.php?id=<?= $row['id'] ?>">Delete</a>
            <?php endif; ?>
        </td>
    </tr>

    <?php endforeach; ?>

</table>

</body>
</html>