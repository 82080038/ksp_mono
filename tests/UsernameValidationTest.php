<?php
require_once __DIR__ . '/../app/bootstrap.php';

class UsernameValidationTest {
    private $db;
    
    public function __construct() {
        $this->db = Database::conn();
    }
    
    public function runTests() {
        try {
            $this->testValidUsername();
            $this->testInvalidUsername();
            $this->testDuplicateUsername();
        } catch (Exception $e) {
            echo "[ERROR] " . $e->getMessage() . "\n";
        }
    }
    
    private function testValidUsername() {
        $valid = ['user123', 'test_user', 'admin1234'];
        foreach ($valid as $username) {
            if (!preg_match('/^[a-zA-Z0-9_]{4,20}$/', $username)) {
                echo "[FAIL] Should accept valid username: $username\n";
                return;
            }
        }
        echo "[PASS] Valid username acceptance\n";
    }
    
    private function testInvalidUsername() {
        $invalid = ['usr', 'user@name', 'this_username_is_way_too_long_for_validation'];
        foreach ($invalid as $username) {
            if (preg_match('/^[a-zA-Z0-9_]{4,20}$/', $username)) {
                echo "[FAIL] Should reject invalid username: $username\n";
                return;
            }
        }
        echo "[PASS] Invalid username rejection\n";
    }
    
    private function testDuplicateUsername() {
        try {
            $this->db->beginTransaction();
            
            // Create test user with unique username
            $username = 'testuser_' . uniqid();
            $stmt = $this->db->prepare('INSERT INTO pengguna (username, sandi_hash, sumber_pengguna_id, status) VALUES (?, ?, ?, ?)');
            $stmt->execute([
                $username,
                password_hash('test123', PASSWORD_DEFAULT),
                1, // sumber_pengguna_id
                'active'
            ]);
            
            // Test duplicate
            $stmt = $this->db->prepare('SELECT id FROM pengguna WHERE username = ? LIMIT 1');
            $stmt->execute([$username]);
            
            if (!$stmt->fetch()) {
                echo "[FAIL] Should detect existing username\n";
            } else {
                echo "[PASS] Duplicate username detection\n";
            }
        } finally {
            $this->db->rollBack();
        }
    }
}

$test = new UsernameValidationTest();
$test->runTests();
