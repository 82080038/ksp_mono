<?php
require_once __DIR__ . '/../../../app/bootstrap.php';
require_once __DIR__ . '/../../../app/helpers.php';

// Check permissions
if (!has_permission('view_members')) {
    echo '<div class="alert alert-danger">Anda tidak memiliki izin untuk mengakses halaman ini.</div>';
    exit;
}

// Dapatkan parameter action dari URL
$action = isset($_GET['action']) ? $_GET['action'] : 'list';

?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Data Anggota</h1>
</div>

<?php
if ($action === 'list') {
    include 'list.php';
} else {
    echo '<div class="alert alert-warning">Halaman tidak ditemukan</div>';
}
?>
