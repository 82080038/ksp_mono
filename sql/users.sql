-- Tabel users untuk autentikasi dasar aplikasi KSP
CREATE TABLE IF NOT EXISTS users (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    role ENUM('admin','user') NOT NULL DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Seed akun default
INSERT INTO users (username, password_hash, role) VALUES
('admin', '$2y$10$hHevYqUuq9URTqB63OAtSenqvqaXNkttj7Gn5.CcxoT1lJmTIwV.S', 'admin'),
('user',  '$2y$10$VhXfK0AKoWgx8WU/dSSD4uFkBLWrRCUHDTeU8H1Xp8vdlZjZKXuxS', 'user');
