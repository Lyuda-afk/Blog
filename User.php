<?php
// User.php
require_once 'Database.php';

class User {
    private $db;
    private $table = 'users';
    
    public function __construct() {
        $database = Database::getInstance();
        $this->db = $database->getConnection();
    }
    
    // Գրանցում
    public function register($data) {
        $errors = [];
        
        // Վալիդացիա
        if (empty($data['username'])) {
            $errors[] = 'Օգտանունը պարտադիր է';
        }
        
        if (empty($data['email'])) {
            $errors[] = 'Էլ․ փոստը պարտադիր է';
        } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Անվավեր էլ․ փոստի հասցե';
        }
        
        if (empty($data['password'])) {
            $errors[] = 'Գաղտնաբառը պարտադիր է';
        } elseif (strlen($data['password']) < 6) {
            $errors[] = 'Գաղտնաբառը պետք է լինի առնվազն 6 նիշ';
        } elseif ($data['password'] !== $data['confirm_password']) {
            $errors[] = 'Գաղտնաբառերը չեն համընկնում';
        }
        
        // Ստուգում ենք, արդյոք օգտատերը գոյություն ունի
        if (empty($errors)) {
            $stmt = $this->db->prepare("SELECT id FROM {$this->table} WHERE username = ? OR email = ?");
            $stmt->execute([$data['username'], $data['email']]);
            
            if ($stmt->rowCount() > 0) {
                $errors[] = 'Օգտանունը կամ էլ․ փոստն արդեն գոյություն ունի';
            }
        }
        
        // Եթե սխալ չկա, գրանցում ենք
        if (empty($errors)) {
            $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);
            
            $stmt = $this->db->prepare(
                "INSERT INTO {$this->table} (username, email, password, full_name) 
                 VALUES (?, ?, ?, ?)"
            );
            
            $success = $stmt->execute([
                $data['username'],
                $data['email'],
                $hashedPassword,
                $data['full_name']
            ]);
            
            if ($success) {
                return ['success' => true, 'user_id' => $this->db->lastInsertId()];
            } else {
                $errors[] = 'Գրանցման սխալ';
            }
        }
        
        return ['success' => false, 'errors' => $errors];
    }
    
    // Մուտք
    public function login($email, $password) {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        
        if ($user && password_verify($password, $user['password'])) {
            // Հեռացնում ենք գաղտնաբառը
            unset($user['password']);
            return ['success' => true, 'user' => $user];
        }
        
        return ['success' => false, 'error' => 'Սխալ էլ․ փոստ կամ գաղտնաբառ'];
    }
    
    // Ստանալ օգտատիրոջ տվյալները ID-ով
    public function getUserById($id) {
        $stmt = $this->db->prepare("SELECT id, username, email, full_name, created_at FROM {$this->table} WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    
    // Ստուգել, արդյոք օգտատերը ադմին է
    public function isAdmin($userId) {
        $stmt = $this->db->prepare("SELECT is_admin FROM {$this->table} WHERE id = ?");
        $stmt->execute([$userId]);
        $result = $stmt->fetch();
        return $result && $result['is_admin'] == 1;
    }
}
?>