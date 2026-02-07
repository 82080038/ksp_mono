/**
 * Address Data Caching System - Browser-side implementation
 * Uses localStorage for better performance and larger storage capacity
 */

class AddressCache {
    constructor() {
        this.baseUrl = '/ksp_mono/api';
        this.cachePrefix = 'address_cache_';
        this.maxDatePrefix = 'address_max_date_'; // Changed from version to max_date
        this.timePrefix = 'address_time_';
        this.cacheDuration = 24 * 60 * 60 * 1000; // 24 hours in milliseconds
    }

    /**
     * Generate cache key for address data
     * @param {string} type - Data type (provinsi, kabkota, kecamatan, kelurahan)
     * @param {number|null} parentId - Parent ID for hierarchical data
     * @returns {string} Cache key
     */
    generateCacheKey(type, parentId = null) {
        let key = `${this.cachePrefix}${type}`;
        if (parentId !== null) {
            key += `_${parentId}`;
        }
        return key;
    }

    /**
     * Check if cached data is still valid using max_date tracking
     * @param {string} type - Data type
     * @param {string} serverMaxDate - Server max date
     * @returns {boolean} Whether cache is valid
     */
    isCacheValid(type, serverMaxDate) {
        const storedMaxDate = localStorage.getItem(`${this.maxDatePrefix}${type}`);
        const storedTime = localStorage.getItem(`${this.timePrefix}${type}`);

        if (!storedMaxDate || !storedTime) {
            return false;
        }

        const currentTime = Date.now();
        const cacheAge = currentTime - parseInt(storedTime);

        // Cache is valid if max_date matches and cache is less than 24 hours old
        return storedMaxDate === serverMaxDate && cacheAge < this.cacheDuration;
    }

    /**
     * Store data in cache with max_date tracking
     * @param {string} type - Data type
     * @param {Array} data - Data to cache
     * @param {string} maxDate - Max date from server
     */
    storeInCache(type, data, maxDate) {
        const key = this.generateCacheKey(type);
        localStorage.setItem(key, JSON.stringify(data));
        localStorage.setItem(`${this.maxDatePrefix}${type}`, maxDate);
        localStorage.setItem(`${this.timePrefix}${type}`, Date.now().toString());
    }

    /**
     * Get cached data
     * @param {string} type - Data type
     * @param {number|null} parentId - Parent ID
     * @returns {Array|null} Cached data or null if not found
     */
    getCachedData(type, parentId = null) {
        const key = this.generateCacheKey(type, parentId);
        const data = localStorage.getItem(key);

        if (!data) {
            return null;
        }

        try {
            return JSON.parse(data);
        } catch (e) {
            console.warn('Invalid cached data for', type, e);
            return null;
        }
    }

    /**
     * Check server for max_date changes (more efficient than version checking)
     * @param {string} type - Data type to check
     * @returns {Promise<Object>} Max date check result
     */
    async checkVersion(type) {
        try {
            const response = await fetch(`${this.baseUrl}/address_cache.php?table=${type}`);

            if (!response.ok) {
                throw new Error(`HTTP ${response.status}: ${response.statusText}`);
            }

            const contentType = response.headers.get('content-type');
            if (!contentType || !contentType.includes('application/json')) {
                const text = await response.text();
                console.error('Non-JSON response from address_cache.php:', text.substring(0, 200));
                throw new Error('Server returned non-JSON response');
            }

            const result = await response.json();

            if (!result.success) {
                throw new Error(result.message || 'Max date check failed');
            }

            return result;
        } catch (error) {
            console.error('Max date check error:', error);
            throw error;
        }
    }

