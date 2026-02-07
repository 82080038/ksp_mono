// Simple Validation Test Runner
const helpers = require('../app/helpers.php');

function runTests() {
  console.log('Running Validation Tests...');
  
  // NPWP Tests
  console.log('\nNPWP Validation:');
  testNPWP('12.345.678.9-012.345', true, 'Standard format');
  testNPWP('123456789012345', true, '15-digit number');
  testNPWP('12.345.678.9', false, 'Incomplete format');
  testNPWP('123456789', false, 'Short number');
  
  // Badan Hukum Tests
  console.log('\nBadan Hukum Validation:');
  testBadanHukum('123456789012', true, '12-digit number');
  testBadanHukum('12345678901', false, '11-digit number');
  testBadanHukum('1234567890123', false, '13-digit number');
}

function testNPWP(input, expected, description) {
  const result = helpers.validate_npwp(input);
  const status = result.valid === expected ? 'PASS' : 'FAIL';
  console.log(`[${status}] ${description}: ${input}`);
}

function testBadanHukum(input, expected, description) {
  const result = helpers.validate_badan_hukum_koperasi(input);
  const status = result === expected ? 'PASS' : 'FAIL';
  console.log(`[${status}] ${description}: ${input}`);
}

runTests();
