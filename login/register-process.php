<?php
session_start();

require_once __DIR__ . '/config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: register.php');
    exit;
}

$fullName = trim($_POST['full_name'] ?? '');
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$csrfToken = $_POST['csrf_token'] ?? '';

if (
    empty($_SESSION['csrf_token']) ||
    !hash_equals($_SESSION['csrf_token'], $csrfToken)
) {
    header('Location: register.php?error=' . urlencode('Your form session has expired. Please try again.'));
    exit;
}

if ($fullName === '' || $email === '' || $password === '') {
    header('Location: register.php?error=' . urlencode('All fields are required.'));
    exit;
}

if (mb_strlen($fullName) > 100) {
    header('Location: register.php?error=' . urlencode('Your name must be 100 characters or fewer.'));
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL) || mb_strlen($email) > 150) {
    header('Location: register.php?error=' . urlencode('Please enter a valid email address.'));
    exit;
}

if (strlen($password) < 8) {
    header('Location: register.php?error=' . urlencode('Your password must be at least 8 characters long.'));
    exit;
}

try {
    $checkStatement = $pdo->prepare(
        'SELECT id
         FROM users
         WHERE email = :email
         LIMIT 1'
    );

    $checkStatement->execute([
        ':email' => $email,
    ]);

    if ($checkStatement->fetch()) {
        header('Location: register.php?error=' . urlencode('Email already registered.'));
        exit;
    }

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $statement = $pdo->prepare(
        'INSERT INTO users (full_name, email, password, role)
         VALUES (:full_name, :email, :password, :role)'
    );

    $statement->execute([
        ':full_name' => $fullName,
        ':email' => $email,
        ':password' => $hashedPassword,
        ':role' => 'client',
    ]);

    unset($_SESSION['csrf_token']);

    header('Location: login.php?success=' . urlencode('Account created successfully. Please log in.'));
    exit;
} catch (PDOException $exception) {
    header('Location: register.php?error=' . urlencode('Unable to create your account. Please try again.'));
    exit;
}