<?php
declare(strict_types=1);

require_once __DIR__ . '/bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('../register.php');
}

$name = trim((string) ($_POST['name'] ?? ''));
$studentId = strtoupper(trim((string) ($_POST['student_id'] ?? '')));
$email = strtolower(trim((string) ($_POST['email'] ?? '')));
$password = (string) ($_POST['password'] ?? '');
$confirmPassword = (string) ($_POST['confirm_password'] ?? '');

$old = [
    'name' => $name,
    'student_id' => $studentId,
    'email' => $email,
];

$errors = [];

if ($name === '' || mb_strlen($name) < 2) {
    $errors['name'] = 'Please enter your full name.';
}

if (!preg_match('/^[A-Z]{2}\\d{6}$/', $studentId)) {
    $errors['student_id'] = 'Student ID must match a format like DC228400.';
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors['email'] = 'Please enter a valid email address.';
} elseif (email_exists($email)) {
    $errors['email'] = 'This email is already registered.';
}

if (!preg_match('/^(?=.*[A-Z])(?=.*\\d).{8,}$/', $password)) {
    $errors['password'] = 'Password must be at least 8 characters and include one uppercase letter and one number.';
}

if ($confirmPassword !== $password) {
    $errors['confirm_password'] = 'Passwords do not match.';
}

if ($errors) {
    store_form_state('register_old', $old);
    store_form_state('register_errors', $errors);
    flash('error', 'Registration Failed', 'Please correct the highlighted fields.');
    redirect('../register.php');
}

$passwordHash = password_hash($password, PASSWORD_DEFAULT);
$token = bin2hex(random_bytes(16));
$tokenHash = hash('sha256', $token);

try {
    $connection = get_db_connection();
    $statement = $connection->prepare(
        'INSERT INTO users (name, student_id, email, password_hash, account_activation_hash)
         VALUES (?, ?, ?, ?, ?)'
    );

    if (!$statement) {
        throw new RuntimeException('Unable to prepare the registration statement.');
    }

    $statement->bind_param('sssss', $name, $studentId, $email, $passwordHash, $tokenHash);
    $statement->execute();
    $userId = (int) $connection->insert_id;
    $statement->close();

    $user = find_user_by_id($userId);
    if ($user) {
        try {
            send_activation_email($user, $token);
            flash('success', 'Registration Complete', 'Your account has been created. Please check your email to activate it before logging in.');
        } catch (Throwable $exception) {
            log_mail_debug('Activation email failed', $exception->getMessage());
            flash('info', 'Account Created', 'Your account was created, but the activation email could not be sent yet. Update SMTP settings and use "Resend Activation".');
        }
    } else {
        flash('success', 'Registration Complete', 'Your account has been created. Please check your email to activate it.');
    }
} catch (Throwable $exception) {
    store_form_state('register_old', $old);
    flash('error', 'Database Error', $exception->getMessage());
    redirect('../register.php');
}

redirect('../login.php');
