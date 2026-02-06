<?php
// Set header untuk response JSON
header('Content-Type: application/json');

// Dapatkan parameter action dari URL
$action = isset($_GET['action']) ? $_GET['action'] : 'list';

// Routing berdasarkan action
switch ($action) {
    case 'list':
        // Tampilkan daftar anggota
        include 'list.php';
        break;
    case 'tambah':
        // Tampilkan form tambah anggota
        include 'form_tambah.php';
        break;
    case 'edit':
        // Tampilkan form edit anggota
        include 'form_edit.php';
        break;
    default:
        // Tampilkan halaman 404
        header('HTTP/1.0 404 Not Found');
        echo 'Halaman tidak ditemukan';
        break;
}
?>
