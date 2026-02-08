/*M!999999\- enable the sandbox mode */ 
-- MariaDB dump 10.19  Distrib 10.11.14-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: koperasi_db
-- ------------------------------------------------------
-- Server version	10.11.14-MariaDB-0ubuntu0.24.04.1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `akuntansi_jenis`
--

DROP TABLE IF EXISTS `akuntansi_jenis`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `akuntansi_jenis` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cooperative_id` int(11) NOT NULL,
  `code` varchar(20) NOT NULL,
  `name` varchar(255) NOT NULL,
  `type` enum('asset','liability','equity','revenue','expense') NOT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_cooperative_code` (`cooperative_id`,`code`),
  KEY `parent_id` (`parent_id`),
  KEY `idx_chart_cooperative` (`cooperative_id`),
  CONSTRAINT `akuntansi_jenis_ibfk_1` FOREIGN KEY (`parent_id`) REFERENCES `akuntansi_jenis` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `akuntansi_jenis`
--

LOCK TABLES `akuntansi_jenis` WRITE;
/*!40000 ALTER TABLE `akuntansi_jenis` DISABLE KEYS */;
INSERT INTO `akuntansi_jenis` VALUES
(1,0,'1000','Kas','asset',NULL,1,'2026-02-03 14:13:20'),
(2,0,'1100','Bank','asset',NULL,1,'2026-02-03 14:13:20'),
(3,0,'2000','Simpanan Anggota','liability',NULL,1,'2026-02-03 14:13:20'),
(4,0,'2100','Pinjaman Anggota','asset',NULL,1,'2026-02-03 14:13:20'),
(5,0,'3000','Modal','equity',NULL,1,'2026-02-03 14:13:20'),
(6,0,'4000','Pendapatan Bunga','revenue',NULL,1,'2026-02-03 14:13:20'),
(7,0,'5000','Beban Bunga','expense',NULL,1,'2026-02-03 14:13:20'),
(8,0,'5100','Beban Operasional','expense',NULL,1,'2026-02-03 14:13:20'),
(10,4,'1000','Kas','asset',NULL,1,'2026-02-04 17:30:29'),
(11,4,'1100','Bank','asset',NULL,1,'2026-02-04 17:30:29'),
(12,4,'2000','Simpanan Anggota','liability',NULL,1,'2026-02-04 17:30:29'),
(13,4,'2100','Pinjaman Anggota','asset',NULL,1,'2026-02-04 17:30:29'),
(14,4,'3000','Modal','equity',NULL,1,'2026-02-04 17:30:29'),
(15,4,'3100','Cadangan','equity',NULL,1,'2026-02-04 17:30:29'),
(16,4,'4000','Pendapatan Bunga','revenue',NULL,1,'2026-02-04 17:30:29'),
(17,4,'4100','Pendapatan Operasional','revenue',NULL,1,'2026-02-04 17:30:29'),
(18,4,'5000','Beban Bunga','expense',NULL,1,'2026-02-04 17:30:29'),
(19,4,'5100','Beban Operasional','expense',NULL,1,'2026-02-04 17:30:29'),
(20,4,'5200','Beban Administrasi','expense',NULL,1,'2026-02-04 17:30:29');
/*!40000 ALTER TABLE `akuntansi_jenis` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `anggota`
--

DROP TABLE IF EXISTS `anggota`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `anggota` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `status_keanggotaan` enum('active','inactive','suspended') DEFAULT 'active',
  `nomor_anggota` varchar(20) NOT NULL,
  `joined_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `nomor_anggota` (`nomor_anggota`),
  KEY `idx_anggota_user` (`user_id`),
  CONSTRAINT `anggota_fk_pengguna` FOREIGN KEY (`user_id`) REFERENCES `pengguna` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `anggota`
--

LOCK TABLES `anggota` WRITE;
/*!40000 ALTER TABLE `anggota` DISABLE KEYS */;
/*!40000 ALTER TABLE `anggota` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `buku_besar`
--

DROP TABLE IF EXISTS `buku_besar`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `buku_besar` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `account_id` int(11) NOT NULL,
  `period` date NOT NULL,
  `beginning_balance` decimal(15,2) DEFAULT 0.00,
  `debit_total` decimal(15,2) DEFAULT 0.00,
  `credit_total` decimal(15,2) DEFAULT 0.00,
  `ending_balance` decimal(15,2) DEFAULT 0.00,
  PRIMARY KEY (`id`),
  UNIQUE KEY `account_id` (`account_id`,`period`),
  CONSTRAINT `buku_besar_ibfk_1` FOREIGN KEY (`account_id`) REFERENCES `akuntansi_jenis` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `buku_besar`
--

LOCK TABLES `buku_besar` WRITE;
/*!40000 ALTER TABLE `buku_besar` DISABLE KEYS */;
/*!40000 ALTER TABLE `buku_besar` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `form_validation_errors`
--

DROP TABLE IF EXISTS `form_validation_errors`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `form_validation_errors` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `input_value` text DEFAULT NULL,
  `field_type` varchar(50) DEFAULT NULL,
  `error_type` varchar(50) DEFAULT NULL,
  `user_ip` varchar(45) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `form_validation_errors`
--

LOCK TABLES `form_validation_errors` WRITE;
/*!40000 ALTER TABLE `form_validation_errors` DISABLE KEYS */;
/*!40000 ALTER TABLE `form_validation_errors` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `izin_modul`
--

DROP TABLE IF EXISTS `izin_modul`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `izin_modul` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `is_active` tinyint(1) DEFAULT 1,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `izin_modul`
--

LOCK TABLES `izin_modul` WRITE;
/*!40000 ALTER TABLE `izin_modul` DISABLE KEYS */;
INSERT INTO `izin_modul` VALUES
(1,'view_users','View user list','2026-02-03 14:13:20',1),
(2,'create_users','Create new users','2026-02-03 14:13:20',1),
(3,'edit_users','Edit user information','2026-02-03 14:13:20',1),
(4,'delete_users','Delete users','2026-02-03 14:13:20',1),
(5,'view_members','View members','2026-02-03 14:13:20',1),
(6,'manage_members','Manage member data','2026-02-03 14:13:20',1),
(7,'view_savings','View savings transactions','2026-02-03 14:13:20',1),
(8,'manage_savings','Manage savings','2026-02-03 14:13:20',1),
(9,'view_loans','View loan applications','2026-02-03 14:13:20',1),
(10,'manage_loans','Manage loans','2026-02-03 14:13:20',1),
(11,'view_accounts','View chart of accounts','2026-02-03 14:13:20',1),
(12,'manage_accounts','Manage accounting','2026-02-03 14:13:20',1),
(13,'view_reports','View reports','2026-02-03 14:13:20',1),
(14,'generate_reports','Generate financial reports','2026-02-03 14:13:20',1),
(15,'vote','Participate in voting','2026-02-03 14:13:20',1),
(16,'manage_votes','Manage voting sessions','2026-02-03 14:13:20',1),
(17,'view_audit','View audit logs','2026-02-03 14:13:20',1),
(18,'admin_access','Full administrative access','2026-02-03 14:13:20',1);
/*!40000 ALTER TABLE `izin_modul` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jurnal`
--

DROP TABLE IF EXISTS `jurnal`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `jurnal` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `entry_date` date NOT NULL,
  `description` text NOT NULL,
  `reference_number` varchar(50) DEFAULT NULL,
  `status` enum('draft','posted') DEFAULT 'draft',
  `posted_by` int(11) DEFAULT NULL,
  `posted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `fk_journal_posted_by` (`posted_by`),
  CONSTRAINT `jurnal_fk_posted_by` FOREIGN KEY (`posted_by`) REFERENCES `pengguna` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jurnal`
--

LOCK TABLES `jurnal` WRITE;
/*!40000 ALTER TABLE `jurnal` DISABLE KEYS */;
/*!40000 ALTER TABLE `jurnal` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jurnal_detail`
--

DROP TABLE IF EXISTS `jurnal_detail`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `jurnal_detail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `journal_entry_id` int(11) NOT NULL,
  `account_id` int(11) NOT NULL,
  `debit` decimal(15,2) DEFAULT 0.00,
  `credit` decimal(15,2) DEFAULT 0.00,
  `description` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `journal_entry_id` (`journal_entry_id`),
  KEY `account_id` (`account_id`),
  CONSTRAINT `jurnal_detail_ibfk_1` FOREIGN KEY (`journal_entry_id`) REFERENCES `jurnal` (`id`),
  CONSTRAINT `jurnal_detail_ibfk_2` FOREIGN KEY (`account_id`) REFERENCES `akuntansi_jenis` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jurnal_detail`
--

LOCK TABLES `jurnal_detail` WRITE;
/*!40000 ALTER TABLE `jurnal_detail` DISABLE KEYS */;
/*!40000 ALTER TABLE `jurnal_detail` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `konfigurasi`
--

DROP TABLE IF EXISTS `konfigurasi`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `konfigurasi` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `key_name` varchar(100) NOT NULL,
  `value` text DEFAULT NULL,
  `description` text DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_name` (`key_name`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `konfigurasi`
--

LOCK TABLES `konfigurasi` WRITE;
/*!40000 ALTER TABLE `konfigurasi` DISABLE KEYS */;
INSERT INTO `konfigurasi` VALUES
(1,'coop_name','Koperasi Simpan Pinjam','Nama koperasi','2026-02-03 14:13:20'),
(2,'interest_rate_savings','3.5','Suku bunga simpanan tahunan (%)','2026-02-03 14:13:20'),
(3,'interest_rate_loans','12.0','Suku bunga pinjaman tahunan (%)','2026-02-03 14:13:20'),
(4,'penalty_rate','2.0','Denda keterlambatan (%) per hari','2026-02-03 14:13:20'),
(5,'shu_distribution_ratio','70','Persentase SHU untuk anggota (%)','2026-02-03 14:13:20');
/*!40000 ALTER TABLE `konfigurasi` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `koperasi_dokumen_riwayat`
--

DROP TABLE IF EXISTS `koperasi_dokumen_riwayat`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `koperasi_dokumen_riwayat` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `koperasi_id` int(11) NOT NULL,
  `document_type` enum('nomor_bh','nib','nik_koperasi','modal_pokok') NOT NULL,
  `document_number_lama` varchar(50) DEFAULT NULL,
  `document_number_baru` varchar(50) DEFAULT NULL,
  `document_value_lama` decimal(15,2) DEFAULT NULL,
  `document_value_baru` decimal(15,2) DEFAULT NULL,
  `tanggal_efektif` date NOT NULL,
  `change_reason` varchar(255) DEFAULT NULL,
  `pengguna_id` int(11) DEFAULT NULL,
  `dibuat_pada` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_cooperative_document` (`koperasi_id`,`document_type`),
  KEY `idx_tanggal_efektif` (`tanggal_efektif`),
  KEY `idx_document_type` (`document_type`),
  KEY `koperasi_dok_fk_pengguna` (`pengguna_id`),
  CONSTRAINT `koperasi_dok_fk_koperasi` FOREIGN KEY (`koperasi_id`) REFERENCES `koperasi_tenant` (`id`) ON DELETE CASCADE,
  CONSTRAINT `koperasi_dok_fk_pengguna` FOREIGN KEY (`pengguna_id`) REFERENCES `pengguna` (`id`) ON DELETE SET NULL,
  CONSTRAINT `koperasi_dokumen_riwayat_ibfk_1` FOREIGN KEY (`koperasi_id`) REFERENCES `koperasi_tenant` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `koperasi_dokumen_riwayat`
--

LOCK TABLES `koperasi_dokumen_riwayat` WRITE;
/*!40000 ALTER TABLE `koperasi_dokumen_riwayat` DISABLE KEYS */;
/*!40000 ALTER TABLE `koperasi_dokumen_riwayat` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `koperasi_jenis`
--

DROP TABLE IF EXISTS `koperasi_jenis`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `koperasi_jenis` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `code` varchar(20) DEFAULT NULL,
  `category` enum('finansial','produksi','jasa','konsumsi','serba_usaha','karyawan') DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `koperasi_jenis`
--

LOCK TABLES `koperasi_jenis` WRITE;
/*!40000 ALTER TABLE `koperasi_jenis` DISABLE KEYS */;
INSERT INTO `koperasi_jenis` VALUES
(1,'Koperasi Simpan Pinjam (KSP)','Koperasi yang bergerak di bidang simpan pinjam untuk anggota, menyediakan layanan tabungan, kredit, dan jasa keuangan lainnya sesuai PP No. 7 Tahun 2021','KSP','finansial',1,'2026-02-04 07:36:54'),
(2,'Koperasi Konsumsi','Koperasi yang bergerak di bidang pemenuhan kebutuhan konsumsi anggota, menyediakan barang dan jasa kebutuhan sehari-hari sesuai PP No. 7 Tahun 2021','KK','konsumsi',1,'2026-02-04 07:36:54'),
(3,'Koperasi Produksi','Koperasi yang bergerak di bidang produksi barang/jasa anggota, mengelola pengolahan, pemasaran, dan distribusi hasil produksi sesuai PP No. 7 Tahun 2021','KP','produksi',1,'2026-02-04 07:36:54'),
(4,'Koperasi Pemasaran','Koperasi yang bergerak di bidang pemasaran hasil produksi anggota, menyediakan layanan distribusi, penjualan, dan ekspor sesuai PP No. 7 Tahun 2021','KPAS','produksi',1,'2026-02-04 07:36:54'),
(5,'Koperasi Jasa','Koperasi yang bergerak di bidang penyediaan jasa untuk anggota, seperti transportasi, komunikasi, konsultasi, dan jasa lainnya sesuai PP No. 7 Tahun 2021','KJ','jasa',1,'2026-02-04 07:36:54'),
(6,'Koperasi Serba Usaha (KSU)','Koperasi yang menjalankan berbagai jenis usaha kombinasi dari beberapa jenis koperasi dalam satu organisasi sesuai PP No. 7 Tahun 2021','KSU','serba_usaha',1,'2026-02-04 07:36:54'),
(7,'Koperasi Karyawan','Koperasi yang bergerak di bidang kesejahteraan karyawan perusahaan, menyediakan simpan pinjam, konsumsi, dan jasa untuk karyawan sesuai PP No. 7 Tahun 2021','KKAR','karyawan',1,'2026-02-04 09:19:02'),
(8,'Koperasi Pertanian','Koperasi yang bergerak di bidang pertanian, menyediakan sarana produksi, pengolahan hasil, dan pemasaran produk pertanian sesuai PP No. 7 Tahun 2021','KOPERTA','produksi',1,'2026-02-04 09:20:25'),
(9,'Koperasi Nelayan','Koperasi yang bergerak di bidang perikanan, menyediakan alat tangkap, pengolahan hasil, dan pemasaran hasil perikanan sesuai PP No. 7 Tahun 2021','KOPERNAL','produksi',1,'2026-02-04 09:20:25'),
(10,'Koperasi Peternakan','Koperasi yang bergerak di bidang peternakan, menyediakan pakan, pengolahan, dan pemasaran hasil peternakan sesuai PP No. 7 Tahun 2021','KOPERTAK','produksi',1,'2026-02-04 09:20:25'),
(11,'Koperasi Perdagangan','Koperasi yang bergerak di bidang perdagangan grosir dan eceran, menyediakan barang dagangan untuk anggota sesuai PP No. 7 Tahun 2021','KOPERDAG','konsumsi',1,'2026-02-04 09:20:25'),
(12,'Koperasi Pondok Pesantren','Koperasi yang bergerak di lingkungan pondok pesantren, menyediakan kebutuhan santri dan wali santri sesuai PP No. 7 Tahun 2021','KOPONTREN','serba_usaha',1,'2026-02-04 09:20:25');
/*!40000 ALTER TABLE `koperasi_jenis` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `koperasi_keuangan_pengaturan`
--

DROP TABLE IF EXISTS `koperasi_keuangan_pengaturan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `koperasi_keuangan_pengaturan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
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
  `created_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_cooperative_year` (`cooperative_id`,`tahun_buku`),
  KEY `created_by` (`created_by`),
  KEY `idx_cooperative_year` (`cooperative_id`,`tahun_buku`),
  KEY `idx_tahun_buku` (`tahun_buku`),
  CONSTRAINT `koperasi_keuangan_pengaturan_ibfk_1` FOREIGN KEY (`cooperative_id`) REFERENCES `koperasi_tenant` (`id`) ON DELETE CASCADE,
  CONSTRAINT `koperasi_keuangan_pengaturan_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `pengguna` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `koperasi_keuangan_pengaturan`
--

LOCK TABLES `koperasi_keuangan_pengaturan` WRITE;
/*!40000 ALTER TABLE `koperasi_keuangan_pengaturan` DISABLE KEYS */;
INSERT INTO `koperasi_keuangan_pengaturan` VALUES
(4,4,2026,'2026-01-01','2026-12-31',100000.00,50000.00,12.00,2.00,'yearly','active','2026-02-04 17:30:29','2026-02-04 17:30:29',5);
/*!40000 ALTER TABLE `koperasi_keuangan_pengaturan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `koperasi_status_riwayat`
--

DROP TABLE IF EXISTS `koperasi_status_riwayat`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `koperasi_status_riwayat` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
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
  `approved_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_cooperative_id` (`koperasi_id`),
  KEY `idx_tanggal_efektif` (`tanggal_efektif`),
  KEY `idx_approval_status` (`approval_status`),
  KEY `koperasi_status_fk_pengguna` (`pengguna_id`),
  KEY `koperasi_status_fk_pengguna_approve` (`pengguna_disetujui_id`),
  CONSTRAINT `koperasi_status_fk_koperasi` FOREIGN KEY (`koperasi_id`) REFERENCES `koperasi_tenant` (`id`) ON DELETE CASCADE,
  CONSTRAINT `koperasi_status_fk_pengguna` FOREIGN KEY (`pengguna_id`) REFERENCES `pengguna` (`id`) ON DELETE SET NULL,
  CONSTRAINT `koperasi_status_fk_pengguna_approve` FOREIGN KEY (`pengguna_disetujui_id`) REFERENCES `pengguna` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `koperasi_status_riwayat`
--

LOCK TABLES `koperasi_status_riwayat` WRITE;
/*!40000 ALTER TABLE `koperasi_status_riwayat` DISABLE KEYS */;
/*!40000 ALTER TABLE `koperasi_status_riwayat` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `koperasi_tenant`
--

DROP TABLE IF EXISTS `koperasi_tenant`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `koperasi_tenant` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
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
  PRIMARY KEY (`id`),
  KEY `idx_cooperative_province` (`provinsi_id`),
  KEY `idx_cooperative_regency` (`kabkota_id`),
  KEY `idx_cooperative_district` (`kecamatan_id`),
  KEY `idx_cooperative_village` (`kelurahan_id`),
  KEY `idx_nomor_bh` (`nomor_badan_hukum`),
  KEY `idx_nib` (`nib`),
  KEY `idx_nik_koperasi` (`nik_koperasi`),
  KEY `idx_status_badan_hukum` (`status_badan_hukum`),
  KEY `idx_tanggal_status_terakhir` (`tanggal_status_terakhir`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `koperasi_tenant`
--

LOCK TABLES `koperasi_tenant` WRITE;
/*!40000 ALTER TABLE `koperasi_tenant` DISABLE KEYS */;
INSERT INTO `koperasi_tenant` VALUES
(8,'KSP POLRES SAMOSIR','[\"1\"]',NULL,'belum_terdaftar',NULL,NULL,NULL,NULL,NULL,NULL,NULL,0.00,'jl. Danau Toba no 03, PASAR PANGURURAN, PANGURURAN, KABUPATEN SAMOSIR, SUMATERA UTARA, 22392',NULL,NULL,NULL,'2026-02-08 11:52:06','2026-02-08 11:52:06',3,40,590,10617);
/*!40000 ALTER TABLE `koperasi_tenant` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `log_audit`
--

DROP TABLE IF EXISTS `log_audit`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `log_audit` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
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
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `idx_table_record` (`table_name`,`record_id`),
  KEY `idx_created_at` (`created_at`),
  CONSTRAINT `log_audit_fk_pengguna` FOREIGN KEY (`user_id`) REFERENCES `pengguna` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `log_audit`
--

LOCK TABLES `log_audit` WRITE;
/*!40000 ALTER TABLE `log_audit` DISABLE KEYS */;
/*!40000 ALTER TABLE `log_audit` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `modal_pokok_perubahan`
--

DROP TABLE IF EXISTS `modal_pokok_perubahan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `modal_pokok_perubahan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cooperative_id` int(11) NOT NULL,
  `modal_pokok_lama` decimal(15,2) NOT NULL,
  `modal_pokok_baru` decimal(15,2) NOT NULL,
  `persentase_perubahan` decimal(5,2) NOT NULL,
  `tanggal_efektif` date NOT NULL,
  `perubahan_type` enum('manual','rat','other') NOT NULL,
  `referensi_id` int(11) DEFAULT NULL,
  `alasan_perubahan` text DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `referensi_id` (`referensi_id`),
  KEY `idx_cooperative_date` (`cooperative_id`,`tanggal_efektif`),
  KEY `idx_perubahan_type` (`perubahan_type`),
  KEY `idx_tanggal_efektif` (`tanggal_efektif`),
  CONSTRAINT `modal_pokok_perubahan_ibfk_1` FOREIGN KEY (`cooperative_id`) REFERENCES `koperasi_tenant` (`id`) ON DELETE CASCADE,
  CONSTRAINT `modal_pokok_perubahan_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `pengguna` (`id`),
  CONSTRAINT `modal_pokok_perubahan_ibfk_3` FOREIGN KEY (`referensi_id`) REFERENCES `rat_sesi` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `modal_pokok_perubahan`
--

LOCK TABLES `modal_pokok_perubahan` WRITE;
/*!40000 ALTER TABLE `modal_pokok_perubahan` DISABLE KEYS */;
/*!40000 ALTER TABLE `modal_pokok_perubahan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `notifikasi`
--

DROP TABLE IF EXISTS `notifikasi`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `notifikasi` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `type` enum('info','warning','error') DEFAULT 'info',
  `sent_at` timestamp NULL DEFAULT current_timestamp(),
  `read_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_notifications_user_read` (`user_id`,`read_at`),
  CONSTRAINT `notifikasi_fk_pengguna` FOREIGN KEY (`user_id`) REFERENCES `pengguna` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notifikasi`
--

LOCK TABLES `notifikasi` WRITE;
/*!40000 ALTER TABLE `notifikasi` DISABLE KEYS */;
/*!40000 ALTER TABLE `notifikasi` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `orang`
--

DROP TABLE IF EXISTS `orang`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `orang` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
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
  `diperbarui_pada` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `pengguna_id` (`pengguna_id`),
  CONSTRAINT `orang_ibfk_1` FOREIGN KEY (`pengguna_id`) REFERENCES `pengguna` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orang`
--

LOCK TABLES `orang` WRITE;
/*!40000 ALTER TABLE `orang` DISABLE KEYS */;
INSERT INTO `orang` VALUES
(5,10,'admin paling baik di dunia','admin','paling baik di','dunia','6281265511982',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'jl. Danau Toba no 03, PASAR PANGURURAN, PANGURURAN, KABUPATEN SAMOSIR, SUMATERA UTARA, 22392',3,40,590,10617,'jl. Danau Toba no','03',NULL,NULL,'22392','Administrator Koperasi','KSP POLRES SAMOSIR','Administrator','Dibuat otomatis saat registrasi koperasi',10,'2026-02-08 04:52:06','2026-02-08 11:52:06');
/*!40000 ALTER TABLE `orang` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pengawas`
--

DROP TABLE IF EXISTS `pengawas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `pengawas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pengguna_id` int(11) NOT NULL,
  `jabatan` varchar(100) NOT NULL,
  `periode_start` date NOT NULL,
  `periode_end` date DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_pengawas_user` (`pengguna_id`),
  CONSTRAINT `pengawas_fk_pengguna` FOREIGN KEY (`pengguna_id`) REFERENCES `pengguna` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pengawas`
--

LOCK TABLES `pengawas` WRITE;
/*!40000 ALTER TABLE `pengawas` DISABLE KEYS */;
/*!40000 ALTER TABLE `pengawas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pengguna`
--

DROP TABLE IF EXISTS `pengguna`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `pengguna` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL,
  `sandi_hash` varchar(255) NOT NULL,
  `sumber_pengguna_id` int(11) NOT NULL,
  `status` enum('active','inactive','pending') DEFAULT 'active',
  `dibuat_pada` timestamp NULL DEFAULT current_timestamp(),
  `diperbarui_pada` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `hp` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  KEY `idx_users_user_db_id` (`sumber_pengguna_id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pengguna`
--

LOCK TABLES `pengguna` WRITE;
/*!40000 ALTER TABLE `pengguna` DISABLE KEYS */;
INSERT INTO `pengguna` VALUES
(10,'root','$2y$10$mr69A.e7sEpZN2CAvGPgYu2wUWSJZXgbgSx.f9pB/Bk4/PxrsjkRS',1,'active','2026-02-08 11:52:06','2026-02-08 11:52:06','6281265511982');
/*!40000 ALTER TABLE `pengguna` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pengguna_izin_peran`
--

DROP TABLE IF EXISTS `pengguna_izin_peran`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `pengguna_izin_peran` (
  `peran_jenis_id` int(11) NOT NULL,
  `izin_modul_id` int(11) NOT NULL,
  `assigned_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`peran_jenis_id`,`izin_modul_id`),
  KEY `permission_id` (`izin_modul_id`),
  CONSTRAINT `pengguna_izin_peran_fk_izin` FOREIGN KEY (`izin_modul_id`) REFERENCES `izin_modul` (`id`) ON DELETE CASCADE,
  CONSTRAINT `pengguna_izin_peran_fk_peran` FOREIGN KEY (`peran_jenis_id`) REFERENCES `peran_jenis` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pengguna_izin_peran`
--

LOCK TABLES `pengguna_izin_peran` WRITE;
/*!40000 ALTER TABLE `pengguna_izin_peran` DISABLE KEYS */;
INSERT INTO `pengguna_izin_peran` VALUES
(2,1,'2026-02-08 11:52:06'),
(2,2,'2026-02-08 11:52:06'),
(2,3,'2026-02-08 11:52:06'),
(2,4,'2026-02-08 11:52:06'),
(2,5,'2026-02-08 11:52:06'),
(2,6,'2026-02-08 11:52:06'),
(2,7,'2026-02-08 11:52:06'),
(2,8,'2026-02-08 11:52:06'),
(2,9,'2026-02-08 11:52:06'),
(2,10,'2026-02-08 11:52:06'),
(2,11,'2026-02-08 11:52:06'),
(2,12,'2026-02-08 11:52:06'),
(2,13,'2026-02-08 11:52:06'),
(2,14,'2026-02-08 11:52:06'),
(2,15,'2026-02-08 11:52:06'),
(2,16,'2026-02-08 11:52:06'),
(2,17,'2026-02-08 11:52:06'),
(2,18,'2026-02-08 11:52:06');
/*!40000 ALTER TABLE `pengguna_izin_peran` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pengguna_peran`
--

DROP TABLE IF EXISTS `pengguna_peran`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `pengguna_peran` (
  `pengguna_id` int(11) NOT NULL,
  `peran_jenis_id` int(11) NOT NULL,
  `assigned_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`pengguna_id`,`peran_jenis_id`),
  KEY `role_id` (`peran_jenis_id`),
  CONSTRAINT `pengguna_peran_fk_pengguna` FOREIGN KEY (`pengguna_id`) REFERENCES `pengguna` (`id`) ON DELETE CASCADE,
  CONSTRAINT `pengguna_peran_fk_peran` FOREIGN KEY (`peran_jenis_id`) REFERENCES `peran_jenis` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pengguna_peran`
--

LOCK TABLES `pengguna_peran` WRITE;
/*!40000 ALTER TABLE `pengguna_peran` DISABLE KEYS */;
INSERT INTO `pengguna_peran` VALUES
(10,2,'2026-02-08 11:52:06');
/*!40000 ALTER TABLE `pengguna_peran` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pengurus`
--

DROP TABLE IF EXISTS `pengurus`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `pengurus` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pengguna_id` int(11) NOT NULL,
  `jabatan` varchar(100) NOT NULL,
  `periode_start` date NOT NULL,
  `periode_end` date DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_pengurus_user` (`pengguna_id`),
  CONSTRAINT `pengurus_fk_pengguna` FOREIGN KEY (`pengguna_id`) REFERENCES `pengguna` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pengurus`
--

LOCK TABLES `pengurus` WRITE;
/*!40000 ALTER TABLE `pengurus` DISABLE KEYS */;
/*!40000 ALTER TABLE `pengurus` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `penjualan_agen`
--

DROP TABLE IF EXISTS `penjualan_agen`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `penjualan_agen` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `agent_id` int(11) NOT NULL,
  `pesanan_id` int(11) NOT NULL,
  `commission` decimal(15,2) NOT NULL,
  `approved` tinyint(1) DEFAULT 0,
  `pengguna_disetujui_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `order_id` (`pesanan_id`),
  KEY `agent_id` (`agent_id`),
  KEY `approved_by` (`pengguna_disetujui_id`),
  CONSTRAINT `penjualan_agen_fk_pengguna_approve` FOREIGN KEY (`pengguna_disetujui_id`) REFERENCES `pengguna` (`id`) ON DELETE SET NULL,
  CONSTRAINT `penjualan_agen_fk_pesanan` FOREIGN KEY (`pesanan_id`) REFERENCES `pesanan` (`id`),
  CONSTRAINT `penjualan_agen_ibfk_1` FOREIGN KEY (`pesanan_id`) REFERENCES `pesanan` (`id`),
  CONSTRAINT `penjualan_agen_ibfk_2` FOREIGN KEY (`agent_id`) REFERENCES `anggota` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `penjualan_agen`
--

LOCK TABLES `penjualan_agen` WRITE;
/*!40000 ALTER TABLE `penjualan_agen` DISABLE KEYS */;
/*!40000 ALTER TABLE `penjualan_agen` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `peran_jenis`
--

DROP TABLE IF EXISTS `peran_jenis`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `peran_jenis` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `peran_jenis`
--

LOCK TABLES `peran_jenis` WRITE;
/*!40000 ALTER TABLE `peran_jenis` DISABLE KEYS */;
INSERT INTO `peran_jenis` VALUES
(1,'super_admin','Super administrator with all access','2026-02-03 14:13:20'),
(2,'admin','Administrator/Pengurus','2026-02-03 14:13:20'),
(3,'pengawas','Pengawas with read/approve access','2026-02-03 14:13:20'),
(4,'anggota','Regular member','2026-02-03 14:13:20'),
(5,'calon_anggota','Prospective member','2026-02-03 14:13:20');
/*!40000 ALTER TABLE `peran_jenis` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pesanan`
--

DROP TABLE IF EXISTS `pesanan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `pesanan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pengguna_id` int(11) DEFAULT NULL,
  `dibuat_pada` timestamp NULL DEFAULT current_timestamp(),
  `total` decimal(15,2) NOT NULL,
  `status` enum('pending','confirmed','shipped','delivered','cancelled') DEFAULT 'pending',
  `alamat_pengiriman` text DEFAULT NULL,
  `status_pembayaran` enum('unpaid','paid','refunded') DEFAULT 'unpaid',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pesanan`
--

LOCK TABLES `pesanan` WRITE;
/*!40000 ALTER TABLE `pesanan` DISABLE KEYS */;
/*!40000 ALTER TABLE `pesanan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pesanan_detail`
--

DROP TABLE IF EXISTS `pesanan_detail`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `pesanan_detail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pesanan_id` int(11) NOT NULL,
  `produk_id` int(11) NOT NULL,
  `kuantitas` int(11) NOT NULL,
  `harga_satuan` decimal(15,2) NOT NULL,
  `total` decimal(15,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `order_id` (`pesanan_id`),
  KEY `product_id` (`produk_id`),
  CONSTRAINT `pesanan_detail_ibfk_1` FOREIGN KEY (`pesanan_id`) REFERENCES `pesanan` (`id`),
  CONSTRAINT `pesanan_detail_ibfk_2` FOREIGN KEY (`produk_id`) REFERENCES `produk` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pesanan_detail`
--

LOCK TABLES `pesanan_detail` WRITE;
/*!40000 ALTER TABLE `pesanan_detail` DISABLE KEYS */;
/*!40000 ALTER TABLE `pesanan_detail` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pinjaman`
--

DROP TABLE IF EXISTS `pinjaman`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `pinjaman` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `anggota_id` int(11) NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `interest_rate` decimal(5,2) NOT NULL,
  `term_months` int(11) NOT NULL,
  `status` enum('pending','approved','active','paid','rejected') DEFAULT 'pending',
  `approved_at` timestamp NULL DEFAULT NULL,
  `pengguna_disetujui_id` int(11) DEFAULT NULL,
  `disbursed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `approved_by` (`pengguna_disetujui_id`),
  KEY `idx_pinjaman_anggota` (`anggota_id`),
  KEY `idx_pinjaman_status` (`status`),
  CONSTRAINT `pinjaman_fk_pengguna_approve` FOREIGN KEY (`pengguna_disetujui_id`) REFERENCES `pengguna` (`id`) ON DELETE SET NULL,
  CONSTRAINT `pinjaman_ibfk_1` FOREIGN KEY (`anggota_id`) REFERENCES `anggota` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pinjaman`
--

LOCK TABLES `pinjaman` WRITE;
/*!40000 ALTER TABLE `pinjaman` DISABLE KEYS */;
/*!40000 ALTER TABLE `pinjaman` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pinjaman_angsuran`
--

DROP TABLE IF EXISTS `pinjaman_angsuran`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `pinjaman_angsuran` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pinjaman_id` int(11) NOT NULL,
  `angsuran_ke` int(11) NOT NULL,
  `tanggal_jatuh_tempo` date NOT NULL,
  `principal_amount` decimal(15,2) NOT NULL,
  `interest_amount` decimal(15,2) NOT NULL,
  `total_amount` decimal(15,2) NOT NULL,
  `paid_amount` decimal(15,2) DEFAULT 0.00,
  `paid_at` timestamp NULL DEFAULT NULL,
  `status` enum('pending','paid','overdue') DEFAULT 'pending',
  `penalty` decimal(15,2) DEFAULT 0.00,
  PRIMARY KEY (`id`),
  KEY `pinjaman_id` (`pinjaman_id`),
  CONSTRAINT `pinjaman_angsuran_ibfk_1` FOREIGN KEY (`pinjaman_id`) REFERENCES `pinjaman` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pinjaman_angsuran`
--

LOCK TABLES `pinjaman_angsuran` WRITE;
/*!40000 ALTER TABLE `pinjaman_angsuran` DISABLE KEYS */;
/*!40000 ALTER TABLE `pinjaman_angsuran` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `produk`
--

DROP TABLE IF EXISTS `produk`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `produk` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(15,2) NOT NULL,
  `stock_quantity` int(11) DEFAULT 0,
  `category` varchar(100) DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `produk`
--

LOCK TABLES `produk` WRITE;
/*!40000 ALTER TABLE `produk` DISABLE KEYS */;
/*!40000 ALTER TABLE `produk` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rat_sesi`
--

DROP TABLE IF EXISTS `rat_sesi`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `rat_sesi` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
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
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `approved_by` (`approved_by`),
  KEY `idx_cooperative_tahun` (`cooperative_id`,`tahun`),
  KEY `idx_tanggal_rapat` (`tanggal_rapat`),
  KEY `idx_status` (`status`),
  CONSTRAINT `rat_sesi_ibfk_1` FOREIGN KEY (`cooperative_id`) REFERENCES `koperasi_tenant` (`id`) ON DELETE CASCADE,
  CONSTRAINT `rat_sesi_ibfk_2` FOREIGN KEY (`approved_by`) REFERENCES `pengguna` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rat_sesi`
--

LOCK TABLES `rat_sesi` WRITE;
/*!40000 ALTER TABLE `rat_sesi` DISABLE KEYS */;
/*!40000 ALTER TABLE `rat_sesi` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shu_anggota`
--

DROP TABLE IF EXISTS `shu_anggota`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `shu_anggota` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `anggota_id` int(11) NOT NULL,
  `shu_distribution_id` int(11) NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `paid` tinyint(1) DEFAULT 0,
  `paid_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `anggota_id` (`anggota_id`),
  KEY `shu_distribution_id` (`shu_distribution_id`),
  CONSTRAINT `shu_anggota_ibfk_1` FOREIGN KEY (`anggota_id`) REFERENCES `anggota` (`id`),
  CONSTRAINT `shu_anggota_ibfk_2` FOREIGN KEY (`shu_distribution_id`) REFERENCES `shu_distribusi` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shu_anggota`
--

LOCK TABLES `shu_anggota` WRITE;
/*!40000 ALTER TABLE `shu_anggota` DISABLE KEYS */;
/*!40000 ALTER TABLE `shu_anggota` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shu_distribusi`
--

DROP TABLE IF EXISTS `shu_distribusi`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `shu_distribusi` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `year` year(4) NOT NULL,
  `total_shu` decimal(15,2) NOT NULL,
  `distributed_at` timestamp NULL DEFAULT NULL,
  `status` enum('calculated','distributed') DEFAULT 'calculated',
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shu_distribusi`
--

LOCK TABLES `shu_distribusi` WRITE;
/*!40000 ALTER TABLE `shu_distribusi` DISABLE KEYS */;
/*!40000 ALTER TABLE `shu_distribusi` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `simpanan_jenis`
--

DROP TABLE IF EXISTS `simpanan_jenis`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `simpanan_jenis` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `interest_rate` decimal(5,2) DEFAULT 0.00,
  `minimum_balance` decimal(15,2) DEFAULT 0.00,
  `description` text DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `simpanan_jenis`
--

LOCK TABLES `simpanan_jenis` WRITE;
/*!40000 ALTER TABLE `simpanan_jenis` DISABLE KEYS */;
/*!40000 ALTER TABLE `simpanan_jenis` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `simpanan_transaksi`
--

DROP TABLE IF EXISTS `simpanan_transaksi`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `simpanan_transaksi` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `anggota_id` int(11) NOT NULL,
  `type_id` int(11) NOT NULL,
  `nilai` decimal(15,2) NOT NULL,
  `transaction_type` enum('deposit','withdraw') NOT NULL,
  `balance_after` decimal(15,2) NOT NULL,
  `tanggal_transaksi` date DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `pengguna_disetujui_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `type_id` (`type_id`),
  KEY `approved_by` (`pengguna_disetujui_id`),
  KEY `idx_simpanan_anggota` (`anggota_id`),
  KEY `idx_simpanan_date` (`tanggal_transaksi`),
  CONSTRAINT `simpanan_transaksi_fk_pengguna_approve` FOREIGN KEY (`pengguna_disetujui_id`) REFERENCES `pengguna` (`id`) ON DELETE SET NULL,
  CONSTRAINT `simpanan_transaksi_fk_simpanan_jenis` FOREIGN KEY (`type_id`) REFERENCES `simpanan_jenis` (`id`) ON DELETE CASCADE,
  CONSTRAINT `simpanan_transaksi_ibfk_1` FOREIGN KEY (`anggota_id`) REFERENCES `anggota` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `simpanan_transaksi`
--

LOCK TABLES `simpanan_transaksi` WRITE;
/*!40000 ALTER TABLE `simpanan_transaksi` DISABLE KEYS */;
/*!40000 ALTER TABLE `simpanan_transaksi` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tenant_konfigurasi`
--

DROP TABLE IF EXISTS `tenant_konfigurasi`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `tenant_konfigurasi` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cooperative_id` int(11) NOT NULL,
  `active_modules` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`active_modules`)),
  `feature_flags` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`feature_flags`)),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `cooperative_id` (`cooperative_id`),
  CONSTRAINT `tenant_konfigurasi_ibfk_1` FOREIGN KEY (`cooperative_id`) REFERENCES `koperasi_tenant` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tenant_konfigurasi`
--

LOCK TABLES `tenant_konfigurasi` WRITE;
/*!40000 ALTER TABLE `tenant_konfigurasi` DISABLE KEYS */;
INSERT INTO `tenant_konfigurasi` VALUES
(3,4,'[]','{\"multi_tenant\":true,\"modular\":true}','2026-02-04 17:30:29','2026-02-04 17:30:29');
/*!40000 ALTER TABLE `tenant_konfigurasi` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `voting`
--

DROP TABLE IF EXISTS `voting`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `voting` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `agenda` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL,
  `status` enum('draft','active','closed') DEFAULT 'draft',
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `fk_votes_created_by` (`created_by`),
  CONSTRAINT `fk_votes_created_by` FOREIGN KEY (`created_by`) REFERENCES `people_db`.`users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `voting`
--

LOCK TABLES `voting` WRITE;
/*!40000 ALTER TABLE `voting` DISABLE KEYS */;
/*!40000 ALTER TABLE `voting` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `voting_suara`
--

DROP TABLE IF EXISTS `voting_suara`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `voting_suara` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `vote_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `choice` varchar(100) NOT NULL,
  `voted_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `idx_vote_ballots_vote_user` (`vote_id`,`user_id`),
  CONSTRAINT `voting_suara_ibfk_1` FOREIGN KEY (`vote_id`) REFERENCES `voting` (`id`),
  CONSTRAINT `voting_suara_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `people_db`.`users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `voting_suara`
--

LOCK TABLES `voting_suara` WRITE;
/*!40000 ALTER TABLE `voting_suara` DISABLE KEYS */;
/*!40000 ALTER TABLE `voting_suara` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-02-08 19:03:02
