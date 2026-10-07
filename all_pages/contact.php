<?php


session_start();

require_once __DIR__ . '/../database/db_connect.php';

date_default_timezone_set('Asia/Kolkata');

$successMessage = '';
$errorMessage = '';

$allowedComplaintTypes = [
    'Service Delay',
    'Installation Issue',
    'Cooling Issue',
    'Piping Issue',
    'Follow-up Required',
    'Other',
];

$formData = [
    'name' => '',
    'phone' => '',
    'email' => '',
    'project' => '',
    'complaint_type' => '',
    'message' => '',
];

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_complaint'])) {
    $formData = [
        'name' => trim($_POST['name'] ?? ''),
        'phone' => trim($_POST['phone'] ?? ''),
        'email' => trim($_POST['email'] ?? ''),
        'project' => trim($_POST['project'] ?? ''),
        'complaint_type' => trim($_POST['complaint_type'] ?? ''),
        'message' => trim($_POST['message'] ?? ''),
    ];

    $submittedToken = $_POST['csrf_token'] ?? '';

    if (!hash_equals($_SESSION['csrf_token'], $submittedToken)) {
        $errorMessage = 'Your form session has expired. Please refresh the page and try again.';
    } elseif (
        $formData['name'] === '' ||
        $formData['phone'] === '' ||
        $formData['email'] === '' ||
        $formData['complaint_type'] === '' ||
        $formData['message'] === ''
    ) {
        $errorMessage = 'Please fill in all required fields.';
    } elseif (!filter_var($formData['email'], FILTER_VALIDATE_EMAIL)) {
        $errorMessage = 'Please enter a valid email address.';
    } elseif (!in_array($formData['complaint_type'], $allowedComplaintTypes, true)) {
        $errorMessage = 'Please select a valid complaint type.';
    } else {
        try {
            $statement = $pdo->prepare(
                'INSERT INTO complaints (
                    name,
                    phone,
                    email,
                    project_name,
                    complaint_type,
                    message,
                    status
                ) VALUES (
                    :name,
                    :phone,
                    :email,
                    :project_name,
                    :complaint_type,
                    :message,
                    :status
                )'
            );

            $statement->execute([
                ':name' => $formData['name'],
                ':phone' => $formData['phone'],
                ':email' => $formData['email'],
                ':project_name' => $formData['project'] !== '' ? $formData['project'] : null,
                ':complaint_type' => $formData['complaint_type'],
                ':message' => $formData['message'],
                ':status' => 'Pending',
            ]);

            $successMessage =
                'Your complaint has been submitted successfully. Our team will contact you shortly.';

            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

            $formData = [
                'name' => '',
                'phone' => '',
                'email' => '',
                'project' => '',
                'complaint_type' => '',
                'message' => '',
            ];
        } catch (PDOException $exception) {
            $errorMessage =
                'Unable to submit your complaint right now. Please try again later.';
        }
    }
}

$pageTitle = 'Contact Us | CoolAir HVAC';
$basePath = '../';

require_once __DIR__ . '/../includes/header.php';
?>

