<?php
declare(strict_types=1);

require_once __DIR__ . '/php/bootstrap.php';

$rows = [];
$dbError = null;

try {
    $connection = get_db_connection();
    $result = $connection->query(
        'SELECT id, full_name, email, phone, topic, message, mail_status, created_at
         FROM contact_messages
         ORDER BY created_at DESC
         LIMIT 25'
    );

    if ($result instanceof mysqli_result) {
        $rows = $result->fetch_all(MYSQLI_ASSOC);
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
    <title>Scenario B | Message Dashboard</title>
    <link rel="stylesheet" href="dashboard.css">
</head>
<body>
    <div class="page-shell">
        <header class="hero compact-hero">
            <p class="eyebrow">Scenario B Dashboard</p>
            <h1>Contact Message History</h1>
            <p class="hero-text">Use this page to prove that the form submission, PRG flow, and database logging are working together.</p>
            <nav class="top-nav">
                <a href="index.php">Back to Contact Form</a>
            </nav>
        </header>

        <main class="card">
            <?php if ($dbError): ?>
                <section class="flash flash-error">
                    <strong>Database not ready yet.</strong>
                    <p><?= h($dbError) ?></p>
                </section>
            <?php elseif (!$rows): ?>
                <section class="empty-state">
                    <h2>No messages saved yet</h2>
                    <p>Import <code>database.sql</code>, then submit the form to populate this dashboard.</p>
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
                                <th>Topic</th>
                                <th>Message</th>
                                <th>Mail Status</th>
                                <th>Created At</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($rows as $row): ?>
                                <tr>
                                    <td><?= h((string) $row['id']) ?></td>
                                    <td><?= h($row['full_name']) ?></td>
                                    <td><?= h($row['email']) ?></td>
                                    <td><?= h($row['phone']) ?></td>
                                    <td><?= h($row['topic']) ?></td>
                                    <td><?= h($row['message']) ?></td>
                                    <td><?= h($row['mail_status']) ?></td>
                                    <td><?= h($row['created_at']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </main>

        <footer><?= h(footer_text()) ?></footer>
    </div>
</body>
</html>
