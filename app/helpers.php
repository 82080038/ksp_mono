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
 * Indonesian Address Formatting Helper Functions
 */

/**
 * Format Indonesian address using international standards
 * @param array $address_components Address components array
 * @return string Formatted address
 */
function format_indonesian_address($address_components) {
    // Indonesian Address Format (6-line structure):
    // [Street Name] [House Number]
    // [Village]
    // [District with Kec. prefix]
    // [Regency]
    // [Province]
    // INDONESIA - [Postal Code]

    $formatted = '';

    // Line 1: Street level (road + house number)
    $street_parts = [];
    if (!empty($address_components['road'])) {
        $street_parts[] = $address_components['road'];
    }
    if (!empty($address_components['houseNumber'])) {
        $street_parts[] = 'No. ' . $address_components['houseNumber'];
    }

    if (!empty($street_parts)) {
        $formatted .= implode(' ', $street_parts) . "\n";
    }

    // Line 2: Village name only
    if (!empty($address_components['village'])) {
        $formatted .= $address_components['village'] . "\n";
    }

    // Line 3: District with Kec. prefix
    if (!empty($address_components['district'])) {
        $formatted .= 'Kec. ' . $address_components['district'] . "\n";
    }

    // Line 4: Regency name
    if (!empty($address_components['regency'])) {
        $formatted .= $address_components['regency'] . "\n";
    }

    // Line 5: Province name
    if (!empty($address_components['province'])) {
        $formatted .= $address_components['province'] . "\n";
    }

    // Line 6: Country with postal code
    $country_line = 'INDONESIA';
    if (!empty($address_components['postcode'])) {
        $country_line .= ' - ' . $address_components['postcode'];
    }
    $formatted .= $country_line;

    return trim($formatted);
}

/**
 * Validate Indonesian address components
 * @param array $address_components Address components
 * @return array Validation result with errors
 */
function validate_indonesian_address($address_components) {
    $errors = [];
    $warnings = [];

    // Required components for Indonesian addresses
    if (empty($address_components['province'])) {
        $errors[] = 'Provinsi wajib diisi';
    }

    if (empty($address_components['city']) && empty($address_components['regency'])) {
        $warnings[] = 'Kota/Kabupaten disarankan diisi untuk alamat lengkap';
    }

    // Postal code validation
    if (!empty($address_components['postcode'])) {
        if (!preg_match('/^\d{5}$/', $address_components['postcode'])) {
            $errors[] = 'Kode pos harus 5 digit angka';
        }
    } else {
        $warnings[] = 'Kode pos disarankan diisi';
    }

    // Street address validation
    if (empty($address_components['road']) && empty($address_components['houseNumber'])) {
        $warnings[] = 'Nama jalan atau nomor rumah disarankan diisi';
    }

    return [
        'valid' => empty($errors),
        'errors' => $errors,
        'warnings' => $warnings
    ];
}

/**
 * Generate address suggestions for Indonesian addresses
 * @param string $input User input
 * @return array Address suggestions
 */
