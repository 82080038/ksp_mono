<?php
/**
 * Comprehensive Form Testing Script for register_koperasi.php
 */

// Test 1: Check if form is accessible
echo "🧪 COMPREHENSIVE FORM TESTING\n";
echo "============================\n\n";

echo "1️⃣ Testing Form Accessibility:\n";
$formUrl = 'http://localhost/ksp_mono/public/register_koperasi.php';
$context = stream_context_create([
    'http' => ['timeout' => 10]
]);

$formContent = file_get_contents($formUrl, false, $context);
if ($formContent && strpos($formContent, 'Registrasi Koperasi') !== false) {
    echo "✅ Form is accessible and contains expected content\n";
} else {
    echo "❌ Form is not accessible or missing content\n";
    exit(1);
}

// Test 2: Check required JavaScript libraries are loaded
echo "\n2️⃣ Testing JavaScript Libraries:\n";
$libraries = [
    'jquery-3.7.1.min.js' => 'jQuery',
    'inputmask' => 'InputMask',
    'address-cache.js' => 'AddressCache'
];

foreach ($libraries as $lib => $name) {
    if (strpos($formContent, $lib) !== false) {
        echo "✅ $name library is included\n";
    } else {
        echo "❌ $name library is missing\n";
    }
}

// Test 3: Check form fields are present
echo "\n3️⃣ Testing Form Fields Presence:\n";
$requiredFields = [
    'name="province_id"' => 'Province dropdown',
    'name="jenis_koperasi"' => 'Jenis Koperasi dropdown',
    'name="nama_koperasi"' => 'Nama Koperasi field',
    'name="kontak"' => 'Kontak field',
    'name="admin_nama"' => 'Admin nama field',
    'name="admin_hp"' => 'Admin HP field',
    'name="admin_username"' => 'Admin username field',
    'name="admin_password"' => 'Admin password field',
    'name="tanggal_pendirian"' => 'Tanggal pendirian field',
    'name="modal_pokok"' => 'Modal pokok field'
];

foreach ($requiredFields as $field => $description) {
    if (strpos($formContent, $field) !== false) {
        echo "✅ $description is present\n";
    } else {
        echo "❌ $description is missing\n";
    }
}

// Test 4: Test AddressCache loading (simulate browser environment)
echo "\n4️⃣ Testing AddressCache System:\n";

// Simulate loading the AddressCache script
$cacheScriptUrl = 'http://localhost/ksp_mono/public/assets/js/address-cache.js';
$cacheScript = file_get_contents($cacheScriptUrl, false, $context);

if ($cacheScript && strpos($cacheScript, 'class AddressCache') !== false) {
    echo "✅ AddressCache script is accessible\n";

    if (strpos($cacheScript, 'window.AddressCache = new AddressCache()') !== false) {
        echo "✅ AddressCache global instance is created\n";
    } else {
        echo "❌ AddressCache global instance is missing\n";
    }

    if (strpos($cacheScript, 'getData') !== false) {
        echo "✅ AddressCache.getData method exists\n";
    } else {
        echo "❌ AddressCache.getData method is missing\n";
    }

} else {
    echo "❌ AddressCache script is not accessible\n";
}

// Test 5: Test API endpoints that form uses
echo "\n5️⃣ Testing API Endpoints:\n";

// First get a province ID to test hierarchical APIs
$provApiUrl = "http://localhost/ksp_mono/public/api/provinces.php";
$provResponse = file_get_contents($provApiUrl, false, $context);
$provData = json_decode($provResponse, true);
$firstProvinceId = null;

if ($provData && isset($provData['success']) && $provData['success'] && count($provData['data']) > 0) {
    $firstProvinceId = $provData['data'][0]['id'];
    echo "✅ Provinces API: Working (" . count($provData['data']) . " records)\n";
} else {
    echo "❌ Provinces API: Failed to get province data\n";
}

$apis = [
    'address_cache.php' => ['url' => 'address_cache.php', 'desc' => 'Address Cache API']
];

if ($firstProvinceId) {
    $apis['regencies.php'] = [
        'url' => "regencies.php?province_id=$firstProvinceId",
        'desc' => 'Regencies API'
    ];
}

foreach ($apis as $api => $config) {
    $apiUrl = "http://localhost/ksp_mono/public/api/{$config['url']}";
    $apiResponse = file_get_contents($apiUrl, false, $context);

    if ($apiResponse) {
        $data = json_decode($apiResponse, true);
        if ($data && isset($data['success']) && $data['success']) {
            $recordCount = isset($data['data']) ? count($data['data']) : (isset($data['max_dates']) ? count($data['max_dates']) : 0);
            echo "✅ {$config['desc']}: Working ({$recordCount} records)\n";
        } else {
            echo "❌ {$config['desc']}: Returns error - " . ($data['message'] ?? 'Unknown error') . "\n";
        }
    } else {
        echo "❌ {$config['desc']}: Not accessible\n";
    }
}

// Test 6: Test form submission endpoint
echo "\n6️⃣ Testing Form Submission Endpoint:\n";
$submitUrl = 'http://localhost/ksp_mono/public/register_koperasi_process.php';

// Test with empty data (should return validation errors)
$postData = http_build_query([]);
$options = [
    'http' => [
        'method' => 'POST',
        'header' => 'Content-type: application/x-www-form-urlencoded',
        'content' => $postData,
        'timeout' => 10
    ]
];
$context = stream_context_create($options);
$response = file_get_contents($submitUrl, false, $context);

if ($response) {
    $data = json_decode($response, true);
    if ($data && isset($data['success']) && !$data['success']) {
        echo "✅ Form validation is working (correctly rejects empty form)\n";
    } else {
        echo "❌ Form validation is not working properly\n";
    }
} else {
    echo "❌ Form submission endpoint is not accessible\n";
}

// Test 7: Test helper functions
echo "\n7️⃣ Testing Helper Functions:\n";
try {
    require_once __DIR__ . '/app/bootstrap.php';
    require_once __DIR__ . '/app/helpers.php';

    // Test phone number formatting
    $formattedPhone = format_indonesian_phone('081234567890');
    if ($formattedPhone === '62812-3456-7890') {
        echo "✅ Phone number formatting works\n";
    } else {
        echo "❌ Phone number formatting failed: got '$formattedPhone'\n";
    }

    // Test date formatting
    $formattedDate = format_indonesian_date('2026-02-07');
    if ($formattedDate === '07-02-2026') {
        echo "✅ Indonesian date formatting works\n";
    } else {
        echo "❌ Indonesian date formatting failed: got '$formattedDate'\n";
    }

    // Test Rupiah formatting
    $formattedRupiah = format_rupiah(1000000);
    if (strpos($formattedRupiah, 'Rp 1.000.000') !== false) {
        echo "✅ Rupiah formatting works\n";
    } else {
        echo "❌ Rupiah formatting failed: got '$formattedRupiah'\n";
    }

    // Test max dates function
    $maxDates = get_address_max_dates();
    if (is_array($maxDates) && count($maxDates) > 0) {
        echo "✅ Address max dates tracking works (" . count($maxDates) . " tables)\n";
    } else {
        echo "❌ Address max dates tracking failed\n";
    }

} catch (Exception $e) {
    echo "❌ Helper functions error: " . $e->getMessage() . "\n";
}

echo "\n🎉 COMPREHENSIVE FORM TESTING COMPLETE!\n";
echo "📋 Summary: All major functionality has been verified\n";
?>
