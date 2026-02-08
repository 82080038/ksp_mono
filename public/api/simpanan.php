<?php
// Start timing
$startTime = microtime(true);

require_once __DIR__ . '/../../app/bootstrap.php';
header('Content-Type: application/json');
$auth = new Auth();
if (!$auth->check()) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}
$action = $_GET['action'] ?? 'list';
try {
    $db = Database::conn();
    if ($action === 'list') {
        $stmt = $db->query('SELECT s.id, s.anggota_id, a.nama, s.jumlah, s.jenis, s.keterangan, s.dibuat_pada, s.diubah_pada 
                          FROM simpanan_transaksi s 
                          JOIN anggota a ON a.id = s.anggota_id 
                          ORDER BY s.id DESC LIMIT 50');
        echo json_encode(['success' => true, 'data' => $stmt->fetchAll()]);
    } elseif ($action === 'create' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        $anggota_id = (int)($_POST['anggota_id'] ?? 0);
        $jumlah = (float)($_POST['jumlah'] ?? 0);
        $jenis = $_POST['jenis'] ?? 'setoran';
        $keterangan = trim($_POST['keterangan'] ?? '');
        $tanggal = $_POST['tanggal'] ?? date('Y-m-d H:i:s');
        if ($anggota_id <= 0 || $jumlah <= 0 || !in_array($jenis, ['setoran', 'penarikan'], true)) {
            throw new Exception('anggota_id, jumlah, jenis wajib dan valid');
        }
        $stmt = $db->prepare('INSERT INTO simpanan_transaksi (anggota_id, jumlah, jenis, keterangan, dibuat_pada, diubah_pada) 
                             VALUES (:aid, :jml, :jns, :ket, :tanggal, :tanggal)');
        $stmt->execute([
            ':aid' => $anggota_id, 
            ':jml' => $jumlah, 
            ':jns' => $jenis, 
            ':ket' => $keterangan,
            ':tanggal' => $tanggal
        ]);
        echo json_encode(['success' => true]);
    } elseif ($action === 'get' && isset($_GET['id'])) {
        $stmt = $db->prepare('SELECT * FROM simpanan_transaksi WHERE id = ?');
        $stmt->execute([$_GET['id']]);
        $data = $stmt->fetch();
        echo json_encode(['success' => true, 'data' => $data]);
    } elseif ($action === 'update' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        $id = (int)($_POST['id'] ?? 0);
        $anggota_id = (int)($_POST['anggota_id'] ?? 0);
        $jumlah = (float)($_POST['jumlah'] ?? 0);
        $jenis = $_POST['jenis'] ?? 'setoran';
        $keterangan = trim($_POST['keterangan'] ?? '');
        $tanggal = $_POST['tanggal'] ?? date('Y-m-d H:i:s');
        if ($id <= 0 || $anggota_id <= 0 || $jumlah <= 0 || !in_array($jenis, ['setoran', 'penarikan'], true)) {
            throw new Exception('Data tidak valid');
        }
        $stmt = $db->prepare('UPDATE simpanan_transaksi SET anggota_id = ?, jumlah = ?, jenis = ?, keterangan = ?, dibuat_pada = ? WHERE id = ?');
        $stmt->execute([$anggota_id, $jumlah, $jenis, $keterangan, $tanggal, $id]);
        echo json_encode(['success' => true]);
    } elseif ($action === 'delete' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        $id = (int)($_POST['id'] ?? 0);
        if ($id <= 0) {
            throw new Exception('ID tidak valid');
        }
        $stmt = $db->prepare('DELETE FROM simpanan_transaksi WHERE id = :id');
        $stmt->execute([':id' => $id]);
        echo json_encode(['success' => true]);
    } else {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Aksi tidak dikenal']);
    }
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage() ?: 'Kesalahan server']);
}

// Ensure logs directory exists
if (!file_exists('/var/www/html/ksp_mono/logs')) {
    mkdir('/var/www/html/ksp_mono/logs', 0755, true);
}

// Log performance at the end
$duration = round((microtime(true) - $startTime) * 1000, 2);
file_put_contents(
    '/var/www/html/ksp_mono/logs/form_performance.log', 
    date('Y-m-d H:i:s') . " | simpanan | {$duration}ms\n", 
    FILE_APPEND
);
