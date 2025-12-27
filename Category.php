<?php
// Category.php
require_once 'Database.php';

class Category {
    private $db;
    private $table = 'categories';
    
    public function __construct() {
        $database = Database::getInstance();
        $this->db = $database->getConnection();
    }
    
    // Ստանալ բոլոր կատեգորիաները
    public function getAll() {
        $stmt = $this->db->query("
            SELECT c.*, COUNT(p.id) as post_count 
            FROM {$this->table} c
            LEFT JOIN posts p ON c.id = p.category_id
            GROUP BY c.id
            ORDER BY c.name
        ");
        return $stmt->fetchAll();
    }
    
    // Ստանալ կատեգորիան ID-ով
    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    
    // Ստեղծել նոր կատեգորիա (ադմինների համար)
    public function create($name, $description = '') {
        $stmt = $this->db->prepare("INSERT INTO {$this->table} (name, description) VALUES (?, ?)");
        return $stmt->execute([$name, $description]);
    }
}
?>