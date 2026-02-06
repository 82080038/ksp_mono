<?php
class Auth
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::conn();
    }

    public function login(string $username, string $password): bool
    {
        $stmt = $this->db->prepare('SELECT id, username, password_hash, status FROM pengguna WHERE username = :u LIMIT 1');
        $stmt->execute([':u' => $username]);
        $user = $stmt->fetch();
        if (!$user) {
            return false;
        }
        if (!password_verify($password, $user['password_hash'])) {
            return false;
        }
        if (isset($user['status']) && $user['status'] !== 'active') {
            return false;
        }
        $_SESSION['user'] = [
            'id' => $user['id'],
            'username' => $user['username'],
            'status' => $user['status'] ?? 'active',
        ];
        return true;
    }

    public function check(): bool
    {
        return isset($_SESSION['user']);
    }

    public function user(): ?array
    {
        return $_SESSION['user'] ?? null;
    }

    public function logout(): void
    {
        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
        }
        session_destroy();
    }
}
