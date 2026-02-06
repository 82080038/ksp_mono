<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../app/bootstrap.php';

// Catatan:
// - Pendaftaran user/admin hanya boleh jika koperasi sudah ada di DB ksp_mono.
// - Data alamat (kecamatan) diambil dari database alamat_db (hanya baca, jangan ubah).
// - Endpoint pengambilan data koperasi/kecamatan belum diimplementasi di berkas ini.
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Registrasi Admin/User - KSP-PEB</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <style>
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }
        .card-header {
            background: linear-gradient(135deg, #007bff, #0056b3);
            color: white;
            border-radius: 15px 15px 0 0;
            padding: 1.5rem;
        }
        .form-floating > label {
            padding: 1rem 0.75rem;
        }
        .btn-primary {
            background: linear-gradient(135deg, #007bff, #0056b3);
            border: none;
            border-radius: 10px;
        }
        .btn-primary:hover {
            background: linear-gradient(135deg, #0056b3, #004085);
        }
    </style>
</head>
<body class="bg-light">
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="card shadow-sm border-0">
                <div class="card-header text-center">
                    <i class="bi bi-person-plus-fill display-4 text-white mb-2"></i>
                    <h5 class="mb-1">Registrasi Admin/User</h5>
                    <small class="text-white-50">Pilih koperasi yang sudah terdaftar. Jika belum ada, daftarkan koperasi terlebih dahulu.</small>
                </div>
                <div class="card-body p-4">
                    <h6 class="text-primary mb-3"><i class="bi bi-building"></i> Pilih Koperasi</h6>
                    <div class="form-floating mb-3">
                        <select class="form-select" name="koperasi_id" id="koperasiSelect" required>
                            <option value="">-- Pilih koperasi terdaftar --</option>
                            <!-- TODO: isi dari DB ksp_mono (tabel koperasi). Jangan ambil dari alamat_db -->
                        </select>
                        <label for="koperasiSelect"><i class="bi bi-building"></i> Koperasi (wajib)</label>
                    </div>
                    <div class="form-floating mb-3">
                        <select class="form-select" name="kecamatan_id" id="kecamatanSelect" required disabled>
                            <option value="">-- Pilih kecamatan --</option>
                            <!-- TODO: isi dari alamat_db (read-only). Jangan ubah alamat_db. -->
                        </select>
                        <label for="kecamatanSelect"><i class="bi bi-geo-alt"></i> Kecamatan (ikuti alamat koperasi)</label>
                    </div>
                    
                    <h6 class="text-primary mb-3 mt-4"><i class="bi bi-person-fill"></i> Informasi Pribadi</h6>
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" name="nama" required placeholder="Nama Lengkap">
                        <label><i class="bi bi-person"></i> Nama Lengkap</label>
                    </div>
                    
                    <h6 class="text-primary mb-3 mt-4"><i class="bi bi-shield-lock-fill"></i> Detail Akun</h6>
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" name="username" required placeholder="Username">
                        <label><i class="bi bi-person-circle"></i> Username</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="email" class="form-control" name="email" required placeholder="Email">
                        <label><i class="bi bi-envelope"></i> Email</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="password" class="form-control" name="password" required placeholder="Password">
                        <label><i class="bi bi-lock"></i> Password</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="password" class="form-control" name="password_confirm" required placeholder="Konfirmasi Password">
                        <label><i class="bi bi-lock-fill"></i> Konfirmasi Password</label>
                    </div>
                    <div class="form-floating mb-3">
                        <select class="form-select" name="peran_jenis_id" required>
                            <option value="">-- Pilih peran --</option>
                            <option value="1">Super Admin</option>
                            <option value="2">Anggota</option>
                        </select>
                        <label><i class="bi bi-shield"></i> Peran</label>
                    </div>
                    <div class="d-grid gap-2 mt-4">
                        <button type="submit" class="btn btn-primary btn-lg"><i class="bi bi-person-add"></i> Daftarkan User</button>
                    </div>
                    <div id="alertUser" class="alert alert-danger mt-3 d-none" role="alert"></div>
                </div>
                <div class="card-footer bg-white text-center">
                    <a href="/ksp_mono/login.php">Kembali ke Login</a>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
$(function(){
    // Load koperasi
    $.getJSON('/ksp_mono/api/koperasi_list.php')
        .done(function(res){
            const $sel = $('#koperasiSelect');
            if (res.success && Array.isArray(res.data)) {
                res.data.forEach(item => {
                    const kecName = item.kecamatan_nama ? ` (${item.kecamatan_nama})` : '';
                    $sel.append(`<option value="${item.id}" data-kec="${item.kecamatan_id}">${item.nama}${kecName}</option>`);
                });
            } else {
                $sel.append('<option value="">Gagal memuat koperasi</option>');
            }
        })
        .fail(function(){
            $('#koperasiSelect').append('<option value="">Gagal memuat koperasi</option>');
        });

    // Load kecamatan master (untuk filter per koperasi)
    let kecamatanMap = {};
    $.getJSON('/ksp_mono/api/kecamatan_list.php')
        .done(function(res){
            if (res.success && Array.isArray(res.data)) {
                res.data.forEach(item => {
                    kecamatanMap[item.id] = item.nama;
                });
            }
        });

    // Saat koperasi dipilih, isi kecamatan sesuai atribut data-kec
    $('#koperasiSelect').on('change', function(){
        const kecId = $(this).find(':selected').data('kec');
        const $kecSel = $('#kecamatanSelect');
        $kecSel.empty();
        if (kecId) {
            const nama = kecamatanMap[kecId] || 'Kecamatan';
            $kecSel.append(`<option value="${kecId}" selected>${nama}</option>`);
            $kecSel.prop('disabled', false);
        } else {
            $kecSel.append('<option value="">-- Pilih kecamatan --</option>');
            $kecSel.prop('disabled', true);
        }
    });

    $('#formRegisterUser').on('submit', function(e){
        e.preventDefault();
        $('#alertUser').addClass('d-none');

        // Validasi tambahan
        const nama = $('input[name="nama"]').val().trim();
        const username = $('input[name="username"]').val().trim();
        const email = $('input[name="email"]').val().trim();
        const password = $('input[name="password"]').val();
        const passwordConfirm = $('input[name="password_confirm"]').val();

        if (nama.length < 2) {
            $('#alertUser').removeClass('d-none alert-info').addClass('alert-danger').text('Nama lengkap minimal 2 karakter.');
            return;
        }
        const usernameRegex = /^[a-zA-Z0-9_]{4,20}$/;
        if (!usernameRegex.test(username)) {
            $('#alertUser').removeClass('d-none alert-info').addClass('alert-danger').text('Username harus 4-20 karakter, hanya huruf, angka, dan underscore.');
            return;
        }
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email)) {
            $('#alertUser').removeClass('d-none alert-info').addClass('alert-danger').text('Email tidak valid.');
            return;
        }
        if (password.length < 8) {
            $('#alertUser').removeClass('d-none alert-info').addClass('alert-danger').text('Password minimal 8 karakter.');
            return;
        }
        const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)/;
        if (!passwordRegex.test(password)) {
            $('#alertUser').removeClass('d-none alert-info').addClass('alert-danger').text('Password harus mengandung huruf besar, kecil, dan angka.');
            return;
        }
        if (password !== passwordConfirm) {
            $('#alertUser').removeClass('d-none alert-info').addClass('alert-danger').text('Konfirmasi password tidak cocok.');
            return;
        }

        const $form = $('#formRegisterUser');
        const data = $form.serialize();
        const $btn = $form.find('button[type="submit"]');
        const original = $btn.html();
        $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Memproses...');
        $.ajax({
            url: $form.attr('action'),
            method: 'POST',
            data: data,
            dataType: 'json'
        }).done(function(res){
            if (res.success) {
                $('#alertUser').removeClass('d-none alert-danger').addClass('alert-info').text(res.message || 'Berhasil daftar. Silakan login.');
                $form[0].reset();
                $('#kecamatanSelect').prop('disabled', true).empty().append('<option value="">-- Pilih kecamatan --</option>');
                setTimeout(()=>{ if(res.redirect) window.location = res.redirect; }, 1200);
            } else {
                $('#alertUser').removeClass('d-none alert-info').addClass('alert-danger').text(res.message || 'Gagal menyimpan data');
            }
        }).fail(function(){
            $('#alertUser').removeClass('d-none alert-info').addClass('alert-danger').text('Terjadi kesalahan koneksi.');
        }).always(function(){
            $btn.prop('disabled', false).html(original);
        });
    });
});
</script>
</body>
</html>
