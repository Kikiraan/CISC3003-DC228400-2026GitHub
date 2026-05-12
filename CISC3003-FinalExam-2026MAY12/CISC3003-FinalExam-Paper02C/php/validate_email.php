<?php
declare(strict_types=1);

require_once __DIR__ . '/bootstrap.php';

header('Content-Type: application/json; charset=UTF-8');

$email = strtolower(trim((string) ($_GET['email'] ?? '')));
$isValid = filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
$available = $isValid ? !email_exists($email) : false;

echo json_encode([
    'valid' => $isValid,
    'available' => $available,
]);
