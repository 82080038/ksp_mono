<?php
/**
 * Test script for Address Caching System
 */

// Include helpers
require_once __DIR__ . '/app/bootstrap.php';
require_once __DIR__ . '/app/helpers.php';

echo "🧪 Testing Address Caching System\n";
echo "================================\n\n";

// Test 1: Check database connection and table_max_dates
echo "1️⃣ Testing Database Setup:\n";
try {
    $db = get_address_db_connection();
    echo "✅ Database connection successful\n";

    // Check table_max_dates exists
    $stmt = $db->query("SHOW TABLES LIKE 'table_max_dates'");
    if ($stmt->fetch()) {
        echo "✅ table_max_dates table exists\n";

        // Check data
        $maxDates = get_address_max_dates();
        echo "📊 Max dates data:\n";
        foreach ($maxDates as $table => $data) {
            echo "   {$table}: {$data['max_date']} ({$data['record_count']} records)\n";
        }
    } else {
        echo "❌ table_max_dates table missing\n";
        exit(1);
    }

} catch (Exception $e) {
    echo "❌ Database error: " . $e->getMessage() . "\n";
    exit(1);
}

// Test 2: Test API response
echo "\n2️⃣ Testing API Endpoint:\n";
$apiUrl = 'http://localhost/ksp_mono/public/api/address_cache.php';
$context = stream_context_create([
    'http' => ['timeout' => 10]
]);

try {
    $response = file_get_contents($apiUrl, false, $context);
    if ($response) {
        $data = json_decode($response, true);
        if ($data && isset($data['success']) && $data['success']) {
            echo "✅ API response successful\n";
            echo "📊 Tables tracked: " . count($data['max_dates']) . "\n";
        } else {
            echo "❌ API returned error: " . ($data['message'] ?? 'Unknown error') . "\n";
        }
    } else {
        echo "❌ No response from API\n";
    }
} catch (Exception $e) {
    echo "❌ API test error: " . $e->getMessage() . "\n";
}

// Test 3: Test helper functions
echo "\n3️⃣ Testing Helper Functions:\n";
try {
    // Test provinces data
    $provinces = get_provinces_data();
    echo "✅ Provinces data: " . count($provinces) . " records\n";

    if (count($provinces) > 0) {
        // Test regencies for first province
        $regencies = get_regencies_data($provinces[0]['id']);
        echo "✅ Regencies for {$provinces[0]['name']}: " . count($regencies) . " records\n";

        if (count($regencies) > 0) {
            // Test districts for first regency
            $districts = get_districts_data($regencies[0]['id']);
            echo "✅ Districts for {$regencies[0]['name']}: " . count($districts) . " records\n";
        }
    }

} catch (Exception $e) {
    echo "❌ Helper functions error: " . $e->getMessage() . "\n";
}

// Test 4: Test caching functionality
echo "\n4️⃣ Testing Caching Logic:\n";
try {
    // Test cache key generation
    $key = generate_address_cache_key('provinsi');
    echo "✅ Cache key generation: {$key}\n";

    // Test cache data retrieval (without actual caching since we're server-side)
    $cachedData = get_cached_address_data('provinsi');
    echo "✅ Cached data retrieval: " . count($cachedData) . " provinces\n";

    // Test change detection
    $hasChanged = has_address_data_changed('provinsi', '2000-01-01 00:00:00');
    echo "✅ Change detection: " . ($hasChanged ? 'Changes detected' : 'No changes') . "\n";

} catch (Exception $e) {
    echo "❌ Caching logic error: " . $e->getMessage() . "\n";
}

echo "\n🎉 Address Caching System Test Complete!\n";
?>
