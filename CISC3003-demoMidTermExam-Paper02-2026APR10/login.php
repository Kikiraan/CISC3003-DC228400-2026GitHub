<?php
session_start();
include 'connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($email === '' || $password === '') {
        echo "<script>alert('Please enter both email and password.'); window.history.back();</script>";
        exit();
    }

    $stmt = $conn->prepare("SELECT fullname, email, password, created_at FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if (!$user) {
        $stmt->close();
        $conn->close();
        echo "<script>alert('No account found with that email.'); window.history.back();</script>";
        exit();
    }

    if (!password_verify($password, $user['password'])) {
        $stmt->close();
        $conn->close();
        echo "<script>alert('Incorrect password.'); window.history.back();</script>";
        exit();
    }

    $_SESSION['user_email'] = $user['email'];
    $_SESSION['user_name'] = $user['fullname'];

    $stmt->close();
    $conn->close();

    header('Location: dashboard.php');
    exit();
}
?>
