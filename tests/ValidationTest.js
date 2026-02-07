const { validate_npwp, validate_badan_hukum_koperasi } = require('../app/helpers.php');

// Validation Test Cases for KSP Mono

// NPWP Validation Tests
describe('NPWP Validation', () => {
  test('Valid NPWP standard format', () => {
    const result = validate_npwp('12.345.678.9-012.345');
    expect(result.valid).toBe(true);
  });

  test('Valid NPWP clean 15-digit number', () => {
    const result = validate_npwp('123456789012345');
    expect(result.valid).toBe(true);
  });

  test('Invalid NPWP incomplete format', () => {
    const result = validate_npwp('12.345.678.9');
    expect(result.valid).toBe(false);
  });

  test('Invalid NPWP short number', () => {
    const result = validate_npwp('123456789');
    expect(result.valid).toBe(false);
  });
});

// Badan Hukum Validation Tests
describe('Badan Hukum Validation', () => {
  test('Valid Badan Hukum 12-digit number', () => {
    const result = validate_badan_hukum_koperasi('123456789012');
    expect(result).toBe(true);
  });

  test('Invalid Badan Hukum 11-digit number', () => {
    const result = validate_badan_hukum_koperasi('12345678901');
    expect(result).toBe(false);
  });

  test('Invalid Badan Hukum 13-digit number', () => {
    const result = validate_badan_hukum_koperasi('1234567890123');
    expect(result).toBe(false);
  });
});
