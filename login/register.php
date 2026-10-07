<?php
session_start();

if (isset($_SESSION['user_id'])) {
    header('Location: ../all_pages/index.php');
    exit;
}

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$error = trim($_GET['error'] ?? '');
$success = trim($_GET['success'] ?? '');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | CoolAir HVAC</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
    >

    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="login-wrapper">
        <div class="login-card">
            <div class="brand-section">
                <div class="brand-icon">
                    <i class="bi bi-snow2"></i>
                </div>

                <h2>CoolAir HVAC</h2>
                <p>Register New User</p>
            </div>

            <div class="form-section">
                <h3>Create Account</h3>
                <p class="login-subtitle">Create your client account to request HVAC services</p>

                <?php if ($error !== ''): ?>
                    <div class="alert alert-danger">
                        <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
                    </div>
                <?php endif; ?>

                <?php if ($success !== ''): ?>
                    <div class="alert alert-success">
                        <?= htmlspecialchars($success, ENT_QUOTES, 'UTF-8') ?>
                    </div>
                <?php endif; ?>

                <form action="register-process.php" method="post">
                    <input
                        type="hidden"
                        name="csrf_token"
                        value="<?= htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8') ?>"
                    >

                    <div class="mb-3">
                        <label for="full_name" class="form-label">Full Name</label>

                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="bi bi-person"></i>
                            </span>

                            <input
                                type="text"
                                id="full_name"
                                name="full_name"
                                class="form-control"
                                placeholder="Enter full name"
                                autocomplete="name"
                                maxlength="100"
                                required
                            >
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email Address</label>

                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="bi bi-envelope"></i>
                            </span>

                            <input
                                type="email"
                                id="email"
                                name="email"
                                class="form-control"
                                placeholder="Enter email"
                                autocomplete="email"
                                maxlength="150"
                                required
                            >
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>

                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="bi bi-lock"></i>
                            </span>

                            <input
                                type="password"
                                id="password"
                                name="password"
                                class="form-control"
                                placeholder="Create a password"
                                autocomplete="new-password"
                                minlength="8"
                                required
                            >
                        </div>
                    </div>

                    <button type="submit" class="btn login-btn w-100">
                        <i class="bi bi-person-plus"></i>
                        Create Account
                    </button>
                </form>

                <div class="client-note mt-3">
                    <i class="bi bi-info-circle"></i>
                    Already have an account?
                    <a href="login.php" class="ms-1">Login here</a>
                </div>

                <div class="client-note mt-3">
                    <i class="bi bi-arrow-left-circle"></i>
                    Continue without registration
                    <a href="../all_pages/index.php" class="ms-1">Back</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>