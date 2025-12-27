<?php
// view_post.php
require_once 'Auth.php';
require_once 'Post.php';
require_once 'Comment.php';
require_once 'User.php';

// Ստանալ գրառման ID-ը
$postId = $_GET['id'] ?? 0;
if (!$postId) {
    header('Location: index.php');
    exit();
}

$post = new Post();
$postData = $post->getById($postId);
$comment = new Comment();
$user = new User();

if (!$postData) {
    header('Location: index.php');
    exit();
}

// Մեկնաբանություն ավելացնել
$currentUser = Auth::getCurrentUser();
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comment'])) {
    if (!Auth::isLoggedIn()) {
        $message = 'Մեկնաբանություն ավելացնելու համար անհրաժեշտ է մուտք գործել';
    } else {
        $commentText = trim($_POST['comment']);
        if (!empty($commentText)) {
            $result = $comment->create($postId, $currentUser['id'], $commentText);
            if ($result['success']) {
                $message = 'Մեկնաբանությունը հաջողությամբ ավելացված է';
            } else {
                $message = $result['error'];
            }
        } else {
            $message = 'Մեկնաբանությունը դատարկ է';
        }
    }
}

// Ստանալ մեկնաբանությունները
$comments = $comment->getByPost($postId);
?>
<!DOCTYPE html>
<html lang="hy" class="dark-mode">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($postData['title']); ?> - NEXUS</title>
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
            line-height: 1.6;
        }

        .container {
            max-width: 900px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            background: var(--card-bg);
            backdrop-filter: blur(10px);
            border-radius: 16px;
            border: 1px solid rgba(0, 243, 255, 0.1);
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: var(--shadow-neon);
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: var(--neon-blue);
            text-decoration: none;
            margin-bottom: 20px;
            font-weight: 500;
        }

        .back-link:hover {
            text-decoration: underline;
        }

        .post-title {
            font-family: 'Syne', sans-serif;
            font-size: 2.2rem;
            margin-bottom: 15px;
            font-weight: 700;
            color: var(--text-primary);
            line-height: 1.3;
        }

        .post-meta {
            display: flex;
            gap: 20px;
            color: var(--text-secondary);
            font-size: 14px;
            margin-bottom: 20px;
        }

        .post-meta i {
            color: var(--neon-blue);
            margin-right: 5px;
        }

        .post-content {
            background: var(--card-bg);
            backdrop-filter: blur(10px);
            padding: 40px;
            border-radius: 16px;
            border: 1px solid rgba(0, 243, 255, 0.1);
            margin-bottom: 30px;
            line-height: 1.8;
            font-size: 18px;
            box-shadow: var(--shadow-neon);
        }

        .post-image {
            width: 100%;
            max-height: 400px;
            object-fit: cover;
            border-radius: 12px;
            margin-bottom: 30px;
            border: 1px solid rgba(0, 243, 255, 0.2);
        }

        .comments-section {
            background: var(--card-bg);
            backdrop-filter: blur(10px);
            padding: 30px;
            border-radius: 16px;
            border: 1px solid rgba(0, 243, 255, 0.1);
            margin-bottom: 30px;
            box-shadow: var(--shadow-neon);
        }

        .section-title {
            font-family: 'Syne', sans-serif;
            font-size: 1.5rem;
            margin-bottom: 20px;
            color: var(--text-primary);
            padding-bottom: 10px;
            border-bottom: 2px solid var(--neon-blue);
        }

        .comment-form {
            margin-bottom: 30px;
        }

        .comment-form textarea {
            width: 100%;
            padding: 15px;
            background: rgba(0, 243, 255, 0.05);
            border: 1px solid rgba(0, 243, 255, 0.2);
            border-radius: 8px;
            color: var(--text-primary);
            font-size: 16px;
            min-height: 120px;
            margin-bottom: 15px;
            resize: vertical;
            font-family: 'Space Grotesk', sans-serif;
        }

        .comment-form textarea:focus {
            outline: none;
            border-color: var(--neon-blue);
            box-shadow: 0 0 0 3px rgba(0, 243, 255, 0.1);
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 12px 25px;
            background: var(--gradient-neon);
            color: var(--dark-bg);
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
            font-family: 'Space Grotesk', sans-serif;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-neon);
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

        .comments-list {
            list-style: none;
        }

        .comment-item {
            padding: 20px;
            border-bottom: 1px solid rgba(0, 243, 255, 0.1);
            margin-bottom: 15px;
        }

        .comment-item:last-child {
            border-bottom: none;
            margin-bottom: 0;
        }

        .comment-author {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 10px;
        }

        .author-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--gradient-neon);
            color: var(--dark-bg);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
        }

        .author-name {
            font-weight: 600;
            color: var(--text-primary);
        }

        .comment-date {
            color: var(--text-tertiary);
            font-size: 14px;
            margin-left: auto;
        }

        .comment-text {
            color: var(--text-secondary);
            line-height: 1.6;
        }

        .no-comments {
            text-align: center;
            padding: 40px;
            color: var(--text-tertiary);
        }

        .no-comments i {
            font-size: 48px;
            margin-bottom: 20px;
            color: var(--neon-blue);
        }

        .login-prompt {
            background: rgba(0, 243, 255, 0.05);
            border: 1px solid rgba(0, 243, 255, 0.2);
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            margin-bottom: 20px;
        }

        .post-actions {
            display: flex;
            gap: 15px;
            justify-content: center;
            margin-top: 30px;
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
        <!-- Վերնագիր -->
        <div class="header">
            <a href="index.php" class="back-link">
                <i class="fas fa-arrow-left"></i> Վերադառնալ գլխավոր էջ
            </a>
            <h1 class="post-title"><?php echo htmlspecialchars($postData['title']); ?></h1>
            <div class="post-meta">
                <span><i class="fas fa-user"></i> <?php echo htmlspecialchars($postData['full_name'] ?: $postData['username']); ?></span>
                <span><i class="fas fa-calendar"></i> <?php echo date('d M, Y', strtotime($postData['created_at'])); ?></span>
                <span><i class="fas fa-eye"></i> <?php echo $postData['views']; ?> դիտում</span>
                <span><i class="fas fa-tag"></i> <?php echo htmlspecialchars($postData['category_name'] ?? 'TECH'); ?></span>
            </div>
        </div>
        
        <!-- Պատկեր -->
        <?php if (!empty($postData['image_url'])): ?>
            <img src="<?php echo htmlspecialchars($postData['image_url']); ?>" 
                 alt="<?php echo htmlspecialchars($postData['title']); ?>" 
                 class="post-image">
        <?php endif; ?>
        
        <!-- Բովանդակություն -->
        <div class="post-content">
            <?php echo nl2br(htmlspecialchars($postData['content'])); ?>
        </div>
        
        <!-- Մեկնաբանությունների բաժին -->
        <div class="comments-section">
            <h2 class="section-title">
                <i class="fas fa-comments"></i> Մեկնաբանություններ
                <span style="font-size: 14px; color: var(--text-tertiary);">(<?php echo count($comments); ?>)</span>
            </h2>
            
            <!-- Մեկնաբանություն ավելացնելու ձև -->
            <div class="comment-form">
                <?php if ($message): ?>
                    <div class="message <?php echo strpos($message, 'հաջողությամբ') !== false ? 'success' : 'error'; ?>">
                        <?php echo $message; ?>
                    </div>
                <?php endif; ?>
                
                <?php if (Auth::isLoggedIn()): ?>
                    <form method="POST" action="">
                        <textarea name="comment" 
                                  placeholder="Գրեք ձեր մեկնաբանությունը այստեղ..."
                                  required></textarea>
                        <button type="submit" class="btn">
                            <i class="fas fa-paper-plane"></i> Ավելացնել մեկնաբանություն
                        </button>
                    </form>
                <?php else: ?>
                    <div class="login-prompt">
                        <p>Մեկնաբանություն ավելացնելու համար անհրաժեշտ է մուտք գործել</p>
                        <div class="post-actions">
                            <a href="login.php" class="btn">
                                <i class="fas fa-sign-in-alt"></i> Մուտք գործել
                            </a>
                            <a href="register.php" class="btn" style="background: var(--cyber-green);">
                                <i class="fas fa-user-plus"></i> Գրանցվել
                            </a>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
            
            <!-- Մեկնաբանությունների ցուցակ -->
            <?php if (empty($comments)): ?>
                <div class="no-comments">
                    <i class="fas fa-comment-slash"></i>
                    <h3>Դեռևս մեկնաբանություններ չկան</h3>
                    <p>Դուք կարող եք լինել առաջինը, ով կթողնի մեկնաբանություն</p>
                </div>
            <?php else: ?>
                <ul class="comments-list">
                    <?php foreach ($comments as $comment): ?>
                    <li class="comment-item">
                        <div class="comment-author">
                            <div class="author-avatar">
                                <?php echo strtoupper(substr($comment['username'], 0, 1)); ?>
                            </div>
                            <div class="author-name"><?php echo htmlspecialchars($comment['full_name'] ?: $comment['username']); ?></div>
                            <div class="comment-date"><?php echo date('d M, Y H:i', strtotime($comment['created_at'])); ?></div>
                        </div>
                        <div class="comment-text">
                            <?php echo nl2br(htmlspecialchars($comment['content'])); ?>
                        </div>
                    </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
        
        <!-- Գործողություններ -->
        <div class="post-actions">
            <a href="index.php" class="btn" style="background: #4b5563;">
                <i class="fas fa-home"></i> Գլխավոր էջ
            </a>
            <?php if (Auth::isLoggedIn()): ?>
                <a href="create_post.php" class="btn" style="background: var(--cyber-green);">
                    <i class="fas fa-plus"></i> Նոր գրառում
                </a>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>