<?php
declare(strict_types=1);

require_once __DIR__ . '/php/bootstrap.php';

$flash = get_flash();
$debugLog = $_SESSION['mail_debug'] ?? [];
$old = $_SESSION['old_input'] ?? [];
$errors = $_SESSION['form_errors'] ?? [];
unset($_SESSION['mail_debug'], $_SESSION['old_input'], $_SESSION['form_errors']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scenario B | Contact Form with PHPMailer</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="page-shell">
        <header class="hero">
            <p class="eyebrow">CISC3003 Final Exam Paper 02 - Scenario B</p>
            <h1>Contact Service with PHPMailer</h1>
            <p class="hero-text">
                This project demonstrates an HTML contact form, client-side validation, server-side validation,
                PHPMailer configuration, SMTP debugging, and the post / redirect / get pattern.
            </p>
            <nav class="top-nav">
                <a href="index.php">Contact Form</a>
                <a href="dashboard.php">Submission Dashboard</a>
                <a href="register.php">Register Page</a>
                <a href="login.php">Login Page</a>
            </nav>
        </header>

        <?php if ($flash): ?>
            <section class="flash flash-<?= h($flash['type']) ?>">
                <strong><?= h($flash['title']) ?></strong>
                <p><?= h($flash['message']) ?></p>
            </section>
        <?php endif; ?>

        <?php if ($errors): ?>
            <section class="flash flash-error">
                <strong>Some fields need attention.</strong>
                <p>Please correct the invalid inputs and submit the form again.</p>
            </section>
        <?php endif; ?>

        <main class="card">
            <form action="php/contact_process.php" method="post" id="contact-form" class="exam-form" novalidate>
                <label for="full_name">Full name</label>
                <input
                    type="text"
                    id="full_name"
                    name="full_name"
                    maxlength="100"
                    required
                    value="<?= h(old_value($old, 'full_name')) ?>"
                >
                <?php if (isset($errors['full_name'])): ?>
                    <small class="error-text"><?= h($errors['full_name']) ?></small>
                <?php endif; ?>

                <label for="email">Email address</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    maxlength="150"
                    required
                    value="<?= h(old_value($old, 'email')) ?>"
                >
                <?php if (isset($errors['email'])): ?>
                    <small class="error-text"><?= h($errors['email']) ?></small>
                <?php endif; ?>

                <label for="phone">Phone number</label>
                <input
                    type="tel"
                    id="phone"
                    name="phone"
                    maxlength="20"
                    placeholder="+853 6200 2400"
                    value="<?= h(old_value($old, 'phone')) ?>"
                >
                <?php if (isset($errors['phone'])): ?>
                    <small class="error-text"><?= h($errors['phone']) ?></small>
                <?php endif; ?>

                <label for="topic">Topic</label>
                <select id="topic" name="topic" required>
                    <option value="">Choose a topic</option>
                    <?php foreach (scenario_b_topics() as $topic): ?>
                        <option value="<?= h($topic) ?>" <?= selected($old, 'topic', $topic) ?>><?= h($topic) ?></option>
                    <?php endforeach; ?>
                </select>
                <?php if (isset($errors['topic'])): ?>
                    <small class="error-text"><?= h($errors['topic']) ?></small>
                <?php endif; ?>

                <label for="message">Message</label>
                <textarea
                    id="message"
                    name="message"
                    rows="7"
                    maxlength="1200"
                    required
                    placeholder="Explain your request clearly so that the email output is meaningful."
                ><?= h(old_value($old, 'message')) ?></textarea>
                <small id="message-counter" class="supporting-text">0 / 1200 characters</small>
                <?php if (isset($errors['message'])): ?>
                    <small class="error-text"><?= h($errors['message']) ?></small>
                <?php endif; ?>

                <label class="choice-row">
                    <input
                        type="checkbox"
                        id="debug_mode"
                        name="debug_mode"
                        value="1"
                        <?= old_value($old, 'debug_mode') ? 'checked' : '' ?>
                    >
                    <span>Enable SMTP debug logging for this request</span>
                </label>

                <div class="button-row">
                    <button type="submit" id="send-button">Send Message</button>
                    <button type="reset" class="secondary" id="clear-button">Clear Form</button>
                </div>
            </form>
        </main>

        <?php if ($debugLog): ?>
            <section class="card debug-card">
                <h2>SMTP Debug Output</h2>
                <p>This block is useful for proving task B.04 when you test failed or successful mail delivery.</p>
                <pre><?= h(implode(PHP_EOL, $debugLog)) ?></pre>
            </section>
        <?php endif; ?>

        <footer>
            <?= h(footer_text()) ?>
        </footer>
    </div>
    <script type="module" src="script.js"></script>
</body>
</html>
