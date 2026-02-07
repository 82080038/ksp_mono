const http = require('http');

const TEST_ENDPOINT = 'http://localhost/ksp_mono/tests/ValidationTestEndpoint.php';

async function testValidation(action, input, expected, description, status = null) {
  const url = `${TEST_ENDPOINT}?action=${action}&input=${encodeURIComponent(input)}${status ? `&status=${status}` : ''}`;
  
  return new Promise((resolve) => {
    http.get(url, (res) => {
      let data = '';
      res.on('data', (chunk) => data += chunk);
      res.on('end', () => {
        try {
          const result = JSON.parse(data);
          const passed = result.valid === expected;
          console.log(`[${passed ? 'PASS' : 'FAIL'}] ${description}: ${input}`);
          if (!passed) console.log(`  Expected: ${expected}, Got: ${result.valid}`);
          resolve(passed);
        } catch (e) {
          console.error('Test error:', e);
          resolve(false);
        }
      });
    }).on('error', (e) => {
      console.error('Request error:', e);
      resolve(false);
    });
  });
}

async function runTests() {
  console.log('Running Validation Tests...');
  
  // Standard Tests
  await testValidation('test_npwp', '12.345.678.9-012.345', true, 'NPWP Standard format');
  await testValidation('test_npwp', '123456789012345', true, 'NPWP 15-digit number');
  await testValidation('test_npwp', '12.345.678.9', false, 'NPWP Incomplete format');
  await testValidation('test_npwp', '123456789', false, 'NPWP Short number');
  
  await testValidation('test_badan_hukum', '123456789012', true, 'Badan Hukum 12-digit');
  await testValidation('test_badan_hukum', '12345678901', false, 'Badan Hukum 11-digit');
  await testValidation('test_badan_hukum', '1234567890123', false, 'Badan Hukum 13-digit');

  // Edge Case Tests
  console.log('\nRunning Edge Case Tests...');
  await testValidation('test_npwp', '', true, 'NPWP Empty field (optional)');
  await testValidation('test_npwp', '12.345.678.9-012.345!@#', false, 'NPWP Special chars');
  await testValidation('test_npwp', '12.345.678.9-012', false, 'NPWP Partial formatting');
  await testValidation('test_npwp', '1'.repeat(100), false, 'NPWP Very long input');
  
  await testValidation('test_badan_hukum', '', true, 'Badan Hukum Empty field (optional)');
  await testValidation('test_badan_hukum', '12345678901A', false, 'Badan Hukum Non-digit chars');
  await testValidation('test_badan_hukum', '１２３４５６７８９０１２', false, 'Badan Hukum Full-width numbers');

  console.log('\nRunning Badan Hukum Validation Tests...');
  
  // Status-based validation tests
  await testValidation('test_badan_hukum', '123456789012', true, 'Valid 12-digit with terdaftar status', 'terdaftar');
  await testValidation('test_badan_hukum', '12345678901', false, 'Invalid 11-digit with terdaftar status', 'terdaftar');
  await testValidation('test_badan_hukum', '1234567890123', false, 'Invalid 13-digit with terdaftar status', 'terdaftar');
  
  // Basic format tests (no status)
  await testValidation('test_badan_hukum', '1234567890', true, 'Valid 10-digit no status');
  await testValidation('test_badan_hukum', '12345', false, 'Invalid short number no status');
  
  // Empty/optional field
  await testValidation('test_badan_hukum', '', true, 'Empty field');
}

runTests();
