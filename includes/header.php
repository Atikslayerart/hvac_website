<?php
/**
 * Shared website header and navigation.
 * File: includes/header.php
 *
 * Before including this file, a page may optionally define:
 * $pageTitle = 'Page Title';
 * $basePath = '../';
 *
 * $basePath is the path from the current PHP page to the project root.
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$companyName = 'CoolAir HVAC';
$tagline = 'Comfort You Can Trust';

$pageTitle = $pageTitle ?? $companyName;
$basePath = $basePath ?? '';

$isLoggedIn = isset($_SESSION['user_id']);
$role = $_SESSION['role'] ?? 'client';
$isAdmin = $role === 'admin';

$homeUrl = $basePath . 'all_pages/index.php';
$aboutUrl = $basePath . 'all_pages/about.php';
$servicesUrl = $basePath . 'all_pages/services.php';
$contactUrl = $basePath . 'all_pages/contact.php';
$scheduleUrl = $basePath . 'all_pages/schedule.php';

$loginUrl = $basePath . 'login/login.php';
$profileUrl = $basePath . 'login/profile.php';
$requestsUrl = $basePath . 'login/requests.php';
$myRequestsUrl = $basePath . 'login/my-requests.php';
$logoutUrl = $basePath . 'login/logout.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle) ?></title>

    <!-- Bootstrap and icon library -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
    >

    <style>
        html {
            scroll-behavior: smooth;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            overflow-x: hidden;
            color: #e5e7eb;
            position: relative;
            background:
                radial-gradient(circle at top left, rgba(0, 153, 255, 0.20), transparent 28%),
                radial-gradient(circle at top right, rgba(255, 98, 0, 0.18), transparent 25%),
                radial-gradient(circle at bottom center, rgba(0, 255, 200, 0.08), transparent 30%),
                linear-gradient(135deg, #06131f 0%, #0b1f33 45%, #111827 100%);
        }

        body::before {
            content: '';
            position: fixed;
            inset: 0;
            pointer-events: none;
            z-index: -2;
            background-image:
                linear-gradient(rgba(255, 255, 255, 0.05) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255, 255, 255, 0.05) 1px, transparent 1px);
            background-size: 70px 70px;
            opacity: 0.18;
            mask-image: radial-gradient(circle at center, black 45%, transparent 100%);
            -webkit-mask-image: radial-gradient(circle at center, black 45%, transparent 100%);
        }

        body::after {
            content: '';
            position: fixed;
            inset: 0;
            pointer-events: none;
            z-index: -1;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 600 600'%3E%3Cfilter id='a'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='.65' numOctaves='3' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23a)'/%3E%3C/svg%3E");
            background-repeat: repeat;
            background-size: 180px;
            opacity: 0.06;
            mix-blend-mode: soft-light;
        }

        .bg-blobs {
            position: fixed;
            inset: 0;
            pointer-events: none;
            z-index: -3;
            overflow: hidden;
        }

        .bg-blobs::before,
        .bg-blobs::after {
            content: '';
            position: absolute;
            width: 500px;
            height: 500px;
            border-radius: 50%;
            filter: blur(80px);
            opacity: 0.35;
            animation: floatBlob 14s ease-in-out infinite;
        }

        .bg-blobs::before {
            background: rgba(255, 98, 0, 0.35);
            top: -120px;
            left: -100px;
        }

        .bg-blobs::after {
            background: rgba(0, 153, 255, 0.28);
            bottom: -160px;
            right: -120px;
            animation-delay: 4s;
        }

        @keyframes floatBlob {
            0%,
            100% {
                transform: translate(0, 0) scale(1);
            }

            50% {
                transform: translate(20px, 30px) scale(1.08);
            }
        }

        /* Main navigation */
        .navbar {
            position: fixed;
            top: 0;
            width: 100%;
            padding: 1rem 5%;
            background: rgba(5, 10, 20, 0.35);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            z-index: 1000;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            width: 54px;
            height: 54px;
            font-size: 28px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.12);
            color: #ffffff;
            cursor: pointer;
            text-decoration: none;
            transition: transform 0.25s ease, background 0.25s ease, color 0.25s ease;
        }

        .logo:hover {
            transform: scale(1.05);
            background: rgba(255, 98, 0, 0.25);
            color: #ffaa5f;
        }

        .nav-right {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .nav-links {
            display: flex;
            list-style: none;
            gap: 2rem;
            align-items: center;
            margin: 0;
            padding: 0;
        }

        .nav-links a {
            color: rgba(255, 255, 255, 0.88);
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s ease;
        }

        .nav-links a:hover {
            color: #ff6200;
        }

        /* Profile menu */
        .profile-dropdown {
            position: relative;
            display: inline-block;
        }

        .profile-btn {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.12);
            color: #e5e7eb;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            text-decoration: none;
            transition: background 0.25s ease, border-color 0.25s ease, transform 0.2s ease;
        }

        .profile-btn:hover {
            background: rgba(255, 98, 0, 0.18);
            border-color: rgba(255, 98, 0, 0.35);
            transform: translateY(-1px);
        }

        .profile-menu {
            position: absolute;
            top: calc(100% + 6px);
            right: 0;
            min-width: 190px;
            padding: 8px 0;
            display: none;
            flex-direction: column;
            z-index: 1100;
            background: rgba(10, 15, 25, 0.95);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.10);
            border-radius: 12px;
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.35);
        }

        .profile-dropdown:hover .profile-menu,
        .profile-dropdown:focus-within .profile-menu {
            display: flex;
        }

        .profile-menu a {
            padding: 10px 14px;
            display: flex;
            align-items: center;
            gap: 10px;
            color: #e5e7eb;
            text-decoration: none;
            transition: background 0.2s ease;
        }

        .profile-menu a:hover {
            background: rgba(255, 255, 255, 0.06);
        }

        /* Mobile menu button */
        .hamburger {
            display: none;
            flex-direction: column;
            gap: 4px;
            border: 0;
            background: transparent;
            cursor: pointer;
        }

        .hamburger span {
            width: 25px;
            height: 3px;
            background: #ffffff;
            transition: 0.3s ease;
        }

        /* Mobile side panel */
        .side-panel {
            position: fixed;
            top: 0;
            right: -300px;
            width: 300px;
            height: 100vh;
            z-index: 999;
            padding: 5rem 2rem 2rem;
            display: flex;
            flex-direction: column;
            gap: 14px;
            background: rgba(10, 15, 25, 0.80);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border-left: 1px solid rgba(255, 255, 255, 0.10);
            transition: right 0.3s ease;
        }

        .side-panel.open {
            right: 0;
        }

        .side-panel a {
            padding: 10px 12px;
            color: #e5e7eb;
            text-decoration: none;
            font-weight: 600;
            border-radius: 8px;
            transition: background 0.2s ease;
        }

        .side-panel a:hover {
            background: rgba(255, 255, 255, 0.06);
        }

        /* Allows page content to start below the fixed navigation bar */
        main {
            padding-top: 86px;
        }

        @media (max-width: 768px) {
            .nav-links {
                display: none;
            }

            .hamburger {
                display: flex;
            }
        }

        @media (max-width: 480px) {
            .navbar {
                padding: 0.8rem 4%;
            }

            .logo {
                width: 50px;
                height: 50px;
            }
        }
    </style>
