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

function formatDateValue(?string $date): string
{
    if (empty($date)) {
        return '—';
    }

    $timestamp = strtotime($date);

    return $timestamp === false
        ? $date
        : date('d M Y, h:i A', $timestamp);
}

$statusLabels = [
    'pending' => 'Pending',
    'under_process' => 'Under Process',
    'confirmed' => 'Confirmed',
    'completed' => 'Completed',
    'cancelled' => 'Cancelled',
];

$statusMap = [
    'approve' => 'confirmed',
    'process' => 'under_process',
    'complete' => 'completed',
    'cancel' => 'cancelled',
    'reopen' => 'pending',
];

$allowedStatuses = array_merge(['all'], array_keys($statusLabels));
$allowedDateFilters = ['all', 'today', 'week', 'month', 'previous'];
$allowedSorts = ['created_at_desc', 'created_at_asc', 'date_asc', 'date_desc'];

$statusFilter = $_GET['status'] ?? 'all';
$dateFilter = $_GET['date_filter'] ?? 'all';
$sortBy = $_GET['sort'] ?? 'created_at_desc';

if (!in_array($statusFilter, $allowedStatuses, true)) {
    $statusFilter = 'all';
}

if (!in_array($dateFilter, $allowedDateFilters, true)) {
    $dateFilter = 'all';
}

if (!in_array($sortBy, $allowedSorts, true)) {
    $sortBy = 'created_at_desc';
}

$filterQuery = http_build_query([
    'status' => $statusFilter,
    'date_filter' => $dateFilter,
    'sort' => $sortBy,
]);

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = trim($_POST['action'] ?? '');
    $appointmentId = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
    $submittedToken = $_POST['csrf_token'] ?? '';

    if (!hash_equals($_SESSION['csrf_token'], $submittedToken)) {
        $_SESSION['requests_message'] = 'Your form session has expired. Please try again.';
        $_SESSION['requests_message_type'] = 'error';
    } elseif (
        !$appointmentId ||
        $appointmentId <= 0 ||
        !isset($statusMap[$action])
    ) {
        $_SESSION['requests_message'] = 'Invalid request.';
        $_SESSION['requests_message_type'] = 'error';
    } else {
        try {
            $newStatus = $statusMap[$action];

            $updateStatement = $pdo->prepare(
                'UPDATE appointments
                 SET status = :status
                 WHERE id = :id'
            );

            $updateStatement->execute([
                ':status' => $newStatus,
                ':id' => $appointmentId,
            ]);

            if ($updateStatement->rowCount() > 0) {
                $_SESSION['requests_message'] =
                    'Request updated to: ' . $statusLabels[$newStatus];

                $_SESSION['requests_message_type'] = 'success';
            } else {
                $_SESSION['requests_message'] =
                    'Request was not found or its status was already unchanged.';

                $_SESSION['requests_message_type'] = 'error';
            }
        } catch (PDOException $exception) {
            $_SESSION['requests_message'] =
                'Failed to update request. Please try again.';

            $_SESSION['requests_message_type'] = 'error';
        }
    }

    header('Location: requests.php?' . $filterQuery);
    exit;
}

$message = $_SESSION['requests_message'] ?? '';
$messageType = $_SESSION['requests_message_type'] ?? '';

unset(
    $_SESSION['requests_message'],
    $_SESSION['requests_message_type']
);

$appointments = [];
$databaseError = '';

try {
    $where = '1 = 1';
    $parameters = [];

    if ($statusFilter !== 'all') {
        $where .= ' AND status = :status';
        $parameters[':status'] = $statusFilter;
    }

    if ($dateFilter === 'today') {
        $where .= ' AND preferred_date = CURDATE()';
    } elseif ($dateFilter === 'week') {
        $where .= ' AND YEARWEEK(preferred_date, 1) = YEARWEEK(CURDATE(), 1)';
    } elseif ($dateFilter === 'month') {
        $where .= ' AND MONTH(preferred_date) = MONTH(CURDATE())
                    AND YEAR(preferred_date) = YEAR(CURDATE())';
    } elseif ($dateFilter === 'previous') {
        $where .= ' AND (
            preferred_date < CURDATE()
            OR (preferred_date = CURDATE() AND preferred_time < CURTIME())
        )';
    }

    $orderBy = match ($sortBy) {
        'created_at_asc' => 'created_at ASC',
        'date_asc' => 'preferred_date ASC, preferred_time ASC',
        'date_desc' => 'preferred_date DESC, preferred_time DESC',
        default => 'created_at DESC',
    };

    $statement = $pdo->prepare(
        "SELECT
            id,
            name,
            email,
            service_type,
            preferred_date,
            preferred_time,
            status,
            created_at
         FROM appointments
         WHERE {$where}
         ORDER BY {$orderBy}"
    );

    $statement->execute($parameters);
    $appointments = $statement->fetchAll();
} catch (PDOException $exception) {
    $databaseError = 'Unable to load appointment requests.';
}

