/**
 * Minimal AddressCache Test Version
 * To isolate the getData method issue
 */

class AddressCacheTest {
    constructor() {
        this.baseUrl = '/ksp_mono/api';
        this.cachePrefix = 'address_cache_test_';
        this.maxDatePrefix = 'address_max_date_test_';
        this.timePrefix = 'address_time_test_';
        this.sizePrefix = 'address_size_test_';
        this.cacheDuration = 24 * 60 * 60 * 1000; // 24 hours

        // Check localStorage reliability and capacity
        this.localStorageReliable = this.checkLocalStorageReliability();
        this.localStorageCapacity = this.checkLocalStorageCapacity();
        console.log('AddressCacheTest constructor - localStorage reliable:', this.localStorageReliable, 'capacity:', this.localStorageCapacity, 'MB');
    }

    // Check localStorage reliability once during construction
    checkLocalStorageReliability() {
        try {
            const testKey = '__storage_test_' + Date.now() + '__';
            const testValue = 'test_value_' + Math.random();

            // Test set
            localStorage.setItem(testKey, testValue);

            // Test get
            const retrieved = localStorage.getItem(testKey);
            if (retrieved !== testValue) {
                return false;
            }

            // Test remove
            localStorage.removeItem(testKey);
            const shouldBeNull = localStorage.getItem(testKey);
            if (shouldBeNull !== null) {
                return false;
            }

            return true;
        } catch (error) {
            console.warn('localStorage reliability check failed:', error);
            return false;
        }
    }

    // Check localStorage capacity and return available space in MB
    checkLocalStorageCapacity() {
        if (!this.localStorageReliable) return 0;

        try {
            const testKey = '__capacity_test__';
            const chunkSize = 1024 * 1024; // 1MB chunks
            let totalSize = 0;
            let testString = 'x'.repeat(chunkSize);

            // Fill localStorage until it's full or we reach a reasonable limit
            while (totalSize < 50) { // Test up to 50MB
                try {
                    const key = testKey + totalSize;
                    localStorage.setItem(key, testString);
                    totalSize++;

                    // Clean up as we go to avoid actually filling storage
                    localStorage.removeItem(key);
                } catch (e) {
                    // Storage is full or quota exceeded
                    break;
                }
            }

            // Estimate available capacity (rough estimate)
            const estimatedCapacity = Math.max(5, totalSize * 0.8); // Conservative estimate
            return estimatedCapacity;

        } catch (error) {
            console.warn('Error checking localStorage capacity:', error);
            return 0;
        }
    }

    // Check if cached data is still valid (with localStorage availability check)
    isCacheValid(type, parentId = null, serverMaxDate = null) {
        try {
            // Check if localStorage is reliable
            if (!this.localStorageReliable) {
                return false;
            }

            const cacheKey = this.generateCacheKey(type, parentId);
            const storedMaxDate = localStorage.getItem(`${this.maxDatePrefix}${cacheKey}`);
            const storedTime = localStorage.getItem(`${this.timePrefix}${cacheKey}`);

            if (!storedMaxDate || !storedTime) {
                return false;
            }

            const currentTime = Date.now();
            const cacheAge = currentTime - parseInt(storedTime);

            // Cache is valid if max_date matches (if provided) and cache is less than 24 hours old
            const dateValid = serverMaxDate ? storedMaxDate === serverMaxDate : true;
            return dateValid && cacheAge < this.cacheDuration;
        } catch (error) {
            console.warn('Error checking cache validity:', error);
            return false;
        }
    }