</head>
<body>
    <div class="bg-blobs"></div>

    <nav class="navbar">
        <a class="logo" href="<?= htmlspecialchars($homeUrl) ?>" aria-label="CoolAir HVAC home">
            <i class="bi bi-snow2"></i>
        </a>

        <div class="nav-right">
            <ul class="nav-links">
                <li><a href="<?= htmlspecialchars($homeUrl) ?>">Home</a></li>

                <?php if (!$isAdmin): ?>
                    <li><a href="<?= htmlspecialchars($aboutUrl) ?>">About</a></li>
                    <li><a href="<?= htmlspecialchars($servicesUrl) ?>">Services</a></li>
                    <li><a href="<?= htmlspecialchars($contactUrl) ?>">Contact</a></li>
                <?php endif; ?>

                <?php if ($isLoggedIn): ?>
                    <li class="profile-dropdown">
                        <a href="<?= htmlspecialchars($profileUrl) ?>" class="profile-btn" title="Profile">
                            <i class="bi bi-person-circle"></i>
                        </a>

                        <div class="profile-menu">
                            <a href="<?= htmlspecialchars($profileUrl) ?>">
                                <i class="bi bi-person"></i> Profile
                            </a>

                            <a href="<?= htmlspecialchars($isAdmin ? $requestsUrl : $myRequestsUrl) ?>">
                                <i class="bi bi-clock-history"></i>
                                <?= $isAdmin ? 'Requests' : 'My Requests' ?>
                            </a>

                            <a href="<?= htmlspecialchars($logoutUrl) ?>">
                                <i class="bi bi-box-arrow-right"></i> Logout
                            </a>
                        </div>
                    </li>
                <?php else: ?>
                    <li><a href="<?= htmlspecialchars($loginUrl) ?>">Login</a></li>
                <?php endif; ?>
            </ul>

            <button
                class="hamburger"
                type="button"
                aria-label="Open navigation menu"
                aria-controls="sidePanel"
                aria-expanded="false"
                onclick="togglePanel(this)"
            >
                <span></span>
                <span></span>
                <span></span>
            </button>
        </div>
    </nav>

    <aside class="side-panel" id="sidePanel">
        <a href="<?= htmlspecialchars($homeUrl) ?>">Home</a>

        <?php if (!$isAdmin): ?>
            <a href="<?= htmlspecialchars($aboutUrl) ?>">About</a>
            <a href="<?= htmlspecialchars($servicesUrl) ?>">Services</a>
            <a href="<?= htmlspecialchars($contactUrl) ?>">Contact</a>
        <?php endif; ?>

        <?php if ($isLoggedIn): ?>
            <a href="<?= htmlspecialchars($profileUrl) ?>">Profile</a>
            <a href="<?= htmlspecialchars($isAdmin ? $requestsUrl : $myRequestsUrl) ?>">
                <?= $isAdmin ? 'Requests' : 'My Requests' ?>
            </a>
            <a href="<?= htmlspecialchars($logoutUrl) ?>">Logout</a>
        <?php else: ?>
            <a href="<?= htmlspecialchars($loginUrl) ?>">Login</a>
        <?php endif; ?>
    </aside>

    <script>
        function togglePanel(button) {
            const panel = document.getElementById('sidePanel');
            const isOpen = panel.classList.toggle('open');

            button.setAttribute('aria-expanded', String(isOpen));
        }
    </script>

    <main>