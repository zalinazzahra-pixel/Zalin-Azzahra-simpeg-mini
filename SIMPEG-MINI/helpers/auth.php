<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: /SIMPEG-MINI/auth/login.php");
    exit;
}