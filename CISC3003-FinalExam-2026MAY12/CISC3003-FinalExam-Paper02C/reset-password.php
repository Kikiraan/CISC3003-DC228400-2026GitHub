<?php
declare(strict_types=1);

require_once __DIR__ . '/php/bootstrap.php';

$token = trim((string) ($_GET['token'] ?? ''));
$flash = get_flash();
$errors = pull_form_state('reset_errors');
$validUser = null;

if ($token !== '') {
    $validUser = find_user_by_token_column('password_reset_hash', hash('sha256', $token));
    if ($validUser && isset($validUser['password_reset_expires_at']) && strtotime((string) $validUser['password_reset_expires_at']) < time()) {
        $validUser = null;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scenario C | Reset Password</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="page-shell narrow-shell">
        <main class="card auth-card">
            <p class="eyebrow">Secure Password Reset</p>
            <h1>Reset Password</h1>

            <?php if ($flash): ?>
                <section class="flash flash-<?= h($flash['type']) ?>">
                    <strong><?= h($flash['title']) ?></strong>
                    <p><?= h($flash['message']) ?></p>
                </section>
            <?php endif; ?>

            <?php if (!$validUser): ?>
                <p>The password reset link is invalid or has expired.</p>
                <p><a href="forgot-password.php">Request a new reset link</a></p>
            <?php else: ?>
                <form action="php/process_reset_password.php" method="post" class="exam-form">
                    <input type="hidden" name="token" value="<?= h($token) ?>">

                    <label for="new_password">New password</label>
                    <input type="password" id="new_password" name="password" minlength="8" required>
                    <?php if (isset($errors['password'])): ?><small class="error-text"><?= h($errors['password']) ?></small><?php endif; ?>

                    <label for="confirm_new_password">Confirm new password</label>
                    <input type="password" id="confirm_new_password" name="confirm_password" minlength="8" required>
                    <?php if (isset($errors['confirm_password'])): ?><small class="error-text"><?= h($errors['confirm_password']) ?></small><?php endif; ?>

                    <button type="submit">Update Password</button>
                </form>
            <?php endif; ?>
        </main>
        <footer><?= h(footer_text()) ?></footer>
    </div>
</body>
</html>
