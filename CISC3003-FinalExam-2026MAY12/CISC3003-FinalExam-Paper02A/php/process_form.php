<?php
declare(strict_types=1);

require_once __DIR__ . '/bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('../index.php');
}

$fullName = trim((string) ($_POST['full_name'] ?? ''));
$email = trim((string) ($_POST['email'] ?? ''));
$phone = trim((string) ($_POST['phone'] ?? ''));
$age = (string) ($_POST['age'] ?? '');
$program = trim((string) ($_POST['program'] ?? ''));
$studyMode = trim((string) ($_POST['study_mode'] ?? ''));
$interests = $_POST['interests'] ?? [];
$experience = trim((string) ($_POST['experience'] ?? ''));
$terms = isset($_POST['terms']) ? '1' : '';

$oldInput = [
    'full_name' => $fullName,
    'email' => $email,
    'phone' => $phone,
    'age' => $age,
    'program' => $program,
    'study_mode' => $studyMode,
    'interests' => is_array($interests) ? $interests : [],
    'experience' => $experience,
    'terms' => $terms,
];

$errors = [];

if ($fullName === '' || mb_strlen($fullName) < 2) {
    $errors['full_name'] = 'Please enter a valid full name.';
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors['email'] = 'Please enter a valid email address.';
}

if ($phone !== '' && !preg_match('/^[0-9+()\\-\\s]{6,20}$/', $phone)) {
    $errors['phone'] = 'Please enter a valid phone number.';
}

$ageValue = filter_var(
    $age,
    FILTER_VALIDATE_INT,
    ['options' => ['min_range' => 16, 'max_range' => 99]]
);

if ($ageValue === false) {
    $errors['age'] = 'Age must be between 16 and 99.';
}

if (!in_array($program, scenario_a_programs(), true)) {
    $errors['program'] = 'Please choose a valid programme.';
}

if (!in_array($studyMode, scenario_a_study_modes(), true)) {
    $errors['study_mode'] = 'Please choose a valid study mode.';
}

if (!is_array($interests) || $interests === []) {
    $errors['interests'] = 'Please choose at least one service.';
} else {
    $allowedInterests = scenario_a_interests();
    foreach ($interests as $interest) {
        if (!in_array($interest, $allowedInterests, true)) {
            $errors['interests'] = 'An invalid service option was submitted.';
            break;
        }
    }
}

if ($experience === '' || mb_strlen($experience) < 10) {
    $errors['experience'] = 'Please provide at least 10 characters of feedback.';
}

if ($terms !== '1') {
    $errors['terms'] = 'You must confirm the accuracy of the information.';
}

if ($errors) {
    $_SESSION['old_input'] = $oldInput;
    $_SESSION['form_errors'] = $errors;
    flash('error', 'Validation Failed', 'The form data did not pass server-side validation.');
    redirect('../index.php');
}

$safeInterests = implode(', ', $interests);

try {
    $connection = get_db_connection();
    $statement = $connection->prepare(
        'INSERT INTO student_feedback
        (full_name, email, phone, age, program, study_mode, interests, experience)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)'
    );

    if (!$statement) {
        throw new RuntimeException('Unable to prepare the insert statement.');
    }

    $statement->bind_param(
        'sssissss',
        $fullName,
        $email,
        $phone,
        $ageValue,
        $program,
        $studyMode,
        $safeInterests,
        $experience
    );

    $statement->execute();
    $statement->close();

    flash('success', 'Submission Saved', 'The form data was validated and inserted into MySQL successfully.');
    redirect('../dashboard.php');
} catch (Throwable $exception) {
    $_SESSION['old_input'] = $oldInput;
    flash('error', 'Database Error', $exception->getMessage());
    redirect('../index.php');
}
