<?php
$pageTitle = 'My Profile | CoolAir HVAC';
$basePath = '../';

require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../database/db_connect.php';

if (!$isLoggedIn) {
    header('Location: login.php?error=' . urlencode('Please log in first.'));
    exit;
}

try {
    $statement = $pdo->prepare(
        'SELECT full_name, email, role, created_at
         FROM users
         WHERE id = :user_id
         LIMIT 1'
    );

    $statement->execute([
        ':user_id' => $_SESSION['user_id'],
    ]);

    $user = $statement->fetch();

    if (!$user) {
        header('Location: logout.php');
        exit;
    }
} catch (PDOException $exception) {
    exit('Unable to load profile information. Please try again later.');
}

$name = $user['full_name'];
$email = $user['email'];
$role = $user['role'];
$memberSince = date('d M Y', strtotime($user['created_at']));
?>

<style>
    .profile-container {
        position: relative;
        z-index: 1;
        max-width: 900px;
        margin: 34px auto 60px;
        padding: 0 20px;
    }

    .profile-card {
        padding: 40px 28px;
        background: rgba(255, 255, 255, 0.04);
        border: 1px solid rgba(255, 255, 255, 0.10);
        border-radius: 20px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.35);
        backdrop-filter: blur(14px);
        -webkit-backdrop-filter: blur(14px);
    }

    .profile-header {
        display: flex;
        align-items: center;
        gap: 18px;
        margin-bottom: 28px;
    }

    .profile-avatar {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 64px;
        height: 64px;
        color: #ffffff;
        font-size: 30px;
        background: linear-gradient(135deg, #ff6200, #ff8a1f);
        border-radius: 50%;
        box-shadow: 0 10px 25px rgba(255, 98, 0, 0.35);
    }

    .profile-title h1 {
        margin-bottom: 4px;
        color: #ffffff;
        font-size: 28px;
        font-weight: 700;
    }

    .profile-role {
        color: rgba(255, 255, 255, 0.70);
        font-size: 13px;
        letter-spacing: 0.8px;
        text-transform: uppercase;
    }

    .profile-section {
        margin-bottom: 26px;
    }

    .profile-section h2 {
        margin-bottom: 14px;
        color: #ffffff;
        font-size: 18px;
        font-weight: 600;
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 14px 20px;
    }

    .info-item {
        padding: 14px;
        background: rgba(255, 255, 255, 0.04);
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 12px;
    }

    .info-label {
        margin-bottom: 4px;
        color: rgba(255, 255, 255, 0.60);
        font-size: 12px;
    }

    .info-value {
        color: #e5e7eb;
        font-size: 15px;
        word-break: break-word;
    }

    .profile-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        margin-top: 10px;
    }

    .btn-profile {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 16px;
        font-size: 14px;
        font-weight: 600;
        text-decoration: none;
        border: 1px solid rgba(255, 255, 255, 0.12);
        border-radius: 10px;
        transition: transform 0.2s ease, box-shadow 0.2s ease, background 0.2s ease;
    }

    .btn-primary {
        color: #ffffff;
        background: linear-gradient(135deg, #ff6200, #ff8a1f);
        box-shadow: 0 10px 25px rgba(255, 98, 0, 0.25);
    }

    .btn-primary:hover {
        color: #ffffff;
        box-shadow: 0 14px 30px rgba(255, 98, 0, 0.32);
        transform: translateY(-2px);
    }

    .btn-secondary {
        color: #e5e7eb;
        background: rgba(255, 255, 255, 0.06);
    }

    .btn-secondary:hover {
        color: #e5e7eb;
        background: rgba(255, 255, 255, 0.10);
        transform: translateY(-1px);
    }

    @media (max-width: 700px) {
        .profile-header {
            flex-direction: column;
            align-items: flex-start;
        }

        .info-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="profile-container">
    <div class="profile-card">
        <div class="profile-header">
            <div class="profile-avatar">
                <i class="bi bi-person"></i>
            </div>

            <div class="profile-title">
                <h1><?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8') ?></h1>

                <div class="profile-role">
                    <?= $role === 'admin' ? 'Administrator' : 'Client' ?>
                </div>
            </div>
        </div>

        <div class="profile-section">
            <h2>Account Information</h2>

            <div class="info-grid">
                <div class="info-item">
                    <div class="info-label">Name</div>
                    <div class="info-value">
                        <?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8') ?>
                    </div>
                </div>

                <div class="info-item">
                    <div class="info-label">Email</div>
                    <div class="info-value">
                        <?= htmlspecialchars($email, ENT_QUOTES, 'UTF-8') ?>
                    </div>
                </div>

                <div class="info-item">
                    <div class="info-label">Role</div>
                    <div class="info-value">
                        <?= $role === 'admin' ? 'Admin' : 'Client' ?>
                    </div>
                </div>

                <div class="info-item">
                    <div class="info-label">Member Since</div>
                    <div class="info-value">
                        <?= htmlspecialchars($memberSince, ENT_QUOTES, 'UTF-8') ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="profile-actions">
            <?php if ($role === 'admin'): ?>
                <a href="requests.php" class="btn-profile btn-primary">
                    <i class="bi bi-clock-history"></i>
                    Requests
                </a>

                <a href="complaint.php" class="btn-profile btn-primary">
                    <i class="bi bi-chat-dots"></i>
                    Complaint
                </a>
            <?php else: ?>
                <a href="my-requests.php" class="btn-profile btn-primary">
                    <i class="bi bi-clock-history"></i>
                    My Requests
                </a>
            <?php endif; ?>

            <a href="../all_pages/index.php" class="btn-profile btn-secondary">
                <i class="bi bi-house"></i>
                Back to Home
            </a>

            <a href="logout.php" class="btn-profile btn-secondary">
                <i class="bi bi-box-arrow-right"></i>
                Logout
            </a>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>