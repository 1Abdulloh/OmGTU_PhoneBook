<?php
class UserManager {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    public function authenticate($username, $password) {
        $sql = "SELECT * FROM users WHERE username = :username AND is_active = true";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':username' => $username]);
        $user = $stmt->fetch();
        
        if ($user && password_verify($password, $user['password_hash'])) {
            $this->updateLastLogin($user['id']);
            return $user;
        }
        return false;
    }
    
    public function authenticateByYandex($yandexId, $email, $name) {
        // Check if user exists with this yandex_id (любая роль, если уже привязан)
        $sql = "SELECT * FROM users WHERE yandex_id = :yandex_id AND is_active = true";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':yandex_id' => $yandexId]);
        $user = $stmt->fetch();
        
        if ($user) {
            // Проверяем, что пользователь имеет право доступа к админ-панели (только admin)
            if ($user['role'] === 'admin') {
                $this->updateLastLogin($user['id']);
                return $user;
            }
            // Если не admin - возвращаем false
            return false;
        }
        
        // If not, check if user with admin role and matching email exists
        // Проверяем пользователей с ролью admin по email
        $sql = "SELECT * FROM users WHERE email = :email AND role = 'admin' AND is_active = true";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':email' => $email]);
        $user = $stmt->fetch();
        
        if ($user) {
            // Link yandex account to existing user
            $sql = "UPDATE users SET 
                    yandex_id = :yandex_id,
                    yandex_email = :yandex_email,
                    yandex_name = :yandex_name,
                    updated_at = CURRENT_TIMESTAMP 
                    WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':id' => $user['id'],
                ':yandex_id' => $yandexId,
                ':yandex_email' => $email,
                ':yandex_name' => $name
            ]);
            
            $this->updateLastLogin($user['id']);
            return $user;
        }
        
        return false; // Only admins can login via Yandex
    }
    
    private function updateLastLogin($userId) {
        $sql = "UPDATE users SET last_login = CURRENT_TIMESTAMP WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $userId]);
    }
    
    public function getUserById($id) {
        $sql = "SELECT * FROM users WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }
    
    public function getAllUsers() {
        $sql = "SELECT * FROM users ORDER BY role, username";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    public function createUser($data) {
        $sql = "INSERT INTO users (username, password_hash, email, first_name, last_name, role) 
                VALUES (:username, :password_hash, :email, :first_name, :last_name, :role)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':username' => $data['username'],
            ':password_hash' => password_hash($data['password'], PASSWORD_DEFAULT),
            ':email' => $data['email'],
            ':first_name' => $data['first_name'] ?? null,
            ':last_name' => $data['last_name'] ?? null,
            ':role' => $data['role'] ?? 'admin'
        ]);
        return $this->db->lastInsertId();
    }
    
    public function updateUser($id, $data) {
        $sql = "UPDATE users SET 
                username = :username,
                email = :email,
                first_name = :first_name,
                last_name = :last_name,
                role = :role,
                is_active = :is_active,
                updated_at = CURRENT_TIMESTAMP 
                WHERE id = :id";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':id' => $id,
            ':username' => $data['username'],
            ':email' => $data['email'],
            ':first_name' => $data['first_name'] ?? null,
            ':last_name' => $data['last_name'] ?? null,
            ':role' => $data['role'] ?? 'viewer',
            ':is_active' => $data['is_active'] ?? true
        ]);
    }
    
    public function deleteUser($id, $currentUserId = null) {
        // Только главный админ (id=1) может удалять других админов
        // Остальные админы могут удалять только не-админов
        if ($currentUserId === 1) {
            // Главный админ может удалять всех, кроме себя
            $sql = "DELETE FROM users WHERE id = :id AND id != 1";
        } else {
            // Обычные админы могут удалять только не-админов
            $sql = "DELETE FROM users WHERE id = :id AND role != 'admin'";
        }
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
    
    public function changePassword($userId, $newPassword) {
        $sql = "UPDATE users SET 
                password_hash = :password_hash,
                updated_at = CURRENT_TIMESTAMP 
                WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':id' => $userId,
            ':password_hash' => password_hash($newPassword, PASSWORD_DEFAULT)
        ]);
    }
}
?>