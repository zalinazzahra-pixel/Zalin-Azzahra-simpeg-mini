<?php
session_start();
require_once '../config/database.php';

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare(
        "SELECT * FROM users WHERE username=?"
    );

    $stmt->bind_param("s", $username);

    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows == 1) {

        $user = $result->fetch_assoc();

        if (
            password_verify(
                $password,
                $user['password']
            )
        ) {

            session_regenerate_id(true);

            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['pegawai_id'] = $user['pegawai_id'];

            if ($user['role'] == 'admin') {
                header("Location: ../admin/dashboard.php");
            } elseif ($user['role'] == 'manager') {
                header("Location: ../manager/dashboard.php");
            } else {
                header("Location: ../karyawan/dashboard.php");
            }

            exit;
        }
    }

    $error = "Username atau Password Salah";
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Login SIMPEG</title>
</head>
<body>

<h2>Login SIMPEG</h2>

<?php if($error): ?>
<p style="color:red"><?= $error ?></p>
<?php endif; ?>

<form method="POST">

    <input
    type="text"
    name="username"
    placeholder="Username"
    required>

    <br><br>

    <input
    type="password"
    name="password"
    placeholder="Password"
    required>

    <br><br>

    <button type="submit">
        Login
    </button>

</form>

</body>
</html>