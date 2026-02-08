-- Migration: Add "orang" table for detailed person information
-- This table will store detailed person information and link to pengguna table
-- Created: 2026-02-08
-- Purpose: Support detailed person data for cooperative registration system

-- Create orang table
CREATE TABLE IF NOT EXISTS `orang` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pengguna_id` int(11) NOT NULL,
  `nama_lengkap` varchar(255) NOT NULL,
  `nama_depan` varchar(100) DEFAULT NULL,
  `nama_tengah` varchar(100) DEFAULT NULL,
  `nama_belakang` varchar(100) DEFAULT NULL,
  `gelar_depan` varchar(50) DEFAULT NULL,
  `gelar_belakang` varchar(50) DEFAULT NULL,
  `jenis_kelamin` enum('L','P','T') DEFAULT NULL,
  `tempat_lahir` varchar(255) DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `hp` varchar(20) DEFAULT NULL,
  `hp_alternatif` varchar(20) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `nik` varchar(20) DEFAULT NULL,
  `kewarganegaraan` varchar(50) DEFAULT NULL,
  `agama` varchar(20) DEFAULT NULL,
  `status_pernikahan` enum('belum_kawin','kawin','cerai','meninggal') DEFAULT NULL,
  `tanggal_pernikahan` date DEFAULT NULL,
  `pekerjaan` varchar(255) DEFAULT NULL,
  `instansi` varchar(255) DEFAULT NULL,
  `jabatan` varchar(255) DEFAULT NULL,
  `alamat_lengkap` text DEFAULT NULL,
  `province_id` int(11) DEFAULT NULL,
  `regency_id` int(11) DEFAULT NULL,
  `district_id` int(11) DEFAULT NULL,
  `village_id` int(11) DEFAULT NULL,
  `nama_jalan` varchar(255) DEFAULT NULL,
  `nomor_rumah` varchar(50) DEFAULT NULL,
  `rt` varchar(10) DEFAULT NULL,
  `rw` varchar(10) DEFAULT NULL,
  `postal_code` varchar(10) DEFAULT NULL,
  `foto_ktp` varchar(255) DEFAULT NULL,
  `foto_selfie` varchar(255) DEFAULT NULL,
  `scan_ktp` varchar(255) DEFAULT NULL,
  `catatan` text DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `dibuat_oleh` int(11) DEFAULT NULL,
  `dibuat_pada` timestamp NULL DEFAULT current_timestamp(),
  `diperbarui_oleh` int(11) DEFAULT NULL,
  `diperbarui_pada` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_pengguna_id` (`pengguna_id`),
  KEY `idx_nama_lengkap` (`nama_lengkap`),
  KEY `idx_hp` (`hp`),
  KEY `idx_email` (`email`),
  KEY `idx_nik` (`nik`),
  KEY `idx_alamat` (`province_id`, `regency_id`, `district_id`, `village_id`),
  KEY `idx_created` (`dibuat_pada`),
  KEY `idx_updated` (`diperbarui_pada`),
  KEY `idx_active` (`is_active`),
  CONSTRAINT `fk_orang_pengguna` FOREIGN KEY (`pengguna_id`) REFERENCES `pengguna` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Add indexes for better performance
CREATE INDEX idx_orang_nama_depan ON orang(nama_depan);
CREATE INDEX idx_orang_nama_belakang ON orang(nama_belakang);
CREATE INDEX idx_orang_jenis_kelamin ON orang(jenis_kelamin);
CREATE INDEX idx_orang_tempat_lahir ON orang(tempat_lahir);
CREATE INDEX idx_orang_tanggal_lahir ON orang(tanggal_lahir);
CREATE INDEX idx_orang_status_pernikahan ON orang(status_pernikahan);
CREATE INDEX idx_orang_pekerjaan ON orang(pekerjaan);

-- Add comments for documentation
ALTER TABLE `orang` COMMENT 'Table untuk menyimpan data lengkap orang yang terhubung dengan tabel pengguna';

