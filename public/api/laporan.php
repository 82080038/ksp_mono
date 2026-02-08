<?php
// Laporan API
require_once __DIR__ . '/../../app/bootstrap.php';
header('Content-Type: application/json');

$auth = new Auth();
if (!$auth->check()) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

$action = $_GET['action'] ?? 'list';
$db = Database::conn();

try {
    if ($action === 'generate' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        $date_from = $_POST['date_from'] ?? '';
        $date_to = $_POST['date_to'] ?? '';
        $report_type = $_POST['report_type'] ?? '';
        
        if (empty($date_from) || empty($date_to) || empty($report_type)) {
            throw new Exception('Parameter tidak lengkap');
        }
        
        $data = [];
        
        if ($report_type === 'simpanan') {
            $stmt = $db->prepare('SELECT s.*, a.nama FROM simpanan_transaksi s JOIN anggota a ON a.id = s.anggota_id WHERE s.dibuat_pada BETWEEN ? AND ? ORDER BY s.dibuat_pada DESC');
            $stmt->execute([$date_from . ' 00:00:00', $date_to . ' 23:59:59']);
            $data = $stmt->fetchAll();
        } elseif ($report_type === 'pinjaman') {
            $stmt = $db->prepare('SELECT p.*, u.username FROM pinjaman p LEFT JOIN pengguna u ON p.user_id = u.id WHERE p.dibuat_pada BETWEEN ? AND ? ORDER BY p.dibuat_pada DESC');
            $stmt->execute([$date_from . ' 00:00:00', $date_to . ' 23:59:59']);
            $data = $stmt->fetchAll();
        } elseif ($report_type === 'anggota') {
            $stmt = $db->prepare('SELECT a.id, a.nomor_anggota, a.status_keanggotaan, a.joined_at, o.nik, o.nama_lengkap as nama, o.alamat, o.no_telepon as no_hp FROM anggota a LEFT JOIN orang o ON a.user_id = o.pengguna_id WHERE a.joined_at BETWEEN ? AND ? ORDER BY a.joined_at DESC');
            $stmt->execute([$date_from . ' 00:00:00', $date_to . ' 23:59:59']);
            $data = $stmt->fetchAll();
        } elseif ($report_type === 'keuangan') {
            // Aggregate data
            $stmt = $db->prepare('SELECT COUNT(*) as count, SUM(jumlah) as total FROM simpanan_transaksi WHERE dibuat_pada BETWEEN ? AND ?');
            $stmt->execute([$date_from . ' 00:00:00', $date_to . ' 23:59:59']);
            $simpanan = $stmt->fetch();
            
            $stmt = $db->prepare('SELECT COUNT(*) as count, SUM(jumlah) as total FROM pinjaman WHERE dibuat_pada BETWEEN ? AND ?');
            $stmt->execute([$date_from . ' 00:00:00', $date_to . ' 23:59:59']);
            $pinjaman = $stmt->fetch();
            
            $data = [
                'total_simpanan' => $simpanan['total'] ?? 0,
                'count_simpanan' => $simpanan['count'] ?? 0,
                'total_pinjaman' => $pinjaman['total'] ?? 0,
                'count_pinjaman' => $pinjaman['count'] ?? 0
            ];
        } else {
            throw new Exception('Jenis laporan tidak valid');
        }
        
        echo json_encode(['success' => true, 'data' => $data]);
    } else {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Aksi tidak dikenal']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>
