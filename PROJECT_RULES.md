# Aturan Proyek KSP-PEB

## Database
Aplikasi ini menggunakan dua database terpisah:

### alamat_db (Read-Only)
- **Tujuan**: Menyimpan data wilayah Indonesia (provinsi, kabupaten/kota, kecamatan, kelurahan/desa).
- **Akses**: Hanya baca (read-only), tidak boleh diubah atau dimodifikasi.
- **Penggunaan**: ID dari alamat_db digunakan oleh database koperasi_db untuk referensi alamat.
- **Koneksi**: Dikonfigurasi di `config.php` dengan key `'alamat_db'`.
- **Helper**: Class `Address` di `app/Address.php` untuk fetch data alamat secara global di aplikasi.
- **API**: Endpoint di `public/api/` (provinces.php, regencies.php, dll.) untuk fetch data JSON via AJAX.

### koperasi_db (Read-Write)
- **Tujuan**: Menyimpan data koperasi, pengguna, dan transaksi terkait.
- **Akses**: Baca dan tulis (read-write).
- **Penggunaan**: Menggunakan ID dari alamat_db untuk referensi alamat koperasi/pengguna.
- **Koneksi**: Dikonfigurasi di `config.php` dengan key `'db'`.
- **Helper**: Class `Database` di `app/Database.php` untuk koneksi.

## Aturan Penggunaan Data Alamat
- **Read-Only**: Semua operasi terhadap alamat_db hanya boleh membaca data, tidak ada INSERT, UPDATE, atau DELETE.
- **Referensi ID**: ID provinsi, regency, district, village dari alamat_db disimpan di koperasi_db (tabel koperasi_tenant) dan ditampilkan di aplikasi.
- **Validasi**: Sebelum menyimpan data koperasi, validasi ID alamat terhadap alamat_db untuk memastikan konsistensi.
- **Tampilan**: Data alamat ditampilkan di form registrasi dan aplikasi melalui dropdown dinamis atau fetch PHP.

## Konfigurasi
- **config.php**: Berisi konfigurasi kedua database dengan key `'db'` dan `'alamat_db'`.
- **Bootstrap**: `app/bootstrap.php` memuat semua helper termasuk `Address` untuk akses global.

## Pengembangan
- **Coding**: Saat mengembangkan, pastikan tidak ada kode yang memodifikasi alamat_db.
- **Testing**: Uji validasi alamat di registrasi koperasi dan pengguna.
- **Dokumentasi**: Aturan ini harus diikuti di semua bagian aplikasi, baik sebelum maupun sesudah login.

## Konfigurasi
- **config.php**: Berisi konfigurasi kedua database dengan key `'db'` dan `'alamat_db'`.
- **Bootstrap**: `app/bootstrap.php` memuat semua helper termasuk `Address` untuk akses global.

## Pengembangan
- **Coding**: Saat mengembangkan, pastikan tidak ada kode yang memodifikasi alamat_db.
- **Testing**: Uji validasi alamat di registrasi koperasi dan pengguna.
- **Dokumentasi**: Aturan ini harus diikuti di semua bagian aplikasi, baik sebelum maupun sesudah login.

## Aturan Tambahan dari Dokumentasi Pengembangan

### Multiple Roles & Fleksibilitas Sistem
- **Pengurus Multiple Roles**: Pengurus koperasi bisa memiliki multiple roles seperti investor, agen/reseller, pembeli.
- **Conflict of Interest Management**:
  - Tracking jika pengurus juga investor/agen.
  - Approval khusus untuk transaksi pengurus sebagai investor/agen.
  - Transparansi dalam komisi dan pembagian keuntungan.
  - Logging semua aktivitas pengurus dalam multiple roles.
- **Transparansi**: Semua transaksi pengurus dalam multiple roles dicatat dan bisa diaudit.
- **Laporan Terpisah**: Laporan untuk pengurus sebagai investor/agen/pembeli terpisah untuk transparansi.

### Keamanan & Akses
- **Password Hashing**: Menggunakan bcrypt untuk hashing password.
- **Role-Based Access Control (RBAC)**: Sistem permission berdasarkan kombinasi roles.
- **Session Timeout**: Implementasi timeout session untuk keamanan.
- **Audit Trail**: Pencatatan semua aktivitas untuk audit.
- **Input Validation**: Validasi dan sanitization input di semua form.

### Database & Struktur
- **Engine Database**: InnoDB untuk transaksi ACID.
- **Character Set**: utf8mb4 untuk mendukung emoji dan karakter khusus.
- **Collation**: utf8mb4_unicode_ci.
- **Backup**: SQL Dump otomatis harian/mingguan.
- **Cache**: Redis opsional untuk session dan cache query.

### API & Integrasi
- **Format API**: JSON untuk semua komunikasi frontend-backend.
- **Authentication**: JWT Token atau Session Token.
- **Versioning**: v1, v2, dll untuk API.
- **Integrasi Pihak Ketiga**: Payment Gateway (Midtrans, Doku), Shipping (RajaOngkir), Bank, E-Wallet, SMS/WhatsApp, Email, Maps, QR Code, Cloud Storage.

### Penamaan & Konvensi Kode
- **Bahasa**: Semua kode dan dokumentasi menggunakan Bahasa Indonesia.
- **Nama Tabel**: Menggunakan pola Indonesia, contoh: pengguna, koperasi_tenant, simpanan_transaksi.
- **Nama Kolom**: Menggunakan format snake_case, contoh: dibuat_pada, diperbarui_pada.
- **FK**: <tabel>_id, contoh: koperasi_id, pengguna_id.
- **Relasi Detail**: Format utama_detail, contoh: pesanan_detail, pinjaman_angsuran.

### Operasional & Bisnis
- **Multiple Roles per User**: Satu user bisa memiliki multiple roles (pengurus + investor + agen + pembeli).
- **Dashboard Berbeda**: Dashboard sesuai roles yang dimiliki user.
- **Transaksi Transparan**: Semua transaksi tercatat dengan role yang terlibat.
- **Approval Workflow**: Workflow approval untuk transaksi yang melibatkan conflict of interest.
- **SHU & Dividen**: Perhitungan otomatis SHU anggota dan dividen investor.
- **Compliance**: Monitoring compliance terhadap regulasi koperasi dan AD/ART.

## Catatan Tambahan
- Jika ada perubahan data alamat, lakukan di luar aplikasi (misalnya via phpMyAdmin atau script eksternal).
- Helper Address menyediakan metode untuk fetch dan validasi data alamat secara PHP.
- Sistem dirancang untuk mobile-first dan responsif di semua perangkat.
- Backup data otomatis dan disaster recovery plan.
- E-modul pelatihan untuk anggota dan pengurus (opsional).
