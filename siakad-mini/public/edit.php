<?php

require_once '../src/Auth.php';
require_once '../src/DosenRepository.php';
require_once '../src/Validator.php';
require_once '../src/Csrf.php';
require_once '../src/Helper.php';

Auth::check();

$repo = new DosenRepository();

$id = (int) $_GET['id'];

$dosen = $repo->find($id);

if (!$dosen) {
    die('Data tidak ditemukan');
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (!Csrf::verify($_POST['csrf_token'])) {
        die('CSRF invalid');
    }

    $errors = Validator::validateDosen($_POST);

    $foto = $dosen['foto'];

    if (!empty($_FILES['foto']['name'])) {

        $upload = Helper::uploadPhoto($_FILES['foto']);

        if ($upload) {
            $foto = $upload;
        }
    }

    if (!$errors) {

        $repo->update($id, [
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

<h2>Edit Dosen</h2>

<?php foreach ($errors as $error): ?>
    <p><?= Helper::e($error) ?></p>
<?php endforeach; ?>

<form method="POST" enctype="multipart/form-data">

    <input type="hidden"
           name="csrf_token"
           value="<?= $token ?>">

    <input type="text"
           name="nidn"
           value="<?= Helper::e($dosen['nidn']) ?>">

    <br>

    <input type="text"
           name="nama"
           value="<?= Helper::e($dosen['nama']) ?>">

    <br>

    <input type="email"
           name="email"
           value="<?= Helper::e($dosen['email']) ?>">

    <br>

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

    <br>

    <select name="status">

        <option value="aktif">Aktif</option>
        <option value="nonaktif">Nonaktif</option>

    </select>

    <br>

    <?php if ($dosen['foto']): ?>

        <img
            src="../uploads/<?= Helper::e($dosen['foto']) ?>"
            width="120">

        <br>

    <?php endif; ?>

    <input type="file" name="foto">

    <br><br>

    <button type="submit">
        Update
    </button>

</form>