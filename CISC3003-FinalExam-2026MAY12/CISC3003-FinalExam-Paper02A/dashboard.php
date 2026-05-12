<?php
declare(strict_types=1);

require_once __DIR__ . '/php/bootstrap.php';

$records = [];
$dbError = null;

try {
    $connection = get_db_connection();
    $result = $connection->query(
        'SELECT id, full_name, email, phone, age, program, study_mode, interests, experience, created_at
         FROM student_feedback
         ORDER BY created_at DESC
         LIMIT 20'
    );

    if ($result instanceof mysqli_result) {
        $records = $result->fetch_all(MYSQLI_ASSOC);
    }
} catch (Throwable $exception) {
    $dbError = $exception->getMessage();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scenario A | Submission Dashboard</title>
    <link rel="stylesheet" href="dashboard.css">
</head>
<body>
    <div class="page-shell">
        <header class="hero compact-hero">
            <p class="eyebrow">Scenario A Dashboard</p>
            <h1>Recent Student Feedback Records</h1>
            <p class="hero-text">This table helps you capture proof that the PHP form processing and MySQL insert are working.</p>
            <nav class="top-nav">
                <a href="index.php">Back to Form</a>
                <a href="db/sample_insert.sql">Manual Insert SQL</a>
            </nav>
        </header>

        <main class="card">
            <?php if ($dbError): ?>
                <section class="flash flash-error">
                    <strong>Database not ready yet.</strong>
                    <p><?= h($dbError) ?></p>
                </section>
            <?php elseif (!$records): ?>
                <section class="empty-state">
                    <h2>No records yet</h2>
                    <p>Run <code>database.sql</code>, then submit the form or execute <code>db/sample_insert.sql</code>.</p>
                </section>
            <?php else: ?>
                <div class="table-scroll">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Age</th>
                                <th>Program</th>
                                <th>Study Mode</th>
                                <th>Interests</th>
                                <th>Experience</th>
                                <th>Created At</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($records as $record): ?>
                                <tr>
                                    <td><?= h((string) $record['id']) ?></td>
                                    <td><?= h($record['full_name']) ?></td>
                                    <td><?= h($record['email']) ?></td>
                                    <td><?= h($record['phone']) ?></td>
                                    <td><?= h((string) $record['age']) ?></td>
                                    <td><?= h($record['program']) ?></td>
                                    <td><?= h($record['study_mode']) ?></td>
                                    <td><?= h($record['interests']) ?></td>
                                    <td><?= h($record['experience']) ?></td>
                                    <td><?= h($record['created_at']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </main>

        <footer>
            <?= h(footer_text()) ?>
        </footer>
    </div>
</body>
</html>
