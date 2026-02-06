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
</head>
<body class="bg-light">
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Registrasi Admin/User</h5>
                    <small class="text-muted">Pilih koperasi yang sudah terdaftar. Jika belum ada, daftarkan koperasi terlebih dahulu.</small>
                </div>
                <div class="card-body">
                    <form id="formRegisterUser" action="/ksp_mono/register_user_action.php" method="post">
                        <div class="mb-3">
                            <label class="form-label">Pilih Koperasi (wajib)</label>
                            <select class="form-select" name="koperasi_id" id="koperasiSelect" required>
                                <option value="">-- Pilih koperasi terdaftar --</option>
                                <!-- TODO: isi dari DB ksp_mono (tabel koperasi). Jangan ambil dari alamat_db -->
                            </select>
                            <div class="form-text">Jika daftar kosong, silakan daftarkan koperasi terlebih dahulu.</div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Kecamatan (ikuti alamat koperasi)</label>
                            <select class="form-select" name="kecamatan_id" id="kecamatanSelect" required disabled>
                                <option value="">-- Pilih kecamatan --</option>
                                <!-- TODO: isi dari alamat_db (read-only). Jangan ubah alamat_db. -->
                            </select>
                            <div class="form-text">Opsi kecamatan mengikuti koperasi yang dipilih.</div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" class="form-control" name="nama" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Username</label>
                            <input type="text" class="form-control" name="username" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" class="form-control" name="password" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Konfirmasi Password</label>
                            <input type="password" class="form-control" name="password_confirm" required>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Daftarkan User</button>
                        </div>
                    </form>
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
