<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include('client/config.php');

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

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-image: url('./Images/animal.13.jpeg'); 
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            position: relative;
            color: #333;
        }

        .container {
            background-color: rgba(255, 255, 255, 0.8); /* Semi-transparent background */
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }

        h2 {
            color: #333;
            margin-bottom: 20px;
        }

        form .form-group {
            margin-bottom: 20px;
            text-align: left;
        }

        form label {
            display: block;
            margin-bottom: 5px;
            color: #555;
        }

        form input[type="text"],
        form input[type="email"],
        form input[type="password"],
        form select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        form button {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            border: none;
            color: #fff;
            font-size: 16px;
            cursor: pointer;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        form button:hover {
            background-color: #0056b3;
        }

        .register-link {
            margin-top: 20px;
        }

        .register-link a {
            color: #007bff;
            text-decoration: none;
            transition: color 0.3s;
        }

        .register-link a:hover {
            color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Sign Up</h2>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
        <div class="form-group">
                <label for="Firstname">First Name:</label>
                <input type="text" id="Firstname" name="Firstname" required>
            </div>
            <div class="form-group">
                <label for="Surname">Surname:</label>
                <input type="text" id="Surname" name="Surname" required>
            </div>
            <div class="form-group">
                <label for="Username">Username:</label>
                <input type="text" id="Username" name="Username" required>
            </div>
            <div class="form-group">
                <label for="Email">Email Address:</label>
                <input type="email" id="Email" name="Email" required>
            </div>
            <div class="form-group">
                <label for="Number">Mobile Number:</label>
                <input type="text" id="Number" name="Number" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="confirm_password">Confirm Password:</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
            </div>
            <button type="submit">Submit</button>
        </form>
        
        <div class="register-link">
            <p>Have an account? <a href="login.php">Login</a></p> 
        </div>
    </div>
</body>
</html>

