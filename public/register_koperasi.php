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
        <div class="col-lg-6">
            <div class="card shadow-sm border-0">
                <div class="card-header text-center">
                    <i class="bi bi-building display-4 text-white mb-2"></i>
                    <h5 class="mb-1">Registrasi Koperasi</h5>
                    <small class="text-white-50">Lengkapi data dasar koperasi Anda</small>
                </div>
                <div class="card-body p-4">
                    <h6 class="text-primary mb-3"><i class="bi bi-geo-alt-fill"></i> Informasi Lokasi</h6>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="form-floating">
                                <select class="form-select" name="province_id" id="koperasiProvSelect" required>
                                    <option value="">-- Pilih provinsi --</option>
                                </select>
                                <label for="koperasiProvSelect"><i class="bi bi-geo-alt"></i> Provinsi</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <select class="form-select" name="regency_id" id="koperasiRegencySelect" required disabled>
                                    <option value="">-- Pilih kab/kota --</option>
                                </select>
                                <label for="koperasiRegencySelect"><i class="bi bi-building"></i> Kabupaten/Kota</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <select class="form-select" name="district_id" id="koperasiDistrictSelect" required disabled>
                                    <option value="">-- Pilih kecamatan --</option>
                                </select>
                                <label for="koperasiDistrictSelect"><i class="bi bi-house"></i> Kecamatan</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <select class="form-select" name="village_id" id="koperasiVillageSelect" required disabled>
                                    <option value="">-- Pilih kelurahan/desa --</option>
                                </select>
                                <label for="koperasiVillageSelect"><i class="bi bi-house-door"></i> Kelurahan/Desa</label>
                            </div>
                        </div>
                        <div class="d-flex flex-wrap gap-3 mt-3 align-items-start">
                            <div class="flex-fill" style="min-width:240px;">
                                <div class="form-floating mb-3">
                                    <textarea class="form-control" name="alamat_lengkap" rows="2" required placeholder="Alamat lengkap"></textarea>
                                    <label><i class="bi bi-map"></i> Alamat Lengkap</label>
                                </div>
                            </div>
                            <div style="width:90px;">
                                <div class="form-floating">
                                    <input type="text" class="form-control" name="postal_code" id="koperasiPostalCode" readonly disabled placeholder="Kode Pos">
                                    <label for="koperasiPostalCode"><i class="bi bi-mailbox"></i> K. Pos</label>
                                </div>
                            </div>
                        </div>
                    
                    <h6 class="text-primary mb-3 mt-4"><i class="bi bi-info-circle-fill"></i> Detail Koperasi</h6>
                   
                 
                   
                    <div class="form-floating mb-3">
                        <select class="form-select" name="jenis_koperasi" required>
                            <option value="">-- Pilih jenis koperasi --</option>
                            <option value="KSP">Koperasi Simpan Pinjam (KSP)</option>
                            <option value="KK">Koperasi Konsumsi</option>
                            <option value="KP">Koperasi Produksi</option>
                            <option value="KJ">Koperasi Jasa</option>
                            <option value="KSU">Koperasi Serba Usaha</option>
                        </select>
                        <label><i class="bi bi-tag"></i> Jenis Koperasi</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" name="nama_koperasi" required placeholder="Nama Koperasi">
                        <label><i class="bi bi-building"></i> Nama Koperasi</label>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" name="kontak" required placeholder="Kontak (Telp/HP)">
                                <label><i class="bi bi-telephone"></i> Kontak (Telp/HP)</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" name="npwp" placeholder="NPWP (opsional)">
                                <label><i class="bi bi-receipt"></i> NPWP (opsional)</label>
                            </div>
                        </div>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" name="badan_hukum" placeholder="Badan Hukum">
                        <label><i class="bi bi-file-text"></i> Badan Hukum</label>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="date" class="form-control" name="tanggal_pendirian">
                                <label><i class="bi bi-calendar"></i> Tanggal Pendirian</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="number" class="form-control" name="modal_pokok" step="0.01" placeholder="0.00">
                                <label><i class="bi bi-cash"></i> Modal Pokok</label>
                            </div>
                        </div>
                    </div>
                    <div class="d-grid gap-2 mt-4">
                        <button type="submit" class="btn btn-primary btn-lg"><i class="bi bi-check-circle"></i> Daftarkan Koperasi</button>
                    </div>
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

        // Validasi tambahan
        const nama = $('input[name="nama_koperasi"]').val().trim();
        const alamat = $('textarea[name="alamat_lengkap"]').val().trim();
        const kontak = $('input[name="kontak"]').val().trim();
        const npwp = $('input[name="npwp"]').val().trim();

        if (nama.length < 3) {
            $('#alertKoperasi').removeClass('d-none alert-info').addClass('alert-danger').text('Nama koperasi minimal 3 karakter.');
            return;
        }
        if (alamat.length < 10) {
            $('#alertKoperasi').removeClass('d-none alert-info').addClass('alert-danger').text('Alamat lengkap minimal 10 karakter.');
            return;
        }
        const phoneRegex = /^[0-9+\-\s()]+$/;
        if (!phoneRegex.test(kontak) || kontak.length < 8) {
            $('#alertKoperasi').removeClass('d-none alert-info').addClass('alert-danger').text('Kontak harus berupa nomor telepon yang valid.');
            return;
        }
        if (npwp && !/^[0-9]{15}$/.test(npwp)) {
            $('#alertKoperasi').removeClass('d-none alert-info').addClass('alert-danger').text('NPWP harus 15 digit angka.');
            return;
        }

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