<style>
    .contact-wrapper {
        padding: 24px 0 80px;
    }

    .contact-hero {
        position: relative;
        overflow: hidden;
        padding: 90px 5% 50px;
        text-align: center;
    }

    .contact-hero h1 {
        position: relative;
        margin-bottom: 1rem;
        color: #ffffff;
        font-size: clamp(2.8rem, 5vw, 4.3rem);
        text-shadow: 0 8px 32px rgba(0, 0, 0, 0.4);
    }

    .contact-hero h1::after {
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

    .contact-hero p {
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

    .contact-grid {
        display: grid;
        grid-template-columns: 1fr 1.15fr 1fr;
        gap: 24px;
        max-width: 1200px;
        margin: 0 auto;
    }

    .contact-card {
        position: relative;
        overflow: hidden;
        padding: 32px;
        background: rgba(255, 255, 255, 0.08);
        border: 1px solid rgba(255, 255, 255, 0.18);
        border-radius: 24px;
        box-shadow: 0 16px 40px rgba(0, 0, 0, 0.2);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        transition: all 0.4s ease;
    }

    .contact-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.1), transparent);
        transition: left 0.6s ease;
    }

    .contact-card:hover {
        background: rgba(255, 255, 255, 0.11);
        border-color: rgba(255, 98, 0, 0.3);
        box-shadow: 0 24px 60px rgba(0, 0, 0, 0.3), 0 0 0 1px rgba(255, 98, 0, 0.15);
        transform: translateY(-6px);
    }

    .contact-card:hover::before {
        left: 100%;
    }

    .contact-card h3 {
        margin-bottom: 12px;
        color: #ffffff;
        font-size: 1.45rem;
        font-weight: 700;
    }

    .contact-card p,
    .contact-card a {
        color: rgba(255, 255, 255, 0.85);
        line-height: 1.8;
        text-decoration: none;
        overflow-wrap: anywhere;
        word-break: break-word;
    }

    .contact-card a:hover {
        color: #ff8a1f;
    }

    .support-note {
        max-width: 1200px;
        margin: 24px auto 0;
        padding: 0 6px;
        color: rgba(255, 255, 255, 0.85);
        font-size: 1.1rem;
        line-height: 1.8;
        text-align: left;
    }

    .support-note strong {
        color: #ffffff;
        font-weight: 700;
    }

    .form-wrap {
        display: grid;
        grid-template-columns: 1.1fr 0.9fr;
        gap: 24px;
        align-items: stretch;
        max-width: 1200px;
        margin: 0 auto;
    }

    .contact-form-card,
    .map-card {
        padding: 32px;
        background: rgba(255, 255, 255, 0.06);
        border: 1px solid rgba(255, 255, 255, 0.12);
        border-radius: 24px;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.22);
        backdrop-filter: blur(16px);
    }

    .contact-form-card h3,
    .map-card h3 {
        margin-bottom: 18px;
        color: #ffffff;
        font-size: 1.8rem;
    }

    .contact-form-card p,
    .map-card p {
        margin-bottom: 18px;
        color: rgba(255, 255, 255, 0.82);
        line-height: 1.8;
    }

    .contact-form {
        display: grid;
        gap: 14px;
    }

    .contact-form input,
    .contact-form textarea,
    .contact-form select {
        width: 100%;
        padding: 14px 16px;
        color: #ffffff;
        font-size: 1rem;
        background: rgba(255, 255, 255, 0.08);
        border: 1px solid rgba(255, 255, 255, 0.14);
        border-radius: 14px;
        outline: none;
    }

    .contact-form input::placeholder,
    .contact-form textarea::placeholder {
        color: rgba(255, 255, 255, 0.55);
    }

    .contact-form textarea {
        min-height: 150px;
        resize: vertical;
    }

    .contact-form option {
        color: #111111;
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

    .map-box {
        width: 100%;
        min-height: 420px;
        overflow: hidden;
        border: 1px solid rgba(255, 255, 255, 0.12);
        border-radius: 18px;
    }

    .map-box iframe {
        width: 100%;
        min-height: 420px;
        border: 0;
    }

    .cta-box {
        max-width: 1200px;
        margin: 0 auto;
        padding: 36px;
        text-align: center;
        background: linear-gradient(135deg, rgba(255, 98, 0, 0.18), rgba(255, 138, 31, 0.08));
        border: 1px solid rgba(255, 255, 255, 0.15);
        border-radius: 26px;
        box-shadow: 0 22px 50px rgba(0, 0, 0, 0.22);
        backdrop-filter: blur(16px);
    }

    .cta-box h2 {
        margin-bottom: 15px;
        color: #ffffff;
        font-size: clamp(2rem, 4vw, 2.8rem);
    }

    .cta-box p {
        max-width: 900px;
        margin: 0 auto 24px;
        color: rgba(255, 255, 255, 0.85);
        line-height: 1.8;
    }

    .cta-actions {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 14px;
    }

    .cta-btn {
        display: inline-block;
        padding: 14px 28px;
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

    .cta-btn.secondary {
        background: transparent;
        border: 1px solid rgba(255, 255, 255, 0.2);
        box-shadow: none;
    }

    .form-alert {
        margin-bottom: 18px;
        padding: 14px 16px;
        font-size: 0.98rem;
        line-height: 1.6;
        border-radius: 14px;
    }

    .form-alert.success {
        color: #d9ffe5;
        background: rgba(38, 170, 85, 0.18);
        border: 1px solid rgba(80, 220, 125, 0.35);
    }

    .form-alert.error {
        color: #ffe0e4;
        background: rgba(220, 53, 69, 0.18);
        border: 1px solid rgba(255, 110, 125, 0.35);
    }

    @media (max-width: 900px) {
        .contact-grid,
        .form-wrap {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 768px) {
        .contact-hero {
            padding: 70px 4% 40px;
        }

        .contact-hero p {
            font-size: 1.05rem;
        }

        .section {
            padding: 70px 4%;
        }

        .map-box,
        .map-box iframe {
            min-height: 320px;
        }

        .support-note {
            font-size: 1rem;
        }
    }
</style>

<div class="contact-wrapper">
    <section class="contact-hero">
        <h1>Contact Us</h1>

        <p>
            Reach out to CoolAir HVAC for HVAC, electrical, and structural solutions.
            We are ready to discuss your project, site requirements, and service needs.
        </p>
    </section>

    <section class="section">
        <div class="section-title">
            <h2>Get in Touch</h2>
        </div>

        <div class="contact-grid">
            <div class="contact-card">
                <h3>Office Address</h3>

                <p>
                    F-383, Third Floor,<br>
                    Chandani Chowk, West Delhi,<br>
                    New Delhi - 110059
                </p>
            </div>

            <div class="contact-card">
                <h3>Phone Numbers</h3>

                <p>
                    <a href="tel:9811189484">98xxxxxxxx</a><br>
                    <a href="tel:9891289484">98xxxxxxxx</a><br>
                    <a href="tel:9311289484">93xxxxxxxx</a>
                </p>
            </div>

            <div class="contact-card">
                <h3>Email</h3>

                <p>
                    <a href="mailto:coolairhvac@gmail.com">coolairhvac@gmail.com</a><br>
                    <a href="mailto:coolairhvacatik@gmail.com">coolairhvacatik@gmail.com</a>
                </p>
            </div>
        </div>

        <p class="support-note">
            <strong>For quick project discussion and service support,</strong>
            contact us directly and our team will assist you with site visits, quotations, and planning.
        </p>
    </section>

    <section class="section">
        <div class="section-title">
            <h2>Raise a Complaint</h2>
        </div>

        <div class="form-wrap">
            <div class="contact-form-card">
                <h3 id="complaint-box">Complaint Box</h3>

                <p>
                    Use the form below to report any service issue, project concern, or follow-up requirement.
                    Our team will review your complaint and contact you shortly.
                </p>

                <?php if ($successMessage !== ''): ?>
                    <div class="form-alert success">
                        <?= htmlspecialchars($successMessage, ENT_QUOTES, 'UTF-8') ?>
                    </div>
                <?php endif; ?>

                <?php if ($errorMessage !== ''): ?>
                    <div class="form-alert error">
                        <?= htmlspecialchars($errorMessage, ENT_QUOTES, 'UTF-8') ?>
                    </div>
                <?php endif; ?>

                <form
                    class="contact-form"
                    action="contact.php#complaint-box"
                    method="post"
                >
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

                    <input
                        type="text"
                        name="project"
                        placeholder="Project / Site Name"
                        value="<?= htmlspecialchars($formData['project'], ENT_QUOTES, 'UTF-8') ?>"
                    >

                    <select name="complaint_type" required>
                        <option value="">Select Complaint Type</option>

                        <?php foreach ($allowedComplaintTypes as $type): ?>
                            <option
                                value="<?= htmlspecialchars($type, ENT_QUOTES, 'UTF-8') ?>"
                                <?= $formData['complaint_type'] === $type ? 'selected' : '' ?>
                            >
                                <?= htmlspecialchars($type, ENT_QUOTES, 'UTF-8') ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <textarea
                        name="message"
                        placeholder="Describe your complaint in detail..."
                        required
                    ><?= htmlspecialchars($formData['message'], ENT_QUOTES, 'UTF-8') ?></textarea>

                    <button type="submit" name="submit_complaint" class="submit-btn">
                        Submit Complaint
                    </button>
                </form>
            </div>

            <div class="map-card">
                <h3>Our Location</h3>

                <p>
                    Visit our office or use the map below to find us easily.
                </p>

                <div class="map-box">
                    <iframe
                        src="https://www.google.com/maps?q=WZ-55B,%20Third%20Floor,%20Chand%20Nagar,%20Choukhandi,%20West%20Delhi,%20New%20Delhi%20-%20110018&output=embed"
                        title="CoolAir HVAC location"
                        loading="lazy"
                        allowfullscreen
                    ></iframe>
                </div>
            </div>
        </div>
    </section>

    <section class="section">
        <div class="cta-box">
            <h2>Need HVAC or Structural Support?</h2>

            <p>
                From chillers and AHUs to plant rooms, electrical works, and structural fabrication,
                our team is ready to help with your next project.
            </p>

            <div class="cta-actions">
                <a href="services.php" class="cta-btn">View Services</a>
                <a href="schedule.php" class="cta-btn secondary">Book An Appointment</a>
            </div>
        </div>
    </section>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>