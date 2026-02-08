<?php
// List koperasi (untuk dropdown registrasi user)
header('Content-Type: application/json');
require_once __DIR__ . '/../../app/bootstrap.php';

try {
    // Main database connection
    $pdo = Database::conn();
    
    // Get koperasi list - using correct column name 'nama_koperasi'
    $stmt = $pdo->query("SELECT id, nama_koperasi AS nama, kecamatan_id AS district_id FROM koperasi_tenant ORDER BY nama_koperasi ASC");
    $rows = $stmt->fetchAll();
    
    // Get kecamatan names (using correct table name 'kecamatan')
    $cfgAlamat = app_config('alamat_db');
    try {
        $pdoAlamat = new PDO(
            sprintf('mysql:host=%s;dbname=%s;charset=%s', 
                $cfgAlamat['host'], 
                $cfgAlamat['name'], 
                $cfgAlamat['charset']),
            $cfgAlamat['user'],
            $cfgAlamat['pass'],
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]
        );
        
        $districtIds = array_column($rows, 'district_id');
        if (!empty($districtIds)) {
            $placeholders = implode(',', array_fill(0, count($districtIds), '?'));
            $stmtKec = $pdoAlamat->prepare("SELECT id, name AS nama FROM kecamatan WHERE id IN ($placeholders)");
            $stmtKec->execute($districtIds);
            $districts = $stmtKec->fetchAll(PDO::FETCH_KEY_PAIR);
            
            // Merge kecamatan names
            foreach ($rows as &$r) {
                $r['kecamatan_nama'] = $districts[$r['district_id']] ?? 'Unknown';
            }
        }
    } catch (PDOException $e) {
        // Log but don't fail if alamat_db is unavailable
        error_log('Alamat DB connection failed: ' . $e->getMessage());
        foreach ($rows as &$r) {
            $r['kecamatan_nama'] = 'Unknown';
        }
    }
    
    echo json_encode(['success' => true, 'data' => $rows]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Database error',
        'error' => $e->getMessage()
    ]);
}
