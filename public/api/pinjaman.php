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
        $stmt = $db->query('SELECT p.id, p.anggota_id, a.nomor_anggota, p.amount, p.term_months, p.status, p.created_at FROM pinjaman p JOIN anggota a ON a.id = p.anggota_id ORDER BY p.id DESC LIMIT 50');
        echo json_encode(['success' => true, 'data' => $stmt->fetchAll()]);
    } elseif ($action === 'create' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        $anggota_id = (int)($_POST['anggota_id'] ?? 0);
        $amount = (float)($_POST['amount'] ?? 0);
        $term_months = (int)($_POST['term_months'] ?? 1);
        $interest_rate = (float)($_POST['interest_rate'] ?? 0);
        if ($anggota_id <= 0 || $amount <= 0 || $term_months <= 0) {
            throw new Exception('anggota_id, amount, term_months wajib dan valid');
        }
        $stmt = $db->prepare('INSERT INTO pinjaman (anggota_id, amount, interest_rate, term_months, status, created_at) VALUES (:aid, :amt, :ir, :tm, \'pending\', NOW())');
        $stmt->execute([':aid' => $anggota_id, ':amt' => $amount, ':ir' => $interest_rate, ':tm' => $term_months]);
        echo json_encode(['success' => true]);
    } elseif ($action === 'update_status' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        $id = (int)($_POST['id'] ?? 0);
        $status = $_POST['status'] ?? '';
        $allowed = ['pending','approved','active','paid','rejected'];
        if ($id <= 0 || !in_array($status, $allowed, true)) {
            throw new Exception('id dan status wajib/valid');
        }
        $stmt = $db->prepare('UPDATE pinjaman SET status = :st WHERE id = :id');
        $stmt->execute([':st' => $status, ':id' => $id]);
        echo json_encode(['success' => true]);
    } elseif ($action === 'delete' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        $id = (int)($_POST['id'] ?? 0);
        if ($id <= 0) {
            throw new Exception('ID tidak valid');
        }
        $stmt = $db->prepare('DELETE FROM pinjaman WHERE id = :id');
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
    date('Y-m-d H:i:s') . " | pinjaman | {$duration}ms\n", 
    FILE_APPEND
);
