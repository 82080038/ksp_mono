-- Rencana migrasi penamaan tabel/kolom ke Bahasa Indonesia (BELUM DIJALANKAN)
-- Jalankan satu per satu setelah backup penuh. Pastikan tidak ada koneksi aktif.

-- 1) Contoh renaming tabel inti (sesuaikan kebutuhan):
-- RENAME TABLE cooperatives TO koperasi_tenant;
-- RENAME TABLE users TO pengguna;
-- RENAME TABLE roles TO peran_jenis;
-- RENAME TABLE permissions TO izin_modul;
-- RENAME TABLE role_permissions TO pengguna_izin_peran;
-- RENAME TABLE user_roles TO pengguna_peran;
-- RENAME TABLE simpanan_transactions TO simpanan_transaksi;
-- RENAME TABLE simpanan_types TO simpanan_jenis;
-- RENAME TABLE pinjaman TO pinjaman;
-- RENAME TABLE pinjaman_angsuran TO pinjaman_angsuran;
-- RENAME TABLE cooperative_financial_settings TO koperasi_keuangan_pengaturan;
-- RENAME TABLE cooperative_status_history TO koperasi_status_riwayat;
-- RENAME TABLE cooperative_document_history TO koperasi_dokumen_riwayat;
-- RENAME TABLE audit_logs TO log_audit;
-- RENAME TABLE notifications TO notifikasi;
-- RENAME TABLE orders TO pesanan;
-- RENAME TABLE order_details TO pesanan_detail;
-- RENAME TABLE products TO produk;
-- Catatan pola penamaan relasi/child: gunakan format <tabel_utama>_<tabel_detail>
-- Contoh: izin (utama) + peran (detail relasi) => izin_peran
-- Sesuaikan tabel lain yang merupakan detail/relasi agar konsisten (mis. peran_pengguna, produk_pesanan, dsb.).

-- Mapping lengkap (belum dieksekusi) untuk seluruh tabel koperasi_db:
-- RENAME TABLE agent_sales                  TO penjualan_agen;
-- RENAME TABLE anggota                      TO anggota;             -- sudah Indonesia
-- RENAME TABLE audit_logs                   TO log_audit;
-- RENAME TABLE chart_of_accounts            TO akuntansi_jenis;
-- RENAME TABLE configs                      TO konfigurasi;
-- RENAME TABLE koperasi_setting_detil       TO koperasi_pengaturan_detail;
-- RENAME TABLE cooperative_document_history TO koperasi_dokumen_riwayat;
-- RENAME TABLE cooperative_financial_settings TO koperasi_keuangan_pengaturan;
-- RENAME TABLE cooperative_status_history   TO koperasi_status_riwayat;
-- RENAME TABLE cooperative_types            TO koperasi_jenis;
-- RENAME TABLE cooperatives                 TO koperasi_tenant;
-- RENAME TABLE general_ledger               TO buku_besar;
-- RENAME TABLE journal_entries              TO jurnal;
-- RENAME TABLE journal_entry_details        TO jurnal_detail;       -- child: jurnal_detail
-- RENAME TABLE member_shu                   TO shu_anggota;
-- RENAME TABLE modal_pokok_changes          TO modal_pokok_perubahan;
-- RENAME TABLE notifications                TO notifikasi;
-- RENAME TABLE orders                       TO pesanan;
-- RENAME TABLE order_details                TO pesanan_detail;      -- child: pesanan_detail
-- RENAME TABLE pengawas                     TO pengawas;            -- sudah
-- RENAME TABLE pengurus                     TO pengurus;            -- sudah
-- RENAME TABLE permissions                  TO izin_modul;
-- RENAME TABLE pinjaman                     TO pinjaman;            -- sudah
-- RENAME TABLE pinjaman_angsuran            TO pinjaman_angsuran;   -- sudah format utama_detail
-- RENAME TABLE products                     TO produk;
-- RENAME TABLE rat_sessions                 TO rat_sesi;
-- RENAME TABLE role_permissions             TO pengguna_izin_peran; -- pola utama_detail (pengguna-izin-peran)
-- RENAME TABLE roles                        TO peran_jenis;
-- RENAME TABLE shu_distributions            TO shu_distribusi;
-- RENAME TABLE simpanan_transactions        TO simpanan_transaksi;
-- RENAME TABLE simpanan_types               TO simpanan_jenis;
-- RENAME TABLE tenant_configs               TO tenant_konfigurasi;
-- RENAME TABLE user_roles                   TO pengguna_peran;
-- RENAME TABLE users                        TO pengguna;
-- RENAME TABLE v_cooperative_complete       TO v_koperasi_lengkap;  -- view
-- RENAME TABLE vote_ballots                 TO voting_suara;
-- RENAME TABLE votes                        TO voting;

