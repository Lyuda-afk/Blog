<?php
// Database.php
class Database {
    private static $instance = null;
    private $connection;
    private $host = 'localhost';
    private $dbname = 'blog_system';
    private $username = 'root';
    private $password = '';
    
    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }
    
    private function __construct() {
        try {
            // Ստեղծում ենք կապը առանց հատուկ բազայի ընտրության
            $this->connection = new PDO(
                "mysql:host={$this->host};charset=utf8mb4",
                $this->username,
                $this->password
            );
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            // Ստեղծում կամ ընտրում ենք տվյալների բազան
            $this->initDatabase();
            
        } catch(PDOException $e) {
            die("Տվյալների բազայի սխալ: " . $e->getMessage());
        }
    }
    
    private function initDatabase() {
        // Ստեղծում ենք տվյալների բազան, եթե գոյություն չունի
        $this->connection->exec("CREATE DATABASE IF NOT EXISTS {$this->dbname}");
        $this->connection->exec("USE {$this->dbname}");
        
        // Ստեղծում ենք աղյուսակները
        $this->createTables();
    }
    
    private function createTables() {
        $queries = [
            // Users աղյուսակ
            "CREATE TABLE IF NOT EXISTS users (
                id INT AUTO_INCREMENT PRIMARY KEY,
                username VARCHAR(50) UNIQUE NOT NULL,
                email VARCHAR(100) UNIQUE NOT NULL,
                password VARCHAR(255) NOT NULL,
                full_name VARCHAR(100),
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                is_admin BOOLEAN DEFAULT FALSE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",
            
            // Categories աղյուսակ
            "CREATE TABLE IF NOT EXISTS categories (
                id INT AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(50) UNIQUE NOT NULL,
                description TEXT,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",
            
            // Posts աղյուսակ
            "CREATE TABLE IF NOT EXISTS posts (
                id INT AUTO_INCREMENT PRIMARY KEY,
                title VARCHAR(200) NOT NULL,
                content TEXT NOT NULL,
                excerpt TEXT,
                image_url VARCHAR(500),
                user_id INT,
                category_id INT,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                views INT DEFAULT 0,
                featured BOOLEAN DEFAULT FALSE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",
            
            // Comments աղյուսակ
            "CREATE TABLE IF NOT EXISTS comments (
                id INT AUTO_INCREMENT PRIMARY KEY,
                post_id INT NOT NULL,
                user_id INT NOT NULL,
                content TEXT NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                approved BOOLEAN DEFAULT TRUE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci"
        ];
        
        foreach ($queries as $query) {
            try {
                $this->connection->exec($query);
            } catch(PDOException $e) {
                // Պարզապես անտեսում ենք, եթե աղյուսակն արդեն գոյություն ունի
            }
        }
        
        // Ավելացնում ենք լռելյայն կատեգորիաներ
        $this->seedCategories();
        
        // Ավելացնում ենք լռելյայն ադմին օգտվող
        $this->seedAdminUser();
    }
    
    private function seedCategories() {
        $categories = ['Տեխնոլոգիա', 'Բնություն', 'Սնունդ', 'Արվեստ', 'Արհեստ'];
        $stmt = $this->connection->prepare("INSERT IGNORE INTO categories (name) VALUES (?)");
        
        foreach ($categories as $category) {
            $stmt->execute([$category]);
        }
    }
    
    private function seedAdminUser() {
        $check = $this->connection->query("SELECT id FROM users WHERE email = 'admin@blog.am'");
        if ($check->rowCount() == 0) {
            $hashedPassword = password_hash('admin123', PASSWORD_DEFAULT);
            $stmt = $this->connection->prepare(
                "INSERT INTO users (username, email, password, full_name, is_admin) 
                 VALUES (?, ?, ?, ?, ?)"
            );
            $stmt->execute(['admin', 'admin@blog.am', $hashedPassword, 'Համակարգի ադմին', 1]);
        }
    }
    
    public function getConnection() {
        return $this->connection;
    }
}
?>