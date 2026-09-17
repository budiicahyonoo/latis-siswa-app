-- ==========================================================
-- Database: latis_siswa
-- Test Skill IT Fullstack - Latiseducation
-- ==========================================================

CREATE DATABASE IF NOT EXISTS latis_siswa CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE latis_siswa;

-- ----------------------------------------------------------
-- Table: users  (untuk login)
-- ----------------------------------------------------------
CREATE TABLE users (
    id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL,
    nama VARCHAR(100) NOT NULL,
    position VARCHAR(100) NOT NULL,
    photo VARCHAR(255) DEFAULT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY username (username)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- password default: "password123" (di-hash dengan password_hash bcrypt)
-- hash di bawah ini valid untuk "password123"
INSERT INTO users (username, password, nama, position, photo) VALUES
('admin', '$2b$10$lHwRdyg7nPI3LYcUt4H.buiPhhSyJAf.x7VLEKJSx/9bWlb4I9J0S', 'Nama Kandidat', 'IT Specialist Fullstack Developer', NULL);

-- ----------------------------------------------------------
-- Table: lembaga
-- ----------------------------------------------------------
CREATE TABLE lembaga (
    id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    nama_lembaga VARCHAR(100) NOT NULL,
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO lembaga (nama_lembaga) VALUES
('Latiseducation'),
('Tutorindonesia');

-- ----------------------------------------------------------
-- Table: siswa
-- ----------------------------------------------------------
CREATE TABLE siswa (
    id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    nis VARCHAR(20) NOT NULL,
    nama_siswa VARCHAR(150) NOT NULL,
    email VARCHAR(150) NOT NULL,
    lembaga_id INT(11) UNSIGNED NOT NULL,
    foto VARCHAR(255) DEFAULT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY nis (nis),
    KEY lembaga_id (lembaga_id),
    CONSTRAINT fk_siswa_lembaga FOREIGN KEY (lembaga_id) REFERENCES lembaga (id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- contoh data (opsional, boleh dihapus)
INSERT INTO siswa (nis, nama_siswa, email, lembaga_id) VALUES
('1001', 'Budi Santoso', 'budi.santoso@example.com', 1),
('1002', 'Siti Aminah', 'siti.aminah@example.com', 2);
