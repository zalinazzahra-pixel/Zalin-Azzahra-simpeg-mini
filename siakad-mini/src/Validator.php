<?php
class Validator {

    public static function validateDosen(array $data): array
    {
        $errors = [];

        if (empty($data['nidn'])) {
            $errors[] = 'NIDN wajib diisi';
        }

        if (empty($data['nama'])) {
            $errors[] = 'Nama wajib diisi';
        }

        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Email tidak valid';
        }

        return $errors;
    }
}