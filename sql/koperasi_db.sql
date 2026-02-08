-- phpMyAdmin SQL Dump
-- version 5.1.1deb5ubuntu1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Waktu pembuatan: 09 Feb 2026 pada 01.52
-- Versi server: 10.6.23-MariaDB-0ubuntu0.22.04.1
-- Versi PHP: 8.1.2-1ubuntu2.23

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `koperasi_db`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `akuntansi_jenis`
--

CREATE TABLE `akuntansi_jenis` (
  `id` int(11) NOT NULL,
  `cooperative_id` int(11) NOT NULL,
  `code` varchar(20) NOT NULL,
  `name` varchar(255) NOT NULL,
  `type` enum('asset','liability','equity','revenue','expense') NOT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `akuntansi_jenis`
--

INSERT INTO `akuntansi_jenis` (`id`, `cooperative_id`, `code`, `name`, `type`, `parent_id`, `is_active`, `created_at`) VALUES
(1, 0, '1000', 'Kas', 'asset', NULL, 1, '2026-02-03 14:13:20'),
(2, 0, '1100', 'Bank', 'asset', NULL, 1, '2026-02-03 14:13:20'),
(3, 0, '2000', 'Simpanan Anggota', 'liability', NULL, 1, '2026-02-03 14:13:20'),
(4, 0, '2100', 'Pinjaman Anggota', 'asset', NULL, 1, '2026-02-03 14:13:20'),
(5, 0, '3000', 'Modal', 'equity', NULL, 1, '2026-02-03 14:13:20'),
(6, 0, '4000', 'Pendapatan Bunga', 'revenue', NULL, 1, '2026-02-03 14:13:20'),
(7, 0, '5000', 'Beban Bunga', 'expense', NULL, 1, '2026-02-03 14:13:20'),
(8, 0, '5100', 'Beban Operasional', 'expense', NULL, 1, '2026-02-03 14:13:20'),
(10, 4, '1000', 'Kas', 'asset', NULL, 1, '2026-02-04 17:30:29'),
(11, 4, '1100', 'Bank', 'asset', NULL, 1, '2026-02-04 17:30:29'),
(12, 4, '2000', 'Simpanan Anggota', 'liability', NULL, 1, '2026-02-04 17:30:29'),
(13, 4, '2100', 'Pinjaman Anggota', 'asset', NULL, 1, '2026-02-04 17:30:29'),
(14, 4, '3000', 'Modal', 'equity', NULL, 1, '2026-02-04 17:30:29'),
(15, 4, '3100', 'Cadangan', 'equity', NULL, 1, '2026-02-04 17:30:29'),
(16, 4, '4000', 'Pendapatan Bunga', 'revenue', NULL, 1, '2026-02-04 17:30:29'),
(17, 4, '4100', 'Pendapatan Operasional', 'revenue', NULL, 1, '2026-02-04 17:30:29'),
(18, 4, '5000', 'Beban Bunga', 'expense', NULL, 1, '2026-02-04 17:30:29'),
(19, 4, '5100', 'Beban Operasional', 'expense', NULL, 1, '2026-02-04 17:30:29'),
(20, 4, '5200', 'Beban Administrasi', 'expense', NULL, 1, '2026-02-04 17:30:29');

-- --------------------------------------------------------

--
-- Struktur dari tabel `anggota`
--

CREATE TABLE `anggota` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `status_keanggotaan` enum('active','inactive','suspended') DEFAULT 'active',
  `nomor_anggota` varchar(20) NOT NULL,
  `joined_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `buku_besar`
--

CREATE TABLE `buku_besar` (
  `id` int(11) NOT NULL,
  `account_id` int(11) NOT NULL,
  `period` date NOT NULL,
  `beginning_balance` decimal(15,2) DEFAULT 0.00,
  `debit_total` decimal(15,2) DEFAULT 0.00,
  `credit_total` decimal(15,2) DEFAULT 0.00,
  `ending_balance` decimal(15,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `form_validation_errors`
--

CREATE TABLE `form_validation_errors` (
  `id` int(11) NOT NULL,
  `input_value` text DEFAULT NULL,
  `field_type` varchar(50) DEFAULT NULL,
  `error_type` varchar(50) DEFAULT NULL,
  `user_ip` varchar(45) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `integration_settings`
--

CREATE TABLE `integration_settings` (
  `id` int(11) NOT NULL DEFAULT 1,
  `reminder_due_days` int(11) DEFAULT 3,
  `reminder_channel` varchar(50) DEFAULT 'email',
  `payment_channel` varchar(50) DEFAULT 'transfer',
  `transfer_fee` decimal(8,2) DEFAULT 0.00,
  `cutoff_time` varchar(10) DEFAULT '17:00',
  `rat_reminder_days` int(11) DEFAULT 7,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `integration_settings`
--

INSERT INTO `integration_settings` (`id`, `reminder_due_days`, `reminder_channel`, `payment_channel`, `transfer_fee`, `cutoff_time`, `rat_reminder_days`, `created_at`, `updated_at`) VALUES
(1, 3, 'email', 'transfer', '0.00', '17:00', 7, '2026-02-08 18:27:54', '2026-02-08 18:27:54');

-- --------------------------------------------------------

--
-- Struktur dari tabel `izin_modul`
--

CREATE TABLE `izin_modul` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `is_active` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `izin_modul`
--

INSERT INTO `izin_modul` (`id`, `name`, `description`, `created_at`, `is_active`) VALUES
(1, 'view_users', 'View user list', '2026-02-03 14:13:20', 1),
(2, 'create_users', 'Create new users', '2026-02-03 14:13:20', 1),
(3, 'edit_users', 'Edit user information', '2026-02-03 14:13:20', 1),
(4, 'delete_users', 'Delete users', '2026-02-03 14:13:20', 1),
(5, 'view_members', 'View members', '2026-02-03 14:13:20', 1),
(6, 'manage_members', 'Manage member data', '2026-02-03 14:13:20', 1),
(7, 'view_savings', 'View savings transactions', '2026-02-03 14:13:20', 1),
(8, 'manage_savings', 'Manage savings', '2026-02-03 14:13:20', 1),
(9, 'view_loans', 'View loan applications', '2026-02-03 14:13:20', 1),
(10, 'manage_loans', 'Manage loans', '2026-02-03 14:13:20', 1),
(11, 'view_accounts', 'View chart of accounts', '2026-02-03 14:13:20', 1),
(12, 'manage_accounts', 'Manage accounting', '2026-02-03 14:13:20', 1),
(13, 'view_reports', 'View reports', '2026-02-03 14:13:20', 1),
(14, 'generate_reports', 'Generate financial reports', '2026-02-03 14:13:20', 1),
(15, 'vote', 'Participate in voting', '2026-02-03 14:13:20', 1),
(16, 'manage_votes', 'Manage voting sessions', '2026-02-03 14:13:20', 1),
(17, 'view_audit', 'View audit logs', '2026-02-03 14:13:20', 1),
(18, 'admin_access', 'Full administrative access', '2026-02-03 14:13:20', 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `jurnal`
--

CREATE TABLE `jurnal` (
  `id` int(11) NOT NULL,
  `entry_date` date NOT NULL,
  `description` text NOT NULL,
  `reference_number` varchar(50) DEFAULT NULL,
  `status` enum('draft','posted') DEFAULT 'draft',
  `posted_by` int(11) DEFAULT NULL,
  `posted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `jurnal_detail`
--

CREATE TABLE `jurnal_detail` (
  `id` int(11) NOT NULL,
  `journal_entry_id` int(11) NOT NULL,
  `account_id` int(11) NOT NULL,
  `debit` decimal(15,2) DEFAULT 0.00,
  `credit` decimal(15,2) DEFAULT 0.00,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `konfigurasi`
--

CREATE TABLE `konfigurasi` (
  `id` int(11) NOT NULL,
  `key_name` varchar(100) NOT NULL,
  `value` text DEFAULT NULL,
  `description` text DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `konfigurasi`
--

INSERT INTO `konfigurasi` (`id`, `key_name`, `value`, `description`, `updated_at`) VALUES
(1, 'coop_name', 'Koperasi Simpan Pinjam', 'Nama koperasi', '2026-02-03 14:13:20'),
(2, 'interest_rate_savings', '3.5', 'Suku bunga simpanan tahunan (%)', '2026-02-03 14:13:20'),
(3, 'interest_rate_loans', '12.0', 'Suku bunga pinjaman tahunan (%)', '2026-02-03 14:13:20'),
(4, 'penalty_rate', '2.0', 'Denda keterlambatan (%) per hari', '2026-02-03 14:13:20'),
(5, 'shu_distribution_ratio', '70', 'Persentase SHU untuk anggota (%)', '2026-02-03 14:13:20');

-- --------------------------------------------------------

--
-- Struktur dari tabel `koperasi_dokumen_riwayat`
--

CREATE TABLE `koperasi_dokumen_riwayat` (
  `id` int(11) NOT NULL,
  `koperasi_id` int(11) NOT NULL,
  `document_type` enum('nomor_bh','nib','nik_koperasi','modal_pokok') NOT NULL,
  `document_number_lama` varchar(50) DEFAULT NULL,
  `document_number_baru` varchar(50) DEFAULT NULL,
  `document_value_lama` decimal(15,2) DEFAULT NULL,
  `document_value_baru` decimal(15,2) DEFAULT NULL,
  `tanggal_efektif` date NOT NULL,
  `change_reason` varchar(255) DEFAULT NULL,
  `pengguna_id` int(11) DEFAULT NULL,
  `dibuat_pada` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `koperasi_jenis`
--

CREATE TABLE `koperasi_jenis` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `code` varchar(20) DEFAULT NULL,
  `category` enum('finansial','produksi','jasa','konsumsi','serba_usaha','karyawan') DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `koperasi_jenis`
--

INSERT INTO `koperasi_jenis` (`id`, `name`, `description`, `code`, `category`, `is_active`, `created_at`) VALUES
(1, 'Koperasi Simpan Pinjam (KSP)', 'Koperasi yang bergerak di bidang simpan pinjam untuk anggota, menyediakan layanan tabungan, kredit, dan jasa keuangan lainnya sesuai PP No. 7 Tahun 2021', 'KSP', 'finansial', 1, '2026-02-04 07:36:54'),
(2, 'Koperasi Konsumsi', 'Koperasi yang bergerak di bidang pemenuhan kebutuhan konsumsi anggota, menyediakan barang dan jasa kebutuhan sehari-hari sesuai PP No. 7 Tahun 2021', 'KK', 'konsumsi', 1, '2026-02-04 07:36:54'),
(3, 'Koperasi Produksi', 'Koperasi yang bergerak di bidang produksi barang/jasa anggota, mengelola pengolahan, pemasaran, dan distribusi hasil produksi sesuai PP No. 7 Tahun 2021', 'KP', 'produksi', 1, '2026-02-04 07:36:54'),
(4, 'Koperasi Pemasaran', 'Koperasi yang bergerak di bidang pemasaran hasil produksi anggota, menyediakan layanan distribusi, penjualan, dan ekspor sesuai PP No. 7 Tahun 2021', 'KPAS', 'produksi', 1, '2026-02-04 07:36:54'),
(5, 'Koperasi Jasa', 'Koperasi yang bergerak di bidang penyediaan jasa untuk anggota, seperti transportasi, komunikasi, konsultasi, dan jasa lainnya sesuai PP No. 7 Tahun 2021', 'KJ', 'jasa', 1, '2026-02-04 07:36:54'),
(6, 'Koperasi Serba Usaha (KSU)', 'Koperasi yang menjalankan berbagai jenis usaha kombinasi dari beberapa jenis koperasi dalam satu organisasi sesuai PP No. 7 Tahun 2021', 'KSU', 'serba_usaha', 1, '2026-02-04 07:36:54'),
(7, 'Koperasi Karyawan', 'Koperasi yang bergerak di bidang kesejahteraan karyawan perusahaan, menyediakan simpan pinjam, konsumsi, dan jasa untuk karyawan sesuai PP No. 7 Tahun 2021', 'KKAR', 'karyawan', 1, '2026-02-04 09:19:02'),
(8, 'Koperasi Pertanian', 'Koperasi yang bergerak di bidang pertanian, menyediakan sarana produksi, pengolahan hasil, dan pemasaran produk pertanian sesuai PP No. 7 Tahun 2021', 'KOPERTA', 'produksi', 1, '2026-02-04 09:20:25'),
(9, 'Koperasi Nelayan', 'Koperasi yang bergerak di bidang perikanan, menyediakan alat tangkap, pengolahan hasil, dan pemasaran hasil perikanan sesuai PP No. 7 Tahun 2021', 'KOPERNAL', 'produksi', 1, '2026-02-04 09:20:25'),
(10, 'Koperasi Peternakan', 'Koperasi yang bergerak di bidang peternakan, menyediakan pakan, pengolahan, dan pemasaran hasil peternakan sesuai PP No. 7 Tahun 2021', 'KOPERTAK', 'produksi', 1, '2026-02-04 09:20:25'),
(11, 'Koperasi Perdagangan', 'Koperasi yang bergerak di bidang perdagangan grosir dan eceran, menyediakan barang dagangan untuk anggota sesuai PP No. 7 Tahun 2021', 'KOPERDAG', 'konsumsi', 1, '2026-02-04 09:20:25'),
(12, 'Koperasi Pondok Pesantren', 'Koperasi yang bergerak di lingkungan pondok pesantren, menyediakan kebutuhan santri dan wali santri sesuai PP No. 7 Tahun 2021', 'KOPONTREN', 'serba_usaha', 1, '2026-02-04 09:20:25');

-- --------------------------------------------------------

--
-- Struktur dari tabel `koperasi_keuangan_pengaturan`
--

CREATE TABLE `koperasi_keuangan_pengaturan` (
  `id` int(11) NOT NULL,
  `cooperative_id` int(11) NOT NULL,
  `tahun_buku` year(4) NOT NULL,
  `periode_mulai` date NOT NULL,
  `periode_akhir` date NOT NULL,
  `simpanan_pokok` decimal(15,2) DEFAULT 0.00,
  `simpanan_wajib` decimal(15,2) DEFAULT 0.00,
  `bunga_pinjaman` decimal(5,2) DEFAULT 12.00,
  `denda_telat` decimal(5,2) DEFAULT 2.00,
  `periode_shu` enum('yearly','semi_annual','quarterly') DEFAULT 'yearly',
  `status` enum('active','inactive','closed') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `koperasi_keuangan_pengaturan`
--

INSERT INTO `koperasi_keuangan_pengaturan` (`id`, `cooperative_id`, `tahun_buku`, `periode_mulai`, `periode_akhir`, `simpanan_pokok`, `simpanan_wajib`, `bunga_pinjaman`, `denda_telat`, `periode_shu`, `status`, `created_at`, `updated_at`, `created_by`) VALUES
(4, 4, 2026, '2026-01-01', '2026-12-31', '100000.00', '50000.00', '12.00', '2.00', 'yearly', 'active', '2026-02-04 17:30:29', '2026-02-04 17:30:29', 5);

-- --------------------------------------------------------

--
-- Struktur dari tabel `koperasi_pengurus`
--

CREATE TABLE `koperasi_pengurus` (
  `id` int(11) NOT NULL,
  `koperasi_tenant_id` int(11) NOT NULL,
  `jabatan` enum('ketua','wakil_ketua','sekretaris','wakil_sekretaris','bendahara','ketua_pengawas','anggota_pengawas') NOT NULL,
  `orang_id` int(11) NOT NULL,
  `tanggal_mulai` date NOT NULL,
  `tanggal_akhir` date DEFAULT NULL,
  `surat_keputusan` varchar(255) DEFAULT NULL,
  `dibuat_pada` timestamp NOT NULL DEFAULT current_timestamp(),
  `diperbarui_pada` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `koperasi_status_riwayat`
--

CREATE TABLE `koperasi_status_riwayat` (
  `id` int(11) NOT NULL,
  `koperasi_id` int(11) NOT NULL,
  `status_sebelumnya` varchar(50) DEFAULT NULL,
  `status_baru` varchar(50) NOT NULL,
  `tanggal_efektif` date DEFAULT NULL,
  `dokumen_path` varchar(255) DEFAULT NULL,
  `pengguna_id` int(11) DEFAULT NULL,
  `dibuat_pada` timestamp NOT NULL DEFAULT current_timestamp(),
  `change_reason` varchar(255) DEFAULT NULL,
  `approval_status` enum('pending','approved','rejected') DEFAULT 'approved',
  `pengguna_disetujui_id` int(11) DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `koperasi_tenant`
--

CREATE TABLE `koperasi_tenant` (
  `id` int(11) NOT NULL,
  `nama_koperasi` varchar(255) NOT NULL,
  `jenis_koperasi` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`jenis_koperasi`)),
  `badan_hukum` varchar(255) DEFAULT NULL,
  `status_badan_hukum` enum('belum_terdaftar','terdaftar','badan_hukum') DEFAULT 'belum_terdaftar',
  `tanggal_status_terakhir` date DEFAULT NULL,
  `catatan_status` text DEFAULT NULL,
  `tanggal_pendirian` date DEFAULT NULL,
  `npwp` varchar(50) DEFAULT NULL,
  `nomor_badan_hukum` varchar(50) DEFAULT NULL,
  `nib` varchar(20) DEFAULT NULL,
  `nik_koperasi` varchar(20) DEFAULT NULL,
  `modal_pokok` decimal(15,2) DEFAULT 0.00,
  `alamat_legal` text DEFAULT NULL,
  `kontak_resmi` varchar(255) DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `dibuat_oleh` int(11) DEFAULT NULL,
  `dibuat_pada` timestamp NULL DEFAULT current_timestamp(),
  `diperbarui_pada` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `provinsi_id` int(11) DEFAULT NULL,
  `kabkota_id` int(11) DEFAULT NULL,
  `kecamatan_id` int(11) DEFAULT NULL,
  `kelurahan_id` int(11) DEFAULT NULL,
  `allowed_occupations` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`allowed_occupations`)),
  `savings_settings` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`savings_settings`)),
  `loans_settings` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`loans_settings`)),
  `reports_settings` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`reports_settings`)),
  `akta_pendirian` varchar(255) DEFAULT NULL,
  `ad_art` varchar(255) DEFAULT NULL,
  `berita_acara_rapat` varchar(255) DEFAULT NULL,
  `rencana_kegiatan` varchar(255) DEFAULT NULL,
  `dewan_pengawas_count` int(11) DEFAULT 0,
  `dewan_pengurus_count` int(11) DEFAULT 0,
  `anggota_count` int(11) DEFAULT 0,
  `simpanan_pokok_total` decimal(15,2) DEFAULT 0.00,
  `rat_terakhir` date DEFAULT NULL,
  `laporan_tahunan_terakhir` date DEFAULT NULL,
  `rencana_kerja_3tahun` varchar(255) DEFAULT NULL,
  `pernyataan_admin` varchar(255) DEFAULT NULL,
  `daftar_sarana` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `koperasi_tenant`
