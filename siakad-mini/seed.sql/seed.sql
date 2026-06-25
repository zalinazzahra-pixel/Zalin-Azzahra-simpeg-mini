```sql
CREATE DATABASE IF NOT EXISTS siakad_mini;
USE siakad_mini;

-- =========================================
-- TABLE USERS
-- =========================================

CREATE TABLE users (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    role ENUM('admin','operator') NOT NULL DEFAULT 'operator',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- =========================================
-- TABLE DOSEN
-- =========================================

CREATE TABLE dosen (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nidn CHAR(10) NOT NULL UNIQUE,
    nama VARCHAR(100) NOT NULL,
    email VARCHAR(120) NOT NULL UNIQUE,

    program_studi ENUM(
        'Teknik Informatika',
        'Sistem Informasi',
        'Teknik Elektro'
    ) NOT NULL,

    foto VARCHAR(255) NULL,

    status ENUM(
        'aktif',
        'nonaktif'
    ) NOT NULL DEFAULT 'aktif',

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ON UPDATE CURRENT_TIMESTAMP,

    deleted_at TIMESTAMP NULL
);

-- =========================================
-- TABLE MATA KULIAH
-- =========================================

CREATE TABLE mata_kuliah (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    kode VARCHAR(12) NOT NULL UNIQUE,

    nama VARCHAR(100) NOT NULL,

    sks TINYINT UNSIGNED NOT NULL
);

-- =========================================
-- TABLE RELASI DOSEN & MATA KULIAH
-- =========================================

CREATE TABLE dosen_matakuliah (

    dosen_id INT UNSIGNED NOT NULL,

    matakuliah_id INT UNSIGNED NOT NULL,

    semester ENUM(
        'Ganjil',
        'Genap'
    ) NOT NULL,

    PRIMARY KEY(
        dosen_id,
        matakuliah_id,
        semester
    ),

    FOREIGN KEY (dosen_id)
    REFERENCES dosen(id)
    ON DELETE CASCADE,

    FOREIGN KEY (matakuliah_id)
    REFERENCES mata_kuliah(id)
    ON DELETE CASCADE
);

-- =========================================
-- TABLE ACTIVITY LOG
-- =========================================

CREATE TABLE activity_log (

    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    user_id INT UNSIGNED NULL,

    aksi VARCHAR(20) NOT NULL,

    entitas VARCHAR(50) NOT NULL,

    entitas_id INT UNSIGNED NULL,

    keterangan VARCHAR(255) NULL,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (user_id)
    REFERENCES users(id)
    ON DELETE SET NULL
);

-- =========================================
-- USER ADMIN
-- PASSWORD = admin123
-- =========================================

INSERT INTO users(
    username,
    password_hash,
    role
)

VALUES(
    'admin',

    '$2y$10$Jz2YQX0F4iRzL7F0Qx5e2uJ6z9f9gD3M2W7wS9M6qA2cL8uYxBz3K',

    'admin'
);

-- =========================================
-- DATA DOSEN
-- =========================================

INSERT INTO dosen(
    nidn,
    nama,
    email,
    program_studi,
    foto,
    status
)

VALUES

(
    '2024150001',
    'Zalin Azzahra',
    'zalin@gmail.com',
    'Teknik Informatika',
    NULL,
    'aktif'
),

(
    '2024150002',
    'Budi Santoso',
    'budi@gmail.com',
    'Sistem Informasi',
    NULL,
    'aktif'
);

-- =========================================
-- DATA MATA KULIAH
-- =========================================

INSERT INTO mata_kuliah(
    kode,
    nama,
    sks
)

VALUES

(
    'IF101',
    'Algoritma',
    3
),

(
    'IF102',
    'Basis Data',
    3
),

(
    'IF103',
    'Pemrograman Web',
    3
);

-- =========================================
-- RELASI DOSEN & MK
-- =========================================

INSERT INTO dosen_matakuliah(
    dosen_id,
    matakuliah_id,
    semester
)

VALUES

(
    1,
    1,
    'Ganjil'
),

(
    1,
    2,
    'Ganjil'
),

(
    2,
    3,
    'Genap'
);
```
