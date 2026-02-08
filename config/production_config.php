<?php
// Production environment configuration

// Strict validation rules
define('MIN_USERNAME_LENGTH', 6);
define('MAX_USERNAME_LENGTH', 20);
define('MIN_PASSWORD_LENGTH', 12);
define('PASSWORD_COMPLEXITY', true); // Requires mixed case and digits

define('PASSWORD_HISTORY_COUNT', 3); // Remember last 3 passwords

define('ACCOUNT_LOCKOUT_ATTEMPTS', 5); // Lock after 5 failed attempts

// Security settings
define('SESSION_TIMEOUT', 1800); // 30 minutes

define('ENABLE_CSRF_PROTECTION', true);
