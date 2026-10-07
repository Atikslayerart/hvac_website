<?php
session_start();

require_once __DIR__ . '/../database/db_connect.php';

date_default_timezone_set('Asia/Kolkata');

if (
    !isset($_SESSION['user_id']) ||
    ($_SESSION['role'] ?? '') !== 'admin'
) {
    header('Location: login.php?error=' . urlencode('Unauthorized access.'));
    exit;
}

function e(mixed $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function formatComplaintDate(?string $date): string
{
    if (empty($date)) {
        return '—';
    }

    $timestamp = strtotime($date);

    return $timestamp === false
        ? '—'
        : date('d M Y, h:i A', $timestamp);
}

$statusLabels = [
    'Pending' => 'Pending',
    'In Progress' => 'In Progress',
    'Resolved' => 'Resolved',
];

$statusMap = [
    'process' => 'In Progress',
    'complete' => 'Resolved',
    'reopen' => 'Pending',
];

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = trim($_POST['action'] ?? '');
    $complaintId = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
    $submittedToken = $_POST['csrf_token'] ?? '';

    if (!hash_equals($_SESSION['csrf_token'], $submittedToken)) {
        $_SESSION['complaint_message'] =
            'Your form session has expired. Please refresh the page and try again.';

        $_SESSION['complaint_message_type'] = 'error';
    } elseif (
        !$complaintId ||
        $complaintId <= 0 ||
        !isset($statusMap[$action])
    ) {
        $_SESSION['complaint_message'] = 'Invalid complaint update request.';
        $_SESSION['complaint_message_type'] = 'error';
    } else {
        try {
            $newStatus = $statusMap[$action];

            $updateStatement = $pdo->prepare(
                'UPDATE complaints
                 SET status = :status, updated_at = NOW()
                 WHERE id = :id'
            );

            $updateStatement->execute([
                ':status' => $newStatus,
                ':id' => $complaintId,
            ]);

            if ($updateStatement->rowCount() > 0) {
                $_SESSION['complaint_message'] =
                    'Complaint #' . $complaintId . ' updated to: ' . $newStatus;

                $_SESSION['complaint_message_type'] = 'success';
            } else {
                $_SESSION['complaint_message'] =
                    'Complaint was not found or its status was already unchanged.';

                $_SESSION['complaint_message_type'] = 'error';
            }
        } catch (PDOException $exception) {
            $_SESSION['complaint_message'] =
                'Failed to update complaint status. Please try again.';

            $_SESSION['complaint_message_type'] = 'error';
        }
    }

    header('Location: complaint.php');
    exit;
}

$message = $_SESSION['complaint_message'] ?? '';
$messageType = $_SESSION['complaint_message_type'] ?? '';

unset(
    $_SESSION['complaint_message'],
    $_SESSION['complaint_message_type']
);

$complaints = [];
$databaseError = '';

try {
    $statement = $pdo->query(
        'SELECT
            id,
            name,
            phone,
            email,
            project_name,
            complaint_type,
            message,
            status,
            created_at
         FROM complaints
         ORDER BY created_at DESC'
    );

    $complaints = $statement->fetchAll();
} catch (PDOException $exception) {
    $databaseError = 'Unable to load complaints.';
}

$pageTitle = 'Complaint Management | Admin | CoolAir HVAC';
$basePath = '../';

require_once __DIR__ . '/../includes/header.php';
?>