    // Store data in cache (with size monitoring)
    storeInCache(type, data, parentId = null, maxDate = null) {
        try {
            // Check if localStorage is reliable
            if (!this.localStorageReliable) {
                console.log('localStorage not reliable, skipping cache storage');
                return;
            }

            // Check if we have enough capacity
            const dataSize = JSON.stringify(data).length;
            const estimatedSizeMB = dataSize / (1024 * 1024);

            if (this.getCurrentCacheSizeMB() + estimatedSizeMB > this.localStorageCapacity * 0.8) {
                console.warn('localStorage near capacity, skipping cache storage');
                return;
            }

            const cacheKey = this.generateCacheKey(type, parentId);
            localStorage.setItem(cacheKey, JSON.stringify(data));
            localStorage.setItem(`${this.maxDatePrefix}${cacheKey}`, maxDate || 'cached');
            localStorage.setItem(`${this.timePrefix}${cacheKey}`, Date.now().toString());
            localStorage.setItem(`${this.sizePrefix}${cacheKey}`, dataSize.toString());

            console.log(`✅ Cached ${type} data (${estimatedSizeMB.toFixed(2)}MB) with key: ${cacheKey}`);
        } catch (error) {
            console.warn('Error storing cache:', error);
            // Continue without caching
        }
    }

    // Get cached data (with validation)
    getCachedData(type, parentId = null) {
        try {
            // Check if localStorage is reliable
            if (!this.localStorageReliable) {
                return null;
            }

            const cacheKey = this.generateCacheKey(type, parentId);
            const data = localStorage.getItem(cacheKey);

            if (!data) {
                return null;
            }

            // Validate cache
            if (!this.isCacheValid(type, parentId)) {
                console.log(`Cache expired for ${cacheKey}, removing`);
                this.removeCache(type, parentId);
                return null;
            }

            const parsed = JSON.parse(data);
            console.log(`✅ Retrieved ${type} data from localStorage (${parsed.length} items)`);
            return parsed;
        } catch (error) {
            console.warn('Error retrieving cached data:', error);
            return null;
        }
    }

    // Remove specific cache entry
    removeCache(type, parentId = null) {
        try {
            const cacheKey = this.generateCacheKey(type, parentId);
            localStorage.removeItem(cacheKey);
            localStorage.removeItem(`${this.maxDatePrefix}${cacheKey}`);
            localStorage.removeItem(`${this.timePrefix}${cacheKey}`);
            localStorage.removeItem(`${this.sizePrefix}${cacheKey}`);
            console.log(`🗑️ Removed cache for ${cacheKey}`);
        } catch (error) {
            console.warn('Error removing cache:', error);
        }
    }

    // Get current cache size in MB
    getCurrentCacheSizeMB() {
        try {
            let totalSize = 0;
            for (let key in localStorage) {
                if (key.startsWith(this.cachePrefix) ||
                    key.startsWith(this.maxDatePrefix) ||
                    key.startsWith(this.timePrefix) ||
                    key.startsWith(this.sizePrefix)) {
                    const sizeStr = localStorage.getItem(key.replace(this.cachePrefix, this.sizePrefix));
                    if (sizeStr) {
                        totalSize += parseInt(sizeStr);
                    }
                }
            }
            return totalSize / (1024 * 1024); // Convert to MB
        } catch (error) {
            console.warn('Error calculating cache size:', error);
            return 0;
        }
    }

    // Generate cache key based on type and parent ID
    generateCacheKey(type, parentId = null) {
        return `${this.cachePrefix}${type}${parentId ? `_${parentId}` : ''}`;
    }

