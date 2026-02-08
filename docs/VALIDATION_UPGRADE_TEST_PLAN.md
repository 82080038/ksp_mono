# Validation Rules Upgrade Test Plan

## Test Cases
1. **Username Validation**:
   - 3 chars (should fail)
   - 4 chars (should pass)
   - 20 chars (should pass)
   - 21 chars (should fail)
   - Special chars (should fail)
   - Existing username (should fail)

2. **Password Validation**:
   - 7 chars (should fail)
   - 8 chars (should pass)
   - 11 chars (should fail)
   - 12 chars (lowercase only - should fail)
   - 12 chars (uppercase only - should fail)
   - 12 chars (digits only - should fail)
   - 12 chars (mixed case + digits - should pass)
   - Mixed case (required)
   - Digits (required)
   - Special chars (optional)
   - Common passwords (should fail)

3. **Security Features**:
   - Multiple failed attempts (trigger lockout)
   - CSRF token verification
   - Session timeout

## Environments
- Development (current rules)
- Staging (new rules)
- Production (after verification)
