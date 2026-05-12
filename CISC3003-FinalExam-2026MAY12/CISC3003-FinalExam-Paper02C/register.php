<?php
declare(strict_types=1);

require_once __DIR__ . '/php/bootstrap.php';

if (current_user()) {
    redirect('dashboard.php');
}

$flash = get_flash();
$old = pull_form_state('register_old');
$errors = pull_form_state('register_errors');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scenario C | Register</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="page-shell narrow-shell">
        <main class="card auth-card">
            <p class="eyebrow">Create Your Account</p>
            <h1>Register</h1>
            <p class="supporting-copy">Your email must be confirmed before you can log in.</p>

            <?php if ($flash): ?>
                <section class="flash flash-<?= h($flash['type']) ?>">
                    <strong><?= h($flash['title']) ?></strong>
                    <p><?= h($flash['message']) ?></p>
                </section>
            <?php endif; ?>

            <form action="php/process_register.php" method="post" id="register-form" class="exam-form" novalidate>
                <label for="name">Full name</label>
                <input type="text" id="name" name="name" maxlength="120" required value="<?= h(old_value($old, 'name')) ?>">
                <?php if (isset($errors['name'])): ?><small class="error-text"><?= h($errors['name']) ?></small><?php endif; ?>

                <label for="student_id">Student ID</label>
                <input type="text" id="student_id" name="student_id" maxlength="20" required value="<?= h(old_value($old, 'student_id')) ?>">
                <small id="student-id-hint" class="supporting-text">Use the format DC228400.</small>
                <?php if (isset($errors['student_id'])): ?><small class="error-text"><?= h($errors['student_id']) ?></small><?php endif; ?>

                <label for="email">Email address</label>
                <input type="email" id="email" name="email" maxlength="190" required value="<?= h(old_value($old, 'email')) ?>">
                <small id="email-availability" class="supporting-text">Enter your email to check whether it is available.</small>
                <?php if (isset($errors['email'])): ?><small class="error-text"><?= h($errors['email']) ?></small><?php endif; ?>

                <label for="password">Password</label>
                <input type="password" id="password" name="password" minlength="8" required>
                <small id="password-hint" class="supporting-text">Use at least 8 characters, including one uppercase letter and one number.</small>
                <?php if (isset($errors['password'])): ?><small class="error-text"><?= h($errors['password']) ?></small><?php endif; ?>

                <label for="confirm_password">Confirm password</label>
                <input type="password" id="confirm_password" name="confirm_password" minlength="8" required>
                <small id="confirm-password-hint" class="supporting-text">Re-enter the same password exactly.</small>
                <?php if (isset($errors['confirm_password'])): ?><small class="error-text"><?= h($errors['confirm_password']) ?></small><?php endif; ?>

                <div class="button-row">
                    <button type="submit" id="register-button">Create Account</button>
                    <a class="button-link secondary-link" href="login.php">Already have an account?</a>
                </div>
            </form>
        </main>
        <footer><?= h(footer_text()) ?></footer>
    </div>
    <script type="module" src="script.js"></script>
</body>
</html>
