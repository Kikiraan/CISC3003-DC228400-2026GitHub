<?php
declare(strict_types=1);

require_once __DIR__ . '/bootstrap.php';

$user = require_login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('../dashboard.php');
}

$name = trim((string) ($_POST['name'] ?? ''));
$errors = [];

if ($name === '' || mb_strlen($name) < 2) {
    $errors['name'] = 'Please enter a valid full name.';
}

if ($errors) {
    store_form_state('profile_errors', $errors);
    flash('error', 'Profile Not Updated', 'Please correct the form and try again.');
    redirect('../dashboard.php');
}

$connection = get_db_connection();
$statement = $connection->prepare('UPDATE users SET name = ? WHERE id = ?');

if (!$statement) {
    flash('error', 'Update Failed', 'Unable to prepare the profile update statement.');
    redirect('../dashboard.php');
}

$userId = (int) $user['id'];
$statement->bind_param('si', $name, $userId);
$statement->execute();
$statement->close();

flash('success', 'Profile Updated', 'Your full name has been updated.');
redirect('../dashboard.php');
