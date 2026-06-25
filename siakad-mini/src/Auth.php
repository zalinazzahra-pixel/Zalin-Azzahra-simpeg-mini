<?php
session_start();

require_once __DIR__ . '/../config/database.php';

class Auth {

    public static function login(string $username, string $password): bool
    {
        $pdo = Database::getConnection();

        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);

        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password_hash'])) {

            session_regenerate_id(true);

            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            return true;
        }

        return false;
    }

    public static function check(): void
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: login.php');
            exit;
        }
    }

    public static function requireAdmin(): void
    {
        if ($_SESSION['role'] !== 'admin') {
            http_response_code(403);
            die('403 Forbidden');
        }
    }

    public static function logout(): void
    {
        session_destroy();
    }
}