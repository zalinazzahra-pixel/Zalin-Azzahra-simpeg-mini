<?php
require_once 'auth.php';

if ($_SESSION['role'] != 'admin') {
    die("Akses Ditolak");
}