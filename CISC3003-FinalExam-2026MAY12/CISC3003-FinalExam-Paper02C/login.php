<?php
declare(strict_types=1);

require_once __DIR__ . '/php/bootstrap.php';

if (current_user()) {
    redirect('dashboard.php');
}

$flash = get_flash();
$old = pull_form_state('login_old');
$errors = pull_form_state('login_errors');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scenario C | Login</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="page-shell narrow-shell">
        <main class="card auth-card">
            <p class="eyebrow">Welcome Back</p>
            <h1>Login</h1>
            <p class="supporting-copy">Only activated accounts are allowed to sign in.</p>

            <?php if ($flash): ?>
                <section class="flash flash-<?= h($flash['type']) ?>">
                    <strong><?= h($flash['title']) ?></strong>
                    <p><?= h($flash['message']) ?></p>
                </section>
            <?php endif; ?>

            <form action="php/process_login.php" method="post" id="login-form" class="exam-form" novalidate>
                <label for="login_email">Email address</label>
                <input type="email" id="login_email" name="email" maxlength="190" required value="<?= h(old_value($old, 'email')) ?>">
                <?php if (isset($errors['email'])): ?><small class="error-text"><?= h($errors['email']) ?></small><?php endif; ?>

                <label for="login_password">Password</label>
                <input type="password" id="login_password" name="password" minlength="8" required>
                <?php if (isset($errors['password'])): ?><small class="error-text"><?= h($errors['password']) ?></small><?php endif; ?>

                <div class="button-row">
                    <button type="submit" id="login-button">Sign In</button>
                    <a class="button-link secondary-link" href="forgot-password.php">Forgot Password?</a>
                </div>
            </form>

            <div class="aux-links">
                <a href="register.php">Create an account</a>
                <a href="resend-activation.php">Resend activation email</a>
            </div>
        </main>
        <footer><?= h(footer_text()) ?></footer>
    </div>
    <script type="module" src="script.js"></script>
</body>
</html>
