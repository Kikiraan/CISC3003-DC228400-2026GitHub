<?php
declare(strict_types=1);

require_once __DIR__ . '/php/bootstrap.php';

$token = trim((string) ($_GET['token'] ?? ''));
$success = false;
$message = 'The activation link is invalid or has already been used.';

if ($token !== '') {
    $tokenHash = hash('sha256', $token);
    $user = find_user_by_token_column('account_activation_hash', $tokenHash);

    if ($user) {
        $connection = get_db_connection();
        $statement = $connection->prepare(
            'UPDATE users
             SET is_active = 1, account_activation_hash = NULL, email_verified_at = NOW()
             WHERE id = ?'
        );

        if ($statement) {
            $userId = (int) $user['id'];
            $statement->bind_param('i', $userId);
            $statement->execute();
            $statement->close();
            $success = true;
            $message = 'Your account has been activated successfully. You can now sign in.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scenario C | Verify Account</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="page-shell narrow-shell">
        <main class="card auth-card">
            <p class="eyebrow">Email Verification</p>
            <h1><?= $success ? 'Account Activated' : 'Activation Failed' ?></h1>
            <section class="flash <?= $success ? 'flash-success' : 'flash-error' ?>">
                <strong><?= $success ? 'Success' : 'Unable to activate' ?></strong>
                <p><?= h($message) ?></p>
            </section>
            <p><a href="login.php">Go to Login</a></p>
        </main>
        <footer><?= h(footer_text()) ?></footer>
    </div>
</body>
</html>
