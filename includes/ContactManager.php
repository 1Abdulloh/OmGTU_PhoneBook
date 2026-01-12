<?php
class ContactManager {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    public function getAllContacts($limit = 100, $offset = 0) {
        $sql = "SELECT c.*, d.name as department_name 
            FROM contacts c 
            LEFT JOIN departments d ON c.department_id = d.id 
            ORDER BY c.name 
            LIMIT :limit OFFSET :offset";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    public function getContactsByDepartment($departmentId) {
        $sql = "SELECT * FROM contacts WHERE department_id = :id ORDER BY name";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $departmentId]);
        return $stmt->fetchAll();
    }
    
    public function getContactById($id) {
        $sql = "SELECT c.*, d.name as department_name 
                FROM contacts c 
                LEFT JOIN departments d ON c.department_id = d.id 
                WHERE c.id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }
    
    public function searchContacts($query, $departmentId = null) {
        $sql = "SELECT c.*, d.name as department_name 
                FROM contacts c 
                LEFT JOIN departments d ON c.department_id = d.id 
                WHERE (LOWER(c.name) LIKE LOWER(:query) 
                   OR LOWER(c.position) LIKE LOWER(:query) 
                   OR c.phone LIKE :query 
                   OR LOWER(c.email) LIKE LOWER(:query) 
                   OR LOWER(d.name) LIKE LOWER(:query))";
        
        $params = [':query' => "%$query%"];
        
        if ($departmentId) {
            $sql .= " AND c.department_id = :dept_id";
            $params[':dept_id'] = $departmentId;
        }
        
        $sql .= " ORDER BY c.name";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
    
    public function getTotalContacts() {
        $sql = "SELECT COUNT(*) as total FROM contacts";
        $stmt = $this->db->query($sql);
        return $stmt->fetchColumn();
    }
    
    public function createContact($data) {
        $sql = "INSERT INTO contacts (name, position, department_id, phone, email, room, internal_phone) 
                VALUES (:name, :position, :department_id, :phone, :email, :room, :internal_phone)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':name' => $data['name'],
            ':position' => $data['position'],
            ':department_id' => $data['department_id'] ?: null,
            ':phone' => $data['phone'],
            ':email' => $data['email'],
            ':room' => $data['room'] ?? null,
            ':internal_phone' => $data['internal_phone'] ?? null
        ]);
        return $this->db->lastInsertId();
    }
    
    public function updateContact($id, $data) {
        $sql = "UPDATE contacts SET 
                name = :name, 
                position = :position, 
                department_id = :department_id, 
                phone = :phone, 
                email = :email,
                room = :room,
                internal_phone = :internal_phone,
                updated_at = CURRENT_TIMESTAMP 
                WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':id' => $id,
            ':name' => $data['name'],
            ':position' => $data['position'],
            ':department_id' => $data['department_id'] ?: null,
            ':phone' => $data['phone'],
            ':email' => $data['email'],
            ':room' => $data['room'] ?? null,
            ':internal_phone' => $data['internal_phone'] ?? null
        ]);
    }
    
    public function deleteContact($id) {
        $sql = "DELETE FROM contacts WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
}
?>