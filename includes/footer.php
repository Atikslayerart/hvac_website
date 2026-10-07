<?php
/**
 * Shared website footer.
 * File: includes/footer.php
 *
 * This file expects these variables from includes/header.php:
 * $companyName, $tagline, $basePath, $isLoggedIn, and $isAdmin.
 *
 * Fallback values are included so the footer does not break if it is
 * accidentally included without header.php.
 */

$companyName = $companyName ?? 'CoolAir HVAC';
$tagline = $tagline ?? 'Comfort You Can Trust';
$basePath = $basePath ?? '';

$isLoggedIn = $isLoggedIn ?? isset($_SESSION['user_id']);
$isAdmin = $isAdmin ?? (($_SESSION['role'] ?? '') === 'admin');

$address = 'F-383, Third Floor, Chandani Chowk, West Delhi, New Delhi - 110059';
$phone = '98xxxxxx';
$email = 'coolairhvac@gmail.com';

$homeUrl = $basePath . 'all_pages/index.php';
$aboutUrl = $basePath . 'all_pages/about.php';
$servicesUrl = $basePath . 'all_pages/services.php';
$contactUrl = $basePath . 'all_pages/contact.php';
$scheduleUrl = $basePath . 'all_pages/schedule.php';

$complaintUrl = $basePath . 'login/complaint.php';
$profileUrl = $basePath . 'login/profile.php';
$requestsUrl = $basePath . 'login/requests.php';
$myRequestsUrl = $basePath . 'login/my-requests.php';
?>

    </main>

    <footer class="footer">
        <div class="footer-inner">
            <div class="footer-brand">
                <h3><?= htmlspecialchars($companyName) ?></h3>
                <p><?= htmlspecialchars($tagline) ?></p>
            </div>

            <div class="footer-links">
                <h4>Quick Links</h4>

                <a href="<?= htmlspecialchars($homeUrl) ?>">Home</a>

                <?php if ($isLoggedIn && $isAdmin): ?>
                    <a href="<?= htmlspecialchars($requestsUrl) ?>">Requests</a>
                    <a href="<?= htmlspecialchars($complaintUrl) ?>">Complaint</a>
                <?php else: ?>
                    <a href="<?= htmlspecialchars($servicesUrl) ?>">Services</a>
                    <a href="<?= htmlspecialchars($aboutUrl) ?>">About</a>
                    <a href="<?= htmlspecialchars($contactUrl) ?>">Contact</a>
                <?php endif; ?>
            </div>

            <div class="footer-contact">
                <h4>Contact</h4>
                <p><?= htmlspecialchars($address) ?></p>
                <p>Phone: <?= htmlspecialchars($phone) ?></p>
                <p>Email: <?= htmlspecialchars($email) ?></p>
            </div>
        </div>

        <div class="footer-bottom">
            <p>&copy; <?= date('Y') ?> <?= htmlspecialchars($companyName) ?>. All rights reserved.</p>
        </div>
    </footer>

    <style>
        .footer {
            padding: 50px 5% 20px;
            background: rgba(5, 10, 20, 0.72);
            border-top: 1px solid rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
        }

        .footer-inner {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 2fr 1fr 1.5fr;
            gap: 2rem;
            padding-bottom: 30px;
        }

        .footer h3,
        .footer h4 {
            color: #ffffff;
            margin-bottom: 1rem;
        }

        .footer p,
        .footer a {
            color: rgba(255, 255, 255, 0.78);
            text-decoration: none;
            line-height: 1.7;
        }

        .footer a:hover {
            color: #ff6200;
        }

        .footer-links {
            display: flex;
            flex-direction: column;
            gap: 0.6rem;
        }

        .footer-bottom {
            border-top: 1px solid rgba(255, 255, 255, 0.08);
            padding-top: 18px;
            text-align: center;
        }

        @media (max-width: 768px) {
            .footer-inner {
                grid-template-columns: 1fr;
            }
        }
    </style>
</body>
</html>