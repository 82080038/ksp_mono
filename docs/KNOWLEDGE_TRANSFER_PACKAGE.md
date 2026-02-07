# Knowledge Transfer Package - Aplikasi KSP Mono
## Untuk Development di Komputer Lain

### 📋 **Overview**
Paket transfer pengetahuan ini berisi semua informasi penting untuk melanjutkan development aplikasi KSP Mono di komputer lain menggunakan Windsurf.

---

## 🗂️ **File Konfigurasi Utama**

### **1. Database Configuration**
```php
// File: config/config.php
// Copy ke komputer baru
$config = [
    'koperasi_db' => [
        'host' => 'localhost',
        'name' => 'koperasi_db',
        'user' => 'root',
        'pass' => 'root',
        'charset' => 'utf8mb4'
    ],
    'alamat_db' => [
        'host' => 'localhost',
        'name' => 'alamat_db',
        'user' => 'root',
        'pass' => 'root',
        'charset' => 'utf8mb4'
    ]
];
```

### **2. Apache/Nginx Configuration**
```apache
# File: .htaccess (Apache)
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ index.php [QSA,L]
```

---

## 📚 **Implementasi Kunci**

### **1. Sistem Caching Data Alamat**
**Lokasi**: `public/assets/js/address-cache-test.js`
**Fitur**:
- ✅ Hierarki caching per-ID (provinsi → kabkota → kecamatan → kelurahan)
- ✅ localStorage capacity monitoring
- ✅ Intelligent cache-first strategy
- ✅ Resource-aware capacity management
- ✅ Data validation sebelum caching

**Konfigurasi**:
```javascript
// Cache settings
this.cacheDuration = 24 * 60 * 60 * 1000; // 24 jam
this.cachePrefix = 'address_cache_test_';
this.maxDatePrefix = 'address_max_date_test_';
this.timePrefix = 'address_time_test_';
this.sizePrefix = 'address_size_test_';
```

### **2. Input Telepon Indonesia**
**Lokasi**: `public/register_koperasi.php` (lines 447-525)
**Fitur**:
- ✅ Auto-remove leading '0'
- ✅ '62' flash effect (3 detik)
- ✅ Format validation (12-15 digit, starts with 62)
- ✅ Real-time formatting (XXXX-XXXX-XXXX)

### **3. Sistem Validasi Form**
**Lokasi**: `public/register_koperasi_process.php`
**Aturan**:
- ✅ Nama koperasi: minimal 3 karakter
- ✅ Alamat lengkap: minimal 10 karakter
- ✅ Kontak: format telepon Indonesia valid
- ✅ NPWP: 15 digit angka (opsional)

---

## 🗃️ **Struktur Database**

### **Tabel Utama**
```sql
-- koperasi_db
koperasi_tenant    -- Data koperasi
pengguna          -- Data user/admin
peran_jenis       -- Daftar roles
pengguna_peran    -- User-role assignments
anggota           -- Data anggota koperasi

-- alamat_db (read-only)
provinsi          -- Data provinsi Indonesia
kabkota           -- Kabupaten/kota per provinsi
kecamatan         -- Kecamatan per kabupaten
kelurahan         -- Kelurahan/desa per kecamatan
```

### **Triggers untuk Max Date Tracking**
```sql
-- Auto-update table_max_dates saat data berubah
DELIMITER ;;
CREATE TRIGGER update_provinsi_max_date
AFTER INSERT ON provinsi
FOR EACH UPDATE table_max_dates
SET max_date = NOW(), last_checked = NOW()
WHERE table_name = 'provinsi';;
DELIMITER ;
```

---

## 🚀 **Setup Development Environment**

### **1. Prerequisites**
```bash
# PHP 8.0+
# MySQL/MariaDB 10.6+
# Apache/Nginx with mod_rewrite
# Composer (optional)
# Node.js (for any frontend tooling)
```

