-- Skema inti aplikasi KSP-Peb (anggota, simpanan, pinjaman)
CREATE TABLE IF NOT EXISTS members (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    no_hp VARCHAR(20) NOT NULL,
    email VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS savings (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    member_id INT UNSIGNED NOT NULL,
    amount DECIMAL(14,2) NOT NULL,
    type ENUM('setoran','penarikan') NOT NULL DEFAULT 'setoran',
    note VARCHAR(255) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_savings_member FOREIGN KEY (member_id) REFERENCES members(id)
        ON DELETE CASCADE ON UPDATE CASCADE,
    INDEX idx_savings_member (member_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS loans (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    member_id INT UNSIGNED NOT NULL,
    amount DECIMAL(14,2) NOT NULL,
    tenor_months INT UNSIGNED NOT NULL DEFAULT 1,
    status ENUM('diajukan','disetujui','dicairkan','lunas','ditolak') NOT NULL DEFAULT 'diajukan',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_loans_member FOREIGN KEY (member_id) REFERENCES members(id)
        ON DELETE CASCADE ON UPDATE CASCADE,
    INDEX idx_loans_member (member_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