-- 2) Contoh perubahan kolom (setelah rename tabel, sesuaikan tipe jika perlu):

-- Pedoman kolom:
-- PK: id
-- FK: <tabel>_id (contoh: koperasi_id, pengguna_id)
-- Waktu: dibuat_pada, diperbarui_pada
-- Ejaan: detail (bukan detil), nomor_badan_hukum, nik_koperasi

-- Draft ALTER (komentar, jalankan selektif setelah backup):

-- ALTER TABLE koperasi_tenant
--   CHANGE id id INT NOT NULL AUTO_INCREMENT,
--   CHANGE nama nama_koperasi VARCHAR(255) NOT NULL,
--   CHANGE jenis jenis_koperasi JSON NULL,
--   CHANGE status_badan_hukum status_badan_hukum ENUM('belum_terdaftar','terdaftar','badan_hukum') DEFAULT 'belum_terdaftar',
--   CHANGE tanggal_status_terakhir tanggal_status_terakhir DATE NULL,
--   CHANGE status_notes catatan_status TEXT NULL,
--   CHANGE tanggal_pendirian tanggal_pendirian DATE NULL,
--   CHANGE npwp npwp VARCHAR(50) NULL,
--   CHANGE nomor_bh nomor_badan_hukum VARCHAR(50) NULL,
--   CHANGE nib nib VARCHAR(20) NULL,
--   CHANGE nik_koperasi nik_koperasi VARCHAR(20) NULL,
--   CHANGE alamat_legal alamat_legal TEXT NULL,
--   CHANGE kontak_resmi kontak_resmi VARCHAR(255) NULL,
--   CHANGE created_by dibuat_oleh INT NULL,
--   CHANGE created_at dibuat_pada TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
--   CHANGE updated_at diperbarui_pada TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
--   CHANGE province_id provinsi_id INT NULL,
--   CHANGE regency_id kabkota_id INT NULL,
--   CHANGE district_id kecamatan_id INT NULL,
--   CHANGE village_id kelurahan_id INT NULL;

-- ALTER TABLE pengguna
--   CHANGE id id INT NOT NULL AUTO_INCREMENT,
--   CHANGE username pengguna_username VARCHAR(100) NOT NULL,
--   CHANGE password_hash sandi_hash VARCHAR(255) NOT NULL,
--   CHANGE user_db_id sumber_pengguna_id INT NOT NULL,
--   CHANGE status status ENUM('active','inactive','pending') DEFAULT 'active',
--   CHANGE created_at dibuat_pada TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
--   CHANGE updated_at diperbarui_pada TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP;

-- ALTER TABLE pengguna_izin_peran
--   CHANGE role_id peran_jenis_id INT NOT NULL,
--   CHANGE permission_id izin_modul_id INT NOT NULL;

-- ALTER TABLE pengguna_peran
--   CHANGE user_id pengguna_id INT NOT NULL,
--   CHANGE role_id peran_jenis_id INT NOT NULL;

-- ALTER TABLE koperasi_status_riwayat
--   CHANGE id id INT NOT NULL AUTO_INCREMENT,
--   CHANGE cooperative_id koperasi_id INT NOT NULL,
--   CHANGE status status VARCHAR(50) NOT NULL,
--   CHANGE tanggal_status tanggal_status DATE NULL,
--   CHANGE status_notes catatan_status TEXT NULL,
--   CHANGE created_at dibuat_pada TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
--   CHANGE updated_at diperbarui_pada TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP;

-- ALTER TABLE koperasi_dokumen_riwayat
--   CHANGE id id INT NOT NULL AUTO_INCREMENT,
--   CHANGE cooperative_id koperasi_id INT NOT NULL,
--   CHANGE document_name dokumen_nama VARCHAR(255) NULL,
--   CHANGE status status VARCHAR(50) NULL,
--   CHANGE created_at dibuat_pada TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
--   CHANGE updated_at diperbarui_pada TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP;

-- ALTER TABLE koperasi_pengaturan_detail
--   CHANGE id id INT NOT NULL AUTO_INCREMENT,
--   CHANGE koperasi_id koperasi_id INT NOT NULL,
--   CHANGE key pengaturan_kunci VARCHAR(100) NOT NULL,
--   CHANGE value pengaturan_nilai TEXT NULL,
--   CHANGE created_at dibuat_pada TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
--   CHANGE updated_at diperbarui_pada TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP;

-- ALTER TABLE pesanan
--   CHANGE id id INT NOT NULL AUTO_INCREMENT,
--   CHANGE user_id pengguna_id INT NOT NULL,
--   CHANGE total_amount total DECIMAL(15,2) DEFAULT 0.00,
--   CHANGE status status VARCHAR(50) NULL,
--   CHANGE created_at dibuat_pada TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
--   CHANGE updated_at diperbarui_pada TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP;

