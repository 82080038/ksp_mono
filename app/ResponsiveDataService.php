<?php
require_once __DIR__ . '/helpers.php';
require_once __DIR__ . '/Database.php';

class ResponsiveDataService {
    private static $db;
    
    private static function initDB() {
        if (!self::$db) {
            self::$db = Database::conn();
        }
    }
    
    public static function getLimits() {
        $deviceType = get_device_type();
        
        return [
            'mobile' => [
                'default' => 5,
                'transactions' => 5,
                'notifications' => 3,
                'members' => 10
            ],
            'tablet' => [
                'default' => 8,
                'transactions' => 8,
                'notifications' => 5,
                'members' => 15
            ],
            'desktop' => [
                'default' => 15,
                'transactions' => 15,
                'notifications' => 10,
                'members' => 25
            ]
        ][$deviceType];
    }
    
    public static function getData(string $table, array $options = []) {
        self::initDB();
        $limits = self::getLimits();
        $limit = $options['limit'] ?? $limits[$table] ?? $limits['default'];
        
        // Validate table and column names
        $validColumns = [
            'anggota' => ['id', 'user_id', 'status_keanggotaan', 'nomor_anggota', 'joined_at', 'updated_at'],
            'transactions' => ['id', 'tanggal', 'jumlah', 'jenis']
        ];
        
        if (!isset($validColumns[$table])) {
            throw new Exception("Invalid table or unspecified columns for: {$table}");
        }
        
        $query = "SELECT * FROM {$table}";
        
        if (isset($options['where'])) {
            $query .= " WHERE {$options['where']}";
        }
        
        if (isset($options['order'])) {
            // Validate order column
            $orderColumn = preg_replace('/\s+(ASC|DESC)$/i', '', $options['order']);
            if (!in_array($orderColumn, $validColumns[$table])) {
                throw new Exception("Invalid order column: {$orderColumn}");
            }
            $query .= " ORDER BY {$options['order']}";
        }
        
        $query .= " LIMIT {$limit}";
        
        return self::$db->query($query)->fetchAll();
    }
}
