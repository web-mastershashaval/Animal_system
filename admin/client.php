<?php
include('config.php');
// Enable error reporting for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();
// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Add new client
    if (isset($_POST['add_client'])) {
        $firstname = $_POST['first_name'];
        $surname = $_POST['last_name'];
        $email = $_POST['email'];
        $number = $_POST['phone'];

        // Prepare and bind
        $sql = "INSERT INTO users (firstname, surname, email, number) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);

        if (!$stmt) {
            die("Error preparing statement: " . $conn->error);
        }

        $stmt->bind_param("ssss", $firstname, $surname, $email, $number);
        if ($stmt->execute()) {
            echo "New client added successfully!";
        } else {
            echo "Error adding client: " . $stmt->error;
        }

        $stmt->close();
    }

    // Update client details
    if (isset($_POST['update_client'])) {
        $clientId = $_POST['client_id'];
        $firstname = $_POST['first_name'];
        $surname = $_POST['last_name'];
        $email = $_POST['email'];
        $number = $_POST['phone'];

        // Prepare and bind
        $sql = "UPDATE users SET firstname=?, surname=?, email=?, number=? WHERE id=?";
        $stmt = $conn->prepare($sql);

        if (!$stmt) {
            die("Error preparing statement: " . $conn->error);
        }

        $stmt->bind_param("ssssi", $firstname, $surname, $email, $number, $clientId);
        if ($stmt->execute()) {
            echo "Client updated successfully!";
        } else {
            echo "Error updating client: " . $stmt->error;
        }

        $stmt->close();
    }

    // Delete a client
    if (isset($_POST['delete_client'])) {
        $clientId = $_POST['client_id'];

        // Prepare and bind
        $sql = "DELETE FROM users WHERE id=?";
        $stmt = $conn->prepare($sql);

        if (!$stmt) {
            die("Error preparing statement: " . $conn->error);
        }

        $stmt->bind_param("i", $clientId);
        if ($stmt->execute()) {
            echo "Client deleted successfully!";
        } else {
            echo "Error deleting client: " . $stmt->error;
        }

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

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Client Management</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
            display: flex;
            min-height: 100vh;
            flex-direction: column;
            align-items: center;
            padding-top: 20px;
        }
        .container {
            width: 100%;
            max-width: 800px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1 {
            font-size: 24px;
            color: #333;
        }
        .form-container {
            width: 100%;
            background-color: #fff;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            color: #666;
            margin-bottom: 5px;
            font-weight: bold;
        }
        .form-group input[type="text"],
        .form-group input[type="email"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 14px;
        }
        .submit-btn {
            background-color: blue;
            color: #fff;
            border: none;
            padding: 12px;
            font-size: 16px;
            cursor: pointer;
            width: 100%;
            border-radius: 5px;
        }
        .submit-btn:hover {
            background-color: #555;
        }
        .table-container {
            width: 100%;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table thead {
            background-color: green;
            color: #fff;
        }
        table th, table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        table tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        table tbody tr:hover {
            background-color: #f1f1f1;
        }
        .action-btn {
            background-color: red;
            color: white;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
            border-radius: 5px;
        }
        .edit-btn {
            background-color: #555;
            margin-right: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Client Management</h1>
        </div>

        <div class="form-container">
            <h2>Add New Client</h2>
            <form action="client.php" method="POST">
                <div class="form-group">
                    <label for="first_name">First Name</label>
                    <input type="text" id="first_name" name="first_name" required>
                </div>

                <div class="form-group">
                    <label for="last_name">Last Name</label>
                    <input type="text" id="last_name" name="last_name" required>
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required>
                </div>

                <div class="form-group">
                    <label for="phone">Phone</label>
                    <input type="text" id="phone" name="phone">
                </div>

                <button type="submit" name="add_client" class="submit-btn">Add Client</button>
            </form>
        </div>

        <div class="table-container">
            <h2>Client List</h2>
            <table>
                <thead>
                    <tr>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['firstname']); ?></td>
                        <td><?php echo htmlspecialchars($row['surname']); ?></td>
                        <td><?php echo htmlspecialchars($row['email']); ?></td>
                        <td><?php echo htmlspecialchars($row['number']); ?></td>
                        <td>
                            <form action="client.php" method="POST" style="display:inline;">
                                <input type="hidden" name="client_id" value="<?php echo $row['id']; ?>">
                                <button type="submit" name="delete_client" class="action-btn">Delete</button>
                            </form>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
