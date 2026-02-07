<?php
/**
 * Helper functions for ksp_mono application
 * Contains formatting utilities for phone numbers, dates, and currency
 */

/**
 * Format Indonesian phone number with masking
 * @param string $phone Phone number
 * @param bool $with_mask Whether to return with mask
 * @return string Formatted phone number
 */
function format_indonesian_phone($phone, $with_mask = true) {
    // Remove all non-numeric characters
    $phone = preg_replace('/\D/', '', $phone);

    // Remove leading zero if exists and add country code if not present
    if (substr($phone, 0, 1) === '0') {
        $phone = '62' . substr($phone, 1);
    } elseif (substr($phone, 0, 2) !== '62') {
        $phone = '62' . $phone;
    }

    if ($with_mask) {
        // Apply masking: every 4 digits with -
        $formatted = '';
        $length = strlen($phone);
        for ($i = 0; $i < $length; $i++) {
            if ($i > 0 && $i % 4 === 0) {
                $formatted .= '-';
            }
            $formatted .= $phone[$i];
        }
        return $formatted;
    }

    return $phone;
}

/**
 * Remove phone number masking for database storage
 * Handles Indonesian phone number formatting (leading 0 -> 62 prefix)
 * @param string $masked_phone Masked phone number
 * @return string Clean phone number for database
 */
function unmask_phone_number($masked_phone) {
    // Remove all non-numeric characters
    $clean = preg_replace('/\D/', '', $masked_phone);

    // Handle Indonesian phone number formatting
    if (strlen($clean) > 0) {
        // If starts with 0, remove it and add 62 prefix
        if (substr($clean, 0, 1) === '0') {
            $clean = '62' . substr($clean, 1);
        }
        // If doesn't start with 62, add 62 prefix
        elseif (substr($clean, 0, 2) !== '62') {
            $clean = '62' . $clean;
        }
    }

    return $clean;
}

/**
 * Format date to Indonesian format (dd-mm-yyyy)
 * @param string $date Date string (yyyy-mm-dd or dd-mm-yyyy)
 * @return string Formatted date
 */
function format_indonesian_date($date) {
    if (empty($date)) return '';

    // If already in dd-mm-yyyy format, return as is
    if (preg_match('/^\d{2}-\d{2}-\d{4}$/', $date)) {
        return $date;
    }

    // Convert from yyyy-mm-dd to dd-mm-yyyy
    if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
        $parts = explode('-', $date);
        return sprintf('%02d-%02d-%04d', $parts[2], $parts[1], $parts[0]);
    }

    return $date;
}

/**
 * Convert Indonesian date format to database format (yyyy-mm-dd)
 * @param string $indonesian_date Date in dd-mm-yyyy format
 * @return string Date in yyyy-mm-dd format
 */
function indonesian_date_to_db($indonesian_date) {
    if (empty($indonesian_date)) return '';

    // Convert from dd-mm-yyyy to yyyy-mm-dd
    if (preg_match('/^\d{2}-\d{2}-\d{4}$/', $indonesian_date)) {
        $parts = explode('-', $indonesian_date);
        return sprintf('%04d-%02d-%02d', $parts[2], $parts[1], $parts[0]);
    }

    return $indonesian_date;
}

/**
 * Format number to Indonesian Rupiah
 * @param float $amount Amount
 * @param bool $show_decimals Whether to show decimals even if .00
 * @return string Formatted currency
 */
function format_rupiah($amount, $show_decimals = false) {
    if (!is_numeric($amount)) {
        $amount = 0;
    }

    $formatted = number_format($amount, 2, ',', '.');

    // Remove .00 if not showing decimals and amount is whole number
    if (!$show_decimals && strpos($formatted, ',00') !== false) {
        $formatted = str_replace(',00', '', $formatted);
    }

    return 'Rp ' . $formatted;
}

/**
 * Parse Rupiah formatted string to number
 * @param string $rupiah_string Rupiah formatted string
 * @return float Parsed number
 */
function parse_rupiah($rupiah_string) {
    // Remove 'Rp ', dots, and replace comma with dot
    $clean = str_replace(['Rp ', '.'], ['', ''], $rupiah_string);
    $clean = str_replace(',', '.', $clean);

    return (float) $clean;
}

/**
 * Validate Indonesian phone number format
 * @param string $phone Phone number
 * @return bool Is valid
 */
function validate_indonesian_phone($phone) {
    $clean_phone = unmask_phone_number($phone);

    // Indonesian phone numbers should be 10-13 digits after country code
    // Including country code 62, total should be 12-15 digits
    $length = strlen($clean_phone);

    return $length >= 12 && $length <= 15 && substr($clean_phone, 0, 2) === '62';
}

/**
 * Validate Indonesian date format (dd-mm-yyyy)
 * @param string $date Date string
 * @return bool Is valid
 */
