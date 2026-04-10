<?php
include 'connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullname = trim($_POST['fullname'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($fullname === '' || $email === '' || $password === '') {
        echo "<script>alert('Please fill in all required fields.'); window.history.back();</script>";
        exit();
    }

    $check = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        $check->close();
        $conn->close();
        echo "<script>alert('Email already exists.'); window.history.back();</script>";
        exit();
    }

    $check->close();

    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    $stmt = $conn->prepare("INSERT INTO users (fullname, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $fullname, $email, $hashedPassword);

    if ($stmt->execute()) {
        $stmt->close();
        $conn->close();
        echo "<script>alert('Registration successful!'); window.location.href='index.php';</script>";
        exit();
    }

    $error = addslashes($conn->error);
    $stmt->close();
    $conn->close();
    echo "<script>alert('Registration failed: ' + " . json_encode($error) . "); window.history.back();</script>";
    exit();
}
?>
