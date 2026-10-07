<?php
session_start();

require_once __DIR__ . '/config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: login.php');
    exit;
}

$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$csrfToken = $_POST['csrf_token'] ?? '';

if (
    empty($_SESSION['csrf_token']) ||
    !hash_equals($_SESSION['csrf_token'], $csrfToken)
) {
    header('Location: login.php?error=' . urlencode('Your form session has expired. Please try again.'));
    exit;
}

if ($email === '' || $password === '') {
    header('Location: login.php?error=' . urlencode('Please fill in all fields.'));
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header('Location: login.php?error=' . urlencode('Please enter a valid email address.'));
    exit;
}

try {
    $statement = $pdo->prepare(
        'SELECT id, full_name, email, password, role
         FROM users
         WHERE email = :email
         LIMIT 1'
    );

    $statement->execute([
        ':email' => $email,
    ]);

    $user = $statement->fetch();

    if (!$user || !password_verify($password, $user['password'])) {
        header('Location: login.php?error=' . urlencode('Invalid email or password.'));
        exit;
    }

    session_regenerate_id(true);

    $_SESSION['user_id'] = (int) $user['id'];
    $_SESSION['full_name'] = $user['full_name'];
    $_SESSION['email'] = $user['email'];
    $_SESSION['role'] = $user['role'];

    unset($_SESSION['csrf_token']);

    header('Location: ../all_pages/index.php');
    exit;
} catch (PDOException $exception) {
    header('Location: login.php?error=' . urlencode('Unable to log in right now. Please try again later.'));
    exit;
}