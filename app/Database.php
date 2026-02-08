<?php
class Database
{
    private static ?PDO $pdo = null;

    public static function conn($dbName = 'koperasi_db') {
        global $config;
        
        if (!isset($config[$dbName])) {
            throw new Exception("Database configuration not found for {$dbName}");
        }
        
        $dbConfig = $config[$dbName];
        
        $dsn = "mysql:host={$dbConfig['host']};dbname={$dbConfig['name']}";
        if (isset($dbConfig['charset'])) {
            $dsn .= ";charset={$dbConfig['charset']}";
        }
        
        return new PDO(
            $dsn,
            $dbConfig['user'],
            $dbConfig['pass'],
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]
        );
    }
}
