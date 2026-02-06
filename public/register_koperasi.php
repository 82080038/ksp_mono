<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../app/bootstrap.php';

// NOTE: Backend penyimpanan belum diimplementasi; form ini hanya tampilan awal.
// Sesuaikan endpoint/action sesuai tabel koperasi di database ksp_mono.

?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Registrasi Koperasi - KSP-PEB</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Registrasi Koperasi</h5>
                    <small class="text-muted">Lengkapi data dasar koperasi.</small>
                </div>
                <div class="card-body">
                    <form id="formRegisterKoperasi" action="/ksp_mono/register_koperasi_action.php" method="post">
                        <div class="row g-2">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Provinsi</label>
                                <select class="form-select" name="province_id" id="koperasiProvSelect" required>
                                    <option value="">-- Pilih provinsi --</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Kabupaten/Kota</label>
                                <select class="form-select" name="regency_id" id="koperasiRegencySelect" required disabled>
                                    <option value="">-- Pilih kab/kota --</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Kecamatan</label>
                                <select class="form-select" name="district_id" id="koperasiDistrictSelect" required disabled>
                                    <option value="">-- Pilih kecamatan --</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Kelurahan/Desa</label>
                                <select class="form-select" name="village_id" id="koperasiVillageSelect" required disabled>
                                    <option value="">-- Pilih kelurahan/desa --</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3" style="max-width:220px;">
                                <label class="form-label">Kode Pos</label>
                                <input type="text" class="form-control" name="postal_code" id="koperasiPostalCode" readonly disabled>
                            </div>
                        </div>
                        
                         <div class="mb-3">
                            <label class="form-label">Alamat</label>
                            <textarea class="form-control" name="alamat_lengkap" rows="2" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nama Koperasi</label>
                            <input type="text" class="form-control" name="nama_koperasi" required>
                        </div>
                       
                        <div class="mb-3">
                            <label class="form-label">Kontak (Telp/HP)</label>
                            <input type="text" class="form-control" name="kontak" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">NPWP (opsional)</label>
                            <input type="text" class="form-control" name="npwp">
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Daftarkan Koperasi</button>
                        </div>
                    </form>
                    <div id="alertKoperasi" class="alert alert-danger mt-3 d-none" role="alert"></div>
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
    const $prov = $('#koperasiProvSelect');
    const $reg  = $('#koperasiRegencySelect');
    const $dist = $('#koperasiDistrictSelect');
    const $vill = $('#koperasiVillageSelect');

    $.getJSON('/ksp_mono/api/provinces.php')
        .done(function(res){
            if (res.success && Array.isArray(res.data)) {
                res.data.forEach(item => $prov.append(`<option value="${item.id}">${item.nama}</option>`));
            }
        });

    $prov.on('change', function(){
        const pid = $(this).val();
        $reg.prop('disabled', true).empty().append('<option value="">-- Pilih kab/kota --</option>');
        $dist.prop('disabled', true).empty().append('<option value="">-- Pilih kecamatan --</option>');
        $vill.prop('disabled', true).empty().append('<option value="">-- Pilih kelurahan/desa --</option>');
        if (!pid) return;
        $.getJSON('/ksp_mono/api/regencies.php', {province_id: pid})
            .done(function(res){
                if (res.success && Array.isArray(res.data)) {
                    res.data.forEach(item => $reg.append(`<option value="${item.id}">${item.nama}</option>`));
                    $reg.prop('disabled', false);
                }
            });
    });

    $reg.on('change', function(){
        const rid = $(this).val();
        $dist.prop('disabled', true).empty().append('<option value="">-- Pilih kecamatan --</option>');
        $vill.prop('disabled', true).empty().append('<option value="">-- Pilih kelurahan/desa --</option>');
        $('#koperasiPostalCode').val('').prop('disabled', true);
        if (!rid) return;
        $.getJSON('/ksp_mono/api/districts.php', {regency_id: rid})
            .done(function(res){
                if (res.success && Array.isArray(res.data)) {
                    res.data.forEach(item => $dist.append(`<option value="${item.id}">${item.nama}</option>`));
                    $dist.prop('disabled', false);
                }
            });
    });

    $dist.on('change', function(){
        const did = $(this).val();
        $vill.prop('disabled', true).empty().append('<option value="">-- Pilih kelurahan/desa --</option>');
        $('#koperasiPostalCode').val('').prop('disabled', true);
        if (!did) return;
        $.getJSON('/ksp_mono/api/villages.php', {district_id: did})
            .done(function(res){
                if (res.success && Array.isArray(res.data)) {
                    res.data.forEach(item => $vill.append(`<option value="${item.id}" data-kodepos="${item.kodepos || ''}">${item.nama}</option>`));
                    $vill.prop('disabled', false);
                }
            });
    });

    $vill.on('change', function(){
        const kode = $(this).find(':selected').data('kodepos') || '';
        $('#koperasiPostalCode').val(kode).prop('disabled', false);
    });

    $('#formRegisterKoperasi').on('submit', function(e){
        e.preventDefault();
        $('#alertKoperasi').addClass('d-none');
        const $form = $('#formRegisterKoperasi');
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
                $('#alertKoperasi').removeClass('d-none alert-danger').addClass('alert-info').text(res.message || 'Koperasi berhasil didaftarkan. Silakan lanjut registrasi admin.');
                $form[0].reset();
            } else {
                $('#alertKoperasi').removeClass('d-none alert-info').addClass('alert-danger').text(res.message || 'Gagal menyimpan data');
            }
        }).fail(function(){
            $('#alertKoperasi').removeClass('d-none alert-info').addClass('alert-danger').text('Terjadi kesalahan koneksi.');
        }).always(function(){
            $btn.prop('disabled', false).html(original);
        });
    });
});
</script>
</body>
</html>
