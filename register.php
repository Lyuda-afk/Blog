<?php
// register.php
require_once 'User.php';
require_once 'Auth.php';

$user = new User();
$message = '';
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'username' => $_POST['username'] ?? '',
        'email' => $_POST['email'] ?? '',
        'password' => $_POST['password'] ?? '',
        'confirm_password' => $_POST['confirm_password'] ?? '',
        'full_name' => $_POST['full_name'] ?? ''
    ];
    
    $result = $user->register($data);
    
    if ($result['success']) {
        // Ավտոմատ մուտք գործել գրանցվելուց հետո
        $auth = new Auth();
        $loginResult = $auth->login($data['email'], $data['password']);
        
        if ($loginResult['success']) {
            header('Location: index.php');
            exit();
        }
    } else {
        $message = implode('<br>', $result['errors']);
    }
}
?>
<!DOCTYPE html>
<html lang="hy" class="dark-mode">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Գրանցում - NEXUS</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700&family=Syne:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --neon-blue: #00f3ff;
            --neon-purple: #b967ff;
            --neon-pink: #ff2d95;
            --cyber-green: #00ff9d;
            --dark-bg: #0a0a0f;
            --card-bg: rgba(20, 20, 30, 0.9);
            --text-primary: #ffffff;
            --text-secondary: #b0b0c0;
            --text-tertiary: #8888a0;
            
            --shadow-neon: 0 0 20px rgba(0, 243, 255, 0.3),
                           0 0 40px rgba(0, 243, 255, 0.2);
            --gradient-neon: linear-gradient(135deg, var(--neon-blue), var(--neon-purple));
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Space Grotesk', sans-serif;
            background: var(--dark-bg);
            color: var(--text-primary);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
            position: relative;
            overflow-x: hidden;
        }

        .register-container {
            width: 100%;
            max-width: 450px;
        }

        .register-card {
            background: var(--card-bg);
            backdrop-filter: blur(10px);
            border-radius: 16px;
            border: 1px solid rgba(0, 243, 255, 0.1);
            padding: 40px;
            box-shadow: var(--shadow-neon);
        }

        .register-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: var(--gradient-neon);
        }

        .logo-section {
            text-align: center;
            margin-bottom: 30px;
        }

        .logo-icon {
            width: 60px;
            height: 60px;
            background: var(--gradient-neon);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            margin: 0 auto 15px;
        }

        .logo-text {
            font-family: 'Syne', sans-serif;
            font-size: 2rem;
            font-weight: 800;
            background: var(--gradient-neon);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 5px;
        }

        .message {
            padding: 12px;
            margin-bottom: 20px;
            border-radius: 8px;
            text-align: center;
            font-size: 0.9rem;
        }

        .error {
            background: rgba(255, 45, 149, 0.1);
            color: var(--neon-pink);
            border: 1px solid rgba(255, 45, 149, 0.3);
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            margin-bottom: 8px;
            color: var(--text-primary);
            font-weight: 600;
        }

        .form-input {
            width: 100%;
            padding: 12px 15px;
            background: rgba(0, 243, 255, 0.05);
            border: 1px solid rgba(0, 243, 255, 0.2);
            border-radius: 8px;
            color: var(--text-primary);
            font-size: 1rem;
            transition: all 0.3s;
        }

        .form-input:focus {
            outline: none;
            border-color: var(--neon-blue);
            box-shadow: 0 0 0 3px rgba(0, 243, 255, 0.1);
        }

        .btn {
            display: block;
            width: 100%;
            padding: 14px;
            background: var(--gradient-neon);
            color: var(--dark-bg);
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-neon);
        }

        .login-link {
            text-align: center;
            margin-top: 20px;
            color: var(--text-secondary);
        }

        .login-link a {
            color: var(--neon-blue);
            text-decoration: none;
        }

        .login-link a:hover {
            text-decoration: underline;
        }

        /* Scrollbar styling */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: rgba(0, 243, 255, 0.1);
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb {
            background: var(--gradient-neon);
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--neon-purple);
        }
    </style>
</head>
<body>
    <div class="register-container">
        <div class="register-card">
            <div class="logo-section">
                <div class="logo-icon">
                    <i class="fas fa-user-plus"></i>
                </div>
                <h1 class="logo-text">ՆՈՐ ՀԱՇԻՎ</h1>
            </div>
            
            <?php if ($message): ?>
                <div class="message error"><?php echo $message; ?></div>
            <?php endif; ?>
            
            <form method="POST" action="">
                <div class="form-group">
                    <label for="full_name" class="form-label">Անուն Ազգանուն</label>
                    <input type="text" id="full_name" name="full_name" 
                           class="form-input"
                           value="<?php echo htmlspecialchars($_POST['full_name'] ?? ''); ?>" 
                           placeholder="Ձեր անուն ազգանունը">
                </div>
                
                <div class="form-group">
                    <label for="username" class="form-label">Օգտանուն</label>
                    <input type="text" id="username" name="username" 
                           class="form-input"
                           value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>" 
                           placeholder="Ընտրեք օգտանուն" required>
                </div>
                
                <div class="form-group">
                    <label for="email" class="form-label">Էլ․ Փոստ</label>
                    <input type="email" id="email" name="email" 
                           class="form-input"
                           value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>" 
                           placeholder="Ձեր էլ․ փոստը" required>
                </div>
                
                <div class="form-group">
                    <label for="password" class="form-label">Գաղտնաբառ</label>
                    <input type="password" id="password" name="password" 
                           class="form-input"
                           placeholder="Գաղտնաբառ (առնվազն 6 նիշ)" required>
                </div>
                
                <div class="form-group">
                    <label for="confirm_password" class="form-label">Հաստատել Գաղտնաբառը</label>
                    <input type="password" id="confirm_password" name="confirm_password" 
                           class="form-input"
                           placeholder="Կրկնեք գաղտնաբառը" required>
                </div>
                
                <button type="submit" class="btn">Գրանցվել</button>
            </form>
            
            <div class="login-link">
                Արդեն հաշիվ ունե՞ք։ <a href="login.php">Մուտք գործել</a>
            </div>
        </div>
    </div>
</body>
</html>