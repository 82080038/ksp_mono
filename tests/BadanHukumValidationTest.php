<?php
require_once __DIR__ . '/../app/helpers.php';

// Test only validation logic without database interactions
class BadanHukumValidationTest {
    public function runTests() {
        $this->testValidNumberWithStatus();
        $this->testInvalidNumberWithStatus();
        $this->testEmptyNumberWithoutStatus();
    }
    
    private function testValidNumberWithStatus() {
        $valid = validate_badan_hukum_koperasi('123456789012', 'terdaftar');
        echo $valid ? "[PASS] Valid 12-digit with status\n" : "[FAIL] Valid 12-digit should pass\n";
    }
    
    private function testInvalidNumberWithStatus() {
        $valid = validate_badan_hukum_koperasi('12345', 'terdaftar');
        echo !$valid ? "[PASS] Invalid number with status rejected\n" : "[FAIL] Invalid number should fail\n";
    }
    
    private function testEmptyNumberWithoutStatus() {
        $valid = validate_badan_hukum_koperasi('', 'belum_terdaftar');
        echo $valid ? "[PASS] Empty number without status accepted\n" : "[FAIL] Empty number should pass\n";
    }
}

$test = new BadanHukumValidationTest();
$test->runTests();
