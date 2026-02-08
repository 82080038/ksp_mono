<?php
// Profil page - bootstrap and helpers already loaded in index.php
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Profil Pengguna</h1>
</div>

<div class="row">
    <div class="col-lg-8 col-md-10 mx-auto">
        <div class="card shadow-sm">
            <div class="card-body d-flex flex-column">
                <!-- Header Profil dengan Ikon -->
                <div class="text-center mb-4">
                    <div class="position-relative d-inline-block mb-3">
                        <img class="img-profile rounded-circle" src="https://ui-avatars.com/api/?name=<?php echo urlencode($_SESSION['user']['person']['nama_lengkap'] ?? $_SESSION['user']['username'] ?? 'User'); ?>&background=fff&color=4e73df" width="80" height="80" alt="avatar">
                        <span class="position-absolute bottom-0 end-0 bg-primary text-white rounded-circle p-1">
                            <i class="bi bi-check-lg"></i>
                        </span>
                    </div>
                    <h4 class="h5 mb-1"><?php echo htmlspecialchars($_SESSION['cooperatives'][0]['nama_koperasi'] ?? 'Koperasi'); ?></h4>
                    <p class="text-muted small">Sistem Informasi Koperasi Simpan Pinjam</p>
                </div>
                
                <!-- Informasi Pengguna -->
                <div class="mb-4">
                    <div class="d-flex align-items-center mb-2">
                        <div class="bg-light rounded p-2 me-2">
                            <i class="bi bi-person-fill text-primary"></i>
                        </div>
                        <div>
                            <small class="text-muted d-block">Nama Lengkap</small>
                            <div class="fw-medium"><?php echo htmlspecialchars(format_name_title_case($_SESSION['user']['person']['nama_lengkap'] ?? 'N/A')); ?></div>
                        </div>
                    </div>
                    
                    <div class="d-flex align-items-center mb-2">
                        <div class="bg-light rounded p-2 me-2">
                            <i class="bi bi-at text-primary"></i>
                        </div>
                        <div>
                            <small class="text-muted d-block">Username</small>
                            <div class="fw-medium"><?php echo htmlspecialchars($_SESSION['user']['username'] ?? 'N/A'); ?></div>
                        </div>
                    </div>
                    
                    <div class="d-flex align-items-center mb-2">
                        <div class="bg-light rounded p-2 me-2">
                            <i class="bi bi-shield-lock-fill text-primary"></i>
                        </div>
                        <div>
                            <small class="text-muted d-block">Hak Akses</small>
                            <div class="fw-medium"><?php echo htmlspecialchars(ucfirst($_SESSION['user']['role'] ?? 'guest')); ?></div>
                        </div>
                    </div>
                    
                    <div class="d-flex align-items-center">
                        <div class="bg-light rounded p-2 me-2">
                            <i class="bi bi-clock-history text-primary"></i>
                        </div>
                        <div>
                            <small class="text-muted d-block">Terakhir Login</small>
                            <div class="fw-medium"><?php echo date('d M Y, H:i'); ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
waitForJqueryAndRun(function() {
    // Any additional JS for profile page
});
</script>
