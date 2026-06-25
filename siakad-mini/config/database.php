<?php
class Database {
    private static ?PDO $instance = null;

    public static function getConnection(): PDO
    {
        if (self::$instance === null) {
            $dsn = 'mysql:host=localhost;dbname=siakad_mini;charset=utf8mb4';

            self::$instance = new PDO($dsn, 'root', '');

            self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            self::$instance->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            self::$instance->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        }

        return self::$instance;
    }
}