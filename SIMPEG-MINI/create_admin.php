<?php
$password_polos = "admin123";
$password_hashed = password_hash($password_polos, PASSWORD_BCRYPT);
$host = 'localhost';
$db   = 'nama_database_mu';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
   $user = $stmt->fetch();
if ($user) {
    if (password_verify($password, $user['password'])) {
        echo "Login Berhasil";
     $stmt = $pdo->prepare($sql);
     $stmt->execute([
         'username' => $username,
         'password' => $password_hashed, 
         'role'     => $role
     ]);

     echo "Admin berhasil dibuat dan disimpan ke database!";

} catch (\PDOException $e) {
}