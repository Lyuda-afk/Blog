<?php
// Post.php
require_once 'Database.php';

class Post {
    private $db;
    private $table = 'posts';
    
    public function __construct() {
        $database = Database::getInstance();
        $this->db = $database->getConnection();
    }
    
    // Ստեղծել նոր գրառում
    public function create($data) {
        $errors = [];
        
        if (empty($data['title'])) {
            $errors[] = 'Վերնագիրը պարտադիր է';
        }
        
        if (empty($data['content'])) {
            $errors[] = 'Բովանդակությունը պարտադիր է';
        }
        
        if (empty($data['user_id'])) {
            $errors[] = 'Օգտատերը պարտադիր է';
        }
        
        if (empty($errors)) {
            // Ստեղծում ենք except (կարճ նկարագրություն)
            $excerpt = substr(strip_tags($data['content']), 0, 150) . '...';
            
            $stmt = $this->db->prepare(
                "INSERT INTO {$this->table} (title, content, excerpt, image_url, user_id, category_id) 
                 VALUES (?, ?, ?, ?, ?, ?)"
            );
            
            $success = $stmt->execute([
                $data['title'],
                $data['content'],
                $excerpt,
                $data['image_url'] ?? '',
                $data['user_id'],
                $data['category_id'] ?? null
            ]);
            
            if ($success) {
                return ['success' => true, 'post_id' => $this->db->lastInsertId()];
            } else {
                $errors[] = 'Գրառման ստեղծման սխալ';
            }
        }
        
        return ['success' => false, 'errors' => $errors];
    }
    
    // Ստանալ բոլոր գրառումները
    public function getAll($limit = 10, $offset = 0) {
        $stmt = $this->db->prepare("
            SELECT p.*, u.username, u.full_name, c.name as category_name 
            FROM {$this->table} p
            LEFT JOIN users u ON p.user_id = u.id
            LEFT JOIN categories c ON p.category_id = c.id
            ORDER BY p.created_at DESC 
            LIMIT ? OFFSET ?
        ");
        $stmt->bindValue(1, $limit, PDO::PARAM_INT);
        $stmt->bindValue(2, $offset, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }
    
    // Ստանալ գրառումը ID-ով
    public function getById($id) {
        $stmt = $this->db->prepare("
            SELECT p.*, u.username, u.full_name, c.name as category_name 
            FROM {$this->table} p
            LEFT JOIN users u ON p.user_id = u.id
            LEFT JOIN categories c ON p.category_id = c.id
            WHERE p.id = ?
        ");
        $stmt->execute([$id]);
        
        $post = $stmt->fetch();
        
        // Ավելացնում ենք դիտումների քանակը
        if ($post) {
            $this->incrementViews($id);
        }
        
        return $post;
    }
    
    // Ստանալ գրառումները ըստ կատեգորիայի
    public function getByCategory($categoryId, $limit = 10) {
        $stmt = $this->db->prepare("
            SELECT p.*, u.username, u.full_name, c.name as category_name 
            FROM {$this->table} p
            LEFT JOIN users u ON p.user_id = u.id
            LEFT JOIN categories c ON p.category_id = c.id
            WHERE p.category_id = ?
            ORDER BY p.created_at DESC 
            LIMIT ?
        ");
        $stmt->bindValue(1, $categoryId, PDO::PARAM_INT);
        $stmt->bindValue(2, $limit, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }
    
    // Ստանալ հեղինակի գրառումները
    public function getByUser($userId, $limit = 10) {
        $stmt = $this->db->prepare("
            SELECT p.*, u.username, u.full_name, c.name as category_name 
            FROM {$this->table} p
            LEFT JOIN users u ON p.user_id = u.id
            LEFT JOIN categories c ON p.category_id = c.id
            WHERE p.user_id = ?
            ORDER BY p.created_at DESC 
            LIMIT ?
        ");
        $stmt->bindValue(1, $userId, PDO::PARAM_INT);
        $stmt->bindValue(2, $limit, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }
    
    // Դիտումների քանակի ավելացում
    private function incrementViews($postId) {
        $stmt = $this->db->prepare("UPDATE {$this->table} SET views = views + 1 WHERE id = ?");
        $stmt->execute([$postId]);
    }
    
    // Ջնջել գրառումը
    public function delete($postId, $userId) {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id = ? AND user_id = ?");
        return $stmt->execute([$postId, $userId]);
    }
    
    // Ընդհանուր գրառումների քանակ
    public function countAll() {
        $stmt = $this->db->query("SELECT COUNT(*) as total FROM {$this->table}");
        return $stmt->fetch()['total'];
    }
}
?>