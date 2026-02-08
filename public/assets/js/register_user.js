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
