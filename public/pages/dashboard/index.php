<?php
require_once __DIR__ . '/../../../app/bootstrap.php';
$auth = new Auth();
if (!$auth->check()) {
    header('Location: /ksp_mono/public/login.php');
    exit;
}
$user = $auth->user() ?: [];
?>

<div class="container py-4">
    <div class="row g-3">
        <!-- Panel Profil Pengguna -->
        <div class="col-lg-3 col-md-4">
            <div class="card shadow-sm h-100">
                <div class="card-body d-flex flex-column">
                    <!-- Header Profil dengan Ikon -->
                    <div class="text-center mb-4">
                        <div class="position-relative d-inline-block mb-3">
                            <div class="bg-primary bg-opacity-10 rounded-circle p-3">
                                <i class="bi bi-person-circle fs-1 text-primary"></i>
                            </div>
                            <span class="position-absolute bottom-0 end-0 bg-primary text-white rounded-circle p-1">
                                <i class="bi bi-check-lg"></i>
                            </span>
                        </div>
                        <h4 class="h5 mb-1">ksp_mono</h4>
                        <p class="text-muted small">Sistem Informasi Koperasi</p>
                    </div>
                    
                    <!-- Informasi Pengguna -->
                    <div class="mb-4">
                        <div class="d-flex align-items-center mb-2">
                            <div class="bg-light rounded p-2 me-2">
                                <i class="bi bi-person-fill text-primary"></i>
                            </div>
                            <div>
                                <small class="text-muted d-block">Nama Pengguna</small>
                                <div class="fw-medium"><?php echo isset($user['username']) ? htmlspecialchars($user['username']) : 'Guest'; ?></div>
                            </div>
                        </div>
                        
                        <div class="d-flex align-items-center mb-2">
                            <div class="bg-light rounded p-2 me-2">
                                <i class="bi bi-shield-lock-fill text-primary"></i>
                            </div>
                            <div>
                                <small class="text-muted d-block">Hak Akses</small>
                                <div class="fw-medium"><?php echo isset($user['role']) ? ucfirst(htmlspecialchars($user['role'])) : 'Tamu'; ?></div>
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
                    
                    <!-- Tombol Aksi -->
                    <div class="mt-auto">
                        <a href="/ksp_mono/public/logout.php" class="btn btn-outline-danger w-100">
                            <i class="bi bi-box-arrow-right me-2"></i>Keluar
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Panel Aktivitas Utama -->
        <div class="col-lg-9 col-md-8">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <h5 class="card-title mb-0">Aktivitas Utama</h5>
                            <p class="text-muted small mb-0">Kelola data dan fitur aplikasi</p>
                        </div>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                <i class="bi bi-funnel me-1"></i> Filter
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item active" href="#">Semua</a></li>
                                <li><a class="dropdown-item" href="#">Favorit</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="#">Sering Digunakan</a></li>
                            </ul>
                        </div>
                    </div>

                    <div class="row g-3">
                        <!-- Kartu Anggota -->
                        <div class="col-xl-4 col-md-6">
                            <a href="?modul=anggota" class="text-decoration-none text-dark">
                                <div class="card h-100 border-0 shadow-sm hover-lift">
                                    <div class="card-body p-4 text-center">
                                        <div class="bg-primary bg-opacity-10 rounded-3 p-3 d-inline-flex mb-3">
                                            <i class="bi bi-people-fill fs-1 text-primary"></i>
                                        </div>
                                        <h5 class="h6 mb-1">Data Anggota</h5>
                                        <p class="text-muted small mb-0">Kelola data anggota koperasi</p>
                                    </div>
                                    <div class="card-footer bg-transparent border-top-0 pt-0 text-center">
                                        <span class="badge bg-primary bg-opacity-10 text-primary">
                                            <i class="bi bi-arrow-right-short"></i> Masuk
                                        </span>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <!-- Kartu Simpanan -->
                        <div class="col-xl-4 col-md-6">
                            <a href="?modul=simpanan" class="text-decoration-none text-dark">
                                <div class="card h-100 border-0 shadow-sm hover-lift">
                                    <div class="card-body p-4 text-center">
                                        <div class="bg-success bg-opacity-10 rounded-3 p-3 d-inline-flex mb-3">
                                            <i class="bi bi-wallet2 fs-1 text-success"></i>
                                        </div>
                                        <h5 class="h6 mb-1">Simpanan</h5>
                                        <p class="text-muted small mb-0">Kelola simpanan anggota koperasi</p>
                                    </div>
                                    <div class="card-footer bg-transparent border-top-0 pt-0 text-center">
                                        <span class="badge bg-success bg-opacity-10 text-success">
                                            <i class="bi bi-arrow-right-short"></i> Masuk
                                        </span>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <!-- Kartu Pinjaman -->
                        <div class="col-xl-4 col-md-6">
                            <a href="?modul=pinjaman" class="text-decoration-none text-dark">
                                <div class="card h-100 border-0 shadow-sm hover-lift">
                                    <div class="card-body p-4 text-center">
                                        <div class="bg-warning bg-opacity-10 rounded-3 p-3 d-inline-flex mb-3">
                                            <i class="bi bi-cash-coin fs-1 text-warning"></i>
                                        </div>
                                        <h5 class="h6 mb-1">Pinjaman</h5>
                                        <p class="text-muted small mb-0">Kelola pinjaman anggota</p>
                                    </div>
                                    <div class="card-footer bg-transparent border-top-0 pt-0 text-center">
                                        <span class="badge bg-warning bg-opacity-10 text-warning">
                                            <i class="bi bi-arrow-right-short"></i> Masuk
                                        </span>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <!-- Kartu Laporan -->
                        <div class="col-xl-4 col-md-6">
                            <a href="?modul=laporan" class="text-decoration-none text-dark">
                                <div class="card h-100 border-0 shadow-sm hover-lift">
                                    <div class="card-body p-4 text-center">
                                        <div class="bg-info bg-opacity-10 rounded-3 p-3 d-inline-flex mb-3">
                                            <i class="bi bi-file-earmark-bar-graph fs-1 text-info"></i>
                                        </div>
                                        <h5 class="h6 mb-1">Laporan</h5>
                                        <p class="text-muted small mb-0">Lihat laporan keuangan</p>
                                    </div>
                                    <div class="card-footer bg-transparent border-top-0 pt-0 text-center">
                                        <span class="badge bg-info bg-opacity-10 text-info">
                                            <i class="bi bi-arrow-right-short"></i> Masuk
                                        </span>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <!-- Kartu Pengaturan -->
                        <div class="col-xl-4 col-md-6">
                            <a href="?modul=pengaturan" class="text-decoration-none text-dark">
                                <div class="card h-100 border-0 shadow-sm hover-lift">
                                    <div class="card-body p-4 text-center">
                                        <div class="bg-secondary bg-opacity-10 rounded-3 p-3 d-inline-flex mb-3">
                                            <i class="bi bi-gear fs-1 text-secondary"></i>
                                        </div>
                                        <h5 class="h6 mb-1">Pengaturan</h5>
                                        <p class="text-muted small mb-0">Pengaturan sistem</p>
                                    </div>
                                    <div class="card-footer bg-transparent border-top-0 pt-0 text-center">
                                        <span class="badge bg-secondary bg-opacity-10 text-secondary">
                                            <i class="bi bi-arrow-right-short"></i> Masuk
                                        </span>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <!-- Kartu Bantuan -->
                        <div class="col-xl-4 col-md-6">
                            <a href="#" class="text-decoration-none text-dark" data-bs-toggle="modal" data-bs-target="#bantuanModal">
                                <div class="card h-100 border-0 shadow-sm hover-lift">
                                    <div class="card-body p-4 text-center">
                                        <div class="bg-purple bg-opacity-10 rounded-3 p-3 d-inline-flex mb-3">
                                            <i class="bi bi-question-circle fs-1 text-purple"></i>
                                        </div>
                                        <h5 class="h6 mb-1">Bantuan</h5>
                                        <p class="text-muted small mb-0">Panduan penggunaan</p>
                                    </div>
                                    <div class="card-footer bg-transparent border-top-0 pt-0 text-center">
                                        <span class="badge bg-purple bg-opacity-10 text-purple">
                                            <i class="bi bi-arrow-right-short"></i> Buka
                                        </span>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Aktivitas Terkini</h5>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Waktu</th>
                                    <th>Aktivitas</th>
                                    <th>Keterangan</th>
                                    <th>Oleh</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="text-nowrap"><?php echo date('d M Y H:i'); ?></td>
                                    <td>Login</td>
                                    <td>Anda berhasil login ke sistem</td>
                                    <td class="text-nowrap"><?php echo isset($user['username']) ? htmlspecialchars($user['username']) : 'System'; ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
