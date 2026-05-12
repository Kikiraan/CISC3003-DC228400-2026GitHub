<?php
declare(strict_types=1);

require_once __DIR__ . '/bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('../forgot-password.php');
}

$token = trim((string) ($_POST['token'] ?? ''));
$password = (string) ($_POST['password'] ?? '');
$confirmPassword = (string) ($_POST['confirm_password'] ?? '');
$errors = [];

if (!preg_match('/^(?=.*[A-Z])(?=.*\\d).{8,}$/', $password)) {
    $errors['password'] = 'Password must be at least 8 characters and include one uppercase letter and one number.';
}

if ($confirmPassword !== $password) {
    $errors['confirm_password'] = 'Passwords do not match.';
}

if ($errors) {
    store_form_state('reset_errors', $errors);
    redirect('../reset-password.php?token=' . urlencode($token));
}

$tokenHash = hash('sha256', $token);
$user = find_user_by_token_column('password_reset_hash', $tokenHash);

if (!$user || !$user['password_reset_expires_at'] || strtotime((string) $user['password_reset_expires_at']) < time()) {
    flash('error', 'Reset Link Invalid', 'The password reset link is invalid or has expired.');
    redirect('../forgot-password.php');
}

$passwordHash = password_hash($password, PASSWORD_DEFAULT);
$connection = get_db_connection();
$statement = $connection->prepare(
    'UPDATE users
     SET password_hash = ?, password_reset_hash = NULL, password_reset_expires_at = NULL
     WHERE id = ?'
);

if (!$statement) {
    flash('error', 'Password Not Updated', 'Unable to prepare the reset statement.');
    redirect('../reset-password.php?token=' . urlencode($token));
}

$userId = (int) $user['id'];
$statement->bind_param('si', $passwordHash, $userId);
$statement->execute();
$statement->close();

flash('success', 'Password Updated', 'Your password has been reset successfully. Please log in.');
redirect('../login.php');
