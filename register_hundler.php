<?php
include('config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $surname = $_POST['Surname'];
    $firstname = $_POST['Firstname'];
    $username = $_POST['Username'];
    $email = $_POST['Email'];
    $number = $_POST['Number'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password !== $confirm_password) {
        die("Passwords do not match.");
    }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Check if there are any existing users
    $result = $conn->query("SELECT COUNT(*) AS user_count FROM users");
    $row = $result->fetch_assoc();
    $user_count = $row['user_count'];

    // Assign role based on whether users exist or not
    $identity = ($user_count == 0) ? 'admin' : 'client';

    $stmt = $conn->prepare("INSERT INTO users (surname, firstname, username, email, number, password, identity) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssss", $surname, $firstname, $username, $email, $number, $hashed_password, $identity);

    if ($stmt->execute()) {
        echo "Registration successful!";
        header("Location: login.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