function suggest_indonesian_address($input) {
    $suggestions = [];

    // Common Indonesian address patterns
    $patterns = [
        'jalan' => ['Jl.', 'Jalan'],
        'gang' => ['Gg.', 'Gang'],
        'nomor' => ['No.', 'Nomor'],
        'rt' => ['RT', 'Rt'],
        'rw' => ['RW', 'Rw'],
        'desa' => ['Ds.', 'Desa'],
        'kelurahan' => ['Kel.', 'Kelurahan'],
        'kecamatan' => ['Kec.', 'Kecamatan'],
        'kabupaten' => ['Kab.', 'Kabupaten'],
        'kota' => ['Kota']
    ];

    // Simple suggestion based on input
    $input_lower = strtolower($input);

    foreach ($patterns as $key => $variations) {
        if (strpos($input_lower, $key) === false) {
            $suggestions[] = $input . ' ' . $variations[0];
        }
    }

    return array_slice($suggestions, 0, 5); // Limit to 5 suggestions
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
 * Validate Indonesian NPWP format (standard format: XX.XXX.XXX.X-XXX.XXX)
 * @param string $npwp NPWP number
 * @return array Validation result with message
 */
function validate_npwp($npwp) {
    // Handle empty input (optional field)
    if (empty($npwp)) {
        return ['valid' => true, 'message' => ''];
    }
    
    // Standard NPWP format: XX.XXX.XXX.X-XXX.XXX
    $pattern = '/^\d{2}\.\d{3}\.\d{3}\.\d-\d{3}\.\d{3}$/';
    
    // Reject any non-format characters
    if (preg_match('/[^0-9.-]/', $npwp)) {
        return ['valid' => false, 'message' => 'Hanya angka, titik, dan strip yang diperbolehkan'];
    }
    
    if (preg_match($pattern, $npwp)) {
        return ['valid' => true, 'message' => 'Format NPWP valid'];
    }
    
    // Also accept clean 15/16 digit numbers
    $clean = preg_replace('/[^0-9]/', '', $npwp);
    if (strlen($clean) === 15 || strlen($clean) === 16) {
        return ['valid' => true, 'message' => 'Format NPWP valid (tanpa pemisah)'];
    }
    
    return ['valid' => false, 'message' => 'Format NPWP tidak valid. Gunakan format: XX.XXX.XXX.X-XXX.XXX'];
}

/**
 * Validate cooperative business entity number
 * @param string $badan_hukum Business entity number
 * @param string|null $status Optional status to validate against number
 * @return bool Is valid
 */
function validate_badan_hukum_koperasi($badan_hukum, $status = null) {
    // Empty is valid (optional field)
    if (empty($badan_hukum)) return true;
    
    // Clean input - remove all non-digit characters
    $clean = preg_replace('/\D/', '', $badan_hukum);
    
    // Must be exactly 12 digits if status requires it
    if ($status === 'badan_hukum' || $status === 'terdaftar') {
        return strlen($clean) === 12;
    }
    
    // For other statuses or no status, just validate basic format
    return strlen($clean) >= 10; // Minimum 10 digits
}

/**
 * Combo Box Helper Functions
 */

/**
 * Initialize combo box with auto-focus and data loading
 * @param string $selector Combo box selector
 * @param array $options Configuration options
 */
function init_combo_box($selector, $options = []) {
    $defaults = [
        'data_url' => '',
        'next_tab_selector' => '',
        'min_length' => 0
    ];
    $options = array_merge($defaults, $options);

    echo "<script>
    $(document).ready(function() {
        $('{$selector}').on('focus', function() {
            // Load data if empty and meets minimum length
            if ($(this).find('option').length <= 1 && $(this).val().length >= {$options['min_length']}) {
                load_combo_data('{$selector}', '{$options['data_url']}');
            }
        }).on('change', function() {
            // Auto-tab to next field if selected
            if ($(this).val() && '{$options['next_tab_selector']}') {
                $('{$options['next_tab_selector']}').focus();
            }
        });
    });

    function load_combo_data(selector, url) {
        $(selector).prop('disabled', true);
        $.getJSON(url, function(data) {
            if (data && data.length > 0) {
                $(selector).empty().append('<option value=\"\">-- Pilih --</option>');
                $.each(data, function(i, item) {
                    $(selector).append($('<option>', {
                        value: item.id,
                        text: item.name
                    }));
                });
            }
            $(selector).prop('disabled', false).focus();
        }).fail(function() {
            $(selector).prop('disabled', false);
        });
    }
    </script>";
}

/**
 * Check if user has specific permission
 * @param string $permission Permission key to check
 * @return bool Whether user has permission
 */
function has_permission($permission) {
    if (!isset($_SESSION['user_id'])) {
        return false;
    }
    
    // Check session cache first
    if (isset($_SESSION['permissions']) && in_array($permission, $_SESSION['permissions'])) {
        return true;
    }
    
    $db = Database::conn();
    
    // Get all permissions for user's roles
    $stmt = $db->prepare('SELECT p.permission_key 
        FROM pengguna_peran pp
        JOIN peran_izin pi ON pp.peran_jenis_id = pi.peran_jenis_id
        JOIN permissions p ON pi.permission_id = p.id
        WHERE pp.pengguna_id = ?');
    $stmt->execute([$_SESSION['user_id']]);
    
    $user_permissions = $stmt->fetchAll(PDO::FETCH_COLUMN);
    $_SESSION['permissions'] = $user_permissions; // Cache in session
    
    return in_array($permission, $user_permissions);
}

?>
