<?php
// login.php
require_once 'Auth.php';

$message = '';

// Եթե օգտատերը արդեն մուտք է գործել, ուղղորդում ենք գլխավոր էջ
if (Auth::isLoggedIn()) {
    header('Location: index.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    
    $auth = new Auth();
    $result = $auth->login($email, $password);
    
    if ($result['success']) {
        header('Location: index.php');
        exit();
    } else {
        $message = $result['error'];
    }
}
?>
<!DOCTYPE html>
<html lang="hy" class="dark-mode">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Մուտք - NEXUS</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700&family=Syne:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --neon-blue: #00f3ff;
            --neon-purple: #b967ff;
            --neon-pink: #ff2d95;
            --cyber-green: #00ff9d;
            --matrix-green: #00ff41;
            --dark-bg: #0a0a0f;
            --darker-bg: #050508;
            --card-bg: rgba(20, 20, 30, 0.9);
            --glass-bg: rgba(30, 30, 40, 0.6);
            --border-glow: rgba(0, 243, 255, 0.3);
            --text-primary: #ffffff;
            --text-secondary: #b0b0c0;
            --text-tertiary: #8888a0;
            
            --shadow-neon: 0 0 20px rgba(0, 243, 255, 0.3),
                           0 0 40px rgba(0, 243, 255, 0.2),
                           0 0 60px rgba(0, 243, 255, 0.1);
            --shadow-purple: 0 0 20px rgba(185, 103, 255, 0.3),
                             0 0 40px rgba(185, 103, 255, 0.2);
            --shadow-pink: 0 0 20px rgba(255, 45, 149, 0.3),
                           0 0 40px rgba(255, 45, 149, 0.2);
            
            --gradient-neon: linear-gradient(135deg, var(--neon-blue), var(--neon-purple));
            --gradient-cyber: linear-gradient(135deg, var(--neon-pink), var(--neon-blue));
            --gradient-matrix: linear-gradient(135deg, var(--matrix-green), var(--cyber-green));
            
            --radius-sm: 4px;
            --radius-md: 8px;
            --radius-lg: 12px;
            --radius-xl: 16px;
            --radius-full: 50px;
            
            --transition-normal: 0.3s cubic-bezier(0.4, 0, 0.2, 1);
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
            overflow: hidden;
            background-image: 
                radial-gradient(circle at 10% 20%, rgba(0, 243, 255, 0.05) 0%, transparent 20%),
                radial-gradient(circle at 90% 80%, rgba(185, 103, 255, 0.05) 0%, transparent 20%),
                radial-gradient(circle at 50% 50%, rgba(255, 45, 149, 0.03) 0%, transparent 30%);
        }

        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: 
                repeating-linear-gradient(
                    0deg,
                    transparent,
                    transparent 1px,
                    rgba(0, 243, 255, 0.02) 1px,
                    rgba(0, 243, 255, 0.02) 2px
                );
            pointer-events: none;
            z-index: -1;
        }

        .login-container {
            width: 100%;
            max-width: 450px;
            position: relative;
            z-index: 2;
        }

        .login-card {
            background: var(--card-bg);
            backdrop-filter: blur(20px);
            border-radius: var(--radius-xl);
            border: 1px solid rgba(0, 243, 255, 0.1);
            padding: 50px 40px;
            box-shadow: var(--shadow-neon);
            transition: all var(--transition-normal);
            position: relative;
            overflow: hidden;
        }

        .login-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: var(--gradient-neon);
            z-index: 1;
        }

        .login-card:hover {
            box-shadow: 
                0 0 30px rgba(0, 243, 255, 0.4),
                0 0 60px rgba(0, 243, 255, 0.2);
            border-color: var(--neon-blue);
        }

        /* Լոգո */
        .logo-section {
            text-align: center;
            margin-bottom: 40px;
        }

        .logo-icon {
            width: 60px;
            height: 60px;
            background: var(--gradient-neon);
            border-radius: var(--radius-md);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            margin: 0 auto 15px;
            position: relative;
            overflow: hidden;
            box-shadow: var(--shadow-neon);
        }

        .logo-icon::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(
                45deg,
                transparent,
                rgba(255, 255, 255, 0.3),
                transparent
            );
            transform: rotate(45deg);
            animation: shine 3s infinite linear;
        }

        @keyframes shine {
            0% { transform: translateX(-100%) rotate(45deg); }
            100% { transform: translateX(100%) rotate(45deg); }
        }

        .logo-text {
            font-family: 'Syne', sans-serif;
            font-size: 2rem;
            font-weight: 800;
            background: var(--gradient-neon);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            letter-spacing: -0.5px;
            margin-bottom: 5px;
        }

        .logo-subtitle {
            color: var(--neon-blue);
            font-size: 0.9rem;
            font-weight: 500;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        /* Հաղորդագրություն */
        .message {
            padding: 12px 20px;
            margin-bottom: 25px;
            border-radius: var(--radius-md);
            text-align: center;
            font-size: 0.9rem;
            font-weight: 500;
            position: relative;
            overflow: hidden;
        }

        .error {
            background: rgba(255, 45, 149, 0.1);
            color: var(--neon-pink);
            border: 1px solid rgba(255, 45, 149, 0.3);
            box-shadow: 0 0 15px rgba(255, 45, 149, 0.2);
        }

        .error::before {
            content: '⚠️';
            margin-right: 8px;
        }

        /* Ձևաթուղթ */
        .form-group {
            margin-bottom: 25px;
        }

        .form-label {
            display: block;
            margin-bottom: 8px;
            color: var(--text-primary);
            font-weight: 600;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .input-wrapper {
            position: relative;
        }

        .form-input {
            width: 100%;
            padding: 15px 20px;
            background: rgba(0, 243, 255, 0.05);
            border: 1px solid rgba(0, 243, 255, 0.2);
            border-radius: var(--radius-lg);
            color: var(--text-primary);
            font-size: 1rem;
            font-family: 'Space Grotesk', sans-serif;
            transition: all var(--transition-normal);
            backdrop-filter: blur(5px);
        }

        .form-input:focus {
            outline: none;
            border-color: var(--neon-blue);
            background: rgba(0, 243, 255, 0.1);
            box-shadow: 
                0 0 0 3px rgba(0, 243, 255, 0.1),
                inset 0 0 10px rgba(0, 243, 255, 0.05);
        }

        .form-input::placeholder {
            color: var(--text-tertiary);
        }

        .input-icon {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--neon-blue);
            font-size: 1rem;
        }

        /* Կոճակ */
        .btn {
            display: block;
            width: 100%;
            padding: 16px;
            background: var(--gradient-neon);
            color: var(--dark-bg);
            border: none;
            border-radius: var(--radius-full);
            font-size: 1rem;
            font-weight: 700;
            font-family: 'Space Grotesk', sans-serif;
            cursor: pointer;
            transition: all var(--transition-normal);
            position: relative;
            overflow: hidden;
            text-transform: uppercase;
            letter-spacing: 1px;
            box-shadow: var(--shadow-neon);
        }

        .btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(
                90deg,
                transparent,
                rgba(255, 255, 255, 0.4),
                transparent
            );
            transition: left 0.7s ease;
        }

        .btn:hover::before {
            left: 100%;
        }

        .btn:hover {
            transform: translateY(-3px);
            box-shadow: 
                0 0 30px rgba(0, 243, 255, 0.4),
                0 0 60px rgba(0, 243, 255, 0.2);
        }

        .btn:active {
            transform: translateY(-1px);
        }

        /* Լինկեր */
        .links-section {
            margin-top: 30px;
            text-align: center;
        }

        .register-link {
            color: var(--text-secondary);
            font-size: 0.9rem;
            margin-bottom: 15px;
        }

        .register-link a {
            color: var(--neon-blue);
            text-decoration: none;
            font-weight: 600;
            transition: all var(--transition-normal);
            position: relative;
        }

        .register-link a::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 0;
            height: 2px;
            background: var(--neon-blue);
            transition: width var(--transition-normal);
        }

        .register-link a:hover::after {
            width: 100%;
        }

        .register-link a:hover {
            color: var(--neon-purple);
        }

        /* Թեստային տվյալներ */
        .test-credentials {
            background: rgba(0, 255, 157, 0.1);
            border: 1px solid rgba(0, 255, 157, 0.3);
            border-radius: var(--radius-md);
            padding: 15px;
            margin-top: 25px;
            text-align: center;
            font-size: 0.85rem;
            box-shadow: 0 0 15px rgba(0, 255, 157, 0.2);
        }

        .test-credentials h4 {
            color: var(--cyber-green);
            margin-bottom: 8px;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .test-credentials p {
            color: var(--text-secondary);
            margin: 5px 0;
            font-family: 'Courier New', monospace;
        }

        /* Code effect */
        .code-effect {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 1;
        }

        .code-line {
            position: absolute;
            color: rgba(0, 255, 65, 0.05);
            font-family: 'Courier New', monospace;
            font-size: 14px;
            animation: fall linear infinite;
            opacity: 0.1;
        }

        @keyframes fall {
            from { transform: translateY(-100px); }
            to { transform: translateY(100vh); }
        }

        /* Responsive */
        @media (max-width: 768px) {
            body {
                padding: 15px;
            }
            
            .login-card {
                padding: 40px 30px;
            }
            
            .logo-text {
                font-size: 1.8rem;
            }
        }

        @media (max-width: 480px) {
            .login-card {
                padding: 30px 20px;
            }
            
            .logo-text {
                font-size: 1.5rem;
            }
            
            .logo-subtitle {
                font-size: 0.8rem;
            }
        }

        /* Ripple effect */
        .ripple {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.6);
            transform: scale(0);
            animation: ripple-animation 0.6s linear;
        }

        @keyframes ripple-animation {
            to {
                transform: scale(4);
                opacity: 0;
            }
        }
    </style>
