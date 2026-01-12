<?php
class DepartmentManager {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    public function getAllDepartments() {
        $sql = "SELECT d.*, 
                (SELECT COUNT(*) FROM contacts WHERE department_id = d.id) as contact_count
                FROM departments d 
                ORDER BY name";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    public function getDepartmentById($id) {
        $sql = "SELECT * FROM departments WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }
    
    public function createDepartment($data) {
        $sql = "INSERT INTO departments (name, description, parent_id) 
                VALUES (:name, :description, :parent_id)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':name' => $data['name'],
            ':description' => $data['description'] ?? null,
            ':parent_id' => $data['parent_id'] ?: null
        ]);
        return $this->db->lastInsertId();
    }
    
    public function updateDepartment($id, $data) {
        $sql = "UPDATE departments SET 
                name = :name, 
                description = :description, 
                parent_id = :parent_id,
                updated_at = CURRENT_TIMESTAMP 
                WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':id' => $id,
            ':name' => $data['name'],
            ':description' => $data['description'] ?? null,
            ':parent_id' => $data['parent_id'] ?: null
        ]);
    }
    
    public function deleteDepartment($id) {
        // First, set contacts department_id to NULL
        $sql = "UPDATE contacts SET department_id = NULL WHERE department_id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        
        // Then delete the department
        $sql = "DELETE FROM departments WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
    
    public function getContactCount($departmentId) {
        $sql = "SELECT COUNT(*) as count FROM contacts WHERE department_id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $departmentId]);
        return $stmt->fetchColumn();
    }
}
?>