$pageTitle = 'All Requests | Admin | CoolAir HVAC';
$basePath = '../';

require_once __DIR__ . '/../includes/header.php';
?>

<style>
    .admin-wrapper {
        position: relative;
        z-index: 1;
        max-width: 1320px;
        margin: 44px auto 70px;
        padding: 0 5%;
    }

    .page-header {
        display: flex;
        flex-wrap: wrap;
        align-items: flex-end;
        justify-content: space-between;
        gap: 24px;
        margin-bottom: 28px;
    }

    .page-title {
        flex: 1 1 500px;
    }

    .page-title h1 {
        margin: 0;
        color: #ffffff;
        font-size: clamp(2rem, 4vw, 3rem);
        line-height: 1.15;
        text-shadow: 0 8px 32px rgba(0, 0, 0, 0.35);
    }

    .page-title p {
        max-width: 650px;
        margin-top: 12px;
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

    .back-profile-btn:hover {
        color: #ffffff;
        background: rgba(255, 123, 50, 0.25);
        border-color: rgba(255, 154, 75, 0.75);
        transform: translateX(-3px);
    }

    .filters {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 10px;
    }

    .filters select {
        min-width: 145px;
        padding: 10px 13px;
        color: #ffffff;
        font-family: inherit;
        font-size: 0.9rem;
        cursor: pointer;
        background: rgba(255, 255, 255, 0.08);
        border: 1px solid rgba(255, 255, 255, 0.14);
        border-radius: 12px;
        outline: none;
    }

    .filters select:focus {
        border-color: rgba(255, 154, 75, 0.8);
        box-shadow: 0 0 0 3px rgba(255, 154, 75, 0.12);
    }

    .filters option {
        color: #111111;
        background: #ffffff;
    }

    .form-alert {
        margin-bottom: 20px;
        padding: 13px 16px;
        line-height: 1.6;
        text-align: center;
        border-radius: 14px;
    }

    .form-alert.success {
        color: #c9f9d8;
        background: rgba(34, 197, 94, 0.16);
        border: 1px solid rgba(34, 197, 94, 0.34);
    }

    .form-alert.error {
        color: #ffe0e4;
        background: rgba(239, 68, 68, 0.16);
        border: 1px solid rgba(239, 68, 68, 0.34);
    }

    .requests-table-wrap {
        width: 100%;
        padding: 18px;
        overflow: visible;
        background: rgba(255, 255, 255, 0.06);
        border: 1px solid rgba(255, 255, 255, 0.13);
        border-radius: 22px;
        box-shadow: 0 20px 45px rgba(0, 0, 0, 0.25);
        backdrop-filter: blur(18px);
        -webkit-backdrop-filter: blur(18px);
    }

    .requests-table {
        width: 100%;
        min-width: 0;
        color: #e5e7eb;
        table-layout: fixed;
        border-collapse: separate;
        border-spacing: 0;
    }

    .requests-table th,
    .requests-table td {
        padding: 15px 13px;
        overflow-wrap: anywhere;
        text-align: left;
        vertical-align: middle;
        word-break: break-word;
        border-bottom: 1px solid rgba(255, 255, 255, 0.09);
    }

    .requests-table th {
        color: #ffad70;
        font-size: 0.84rem;
        font-weight: 700;
        line-height: 1.3;
        letter-spacing: 0.02em;
        white-space: nowrap;
        background: rgba(255, 98, 0, 0.10);
    }

    .requests-table th:first-child {
        border-radius: 12px 0 0 12px;
    }

    .requests-table th:last-child {
        border-radius: 0 12px 12px 0;
    }

    .requests-table td {
        color: rgba(255, 255, 255, 0.87);
        font-size: 0.92rem;
        line-height: 1.55;
    }

    .requests-table tbody tr {
        transition: background 0.25s ease;
    }

    .requests-table tbody tr:hover {
        background: rgba(255, 255, 255, 0.055);
    }

    .requests-table tbody tr:last-child td {
        border-bottom: 0;
    }

    .requests-table th:nth-child(1),
    .requests-table td:nth-child(1) {
        width: 7%;
    }

    .requests-table th:nth-child(2),
    .requests-table td:nth-child(2) {
        width: 19%;
    }

    .requests-table th:nth-child(3),
    .requests-table td:nth-child(3) {
        width: 16%;
    }

    .requests-table th:nth-child(4),
    .requests-table td:nth-child(4) {
        width: 15%;
    }

    .requests-table th:nth-child(5),
    .requests-table td:nth-child(5) {
        width: 14%;
    }

    .requests-table th:nth-child(6),
    .requests-table td:nth-child(6) {
        width: 12%;
    }

    .requests-table th:nth-child(7),
    .requests-table td:nth-child(7) {
        width: 22%;
    }

    .request-secondary-text {
        display: inline-block;
        margin-top: 4px;
        color: rgba(255, 255, 255, 0.60);
        font-size: 0.78rem;
        line-height: 1.45;
    }

    .status-badge {
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

    .status-under_process {
        color: #c8e7ff;
        background: rgba(59, 130, 246, 0.18);
        border: 1px solid rgba(59, 130, 246, 0.42);
    }

    .status-confirmed {
        color: #bff5cb;
        background: rgba(34, 197, 94, 0.18);
        border: 1px solid rgba(34, 197, 94, 0.42);
    }

    .status-completed {
        color: #b9f8d9;
        background: rgba(16, 185, 129, 0.18);
        border: 1px solid rgba(16, 185, 129, 0.42);
    }

    .status-cancelled {
        color: #ffc5ca;
        background: rgba(239, 68, 68, 0.18);
        border: 1px solid rgba(239, 68, 68, 0.42);
    }

    .action-form {
        display: grid;
        grid-template-columns: repeat(2, minmax(82px, 1fr));
        gap: 8px;
        width: 100%;
        min-width: 190px;
        margin: 0;
    }

    .btn-action {
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

    .btn-action:hover {
        box-shadow: 0 5px 14px rgba(0, 0, 0, 0.18);
        transform: translateY(-2px);
    }

    .btn-action:active {
        transform: translateY(0);
    }

    .btn-action:focus-visible {
        outline: 2px solid #ff9a4b;
        outline-offset: 2px;
    }

    .btn-approve {
        color: #bff5cb;
        background: rgba(34, 197, 94, 0.18);
        border-color: rgba(34, 197, 94, 0.35);
    }

    .btn-approve:hover {
        background: rgba(34, 197, 94, 0.35);
        border-color: rgba(34, 197, 94, 0.65);
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

    .btn-cancel {
        color: #ffc5ca;
        background: rgba(239, 68, 68, 0.18);
        border-color: rgba(239, 68, 68, 0.35);
    }

    .btn-cancel:hover {
        background: rgba(239, 68, 68, 0.35);
        border-color: rgba(239, 68, 68, 0.65);
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

    .request-closed {
        display: inline-flex;
        align-items: center;
        min-height: 36px;
        color: rgba(255, 255, 255, 0.52);
        font-size: 0.82rem;
        white-space: nowrap;
    }

    .empty-state,
    .error-state {
        padding: 55px 20px;
        line-height: 1.7;
        text-align: center;
        border-radius: 16px;
    }

    .empty-state {
        color: rgba(255, 255, 255, 0.76);
        background: rgba(255, 255, 255, 0.04);
    }

    .error-state {
        color: #ffe0e4;
        background: rgba(239, 68, 68, 0.14);
        border: 1px solid rgba(239, 68, 68, 0.30);
    }

    @media (max-width: 900px) {
        .page-header {
            align-items: flex-start;
        }

        .filters {
            width: 100%;
        }

        .filters select {
            flex: 1;
        }
    }

    @media (max-width: 768px) {
        .admin-wrapper {
            margin-top: 34px;
            padding: 0 4%;
        }

        .page-title h1 {
            font-size: 2rem;
        }

        .requests-table-wrap {
            padding: 12px;
            overflow-x: auto;
            overflow-y: visible;
            border-radius: 18px;
            scrollbar-width: thin;
            scrollbar-color: #ff7b32 rgba(255, 255, 255, 0.08);
        }

        .requests-table-wrap::-webkit-scrollbar {
            height: 8px;
        }

        .requests-table-wrap::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.08);
            border-radius: 20px;
        }

        .requests-table-wrap::-webkit-scrollbar-thumb {
            background: #ff7b32;
            border-radius: 20px;
        }

        .requests-table {
            min-width: 1160px;
        }

        .action-form {
            grid-template-columns: repeat(2, 86px);
        }

        .btn-action {
            min-height: 36px;
            padding: 8px 7px;
            font-size: 0.72rem;
        }
    }

    @media (max-width: 480px) {
        .filters {
            flex-direction: column;
            align-items: stretch;
        }

        .filters select {
            width: 100%;
        }
    }
</style>

<div class="admin-wrapper">
    <div class="page-header">
        <div class="page-title">
            <h1>All Requests</h1>

            <p>
                Review appointment requests, manage schedules, and update
                the current request status.
            </p>

            <a href="profile.php" class="back-profile-btn">
                <span aria-hidden="true">←</span>
                Back to Profile
            </a>
        </div>

        <form class="filters" method="get" action="requests.php">
            <select
                name="status"
                aria-label="Filter requests by status"
                onchange="this.form.submit()"
            >
                <option value="all" <?= $statusFilter === 'all' ? 'selected' : '' ?>>
                    All Status
                </option>
                <option value="pending" <?= $statusFilter === 'pending' ? 'selected' : '' ?>>
                    Pending
                </option>
                <option value="under_process" <?= $statusFilter === 'under_process' ? 'selected' : '' ?>>
                    Under Process
                </option>
                <option value="confirmed" <?= $statusFilter === 'confirmed' ? 'selected' : '' ?>>
                    Confirmed
                </option>
                <option value="completed" <?= $statusFilter === 'completed' ? 'selected' : '' ?>>
                    Completed
                </option>
                <option value="cancelled" <?= $statusFilter === 'cancelled' ? 'selected' : '' ?>>
                    Cancelled
                </option>
            </select>

            <select
                name="date_filter"
                aria-label="Filter requests by date"
                onchange="this.form.submit()"
            >
                <option value="all" <?= $dateFilter === 'all' ? 'selected' : '' ?>>
                    All Time
                </option>
                <option value="today" <?= $dateFilter === 'today' ? 'selected' : '' ?>>
                    Today
                </option>
                <option value="week" <?= $dateFilter === 'week' ? 'selected' : '' ?>>
                    This Week
                </option>
                <option value="month" <?= $dateFilter === 'month' ? 'selected' : '' ?>>
                    This Month
                </option>
                <option value="previous" <?= $dateFilter === 'previous' ? 'selected' : '' ?>>
                    Previous
                </option>
            </select>

            <select
                name="sort"
                aria-label="Sort requests"
                onchange="this.form.submit()"
            >
                <option value="created_at_desc" <?= $sortBy === 'created_at_desc' ? 'selected' : '' ?>>
                    Newest First
                </option>
                <option value="created_at_asc" <?= $sortBy === 'created_at_asc' ? 'selected' : '' ?>>
                    Oldest First
                </option>
                <option value="date_asc" <?= $sortBy === 'date_asc' ? 'selected' : '' ?>>
                    Date Earliest
                </option>
                <option value="date_desc" <?= $sortBy === 'date_desc' ? 'selected' : '' ?>>
                    Date Latest
                </option>
            </select>
        </form>
    </div>

    <?php if ($message !== ''): ?>
        <div class="form-alert <?= e($messageType) ?>" role="alert">
            <?= e($message) ?>
        </div>
    <?php endif; ?>

    <section class="requests-table-wrap">
        <?php if ($databaseError !== ''): ?>
            <div class="error-state">
                <?= e($databaseError) ?>
            </div>
        <?php elseif ($appointments === []): ?>
            <div class="empty-state">
                No requests found for this filter.
            </div>
        <?php else: ?>
            <table class="requests-table">
                <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Client</th>
                        <th scope="col">Service</th>
                        <th scope="col">Date &amp; Time</th>
                        <th scope="col">Status</th>
                        <th scope="col">Created At</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach ($appointments as $appointment): ?>
                        <?php
                        $status = strtolower(trim($appointment['status'] ?? 'pending'));

                        if (!array_key_exists($status, $statusLabels)) {
                            $status = 'pending';
                        }

                        $statusLabel = $statusLabels[$status];
                        ?>

                        <tr>
                            <td><?= (int) $appointment['id'] ?></td>

                            <td>
                                <?= e($appointment['name'] ?? '—') ?>

                                <span class="request-secondary-text">
                                    <?= e($appointment['email'] ?? '—') ?>
                                </span>
                            </td>

                            <td><?= e($appointment['service_type'] ?? '—') ?></td>

                            <td>
                                <?= e($appointment['preferred_date'] ?? '—') ?>

                                <span class="request-secondary-text">
                                    <?= e($appointment['preferred_time'] ?? '—') ?>
                                </span>
                            </td>

                            <td>
                                <span class="status-badge status-<?= e($status) ?>">
                                    <?= e($statusLabel) ?>
                                </span>
                            </td>

                            <td>
                                <?= e(formatDateValue($appointment['created_at'] ?? null)) ?>
                            </td>

                            <td>
                                <form
                                    class="action-form"
                                    method="post"
                                    action="requests.php?<?= e($filterQuery) ?>"
                                >
                                    <input
                                        type="hidden"
                                        name="csrf_token"
                                        value="<?= e($_SESSION['csrf_token']) ?>"
                                    >

                                    <input
                                        type="hidden"
                                        name="id"
                                        value="<?= (int) $appointment['id'] ?>"
                                    >

                                    <?php if ($status === 'pending'): ?>
                                        <button
                                            type="submit"
                                            name="action"
                                            value="approve"
                                            class="btn-action btn-approve"
                                        >
                                            Approve
                                        </button>

                                        <button
                                            type="submit"
                                            name="action"
                                            value="process"
                                            class="btn-action btn-process"
                                        >
                                            Process
                                        </button>

                                        <button
                                            type="submit"
                                            name="action"
                                            value="cancel"
                                            class="btn-action btn-cancel"
                                        >
                                            Cancel
                                        </button>
                                    <?php elseif ($status === 'under_process'): ?>
                                        <button
                                            type="submit"
                                            name="action"
                                            value="complete"
                                            class="btn-action btn-complete"
                                        >
                                            Complete
                                        </button>

                                        <button
                                            type="submit"
                                            name="action"
                                            value="cancel"
                                            class="btn-action btn-cancel"
                                        >
                                            Cancel
                                        </button>
                                    <?php elseif ($status === 'confirmed'): ?>
                                        <button
                                            type="submit"
                                            name="action"
                                            value="process"
                                            class="btn-action btn-process"
                                        >
                                            Mark Process
                                        </button>

                                        <button
                                            type="submit"
                                            name="action"
                                            value="complete"
                                            class="btn-action btn-complete"
                                        >
                                            Complete
                                        </button>

                                        <button
                                            type="submit"
                                            name="action"
                                            value="cancel"
                                            class="btn-action btn-cancel"
                                        >
                                            Cancel
                                        </button>
                                    <?php elseif ($status === 'completed'): ?>
                                        <span class="request-closed">Closed</span>

                                        <button
                                            type="submit"
                                            name="action"
                                            value="reopen"
                                            class="btn-action btn-reopen"
                                        >
                                            Reopen
                                        </button>
                                    <?php elseif ($status === 'cancelled'): ?>
                                        <button
                                            type="submit"
                                            name="action"
                                            value="reopen"
                                            class="btn-action btn-reopen"
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

<?php require_once __DIR__ . '/../includes/footer.php'; ?>