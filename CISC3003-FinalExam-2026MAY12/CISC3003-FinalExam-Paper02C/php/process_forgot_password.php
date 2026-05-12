<?php
declare(strict_types=1);

require_once __DIR__ . '/bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('../forgot-password.php');
}

$email = strtolower(trim((string) ($_POST['email'] ?? '')));
$errors = [];

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors['email'] = 'Please enter a valid email address.';
}

if ($errors) {
    store_form_state('forgot_old', ['email' => $email]);
    store_form_state('forgot_errors', $errors);
    flash('error', 'Reset Request Failed', 'Please enter a valid email address.');
    redirect('../forgot-password.php');
}

$user = find_user_by_email($email);

if ($user) {
    $token = bin2hex(random_bytes(16));
    $tokenHash = hash('sha256', $token);
    $expiresAt = date('Y-m-d H:i:s', time() + 3600);

    $connection = get_db_connection();
    $statement = $connection->prepare(
        'UPDATE users SET password_reset_hash = ?, password_reset_expires_at = ? WHERE id = ?'
    );

    if ($statement) {
        $userId = (int) $user['id'];
        $statement->bind_param('ssi', $tokenHash, $expiresAt, $userId);
        $statement->execute();
        $statement->close();

        try {
            send_password_reset_email($user, $token);
        } catch (Throwable $exception) {
            log_mail_debug('Password reset email failed', $exception->getMessage());
            flash('error', 'Reset Email Not Sent', 'The reset token was created, but the email could not be sent. Check php/mail_debug.log and mail_config.php.');
            redirect('../forgot-password.php');
        }
    }
}

flash('success', 'Reset Email Requested', 'If the email exists in our system, a password reset link has been sent.');
redirect('../login.php');
