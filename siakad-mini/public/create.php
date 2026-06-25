<?php

require_once '../src/Auth.php';
require_once '../src/DosenRepository.php';
require_once '../src/Validator.php';
require_once '../src/Csrf.php';
require_once '../src/Helper.php';

Auth::check();
Auth::requireAdmin();

$repo = new DosenRepository();

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (!Csrf::verify($_POST['csrf_token'])) {
        die('CSRF invalid');
    }

    $errors = Validator::validateDosen($_POST);

    $foto = null;

    if (!empty($_FILES['foto']['name'])) {

        $upload = Helper::uploadPhoto($_FILES['foto']);

        if ($upload) {
            $foto = $upload;
        } else {
            $errors[] = 'Upload foto gagal';
        }
    }

    if (empty($errors)) {

        $repo->create([
            'nidn' => $_POST['nidn'],
            'nama' => $_POST['nama'],
            'email' => $_POST['email'],
            'program_studi' => $_POST['program_studi'],
            'foto' => $foto,
            'status' => $_POST['status']
        ]);

        Helper::redirect('index.php');
    }
}

$token = Csrf::generate();

?>

<!DOCTYPE html>

<html>

<head>
    <title>Tambah Dosen</title>
</head>

<body>

<h2>Tambah Dosen</h2>

<?php foreach ($errors as $error): ?>

    <p><?= Helper::e($error) ?></p>

<?php endforeach; ?>

<form method="POST" enctype="multipart/form-data">

    <input
        type="hidden"
        name="csrf_token"
        value="<?= $token ?>"
    >

    <input
        type="text"
        name="nidn"
        placeholder="NIDN"
    >

    <br><br>

    <input
        type="text"
        name="nama"
        placeholder="Nama"
    >

    <br><br>

    <input
        type="email"
        name="email"
        placeholder="Email"
    >

    <br><br>

    <select name="program_studi">

        <option value="Teknik Informatika">
            Teknik Informatika
        </option>

        <option value="Sistem Informasi">
            Sistem Informasi
        </option>

        <option value="Teknik Elektro">
            Teknik Elektro
        </option>

    </select>

    <br><br>

    <select name="status">

        <option value="aktif">
            Aktif
        </option>

        <option value="nonaktif">
            Nonaktif
        </option>

    </select>

    <br><br>

    <input type="file" name="foto">

    <br><br>

    <button type="submit">
        Simpan
    </button>

</form>

</body>
</html>