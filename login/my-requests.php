<?php
$pageTitle = 'My Requests | CoolAir HVAC';
$basePath = '../';

require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../database/db_connect.php';

if (!$isLoggedIn) {
    header('Location: login.php?error=' . urlencode('Please log in first.'));
    exit;
}

if ($isAdmin) {
    header('Location: requests.php');
    exit;
}

$appointments = [];
$errorMessage = '';

try {
    $statement = $pdo->prepare(
        'SELECT
            id,
            name,
            email,
            phone,
            service_type,
            preferred_date,
            preferred_time,
            message,
            status,
            created_at
         FROM appointments
         WHERE user_id = :user_id
         ORDER BY created_at DESC'
    );

    $statement->execute([
        ':user_id' => $_SESSION['user_id'],
    ]);

    $appointments = $statement->fetchAll();
} catch (PDOException $exception) {
    $errorMessage = 'Unable to load your requests right now. Please try again later.';
}

$statusLabels = [
    'pending' => 'Pending',
    'under_process' => 'Under Process',
    'confirmed' => 'Confirmed',
    'completed' => 'Completed',
    'cancelled' => 'Cancelled',
];
?>

<style>
    .requests-wrapper {
        position: relative;
        z-index: 1;
        max-width: 1100px;
        margin: 34px auto 60px;
        padding: 0 20px;
    }

    .page-title {
        margin-bottom: 36px;
        text-align: center;
    }

    .page-title h1 {
        position: relative;
        display: inline-block;
        color: #ffffff;
        font-size: clamp(2rem, 4vw, 2.8rem);
    }

    .page-title h1::after {
        content: '';
        position: absolute;
        bottom: -8px;
        left: 50%;
        width: 80px;
        height: 3px;
        background: #ff6200;
        border-radius: 2px;
        transform: translateX(-50%);
    }

    .requests-list {
        display: grid;
        gap: 18px;
    }

    .request-card {
        padding: 20px 18px;
        background: rgba(255, 255, 255, 0.06);
        border: 1px solid rgba(255, 255, 255, 0.10);
        border-radius: 18px;
        box-shadow: 0 14px 40px rgba(0, 0, 0, 0.28);
        backdrop-filter: blur(14px);
        -webkit-backdrop-filter: blur(14px);
    }

    .request-header {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        margin-bottom: 12px;
    }

    .request-service {
        color: #ffffff;
        font-size: 1.15rem;
        font-weight: 700;
    }

    .status-badge {
        padding: 6px 10px;
        font-size: 0.8rem;
        font-weight: 700;
        letter-spacing: 0.6px;
        text-transform: uppercase;
        border-radius: 999px;
    }

    .status-pending {
        color: #fde68a;
        background: rgba(251, 191, 36, 0.18);
        border: 1px solid rgba(251, 191, 36, 0.45);
    }

    .status-under_process {
        color: #93c5fd;
        background: rgba(59, 130, 246, 0.18);
        border: 1px solid rgba(59, 130, 246, 0.45);
    }

    .status-confirmed {
        color: #86efac;
        background: rgba(34, 197, 94, 0.18);
        border: 1px solid rgba(34, 197, 94, 0.45);
    }

    .status-completed {
        color: #6ee7b7;
        background: rgba(16, 185, 129, 0.18);
        border: 1px solid rgba(16, 185, 129, 0.45);
    }

    .status-cancelled {
        color: #fca5a5;
        background: rgba(239, 68, 68, 0.18);
        border: 1px solid rgba(239, 68, 68, 0.45);
    }

    .request-body {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 12px 18px;
        font-size: 0.95rem;
    }

    .request-item strong {
        display: block;
        margin-bottom: 3px;
        color: rgba(255, 255, 255, 0.70);
        font-size: 0.8rem;
    }

    .request-item span {
        color: #e5e7eb;
    }

    .request-email,
    .request-message {
        grid-column: 1 / -1;
    }

    .request-message {
        margin-top: 6px;
        padding-top: 10px;
        border-top: 1px solid rgba(255, 255, 255, 0.08);
    }

    .empty-state,
    .error-state {
        padding: 60px 20px;
        text-align: center;
        background: rgba(255, 255, 255, 0.04);
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 18px;
    }

    .empty-state h3,
    .error-state h3 {
        margin-bottom: 8px;
        color: #ffffff;
    }

    .empty-state p,
    .error-state p {
        color: rgba(255, 255, 255, 0.70);
    }

    .cta-btn {
        display: inline-block;
        margin-top: 14px;
        padding: 12px 22px;
        color: #ffffff;
        font-weight: 600;
        text-decoration: none;
        background: linear-gradient(135deg, #ff6200, #ff8a1f);
        border-radius: 50px;
        box-shadow: 0 12px 30px rgba(255, 98, 0, 0.28);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .cta-btn:hover {
        color: #ffffff;
        box-shadow: 0 16px 34px rgba(255, 98, 0, 0.36);
        transform: translateY(-2px);
    }

    @media (max-width: 700px) {
        .request-body {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="requests-wrapper">
    <div class="page-title">
        <h1>My Requests</h1>
    </div>

    <?php if ($errorMessage !== ''): ?>
        <div class="error-state">
            <h3>Unable to load requests</h3>
            <p><?= htmlspecialchars($errorMessage, ENT_QUOTES, 'UTF-8') ?></p>
        </div>
    <?php elseif ($appointments === []): ?>
        <div class="empty-state">
            <h3>No requests yet</h3>
            <p>You have not booked any appointments yet.</p>

            <a href="../all_pages/schedule.php" class="cta-btn">
                Book an Appointment
            </a>
        </div>
    <?php else: ?>
        <div class="requests-list">
            <?php foreach ($appointments as $appointment): ?>
                <?php
                $status = $appointment['status'];
                $statusClass = array_key_exists($status, $statusLabels)
                    ? 'status-' . $status
                    : 'status-pending';

                $statusLabel = $statusLabels[$status] ?? 'Pending';
                ?>

                <div class="request-card">
                    <div class="request-header">
                        <div class="request-service">
                            <?= htmlspecialchars($appointment['service_type'], ENT_QUOTES, 'UTF-8') ?>
                        </div>

                        <span class="status-badge <?= htmlspecialchars($statusClass, ENT_QUOTES, 'UTF-8') ?>">
                            <?= htmlspecialchars($statusLabel, ENT_QUOTES, 'UTF-8') ?>
                        </span>
                    </div>

                    <div class="request-body">
                        <div class="request-item">
                            <strong>Date</strong>
                            <span>
                                <?= htmlspecialchars(date('d M Y', strtotime($appointment['preferred_date'])), ENT_QUOTES, 'UTF-8') ?>
                            </span>
                        </div>

                        <div class="request-item">
                            <strong>Time</strong>
                            <span>
                                <?= htmlspecialchars(date('h:i A', strtotime($appointment['preferred_time'])), ENT_QUOTES, 'UTF-8') ?>
                            </span>
                        </div>

                        <div class="request-item">
                            <strong>Name</strong>
                            <span>
                                <?= htmlspecialchars($appointment['name'], ENT_QUOTES, 'UTF-8') ?>
                            </span>
                        </div>

                        <div class="request-item">
                            <strong>Phone</strong>
                            <span>
                                <?= htmlspecialchars($appointment['phone'], ENT_QUOTES, 'UTF-8') ?>
                            </span>
                        </div>

                        <div class="request-item request-email">
                            <strong>Email</strong>
                            <span>
                                <?= htmlspecialchars($appointment['email'], ENT_QUOTES, 'UTF-8') ?>
                            </span>
                        </div>

                        <div class="request-item request-message">
                            <strong>Message</strong>
                            <span>
                                <?= nl2br(htmlspecialchars($appointment['message'], ENT_QUOTES, 'UTF-8')) ?>
                            </span>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>