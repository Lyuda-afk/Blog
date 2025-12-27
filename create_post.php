<?php
// create_post.php
require_once 'Auth.php';
require_once 'Post.php';
require_once 'Category.php';

// Ստուգում ենք, արդյոք օգտատերը մուտք է գործել
Auth::requireLogin();

$post = new Post();
$category = new Category();
$categories = $category->getAll();
$currentUser = Auth::getCurrentUser();

$message = '';
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'title' => $_POST['title'] ?? '',
        'content' => $_POST['content'] ?? '',
        'image_url' => $_POST['image_url'] ?? '',
        'user_id' => $currentUser['id'],
        'category_id' => $_POST['category_id'] ?? null
    ];
    
    $result = $post->create($data);
    
    if ($result['success']) {
        $message = 'Գրառումը հաջողությամբ ստեղծված է!';
        $success = true;
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
    <title>Նոր գրառում - NEXUS</title>
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
            padding: 20px;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            background: var(--card-bg);
            backdrop-filter: blur(10px);
            border-radius: 16px;
            border: 1px solid rgba(0, 243, 255, 0.1);
            box-shadow: var(--shadow-neon);
            overflow: hidden;
        }

        .header {
            background: var(--gradient-neon);
            color: var(--dark-bg);
            padding: 25px;
            text-align: center;
        }

        .header h1 {
            font-family: 'Syne', sans-serif;
            font-size: 28px;
            margin-bottom: 5px;
            font-weight: 700;
        }

        .header p {
            opacity: 0.9;
            font-size: 14px;
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: var(--dark-bg);
            text-decoration: none;
            margin-bottom: 15px;
            font-size: 14px;
            font-weight: 500;
        }

        .back-link:hover {
            text-decoration: underline;
        }

        .form-container {
            padding: 30px;
        }

        .message {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 8px;
            text-align: center;
            font-weight: 500;
        }

        .success {
            background: rgba(0, 255, 157, 0.1);
            color: var(--cyber-green);
            border: 1px solid rgba(0, 255, 157, 0.3);
        }

        .error {
            background: rgba(255, 45, 149, 0.1);
            color: var(--neon-pink);
            border: 1px solid rgba(255, 45, 149, 0.3);
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: var(--text-primary);
            font-weight: 600;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 14px;
            background: rgba(0, 243, 255, 0.05);
            border: 1px solid rgba(0, 243, 255, 0.2);
            border-radius: 8px;
            color: var(--text-primary);
            font-size: 16px;
            font-family: 'Space Grotesk', sans-serif;
            transition: all 0.3s;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: var(--neon-blue);
            box-shadow: 0 0 0 3px rgba(0, 243, 255, 0.1);
        }

        .form-group textarea {
            min-height: 250px;
            resize: vertical;
        }

        .btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            width: 100%;
            padding: 16px;
            background: var(--gradient-neon);
            color: var(--dark-bg);
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s;
            font-family: 'Space Grotesk', sans-serif;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-neon);
        }

        .preview {
            background: rgba(0, 243, 255, 0.05);
            padding: 15px;
            border-radius: 8px;
            margin-top: 10px;
            border: 1px solid rgba(0, 243, 255, 0.2);
        }

        .preview h4 {
            color: var(--neon-blue);
            margin-bottom: 10px;
        }

        .preview img {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
            border: 1px solid rgba(0, 243, 255, 0.3);
        }

        .char-count {
            margin-top: 5px;
            font-size: 14px;
            color: var(--text-tertiary);
            text-align: right;
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
    <div class="container">
        <div class="header">
            <a href="index.php" class="back-link">
                <i class="fas fa-arrow-left"></i> Գլխավոր էջ
            </a>
            <h1>ՆՈՐ ԳՐԱՌՈՒՄ</h1>
            <p>Կիսվեք ձեր գաղափարներով ապագայի համայնքի հետ</p>
        </div>
        
        <div class="form-container">
            <?php if ($message): ?>
                <div class="message <?php echo $success ? 'success' : 'error'; ?>">
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>
            
            <form method="POST" action="">
                <div class="form-group">
                    <label for="title">Վերնագիր *</label>
                    <input type="text" id="title" name="title" 
                           placeholder="Ձեր գրառման վերնագիրը" 
                           value="<?php echo htmlspecialchars($_POST['title'] ?? ''); ?>" 
                           required>
                </div>
                
                <div class="form-group">
                    <label for="category_id">Կատեգորիա</label>
                    <select id="category_id" name="category_id">
                        <option value="">Ընտրեք կատեգորիան</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?php echo $cat['id']; ?>" 
                                <?php echo (isset($_POST['category_id']) && $_POST['category_id'] == $cat['id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($cat['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="image_url">Պատկերի հղում</label>
                    <input type="url" id="image_url" name="image_url" 
                           placeholder="https://example.com/image.jpg"
                           value="<?php echo htmlspecialchars($_POST['image_url'] ?? ''); ?>">
                    <?php if (!empty($_POST['image_url'])): ?>
                        <div class="preview">
                            <h4>Պատկերի նախադիտում</h4>
                            <img src="<?php echo htmlspecialchars($_POST['image_url']); ?>" 
                                 alt="Preview" id="imagePreview">
                        </div>
                    <?php endif; ?>
                </div>
                
                <div class="form-group">
                    <label for="content">Բովանդակություն *</label>
                    <textarea id="content" name="content" 
                              placeholder="Ձեր գրառման բովանդակությունը..." 
                              required><?php echo htmlspecialchars($_POST['content'] ?? ''); ?></textarea>
                    <div class="char-count" id="charCount">Նիշեր: 0</div>
                </div>
                
                <button type="submit" class="btn">
                    <i class="fas fa-paper-plane"></i> ՀՐԱՊԱՐԱԿԵԼ
                </button>
            </form>
        </div>
    </div>

    <script>
        // Պատկերի նախադիտում
        const imageUrlInput = document.getElementById('image_url');
        const imagePreview = document.getElementById('imagePreview');
        
        imageUrlInput.addEventListener('input', function() {
            const url = this.value;
            if (url) {
                if (!imagePreview) {
                    const previewDiv = document.createElement('div');
                    previewDiv.className = 'preview';
                    previewDiv.innerHTML = `
                        <h4>Պատկերի նախադիտում</h4>
                        <img src="${url}" alt="Preview" id="imagePreview">
                    `;
                    imageUrlInput.parentNode.appendChild(previewDiv);
                } else {
                    imagePreview.src = url;
                }
            }
        });
        
        // Բովանդակության երկարության ցուցում
        const contentTextarea = document.getElementById('content');
        const charCount = document.getElementById('charCount');
        
        function updateCharCount() {
            const length = contentTextarea.value.length;
            charCount.textContent = `Նիշեր: ${length}`;
            
            if (length > 1000) {
                charCount.style.color = 'var(--neon-pink)';
            } else if (length > 500) {
                charCount.style.color = 'var(--neon-purple)';
            } else {
                charCount.style.color = 'var(--cyber-green)';
            }
        }
        
        contentTextarea.addEventListener('input', updateCharCount);
        updateCharCount(); // Initial count
        
        // Form validation
        const form = document.querySelector('form');
        form.addEventListener('submit', function(e) {
            const title = document.getElementById('title').value;
            const content = document.getElementById('content').value;
            
            if (title.length < 3) {
                e.preventDefault();
                alert('Վերնագիրը պետք է պարունակի առնվազն 3 նիշ');
                return;
            }
            
            if (content.length < 10) {
                e.preventDefault();
                alert('Բովանդակությունը պետք է պարունակի առնվազն 10 նիշ');
                return;
            }
        });
    </script>
</body>
</html>