function validate_indonesian_date($date) {
    if (!preg_match('/^\d{2}-\d{2}-\d{4}$/', $date)) {
        return false;
    }

    $parts = explode('-', $date);
    $day = (int) $parts[0];
    $month = (int) $parts[1];
    $year = (int) $parts[2];

    return checkdate($month, $day, $year) && $year >= 1900 && $year <= 2100;
}

/**
 * Validate Indonesian NPWP format (15 or 16 digits)
 * @param string $npwp NPWP number
 * @return bool Is valid
 */
function validate_npwp($npwp) {
    // Remove all non-numeric characters
    $clean_npwp = preg_replace('/\D/', '', $npwp);

    // NPWP should be 15 or 16 digits
    return strlen($clean_npwp) === 15 || strlen($clean_npwp) === 16;
}

/**
 * Validate cooperative business entity number (NIK format)
 * Format: XX/BH/XX/.X/XXXX or similar patterns
 * @param string $badan_hukum Business entity number
 * @return bool Is valid
 */
function validate_badan_hukum_koperasi($badan_hukum) {
    if (empty($badan_hukum)) return true; // Optional field

    // Common patterns for cooperative business entity numbers:
    // - Contains /BH/ (Badan Hukum)
    // - May have province codes, registration numbers
    return strpos($badan_hukum, '/BH/') !== false || strlen($badan_hukum) >= 10;
}

// ============================================================================
// ADDRESS DATA CACHING SYSTEM
// ============================================================================

/**
 * Get address database connection
 * @return PDO Address database connection
 */
function get_address_db_connection() {
    static $addressDb = null;

    if ($addressDb === null) {
        $config = require __DIR__ . '/../config/config.php';
        try {
            $addressDb = new PDO(
                "mysql:host={$config['alamat_db']['host']};dbname={$config['alamat_db']['name']};charset={$config['alamat_db']['charset']}",
                $config['alamat_db']['user'],
                $config['alamat_db']['pass'],
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ]
            );
        } catch (PDOException $e) {
            error_log('Address DB connection error: ' . $e->getMessage());
            throw new Exception('Failed to connect to address database');
        }
    }

    return $addressDb;
}

/**
 * Get address data versions using max_date approach (more efficient)
 * @return array Max date information for all address tables
 */
function get_address_max_dates() {
    try {
        $db = get_address_db_connection();
        $stmt = $db->query("SELECT table_name, max_date, record_count, last_checked FROM table_max_dates");
        $max_dates = [];
        while ($row = $stmt->fetch()) {
            $max_dates[$row['table_name']] = [
                'max_date' => $row['max_date'],
                'record_count' => $row['record_count'],
                'last_checked' => $row['last_checked']
            ];
        }
        return $max_dates;
    } catch (Exception $e) {
        error_log('Error getting address max dates: ' . $e->getMessage());
        return [];
    }
}

/**
 * Check if address data has changed since last check
 * @param string $type Data type
 * @param string $last_max_date Last known max date
 * @return bool Whether data has changed
 */
function has_address_data_changed($type, $last_max_date) {
    try {
        $db = get_address_db_connection();
        $stmt = $db->prepare("SELECT max_date FROM table_max_dates WHERE table_name = ?");
        $stmt->execute([$type]);
        $result = $stmt->fetch();

        if (!$result) {
            return true; // Table not found, assume changed
        }

        return $result['max_date'] !== $last_max_date;
    } catch (Exception $e) {
        error_log('Error checking address data changes: ' . $e->getMessage());
        return true; // On error, assume changed to force refresh
    }
}

/**
 * Get all provinces data
 * @return array Provinces data
 */
function get_provinces_data() {
    try {
        $db = get_address_db_connection();
        $stmt = $db->query("SELECT id, code, name FROM provinsi ORDER BY name");
        return $stmt->fetchAll();
    } catch (Exception $e) {
        error_log('Error getting provinces: ' . $e->getMessage());
        return [];
    }
}

/**
 * Get regencies by province ID
 * @param int $province_id Province ID
 * @return array Regencies data
 */
function get_regencies_data($province_id) {
    try {
        $db = get_address_db_connection();
        $stmt = $db->prepare("SELECT id, code, name, postal_code FROM kabkota WHERE province_id = ? ORDER BY name");
        $stmt->execute([$province_id]);
        return $stmt->fetchAll();
    } catch (Exception $e) {
        error_log('Error getting regencies: ' . $e->getMessage());
        return [];
    }
}

/**
 * Get districts by regency ID
 * @param int $regency_id Regency ID
 * @return array Districts data
 */
