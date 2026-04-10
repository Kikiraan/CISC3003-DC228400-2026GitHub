<?php
session_start();

if (!isset($_SESSION['user_email'])) {
    header('Location: index.php');
    exit();
}

include 'connect.php';

$email = $_SESSION['user_email'];
$stmt = $conn->prepare("SELECT fullname, email, created_at FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

$stmt->close();
$conn->close();

if (!$user) {
    session_unset();
    session_destroy();
    header('Location: index.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>dc228400 Wang Yufeng Dashboard</title>
    <link rel="stylesheet" href="css/dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer">
</head>
<body>
    <main class="page-shell">
        <section class="dashboard-container">
            <nav class="navbar">
                <h1>Welcome, <span id="username"><?php echo htmlspecialchars($user['fullname']); ?></span></h1>
                <a href="logout.php" class="btn logout-btn">Logout <i class="fas fa-sign-out-alt" aria-hidden="true"></i></a>
            </nav>

            <section class="user-details">
                <img src="images/website_7376495.png" alt="Profile illustration">
                <h2>Your Profile</h2>
                <p><strong>Full Name:</strong> <?php echo htmlspecialchars($user['fullname']); ?></p>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
                <p><strong>Member Since:</strong> <?php echo htmlspecialchars(date('F Y', strtotime($user['created_at']))); ?></p>
            </section>

            <section class="dashboard-actions">
                <button class="btn" type="button">Update Profile</button>
                <button class="btn" type="button">View Reports</button>
                <button class="btn" type="button">Settings</button>
            </section>
        </section>

        <footer class="page-footer">CISC3003 Web Programming: dc228400 Wang Yufeng 2026</footer>
    </main>
</body>
</html>
