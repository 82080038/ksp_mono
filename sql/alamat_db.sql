-- Skema untuk database alamat_db (data wilayah Indonesia)
-- Jalankan: mysql -u root -proot < alamat_db.sql

CREATE DATABASE IF NOT EXISTS alamat_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE alamat_db;

-- Tabel provinces
CREATE TABLE IF NOT EXISTS provinces (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL
);

-- Tabel regencies
CREATE TABLE IF NOT EXISTS regencies (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    province_id INT NOT NULL,
    FOREIGN KEY (province_id) REFERENCES provinces(id)
);

-- Tabel districts
CREATE TABLE IF NOT EXISTS districts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    regency_id INT NOT NULL,
    FOREIGN KEY (regency_id) REFERENCES regencies(id)
);

-- Tabel villages
CREATE TABLE IF NOT EXISTS villages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    district_id INT NOT NULL,
    kodepos VARCHAR(10) NULL,
    FOREIGN KEY (district_id) REFERENCES districts(id)
);

-- Sample data (contoh, tambahkan data lengkap sesuai kebutuhan)
INSERT INTO provinces (id, name) VALUES
(1, 'Aceh'),
(2, 'Sumatera Utara'),
(3, 'Sumatera Barat'),
(4, 'Riau'),
(5, 'Jambi'),
(6, 'Sumatera Selatan'),
(7, 'Bengkulu'),
(8, 'Lampung'),
(9, 'Kepulauan Bangka Belitung'),
(10, 'Kepulauan Riau'),
(11, 'DKI Jakarta'),
(12, 'Jawa Barat'),
(13, 'Jawa Tengah'),
(14, 'DI Yogyakarta'),
(15, 'Jawa Timur'),
(16, 'Banten'),
(17, 'Bali'),
(18, 'Nusa Tenggara Barat'),
(19, 'Nusa Tenggara Timur'),
(20, 'Kalimantan Barat'),
(21, 'Kalimantan Tengah'),
(22, 'Kalimantan Selatan'),
(23, 'Kalimantan Timur'),
(24, 'Kalimantan Utara'),
(25, 'Sulawesi Utara'),
(26, 'Sulawesi Tengah'),
(27, 'Sulawesi Selatan'),
(28, 'Sulawesi Tenggara'),
(29, 'Gorontalo'),
(30, 'Sulawesi Barat'),
(31, 'Maluku'),
(32, 'Maluku Utara'),
(33, 'Papua Barat'),
(34, 'Papua');

-- Contoh untuk regencies, districts, villages (minimal untuk testing)
-- Tambahkan lebih lengkap sesuai data aktual
INSERT INTO regencies (id, name, province_id) VALUES
(1, 'Kota Banda Aceh', 1),
(2, 'Kota Medan', 2),
(3, 'Kota Padang', 3),
(4, 'Kota Pekanbaru', 4),
(5, 'Kota Jambi', 5),
(6, 'Kota Palembang', 6),
(7, 'Kota Bengkulu', 7),
(8, 'Kota Bandar Lampung', 8),
(9, 'Kota Pangkal Pinang', 9),
(10, 'Kota Tanjung Pinang', 10),
(11, 'Kota Jakarta Pusat', 11),
(12, 'Kota Bandung', 12),
(13, 'Kota Semarang', 13),
(14, 'Kota Yogyakarta', 14),
(15, 'Kota Surabaya', 15),
(16, 'Kota Serang', 16),
(17, 'Kota Denpasar', 17),
(18, 'Kota Mataram', 18),
(19, 'Kota Kupang', 19),
(20, 'Kota Pontianak', 20),
(21, 'Kota Palangka Raya', 21),
(22, 'Kota Banjarmasin', 22),
(23, 'Kota Samarinda', 23),
(24, 'Kota Tarakan', 24),
(25, 'Kota Manado', 25),
(26, 'Kota Palu', 26),
(27, 'Kota Makassar', 27),
(28, 'Kota Kendari', 28),
(29, 'Kota Gorontalo', 29),
(30, 'Kota Mamuju', 30),
(31, 'Kota Ambon', 31),
(32, 'Kota Ternate', 32),
(33, 'Kota Sorong', 33),
(34, 'Kota Jayapura', 34);

INSERT INTO districts (id, name, regency_id) VALUES
(1, 'Banda Aceh', 1),
(2, 'Medan Kota', 2),
(3, 'Padang Barat', 3),
(4, 'Pekanbaru Kota', 4),
(5, 'Jambi Selatan', 5),
(6, 'Palembang', 6),
(7, 'Bengkulu', 7),
(8, 'Bandar Lampung', 8),
(9, 'Pangkal Pinang', 9),
(10, 'Tanjung Pinang', 10),
(11, 'Tanah Abang', 11),
(12, 'Bandung', 12),
(13, 'Semarang Barat', 13),
(14, 'Yogyakarta', 14),
(15, 'Surabaya', 15),
(16, 'Serang', 16),
(17, 'Denpasar', 17),
(18, 'Mataram', 18),
(19, 'Kupang', 19),
(20, 'Pontianak Kota', 20),
(21, 'Palangka Raya', 21),
(22, 'Banjarmasin', 22),
(23, 'Samarinda', 23),
(24, 'Tarakan', 24),
(25, 'Manado', 25),
(26, 'Palu Barat', 26),
(27, 'Makassar', 27),
(28, 'Kendari', 28),
(29, 'Gorontalo', 29),
(30, 'Mamuju', 30),
(31, 'Ambon', 31),
(32, 'Ternate', 32),
(33, 'Sorong', 33),
(34, 'Jayapura', 34);

INSERT INTO villages (id, name, district_id, kodepos) VALUES
(1, 'Peunayong', 1, '23111'),
(2, 'Medan Kota', 2, '20211'),
(3, 'Padang Pasir', 3, '25111'),
(4, 'Pekanbaru Kota', 4, '28111'),
(5, 'Jambi Selatan', 5, '36111'),
(6, 'Palembang', 6, '30111'),
(7, 'Bengkulu', 7, '38111'),
(8, 'Bandar Lampung', 8, '35111'),
(9, 'Pangkal Pinang', 9, '33111'),
(10, 'Tanjung Pinang', 10, '29111'),
(11, 'Tanah Abang', 11, '10230'),
(12, 'Bandung', 12, '40111'),
(13, 'Semarang Barat', 13, '50111'),
(14, 'Yogyakarta', 14, '55111'),
(15, 'Surabaya', 15, '60111'),
(16, 'Serang', 16, '42111'),
(17, 'Denpasar', 17, '80111'),
(18, 'Mataram', 18, '83111'),
(19, 'Kupang', 19, '85111'),
(20, 'Pontianak Kota', 20, '78111'),
(21, 'Palangka Raya', 21, '73111'),
(22, 'Banjarmasin', 22, '70111'),
(23, 'Samarinda', 23, '75111'),
(24, 'Tarakan', 24, '77111'),
(25, 'Manado', 25, '95111'),
(26, 'Palu Barat', 26, '94111'),
(27, 'Makassar', 27, '90111'),
(28, 'Kendari', 28, '93111'),
(29, 'Gorontalo', 29, '96111'),
(30, 'Mamuju', 30, '91511'),
(31, 'Ambon', 31, '97111'),
(32, 'Ternate', 32, '97711'),
(33, 'Sorong', 33, '98411'),
(34, 'Jayapura', 34, '99111');
