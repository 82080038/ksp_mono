<div class="row">
    <div class="col-lg-8 col-md-10 mx-auto">
        <div class="card shadow-sm">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-plus-circle me-2"></i>Tambah Anggota Baru
                </h5>
            </div>
            <div class="card-body">
                <form method="post" action="?modul=anggota&action=save">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="nomor_anggota" class="form-label">Nomor Anggota</label>
                                <input type="text" name="nomor_anggota" id="nomor_anggota" class="form-control" placeholder="Masukkan nomor anggota" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="nama" class="form-label">Nama Lengkap</label>
                                <input type="text" name="nama" id="nama" class="form-control" placeholder="Masukkan nama lengkap" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" name="email" id="email" class="form-control" placeholder="Masukkan email" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="status_keanggotaan" class="form-label">Status Keanggotaan</label>
                                <select name="status_keanggotaan" id="status_keanggotaan" class="form-control" required>
                                    <option value="active">Aktif</option>
                                    <option value="inactive">Tidak Aktif</option>
                                    <option value="pending">Pending</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-flex gap-2 pt-3">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle me-1"></i>Simpan Anggota
                        </button>
                        <a href="?modul=anggota" class="btn btn-secondary">
                            <i class="bi bi-arrow-left me-1"></i>Kembali
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
waitForJqueryAndRun(function() {
    // Form validation
    $('form').on('submit', function(e) {
        // Basic validation
        const nomorAnggota = $('#nomor_anggota').val().trim();
        const nama = $('#nama').val().trim();
        const email = $('#email').val().trim();
        
        if (!nomorAnggota || !nama || !email) {
            e.preventDefault();
            alert('Semua field harus diisi');
            return false;
        }
        
        // Email validation
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email)) {
            e.preventDefault();
            alert('Format email tidak valid');
            return false;
        }
    });
});
</script>