</head>
<body>
    <!-- Code effect -->
    <div class="code-effect" id="codeEffect"></div>

    <div class="login-container">
        <div class="login-card">
            <!-- Լոգո -->
            <div class="logo-section">
                <div class="logo-icon">
                    <i class="fas fa-bolt"></i>
                </div>
                <h1 class="logo-text">NEXUS</h1>
                <p class="logo-subtitle">SYSTEM ACCESS</p>
            </div>
            
            <!-- Հաղորդագրություն -->
            <?php if ($message): ?>
                <div class="message error"><?php echo $message; ?></div>
            <?php endif; ?>
            
            <!-- Մուտքի ձևաթուղթ -->
            <form method="POST" action="" id="loginForm">
                <div class="form-group">
                    <label for="email" class="form-label">
                        <i class="fas fa-user"></i> Էլ․ Փոստ
                    </label>
                    <div class="input-wrapper">
                        <input type="email" id="email" name="email" 
                               class="form-input"
                               value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>" 
                               placeholder="username@nexus.am"
                               required>
                        <span class="input-icon">
                            <i class="fas fa-at"></i>
                        </span>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="password" class="form-label">
                        <i class="fas fa-key"></i> Գաղտնաբառ
                    </label>
                    <div class="input-wrapper">
                        <input type="password" id="password" name="password" 
                               class="form-input"
                               placeholder="••••••••"
                               required>
                        <span class="input-icon">
                            <i class="fas fa-lock"></i>
                        </span>
                    </div>
                </div>
                
                <button type="submit" class="btn" id="submitBtn">
                    ՄՈՒՏՔ ԳՈՐԾԵԼ
                </button>
            </form>
            
            <!-- Լինկեր -->
            <div class="links-section">
                <p class="register-link">
                    Նոր օգտատեր եք? <a href="register.php">Ստեղծել հաշիվ</a>
                </p>
                <p class="register-link">
                    <a href="index.php">
                        <i class="fas fa-arrow-left"></i> Վերադառնալ գլխավոր էջ
                    </a>
                </p>
            </div>
            
        </div>
    </div>

    <script>
        // Matrix code effect
        const codeEffect = document.getElementById('codeEffect');
        const characters = '01';
        
        function createCodeLine() {
            const line = document.createElement('div');
            line.className = 'code-line';
            line.style.left = Math.random() * 100 + 'vw';
            line.style.animationDuration = (Math.random() * 5 + 3) + 's';
            line.style.opacity = Math.random() * 0.05 + 0.02;
            line.style.fontSize = (Math.random() * 8 + 10) + 'px';
            
            let code = '';
            const length = Math.floor(Math.random() * 20) + 10;
            for (let i = 0; i < length; i++) {
                code += characters.charAt(Math.floor(Math.random() * characters.length));
            }
            line.textContent = code;
            
            codeEffect.appendChild(line);
            
            setTimeout(() => {
                line.remove();
            }, parseFloat(line.style.animationDuration) * 1000);
        }
        
        // Create initial code lines
        for (let i = 0; i < 15; i++) {
            setTimeout(() => createCodeLine(), i * 300);
        }
        
        // Continue creating code lines
        setInterval(createCodeLine, 300);

        // Ripple effect on button click
        const submitBtn = document.getElementById('submitBtn');
        submitBtn.addEventListener('click', function(e) {
            const rect = this.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;
            
            const ripple = document.createElement('span');
            ripple.className = 'ripple';
            ripple.style.left = x + 'px';
            ripple.style.top = y + 'px';
            
            this.appendChild(ripple);
            
            setTimeout(() => {
                ripple.remove();
            }, 600);
        });

        // Form submission animation
        const loginForm = document.getElementById('loginForm');
        loginForm.addEventListener('submit', function(e) {
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            
            if (email && password) {
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> ՄՈՒՏՔԵՑՈՒՄ...';
                submitBtn.disabled = true;
                setTimeout(() => {
                    submitBtn.innerHTML = '<i class="fas fa-check"></i> ՄՈՒՏՔԵՑՎԱԾ Է';
                    submitBtn.style.background = 'var(--gradient-matrix)';
                }, 1000);
            }
        });

        // Input focus effects
        const inputs = document.querySelectorAll('.form-input');
        inputs.forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.style.transform = 'scale(1.02)';
            });
            
            input.addEventListener('blur', function() {
                this.parentElement.style.transform = 'scale(1)';
            });
        });

        // Keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            // Ctrl + Enter to submit
            if (e.ctrlKey && e.key === 'Enter') {
                loginForm.submit();
            }
            
            // Escape to focus email field
            if (e.key === 'Escape') {
                document.getElementById('email').focus();
            }
        });
    </script>
</body>
</html>