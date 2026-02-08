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
        $stmt = $this->db->prepare('SELECT id, username, sandi_hash, status FROM pengguna WHERE username = :u LIMIT 1');
        $stmt->execute([':u' => $username]);
        $user = $stmt->fetch();
        if (!$user) {
            return false;
        }
        if (!password_verify($password, $user['sandi_hash'])) {
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
        // Check both old session format and new registration session format
        return isset($_SESSION['user']) || (isset($_SESSION['user_id']) && isset($_SESSION['username']));
    }

    public function user(): ?array
    {
        // Return user data from session (either login or registration)
        if (isset($_SESSION['user'])) {
            return $_SESSION['user'];
        }
        
        // Fallback to registration session
        if (isset($_SESSION['user_id']) && isset($_SESSION['username'])) {
            return [
                'id' => $_SESSION['user_id'],
                'username' => $_SESSION['username'],
                'nama_lengkap' => $_SESSION['nama_lengkap'] ?? '',
                'hp' => $_SESSION['hp'] ?? '',
                'role' => $_SESSION['role'] ?? 'admin',
                'role_id' => $_SESSION['role_id'] ?? 2,
                'permissions' => $_SESSION['permissions'] ?? [],
                'status' => 'active'
            ];
        }
        
        return null;
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
