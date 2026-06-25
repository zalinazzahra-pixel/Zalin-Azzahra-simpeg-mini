<?php

class Helper {

    public static function redirect(string $url): void
    {
        header("Location: $url");
        exit;
    }

    public static function e(?string $value): string
    {
        return htmlspecialchars(
            $value ?? '',
            ENT_QUOTES,
            'UTF-8'
        );
    }

    public static function old(string $key): string
    {
        return self::e($_POST[$key] ?? '');
    }

    public static function uploadPhoto(array $file): ?string
    {
        if (empty($file['name'])) {
            return null;
        }

        $finfo = finfo_open(FILEINFO_MIME_TYPE);

        $mime = finfo_file(
            $finfo,
            $file['tmp_name']
        );

        $allowed = [
            'image/jpeg' => 'jpg',
            'image/png'  => 'png'
        ];

        if (!array_key_exists($mime, $allowed)) {
            return null;
        }

        $filename = sha1(
            uniqid((string) rand(), true)
        );

        $filename .= '.' . $allowed[$mime];

        move_uploaded_file(
            $file['tmp_name'],
            __DIR__ . '/../uploads/' . $filename
        );

        return $filename;
    }

    public static function setFlash(string $message): void
    {
        $_SESSION['flash'] = $message;
    }

    public static function flash(): void
    {
        if (isset($_SESSION['flash'])) {

            echo '<p>'
                . self::e($_SESSION['flash'])
                . '</p>';

            unset($_SESSION['flash']);
        }
    }
}