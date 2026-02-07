<?php
require_once __DIR__ . '/../app/bootstrap.php';
require_once __DIR__ . '/../app/helpers.php';

// Mock permission function if not exists
if (!function_exists('has_permission')) {
    function has_permission($permission) {
        return in_array($permission, $_SESSION['permissions'] ?? []);
    }
}

class DashboardCoopTest {
    private $db;
    
    public function __construct() {
        $this->db = Database::conn();
        $_SESSION = [];
    }
    
    public function runTests() {
        $this->testMenuVisibility();
        $this->testFormSubmission();
    }
    
    private function testMenuVisibility() {
        // Simulate admin user with permissions
        $_SESSION['permissions'] = ['manage_cooperative'];
        
        ob_start();
        include __DIR__ . '/mocks/navbar.php';
        $output = ob_get_clean();
        
        if (strpos($output, 'Detail Koperasi') !== false) {
            echo "[PASS] Menu item shows for authorized users\n";
        } else {
            echo "[FAIL] Menu item should show for authorized users\n";
        }
    }
    
    private function testFormSubmission() {
        // Simulate form submission
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST = [
            'status_badan_hukum' => 'terdaftar',
            'nomor_badan_hukum' => '123456789012',
            'kontak' => '6281234567890'
        ];
        
        ob_start();
        include __DIR__ . '/mocks/update_coop_details.php';
        $output = json_decode(ob_get_clean(), true);
        
        if ($output['success'] === true) {
            echo "[PASS] Valid form submission accepted\n";
        } else {
            echo "[FAIL] Valid submission should be accepted: ".($output['message'] ?? '')."\n";
        }
    }
}

$test = new DashboardCoopTest();
$test->runTests();