    // Get data with sophisticated caching strategy
    async getData(type, parentId = null, options = {}) {
        console.log(`AddressCacheTest.getData: ${type}, parentId: ${parentId}`);

        try {
            // Strategy: Check cache first, then database if needed

            // 1. Check if data exists in cache and is valid
            const cachedData = this.getCachedData(type, parentId);
            if (cachedData && cachedData.length > 0) {
                console.log(`🎯 Using cached ${type} data (${cachedData.length} items)`);
                return cachedData;
            }

            // 2. Fetch from database
            // Always try to fetch fresh data when localStorage is disabled
            // This ensures the method works even in incognito/cache-disabled mode
            console.log(`📡 Fetching fresh ${type} data from server`);

            // Map address types to API endpoints
            const apiEndpointMap = {
                'provinsi': 'provinces.php',
                'kabkota': 'regencies.php',
                'kecamatan': 'districts.php',
                'kelurahan': 'villages.php'
            };

            const apiEndpoint = apiEndpointMap[type] || 'provinces.php';
            let url = `${this.baseUrl}/${apiEndpoint}`;

            // Add query parameters for hierarchical data
            if (parentId !== null) {
                if (type === 'kabkota') {
                    url += `?province_id=${parentId}`;
                } else if (type === 'kecamatan') {
                    url += `?regency_id=${parentId}`;
                } else if (type === 'kelurahan') {
                    url += `?district_id=${parentId}`;
                }
            }

            console.log('Fetching URL:', url);
            const response = await fetch(url);
            console.log('Fetch response status:', response.status);

            if (!response.ok) {
                throw new Error(`HTTP ${response.status}: ${response.statusText}`);
            }

            const contentType = response.headers.get('content-type');
            console.log('Content-Type:', contentType);

            if (!contentType || !contentType.includes('application/json')) {
                const text = await response.text();
                console.error('Non-JSON response:', text.substring(0, 100));
                throw new Error('Server returned non-JSON response');
            }

            const result = await response.json();
            console.log('JSON response:', result);

            if (!result.success) {
                throw new Error(result.message || 'Server error');
            }

            const data = result.data || [];

            // Try to cache the data (but don't fail if localStorage is disabled)
            try {
                this.storeInCache(type, data, 'test-cache-' + Date.now());
            } catch (cacheError) {
                console.warn('Cache storage failed (likely localStorage disabled):', cacheError.message);
                // Continue without caching
            }

            return data;

        } catch (error) {
            console.error('AddressCacheTest.getData error:', error);

            // Don't try to return cached data as fallback when localStorage is disabled
            // Just re-throw the error to trigger the jQuery fallback
            throw error;
        }
    }

    // Get cache statistics
    getCacheStats() {
        const stats = {};
        const types = ['provinsi', 'kabkota', 'kecamatan', 'kelurahan'];

        types.forEach(type => {
            const data = this.getCachedData(type);
            const maxDate = localStorage.getItem(`${this.maxDatePrefix}${type}`);
            const time = localStorage.getItem(`${this.timePrefix}${type}`);

            stats[type] = {
                hasCache: data !== null,
                recordCount: data ? data.length : 0,
                maxDate: maxDate,
                cacheTime: time ? new Date(parseInt(time)).toLocaleString() : null,
                isExpired: time ? (Date.now() - parseInt(time)) > this.cacheDuration : true
            };
        });

        return stats;
    }

    // Clear cache
    clearCache(type = null) {
        const types = type ? [type] : ['provinsi', 'kabkota', 'kecamatan', 'kelurahan'];

        types.forEach(t => {
            localStorage.removeItem(`${this.cachePrefix}${t}`);
            localStorage.removeItem(`${this.maxDatePrefix}${t}`);
            localStorage.removeItem(`${this.timePrefix}${t}`);
            console.log(`🗑️ Cleared cache for ${t}`);
        });
    }

    // Simple sync method for testing
    getVersion() {
        console.log('AddressCacheTest.getVersion called');
        return '1.0.0-test';
    }
}

// Create global instance
console.log('Creating AddressCacheTest global instance...');
window.AddressCacheTest = new AddressCacheTest();
console.log('AddressCacheTest global instance created');

console.log('AddressCacheTest methods:', Object.getOwnPropertyNames(Object.getPrototypeOf(window.AddressCacheTest)).filter(name => typeof window.AddressCacheTest[name] === 'function'));

// Test basic functionality
setTimeout(() => {
    console.log('Testing AddressCacheTest.getVersion():', window.AddressCacheTest.getVersion());
    console.log('AddressCacheTest.getData available:', typeof window.AddressCacheTest.getData === 'function');
}, 100);