--

INSERT INTO `koperasi_tenant` (`id`, `nama_koperasi`, `jenis_koperasi`, `badan_hukum`, `status_badan_hukum`, `tanggal_status_terakhir`, `catatan_status`, `tanggal_pendirian`, `npwp`, `nomor_badan_hukum`, `nib`, `nik_koperasi`, `modal_pokok`, `alamat_legal`, `kontak_resmi`, `logo`, `dibuat_oleh`, `dibuat_pada`, `diperbarui_pada`, `provinsi_id`, `kabkota_id`, `kecamatan_id`, `kelurahan_id`, `allowed_occupations`, `savings_settings`, `loans_settings`, `reports_settings`, `akta_pendirian`, `ad_art`, `berita_acara_rapat`, `rencana_kegiatan`, `dewan_pengawas_count`, `dewan_pengurus_count`, `anggota_count`, `simpanan_pokok_total`, `rat_terakhir`, `laporan_tahunan_terakhir`, `rencana_kerja_3tahun`, `pernyataan_admin`, `daftar_sarana`) VALUES
(8, 'KSP POLRES SAMOSIR', '[\"1\"]', NULL, 'belum_terdaftar', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', 'jl. Danau Toba no 03, PASAR PANGURURAN, PANGURURAN, KABUPATEN SAMOSIR, SUMATERA UTARA, 22392', NULL, NULL, NULL, '2026-02-08 11:52:06', '2026-02-08 11:52:06', 3, 40, 590, 10617, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, '0.00', NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `loan_types`
--

CREATE TABLE `loan_types` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `interest_rate` decimal(8,4) DEFAULT 0.0000,
  `interest_method` enum('flat','menurun') DEFAULT 'flat',
  `max_tenor_months` int(11) DEFAULT 0,
  `max_plafon_savings_ratio` decimal(8,2) DEFAULT 0.00,
  `max_installment_income_ratio` decimal(8,2) DEFAULT 0.00,
  `admin_fee` decimal(15,2) DEFAULT 0.00,
  `provision_fee` decimal(8,2) DEFAULT 0.00,
  `penalty_rate` decimal(8,4) DEFAULT 0.0000,
  `description` text DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `insurance_rate` decimal(8,2) DEFAULT 0.00,
  `require_insurance` tinyint(1) DEFAULT 0,
  `ltv_ratio` decimal(8,2) DEFAULT 0.00,
  `collateral_type` varchar(100) DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `loan_types`
--

INSERT INTO `loan_types` (`id`, `name`, `interest_rate`, `interest_method`, `max_tenor_months`, `max_plafon_savings_ratio`, `max_installment_income_ratio`, `admin_fee`, `provision_fee`, `penalty_rate`, `description`, `is_active`, `created_at`, `updated_at`, `insurance_rate`, `require_insurance`, `ltv_ratio`, `collateral_type`) VALUES
(1, 'Konsumtif', '18.0000', 'menurun', 24, '3.00', '40.00', '50000.00', '1.50', '0.1000', 'Pinjaman konsumsi, plafon kecil-menengah', 1, '2026-02-08 17:40:36', '2026-02-08 17:58:19', '0.00', 0, '0.00', ''),
(2, 'Produktif', '14.0000', 'menurun', 36, '5.00', '40.00', '75000.00', '2.00', '0.1000', 'Modal usaha/produktif', 1, '2026-02-08 17:40:36', '2026-02-08 17:40:36', '0.00', 0, '0.00', ''),
(3, 'Darurat', '12.0000', 'flat', 6, '1.00', '30.00', '25000.00', '1.00', '0.0500', 'Plafon kecil, proses cepat', 1, '2026-02-08 17:40:36', '2026-02-08 17:40:36', '0.00', 0, '0.00', '');

-- --------------------------------------------------------

--
-- Struktur dari tabel `log_audit`
--

CREATE TABLE `log_audit` (
  `id` int(11) NOT NULL,
  `table_name` varchar(50) NOT NULL,
  `record_id` int(11) NOT NULL,
  `field_name` varchar(50) NOT NULL,
  `old_value` text DEFAULT NULL,
  `new_value` text DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `action` enum('INSERT','UPDATE','DELETE') NOT NULL,
  `details` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`details`)),
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `modal_pokok_perubahan`
--

