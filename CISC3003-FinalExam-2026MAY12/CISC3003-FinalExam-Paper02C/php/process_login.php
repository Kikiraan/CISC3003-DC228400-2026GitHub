<?php
declare(strict_types=1);

require_once __DIR__ . '/bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('../login.php');
}

$email = strtolower(trim((string) ($_POST['email'] ?? '')));
$password = (string) ($_POST['password'] ?? '');

$old = ['email' => $email];
$errors = [];

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors['email'] = 'Please enter a valid email address.';
}

if ($password === '') {
    $errors['password'] = 'Please enter your password.';
}

if ($errors) {
    store_form_state('login_old', $old);
    store_form_state('login_errors', $errors);
    flash('error', 'Login Failed', 'Please correct the highlighted fields.');
    redirect('../login.php');
}

$user = find_user_by_email($email);

if (!$user || !password_verify($password, $user['password_hash'])) {
    store_form_state('login_old', $old);
    flash('error', 'Login Failed', 'The email or password is incorrect.');
    redirect('../login.php');
}

if (!(bool) $user['is_active']) {
    store_form_state('login_old', $old);
    flash('error', 'Account Not Activated', 'Please activate your account by email before logging in.');
    redirect('../login.php');
}

session_regenerate_id(true);
$_SESSION['user_id'] = (int) $user['id'];

$connection = get_db_connection();
$statement = $connection->prepare('UPDATE users SET last_login_at = NOW() WHERE id = ?');
if ($statement) {
    $userId = (int) $user['id'];
    $statement->bind_param('i', $userId);
    $statement->execute();
    $statement->close();
}

flash('success', 'Login Successful', 'Welcome to your dashboard.');
redirect('../dashboard.php');
