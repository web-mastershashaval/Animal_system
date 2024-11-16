<?php
session_start();
include('config.php'); 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Prepare SQL statement to get user details along with their role
    $sql = "SELECT id, username, password, identity FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Verify the hashed password
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['identity'] = $user['identity']; // Store the role in the session

            // Redirect based on the user's role
            if ($user['identity'] === 'admin') {
                header("Location: /admin/admin_dashboard.php"); // Redirect to admin dashboard
            } else {
                header("Location: client/lient_dashboard.php"); // Redirect to client dashboard
            }
            exit();
        } else {
            echo "Invalid username or password.";
            header('Location:register.php');
        }
    } else {
        echo "Invalid username or password.";
        header('Location:register.php');
    }

    $stmt->close();
    $conn->close();
}
?>
