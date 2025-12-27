<?php
// Auth.php
require_once 'User.php';

class Auth {
    private $user;
    
    public function __construct() {
        $this->user = new User();
    }
    
    // Մուտք գործել
    public function login($email, $password) {
        $result = $this->user->login($email, $password);
        
        if ($result['success']) {
            session_start();
            $_SESSION['user_id'] = $result['user']['id'];
            $_SESSION['username'] = $result['user']['username'];
            $_SESSION['email'] = $result['user']['email'];
            $_SESSION['full_name'] = $result['user']['full_name'];
            $_SESSION['is_admin'] = $result['user']['is_admin'];
            
            return ['success' => true];
        }
        
        return $result;
    }
    
    // Դուրս գալ
    public function logout() {
        session_start();
        session_unset();
        session_destroy();
        return true;
    }
    
    // Ստուգել, արդյոք օգտատերը մուտք է գործել
    public static function isLoggedIn() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        return isset($_SESSION['user_id']);
    }
    
    // Ստուգել, արդյոք օգտատերը ադմին է
    public static function isAdmin() {
        if (self::isLoggedIn()) {
            return isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1;
        }
        return false;
    }
    
    // Ստանալ ներկայիս օգտատիրոջ տվյալները
    public static function getCurrentUser() {
        if (self::isLoggedIn()) {
            return [
                'id' => $_SESSION['user_id'],
                'username' => $_SESSION['username'],
                'email' => $_SESSION['email'],
                'full_name' => $_SESSION['full_name'],
                'is_admin' => $_SESSION['is_admin']
            ];
        }
        return null;
    }
    
    // Վերահղում, եթե մուտք չի գործած
    public static function requireLogin() {
        if (!self::isLoggedIn()) {
            header('Location: login.php');
            exit();
        }       
    }
    
    // Վերահղում, եթե ադմին չէ
    public static function requireAdmin() {
        self::requireLogin();
        if (!self::isAdmin()) {
            header('Location: index.php');
            exit();
        }
    }
}
?>