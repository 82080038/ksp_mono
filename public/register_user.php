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
    <title>Registrasi Admin/User - ksp_mono</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
</head>
<body class="bg-light">
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="card shadow-sm border-0">
                <div class="card-header text-center bg-primary text-white">
                    <i class="bi bi-person-plus-fill display-4 mb-2"></i>
                    <h5 class="mb-1">Registrasi Admin/User</h5>
                    <small>Pilih koperasi yang sudah terdaftar</small>
                </div>
                <div class="card-body p-4">
                    <form id="formRegisterUser" action="register_user_process.php" method="POST">
                        <div class="input-field">
                            <select class="browser-default" name="koperasi_id" id="koperasiSelect" required>
                                <option value="">-- Pilih Koperasi --</option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label for="usernameInput" class="form-label">Username</label>
                            <input type="text" class="form-control" id="usernameInput" name="username" required>
                            <div class="invalid-feedback">Username minimal 4 karakter</div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="passwordInput" class="form-label">Password</label>
                            <input type="password" class="form-control" id="passwordInput" name="password" required>
                            <div class="invalid-feedback">Password minimal 4 karakter</div>
                        </div>
                        
                        <button type="submit" class="btn btn-primary btn-md w-100">
                            <i class="bi bi-person-plus"></i> Daftar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
$(function(){
    // Load koperasi
    $.getJSON('api/koperasi_list.php')
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
    $.getJSON('api/kecamatan_list.php')
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

    // Client-side validation
    $('#usernameInput').on('input', function() {
        const valid = /^[a-zA-Z0-9_]{4,20}$/.test($(this).val());
        $(this).toggleClass('is-invalid', !valid);
    });
    
    $('#passwordInput').on('input', function() {
        const valid = /^[a-zA-Z0-9_]{4,20}$/.test($(this).val());
        $(this).toggleClass('is-invalid', !valid);
    });
    
    $('#formRegisterUser').on('submit', function(e){
        e.preventDefault();
        $('#alertUser').addClass('d-none');

        // Validasi tambahan
        const username = $('input[name="username"]').val().trim();
        const password = $('input[name="password"]').val().trim();

        const usernameRegex = /^[a-zA-Z0-9_]{4,20}$/;
        if (!usernameRegex.test(username)) {
            $('#alertUser').removeClass('d-none alert-info').addClass('alert-danger').text('Username harus 4-20 karakter, hanya huruf, angka, dan underscore.');
            return;
        }

        const passwordRegex = /^[a-zA-Z0-9_]{4,20}$/;
        if (!passwordRegex.test(password)) {
            $('#alertUser').removeClass('d-none alert-info').addClass('alert-danger').text('Password harus 4-20 karakter, hanya huruf, angka, dan underscore.');
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
