<?php
// Helper untuk data alamat dari alamat_db (read-only)
class Address
{
    private static ?PDO $pdo = null;

    private static function conn(): PDO
    {
        if (self::$pdo === null) {
            $cfg = app_config('alamat_db');
            $dsn = sprintf('mysql:host=%s;dbname=%s;charset=%s', $cfg['host'], $cfg['name'], $cfg['charset']);
            self::$pdo = new PDO($dsn, $cfg['user'], $cfg['pass'], [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]);
        }
        return self::$pdo;
    }

    // Mendapatkan semua provinsi
    public static function getProvinces(): array
    {
        $pdo = self::conn();
        $stmt = $pdo->query('SELECT id, name AS nama FROM provinces ORDER BY name ASC');
        return $stmt->fetchAll();
    }

    // Mendapatkan kabupaten/kota berdasarkan province_id
    public static function getRegencies(int $province_id): array
    {
        $pdo = self::conn();
        $stmt = $pdo->prepare('SELECT id, name AS nama FROM regencies WHERE province_id = :pid ORDER BY name ASC');
        $stmt->execute([':pid' => $province_id]);
        return $stmt->fetchAll();
    }

    // Mendapatkan kecamatan berdasarkan regency_id
    public static function getDistricts(int $regency_id): array
    {
        $pdo = self::conn();
        $stmt = $pdo->prepare('SELECT id, name AS nama FROM districts WHERE regency_id = :rid ORDER BY name ASC');
        $stmt->execute([':rid' => $regency_id]);
        return $stmt->fetchAll();
    }

    // Mendapatkan kelurahan/desa berdasarkan district_id
    public static function getVillages(int $district_id): array
    {
        $pdo = self::conn();
        $pdo->exec("SET SESSION sql_mode = ''"); // untuk kompatibilitas
        $hasPostal = false;
        $cols = $pdo->query("SHOW COLUMNS FROM villages LIKE 'postal_code'")->fetch();
        if ($cols) {
            $hasPostal = true;
        }
        $sql = $hasPostal
            ? 'SELECT id, name AS nama, postal_code AS kodepos FROM villages WHERE district_id = :did ORDER BY name ASC'
            : 'SELECT id, name AS nama FROM villages WHERE district_id = :did ORDER BY name ASC';
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':did' => $district_id]);
        $rows = $stmt->fetchAll();
        if ($hasPostal) {
            foreach ($rows as &$r) {
                if (!isset($r['kodepos'])) $r['kodepos'] = '';
            }
        }
        return $rows;
    }

    // Validasi apakah provinsi ada
    public static function validateProvince(int $id): bool
    {
        $pdo = self::conn();
        $stmt = $pdo->prepare('SELECT id FROM provinces WHERE id = :id LIMIT 1');
        $stmt->execute([':id' => $id]);
        return (bool)$stmt->fetch();
    }

    // Validasi apakah regency ada dan sesuai province
    public static function validateRegency(int $id, int $province_id): bool
    {
        $pdo = self::conn();
        $stmt = $pdo->prepare('SELECT id FROM regencies WHERE id = :id AND province_id = :pid LIMIT 1');
        $stmt->execute([':id' => $id, ':pid' => $province_id]);
        return (bool)$stmt->fetch();
    }

    // Validasi apakah district ada dan sesuai regency
    public static function validateDistrict(int $id, int $regency_id): bool
    {
        $pdo = self::conn();
        $stmt = $pdo->prepare('SELECT id FROM districts WHERE id = :id AND regency_id = :rid LIMIT 1');
        $stmt->execute([':id' => $id, ':rid' => $regency_id]);
        return (bool)$stmt->fetch();
    }

    // Validasi apakah village ada dan sesuai district
    public static function validateVillage(int $id, int $district_id): bool
    {
        $pdo = self::conn();
        $stmt = $pdo->prepare('SELECT id FROM villages WHERE id = :id AND district_id = :did LIMIT 1');
        $stmt->execute([':id' => $id, ':did' => $district_id]);
        return (bool)$stmt->fetch();
    }

    // Mendapatkan nama provinsi berdasarkan id
    public static function getProvinceName(int $id): ?string
    {
        $pdo = self::conn();
        $stmt = $pdo->prepare('SELECT name FROM provinces WHERE id = :id LIMIT 1');
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch();
        return $row ? $row['name'] : null;
    }

    // Mendapatkan nama regency berdasarkan id
    public static function getRegencyName(int $id): ?string
    {
        $pdo = self::conn();
        $stmt = $pdo->prepare('SELECT name FROM regencies WHERE id = :id LIMIT 1');
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch();
        return $row ? $row['name'] : null;
    }

    // Mendapatkan nama district berdasarkan id
    public static function getDistrictName(int $id): ?string
    {
        $pdo = self::conn();
        $stmt = $pdo->prepare('SELECT name FROM districts WHERE id = :id LIMIT 1');
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch();
        return $row ? $row['name'] : null;
    }

    // Mendapatkan nama village berdasarkan id
    public static function getVillageName(int $id): ?string
    {
        $pdo = self::conn();
        $stmt = $pdo->prepare('SELECT name FROM villages WHERE id = :id LIMIT 1');
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch();
        return $row ? $row['name'] : null;
    }
}
