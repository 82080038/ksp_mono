<?php
require_once __DIR__ . '/../app/bootstrap.php';

class RoleAssignmentTest {
    private $db;
    
    public function __construct() {
        $this->db = Database::conn();
    }
    
    public function runTests() {
        $this->testAdminRoleAssignment();
    }
    
    private function testAdminRoleAssignment() {
        try {
            $this->db->beginTransaction();
            
            // Create test user with ALL required fields
            $stmt = $this->db->prepare('INSERT INTO pengguna (username, sandi_hash, sumber_pengguna_id, status) VALUES (?, ?, ?, ?)');
            $username = 'testuser_' . uniqid();
            $stmt->execute([
                $username,
                password_hash('test123', PASSWORD_DEFAULT),
                1, // sumber_pengguna_id
                'active'
            ]);
            $user_id = $this->db->lastInsertId();
            
            // Assign admin role
            $stmt = $this->db->prepare('INSERT INTO pengguna_peran (pengguna_id, peran_jenis_id) VALUES (?, 2)');
            $stmt->execute([$user_id]);
            
            // Verify role assignment
            $stmt = $this->db->prepare('SELECT r.name FROM pengguna_peran pr JOIN peran_jenis r ON pr.peran_jenis_id = r.id WHERE pr.pengguna_id = ?');
            $stmt->execute([$user_id]);
            $role = $stmt->fetchColumn();
            
            if ($role === 'admin') {
                echo "[PASS] Admin role assigned correctly\n";
            } else {
                echo "[FAIL] Expected admin role, got: $role\n";
            }
        } finally {
            $this->db->rollBack();
        }
    }
}

$test = new RoleAssignmentTest();
$test->runTests();
