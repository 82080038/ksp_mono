# Rencana CRUD & Registrasi (Prioritas Material Style)

## Prioritas Utama (pra-login)
1. **Registrasi Koperasi**
   - Form pendaftaran koperasi (nama koperasi, alamat, kontak, NPWP optional).
   - Simpan sebagai record utama (tabel `koperasi`).
   - Validasi server-side & client-side.
2. **Registrasi Admin Pertama**
   - Form admin (username, email, password, konfirmasi, nama lengkap).
   - Terkait ke koperasi yang baru dibuat.
   - After submit: auto-login admin atau redirect ke login.
3. **Login/Logout**
   - Redirect sukses ke `/ksp_mono/` (dashboard).
   - Logout ke `/ksp_mono/login.php`.

## Tahap CRUD per Modul (setelah login)
1. **Dashboard**
   - Ringkasan cards (anggota, simpanan, pinjaman) dengan gaya material.
   - Aktivitas terbaru (table) + CTA menuju modul.
2. **Anggota**
   - List (table), Tambah, Edit, Hapus.
   - Field utama: NIK, Nama, Alamat, No HP.
   - API `/api/anggota.php` (GET/POST/PUT/DELETE).
3. **Simpanan**
   - List transaksi + filter tanggal/anggota.
   - Tambah, Edit, Hapus transaksi simpanan.
   - API `/api/simpanan.php`.
4. **Pinjaman**
   - List pinjaman + status (pengajuan, berjalan, lunas).
   - Tambah, Edit status, Hapus.
   - API `/api/pinjaman.php`.
5. **Laporan**
   - Placeholder tabel + filter (periode, jenis laporan) dan tombol export (dummy dulu).
6. **Pengaturan**
   - Profil admin: nama, email, password.
   - Preferensi tema (material colors) opsional.

## Gaya Material (untuk semua halaman)
- Palet: primary `#6200ee`, secondary `#018786`, bg `#f4f5fb`, font Roboto.
- Elevation: shadow lembut (0 10px 24px rgba(0,0,0,0.06)).
- Rounded 12px cards, 8px inputs/buttons.
- Spacing: padding konten 24px, gap antar card 20px.

## Tautan/Nav
- Topbar dropdown id unik `topUserDropdown`.
- Sidebar nav: Dashboard, Anggota, Simpanan, Pinjaman, Laporan, Pengaturan.
- Pastikan semua href tanpa `/public` berlebih (basis `/ksp_mono/`).

## Langkah Eksekusi Singkat
1) Implement form registrasi koperasi + admin (pra-login) beserta endpoint penyimpanan.
2) Cek/rapikan navbar/sidebar (Material look) dan toggler/dropdown.
3) CRUD Anggota selesai (guard jQuery sudah ada) — lanjut Simpanan/Pinjaman/Laporan/Pengaturan secara bertahap.
4) Uji konsol untuk error/404 setiap modul.
