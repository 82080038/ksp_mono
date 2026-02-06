<?php
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
        $stmt = $db->query('SELECT a.id, a.nomor_anggota, a.status_keanggotaan, a.joined_at, p.username FROM anggota a LEFT JOIN pengguna p ON a.user_id = p.id ORDER BY a.id DESC LIMIT 50');
        echo json_encode(['success' => true, 'data' => $stmt->fetchAll()]);
    } elseif ($action === 'create' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        $user_id = (int)($_POST['user_id'] ?? 0);
        $nomor_anggota = trim($_POST['nomor_anggota'] ?? '');
        $status_keanggotaan = $_POST['status_keanggotaan'] ?? 'active';
        if ($user_id <= 0 || $nomor_anggota === '') {
            throw new Exception('user_id dan nomor_anggota wajib diisi');
        }
        $stmt = $db->prepare('INSERT INTO anggota (user_id, nomor_anggota, status_keanggotaan, joined_at, updated_at) VALUES (:uid, :na, :sk, NOW(), NOW())');
        $stmt->execute([':uid' => $user_id, ':na' => $nomor_anggota, ':sk' => $status_keanggotaan]);
        echo json_encode(['success' => true]);
    } elseif ($action === 'delete' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        $id = (int)($_POST['id'] ?? 0);
        if ($id <= 0) {
            throw new Exception('ID tidak valid');
        }
        $stmt = $db->prepare('DELETE FROM anggota WHERE id = :id');
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