function get_districts_data($regency_id) {
    try {
        $db = get_address_db_connection();
        $stmt = $db->prepare("SELECT id, code, name FROM kecamatan WHERE regency_id = ? ORDER BY name");
        $stmt->execute([$regency_id]);
        return $stmt->fetchAll();
    } catch (Exception $e) {
        error_log('Error getting districts: ' . $e->getMessage());
        return [];
    }
}

/**
 * Get villages by district ID
 * @param int $district_id District ID
 * @return array Villages data
 */
function get_villages_data($district_id) {
    try {
        $db = get_address_db_connection();
        $stmt = $db->prepare("SELECT id, code, name, postal_code FROM kelurahan WHERE district_id = ? ORDER BY name");
        $stmt->execute([$district_id]);
        return $stmt->fetchAll();
    } catch (Exception $e) {
        error_log('Error getting villages: ' . $e->getMessage());
        return [];
    }
}

/**
 * Generate cache key for address data
 * @param string $type Data type (provinces, regencies, districts, villages)
 * @param int|null $parent_id Parent ID (for hierarchical data)
 * @return string Cache key
 */
function generate_address_cache_key($type, $parent_id = null) {
    $key = "address_{$type}";
    if ($parent_id !== null) {
        $key .= "_{$parent_id}";
    }
    return $key;
}

/**
 * Get cached address data or fetch from database using max_date tracking
 * @param string $type Data type
 * @param int|null $parent_id Parent ID
 * @param array $max_dates Current max dates
 * @return array Address data
 */
function get_cached_address_data($type, $parent_id = null, $max_dates = null) {
    if ($max_dates === null) {
        $max_dates = get_address_max_dates();
    }

    $cache_key = generate_address_cache_key($type, $parent_id);
    $max_date_key = "address_max_date_{$type}";
    $cache_time_key = "address_cache_time_{$type}";

    // Get stored data and max_date from cache
    $stored_max_date = isset($_COOKIE[$max_date_key]) ? $_COOKIE[$max_date_key] : null;
    $stored_cache_time = isset($_COOKIE[$cache_time_key]) ? (int)$_COOKIE[$cache_time_key] : 0;

    $current_max_date = isset($max_dates[$type]['max_date']) ? $max_dates[$type]['max_date'] : null;
    $current_time = time();

    // Check if cache is still valid (24 hours or max_date unchanged)
    $cache_valid = ($stored_max_date === $current_max_date) &&
                   ($current_time - $stored_cache_time < 86400); // 24 hours

    if ($cache_valid && isset($_COOKIE[$cache_key])) {
        // Return cached data
        $cached_data = json_decode($_COOKIE[$cache_key], true);
        if ($cached_data !== null) {
            return $cached_data;
        }
    }

    // Fetch fresh data from database
    $data = [];
    switch ($type) {
        case 'provinsi':
            $data = get_provinces_data();
            break;
        case 'kabkota':
            $data = get_regencies_data($parent_id);
            break;
        case 'kecamatan':
            $data = get_districts_data($parent_id);
            break;
        case 'kelurahan':
            $data = get_villages_data($parent_id);
            break;
    }

    // Store in cache (using cookies for simplicity - in production, consider localStorage or sessionStorage)
    setcookie($cache_key, json_encode($data), time() + 86400, '/'); // 24 hours
    setcookie($max_date_key, $current_max_date, time() + 86400, '/');
    setcookie($cache_time_key, $current_time, time() + 86400, '/');

    return $data;
}

/**
 * Clear address data cache
 * @param string|null $type Specific type to clear, null for all
 */
function clear_address_cache($type = null) {
    $cache_keys = ['provinsi', 'kabkota', 'kecamatan', 'kelurahan'];

    if ($type !== null) {
        $cache_keys = [$type];
    }

    foreach ($cache_keys as $key) {
        $cache_key = generate_address_cache_key($key);
        $max_date_key = "address_max_date_{$key}";
        $cache_time_key = "address_cache_time_{$key}";

        setcookie($cache_key, '', time() - 3600, '/');
        setcookie($max_date_key, '', time() - 3600, '/');
        setcookie($cache_time_key, '', time() - 3600, '/');
    }
}

/**
 * Get address data with caching (JSON response for API)
 * @param string $type Data type
 * @param int|null $parent_id Parent ID
 * @return string JSON response
 */
function get_address_data_json($type, $parent_id = null) {
    try {
        $max_dates = get_address_max_dates();
        $data = get_cached_address_data($type, $parent_id, $max_dates);

        return json_encode([
            'success' => true,
            'data' => $data,
            'cached' => true,
            'max_date' => isset($max_dates[$type]) ? $max_dates[$type]['max_date'] : null,
            'record_count' => isset($max_dates[$type]) ? $max_dates[$type]['record_count'] : 0
        ]);
    } catch (Exception $e) {
        return json_encode([
            'success' => false,
            'message' => 'Error fetching address data',
            'error' => $e->getMessage()
        ]);
    }
}
?>