<style>
    .complaint-admin-page {
        min-height: 100vh;
        padding: 44px 5% 80px;
    }

    .complaint-admin-container {
        width: 100%;
        max-width: 1320px;
        margin: 0 auto;
    }

    .complaint-admin-header {
        display: flex;
        flex-wrap: wrap;
        align-items: flex-end;
        justify-content: space-between;
        gap: 24px;
        margin-bottom: 28px;
    }

    .complaint-admin-title {
        flex: 1 1 600px;
        min-width: 280px;
    }

    .complaint-admin-title h1 {
        margin: 0;
        color: #ffffff;
        font-size: clamp(2rem, 4vw, 3rem);
        line-height: 1.15;
        text-shadow: 0 8px 32px rgba(0, 0, 0, 0.35);
    }

    .complaint-admin-title p {
        max-width: 680px;
        margin: 12px 0 0;
        color: rgba(255, 255, 255, 0.78);
        line-height: 1.7;
    }

    .back-profile-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        margin-top: 18px;
        padding: 10px 15px;
        color: #ffd0a8;
        font-size: 0.88rem;
        font-weight: 600;
        text-decoration: none;
        background: rgba(255, 123, 50, 0.12);
        border: 1px solid rgba(255, 154, 75, 0.42);
        border-radius: 10px;
        transition: transform 0.2s ease, color 0.2s ease, background 0.2s ease, border-color 0.2s ease;
    }

    .back-profile-btn span {
        font-size: 1.15rem;
        line-height: 1;
    }

    .back-profile-btn:hover {
        color: #ffffff;
        background: rgba(255, 123, 50, 0.25);
        border-color: rgba(255, 154, 75, 0.75);
        transform: translateX(-3px);
    }

    .complaint-alert {
        margin-bottom: 20px;
        padding: 13px 16px;
        line-height: 1.6;
        text-align: center;
        border-radius: 14px;
    }

    .complaint-alert.success {
        color: #c9f9d8;
        background: rgba(34, 197, 94, 0.16);
        border: 1px solid rgba(34, 197, 94, 0.34);
    }

    .complaint-alert.error {
        color: #ffe0e4;
        background: rgba(239, 68, 68, 0.16);
        border: 1px solid rgba(239, 68, 68, 0.34);
    }

    .complaint-table-card {
        width: 100%;
        max-width: 100%;
        padding: 18px;
        overflow-x: auto;
        overflow-y: visible;
        background: rgba(255, 255, 255, 0.06);
        border: 1px solid rgba(255, 255, 255, 0.13);
        border-radius: 22px;
        box-shadow: 0 20px 45px rgba(0, 0, 0, 0.25);
        backdrop-filter: blur(18px);
        -webkit-backdrop-filter: blur(18px);
        scrollbar-width: thin;
        scrollbar-color: #ff7b32 rgba(255, 255, 255, 0.08);
    }

    .complaint-table-card::-webkit-scrollbar {
        height: 8px;
    }

    .complaint-table-card::-webkit-scrollbar-track {
        background: rgba(255, 255, 255, 0.08);
        border-radius: 20px;
    }

    .complaint-table-card::-webkit-scrollbar-thumb {
        background: #ff7b32;
        border-radius: 20px;
    }

    .complaint-table {
        width: 100%;
        min-width: 1200px;
        table-layout: fixed;
        border-collapse: separate;
        border-spacing: 0;
    }

    .complaint-table th,
    .complaint-table td {
        padding: 15px 13px;
        overflow-wrap: anywhere;
        text-align: left;
        vertical-align: middle;
        word-break: break-word;
        border-bottom: 1px solid rgba(255, 255, 255, 0.09);
    }

    .complaint-table th {
        color: #ffad70;
        font-size: 0.84rem;
        font-weight: 700;
        line-height: 1.3;
        letter-spacing: 0.02em;
        white-space: nowrap;
        background: rgba(255, 98, 0, 0.10);
    }

    .complaint-table th:first-child {
        border-radius: 12px 0 0 12px;
    }

    .complaint-table th:last-child {
        border-radius: 0 12px 12px 0;
    }

    .complaint-table td {
        color: rgba(255, 255, 255, 0.87);
        font-size: 0.92rem;
        line-height: 1.55;
    }

    .complaint-table tbody tr {
        transition: background 0.25s ease;
    }

    .complaint-table tbody tr:hover {
        background: rgba(255, 255, 255, 0.055);
    }

    .complaint-table tbody tr:last-child td {
        border-bottom: 0;
    }

    .complaint-table th:nth-child(1),
    .complaint-table td:nth-child(1) {
        width: 13%;
    }

    .complaint-table th:nth-child(2),
    .complaint-table td:nth-child(2) {
        width: 17%;
    }

    .complaint-table th:nth-child(3),
    .complaint-table td:nth-child(3) {
        width: 14%;
    }

    .complaint-table th:nth-child(4),
    .complaint-table td:nth-child(4) {
        width: 13%;
    }

    .complaint-table th:nth-child(5),
    .complaint-table td:nth-child(5) {
        width: 22%;
    }

    .complaint-table th:nth-child(6),
    .complaint-table td:nth-child(6) {
        width: 11%;
    }

    .complaint-table th:nth-child(7),
    .complaint-table td:nth-child(7) {
        width: 20%;
    }

    .complaint-table a {
        color: #ff9a4b;
        text-decoration: none;
        transition: color 0.2s ease;
    }

    .complaint-table a:hover {
        color: #ffffff;
        text-decoration: underline;
    }

    .complaint-message {
        min-width: 0;
        padding: 15px 13px;
        vertical-align: middle;
    }

    .complaint-message-text {
        display: block;
        width: 100%;
        max-width: 350px;
        color: rgba(255, 255, 255, 0.88);
        line-height: 1.65;
        overflow-wrap: break-word;
        word-break: normal;
        white-space: normal;
    }

    .complaint-date {
        min-width: 0;
        color: rgba(255, 255, 255, 0.72);
        line-height: 1.45;
        white-space: normal;
    }

    .complaint-status {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 105px;
        padding: 7px 10px;
        font-size: 0.72rem;
        font-weight: 700;
        line-height: 1.2;
        letter-spacing: 0.03em;
        text-align: center;
        text-transform: uppercase;
        border-radius: 999px;
    }

    .status-pending {
        color: #ffe4ad;
        background: rgba(251, 191, 36, 0.18);
        border: 1px solid rgba(251, 191, 36, 0.42);
    }

    .status-in-progress {
        color: #c8e7ff;
        background: rgba(59, 130, 246, 0.18);
        border: 1px solid rgba(59, 130, 246, 0.42);
    }

    .status-resolved {
        color: #b9f8d9;
        background: rgba(16, 185, 129, 0.18);
        border: 1px solid rgba(16, 185, 129, 0.42);
    }

    .complaint-actions {
        display: grid;
        grid-template-columns: repeat(2, minmax(82px, 1fr));
        gap: 8px;
        width: 100%;
        min-width: 185px;
        margin: 10px 0 0;
        align-items: center;
    }

    .complaint-action-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 100%;
        min-height: 36px;
        padding: 8px 9px;
        color: #ffffff;
        font-family: inherit;
        font-size: 0.74rem;
        font-weight: 600;
        line-height: 1.2;
        text-align: center;
        cursor: pointer;
        background: rgba(255, 255, 255, 0.07);
        border: 1px solid rgba(255, 255, 255, 0.16);
        border-radius: 9px;
        transition: transform 0.2s ease, background 0.2s ease, border-color 0.2s ease, box-shadow 0.2s ease;
    }

    .complaint-action-btn:hover {
        box-shadow: 0 5px 14px rgba(0, 0, 0, 0.18);
        transform: translateY(-2px);
    }

    .complaint-action-btn:active {
        transform: translateY(0);
    }

    .complaint-action-btn:focus-visible {
        outline: 2px solid #ff9a4b;
        outline-offset: 2px;
    }

    .btn-process {
        color: #c8e7ff;
        background: rgba(59, 130, 246, 0.18);
        border-color: rgba(59, 130, 246, 0.35);
    }

    .btn-process:hover {
        background: rgba(59, 130, 246, 0.35);
        border-color: rgba(59, 130, 246, 0.65);
    }

    .btn-complete {
        color: #b9f8d9;
        background: rgba(16, 185, 129, 0.18);
        border-color: rgba(16, 185, 129, 0.35);
    }

    .btn-complete:hover {
        background: rgba(16, 185, 129, 0.35);
        border-color: rgba(16, 185, 129, 0.65);
    }

    .btn-reopen {
        color: #ffe4ad;
        background: rgba(251, 191, 36, 0.18);
        border-color: rgba(251, 191, 36, 0.35);
    }

    .btn-reopen:hover {
        background: rgba(251, 191, 36, 0.35);
        border-color: rgba(251, 191, 36, 0.65);
    }

    .complaint-closed {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 36px;
        color: rgba(255, 255, 255, 0.52);
        font-size: 0.82rem;
        white-space: nowrap;
    }

    .complaint-empty,
    .complaint-error {
        padding: 50px 20px;
        line-height: 1.7;
        text-align: center;
        border-radius: 16px;
    }

    .complaint-empty {
        color: rgba(255, 255, 255, 0.76);
        background: rgba(255, 255, 255, 0.04);
    }

    .complaint-error {
        color: #ffe0e4;
        background: rgba(239, 68, 68, 0.14);
        border: 1px solid rgba(239, 68, 68, 0.30);
    }

    @media (max-width: 900px) {
        .complaint-admin-header {
            align-items: flex-start;
        }
    }

    @media (max-width: 768px) {
        .complaint-admin-page {
            padding: 34px 4% 65px;
        }

        .complaint-admin-header {
            gap: 18px;
        }

        .complaint-admin-title {
            width: 100%;
        }

        .complaint-admin-title h1 {
            font-size: 2rem;
        }

        .complaint-table-card {
            padding: 12px;
            border-radius: 18px;
        }

        .complaint-actions {
            grid-template-columns: repeat(2, 86px);
        }

        .complaint-action-btn {
            min-height: 36px;
            padding: 8px 7px;
            font-size: 0.72rem;
        }
    }
