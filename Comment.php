<?php
// Comment.php
require_once 'Database.php';

class Comment {
    private $db;
    private $table = 'comments';
    
    public function __construct() {
        $database = Database::getInstance();
        $this->db = $database->getConnection();
    }
    
    // Ավելացնել մեկնաբանություն
    public function create($postId, $userId, $content) {
        if (empty($content)) {
            return ['success' => false, 'error' => 'Մեկնաբանությունը դատարկ է'];
        }
        
        $stmt = $this->db->prepare(
            "INSERT INTO {$this->table} (post_id, user_id, content) VALUES (?, ?, ?)"
        );
        
        $success = $stmt->execute([$postId, $userId, $content]);
        
        if ($success) {
            return ['success' => true, 'comment_id' => $this->db->lastInsertId()];
        }
        
        return ['success' => false, 'error' => 'Մեկնաբանության ավելացման սխալ'];
    }
    
    // Ստանալ գրառման մեկնաբանությունները
    public function getByPost($postId) {
        $stmt = $this->db->prepare("
            SELECT c.*, u.username, u.full_name 
            FROM {$this->table} c
            JOIN users u ON c.user_id = u.id
            WHERE c.post_id = ? AND c.approved = TRUE
            ORDER BY c.created_at ASC
        ");
        $stmt->execute([$postId]);
        return $stmt->fetchAll();
    }
    
    // Ստանալ մեկնաբանությունը ID-ով
    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    
    // Ջնջել մեկնաբանությունը
    public function delete($commentId, $userId) {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id = ? AND user_id = ?");
        return $stmt->execute([$commentId, $userId]);
    }
}
?>