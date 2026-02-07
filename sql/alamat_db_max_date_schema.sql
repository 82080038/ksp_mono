-- Address Data Max Date Tracking Schema
-- More efficient change detection using max date instead of full table scanning

-- Create table to track max dates for each address table
CREATE TABLE IF NOT EXISTS `table_max_dates` (
    `table_name` varchar(50) NOT NULL,
    `max_date` timestamp DEFAULT CURRENT_TIMESTAMP,
    `record_count` int(11) unsigned DEFAULT 0,
    `last_checked` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`table_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert initial max dates for existing tables
INSERT INTO `table_max_dates` (`table_name`, `max_date`, `record_count`) VALUES
('provinsi', (SELECT COALESCE(MAX(created_at), '2000-01-01 00:00:00') FROM provinsi), (SELECT COUNT(*) FROM provinsi)),
('kabkota', (SELECT COALESCE(MAX(created_at), '2000-01-01 00:00:00') FROM kabkota), (SELECT COUNT(*) FROM kabkota)),
('kecamatan', (SELECT COALESCE(MAX(created_at), '2000-01-01 00:00:00') FROM kecamatan), (SELECT COUNT(*) FROM kecamatan)),
('kelurahan', (SELECT COALESCE(MAX(created_at), '2000-01-01 00:00:00') FROM kelurahan), (SELECT COUNT(*) FROM kelurahan))
ON DUPLICATE KEY UPDATE
    `max_date` = VALUES(`max_date`),
    `record_count` = VALUES(`record_count`),
    `last_checked` = CURRENT_TIMESTAMP;

-- Drop old alamat_versions table if exists (we'll use the more efficient max_date approach)
DROP TABLE IF EXISTS `alamat_versions`;

-- Function to update max_date for a table
DELIMITER ;;

CREATE PROCEDURE `update_table_max_date`(IN tbl_name VARCHAR(50))
BEGIN
    SET @sql = CONCAT('UPDATE table_max_dates SET max_date = (SELECT COALESCE(MAX(created_at), ''2000-01-01 00:00:00'') FROM ', tbl_name, '), record_count = (SELECT COUNT(*) FROM ', tbl_name, ') WHERE table_name = ''', tbl_name, '''');
    PREPARE stmt FROM @sql;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
END;;

DELIMITER ;

-- Create triggers for provinsi table
DELIMITER ;;

CREATE TRIGGER `provinsi_max_date_insert` AFTER INSERT ON `provinsi`
    FOR EACH ROW BEGIN
        UPDATE table_max_dates SET max_date = (SELECT COALESCE(MAX(created_at), '2000-01-01 00:00:00') FROM provinsi), record_count = (SELECT COUNT(*) FROM provinsi) WHERE table_name = 'provinsi';
    END;;

CREATE TRIGGER `provinsi_max_date_update` AFTER UPDATE ON `provinsi`
    FOR EACH ROW BEGIN
        UPDATE table_max_dates SET max_date = (SELECT COALESCE(MAX(created_at), '2000-01-01 00:00:00') FROM provinsi), record_count = (SELECT COUNT(*) FROM provinsi) WHERE table_name = 'provinsi';
    END;;

CREATE TRIGGER `provinsi_max_date_delete` AFTER DELETE ON `provinsi`
    FOR EACH ROW BEGIN
        UPDATE table_max_dates SET max_date = (SELECT COALESCE(MAX(created_at), '2000-01-01 00:00:00') FROM provinsi), record_count = (SELECT COUNT(*) FROM provinsi) WHERE table_name = 'provinsi';
    END;;

-- Create triggers for kabkota table
CREATE TRIGGER `kabkota_max_date_insert` AFTER INSERT ON `kabkota`
    FOR EACH ROW BEGIN
        UPDATE table_max_dates SET max_date = (SELECT COALESCE(MAX(created_at), '2000-01-01 00:00:00') FROM kabkota), record_count = (SELECT COUNT(*) FROM kabkota) WHERE table_name = 'kabkota';
    END;;

CREATE TRIGGER `kabkota_max_date_update` AFTER UPDATE ON `kabkota`
    FOR EACH ROW BEGIN
        UPDATE table_max_dates SET max_date = (SELECT COALESCE(MAX(created_at), '2000-01-01 00:00:00') FROM kabkota), record_count = (SELECT COUNT(*) FROM kabkota) WHERE table_name = 'kabkota';
    END;;

CREATE TRIGGER `kabkota_max_date_delete` AFTER DELETE ON `kabkota`
    FOR EACH ROW BEGIN
        UPDATE table_max_dates SET max_date = (SELECT COALESCE(MAX(created_at), '2000-01-01 00:00:00') FROM kabkota), record_count = (SELECT COUNT(*) FROM kabkota) WHERE table_name = 'kabkota';
    END;;

-- Create triggers for kecamatan table
CREATE TRIGGER `kecamatan_max_date_insert` AFTER INSERT ON `kecamatan`
    FOR EACH ROW BEGIN
        UPDATE table_max_dates SET max_date = (SELECT COALESCE(MAX(created_at), '2000-01-01 00:00:00') FROM kecamatan), record_count = (SELECT COUNT(*) FROM kecamatan) WHERE table_name = 'kecamatan';
    END;;

CREATE TRIGGER `kecamatan_max_date_update` AFTER UPDATE ON `kecamatan`
    FOR EACH ROW BEGIN
        UPDATE table_max_dates SET max_date = (SELECT COALESCE(MAX(created_at), '2000-01-01 00:00:00') FROM kecamatan), record_count = (SELECT COUNT(*) FROM kecamatan) WHERE table_name = 'kecamatan';
    END;;

CREATE TRIGGER `kecamatan_max_date_delete` AFTER DELETE ON `kecamatan`
    FOR EACH ROW BEGIN
        UPDATE table_max_dates SET max_date = (SELECT COALESCE(MAX(created_at), '2000-01-01 00:00:00') FROM kecamatan), record_count = (SELECT COUNT(*) FROM kecamatan) WHERE table_name = 'kecamatan';
    END;;

-- Create triggers for kelurahan table
CREATE TRIGGER `kelurahan_max_date_insert` AFTER INSERT ON `kelurahan`
    FOR EACH ROW BEGIN
        UPDATE table_max_dates SET max_date = (SELECT COALESCE(MAX(created_at), '2000-01-01 00:00:00') FROM kelurahan), record_count = (SELECT COUNT(*) FROM kelurahan) WHERE table_name = 'kelurahan';
    END;;

CREATE TRIGGER `kelurahan_max_date_update` AFTER UPDATE ON `kelurahan`
    FOR EACH ROW BEGIN
        UPDATE table_max_dates SET max_date = (SELECT COALESCE(MAX(created_at), '2000-01-01 00:00:00') FROM kelurahan), record_count = (SELECT COUNT(*) FROM kelurahan) WHERE table_name = 'kelurahan';
    END;;

CREATE TRIGGER `kelurahan_max_date_delete` AFTER DELETE ON `kelurahan`
    FOR EACH ROW BEGIN
        UPDATE table_max_dates SET max_date = (SELECT COALESCE(MAX(created_at), '2000-01-01 00:00:00') FROM kelurahan), record_count = (SELECT COUNT(*) FROM kelurahan) WHERE table_name = 'kelurahan';
    END;;

DELIMITER ;