-- Add column comments
ALTER TABLE `orang` MODIFY COLUMN `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID unik untuk tabel orang';
ALTER TABLE `orang` MODIFY COLUMN `pengguna_id` int(11) NOT NULL COMMENT 'Foreign key ke tabel pengguna';
ALTER TABLE `orang` MODIFY COLUMN `nama_lengkap` varchar(255) NOT NULL COMMENT 'Nama lengkap orang';
ALTER TABLE `orang` MODIFY COLUMN `nama_depan` varchar(100) DEFAULT NULL COMMENT 'Nama depan orang';
ALTER TABLE `orang` MODIFY COLUMN `nama_tengah` varchar(100) DEFAULT NULL COMMENT 'Nama tengah orang';
ALTER TABLE `orang` MODIFY COLUMN `nama_belakang` varchar(100) DEFAULT NULL COMMENT 'Nama belakang orang';
ALTER TABLE `orang` MODIFY COLUMN `gelar_depan` varchar(50) DEFAULT NULL COMMENT 'Gelar depan (contoh: Bapak, Ibu)';
ALTER TABLE `orang` MODIFY COLUMN `gelar_belakang` varchar(50) DEFAULT NULL COMMENT 'Gelar belakang (contoh: S.H., S.Kom)';
ALTER TABLE `orang` MODIFY COLUMN `jenis_kelamin` enum('L','P','T') DEFAULT NULL COMMENT 'Jenis kelamin: L=Laki-laki, P=Perempuan, T=Tidak diketahui';
ALTER TABLE `orang` MODIFY COLUMN `tempat_lahir` varchar(255) DEFAULT NULL COMMENT 'Tempat lahir';
ALTER TABLE `orang` MODIFY COLUMN `tanggal_lahir` date DEFAULT NULL COMMENT 'Tanggal lahir';
ALTER TABLE `orang` MODIFY COLUMN `hp` varchar(20) DEFAULT NULL COMMENT 'Nomor HP utama';
ALTER TABLE `orang` MODIFY COLUMN `hp_alternatif` varchar(20) DEFAULT NULL COMMENT 'Nomor HP alternatif';
ALTER TABLE `orang` MODIFY COLUMN `email` varchar(255) DEFAULT NULL COMMENT 'Email aktif';
ALTER TABLE `orang` MODIFY COLUMN `nik` varchar(20) DEFAULT NULL COMMENT 'Nomor Induk Kependudukan';
ALTER TABLE `orang` MODIFY COLUMN `kewarganegaraan` varchar(50) DEFAULT NULL COMMENT 'Kewarganegaraan';
ALTER TABLE `orang` MODIFY COLUMN `agama` varchar(20) DEFAULT NULL COMMENT 'Agama';
ALTER TABLE `orang` MODIFY COLUMN `status_pernikahan` enum('belum_kawin','kawin','cerai','meninggal') DEFAULT NULL COMMENT 'Status pernikahan';
ALTER TABLE `orang` MODIFY COLUMN `tanggal_pernikahan` date DEFAULT NULL COMMENT 'Tanggal pernikahan';
ALTER TABLE `orang` MODIFY COLUMN `pekerjaan` varchar(255) DEFAULT NULL COMMENT 'Pekerjaan/Profesi';
ALTER TABLE `orang` MODIFY COLUMN `instansi` varchar(255) DEFAULT NULL COMMENT 'Instansi/Kerja';
ALTER TABLE `orang` MODIFY COLUMN `jabatan` varchar(255) DEFAULT NULL COMMENT 'Jabatan/Posisi';
ALTER TABLE `orang` MODIFY COLUMN `alamat_lengkap` text DEFAULT NULL COMMENT 'Alamat lengkap gabungan';
ALTER TABLE `orang` MODIFY COLUMN `province_id` int(11) DEFAULT NULL COMMENT 'ID provinsi dari alamat_db';
ALTER TABLE `orang` MODIFY COLUMN `regency_id` int(11) DEFAULT NULL COMMENT 'ID kabupaten/kota dari alamat_db';
ALTER TABLE `orang` MODIFY COLUMN `district_id` int(11) DEFAULT NULL COMMENT 'ID kecamatan dari alamat_db';
ALTER TABLE `orang` MODIFY COLUMN `village_id` int(11) DEFAULT NULL COMMENT 'ID kelurahan/desa dari alamat_db';
ALTER TABLE `orang` MODIFY COLUMN `nama_jalan` varchar(255) DEFAULT NULL COMMENT 'Nama jalan';
ALTER TABLE `orang` MODIFY COLUMN `nomor_rumah` varchar(50) DEFAULT NULL COMMENT 'Nomor rumah';
ALTER TABLE `orang` MODIFY COLUMN `rt` varchar(10) DEFAULT NULL COMMENT 'RT (Rukun Tetangga)';
ALTER TABLE `orang` MODIFY COLUMN `rw` varchar(10) DEFAULT NULL COMMENT 'RW (Rukun Warga)';
ALTER TABLE `orang` MODIFY COLUMN `postal_code` varchar(10) DEFAULT NULL COMMENT 'Kode pos';
ALTER TABLE `orang` MODIFY COLUMN `foto_ktp` varchar(255) DEFAULT NULL COMMENT 'Path foto KTP';
ALTER TABLE `orang` MODIFY COLUMN `foto_selfie` varchar(255) DEFAULT NULL COMMENT 'Path foto selfie';
ALTER TABLE `orang` MODIFY COLUMN `scan_ktp` varchar(255) DEFAULT NULL COMMENT 'Path scan KTP';
ALTER TABLE `orang` MODIFY COLUMN `catatan` text DEFAULT NULL COMMENT 'Catatan tambahan';
ALTER TABLE `orang` MODIFY COLUMN `is_active` tinyint(1) DEFAULT 1 COMMENT 'Status aktif (1=aktif, 0=non-aktif)';
ALTER TABLE `orang` MODIFY COLUMN `dibuat_oleh` int(11) DEFAULT NULL COMMENT 'ID user yang membuat data';
ALTER TABLE `orang` MODIFY COLUMN `dibuat_pada` timestamp NULL DEFAULT current_timestamp() COMMENT 'Waktu pembuatan data';
ALTER TABLE `orang` MODIFY COLUMN `diperbarui_oleh` int(11) DEFAULT NULL COMMENT 'ID user yang mengubah data';
ALTER TABLE `orang` MODIFY COLUMN `diperbarui_pada` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp() COMMENT 'Waktu perubahan data';