-- ALTER TABLE pesanan_detail
--   CHANGE id id INT NOT NULL AUTO_INCREMENT,
--   CHANGE order_id pesanan_id INT NOT NULL,
--   CHANGE product_id produk_id INT NOT NULL,
--   CHANGE quantity kuantitas INT NOT NULL,
--   CHANGE price harga_satuan DECIMAL(15,2) DEFAULT 0.00,
--   CHANGE subtotal subtotal DECIMAL(15,2) DEFAULT 0.00;

-- ALTER TABLE simpanan_transaksi
--   CHANGE id id INT NOT NULL AUTO_INCREMENT,
--   CHANGE member_id anggota_id INT NOT NULL,
--   CHANGE type_id simpanan_jenis_id INT NOT NULL,
--   CHANGE amount nilai DECIMAL(15,2) DEFAULT 0.00,
--   CHANGE transaction_date tanggal_transaksi DATE NULL,
--   CHANGE notes keterangan TEXT NULL,
--   CHANGE status status VARCHAR(50) NULL;

-- ALTER TABLE simpanan_jenis
--   CHANGE id id INT NOT NULL AUTO_INCREMENT,
--   CHANGE name nama VARCHAR(100) NOT NULL,
--   CHANGE description deskripsi TEXT NULL;

-- ALTER TABLE pinjaman
--   CHANGE id id INT NOT NULL AUTO_INCREMENT,
--   CHANGE member_id anggota_id INT NOT NULL,
--   CHANGE principal pokok DECIMAL(15,2) DEFAULT 0.00,
--   CHANGE interest_rate bunga_persen DECIMAL(5,2) DEFAULT 0.00,
--   CHANGE tenor tenor INT NULL,
--   CHANGE status status VARCHAR(50) NULL,
--   CHANGE start_date tanggal_mulai DATE NULL;

-- ALTER TABLE pinjaman_angsuran
--   CHANGE id id INT NOT NULL AUTO_INCREMENT,
--   CHANGE pinjaman_id pinjaman_id INT NOT NULL,
--   CHANGE installment_no angsuran_ke INT NULL,
--   CHANGE amount jumlah DECIMAL(15,2) DEFAULT 0.00,
--   CHANGE due_date jatuh_tempo DATE NULL,
--   CHANGE status status VARCHAR(50) NULL;

-- ALTER TABLE produk
--   CHANGE id id INT NOT NULL AUTO_INCREMENT,
--   CHANGE name nama VARCHAR(150) NOT NULL,
--   CHANGE description deskripsi TEXT NULL,
--   CHANGE price harga DECIMAL(15,2) DEFAULT 0.00,
--   CHANGE stock stok INT DEFAULT 0;

-- ALTER TABLE penjualan_agen
--   CHANGE id id INT NOT NULL AUTO_INCREMENT,
--   CHANGE order_id pesanan_id INT NOT NULL,
--   CHANGE agent_name nama_agen VARCHAR(150) NULL,
--   CHANGE commission komisi DECIMAL(15,2) DEFAULT 0.00;

-- 3) FK (ingat sesuaikan struktur aktual sebelum eksekusi):
--   pesanan.user_id        -> pengguna.id
--   pesanan.koperasi_id    -> koperasi_tenant.id (jika kolom ada)
--   pesanan_detail.pesanan_id -> pesanan.id
--   pesanan_detail.produk_id  -> produk.id
--   simpanan_transaksi.anggota_id -> anggota.id
--   simpanan_transaksi.simpanan_jenis_id -> simpanan_jenis.id
--   pinjaman.anggota_id    -> anggota.id
--   pinjaman_angsuran.pinjaman_id -> pinjaman.id
--   pengguna_peran: pengguna_id -> pengguna.id; peran_jenis_id -> peran_jenis.id
--   pengguna_izin_peran: peran_jenis_id -> peran_jenis.id; izin_modul_id -> izin_modul.id
--   koperasi_status_riwayat.koperasi_id -> koperasi_tenant.id
--   koperasi_dokumen_riwayat.koperasi_id -> koperasi_tenant.id

-- 4) Langkah aman eksekusi:
--  a) BACKUP: mysqldump -u root -proot ksp_mono > /tmp/ksp_mono_before_rename.sql
--  b) Matikan akses aplikasi sementara.
--  c) Jalankan RENAME TABLE satu per satu (atau batch jika yakin).
--  d) Jalankan ALTER TABLE per kolom sesuai kebutuhan.
--  e) Ubah kode PHP/SQL agar memakai nama baru (tabel & kolom), lalu uji regresi penuh.

-- Catatan: Sesuaikan daftar tabel/kolom di atas dengan kebutuhan pasti. Jangan jalankan tanpa backup.
