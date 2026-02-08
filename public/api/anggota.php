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
        $db->beginTransaction();
        try {
            // Insert orang
            $stmt = $db->prepare('INSERT INTO orang (nama_lengkap, tanggal_lahir, jenis_kelamin, alamat, no_telepon, pekerjaan_master_id, pekerjaan_pangkat_id, nik, nrp_nip) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)');
            $stmt->execute([
                $_POST['nama_lengkap'],
                $_POST['tanggal_lahir'] ?: null,
                $_POST['jenis_kelamin'] ?: null,
                $_POST['alamat'] ?: null,
                $_POST['no_telepon'] ?: null,
                $_POST['pekerjaan_id'],
                $_POST['pangkat_id'] ?: null,
                $_POST['nik'],
                $_POST['nrp_nip'] ?: null
            ]);
            $orang_id = $db->lastInsertId();

            // Insert pengguna
            $username = strtolower(str_replace(' ', '', $_POST['nama_lengkap'])) . rand(100, 999);
            $password = password_hash('password123', PASSWORD_DEFAULT);
            $stmt = $db->prepare('INSERT INTO pengguna (username, email, password, orang_id, dibuat_pada) VALUES (?, ?, ?, ?, NOW())');
            $stmt->execute([$username, $_POST['email'], $password, $orang_id]);
            $user_id = $db->lastInsertId();

            // Insert anggota
            $stmt = $db->prepare('INSERT INTO anggota (user_id, nomor_anggota, status_keanggotaan, joined_at) VALUES (?, ?, ?, NOW())');
            $stmt->execute([$user_id, $_POST['nomor_anggota'], $_POST['status_keanggotaan']]);

            $db->commit();
            echo json_encode(['success' => true]);
        } catch (Exception $e) {
            $db->rollBack();
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    } elseif ($action === 'get' && isset($_GET['id'])) {
        $stmt = $db->prepare('SELECT a.*, o.*, pm.nama as pekerjaan_nama, pp.nama as pangkat_nama FROM anggota a LEFT JOIN orang o ON a.user_id = o.pengguna_id LEFT JOIN pekerjaan_master pm ON o.pekerjaan_master_id = pm.id LEFT JOIN pekerjaan_pangkat pp ON o.pekerjaan_pangkat_id = pp.id WHERE a.id = ?');
        $stmt->execute([$_GET['id']]);
        $data = $stmt->fetch();
        echo json_encode(['success' => true, 'data' => $data]);
    } elseif ($action === 'check_pangkat' && isset($_GET['id'])) {
        $stmt = $db->prepare('SELECT COUNT(*) as count FROM pekerjaan_pangkat WHERE pekerjaan_master_id = ?');
        $stmt->execute([$_GET['id']]);
        $has = $stmt->fetch()['count'] > 0;
        $pangkat = [];
        if ($has) {
            $stmt = $db->query('SELECT id, nama FROM pekerjaan_pangkat WHERE pekerjaan_master_id = ' . $_GET['id']);
            $pangkat = $stmt->fetchAll();
        }
        echo json_encode(['has_pangkat' => $has, 'pangkat' => $pangkat]);
    } elseif ($action === 'check_unique' && isset($_GET['field']) && isset($_GET['value'])) {
        $field = $_GET['field'];
        $value = trim($_GET['value']);
        if (!$value) {
            echo json_encode(['exists' => false, 'name' => '']);
            exit;
        }
        $query = '';
        if ($field === 'email') {
            $query = 'SELECT o.nama_lengkap FROM orang o JOIN pengguna p ON o.id = p.orang_id WHERE p.email = ?';
        } elseif ($field === 'nomor_anggota') {
            $query = 'SELECT o.nama_lengkap FROM orang o JOIN pengguna p ON o.id = p.orang_id JOIN anggota a ON a.user_id = p.id WHERE a.nomor_anggota = ?';
        } elseif ($field === 'nik') {
            $query = 'SELECT nama_lengkap FROM orang WHERE nik = ?';
        } elseif ($field === 'nrp_nip') {
            $query = 'SELECT nama_lengkap FROM orang WHERE nrp_nip = ?';
        }
        if ($query) {
            $stmt = $db->prepare($query);
            $stmt->execute([$value]);
            $data = $stmt->fetch();
            echo json_encode(['exists' => !!$data, 'name' => $data ? $data['nama_lengkap'] : '']);
        } else {
            echo json_encode(['exists' => false, 'name' => '']);
        }
    } elseif ($action === 'update' && $_SERVER['REQUEST_METHOD'] === 'POST') {;
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
