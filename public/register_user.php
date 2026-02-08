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
<script src="/ksp_mono/public/assets/js/register_user.js"></script>
</body>
</html>