CREATE TABLE `modal_pokok_perubahan` (
  `id` int(11) NOT NULL,
  `cooperative_id` int(11) NOT NULL,
  `modal_pokok_lama` decimal(15,2) NOT NULL,
  `modal_pokok_baru` decimal(15,2) NOT NULL,
  `persentase_perubahan` decimal(5,2) NOT NULL,
  `tanggal_efektif` date NOT NULL,
  `perubahan_type` enum('manual','rat','other') NOT NULL,
  `referensi_id` int(11) DEFAULT NULL,
  `alasan_perubahan` text DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `modul`
--

CREATE TABLE `modul` (
  `id` int(11) NOT NULL,
  `nama` varchar(50) NOT NULL,
  `nama_tampil` varchar(100) NOT NULL,
  `ikon` varchar(50) DEFAULT NULL,
  `permission_required` varchar(100) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `urutan` int(11) DEFAULT 0,
  `show_in_navbar` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `modul`
--

INSERT INTO `modul` (`id`, `nama`, `nama_tampil`, `ikon`, `permission_required`, `is_active`, `urutan`, `show_in_navbar`) VALUES
(1, 'dashboard', 'Dashboard', 'bi-house-door', NULL, 1, 1, 0),
(2, 'anggota', 'Data Anggota', 'bi-people-fill', 'view_members', 1, 2, 0),
(3, 'simpanan', 'Simpanan', 'bi-wallet2', 'view_savings', 1, 3, 0),
(4, 'pinjaman', 'Pinjaman', 'bi-cash-coin', 'view_loans', 1, 4, 0),
(5, 'laporan', 'Laporan', 'bi-file-earmark-bar-graph', 'view_reports', 1, 5, 0),
(6, 'pengaturan', 'Pengaturan', 'bi-gear', 'admin_access', 1, 6, 1),
(7, 'coop_details', 'Detail Koperasi', 'bi-building', 'manage_cooperative', 1, 7, 0),
(8, 'profil', 'Profil', 'bi-person', NULL, 1, 8, 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `notifikasi`
--

CREATE TABLE `notifikasi` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `type` enum('info','warning','error') DEFAULT 'info',
  `sent_at` timestamp NULL DEFAULT current_timestamp(),
  `read_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `orang`
--

CREATE TABLE `orang` (
  `id` int(11) NOT NULL,
  `pengguna_id` int(11) NOT NULL,
  `nama_lengkap` varchar(255) NOT NULL,
  `nama_depan` varchar(100) DEFAULT NULL,
  `nama_tengah` varchar(100) DEFAULT NULL,
  `nama_belakang` varchar(100) DEFAULT NULL,
  `hp` varchar(20) DEFAULT NULL,
  `hp_alternatif` varchar(20) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `nik` varchar(20) DEFAULT NULL,
  `kewarganegaraan` varchar(50) DEFAULT NULL,
  `agama` varchar(50) DEFAULT NULL,
  `jenis_kelamin` enum('L','P') DEFAULT NULL,
  `tempat_lahir` varchar(100) DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `alamat_lengkap` text DEFAULT NULL,
  `province_id` int(11) DEFAULT NULL,
  `regency_id` int(11) DEFAULT NULL,
  `district_id` int(11) DEFAULT NULL,
  `village_id` int(11) DEFAULT NULL,
  `nama_jalan` varchar(255) DEFAULT NULL,
  `nomor_rumah` varchar(10) DEFAULT NULL,
  `rt` varchar(5) DEFAULT NULL,
  `rw` varchar(5) DEFAULT NULL,
  `postal_code` varchar(10) DEFAULT NULL,
  `pekerjaan` varchar(100) DEFAULT NULL,
  `instansi` varchar(255) DEFAULT NULL,
  `jabatan` varchar(100) DEFAULT NULL,
  `catatan` text DEFAULT NULL,
  `dibuat_oleh` int(11) DEFAULT NULL,
  `dibuat_pada` timestamp NULL DEFAULT current_timestamp(),
  `diperbarui_pada` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `orang`
--

INSERT INTO `orang` (`id`, `pengguna_id`, `nama_lengkap`, `nama_depan`, `nama_tengah`, `nama_belakang`, `hp`, `hp_alternatif`, `email`, `nik`, `kewarganegaraan`, `agama`, `jenis_kelamin`, `tempat_lahir`, `tanggal_lahir`, `alamat_lengkap`, `province_id`, `regency_id`, `district_id`, `village_id`, `nama_jalan`, `nomor_rumah`, `rt`, `rw`, `postal_code`, `pekerjaan`, `instansi`, `jabatan`, `catatan`, `dibuat_oleh`, `dibuat_pada`, `diperbarui_pada`) VALUES
(5, 10, 'admin paling baik di dunia', 'admin', 'paling baik di', 'dunia', '6281265511982', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'jl. Danau Toba no 03, PASAR PANGURURAN, PANGURURAN, KABUPATEN SAMOSIR, SUMATERA UTARA, 22392', 3, 40, 590, 10617, 'jl. Danau Toba no', '03', NULL, NULL, '22392', 'Administrator Koperasi', 'KSP POLRES SAMOSIR', 'Administrator', 'Dibuat otomatis saat registrasi koperasi', 10, '2026-02-08 04:52:06', '2026-02-08 11:52:06');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pekerjaan_master`
--

CREATE TABLE `pekerjaan_master` (
  `id` int(11) NOT NULL,
  `nama_pekerjaan` varchar(100) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `dibuat_pada` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pekerjaan_master`
--

INSERT INTO `pekerjaan_master` (`id`, `nama_pekerjaan`, `deskripsi`, `dibuat_pada`) VALUES
(1, 'PNS', 'Pegawai Negeri Sipil', '2026-02-08 14:27:47'),
(2, 'Swasta', 'Karyawan Swasta', '2026-02-08 14:27:47'),
(3, 'Wiraswasta', 'Wiraswasta/Entrepreneur', '2026-02-08 14:27:47'),
(4, 'Pelajar', 'Pelajar/Mahasiswa', '2026-02-08 14:27:47'),
(5, 'Ibu Rumah Tangga', 'Ibu Rumah Tangga', '2026-02-08 14:27:47'),
(6, 'TNI/Polri', 'Tentara Nasional Indonesia/Polisi Republik Indonesia', '2026-02-08 14:27:47'),
(7, 'Buruh', 'Buruh/Pekerja Kasar', '2026-02-08 14:27:47'),
(8, 'Lainnya', 'Pekerjaan Lainnya', '2026-02-08 14:27:47');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pekerjaan_pangkat`
--

CREATE TABLE `pekerjaan_pangkat` (
  `id` int(11) NOT NULL,
  `pekerjaan_master_id` int(11) DEFAULT NULL,
  `nama_pangkat` varchar(100) NOT NULL,
  `level` int(11) DEFAULT NULL,
  `deskripsi` text DEFAULT NULL,
  `dibuat_pada` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pekerjaan_pangkat`
--

INSERT INTO `pekerjaan_pangkat` (`id`, `pekerjaan_master_id`, `nama_pangkat`, `level`, `deskripsi`, `dibuat_pada`) VALUES
(1, 1, 'I/a', 1, 'Pangkat I/a', '2026-02-08 14:27:53'),
(2, 1, 'I/b', 2, 'Pangkat I/b', '2026-02-08 14:27:53'),
(3, 1, 'I/c', 3, 'Pangkat I/c', '2026-02-08 14:27:53'),
(4, 1, 'I/d', 4, 'Pangkat I/d', '2026-02-08 14:27:53'),
(5, 1, 'II/a', 5, 'Pangkat II/a', '2026-02-08 14:27:53'),
(6, 1, 'II/b', 6, 'Pangkat II/b', '2026-02-08 14:27:53'),
(7, 1, 'II/c', 7, 'Pangkat II/c', '2026-02-08 14:27:53'),
(8, 1, 'II/d', 8, 'Pangkat II/d', '2026-02-08 14:27:53'),
(9, 1, 'III/a', 9, 'Pangkat III/a', '2026-02-08 14:27:53'),
(10, 1, 'III/b', 10, 'Pangkat III/b', '2026-02-08 14:27:53'),
(11, 1, 'III/c', 11, 'Pangkat III/c', '2026-02-08 14:27:53'),
(12, 1, 'III/d', 12, 'Pangkat III/d', '2026-02-08 14:27:53'),
(13, 1, 'IV/a', 13, 'Pangkat IV/a', '2026-02-08 14:27:53'),
(14, 1, 'IV/b', 14, 'Pangkat IV/b', '2026-02-08 14:27:53'),
(15, 1, 'IV/c', 15, 'Pangkat IV/c', '2026-02-08 14:27:53'),
(16, 1, 'IV/d', 16, 'Pangkat IV/d', '2026-02-08 14:27:53'),
(17, 2, 'Staff', 1, 'Staff', '2026-02-08 14:27:53'),
(18, 2, 'Supervisor', 2, 'Supervisor', '2026-02-08 14:27:53'),
(19, 2, 'Manager', 3, 'Manager', '2026-02-08 14:27:53'),
(20, 2, 'Direktur', 4, 'Direktur', '2026-02-08 14:27:53'),
(21, 3, 'Pemilik Usaha', 1, 'Pemilik Usaha Kecil', '2026-02-08 14:27:53'),
(22, 3, 'Entrepreneur', 2, 'Wiraswasta', '2026-02-08 14:27:53'),
(23, 6, 'Prada', 1, 'Prada', '2026-02-08 14:27:53'),
(24, 6, 'Pratu', 2, 'Pratu', '2026-02-08 14:27:53'),
(25, 6, 'Praka', 3, 'Praka', '2026-02-08 14:27:53'),
(26, 6, 'Kopda', 4, 'Kopda', '2026-02-08 14:27:53'),
(27, 6, 'Koptu', 5, 'Koptu', '2026-02-08 14:27:53'),
(28, 6, 'Kopka', 6, 'Kopka', '2026-02-08 14:27:53'),
(29, 6, 'Serma', 7, 'Serma', '2026-02-08 14:27:53'),
(30, 6, 'Serka', 8, 'Serka', '2026-02-08 14:27:53'),
(31, 6, 'Sertu', 9, 'Sertu', '2026-02-08 14:27:53'),
(32, 6, 'Serda', 10, 'Serda', '2026-02-08 14:27:53');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pengawas`
--

CREATE TABLE `pengawas` (
  `id` int(11) NOT NULL,
  `pengguna_id` int(11) NOT NULL,
  `jabatan` varchar(100) NOT NULL,
  `periode_start` date NOT NULL,
  `periode_end` date DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `pengguna`
--

CREATE TABLE `pengguna` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `sandi_hash` varchar(255) NOT NULL,
  `sumber_pengguna_id` int(11) NOT NULL,
  `status` enum('active','inactive','pending') DEFAULT 'active',
  `dibuat_pada` timestamp NULL DEFAULT current_timestamp(),
  `diperbarui_pada` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `hp` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `pengguna`
--

INSERT INTO `pengguna` (`id`, `username`, `sandi_hash`, `sumber_pengguna_id`, `status`, `dibuat_pada`, `diperbarui_pada`, `hp`) VALUES
(10, 'root', '$2y$10$mr69A.e7sEpZN2CAvGPgYu2wUWSJZXgbgSx.f9pB/Bk4/PxrsjkRS', 1, 'active', '2026-02-08 11:52:06', '2026-02-08 11:52:06', '6281265511982');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pengguna_izin_peran`
--

CREATE TABLE `pengguna_izin_peran` (
  `peran_jenis_id` int(11) NOT NULL,
  `izin_modul_id` int(11) NOT NULL,
  `assigned_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `pengguna_izin_peran`
--

INSERT INTO `pengguna_izin_peran` (`peran_jenis_id`, `izin_modul_id`, `assigned_at`) VALUES
(2, 1, '2026-02-08 11:52:06'),
(2, 2, '2026-02-08 11:52:06'),
(2, 3, '2026-02-08 11:52:06'),
(2, 4, '2026-02-08 11:52:06'),
(2, 5, '2026-02-08 11:52:06'),
(2, 6, '2026-02-08 11:52:06'),
(2, 7, '2026-02-08 11:52:06'),
(2, 8, '2026-02-08 11:52:06'),
(2, 9, '2026-02-08 11:52:06'),
(2, 10, '2026-02-08 11:52:06'),
(2, 11, '2026-02-08 11:52:06'),
(2, 12, '2026-02-08 11:52:06'),
(2, 13, '2026-02-08 11:52:06'),
(2, 14, '2026-02-08 11:52:06'),
(2, 15, '2026-02-08 11:52:06'),
(2, 16, '2026-02-08 11:52:06'),
(2, 17, '2026-02-08 11:52:06'),
(2, 18, '2026-02-08 11:52:06');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pengguna_peran`
--

CREATE TABLE `pengguna_peran` (
  `pengguna_id` int(11) NOT NULL,
  `peran_jenis_id` int(11) NOT NULL,
  `assigned_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `pengguna_peran`
--

INSERT INTO `pengguna_peran` (`pengguna_id`, `peran_jenis_id`, `assigned_at`) VALUES
(10, 2, '2026-02-08 11:52:06');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pengurus`
--

CREATE TABLE `pengurus` (
  `id` int(11) NOT NULL,
  `pengguna_id` int(11) NOT NULL,
  `jabatan` varchar(100) NOT NULL,
  `periode_start` date NOT NULL,
  `periode_end` date DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `penjualan_agen`
--

CREATE TABLE `penjualan_agen` (
  `id` int(11) NOT NULL,
  `agent_id` int(11) NOT NULL,
  `pesanan_id` int(11) NOT NULL,
  `commission` decimal(15,2) NOT NULL,
  `approved` tinyint(1) DEFAULT 0,
  `pengguna_disetujui_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `peran_izin`
--

CREATE TABLE `peran_izin` (
  `peran_jenis_id` int(11) NOT NULL,
  `permission_id` int(11) NOT NULL,
  `assigned_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `peran_izin`
--

INSERT INTO `peran_izin` (`peran_jenis_id`, `permission_id`, `assigned_at`) VALUES
(1, 1, '2026-02-07 18:25:07'),
(1, 2, '2026-02-07 18:25:07'),
(1, 3, '2026-02-07 18:25:07'),
(1, 4, '2026-02-07 18:25:07'),
(2, 1, '2026-02-07 18:25:07'),
(2, 2, '2026-02-07 18:25:07'),
(2, 3, '2026-02-07 18:25:07');

-- --------------------------------------------------------

--
-- Struktur dari tabel `peran_jenis`
--

CREATE TABLE `peran_jenis` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `peran_jenis`
--

INSERT INTO `peran_jenis` (`id`, `name`, `description`, `created_at`) VALUES
(1, 'super_admin', 'Super administrator with all access', '2026-02-03 14:13:20'),
(2, 'admin', 'Administrator/Pengurus', '2026-02-03 14:13:20'),
(3, 'pengawas', 'Pengawas with read/approve access', '2026-02-03 14:13:20'),
(4, 'anggota', 'Regular member', '2026-02-03 14:13:20'),
(5, 'calon_anggota', 'Prospective member', '2026-02-03 14:13:20');

-- --------------------------------------------------------

--
-- Struktur dari tabel `permissions`
--

CREATE TABLE `permissions` (
  `id` int(11) NOT NULL,
  `permission_key` varchar(50) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `permissions`
--

INSERT INTO `permissions` (`id`, `permission_key`, `description`, `created_at`) VALUES
(1, 'manage_cooperative', 'Manage cooperative details and settings', '2026-02-07 18:25:07'),
(2, 'manage_members', 'Manage cooperative members', '2026-02-07 18:25:07'),
(3, 'view_reports', 'View financial reports', '2026-02-07 18:25:07'),
(4, 'approve_loans', 'Approve loan applications', '2026-02-07 18:25:07');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pesanan`
--

CREATE TABLE `pesanan` (
  `id` int(11) NOT NULL,
  `pengguna_id` int(11) DEFAULT NULL,
  `dibuat_pada` timestamp NULL DEFAULT current_timestamp(),
  `total` decimal(15,2) NOT NULL,
  `status` enum('pending','confirmed','shipped','delivered','cancelled') DEFAULT 'pending',
  `alamat_pengiriman` text DEFAULT NULL,
  `status_pembayaran` enum('unpaid','paid','refunded') DEFAULT 'unpaid'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `pesanan_detail`
--

CREATE TABLE `pesanan_detail` (
  `id` int(11) NOT NULL,
  `pesanan_id` int(11) NOT NULL,
  `produk_id` int(11) NOT NULL,
  `kuantitas` int(11) NOT NULL,
  `harga_satuan` decimal(15,2) NOT NULL,
  `total` decimal(15,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `pinjaman`
--

CREATE TABLE `pinjaman` (
  `id` int(11) NOT NULL,
  `anggota_id` int(11) NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `interest_rate` decimal(5,2) NOT NULL,
  `term_months` int(11) NOT NULL,
  `status` enum('pending','approved','active','paid','rejected') DEFAULT 'pending',
  `approved_at` timestamp NULL DEFAULT NULL,
  `pengguna_disetujui_id` int(11) DEFAULT NULL,
  `disbursed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `pinjaman_angsuran`
--

CREATE TABLE `pinjaman_angsuran` (
  `id` int(11) NOT NULL,
  `pinjaman_id` int(11) NOT NULL,
  `angsuran_ke` int(11) NOT NULL,
  `tanggal_jatuh_tempo` date NOT NULL,
  `principal_amount` decimal(15,2) NOT NULL,
  `interest_amount` decimal(15,2) NOT NULL,
  `total_amount` decimal(15,2) NOT NULL,
  `paid_amount` decimal(15,2) DEFAULT 0.00,
  `paid_at` timestamp NULL DEFAULT NULL,
  `status` enum('pending','paid','overdue') DEFAULT 'pending',
  `penalty` decimal(15,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `produk`
--

CREATE TABLE `produk` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(15,2) NOT NULL,
  `stock_quantity` int(11) DEFAULT 0,
  `category` varchar(100) DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `rat_checklist`
--

CREATE TABLE `rat_checklist` (
  `id` int(11) NOT NULL,
  `koperasi_tenant_id` int(11) NOT NULL,
  `item` varchar(200) NOT NULL,
  `required` tinyint(1) DEFAULT 1,
  `status` enum('pending','done') DEFAULT 'pending',
  `notes` text DEFAULT NULL,
  `order_no` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `rat_sesi`
--

CREATE TABLE `rat_sesi` (
  `id` int(11) NOT NULL,
  `cooperative_id` int(11) NOT NULL,
  `tahun` int(11) NOT NULL,
  `tanggal_rapat` date NOT NULL,
  `tempat` varchar(255) DEFAULT NULL,
  `agenda` text DEFAULT NULL,
  `status` enum('scheduled','in_progress','completed','cancelled') DEFAULT 'scheduled',
  `modal_pokok_sebelum` decimal(15,2) DEFAULT 0.00,
  `modal_pokok_setelah` decimal(15,2) DEFAULT 0.00,
  `persentase_perubahan` decimal(5,2) DEFAULT 0.00,
  `alasan_perubahan` text DEFAULT NULL,
  `approved_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `savings_types`
--

CREATE TABLE `savings_types` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `interest_rate` decimal(8,4) DEFAULT 0.0000,
  `min_deposit` decimal(15,2) DEFAULT 0.00,
  `admin_fee` decimal(15,2) DEFAULT 0.00,
  `penalty_rate` decimal(8,4) DEFAULT 0.0000,
  `description` text DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `lock_period_days` int(11) DEFAULT 0,
  `early_withdraw_fee` decimal(8,2) DEFAULT 0.00,
  `min_balance` decimal(15,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `savings_types`
--

INSERT INTO `savings_types` (`id`, `name`, `interest_rate`, `min_deposit`, `admin_fee`, `penalty_rate`, `description`, `is_active`, `created_at`, `updated_at`, `lock_period_days`, `early_withdraw_fee`, `min_balance`) VALUES
(1, 'Simpanan Pokok', '0.0000', '0.00', '0.00', '0.0000', 'Simpanan wajib saat masuk anggota', 1, '2026-02-08 17:59:38', '2026-02-08 17:59:38', 0, '0.00', '0.00'),
(2, 'Simpanan Wajib', '0.0000', '50000.00', '0.00', '0.0000', 'Setoran bulanan anggota', 1, '2026-02-08 17:59:38', '2026-02-08 17:59:38', 0, '0.00', '0.00'),
(3, 'Simpanan Sukarela', '4.0000', '50000.00', '0.00', '0.0000', 'Sukarela, bisa ditarik, bunga ringan', 1, '2026-02-08 17:59:38', '2026-02-08 17:59:38', 0, '0.00', '0.00');

-- --------------------------------------------------------

--
-- Struktur dari tabel `shu_anggota`
--

CREATE TABLE `shu_anggota` (
  `id` int(11) NOT NULL,
  `anggota_id` int(11) NOT NULL,
  `shu_distribution_id` int(11) NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `paid` tinyint(1) DEFAULT 0,
  `paid_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `shu_distribusi`
--

CREATE TABLE `shu_distribusi` (
  `id` int(11) NOT NULL,
  `year` year(4) NOT NULL,
  `total_shu` decimal(15,2) NOT NULL,
  `distributed_at` timestamp NULL DEFAULT NULL,
  `status` enum('calculated','distributed') DEFAULT 'calculated',
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `simpanan_jenis`
--

CREATE TABLE `simpanan_jenis` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `interest_rate` decimal(5,2) DEFAULT 0.00,
  `minimum_balance` decimal(15,2) DEFAULT 0.00,
  `description` text DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `simpanan_transaksi`
--

CREATE TABLE `simpanan_transaksi` (
  `id` int(11) NOT NULL,
  `anggota_id` int(11) NOT NULL,
  `type_id` int(11) NOT NULL,
  `nilai` decimal(15,2) NOT NULL,
  `transaction_type` enum('deposit','withdraw') NOT NULL,
  `balance_after` decimal(15,2) NOT NULL,
  `tanggal_transaksi` date DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `pengguna_disetujui_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `tenant_konfigurasi`
--

CREATE TABLE `tenant_konfigurasi` (
  `id` int(11) NOT NULL,
  `cooperative_id` int(11) NOT NULL,
  `active_modules` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`active_modules`)),
  `feature_flags` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`feature_flags`)),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `tenant_konfigurasi`
--

INSERT INTO `tenant_konfigurasi` (`id`, `cooperative_id`, `active_modules`, `feature_flags`, `created_at`, `updated_at`) VALUES
(3, 4, '[]', '{\"multi_tenant\":true,\"modular\":true}', '2026-02-04 17:30:29', '2026-02-04 17:30:29');

-- --------------------------------------------------------

--
-- Struktur dari tabel `voting`
--

CREATE TABLE `voting` (
  `id` int(11) NOT NULL,
  `agenda` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL,
  `status` enum('draft','active','closed') DEFAULT 'draft',
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `voting_suara`
--

CREATE TABLE `voting_suara` (
  `id` int(11) NOT NULL,
  `vote_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `choice` varchar(100) NOT NULL,
  `voted_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Stand-in struktur untuk tampilan `v_koperasi_lengkap`
-- (Lihat di bawah untuk tampilan aktual)
--
CREATE TABLE `v_koperasi_lengkap` (
);

-- --------------------------------------------------------

--
-- Struktur untuk view `v_koperasi_lengkap`
--
DROP TABLE IF EXISTS `v_koperasi_lengkap`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_koperasi_lengkap`  AS SELECT `c`.`id` AS `id`, `c`.`nama_koperasi` AS `nama`, `c`.`jenis_koperasi` AS `jenis`, `c`.`badan_hukum` AS `badan_hukum`, `c`.`tanggal_pendirian` AS `tanggal_pendirian`, `c`.`npwp` AS `npwp`, `c`.`alamat_legal` AS `alamat_legal`, `c`.`kontak_resmi` AS `kontak_resmi`, `c`.`logo` AS `logo`, `c`.`dibuat_oleh` AS `dibuat_oleh`, `c`.`dibuat_pada` AS `dibuat_pada`, `c`.`diperbarui_pada` AS `diperbarui_pada`, `p`.`name` AS `province_name`, `r`.`name` AS `regency_name`, `d`.`name` AS `district_name`, `v`.`name` AS `village_name` FROM ((((`koperasi_tenant` `c` left join `alamat_db`.`provinces` `p` on(`c`.`provinsi_id` = `p`.`id`)) left join `alamat_db`.`regencies` `r` on(`c`.`kabkota_id` = `r`.`id`)) left join `alamat_db`.`districts` `d` on(`c`.`kecamatan_id` = `d`.`id`)) left join `alamat_db`.`villages` `v` on(`c`.`kelurahan_id` = `v`.`id`)) ;

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `akuntansi_jenis`
--
ALTER TABLE `akuntansi_jenis`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_cooperative_code` (`cooperative_id`,`code`),
  ADD KEY `parent_id` (`parent_id`),
  ADD KEY `idx_chart_cooperative` (`cooperative_id`);

--
-- Indeks untuk tabel `anggota`
--
ALTER TABLE `anggota`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nomor_anggota` (`nomor_anggota`),
  ADD KEY `idx_anggota_user` (`user_id`);

--
-- Indeks untuk tabel `buku_besar`
--
ALTER TABLE `buku_besar`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `account_id` (`account_id`,`period`);

--
-- Indeks untuk tabel `form_validation_errors`
--
ALTER TABLE `form_validation_errors`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `integration_settings`
--
ALTER TABLE `integration_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `izin_modul`
--
ALTER TABLE `izin_modul`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indeks untuk tabel `jurnal`
--
ALTER TABLE `jurnal`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_journal_posted_by` (`posted_by`);

--
-- Indeks untuk tabel `jurnal_detail`
--
ALTER TABLE `jurnal_detail`
  ADD PRIMARY KEY (`id`),
  ADD KEY `journal_entry_id` (`journal_entry_id`),
  ADD KEY `account_id` (`account_id`);

--
-- Indeks untuk tabel `konfigurasi`
--
ALTER TABLE `konfigurasi`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `key_name` (`key_name`);

--
-- Indeks untuk tabel `koperasi_dokumen_riwayat`
--
ALTER TABLE `koperasi_dokumen_riwayat`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_cooperative_document` (`koperasi_id`,`document_type`),
  ADD KEY `idx_tanggal_efektif` (`tanggal_efektif`),
  ADD KEY `idx_document_type` (`document_type`),
  ADD KEY `koperasi_dok_fk_pengguna` (`pengguna_id`);

--
-- Indeks untuk tabel `koperasi_jenis`
--
ALTER TABLE `koperasi_jenis`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indeks untuk tabel `koperasi_keuangan_pengaturan`
--
ALTER TABLE `koperasi_keuangan_pengaturan`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_cooperative_year` (`cooperative_id`,`tahun_buku`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `idx_cooperative_year` (`cooperative_id`,`tahun_buku`),
  ADD KEY `idx_tahun_buku` (`tahun_buku`);

--
-- Indeks untuk tabel `koperasi_pengurus`
--
ALTER TABLE `koperasi_pengurus`
  ADD PRIMARY KEY (`id`),
  ADD KEY `koperasi_tenant_id` (`koperasi_tenant_id`),
  ADD KEY `orang_id` (`orang_id`);

--
-- Indeks untuk tabel `koperasi_status_riwayat`
--
ALTER TABLE `koperasi_status_riwayat`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_cooperative_id` (`koperasi_id`),
  ADD KEY `idx_tanggal_efektif` (`tanggal_efektif`),
  ADD KEY `idx_approval_status` (`approval_status`),
  ADD KEY `koperasi_status_fk_pengguna` (`pengguna_id`),
  ADD KEY `koperasi_status_fk_pengguna_approve` (`pengguna_disetujui_id`);

--
-- Indeks untuk tabel `koperasi_tenant`
--
ALTER TABLE `koperasi_tenant`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_cooperative_province` (`provinsi_id`),
  ADD KEY `idx_cooperative_regency` (`kabkota_id`),
  ADD KEY `idx_cooperative_district` (`kecamatan_id`),
  ADD KEY `idx_cooperative_village` (`kelurahan_id`),
  ADD KEY `idx_nomor_bh` (`nomor_badan_hukum`),
  ADD KEY `idx_nib` (`nib`),
  ADD KEY `idx_nik_koperasi` (`nik_koperasi`),
  ADD KEY `idx_status_badan_hukum` (`status_badan_hukum`),
  ADD KEY `idx_tanggal_status_terakhir` (`tanggal_status_terakhir`);

--
-- Indeks untuk tabel `loan_types`
--
ALTER TABLE `loan_types`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `log_audit`
--
ALTER TABLE `log_audit`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `idx_table_record` (`table_name`,`record_id`),
  ADD KEY `idx_created_at` (`created_at`);

--
-- Indeks untuk tabel `modal_pokok_perubahan`
--
ALTER TABLE `modal_pokok_perubahan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `referensi_id` (`referensi_id`),
  ADD KEY `idx_cooperative_date` (`cooperative_id`,`tanggal_efektif`),
  ADD KEY `idx_perubahan_type` (`perubahan_type`),
  ADD KEY `idx_tanggal_efektif` (`tanggal_efektif`);

--
-- Indeks untuk tabel `modul`
--
ALTER TABLE `modul`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nama` (`nama`);

--
-- Indeks untuk tabel `notifikasi`
--
ALTER TABLE `notifikasi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_notifications_user_read` (`user_id`,`read_at`);

--
-- Indeks untuk tabel `orang`
--
ALTER TABLE `orang`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pengguna_id` (`pengguna_id`);

--
-- Indeks untuk tabel `pekerjaan_master`
--
ALTER TABLE `pekerjaan_master`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `pekerjaan_pangkat`
--
ALTER TABLE `pekerjaan_pangkat`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pekerjaan_master_id` (`pekerjaan_master_id`);

--
-- Indeks untuk tabel `pengawas`
--
ALTER TABLE `pengawas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_pengawas_user` (`pengguna_id`);

--
-- Indeks untuk tabel `pengguna`
--
ALTER TABLE `pengguna`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `idx_users_user_db_id` (`sumber_pengguna_id`);

--
-- Indeks untuk tabel `pengguna_izin_peran`
--
ALTER TABLE `pengguna_izin_peran`
  ADD PRIMARY KEY (`peran_jenis_id`,`izin_modul_id`),
  ADD KEY `permission_id` (`izin_modul_id`);

--
-- Indeks untuk tabel `pengguna_peran`
--
ALTER TABLE `pengguna_peran`
  ADD PRIMARY KEY (`pengguna_id`,`peran_jenis_id`),
  ADD KEY `role_id` (`peran_jenis_id`);

--
-- Indeks untuk tabel `pengurus`
--
ALTER TABLE `pengurus`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_pengurus_user` (`pengguna_id`);

--
-- Indeks untuk tabel `penjualan_agen`
--
ALTER TABLE `penjualan_agen`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`pesanan_id`),
  ADD KEY `agent_id` (`agent_id`),
  ADD KEY `approved_by` (`pengguna_disetujui_id`);

--
-- Indeks untuk tabel `peran_izin`
--
ALTER TABLE `peran_izin`
  ADD PRIMARY KEY (`peran_jenis_id`,`permission_id`),
  ADD KEY `permission_id` (`permission_id`);

--
-- Indeks untuk tabel `peran_jenis`
--
ALTER TABLE `peran_jenis`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indeks untuk tabel `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permission_key` (`permission_key`);

--
-- Indeks untuk tabel `pesanan`
--
ALTER TABLE `pesanan`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `pesanan_detail`
--
ALTER TABLE `pesanan_detail`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`pesanan_id`),
  ADD KEY `product_id` (`produk_id`);

--
-- Indeks untuk tabel `pinjaman`
--
ALTER TABLE `pinjaman`
  ADD PRIMARY KEY (`id`),
  ADD KEY `approved_by` (`pengguna_disetujui_id`),
  ADD KEY `idx_pinjaman_anggota` (`anggota_id`),
  ADD KEY `idx_pinjaman_status` (`status`);

--
-- Indeks untuk tabel `pinjaman_angsuran`
--
ALTER TABLE `pinjaman_angsuran`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pinjaman_id` (`pinjaman_id`);

--
-- Indeks untuk tabel `produk`
--
ALTER TABLE `produk`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `rat_checklist`
--
ALTER TABLE `rat_checklist`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `rat_sesi`
--
ALTER TABLE `rat_sesi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `approved_by` (`approved_by`),
  ADD KEY `idx_cooperative_tahun` (`cooperative_id`,`tahun`),
  ADD KEY `idx_tanggal_rapat` (`tanggal_rapat`),
  ADD KEY `idx_status` (`status`);

--
-- Indeks untuk tabel `savings_types`
--
ALTER TABLE `savings_types`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `shu_anggota`
--
ALTER TABLE `shu_anggota`
  ADD PRIMARY KEY (`id`),
  ADD KEY `anggota_id` (`anggota_id`),
  ADD KEY `shu_distribution_id` (`shu_distribution_id`);

--
-- Indeks untuk tabel `shu_distribusi`
--
ALTER TABLE `shu_distribusi`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `simpanan_jenis`
--
ALTER TABLE `simpanan_jenis`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `simpanan_transaksi`
--
ALTER TABLE `simpanan_transaksi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `type_id` (`type_id`),
  ADD KEY `approved_by` (`pengguna_disetujui_id`),
  ADD KEY `idx_simpanan_anggota` (`anggota_id`),
  ADD KEY `idx_simpanan_date` (`tanggal_transaksi`);

--
-- Indeks untuk tabel `tenant_konfigurasi`
--
ALTER TABLE `tenant_konfigurasi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cooperative_id` (`cooperative_id`);

--
-- Indeks untuk tabel `voting`
--
ALTER TABLE `voting`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_votes_created_by` (`created_by`);

--
-- Indeks untuk tabel `voting_suara`
--
ALTER TABLE `voting_suara`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `idx_vote_ballots_vote_user` (`vote_id`,`user_id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `akuntansi_jenis`
--
ALTER TABLE `akuntansi_jenis`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT untuk tabel `anggota`
--
ALTER TABLE `anggota`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `buku_besar`
--
ALTER TABLE `buku_besar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `form_validation_errors`
--
ALTER TABLE `form_validation_errors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `izin_modul`
--
ALTER TABLE `izin_modul`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT untuk tabel `jurnal`
--
ALTER TABLE `jurnal`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `jurnal_detail`
--
ALTER TABLE `jurnal_detail`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `konfigurasi`
--
ALTER TABLE `konfigurasi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `koperasi_dokumen_riwayat`
--
ALTER TABLE `koperasi_dokumen_riwayat`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `koperasi_jenis`
--
ALTER TABLE `koperasi_jenis`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT untuk tabel `koperasi_keuangan_pengaturan`
--
ALTER TABLE `koperasi_keuangan_pengaturan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `koperasi_pengurus`
--
ALTER TABLE `koperasi_pengurus`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `koperasi_status_riwayat`
--
ALTER TABLE `koperasi_status_riwayat`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `koperasi_tenant`
--
ALTER TABLE `koperasi_tenant`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT untuk tabel `loan_types`
--
ALTER TABLE `loan_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `log_audit`
--
ALTER TABLE `log_audit`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `modal_pokok_perubahan`
--
ALTER TABLE `modal_pokok_perubahan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `modul`
--
ALTER TABLE `modul`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT untuk tabel `notifikasi`
--
ALTER TABLE `notifikasi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `orang`
--
ALTER TABLE `orang`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `pekerjaan_master`
--
ALTER TABLE `pekerjaan_master`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT untuk tabel `pekerjaan_pangkat`
--
ALTER TABLE `pekerjaan_pangkat`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT untuk tabel `pengawas`
--
ALTER TABLE `pengawas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `pengguna`
--
ALTER TABLE `pengguna`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT untuk tabel `pengurus`
--
ALTER TABLE `pengurus`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `penjualan_agen`
--
ALTER TABLE `penjualan_agen`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `peran_jenis`
--
ALTER TABLE `peran_jenis`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `pesanan`
--
ALTER TABLE `pesanan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `pesanan_detail`
--
ALTER TABLE `pesanan_detail`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `pinjaman`
--
ALTER TABLE `pinjaman`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `pinjaman_angsuran`
--
ALTER TABLE `pinjaman_angsuran`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `produk`
--
ALTER TABLE `produk`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `rat_checklist`
--
ALTER TABLE `rat_checklist`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `rat_sesi`
--
ALTER TABLE `rat_sesi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `savings_types`
--
ALTER TABLE `savings_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `shu_anggota`
--
ALTER TABLE `shu_anggota`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `shu_distribusi`
--
ALTER TABLE `shu_distribusi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `simpanan_jenis`
--
ALTER TABLE `simpanan_jenis`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `simpanan_transaksi`
--
ALTER TABLE `simpanan_transaksi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `tenant_konfigurasi`
--
ALTER TABLE `tenant_konfigurasi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `voting`
--
ALTER TABLE `voting`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `voting_suara`
--
ALTER TABLE `voting_suara`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `akuntansi_jenis`
--
ALTER TABLE `akuntansi_jenis`
  ADD CONSTRAINT `akuntansi_jenis_ibfk_1` FOREIGN KEY (`parent_id`) REFERENCES `akuntansi_jenis` (`id`);

--
-- Ketidakleluasaan untuk tabel `anggota`
--
ALTER TABLE `anggota`
  ADD CONSTRAINT `anggota_fk_pengguna` FOREIGN KEY (`user_id`) REFERENCES `pengguna` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `buku_besar`
--
ALTER TABLE `buku_besar`
  ADD CONSTRAINT `buku_besar_ibfk_1` FOREIGN KEY (`account_id`) REFERENCES `akuntansi_jenis` (`id`);

--
-- Ketidakleluasaan untuk tabel `jurnal`
--
ALTER TABLE `jurnal`
  ADD CONSTRAINT `jurnal_fk_posted_by` FOREIGN KEY (`posted_by`) REFERENCES `pengguna` (`id`) ON DELETE SET NULL;

--
-- Ketidakleluasaan untuk tabel `jurnal_detail`
--
ALTER TABLE `jurnal_detail`
  ADD CONSTRAINT `jurnal_detail_ibfk_1` FOREIGN KEY (`journal_entry_id`) REFERENCES `jurnal` (`id`),
  ADD CONSTRAINT `jurnal_detail_ibfk_2` FOREIGN KEY (`account_id`) REFERENCES `akuntansi_jenis` (`id`);

--
-- Ketidakleluasaan untuk tabel `koperasi_dokumen_riwayat`
--
ALTER TABLE `koperasi_dokumen_riwayat`
  ADD CONSTRAINT `koperasi_dok_fk_koperasi` FOREIGN KEY (`koperasi_id`) REFERENCES `koperasi_tenant` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `koperasi_dok_fk_pengguna` FOREIGN KEY (`pengguna_id`) REFERENCES `pengguna` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `koperasi_dokumen_riwayat_ibfk_1` FOREIGN KEY (`koperasi_id`) REFERENCES `koperasi_tenant` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `koperasi_keuangan_pengaturan`
--
ALTER TABLE `koperasi_keuangan_pengaturan`
  ADD CONSTRAINT `koperasi_keuangan_pengaturan_ibfk_1` FOREIGN KEY (`cooperative_id`) REFERENCES `koperasi_tenant` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `koperasi_keuangan_pengaturan_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `pengguna` (`id`) ON DELETE SET NULL;

--
-- Ketidakleluasaan untuk tabel `koperasi_pengurus`
--
ALTER TABLE `koperasi_pengurus`
  ADD CONSTRAINT `koperasi_pengurus_ibfk_1` FOREIGN KEY (`koperasi_tenant_id`) REFERENCES `koperasi_tenant` (`id`),
  ADD CONSTRAINT `koperasi_pengurus_ibfk_2` FOREIGN KEY (`orang_id`) REFERENCES `orang` (`id`);

--
-- Ketidakleluasaan untuk tabel `koperasi_status_riwayat`
--
ALTER TABLE `koperasi_status_riwayat`
  ADD CONSTRAINT `koperasi_status_fk_koperasi` FOREIGN KEY (`koperasi_id`) REFERENCES `koperasi_tenant` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `koperasi_status_fk_pengguna` FOREIGN KEY (`pengguna_id`) REFERENCES `pengguna` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `koperasi_status_fk_pengguna_approve` FOREIGN KEY (`pengguna_disetujui_id`) REFERENCES `pengguna` (`id`) ON DELETE SET NULL;

--
-- Ketidakleluasaan untuk tabel `log_audit`
--
ALTER TABLE `log_audit`
  ADD CONSTRAINT `log_audit_fk_pengguna` FOREIGN KEY (`user_id`) REFERENCES `pengguna` (`id`) ON DELETE SET NULL;

--
-- Ketidakleluasaan untuk tabel `modal_pokok_perubahan`
--
ALTER TABLE `modal_pokok_perubahan`
  ADD CONSTRAINT `modal_pokok_perubahan_ibfk_1` FOREIGN KEY (`cooperative_id`) REFERENCES `koperasi_tenant` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `modal_pokok_perubahan_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `pengguna` (`id`),
  ADD CONSTRAINT `modal_pokok_perubahan_ibfk_3` FOREIGN KEY (`referensi_id`) REFERENCES `rat_sesi` (`id`);

--
-- Ketidakleluasaan untuk tabel `notifikasi`
--
ALTER TABLE `notifikasi`
  ADD CONSTRAINT `notifikasi_fk_pengguna` FOREIGN KEY (`user_id`) REFERENCES `pengguna` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `orang`
--
ALTER TABLE `orang`
  ADD CONSTRAINT `orang_ibfk_1` FOREIGN KEY (`pengguna_id`) REFERENCES `pengguna` (`id`);

--
-- Ketidakleluasaan untuk tabel `pekerjaan_pangkat`
--
ALTER TABLE `pekerjaan_pangkat`
  ADD CONSTRAINT `pekerjaan_pangkat_ibfk_1` FOREIGN KEY (`pekerjaan_master_id`) REFERENCES `pekerjaan_master` (`id`);

--
-- Ketidakleluasaan untuk tabel `pengawas`
--
ALTER TABLE `pengawas`
  ADD CONSTRAINT `pengawas_fk_pengguna` FOREIGN KEY (`pengguna_id`) REFERENCES `pengguna` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `pengguna_izin_peran`
--
ALTER TABLE `pengguna_izin_peran`
  ADD CONSTRAINT `pengguna_izin_peran_fk_izin` FOREIGN KEY (`izin_modul_id`) REFERENCES `izin_modul` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `pengguna_izin_peran_fk_peran` FOREIGN KEY (`peran_jenis_id`) REFERENCES `peran_jenis` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `pengguna_peran`
--
ALTER TABLE `pengguna_peran`
  ADD CONSTRAINT `pengguna_peran_fk_pengguna` FOREIGN KEY (`pengguna_id`) REFERENCES `pengguna` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `pengguna_peran_fk_peran` FOREIGN KEY (`peran_jenis_id`) REFERENCES `peran_jenis` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `pengurus`
--
ALTER TABLE `pengurus`
  ADD CONSTRAINT `pengurus_fk_pengguna` FOREIGN KEY (`pengguna_id`) REFERENCES `pengguna` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `penjualan_agen`
--
ALTER TABLE `penjualan_agen`
  ADD CONSTRAINT `penjualan_agen_fk_pengguna_approve` FOREIGN KEY (`pengguna_disetujui_id`) REFERENCES `pengguna` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `penjualan_agen_fk_pesanan` FOREIGN KEY (`pesanan_id`) REFERENCES `pesanan` (`id`),
  ADD CONSTRAINT `penjualan_agen_ibfk_1` FOREIGN KEY (`pesanan_id`) REFERENCES `pesanan` (`id`),
  ADD CONSTRAINT `penjualan_agen_ibfk_2` FOREIGN KEY (`agent_id`) REFERENCES `anggota` (`id`);

--
-- Ketidakleluasaan untuk tabel `peran_izin`
--
ALTER TABLE `peran_izin`
  ADD CONSTRAINT `peran_izin_ibfk_1` FOREIGN KEY (`peran_jenis_id`) REFERENCES `peran_jenis` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `peran_izin_ibfk_2` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `pesanan_detail`
--
ALTER TABLE `pesanan_detail`
  ADD CONSTRAINT `pesanan_detail_ibfk_1` FOREIGN KEY (`pesanan_id`) REFERENCES `pesanan` (`id`),
  ADD CONSTRAINT `pesanan_detail_ibfk_2` FOREIGN KEY (`produk_id`) REFERENCES `produk` (`id`);

--
-- Ketidakleluasaan untuk tabel `pinjaman`
--
ALTER TABLE `pinjaman`
  ADD CONSTRAINT `pinjaman_fk_pengguna_approve` FOREIGN KEY (`pengguna_disetujui_id`) REFERENCES `pengguna` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `pinjaman_ibfk_1` FOREIGN KEY (`anggota_id`) REFERENCES `anggota` (`id`);

--
-- Ketidakleluasaan untuk tabel `pinjaman_angsuran`
--
ALTER TABLE `pinjaman_angsuran`
  ADD CONSTRAINT `pinjaman_angsuran_ibfk_1` FOREIGN KEY (`pinjaman_id`) REFERENCES `pinjaman` (`id`);

--
-- Ketidakleluasaan untuk tabel `rat_sesi`
--
ALTER TABLE `rat_sesi`
  ADD CONSTRAINT `rat_sesi_ibfk_1` FOREIGN KEY (`cooperative_id`) REFERENCES `koperasi_tenant` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `rat_sesi_ibfk_2` FOREIGN KEY (`approved_by`) REFERENCES `pengguna` (`id`);

--
-- Ketidakleluasaan untuk tabel `shu_anggota`
--
ALTER TABLE `shu_anggota`
  ADD CONSTRAINT `shu_anggota_ibfk_1` FOREIGN KEY (`anggota_id`) REFERENCES `anggota` (`id`),
  ADD CONSTRAINT `shu_anggota_ibfk_2` FOREIGN KEY (`shu_distribution_id`) REFERENCES `shu_distribusi` (`id`);

--
-- Ketidakleluasaan untuk tabel `simpanan_transaksi`
--
ALTER TABLE `simpanan_transaksi`
  ADD CONSTRAINT `simpanan_transaksi_fk_pengguna_approve` FOREIGN KEY (`pengguna_disetujui_id`) REFERENCES `pengguna` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `simpanan_transaksi_fk_simpanan_jenis` FOREIGN KEY (`type_id`) REFERENCES `simpanan_jenis` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `simpanan_transaksi_ibfk_1` FOREIGN KEY (`anggota_id`) REFERENCES `anggota` (`id`);

--
-- Ketidakleluasaan untuk tabel `tenant_konfigurasi`
--
ALTER TABLE `tenant_konfigurasi`
  ADD CONSTRAINT `tenant_konfigurasi_ibfk_1` FOREIGN KEY (`cooperative_id`) REFERENCES `koperasi_tenant` (`id`);

--
-- Ketidakleluasaan untuk tabel `voting`
--
ALTER TABLE `voting`
  ADD CONSTRAINT `fk_votes_created_by` FOREIGN KEY (`created_by`) REFERENCES `people_db`.`users` (`id`) ON DELETE SET NULL;

--
-- Ketidakleluasaan untuk tabel `voting_suara`
--
ALTER TABLE `voting_suara`
  ADD CONSTRAINT `voting_suara_ibfk_1` FOREIGN KEY (`vote_id`) REFERENCES `voting` (`id`),
  ADD CONSTRAINT `voting_suara_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `people_db`.`users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
