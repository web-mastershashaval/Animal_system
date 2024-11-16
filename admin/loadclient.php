<?php
include('config.php');
session_start();
// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Add new client
    if (isset($_POST['add_client'])) {
        $firstname = $_POST['first_name'];
        $surname = $_POST['last_name'];
        $email = $_POST['email'];
        $number = $_POST['phone'];

        $sql = "INSERT INTO users (firstname, surname, email, number) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);

        // Error handling for prepare
        if (!$stmt) {
            die("Error preparing statement: " . $conn->error);
        }

        $stmt->bind_param("ssss", $firstname, $surname, $email, $number);
        $stmt->execute();
        $stmt->close();
    }

    // Update client details
    if (isset($_POST['update_client'])) {
        $clientId = $_POST['client_id'];
        $firstname = $_POST['first_name'];
        $surname = $_POST['last_name'];
        $email = $_POST['email'];
        $number = $_POST['phone'];

        $sql = "UPDATE users SET firstname=?, surname=?, email=?, number=? WHERE id=?";
        $stmt = $conn->prepare($sql);

        // Error handling for prepare
        if (!$stmt) {
            die("Error preparing statement: " . $conn->error);
        }

        $stmt->bind_param("ssssi", $firstname, $surname, $email, $number, $clientId);
        $stmt->execute();
        $stmt->close();
    }

    // Delete a client
    if (isset($_POST['delete_client'])) {
        $clientId = $_POST['client_id'];

        $sql = "DELETE FROM users WHERE id=?";
        $stmt = $conn->prepare($sql);

        // Error handling for prepare
        if (!$stmt) {
            die("Error preparing statement: " . $conn->error);
        }

        $stmt->bind_param("i", $clientId);
        $stmt->execute();
        $stmt->close();
    }
}

// Fetch all clients
$query = "SELECT * FROM users";
$result = $conn->query($query);

// Error handling for query
if (!$result) {
    die("Error executing query: " . $conn->error);
}
?>