<?php
declare(strict_types=1);

require_once __DIR__ . '/php/bootstrap.php';

$flash = get_flash();
$user = current_user();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scenario C | Auth Portal</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="page-shell">
        <header class="hero">
            <p class="eyebrow">CISC3003 Final Exam Paper 02 - Scenario C</p>
            <h1>Secure SignUp / SignIn Portal</h1>
            <p class="hero-text">
                This project demonstrates secure registration, JavaScript validation, Ajax email validation,
                email confirmation before login, password reset by email, and a protected dashboard.
            </p>
            <div class="hero-actions">
                <a href="register.php">Create Account</a>
                <a href="login.php">Sign In</a>
                <a href="forgot-password.php">Forgot Password</a>
                <a href="resend-activation.php">Resend Activation</a>
                <?php if ($user): ?>
                    <a href="dashboard.php">Open Dashboard</a>
                <?php endif; ?>
            </div>
        </header>

        <?php if ($flash): ?>
            <section class="flash flash-<?= h($flash['type']) ?>">
                <strong><?= h($flash['title']) ?></strong>
                <p><?= h($flash['message']) ?></p>
            </section>
        <?php endif; ?>

        <main class="grid-layout">
            <section class="card">
                <h2>Quick Links</h2>
                <div class="service-list">
                    <a class="service-card" href="register.php">
                        <strong>Register</strong>
                        <span>Create a new account with server-side validation.</span>
                    </a>
                    <a class="service-card" href="login.php">
                        <strong>Login</strong>
                        <span>Sign in only after email activation.</span>
                    </a>
                    <a class="service-card" href="forgot-password.php">
                        <strong>Password Reset</strong>
                        <span>Request a secure reset link by email.</span>
                    </a>
                    <a class="service-card" href="dashboard.php">
                        <strong>User Dashboard</strong>
                        <span>View account details and manage your profile.</span>
                    </a>
                </div>
            </section>
        </main>

        <footer><?= h(footer_text()) ?></footer>
    </div>
</body>
</html>