</style>

<div class="complaint-admin-page">
    <div class="complaint-admin-container">
        <div class="complaint-admin-header">
            <div class="complaint-admin-title">
                <h1>Complaint Management</h1>

                <p>
                    Review customer complaints, track their progress, and
                    update their service status.
                </p>

                <a href="profile.php" class="back-profile-btn">
                    <span aria-hidden="true">←</span>
                    Back to Profile
                </a>
            </div>
        </div>

        <?php if ($message !== ''): ?>
            <div class="complaint-alert <?= e($messageType) ?>" role="alert">
                <?= e($message) ?>
            </div>
        <?php endif; ?>

        <section class="complaint-table-card">
            <?php if ($databaseError !== ''): ?>
                <div class="complaint-error">
                    <?= e($databaseError) ?>
                </div>
            <?php elseif ($complaints === []): ?>
                <div class="complaint-empty">
                    No complaints have been submitted yet.
                </div>
            <?php else: ?>
                <table class="complaint-table">
                    <thead>
                        <tr>
                            <th scope="col">Customer</th>
                            <th scope="col">Contact</th>
                            <th scope="col">Project / Site</th>
                            <th scope="col">Complaint Type</th>
                            <th scope="col">Complaint Message</th>
                            <th scope="col">Received</th>
                            <th scope="col">Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php foreach ($complaints as $complaint): ?>
                            <?php
                            $status = $complaint['status'] ?? 'Pending';

                            if (!array_key_exists($status, $statusLabels)) {
                                $status = 'Pending';
                            }

                            $statusClass = strtolower(str_replace(' ', '-', $status));
                            ?>

                            <tr>
                                <td><?= e($complaint['name'] ?? '—') ?></td>

                                <td>
                                    <?php if (!empty($complaint['phone'])): ?>
                                        <a href="tel:<?= e($complaint['phone']) ?>">
                                            <?= e($complaint['phone']) ?>
                                        </a>
                                    <?php else: ?>
                                        —
                                    <?php endif; ?>

                                    <br>

                                    <?php if (!empty($complaint['email'])): ?>
                                        <a href="mailto:<?= e($complaint['email']) ?>">
                                            <?= e($complaint['email']) ?>
                                        </a>
                                    <?php else: ?>
                                        —
                                    <?php endif; ?>
                                </td>

                                <td>
                                    <?= !empty($complaint['project_name'])
                                        ? e($complaint['project_name'])
                                        : '—' ?>
                                </td>

                                <td><?= e($complaint['complaint_type'] ?? '—') ?></td>

                                <td class="complaint-message">
                                    <span class="complaint-message-text">
                                        <?= nl2br(e($complaint['message'] ?? '—')) ?>
                                    </span>
                                </td>

                                <td class="complaint-date">
                                    <?= e(formatComplaintDate($complaint['created_at'] ?? null)) ?>
                                </td>

                                <td>
                                    <span class="complaint-status status-<?= e($statusClass) ?>">
                                        <?= e($statusLabels[$status]) ?>
                                    </span>

                                    <form
                                        method="post"
                                        action="complaint.php"
                                        class="complaint-actions"
                                    >
                                        <input
                                            type="hidden"
                                            name="csrf_token"
                                            value="<?= e($_SESSION['csrf_token']) ?>"
                                        >

                                        <input
                                            type="hidden"
                                            name="id"
                                            value="<?= (int) $complaint['id'] ?>"
                                        >

                                        <?php if ($status === 'Pending'): ?>
                                            <button
                                                type="submit"
                                                name="action"
                                                value="process"
                                                class="complaint-action-btn btn-process"
                                            >
                                                Process
                                            </button>
                                        <?php elseif ($status === 'In Progress'): ?>
                                            <button
                                                type="submit"
                                                name="action"
                                                value="complete"
                                                class="complaint-action-btn btn-complete"
                                            >
                                                Resolve
                                            </button>
                                        <?php elseif ($status === 'Resolved'): ?>
                                            <button
                                                type="submit"
                                                name="action"
                                                value="reopen"
                                                class="complaint-action-btn btn-reopen"
                                            >
                                                Reopen
                                            </button>
                                        <?php endif; ?>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </section>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>