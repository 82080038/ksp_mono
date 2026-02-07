<?php
require_once __DIR__ . '/../app/bootstrap.php';
require_once __DIR__ . '/../app/helpers.php';

// Test cases for Badan Hukum validation in registration
class RegistrationTest {
    private $db;
    
    public function __construct() {
        $this->db = Database::conn();
    }
    
    public function runTests() {
        try {
            $this->db->beginTransaction();
            
            $_SERVER['REQUEST_METHOD'] = 'POST';
            $_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
            
            $this->testValidRegistrationWithBadanHukum();
            $this->testInvalidBadanHukumNumber();
            $this->testRegistrationWithoutBadanHukum();
            $this->testStatusFieldVisibility();
            $this->testBelumTerdaftarHandling();
            
            $this->db->rollBack(); // Cleanup after tests
        } catch (Exception $e) {
            $this->db->rollBack();
            echo "Test error: " . $e->getMessage();
        }
    }
    
    private function setupTestData() {
        $uniqueId = substr(uniqid(), 0, 8);
        return [
            'province_id' => '1',
            'regency_id' => '1',
            'district_id' => '1',
            'village_id' => '1',
            'alamat_lengkap' => 'Jl. Test Alamat Lengkap 123',
            'jenis_koperasi' => 'KSP',
            'nama_koperasi' => 'Test Koperasi ' . $uniqueId,
            'kontak' => '6281234567890',
            'npwp' => '',
            'tanggal_pendirian' => '01-01-2025',
            'modal_pokok' => '1.000.000',
            'admin_nama' => 'Test Admin',
            'admin_hp' => '6281234567890',
            'admin_username' => 'test_' . $uniqueId,
            'admin_password' => 'Test123!',
            'admin_password_confirm' => 'Test123!'
        ];
    }
    
    private function testValidRegistrationWithBadanHukum() {
        $db = Database::conn();
        try {
            $db->beginTransaction();
            
            $_POST = $this->setupTestData();
            $_POST['badan_hukum'] = '123456789012';
            
            ob_start();
            include __DIR__ . '/../public/register_koperasi_process.php';
            $output = json_decode(ob_get_clean(), true);
            
            if ($output['success'] === true) {
                echo "[PASS] Valid registration with Badan Hukum\n";
                
                // Verify role assignment
                $user_id = $db->lastInsertId();
                $stmt = $db->prepare('SELECT r.name FROM pengguna_peran pr JOIN peran_jenis r ON pr.peran_jenis_id = r.id WHERE pr.pengguna_id = ?');
                $stmt->execute([$user_id]);
                $role = $stmt->fetchColumn();
                
                if ($role === 'admin') {
                    echo "[PASS] Admin role assigned correctly\n";
                } else {
                    echo "[FAIL] Admin role not assigned (got: $role)\n";
                }
            } else {
                echo "[FAIL] Valid registration with Badan Hukum: ".$output['message']."\n";
            }
        } finally {
            $db->rollBack();
        }
    }
    
    private function testInvalidBadanHukumNumber() {
        $this->db->exec('SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0');
        $this->db->exec('SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0');
        
        $_POST = $this->setupTestData();
        $_POST['badan_hukum'] = '12345678901';
        
        ob_start();
        include __DIR__ . '/../public/register_koperasi_process.php';
        $output = json_decode(ob_get_clean(), true);
        
        if ($output['success'] === false && strpos($output['message'], '12 digit') !== false) {
            echo "[PASS] Invalid Badan Hukum rejected\n";
        } else {
            echo "[FAIL] Invalid Badan Hukum should be rejected\n";
        }
    }
    
    private function testRegistrationWithoutBadanHukum() {
        $this->db->exec('SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0');
        $this->db->exec('SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0');
        
        $_POST = $this->setupTestData();
        $_POST['badan_hukum'] = '';
        
        ob_start();
        include __DIR__ . '/../public/register_koperasi_process.php';
        $output = json_decode(ob_get_clean(), true);
        
        if ($output['success'] === true) {
            echo "[PASS] Registration without Badan Hukum\n";
        } else {
            echo "[FAIL] Registration without Badan Hukum should succeed\n";
        }
    }
    
    private function testStatusFieldVisibility() {
        $this->db->exec('SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0');
        $this->db->exec('SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0');
        
        // Simulate status change to 'terdaftar'
        $_POST = $this->setupTestData();
        $_POST['status_badan_hukum'] = 'terdaftar';
        $_POST['nomor_badan_hukum'] = '123456789012';
        
        ob_start();
        include __DIR__ . '/../public/register_koperasi_process.php';
        $output = json_decode(ob_get_clean(), true);
        
        if ($output['success'] === true) {
            echo "[PASS] Status 'terdaftar' shows number field and accepts valid input\n";
        } else {
            echo "[FAIL] Status 'terdaftar' should accept valid number\n";
        }
    }
    
    private function testBelumTerdaftarHandling() {
        $this->db->exec('SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0');
        $this->db->exec('SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0');
        
        // Simulate status 'belum_terdaftar' with empty number
        $_POST = $this->setupTestData();
        $_POST['status_badan_hukum'] = 'belum_terdaftar';
        $_POST['nomor_badan_hukum'] = '';
        
        ob_start();
        include __DIR__ . '/../public/register_koperasi_process.php';
        $output = json_decode(ob_get_clean(), true);
        
        if ($output['success'] === true) {
            echo "[PASS] Status 'belum_terdaftar' accepts empty number\n";
        } else {
            echo "[FAIL] Status 'belum_terdaftar' should accept empty number\n";
        }
    }
}

// Run tests
$test = new RegistrationTest();
$test->runTests();