### **2. Database Setup**
```bash
# ✅ UPDATED: Import database dengan DATA TERLENGKAP
mysql -u root -proot < sql/alamat_db_with_data.sql
mysql -u root -proot < sql/koperasi_db_with_data.sql

# Verifikasi import berhasil
mysql -u root -proot alamat_db -e "SELECT COUNT(*) as alamat_records FROM provinsi;"
mysql -u root -proot koperasi_db -e "SHOW TABLES;"

# Backup database sebelum development (recommended)
mysqldump -u root -proot alamat_db > sql/alamat_db_backup_$(date +%Y%m%d).sql
mysqldump -u root -proot koperasi_db > sql/koperasi_db_backup_$(date +%Y%m%d).sql
```

### **📋 REMINDER UNTUK KOMPUTER LAIN:**
```
🚨 PENTING: Setelah git pull, HARUS sync database menggunakan file terbaru!

File database terbaru:
- sql/alamat_db_with_data.sql    (data alamat lengkap)
- sql/koperasi_db_with_data.sql  (data koperasi lengkap)

Langkah sinkronisasi:
1. Backup database lokal jika ada data penting
2. Drop & recreate databases jika perlu
3. Import file SQL terbaru
4. Verifikasi data sudah ter-import dengan benar
5. Test aplikasi untuk memastikan semuanya bekerja

PERINGATAN: Jangan commit file database dengan data sensitif ke GitHub!
```

### **3. Web Server Configuration**
```bash
# Apache virtual host
<VirtualHost *:80>
    ServerName ksp-mono.local
    DocumentRoot /path/to/ksp_mono/public
    <Directory /path/to/ksp_mono/public>
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

### **4. File Permissions**
```bash
# Set proper permissions
sudo chown -R www-data:www-data /var/www/html/ksp_mono
sudo chmod -R 755 /var/www/html/ksp_mono
sudo chmod -R 777 /var/www/html/ksp_mono/public/assets/js # For cache files
```

---

## 📖 **Dokumentasi Lengkap**

### **File Dokumentasi**
- `README.md` - Overview aplikasi
- `docs/ADDRESS_CACHING_STRATEGY.md` - Strategi caching detail
- `docs/APPLICATION_IMPROVEMENT_SUMMARY.md` - Summary improvements
- `docs/DOKUMENTASI_LENGKAP.md` - Dokumentasi lengkap

### **API Endpoints**
```php
// Address APIs
/api/provinces.php     → List provinsi
/api/regencies.php     → List kabupaten (by province_id)
/api/districts.php     → List kecamatan (by regency_id)
/api/villages.php      → List kelurahan (by district_id)
/api/address_cache.php → Cache validation info

// Business APIs
/api/koperasi_list.php → List koperasi terdaftar
/api/anggota.php       → CRUD anggota koperasi
```

---

## 🔧 **Troubleshooting Guide**

### **Common Issues**

#### **1. Cache Not Working**
```javascript
// Check localStorage
console.log('localStorage available:', typeof localStorage !== 'undefined');

// Clear cache
window.AddressCacheTest.clearCache();
```

#### **2. Form Validation Errors**
```javascript
// Check console for validation messages
// Verify phone format: 6281234567890 (12-15 digits, starts with 62)
// Verify NPWP: 123456789012345 (15 digits)
```

#### **3. Database Connection Issues**
```bash
# Test database connection
mysql -u root -proot koperasi_db -e "SELECT 1;"
mysql -u root -proot alamat_db -e "SELECT 1;"
```

---

## 🎯 **Key Features Implemented**

### **✅ Address Data Management**
- Hierarchical dropdown loading (Province → Regency → District → Village)
- Intelligent caching with localStorage capacity monitoring
- Real-time data validation

### **✅ User Registration System**
- Cooperative registration with address selection
- Admin/user creation with role assignment
- Phone number formatting with Indonesian standards

### **✅ Form Validation**
- Real-time input validation
- Indonesian phone number format validation
- Business entity number validation
- Comprehensive error handling

### **✅ Security Features**
- Session-based authentication
- Role-based access control (RBAC)
- Input sanitization
- SQL injection prevention

---

## 📞 **Contact & Support**

Untuk pertanyaan lebih lanjut atau troubleshooting, lihat:
- `docs/DOKUMENTASI_LENGKAP.md`
- Console logs untuk debugging
- Database logs untuk error tracking

---

**Paket transfer pengetahuan ini memastikan development dapat dilanjutkan dengan lancar di komputer lain dengan semua konteks dan konfigurasi yang diperlukan.** 🎯📚
