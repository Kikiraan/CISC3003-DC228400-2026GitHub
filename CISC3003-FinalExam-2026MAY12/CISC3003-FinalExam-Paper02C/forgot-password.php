<?php
declare(strict_types=1);

require_once __DIR__ . '/php/bootstrap.php';

$flash = get_flash();
$old = pull_form_state('forgot_old');
$errors = pull_form_state('forgot_errors');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scenario C | Forgot Password</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="page-shell narrow-shell">
        <main class="card auth-card">
            <p class="eyebrow">Password Recovery</p>
            <h1>Forgot Password</h1>
            <p class="supporting-copy">Enter your account email to receive a secure reset link.</p>

            <?php if ($flash): ?>
                <section class="flash flash-<?= h($flash['type']) ?>">
                    <strong><?= h($flash['title']) ?></strong>
                    <p><?= h($flash['message']) ?></p>
                </section>
            <?php endif; ?>

            <form action="php/process_forgot_password.php" method="post" class="exam-form">
                <label for="forgot_email">Email address</label>
                <input type="email" id="forgot_email" name="email" maxlength="190" required value="<?= h(old_value($old, 'email')) ?>">
                <?php if (isset($errors['email'])): ?><small class="error-text"><?= h($errors['email']) ?></small><?php endif; ?>

                <div class="button-row">
                    <button type="submit">Send Reset Email</button>
                    <a class="button-link secondary-link" href="login.php">Back to Login</a>
                </div>
            </form>
        </main>
        <footer><?= h(footer_text()) ?></footer>
    </div>
</body>
</html>
