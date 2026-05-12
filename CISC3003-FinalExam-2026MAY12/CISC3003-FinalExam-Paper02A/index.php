<?php
declare(strict_types=1);

require_once __DIR__ . '/php/bootstrap.php';

$flash = get_flash();
$old = $_SESSION['old_input'] ?? [];
$errors = $_SESSION['form_errors'] ?? [];
unset($_SESSION['old_input'], $_SESSION['form_errors']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scenario A | Student Feedback Form</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="page-shell">
        <header class="hero">
            <p class="eyebrow">CISC3003 Final Exam Paper 02 - Scenario A</p>
            <h1>Student Experience &amp; Service Request Form</h1>
            <p class="hero-text">
                This page demonstrates semantic HTML form design, server-side validation with PHP,
                SQL injection prevention, and MySQL data insertion with prepared statements.
            </p>
            <nav class="top-nav">
                <a href="index.php">Form</a>
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
                <strong>Please correct the highlighted fields.</strong>
                <p>The submission was not saved because some values did not pass validation.</p>
            </section>
        <?php endif; ?>

        <main class="card">
            <form action="php/process_form.php" method="post" class="exam-form" novalidate>
                <fieldset>
                    <legend>Student Profile</legend>

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

                    <label for="phone">Mobile number</label>
                    <input
                        type="text"
                        id="phone"
                        name="phone"
                        maxlength="20"
                        placeholder="+853 1234 5678"
                        value="<?= h(old_value($old, 'phone')) ?>"
                    >
                    <?php if (isset($errors['phone'])): ?>
                        <small class="error-text"><?= h($errors['phone']) ?></small>
                    <?php endif; ?>

                    <label for="age">Age</label>
                    <input
                        type="number"
                        id="age"
                        name="age"
                        min="16"
                        max="99"
                        required
                        value="<?= h(old_value($old, 'age')) ?>"
                    >
                    <?php if (isset($errors['age'])): ?>
                        <small class="error-text"><?= h($errors['age']) ?></small>
                    <?php endif; ?>
                </fieldset>

                <fieldset>
                    <legend>Study Preferences</legend>

                    <label for="program">Programme enrolled</label>
                    <select id="program" name="program" required>
                        <option value="">Select a programme</option>
                        <?php foreach (scenario_a_programs() as $program): ?>
                            <option value="<?= h($program) ?>" <?= selected($old, 'program', $program) ?>>
                                <?= h($program) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <?php if (isset($errors['program'])): ?>
                        <small class="error-text"><?= h($errors['program']) ?></small>
                    <?php endif; ?>

                    <fieldset class="nested-fieldset">
                        <legend>Preferred study mode</legend>
                        <?php foreach (scenario_a_study_modes() as $studyMode): ?>
                            <label class="choice-row">
                                <input
                                    type="radio"
                                    name="study_mode"
                                    value="<?= h($studyMode) ?>"
                                    <?= checked($old, 'study_mode', $studyMode) ?>
                                    required
                                >
                                <span><?= h($studyMode) ?></span>
                            </label>
                        <?php endforeach; ?>
                        <?php if (isset($errors['study_mode'])): ?>
                            <small class="error-text"><?= h($errors['study_mode']) ?></small>
                        <?php endif; ?>
                    </fieldset>

                    <fieldset class="nested-fieldset">
                        <legend>Services you want to use</legend>
                        <?php foreach (scenario_a_interests() as $interest): ?>
                            <label class="choice-row">
                                <input
                                    type="checkbox"
                                    name="interests[]"
                                    value="<?= h($interest) ?>"
                                    <?= checked_array($old, 'interests', $interest) ?>
                                >
                                <span><?= h($interest) ?></span>
                            </label>
                        <?php endforeach; ?>
                        <?php if (isset($errors['interests'])): ?>
                            <small class="error-text"><?= h($errors['interests']) ?></small>
                        <?php endif; ?>
                    </fieldset>
                </fieldset>

                <fieldset>
                    <legend>Comments</legend>

                    <label for="experience">Describe your learning experience</label>
                    <textarea
                        id="experience"
                        name="experience"
                        rows="6"
                        maxlength="800"
                        placeholder="Share what works well and what can be improved."
                        required
                    ><?= h(old_value($old, 'experience')) ?></textarea>
                    <small id="textarea-counter" class="supporting-text">0 / 800 characters</small>
                    <?php if (isset($errors['experience'])): ?>
                        <small class="error-text"><?= h($errors['experience']) ?></small>
                    <?php endif; ?>

                    <label class="choice-row checkbox-standalone">
                        <input
                            type="checkbox"
                            name="terms"
                            value="1"
                            <?= old_value($old, 'terms') ? 'checked' : '' ?>
                            required
                        >
                        <span>I confirm the information above is accurate.</span>
                    </label>
                    <?php if (isset($errors['terms'])): ?>
                        <small class="error-text"><?= h($errors['terms']) ?></small>
                    <?php endif; ?>
                </fieldset>

                <div class="button-row">
                    <button type="submit" id="submit-button">Submit Form</button>
                    <button type="reset" class="secondary" id="reset-button">Reset Form</button>
                </div>
            </form>
        </main>

        <footer>
            <?= h(footer_text()) ?>
        </footer>
    </div>
    <script type="module" src="script.js"></script>
</body>
</html>
