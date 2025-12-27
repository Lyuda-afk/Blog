<?php
// index.php
require_once 'Auth.php';
require_once 'Post.php';
require_once 'Category.php';
require_once 'User.php';

// Ստուգում ենք, արդյոք օգտատերը մուտք է գործել
$isLoggedIn = Auth::isLoggedIn();
$currentUser = Auth::getCurrentUser();

// Ստանում ենք գրառումները
$post = new Post();
$posts = $post->getAll(6);

// Ստանում ենք կատեգորիաները
$category = new Category();
$categories = $category->getAll();
?>
<!DOCTYPE html>
<html lang="hy" class="dark-mode">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nexus Blog - Ժամանակակից Բլոգային Պլատֆորմ</title>
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
            --card-bg: rgba(20, 20, 30, 0.7);
            --glass-bg: rgba(30, 30, 40, 0.3);
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
            --shadow-green: 0 0 20px rgba(0, 255, 157, 0.3),
                            0 0 40px rgba(0, 255, 157, 0.2);
            
            --gradient-neon: linear-gradient(135deg, var(--neon-blue), var(--neon-purple));
            --gradient-cyber: linear-gradient(135deg, var(--neon-pink), var(--neon-blue));
            --gradient-matrix: linear-gradient(135deg, var(--matrix-green), var(--cyber-green));
            
            --radius-sm: 4px;
            --radius-md: 8px;
            --radius-lg: 12px;
            --radius-xl: 16px;
            --radius-full: 50px;
            
            --transition-fast: 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            --transition-normal: 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            --transition-slow: 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
            scroll-padding-top: 120px;
        }

        body {
            font-family: 'Space Grotesk', sans-serif;
            line-height: 1.6;
            color: var(--text-primary);
            background: var(--dark-bg);
            min-height: 100vh;
            position: relative;
            overflow-x: hidden;
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

        .container {
            width: 100%;
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 20px;
        }

        /* Վերնագիր */
        .header {
            background: rgba(10, 10, 15, 0.9);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(0, 243, 255, 0.1);
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            padding: 15px 0;
        }

        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 30px;
        }

        /* Լոգո */
        .logo {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .logo a {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
            color: var(--text-primary);
        }

        .logo-icon {
            width: 50px;
            height: 50px;
            background: var(--gradient-neon);
            border-radius: var(--radius-md);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
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
            font-size: 1.8rem;
            font-weight: 800;
            background: var(--gradient-neon);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            letter-spacing: -0.5px;
        }

        .logo-tagline {
            font-size: 0.8rem;
            color: var(--neon-blue);
            font-weight: 500;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        /* Նավիգացիա */
        .main-nav ul {
            display: flex;
            list-style: none;
            gap: 20px;
            align-items: center;
        }

        .nav-link {
            position: relative;
            color: var(--text-secondary);
            text-decoration: none;
            font-weight: 500;
            padding: 10px 16px;
            border-radius: var(--radius-full);
            transition: all var(--transition-normal);
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.95rem;
            background: transparent;
            border: 1px solid transparent;
        }

        .nav-link:hover {
            color: var(--neon-blue);
            background: rgba(0, 243, 255, 0.1);
            border-color: rgba(0, 243, 255, 0.3);
        }

        .nav-link.active {
            color: var(--neon-blue);
            background: rgba(0, 243, 255, 0.15);
            border-color: var(--neon-blue);
            box-shadow: 0 0 15px rgba(0, 243, 255, 0.2);
        }

        .nav-link i {
            font-size: 1rem;
        }

        /* Օգտատիրոջ գործողություններ */
        .user-actions {
            display: flex;
            gap: 12px;
            align-items: center;
        }

        .btn {
            padding: 10px 24px;
            border-radius: var(--radius-full);
            font-weight: 600;
            text-decoration: none;
            transition: all var(--transition-normal);
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 0.9rem;
            border: 1px solid transparent;
            cursor: pointer;
            position: relative;
            overflow: hidden;
            background: var(--card-bg);
            color: var(--text-primary);
            backdrop-filter: blur(10px);
        }

        .btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(
                135deg,
                rgba(0, 243, 255, 0.1),
                rgba(185, 103, 255, 0.1)
            );
            opacity: 0;
            transition: opacity var(--transition-normal);
        }

        .btn:hover::before {
            opacity: 1;
        }

        .btn-login {
            border-color: rgba(0, 243, 255, 0.3);
        }

        .btn-login:hover {
            border-color: var(--neon-blue);
            box-shadow: var(--shadow-neon);
        }

        .btn-register {
            background: var(--gradient-neon);
            color: var(--dark-bg);
            font-weight: 700;
        }

        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-neon);
        }

        .btn-add-post {
            background: var(--gradient-cyber);
            color: var(--dark-bg);
            font-weight: 700;
        }

        .btn-add-post:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-purple);
        }

        .btn-logout {
            background: var(--gradient-matrix);
            color: var(--dark-bg);
            font-weight: 700;
        }

        .btn-logout:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-green);
        }

        /* Օգտատիրոջ պրոֆիլ */
        .user-profile {
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
            padding: 8px 16px;
            border-radius: var(--radius-full);
            background: var(--card-bg);
            border: 1px solid rgba(0, 243, 255, 0.2);
            transition: all var(--transition-normal);
            backdrop-filter: blur(10px);
        }

        .user-profile:hover {
            border-color: var(--neon-blue);
            box-shadow: var(--shadow-neon);
        }

        .user-avatar {
            width: 36px;
            height: 36px;
            background: var(--gradient-neon);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--dark-bg);
            font-weight: bold;
            font-size: 1rem;
            border: 2px solid var(--dark-bg);
        }

        .user-info {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .user-name {
            font-weight: 600;
            color: var(--text-primary);
            font-size: 0.9rem;
        }

        .user-role {
            font-size: 0.7rem;
            color: var(--neon-blue);
            background: rgba(0, 243, 255, 0.1);
            padding: 2px 8px;
            border-radius: var(--radius-full);
            display: inline-block;
        }

        /* Հերոս բաժին */
        .hero {
            padding: 180px 0 100px;
            position: relative;
            overflow: hidden;
            margin-bottom: 80px;
        }

        .hero-content {
            max-width: 800px;
            margin: 0 auto;
            text-align: center;
            position: relative;
        }

        .hero-title {
            font-family: 'Syne', sans-serif;
            font-size: 4rem;
            line-height: 1.1;
            margin-bottom: 20px;
            color: var(--text-primary);
            position: relative;
            display: inline-block;
        }

        .hero-title::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 200px;
            height: 3px;
            background: var(--gradient-neon);
            border-radius: var(--radius-full);
        }

        .hero-description {
            font-size: 1.2rem;
            color: var(--text-secondary);
            margin-bottom: 40px;
            line-height: 1.8;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }

        .hero-cta {
            display: flex;
            gap: 20px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .hero-btn {
            padding: 16px 40px;
            font-size: 1rem;
            font-weight: 700;
            border-radius: var(--radius-full);
            text-decoration: none;
            transition: all var(--transition-normal);
            position: relative;
            overflow: hidden;
            display: inline-flex;
            align-items: center;
            gap: 12px;
            border: 1px solid transparent;
        }

        .hero-btn-primary {
            background: var(--gradient-neon);
            color: var(--dark-bg);
        }

        .hero-btn-secondary {
            background: transparent;
            color: var(--neon-blue);
            border-color: var(--neon-blue);
        }

        .hero-btn:hover {
            transform: translateY(-3px);
            box-shadow: var(--shadow-neon);
        }

        .hero-btn-secondary:hover {
            background: rgba(0, 243, 255, 0.1);
        }

        .hero-btn i {
            font-size: 1.1rem;
        }

        /* Հատկություններ */
        .features {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
            margin-bottom: 80px;
        }

        .feature-card {
            background: var(--card-bg);
            padding: 40px 30px;
            border-radius: var(--radius-xl);
            transition: all var(--transition-normal);
            position: relative;
            border: 1px solid rgba(0, 243, 255, 0.1);
            backdrop-filter: blur(10px);
            overflow: hidden;
        }

        .feature-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: var(--gradient-neon);
            transform: scaleX(0);
            transition: transform var(--transition-normal);
            transform-origin: left;
        }

        .feature-card:hover::before {
            transform: scaleX(1);
        }

        .feature-card:hover {
            transform: translateY(-5px);
            border-color: var(--neon-blue);
            box-shadow: var(--shadow-neon);
        }

        .feature-icon {
            width: 60px;
            height: 60px;
            background: var(--card-bg);
            border-radius: var(--radius-lg);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--neon-blue);
            font-size: 1.5rem;
            margin-bottom: 25px;
            border: 1px solid rgba(0, 243, 255, 0.2);
        }

        .feature-title {
            font-family: 'Syne', sans-serif;
            font-size: 1.5rem;
            color: var(--text-primary);
            margin-bottom: 15px;
        }

        .feature-description {
            color: var(--text-secondary);
            line-height: 1.7;
        }

        /* Գրառումների բաժին */
        .posts-section {
            margin-bottom: 100px;
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 50px;
            padding-bottom: 20px;
            border-bottom: 1px solid rgba(0, 243, 255, 0.1);
        }

        .section-title {
            font-family: 'Syne', sans-serif;
            font-size: 2.5rem;
            color: var(--text-primary);
            position: relative;
        }

        .section-title::before {
            content: '';
            position: absolute;
            left: 0;
            bottom: -10px;
            width: 60px;
            height: 3px;
            background: var(--gradient-neon);
            border-radius: var(--radius-full);
        }

        .section-subtitle {
            color: var(--text-secondary);
            font-size: 1rem;
            max-width: 600px;
            margin-top: 10px;
        }

        .view-all-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: var(--neon-blue);
            text-decoration: none;
            font-weight: 600;
            padding: 12px 24px;
            border-radius: var(--radius-full);
            background: rgba(0, 243, 255, 0.1);
            border: 1px solid rgba(0, 243, 255, 0.2);
            transition: all var(--transition-normal);
        }

        .view-all-link:hover {
            background: rgba(0, 243, 255, 0.2);
            border-color: var(--neon-blue);
            box-shadow: var(--shadow-neon);
        }

        /* Գրառումների ցանց */
        .posts-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(380px, 1fr));
            gap: 30px;
        }

        .post-card {
            background: var(--card-bg);
            border-radius: var(--radius-xl);
            overflow: hidden;
            transition: all var(--transition-normal);
            position: relative;
            height: 100%;
            display: flex;
            flex-direction: column;
            border: 1px solid rgba(0, 243, 255, 0.1);
            backdrop-filter: blur(10px);
        }

        .post-card:hover {
            transform: translateY(-8px);
            border-color: var(--neon-blue);
            box-shadow: var(--shadow-neon);
        }

        .post-image {
            height: 240px;
            position: relative;
            overflow: hidden;
        }

        .post-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform var(--transition-slow);
        }

        .post-card:hover .post-image img {
            transform: scale(1.1);
        }

        .image-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(to bottom, transparent 50%, rgba(10, 10, 15, 0.8));
            display: flex;
            align-items: flex-end;
            padding: 20px;
        }

        .post-category {
            background: var(--gradient-neon);
            color: var(--dark-bg);
            padding: 6px 16px;
            border-radius: var(--radius-full);
            font-size: 0.8rem;
            font-weight: 700;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        .post-content {
            padding: 30px;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }

        .post-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            color: var(--text-tertiary);
            font-size: 0.85rem;
        }

        .post-date {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .post-date i {
            color: var(--neon-blue);
        }

        .post-author {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .post-author img {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            border: 2px solid rgba(0, 243, 255, 0.3);
        }

        .post-title {
            font-family: 'Syne', sans-serif;
            font-size: 1.4rem;
            line-height: 1.3;
            margin-bottom: 20px;
            color: var(--text-primary);
        }

        .post-title a {
            color: inherit;
            text-decoration: none;
            transition: color var(--transition-normal);
            display: block;
        }

        .post-title a:hover {
            color: var(--neon-blue);
        }

        .post-excerpt {
            color: var(--text-secondary);
            margin-bottom: 25px;
            line-height: 1.6;
            flex-grow: 1;
        }

        .post-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: auto;
        }

        .read-more {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: var(--neon-blue);
            text-decoration: none;
            font-weight: 600;
            padding: 10px 20px;
            border-radius: var(--radius-full);
            background: rgba(0, 243, 255, 0.1);
            border: 1px solid rgba(0, 243, 255, 0.2);
            transition: all var(--transition-normal);
        }

        .read-more:hover {
            background: rgba(0, 243, 255, 0.2);
            border-color: var(--neon-blue);
            gap: 12px;
        }

        .post-stats {
            display: flex;
            gap: 15px;
            color: var(--text-tertiary);
            font-size: 0.85rem;
        }

        .post-stat {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .post-stat i {
            color: var(--neon-blue);
        }

        /* Կատեգորիաների բաժին */
        .categories-section {
            margin-bottom: 100px;
        }

        .categories-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 25px;
        }

        .category-card {
            background: var(--card-bg);
            padding: 40px 30px;
            border-radius: var(--radius-xl);
            text-decoration: none;
            color: var(--text-primary);
            transition: all var(--transition-normal);
            position: relative;
            border: 1px solid rgba(0, 243, 255, 0.1);
            backdrop-filter: blur(10px);
            text-align: center;
            overflow: hidden;
        }

        .category-card:hover {
            transform: translateY(-5px);
            border-color: var(--neon-blue);
            box-shadow: var(--shadow-neon);
        }

        .category-icon-wrapper {
            width: 80px;
            height: 80px;
            background: rgba(0, 243, 255, 0.1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 25px;
            transition: all var(--transition-normal);
            border: 1px solid rgba(0, 243, 255, 0.2);
        }

        .category-card:hover .category-icon-wrapper {
            background: rgba(0, 243, 255, 0.2);
            border-color: var(--neon-blue);
            transform: scale(1.1);
        }

        .category-icon {
            font-size: 2rem;
            color: var(--neon-blue);
            transition: all var(--transition-normal);
        }

        .category-card:hover .category-icon {
            transform: rotate(15deg);
        }

        .category-name {
            font-family: 'Syne', sans-serif;
            font-size: 1.3rem;
            color: var(--text-primary);
            margin-bottom: 12px;
        }

        .category-count {
            font-size: 0.9rem;
            color: var(--neon-blue);
            background: rgba(0, 243, 255, 0.1);
            padding: 6px 16px;
            border-radius: var(--radius-full);
            font-weight: 600;
            display: inline-block;
        }

        /* Ներքևի մաս */
        .footer {
            background: var(--darker-bg);
            color: white;
            padding: 80px 0 30px;
            margin-top: 100px;
            position: relative;
            border-top: 1px solid rgba(0, 243, 255, 0.1);
        }

        .footer::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: var(--gradient-neon);
        }

        .footer-content {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 40px;
            margin-bottom: 60px;
        }

        .footer-section h3 {
            font-family: 'Syne', sans-serif;
            font-size: 1.4rem;
            margin-bottom: 25px;
            color: var(--text-primary);
            position: relative;
            padding-bottom: 15px;
        }

        .footer-section h3::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 40px;
            height: 3px;
            background: var(--neon-blue);
            border-radius: var(--radius-full);
        }

        .footer-section p {
            color: var(--text-secondary);
            line-height: 1.8;
            margin-bottom: 25px;
        }

        .footer-links {
            list-style: none;
        }

        .footer-links li {
            margin-bottom: 12px;
        }

        .footer-links a {
            color: var(--text-secondary);
            text-decoration: none;
            transition: all var(--transition-normal);
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 6px 0;
        }

        .footer-links a:hover {
            color: var(--neon-blue);
            transform: translateX(5px);
        }

        .footer-links a i {
            width: 16px;
            font-size: 0.8rem;
        }

        .social-links {
            display: flex;
            gap: 12px;
            margin-top: 25px;
        }

        .social-link {
            width: 40px;
            height: 40px;
            background: rgba(0, 243, 255, 0.1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--neon-blue);
            text-decoration: none;
            font-size: 1rem;
            transition: all var(--transition-normal);
            border: 1px solid rgba(0, 243, 255, 0.2);
        }

        .social-link:hover {
            background: rgba(0, 243, 255, 0.2);
            border-color: var(--neon-blue);
            transform: translateY(-3px) rotate(10deg);
            box-shadow: var(--shadow-neon);
        }

        .newsletter-form {
            margin-top: 20px;
        }

        .newsletter-input {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid rgba(0, 243, 255, 0.2);
            border-radius: var(--radius-full);
            background: rgba(0, 243, 255, 0.05);
            color: var(--text-primary);
            font-size: 0.9rem;
            margin-bottom: 12px;
            transition: all var(--transition-normal);
        }

        .newsletter-input:focus {
            outline: none;
            border-color: var(--neon-blue);
            background: rgba(0, 243, 255, 0.1);
            box-shadow: 0 0 0 3px rgba(0, 243, 255, 0.1);
        }

        .newsletter-input::placeholder {
            color: var(--text-tertiary);
        }

        .copyright {
            text-align: center;
            padding-top: 30px;
            border-top: 1px solid rgba(0, 243, 255, 0.1);
            color: var(--text-tertiary);
            font-size: 0.85rem;
        }

        /* Scroll to top button */
        .scroll-top {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 50px;
            height: 50px;
            background: var(--gradient-neon);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--dark-bg);
            font-size: 1.2rem;
            text-decoration: none;
            opacity: 0;
            visibility: hidden;
            transform: translateY(20px);
            transition: all var(--transition-normal);
            z-index: 999;
            font-weight: bold;
        }

        .scroll-top.visible {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .scroll-top:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-neon);
        }

        /* Անիմացիաներ */
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }

        @keyframes scanline {
            0% { transform: translateY(-100%); }
            100% { transform: translateY(100%); }
        }

        /* Responsive */
        @media (max-width: 1200px) {
            .container {
                padding: 0 15px;
            }
            
            .hero-title {
                font-size: 3.5rem;
            }
            
            .posts-grid {
                grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            }
        }

        @media (max-width: 992px) {
            .header-content {
                flex-direction: column;
                gap: 20px;
            }
            
            .hero {
                padding: 150px 0 80px;
            }
            
            .hero-title {
                font-size: 3rem;
            }
            
            .section-title {
                font-size: 2.2rem;
            }
            
            .features {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 768px) {
            .main-nav ul {
                flex-wrap: wrap;
                justify-content: center;
                gap: 10px;
            }
            
            .nav-link {
                padding: 8px 12px;
                font-size: 0.9rem;
            }
            
            .hero-title {
                font-size: 2.5rem;
            }
            
            .hero-description {
                font-size: 1.1rem;
            }
            
            .hero-cta {
                flex-direction: column;
                align-items: center;
            }
            
            .posts-grid {
                grid-template-columns: 1fr;
            }
            
            .categories-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .section-header {
                flex-direction: column;
                gap: 20px;
                text-align: center;
            }
            
            .section-title::before {
                left: 50%;
                transform: translateX(-50%);
            }
        }

        @media (max-width: 576px) {
            .hero-title {
                font-size: 2rem;
            }
            
            .categories-grid {
                grid-template-columns: 1fr;
            }
            
            .footer-content {
                grid-template-columns: 1fr;
                text-align: center;
            }
            
            .footer-section h3::after {
                left: 50%;
                transform: translateX(-50%);
            }
            
            .social-links {
                justify-content: center;
            }
            
            .user-actions {
                flex-wrap: wrap;
                justify-content: center;
            }
            
            .logo-text {
                font-size: 1.5rem;
            }
        }

        /* Code effect */
        .code-effect {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: -1;
        }

        .code-line {
            position: absolute;
            color: rgba(0, 255, 65, 0.1);
            font-family: 'Courier New', monospace;
            font-size: 14px;
            animation: fall linear infinite;
        }

        @keyframes fall {
            from { transform: translateY(-100px); }
            to { transform: translateY(100vh); }
        }
    </style>
</head>
<body>
    <!-- Matrix code effect -->
    <div class="code-effect" id="codeEffect"></div>

    <!-- Վերնագիր -->
    <header class="header">
        <div class="container">
            <div class="header-content">
                <!-- Լոգո -->
                <div class="logo">
                    <a href="index.php">
                        <div class="logo-icon">
                            <i class="fas fa-bolt"></i>
                        </div>
                        <div>
                            <div class="logo-text">NEXUS</div>
                            <div class="logo-tagline">BLOG PLATFORM</div>
                        </div>
                    </a>
                </div>
                
                <!-- Նավիգացիա -->
                <nav class="main-nav">
                    <ul>
                        <li><a href="index.php" class="nav-link active"><i class="fas fa-terminal"></i> Գլխավոր</a></li>
                        <li><a href="#posts" class="nav-link"><i class="fas fa-code"></i> Գրառումներ</a></li>
                        <li><a href="#categories" class="nav-link"><i class="fas fa-layer-group"></i> Կատեգորիաներ</a></li>
                        <li><a href="#features" class="nav-link"><i class="fas fa-rocket"></i> Հատկություններ</a></li>
                        
                        <!-- Օգտատիրոջ գործողություններ -->
                        <div class="user-actions">
                            <?php if ($isLoggedIn): ?>
                                <div class="user-profile">
                                    <div class="user-avatar">
                                        <?php echo strtoupper(substr($currentUser['username'], 0, 1)); ?>
                                    </div>
                                    <div class="user-info">
                                        <div class="user-name"><?php echo htmlspecialchars($currentUser['username']); ?></div>
                                        <div class="user-role"><?php echo $currentUser['is_admin'] ? 'ADMIN' : 'USER'; ?></div>
                                    </div>
                                </div>
                                <?php if (Auth::isAdmin()): ?>
                                    <a href="admin.php" class="btn btn-add-post">
                                        <i class="fas fa-crown"></i> Ադմին
                                    </a>
                                <?php endif; ?>
                                <a href="create_post.php" class="btn btn-add-post">
                                    <i class="fas fa-plus"></i> Նոր գրառում
                                </a>
                                <a href="logout.php" class="btn btn-logout">
                                    <i class="fas fa-sign-out-alt"></i> Դուրս գալ
                                </a>
                            <?php else: ?>
                                <a href="login.php" class="btn btn-login">
                                    <i class="fas fa-sign-in-alt"></i> Մուտք
                                </a>
                                <a href="register.php" class="btn btn-register">
                                    <i class="fas fa-user-plus"></i> Գրանցվել
                                </a>
                            <?php endif; ?>
                        </div>
                    </ul>
                </nav>
            </div>
        </div>
    </header>

    <!-- Հերոս բաժին -->
    <section class="hero">
        <div class="container">
            <div class="hero-content">
                <h1 class="hero-title">CODE. CREATE.<br>CONNECT.</h1>
                <p class="hero-description">
                    Միացեք ապագայի բլոգինգ պլատֆորմին, որտեղ տեխնոլոգիան հանդիպում է ստեղծագործությանը։ 
                    Ձեր մտքերն արտահայտեք կոդի լեզվով։
                </p>
                <div class="hero-cta">
                    <?php if ($isLoggedIn): ?>
                        <a href="create_post.php" class="hero-btn hero-btn-primary">
                            <i class="fas fa-terminal"></i> Սկսել կոդավորել
                        </a>
                        <a href="#posts" class="hero-btn hero-btn-secondary">
                            <i class="fas fa-network-wired"></i> Ուսումնասիրել
                        </a>
                    <?php else: ?>
                        <a href="register.php" class="hero-btn hero-btn-primary">
                            <i class="fas fa-rocket"></i> Միանալ Ապագային
                        </a>
                        <a href="#features" class="hero-btn hero-btn-secondary">
                            <i class="fas fa-info-circle"></i> Տեսնել Ավելին
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>

    <!-- Հատկություններ -->
    <section id="features" class="container">
        <div class="features">
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-microchip"></i>
                </div>
                <h3 class="feature-title">AI Օգնական</h3>
                <p class="feature-description">
                    Օգտագործեք արհեստական բանականությունը՝ ձեր գրառումները կատարելագործելու և 
                    նոր գաղափարներ առաջարկելու համար։
                </p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <h3 class="feature-title">Բլոկչեյն Անվտանգություն</h3>
                <p class="feature-description">
                    Ձեր գրառումները պաշտպանված են բլոկչեյն տեխնոլոգիայով։ 
                    Յուրաքանչյուր հրապարակում ունի իր թվային հետքը։
                </p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-bolt"></i>
                </div>
                <h3 class="feature-title">Լույսի արագություն</h3>
                <p class="feature-description">
                    Անհավատալիորեն արագ պլատֆորմ՝ հենված Edge Computing-ի վրա։ 
                    Ձեր գրառումները հասանելի են ամբողջ աշխարհում։
                </p>
            </div>
        </div>
    </section>

    <!-- Գրառումների բաժին -->
    <section id="posts" class="container posts-section">
        <div class="section-header">
            <div>
                <h2 class="section-title">ՎԵՐՋԻՆ ԿՈԴԱՅԻՆ ԳՐԱՌՈՒՄՆԵՐ</h2>
                <p class="section-subtitle">Թարմացումներ տեխնոլոգիաների, կոդավորման և ապագայի մասին</p>
            </div>
            <a href="all_posts.php" class="view-all-link">
                <span>ԴԻՏԵԼ ԲՈԼՈՐԸ</span>
                <i class="fas fa-arrow-right"></i>
            </a>
        </div>
        
        <?php if (empty($posts)): ?>
            <div class="no-posts" style="text-align: center; padding: 80px 20px; background: var(--card-bg); border-radius: var(--radius-xl); border: 1px solid rgba(0, 243, 255, 0.1);">
                <div style="font-size: 5rem; color: rgba(0, 243, 255, 0.3); margin-bottom: 20px;">
                    <i class="fas fa-code"></i>
                </div>
                <h3 style="color: var(--text-primary); margin-bottom: 15px; font-size: 1.8rem;">ԴԱՏԱՐԿ ՏԱՐԱԾՔ</h3>
                <p style="color: var(--text-secondary); max-width: 500px; margin: 0 auto 30px; font-size: 1.1rem;">
                    Դուք կարող եք լինել առաջինը, ով կգրի գրառում մեր դիստոպիական ապագայում
                </p>
                <?php if ($isLoggedIn): ?>
                    <a href="create_post.php" class="btn btn-add-post" style="font-size: 1rem; padding: 14px 32px;">
                        <i class="fas fa-terminal"></i> ՍՏԵՂԾԵԼ ԱՌԱՋԻՆ ԳՐԱՌՈՒՄԸ
                    </a>
                <?php else: ?>
                    <a href="register.php" class="btn btn-register" style="font-size: 1rem; padding: 14px 32px;">
                        <i class="fas fa-user-plus"></i> ԳՐԱՆՑՎԵԼ ԵՎ ՍԿՍԵԼ
                    </a>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <div class="posts-grid">
                <?php foreach ($posts as $post): ?>
                <article class="post-card">
                    <div class="post-image">
                        <img src="https://images.unsplash.com/photo-1550745165-9bc0b252726f?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="<?php echo htmlspecialchars($post['title']); ?>">
                        <div class="image-overlay">
                            <span class="post-category"><?php echo htmlspecialchars($post['category_name'] ?? 'TECH'); ?></span>
                        </div>
                    </div>
                    <div class="post-content">
                        <div class="post-meta">
                            <div class="post-date">
                                <i class="far fa-clock"></i>
                                <?php echo date('d/m/Y', strtotime($post['created_at'])); ?>
                            </div>
                            <div class="post-author">
                                <img src="https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?ixlib=rb-4.0.3&auto=format&fit=crop&w=100&q=80" alt="Հեղինակ">
                                <span>@<?php echo htmlspecialchars($post['username']); ?></span>
                            </div>
                        </div>
                        <h3 class="post-title">
                            <a href="view_post.php?id=<?php echo $post['id']; ?>">
                                <?php echo htmlspecialchars($post['title']); ?>
                            </a>
                        </h3>
                        <p class="post-excerpt">
                            <?php echo htmlspecialchars($post['excerpt'] ?? substr($post['content'], 0, 120) . '...'); ?>
                        </p>
                        <div class="post-footer">
                            <a href="view_post.php?id=<?php echo $post['id']; ?>" class="read-more">
                                <span>ԿԱՐԴԱԼ</span>
                                <i class="fas fa-arrow-right"></i>
                            </a>
                            <div class="post-stats">
                                <div class="post-stat">
                                    <i class="fas fa-eye"></i>
                                    <span><?php echo $post['views']; ?></span>
                                </div>
                                <div class="post-stat">
                                    <i class="fas fa-comment"></i>
                                    <span>18</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </section>

    <!-- Կատեգորիաների բաժին -->
    <section id="categories" class="container categories-section">
        <div class="section-header">
            <div>
                <h2 class="section-title">ՏԵԽՆՈԼՈԳԻԱԿԱՆ ԿԱՏԵԳՈՐԻԱՆԵՐ</h2>
                <p class="section-subtitle">Ընտրեք ձեր ոլորտը և սկսեք ուսումնասիրել ապագան</p>
            </div>
            <a href="all_categories.php" class="view-all-link">
                <span>ԲՈԼՈՐ ԿԱՏԵԳՈՐԻԱՆԵՐԸ</span>
                <i class="fas fa-arrow-right"></i>
            </a>
        </div>
        
        <div class="categories-grid">
            <?php foreach ($categories as $category): ?>
            <a href="category.php?id=<?php echo $category['id']; ?>" class="category-card">
                <div class="category-icon-wrapper">
                    <?php 
                    $icons = [
                        1 => 'fas fa-robot',
                        2 => 'fas fa-code',
                        3 => 'fas fa-database',
                        4 => 'fas fa-vr-cardboard',
                        5 => 'fas fa-satellite'
                    ];
                    $icon = $icons[$category['id']] ?? 'fas fa-microchip';
                    ?>
                    <i class="<?php echo $icon; ?> category-icon"></i>
                </div>
                <h3 class="category-name"><?php echo htmlspecialchars($category['name']); ?></h3>
                <span class="category-count"><?php echo $category['post_count']; ?> POSTS</span>
            </a>
            <?php endforeach; ?>
            
            <!-- Դիտարկեք ավելի շատ կատեգորիա -->
            <a href="all_categories.php" class="category-card" style="background: var(--gradient-neon); color: var(--dark-bg);">
                <div class="category-icon-wrapper" style="background: rgba(10, 10, 15, 0.2); border-color: rgba(10, 10, 15, 0.3);">
                    <i class="fas fa-plus category-icon" style="color: var(--dark-bg);"></i>
                </div>
                <h3 class="category-name" style="color: var(--dark-bg);">VIEW ALL</h3>
                <span class="category-count" style="background: rgba(10, 10, 15, 0.2); color: var(--dark-bg);">EXPLORE</span>
            </a>
        </div>
    </section>

    <!-- Ներքևի մաս -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <!-- Մաս 1 -->
                <div class="footer-section">
                    <h3>NEXUS BLOG</h3>
                    <p>
                        Ապագայի բլոգային պլատֆորմ՝ հենված նորագույն տեխնոլոգիաների վրա։ 
                        Միացեք մեզ՝ ստեղծելու ապագան։
                    </p>
                    <div class="social-links">
                        <a href="#" class="social-link"><i class="fab fa-github"></i></a>
                        <a href="#" class="social-link"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="social-link"><i class="fab fa-discord"></i></a>
                        <a href="#" class="social-link"><i class="fab fa-telegram"></i></a>
                        <a href="#" class="social-link"><i class="fab fa-linkedin"></i></a>
                    </div>
                </div>

                <!-- Մաս 2 -->
                <div class="footer-section">
                    <h3>QUICK ACCESS</h3>
                    <ul class="footer-links">
                        <li><a href="index.php"><i class="fas fa-terminal"></i> Գլխավոր</a></li>
                        <li><a href="#posts"><i class="fas fa-code"></i> Գրառումներ</a></li>
                        <li><a href="#categories"><i class="fas fa-layer-group"></i> Կատեգորիաներ</a></li>
                        <li><a href="about.php"><i class="fas fa-info-circle"></i> Մեր Մասին</a></li>
                        <li><a href="contact.php"><i class="fas fa-comments"></i> Կապ</a></li>
                    </ul>
                </div>

                <!-- Մաս 3 -->
                <div class="footer-section">
                    <h3>CONTACT DATA</h3>
                    <ul class="footer-links">
                        <li><a href="tel:+37410123456"><i class="fas fa-phone"></i> +374 10 123456</a></li>
                        <li><a href="mailto:contact@nexus.am"><i class="fas fa-envelope"></i> contact@nexus.am</a></li>
                        <li><a href="#"><i class="fas fa-map-marker-alt"></i> Երևանի Թեկնոսֆեր</a></li>
                        <li><a href="#"><i class="fas fa-clock"></i> 24/7 Online</a></li>
                    </ul>
                </div>

                <!-- Մաս 4 -->
                <div class="footer-section">
                    <h3>NEWSLETTER</h3>
                    <p>Բաժանորդագրվեք և ստացեք տեխնոլոգիական թարմացումներ ու էքսկլյուզիվ նյութեր</p>
                    <form class="newsletter-form">
                        <input type="email" class="newsletter-input" placeholder="YOUR@EMAIL.COM" required>
                        <button type="submit" class="btn btn-register" style="width: 100%;">
                            <i class="fas fa-paper-plane"></i> SUBSCRIBE
                        </button>
                    </form>
                </div>
            </div>
            
            <div class="copyright">
                <p>&copy; 2024 NEXUS BLOG. ALL RIGHTS RESERVED.</p>
                <p style="margin-top: 10px; font-size: 0.8rem;">
                    <a href="privacy.php" style="color: var(--text-tertiary); text-decoration: none; margin: 0 10px;">PRIVACY POLICY</a> | 
                    <a href="terms.php" style="color: var(--text-tertiary); text-decoration: none; margin: 0 10px;">TERMS OF SERVICE</a>
                </p>
            </div>
        </div>
    </footer>

    <!-- Scroll to top button -->
    <a href="#" class="scroll-top" id="scrollTop">
        <i class="fas fa-chevron-up"></i>
    </a>

    <script>
        // Matrix code effect
        const codeEffect = document.getElementById('codeEffect');
        const characters = '01';
        
        function createCodeLine() {
            const line = document.createElement('div');
            line.className = 'code-line';
            line.style.left = Math.random() * 100 + 'vw';
            line.style.animationDuration = (Math.random() * 5 + 3) + 's';
            line.style.opacity = Math.random() * 0.3 + 0.1;
            line.style.fontSize = (Math.random() * 10 + 10) + 'px';
            
            let code = '';
            const length = Math.floor(Math.random() * 30) + 10;
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
        for (let i = 0; i < 20; i++) {
            setTimeout(() => createCodeLine(), i * 200);
        }
        
        // Continue creating code lines
        setInterval(createCodeLine, 200);

        // Scroll to top functionality
        const scrollTopBtn = document.getElementById('scrollTop');
        
        window.addEventListener('scroll', () => {
            if (window.pageYOffset > 300) {
                scrollTopBtn.classList.add('visible');
            } else {
                scrollTopBtn.classList.remove('visible');
            }
        });
        
        scrollTopBtn.addEventListener('click', (e) => {
            e.preventDefault();
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });

        // Smooth scrolling for navigation links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                const targetId = this.getAttribute('href');
                if (targetId === '#') return;
                
                const targetElement = document.querySelector(targetId);
                if (targetElement) {
                    window.scrollTo({
                        top: targetElement.offsetTop - 100,
                        behavior: 'smooth'
                    });
                }
            });
        });

        // Add hover effect to cards
        document.querySelectorAll('.post-card, .feature-card, .category-card').forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-8px)';
            });
            
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
            });
        });

        // Newsletter form submission
        const newsletterForm = document.querySelector('.newsletter-form');
        if (newsletterForm) {
            newsletterForm.addEventListener('submit', function(e) {
                e.preventDefault();
                const email = this.querySelector('.newsletter-input').value;
                
                // Simulate submission
                const submitBtn = this.querySelector('button');
                const originalText = submitBtn.innerHTML;
                
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> PROCESSING...';
                submitBtn.disabled = true;
                
                setTimeout(() => {
                    submitBtn.innerHTML = '<i class="fas fa-check"></i> SUBSCRIBED!';
                    submitBtn.style.background = 'var(--cyber-green)';
                    submitBtn.style.color = 'var(--dark-bg)';
                    
                    setTimeout(() => {
                        submitBtn.innerHTML = originalText;
                        submitBtn.disabled = false;
                        submitBtn.style.background = '';
                        submitBtn.style.color = '';
                        this.reset();
                    }, 2000);
                }, 1500);
            });
        }

        // Add typing effect to hero title
        const heroTitle = document.querySelector('.hero-title');
        const originalTitle = heroTitle.textContent;
        heroTitle.textContent = '';
        
        let i = 0;
        function typeWriter() {
            if (i < originalTitle.length) {
                heroTitle.textContent += originalTitle.charAt(i);
                i++;
                setTimeout(typeWriter, 50);
            }
        }
        
        // Start typing effect after page load
        setTimeout(typeWriter, 500);

        // Add glitch effect to logo on hover
        const logoIcon = document.querySelector('.logo-icon');
        logoIcon.addEventListener('mouseenter', function() {
            this.style.animation = 'none';
            setTimeout(() => {
                this.style.animation = 'shine 3s infinite linear';
            }, 100);
        });
    </script>
</body>
</html>