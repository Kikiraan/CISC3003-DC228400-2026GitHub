<?php
declare(strict_types=1);

require_once __DIR__ . '/bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('../resend-activation.php');
}

$email = strtolower(trim((string) ($_POST['email'] ?? '')));
$errors = [];

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors['email'] = 'Please enter a valid email address.';
}

if ($errors) {
    store_form_state('resend_old', ['email' => $email]);
    store_form_state('resend_errors', $errors);
    flash('error', 'Request Failed', 'Please enter a valid email address.');
    redirect('../resend-activation.php');
}

$user = find_user_by_email($email);

if ($user && !(bool) $user['is_active']) {
    $token = bin2hex(random_bytes(16));
    $tokenHash = hash('sha256', $token);

    $connection = get_db_connection();
    $statement = $connection->prepare('UPDATE users SET account_activation_hash = ? WHERE id = ?');
    if ($statement) {
        $userId = (int) $user['id'];
        $statement->bind_param('si', $tokenHash, $userId);
        $statement->execute();
        $statement->close();

        try {
            send_activation_email($user, $token);
        } catch (Throwable $exception) {
            log_mail_debug('Resend activation email failed', $exception->getMessage());
            flash('error', 'Activation Email Not Sent', 'The activation token was updated, but the email could not be sent. Check php/mail_debug.log and mail_config.php.');
            redirect('../resend-activation.php');
        }
    }
}

flash('success', 'Activation Email Requested', 'If the account exists and is not activated, a new activation email has been sent.');
redirect('../login.php');
