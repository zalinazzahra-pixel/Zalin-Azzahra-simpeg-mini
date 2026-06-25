<?php

require_once __DIR__ . '/../config/database.php';

class DosenRepository
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getConnection();
    }

    /*
    |--------------------------------------------------------------------------
    | Ambil semua data dosen
    |--------------------------------------------------------------------------
    */

    public function all(): array
    {
        $sql = "
            SELECT
                dosen.*,
                COUNT(dosen_matakuliah.matakuliah_id) AS total_mk

            FROM dosen

            LEFT JOIN dosen_matakuliah
            ON dosen.id = dosen_matakuliah.dosen_id

            WHERE deleted_at IS NULL

            GROUP BY dosen.id

            ORDER BY dosen.id DESC
        ";

        return $this->pdo
            ->query($sql)
            ->fetchAll();
    }

    /*
    |--------------------------------------------------------------------------
    | Cari dosen berdasarkan ID
    |--------------------------------------------------------------------------
    */

    public function find(int $id)
    {
        $stmt = $this->pdo->prepare(
            "SELECT * FROM dosen
             WHERE id = ?"
        );

        $stmt->execute([$id]);

        return $stmt->fetch();
    }

    /*
    |--------------------------------------------------------------------------
    | Tambah dosen
    |--------------------------------------------------------------------------
    */

    public function create(array $data): bool
    {
        $stmt = $this->pdo->prepare(
            "INSERT INTO dosen
            (
                nidn,
                nama,
                email,
                program_studi,
                foto,
                status
            )

            VALUES (?, ?, ?, ?, ?, ?)"
        );

        return $stmt->execute([

            $data['nidn'],
            $data['nama'],
            $data['email'],
            $data['program_studi'],
            $data['foto'],
            $data['status']

        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Update dosen
    |--------------------------------------------------------------------------
    */

    public function update(int $id, array $data): bool
    {
        $stmt = $this->pdo->prepare(
            "UPDATE dosen

             SET
                nidn = ?,
                nama = ?,
                email = ?,
                program_studi = ?,
                foto = ?,
                status = ?

             WHERE id = ?"
        );

        return $stmt->execute([

            $data['nidn'],
            $data['nama'],
            $data['email'],
            $data['program_studi'],
            $data['foto'],
            $data['status'],
            $id

        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Soft delete
    |--------------------------------------------------------------------------
    */

    public function softDelete(int $id): bool
    {
        $stmt = $this->pdo->prepare(
            "UPDATE dosen
             SET deleted_at = NOW()
             WHERE id = ?"
        );

        return $stmt->execute([$id]);
    }

    /*
    |--------------------------------------------------------------------------
    | Restore data
    |--------------------------------------------------------------------------
    */

    public function restore(int $id): bool
    {
        $stmt = $this->pdo->prepare(
            "UPDATE dosen
             SET deleted_at = NULL
             WHERE id = ?"
        );

        return $stmt->execute([$id]);
    }

    /*
    |--------------------------------------------------------------------------
    | Ambil data trash
    |--------------------------------------------------------------------------
    */

    public function trash(): array
    {
        $stmt = $this->pdo->query(
            "SELECT *
             FROM dosen
             WHERE deleted_at IS NOT NULL
             ORDER BY deleted_at DESC"
        );

        return $stmt->fetchAll();
    }

    /*
    |--------------------------------------------------------------------------
    | Assign mata kuliah
    |--------------------------------------------------------------------------
    */

    public function assignMatakuliah(
        int $dosenId,
        array $matakuliahIds,
        string $semester
    ): bool {

        try {

            $this->pdo->beginTransaction();

            /*
            | Hapus relasi lama
            */

            $delete = $this->pdo->prepare(
                "DELETE FROM dosen_matakuliah
                 WHERE dosen_id = ?"
            );

            $delete->execute([$dosenId]);

            /*
            | Insert relasi baru
            */

            $insert = $this->pdo->prepare(
                "INSERT INTO dosen_matakuliah
                (
                    dosen_id,
                    matakuliah_id,
                    semester
                )

                VALUES (?, ?, ?)"
            );

            foreach ($matakuliahIds as $mkId) {

                $insert->execute([
                    $dosenId,
                    $mkId,
                    $semester
                ]);
            }

            $this->pdo->commit();

            return true;

        } catch (PDOException $e) {

            $this->pdo->rollBack();

            return false;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Statistik dashboard
    |--------------------------------------------------------------------------
    */

    public function statistikProdi(): array
    {
        $stmt = $this->pdo->query(
            "SELECT
                program_studi,
                COUNT(*) AS total

             FROM dosen

             WHERE deleted_at IS NULL

             GROUP BY program_studi"
        );

        return $stmt->fetchAll();
    }

    public function statistikStatus(): array
    {
        $stmt = $this->pdo->query(
            "SELECT
                status,
                COUNT(*) AS total

             FROM dosen

             WHERE deleted_at IS NULL

             GROUP BY status"
        );

        return $stmt->fetchAll();
    }
}