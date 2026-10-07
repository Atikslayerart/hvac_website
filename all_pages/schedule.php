<?php
session_start();

require_once __DIR__ . '/../database/db_connect.php';

date_default_timezone_set('Asia/Kolkata');

if (!isset($_SESSION['user_id'])) {
    header(
        'Location: ../login/login.php?error=' .
        urlencode('Please log in to book an appointment.')
    );
    exit;
}

$message = '';
$messageType = '';

$services = [
    'HVAC System',
    'Chiller Installation',
    'AHU / MAU',
    'Cooling Tower',
    'VRF / VRV',
    'Plant Room',
    'Pump Room',
    'Pipeline Work',
    'Consultancy',
    'Other',
];

$formData = [
    'name' => '',
    'phone' => '',
    'email' => '',
    'service' => '',
    'date' => '',
    'time' => '',
    'message' => '',
];

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $formData = [
        'name' => trim($_POST['name'] ?? ''),
        'phone' => trim($_POST['phone'] ?? ''),
        'email' => trim($_POST['email'] ?? ''),
        'service' => trim($_POST['service'] ?? ''),
        'date' => trim($_POST['date'] ?? ''),
        'time' => trim($_POST['time'] ?? ''),
        'message' => trim($_POST['message'] ?? ''),
    ];

    $submittedToken = $_POST['csrf_token'] ?? '';
    $selectedDate = DateTime::createFromFormat('Y-m-d', $formData['date']);
    $selectedTime = DateTime::createFromFormat('H:i', $formData['time']);

    if (!hash_equals($_SESSION['csrf_token'], $submittedToken)) {
        $message = 'Your form session has expired. Please try again.';
        $messageType = 'error';
    } elseif (
        $formData['name'] === '' ||
        $formData['phone'] === '' ||
        $formData['email'] === '' ||
        $formData['service'] === '' ||
        $formData['date'] === '' ||
        $formData['time'] === '' ||
        $formData['message'] === ''
    ) {
        $message = 'Please fill in all required fields.';
        $messageType = 'error';
    } elseif (!filter_var($formData['email'], FILTER_VALIDATE_EMAIL)) {
        $message = 'Please enter a valid email address.';
        $messageType = 'error';
    } elseif (!in_array($formData['service'], $services, true)) {
        $message = 'Please select a valid service.';
        $messageType = 'error';
    } elseif (
        !$selectedDate ||
        $selectedDate->format('Y-m-d') !== $formData['date'] ||
        !$selectedTime ||
        $selectedTime->format('H:i') !== $formData['time']
    ) {
        $message = 'Please enter a valid preferred date and time.';
        $messageType = 'error';
    } elseif ($formData['date'] < date('Y-m-d')) {
        $message = 'Please select today or a future date.';
        $messageType = 'error';
    } else {
        try {
            $statement = $pdo->prepare(
                'INSERT INTO appointments (
                    user_id,
                    name,
                    email,
                    phone,
                    service_type,
                    preferred_date,
                    preferred_time,
                    message,
                    status
                ) VALUES (
                    :user_id,
                    :name,
                    :email,
                    :phone,
                    :service,
                    :preferred_date,
                    :preferred_time,
                    :message,
                    :status
                )'
            );

            $statement->execute([
                ':user_id' => $_SESSION['user_id'],
                ':name' => $formData['name'],
                ':email' => $formData['email'],
                ':phone' => $formData['phone'],
                ':service' => $formData['service'],
                ':preferred_date' => $formData['date'],
                ':preferred_time' => $formData['time'],
                ':message' => $formData['message'],
                ':status' => 'pending',
            ]);

            $_SESSION['appointment_success'] =
                'Your appointment request has been submitted successfully.';

            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

            header('Location: schedule.php');
            exit;
        } catch (PDOException $exception) {
            $message = 'Something went wrong. Please try again later.';
            $messageType = 'error';
        }
    }
}

if (isset($_SESSION['appointment_success'])) {
    $message = $_SESSION['appointment_success'];
    $messageType = 'success';

    unset($_SESSION['appointment_success']);
}

$pageTitle = 'Schedule a Visit | CoolAir HVAC';
$basePath = '../';

require_once __DIR__ . '/../includes/header.php';
?>

