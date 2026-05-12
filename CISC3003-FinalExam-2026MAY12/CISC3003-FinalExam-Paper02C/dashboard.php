<?php
declare(strict_types=1);

require_once __DIR__ . '/php/bootstrap.php';

$user = require_login();
$flash = get_flash();
$errors = pull_form_state('profile_errors');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scenario C | Dashboard</title>
    <link rel="stylesheet" href="dashboard.css">
</head>
<body>
    <div class="page-shell">
        <header class="hero compact-hero">
            <p class="eyebrow">Protected User Dashboard</p>
            <h1>Hello, <?= h($user['name']) ?></h1>
            <p class="hero-text">
                You joined this site on <?= h(format_datetime($user['created_at'])) ?>.
                Your last login was <?= h(format_datetime($user['last_login_at'])) ?>.
            </p>
            <div class="hero-actions">
                <a href="index.php">Homepage</a>
                <a href="forgot-password.php">Request Password Reset</a>
                <a href="logout.php">Logout</a>
            </div>
        </header>

        <?php if ($flash): ?>
            <section class="flash flash-<?= h($flash['type']) ?>">
                <strong><?= h($flash['title']) ?></strong>
                <p><?= h($flash['message']) ?></p>
            </section>
        <?php endif; ?>

        <main class="dashboard-grid">
            <section class="card">
                <h2>Account Summary</h2>
                <dl class="summary-grid">
                    <div>
                        <dt>Full Name</dt>
                        <dd><?= h($user['name']) ?></dd>
                    </div>
                    <div>
                        <dt>Student ID</dt>
                        <dd><?= h($user['student_id']) ?></dd>
                    </div>
                    <div>
                        <dt>Email</dt>
                        <dd><?= h($user['email']) ?></dd>
                    </div>
                    <div>
                        <dt>Email Status</dt>
                        <dd><?= $user['is_active'] ? 'Activated' : 'Pending Activation' ?></dd>
                    </div>
                    <div>
                        <dt>Member Since</dt>
                        <dd><?= h(format_datetime($user['created_at'])) ?></dd>
                    </div>
                    <div>
                        <dt>Verified At</dt>
                        <dd><?= h(format_datetime($user['email_verified_at'])) ?></dd>
                    </div>
                </dl>
            </section>

            <section class="card">
                <h2>Profile Update</h2>
                <form action="php/process_profile.php" method="post" class="exam-form">
                    <label for="profile_name">Update full name</label>
                    <input type="text" id="profile_name" name="name" maxlength="120" required value="<?= h($user['name']) ?>">
                    <?php if (isset($errors['name'])): ?><small class="error-text"><?= h($errors['name']) ?></small><?php endif; ?>
                    <button type="submit">Save Profile</button>
                </form>
            </section>

            <section class="card">
                <h2>Services Under Your Control</h2>
                <div class="service-list">
                    <a class="service-card" href="forgot-password.php">
                        <strong>Password Reset</strong>
                        <span>Send yourself a secure password reset link.</span>
                    </a>
                    <a class="service-card" href="index.php">
                        <strong>Portal Homepage</strong>
                        <span>Return to the main landing page.</span>
                    </a>
                    <a class="service-card" href="logout.php">
                        <strong>Logout</strong>
                        <span>End your session safely.</span>
                    </a>
                </div>
            </section>
        </main>

        <footer><?= h(footer_text()) ?></footer>
    </div>
</body>
</html>
