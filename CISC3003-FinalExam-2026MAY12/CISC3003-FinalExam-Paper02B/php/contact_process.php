<?php
declare(strict_types=1);

require_once __DIR__ . '/bootstrap.php';
require_once __DIR__ . '/mailer.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('../index.php');
}

$fullName = trim((string) ($_POST['full_name'] ?? ''));
$email = trim((string) ($_POST['email'] ?? ''));
$phone = trim((string) ($_POST['phone'] ?? ''));
$topic = trim((string) ($_POST['topic'] ?? ''));
$message = trim((string) ($_POST['message'] ?? ''));
$debugMode = isset($_POST['debug_mode']);

$oldInput = [
    'full_name' => $fullName,
    'email' => $email,
    'phone' => $phone,
    'topic' => $topic,
    'message' => $message,
    'debug_mode' => $debugMode ? '1' : '',
];

$errors = [];

if ($fullName === '' || mb_strlen($fullName) < 2) {
    $errors['full_name'] = 'Please enter your full name.';
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors['email'] = 'Please enter a valid email address.';
}

if ($phone !== '' && !preg_match('/^[0-9+()\\-\\s]{6,20}$/', $phone)) {
    $errors['phone'] = 'Please enter a valid phone number.';
}

if (!in_array($topic, scenario_b_topics(), true)) {
    $errors['topic'] = 'Please choose a valid topic.';
}

if ($message === '' || mb_strlen($message) < 15) {
    $errors['message'] = 'Please enter at least 15 characters.';
}

if ($errors) {
    $_SESSION['old_input'] = $oldInput;
    $_SESSION['form_errors'] = $errors;
    flash('error', 'Validation Failed', 'The form data did not pass validation.');
    redirect('../index.php');
}

$config = require __DIR__ . '/mail_config.php';
$debugLog = [];
$mailStatus = 'failed';

try {
    $mail = build_mailer($config, $debugMode, $debugLog);
    $mail->setFrom($config['from_email'], $config['from_name']);
    $mail->addAddress($config['to_email'], $config['to_name']);
    $mail->addReplyTo($email, $fullName);
    $mail->Subject = 'Scenario B Contact Form: ' . $topic;
    $mail->isHTML(true);
    $mail->Body = sprintf(
        '<h2>Scenario B Contact Form Submission</h2>
        <p><strong>Name:</strong> %s</p>
        <p><strong>Email:</strong> %s</p>
        <p><strong>Phone:</strong> %s</p>
        <p><strong>Topic:</strong> %s</p>
        <p><strong>Message:</strong><br>%s</p>',
        h($fullName),
        h($email),
        h($phone === '' ? 'Not provided' : $phone),
        h($topic),
        nl2br(h($message))
    );
    $mail->AltBody = "Name: {$fullName}\nEmail: {$email}\nPhone: {$phone}\nTopic: {$topic}\n\nMessage:\n{$message}";
    $mail->send();
    $mailStatus = 'sent';
    flash('success', 'Message Sent', 'PHPMailer successfully sent the email and the request has been logged.');
} catch (Throwable $exception) {
    $debugLog[] = 'Mailer exception: ' . $exception->getMessage();
    flash('error', 'Email Not Sent', 'The request was saved, but email sending failed. Review the SMTP debug output.');
}

try {
    $connection = get_db_connection();
    $statement = $connection->prepare(
        'INSERT INTO contact_messages (full_name, email, phone, topic, message, debug_requested, mail_status)
         VALUES (?, ?, ?, ?, ?, ?, ?)'
    );

    if (!$statement) {
        throw new RuntimeException('Unable to prepare the insert statement.');
    }

    $debugRequested = $debugMode ? 1 : 0;
    $statement->bind_param('sssssis', $fullName, $email, $phone, $topic, $message, $debugRequested, $mailStatus);
    $statement->execute();
    $statement->close();
} catch (Throwable $exception) {
    $debugLog[] = 'Database exception: ' . $exception->getMessage();
    flash('error', 'Database Error', 'The email step finished, but the database log insert failed.');
}

if ($debugLog) {
    $_SESSION['mail_debug'] = $debugLog;
}

redirect('../index.php');