<style>
    .schedule-wrapper {
        padding: 24px 0 80px;
    }

    .schedule-hero {
        position: relative;
        overflow: hidden;
        padding: 90px 5% 60px;
        text-align: center;
    }

    .schedule-hero h1 {
        position: relative;
        margin-bottom: 1rem;
        color: #ffffff;
        font-size: clamp(2.8rem, 5vw, 4.3rem);
        text-shadow: 0 8px 32px rgba(0, 0, 0, 0.4);
    }

    .schedule-hero h1::after {
        content: '';
        position: absolute;
        bottom: -12px;
        left: 50%;
        width: 120px;
        height: 4px;
        background: linear-gradient(90deg, transparent, #ff6200, #ff8a1f, #ff6200, transparent);
        border-radius: 2px;
        box-shadow: 0 2px 12px rgba(255, 98, 0, 0.6);
        transform: translateX(-50%);
    }

    .schedule-hero p {
        max-width: 850px;
        margin: 25px auto 0;
        color: rgba(255, 255, 255, 0.85);
        font-size: 1.2rem;
        line-height: 1.8;
    }

    .section {
        position: relative;
        padding: 80px 5%;
    }

    .section-title {
        margin-bottom: 50px;
        text-align: center;
    }

    .section-title h2 {
        position: relative;
        display: inline-block;
        color: #ffffff;
        font-size: clamp(2rem, 4vw, 3rem);
    }

    .section-title h2::after {
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

    .schedule-grid {
        display: grid;
        grid-template-columns: 1.05fr 0.95fr;
        gap: 24px;
        align-items: stretch;
        max-width: 1200px;
        margin: 0 auto;
    }

    .schedule-card {
        padding: 32px;
        background: rgba(255, 255, 255, 0.08);
        border: 1px solid rgba(255, 255, 255, 0.18);
        border-radius: 28px;
        box-shadow: 0 18px 50px rgba(0, 0, 0, 0.24);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
    }

    .schedule-card h3 {
        margin-bottom: 14px;
        color: #ffffff;
        font-size: 1.8rem;
    }

    .schedule-card p {
        margin-bottom: 20px;
        color: rgba(255, 255, 255, 0.84);
        line-height: 1.8;
    }

    .schedule-form {
        display: grid;
        gap: 14px;
    }

    .schedule-form input,
    .schedule-form select,
    .schedule-form textarea {
        width: 100%;
        padding: 14px 16px;
        color: #ffffff;
        font-size: 1rem;
        background: rgba(255, 255, 255, 0.08);
        border: 1px solid rgba(255, 255, 255, 0.14);
        border-radius: 14px;
        outline: none;
    }

    .schedule-form input::placeholder,
    .schedule-form textarea::placeholder {
        color: rgba(255, 255, 255, 0.55);
    }

    .schedule-form option {
        color: #111111;
    }

    .schedule-form textarea {
        min-height: 150px;
        resize: vertical;
    }

    .submit-btn {
        width: fit-content;
        padding: 14px 28px;
        color: #ffffff;
        font-weight: 600;
        cursor: pointer;
        background: linear-gradient(135deg, #ff6200, #ff8a1f);
        border: 0;
        border-radius: 50px;
        box-shadow: 0 12px 30px rgba(255, 98, 0, 0.28);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .submit-btn:hover {
        box-shadow: 0 16px 34px rgba(255, 98, 0, 0.36);
        transform: translateY(-2px);
    }

    .info-list {
        display: grid;
        gap: 14px;
        margin-top: 18px;
    }

    .info-item {
        padding: 16px 18px;
        color: rgba(255, 255, 255, 0.86);
        line-height: 1.7;
        background: rgba(255, 255, 255, 0.06);
        border: 1px solid rgba(255, 255, 255, 0.10);
        border-radius: 16px;
    }

    .info-item strong {
        color: #ffffff;
    }

    .info-item a {
        color: #ffffff;
        text-decoration: none;
    }

    .info-item a:hover {
        color: #ff8a1f;
    }

    .form-alert {
        max-width: 1200px;
        margin: 0 auto 30px;
        padding: 14px 18px;
        font-size: 0.95rem;
        text-align: center;
        border-radius: 12px;
    }

    .form-alert.success {
        color: #bbf7d0;
        background: rgba(34, 197, 94, 0.15);
        border: 1px solid rgba(34, 197, 94, 0.35);
    }

    .form-alert.error {
        color: #fecaca;
        background: rgba(239, 68, 68, 0.15);
        border: 1px solid rgba(239, 68, 68, 0.35);
    }

    @media (max-width: 900px) {
        .schedule-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 768px) {
        .schedule-hero {
            padding: 70px 4% 40px;
        }

        .schedule-hero p {
            font-size: 1.05rem;
        }

        .section {
            padding: 70px 4%;
        }
    }
</style>

<div class="schedule-wrapper">
    <?php if ($message !== ''): ?>
        <div class="form-alert <?= htmlspecialchars($messageType, ENT_QUOTES, 'UTF-8') ?>">
            <?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?>
        </div>
    <?php endif; ?>

    <section class="schedule-hero">
        <h1>Schedule a Visit</h1>

        <p>
            Book your site visit, project discussion, or service consultation with CoolAir HVAC.
            Use this page for both appointment and schedule requests.
        </p>
    </section>

    <section class="section">
        <div class="section-title">
            <h2>Appointment Form</h2>
        </div>

        <div class="schedule-grid">
            <div class="schedule-card">
                <h3>Request a Schedule</h3>

                <p>
                    Fill in your details and our team will contact you for the next available slot.
                    This page is connected for both <strong>Book Appointment</strong> and <strong>Schedule Visit</strong>.
                </p>

                <form class="schedule-form" action="schedule.php" method="post">
                    <input
                        type="hidden"
                        name="csrf_token"
                        value="<?= htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8') ?>"
                    >

                    <input
                        type="text"
                        name="name"
                        placeholder="Your Name"
                        value="<?= htmlspecialchars($formData['name'], ENT_QUOTES, 'UTF-8') ?>"
                        required
                    >

                    <input
                        type="tel"
                        name="phone"
                        placeholder="Your Phone Number"
                        value="<?= htmlspecialchars($formData['phone'], ENT_QUOTES, 'UTF-8') ?>"
                        required
                    >

                    <input
                        type="email"
                        name="email"
                        placeholder="Your Email Address"
                        value="<?= htmlspecialchars($formData['email'], ENT_QUOTES, 'UTF-8') ?>"
                        required
                    >

                    <select name="service" required>
                        <option value="">Select Service</option>

                        <?php foreach ($services as $service): ?>
                            <option
                                value="<?= htmlspecialchars($service, ENT_QUOTES, 'UTF-8') ?>"
                                <?= $formData['service'] === $service ? 'selected' : '' ?>
                            >
                                <?= htmlspecialchars($service, ENT_QUOTES, 'UTF-8') ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <input
                        type="date"
                        name="date"
                        min="<?= date('Y-m-d') ?>"
                        value="<?= htmlspecialchars($formData['date'], ENT_QUOTES, 'UTF-8') ?>"
                        required
                    >

                    <input
                        type="time"
                        name="time"
                        value="<?= htmlspecialchars($formData['time'], ENT_QUOTES, 'UTF-8') ?>"
                        required
                    >

                    <textarea
                        name="message"
                        placeholder="Write your project details or visit requirements..."
                        required
                    ><?= htmlspecialchars($formData['message'], ENT_QUOTES, 'UTF-8') ?></textarea>

                    <button type="submit" class="submit-btn">
                        Submit Request
                    </button>
                </form>
            </div>

            <div class="schedule-card">
                <h3>Company Details</h3>

                <p>
                    Contact us directly if you need urgent coordination or a quick discussion before the visit.
                </p>

                <div class="info-list">
                    <div class="info-item">
                        <strong>Office Address:</strong><br>
                        F-383, Third Floor, Chandani Chowk,<br>
                        West Delhi, New Delhi - 110059
                    </div>

                    <div class="info-item">
                        <strong>Phone:</strong><br>
                        <a href="tel:9811189484">98xxxxxxxx</a><br>
                        <a href="tel:9891289484">98xxxxxxxx</a><br>
                        <a href="tel:9311289484">93xxxxxxxx</a>
                    </div>

                    <div class="info-item">
                        <strong>Email:</strong><br>
                        <a href="mailto:coolairhvac@gmail.com">coolairhvac@gmail.com</a><br>
                        <a href="mailto:coolairhvacatik@gmail.com">coolairhvacatik@gmail.com</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>