    /**
     * Fetch data from server
     * @param {string} type - Data type
     * @param {number|null} parentId - Parent ID
     * @param {Object} params - Additional parameters
     * @returns {Promise<Array>} Fetched data
     */
    async fetchFromServer(type, parentId = null, params = {}) {
        // Map database table names to API endpoint names
        const apiEndpointMap = {
            'provinsi': 'provinces',
            'kabkota': 'regencies',
            'kecamatan': 'districts',
            'kelurahan': 'villages'
        };

        // Get the correct API endpoint name
        const apiEndpoint = apiEndpointMap[type] || type;

        let url = `${this.baseUrl}/${apiEndpoint}.php`;

        // Build query parameters
        const queryParams = new URLSearchParams();

        if (parentId !== null) {
            // Map parameter names based on type
            const paramMap = {
                kabkota: 'province_id',
                kecamatan: 'regency_id',
                kelurahan: 'district_id'
            };

            if (paramMap[type]) {
                queryParams.set(paramMap[type], parentId.toString());
            }
        }

        // Add additional params
        Object.keys(params).forEach(key => {
            queryParams.set(key, params[key]);
        });

        if (queryParams.toString()) {
            url += '?' + queryParams.toString();
        }

        try {
            const response = await fetch(url);

            if (!response.ok) {
                throw new Error(`HTTP ${response.status}: ${response.statusText}`);
            }

            const contentType = response.headers.get('content-type');
            if (!contentType || !contentType.includes('application/json')) {
                const text = await response.text();
                console.error(`Non-JSON response from ${apiEndpoint}.php:`, text.substring(0, 200));
                throw new Error('Server returned non-JSON response');
            }

            const result = await response.json();

            if (!result.success) {
                throw new Error(result.message || 'Server error');
            }

            return result.data || [];
        } catch (error) {
            console.error('Server fetch error:', error);
            throw error;
        }
    }

    /**
     * Get address data with caching using max_date tracking
     * @param {string} type - Data type (provinsi, kabkota, kecamatan, kelurahan)
     * @param {number|null} parentId - Parent ID for hierarchical data
     * @param {Object} options - Options for fetching
     * @returns {Promise<Array>} Address data
     */
    async getData(type, parentId = null, options = {}) {
        const { forceRefresh = false, params = {} } = options;

        try {
            // Check max_date from server
            let maxDateCheck = null;
            if (!forceRefresh) {
                try {
                    maxDateCheck = await this.checkVersion(type);
                } catch (e) {
                    console.warn('Max date check failed, fetching fresh data:', e);
                }
            }

            let data = null;
            let shouldCache = false;

            if (!forceRefresh && maxDateCheck && this.isCacheValid(type, maxDateCheck.server_max_date)) {
                // Use cached data
                data = this.getCachedData(type, parentId);
                if (data) {
                    console.log(`Using cached ${type} data`);
                    return data;
                }
            }

            // Fetch fresh data from server
            console.log(`Fetching fresh ${type} data from server`);
            data = await this.fetchFromServer(type, parentId, params);
            shouldCache = true;

            // Cache the data if we have max_date info
            if (shouldCache && maxDateCheck && maxDateCheck.server_max_date) {
                this.storeInCache(type, data, maxDateCheck.server_max_date);
                console.log(`Cached ${type} data with max_date ${maxDateCheck.server_max_date}`);
            }

            return data;

        } catch (error) {
            console.error(`Error getting ${type} data:`, error);

            // Try to return cached data as fallback
            const cachedData = this.getCachedData(type, parentId);
            if (cachedData) {
                console.warn(`Returning stale cached ${type} data due to error`);
                return cachedData;
            }

            throw error;
        }
    }

    /**
     * Clear cache for specific type or all types
     * @param {string|null} type - Specific type to clear, null for all
     */
    clearCache(type = null) {
        const types = ['provinsi', 'kabkota', 'kecamatan', 'kelurahan'];

        (type ? [type] : types).forEach(t => {
            // Clear main cache
            localStorage.removeItem(this.generateCacheKey(t));

            // Clear max_date and time data
            localStorage.removeItem(`${this.maxDatePrefix}${t}`);
            localStorage.removeItem(`${this.timePrefix}${t}`);

            console.log(`Cleared cache for ${t}`);
        });
    }

    /**
     * Get cache statistics
     * @returns {Object} Cache statistics
     */
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
}

// Create global instance with simplified approach
console.log('Initializing AddressCache...');

try {
    // Create the instance
    const addressCacheInstance = new AddressCache();

    // Verify it has the required methods before assigning globally
    if (addressCacheInstance && typeof addressCacheInstance.getData === 'function') {
        window.AddressCache = addressCacheInstance;
        console.log('✅ AddressCache initialized successfully');
        console.log('Available methods:', Object.getOwnPropertyNames(Object.getPrototypeOf(addressCacheInstance)).filter(name => typeof addressCacheInstance[name] === 'function'));
    } else {
        console.error('❌ AddressCache instance missing required methods');
        window.AddressCache = null;
    }
} catch (error) {
    console.error('❌ Error initializing AddressCache:', error.message);
    window.AddressCache = null;
}

// jQuery integration (only if AddressCache was successfully created)
if (typeof $ !== 'undefined' && window.AddressCache) {
    $.addressCache = window.AddressCache;
}
