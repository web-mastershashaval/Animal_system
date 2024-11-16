<?php
// Include your database configuration file
include('config.php');
session_start();
// Handle form submission for booking a new appointment
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['book_appointment'])) {
    $clientName = $_POST['clientName'];
    $petName = $_POST['petName'];
    $appointmentDate = $_POST['appointmentDate'];
    $appointmentTime = $_POST['appointmentTime'];

    // Insert the appointment into the database
    $sql = "INSERT INTO appointments (client_name, pet_name, appointment_date, appointment_time) 
            VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $clientName, $petName, $appointmentDate, $appointmentTime);
    $stmt->execute();
    $stmt->close();
}

// Handle appointment deletion
if (isset($_GET['delete'])) {
    $appointmentId = $_GET['delete'];

    // Prepare the SQL query to delete the appointment
    $sql = "DELETE FROM appointments WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $appointmentId);
    
    if ($stmt->execute()) {
        // Redirect back to the same page after deletion
        header("Location: admin_dashboard.php");
        exit();
    } else {
        echo "Error deleting appointment: " . $stmt->error;
    }
    $stmt->close();
}

// Handle appointment editing
if (isset($_GET['edit'])) {
    $appointmentId = $_GET['edit'];

    // Fetch the appointment details from the database
    $sql = "SELECT * FROM appointments WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $appointmentId);
    $stmt->execute();
    $result = $stmt->get_result();
    $appointment = $result->fetch_assoc();

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_appointment'])) {
        $clientName = $_POST['clientName'];
        $petName = $_POST['petName'];
        $appointmentDate = $_POST['appointmentDate'];
        $appointmentTime = $_POST['appointmentTime'];

        // Update the appointment in the database
        $sql = "UPDATE appointments SET client_name = ?, pet_name = ?, appointment_date = ?, appointment_time = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssi", $clientName, $petName, $appointmentDate, $appointmentTime, $appointmentId);

        if ($stmt->execute()) {
            // Redirect back to the appointment overview after update
            header("Location: admin_dashboard.php");
            exit();
        } else {
            echo "Error updating appointment: " . $stmt->error;
        }
        $stmt->close();
    }
}

// Fetch all appointments from the database
$query = "SELECT * FROM appointments";
$result = $conn->query($query);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointments</title>
        <style>
            /* Your CSS code here (same as provided) */
    * {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
    }

    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: #fafafa;
        color: #333;
        display: flex;
        flex-direction: column;
        min-height: 100vh;
    }

    .header {
        text-align: center;
        margin-bottom: 20px;
        background-color: #4CAF50;
        padding: 20px;
        color: #fff;
    }

    .header h1 {
        font-size: 36px;
        font-weight: bold;
    }

    .form-container {
        width: 100%;
        max-width: 700px;
        background-color: #fff;
        padding: 20px;
        margin: 0 auto 30px;
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .form-container h2 {
        margin-bottom: 15px;
        font-size: 24px;
        color: #333;
    }

    .form-group {
        margin-bottom: 15px;
    }

    .form-group label {
        font-weight: 600;
        color: #444;
        margin-bottom: 5px;
        display: block;
    }

    .form-group input {
        width: 100%;
        padding: 10px;
        font-size: 16px;
        border: 1px solid #ccc;
        border-radius: 5px;
        background-color: #f9f9f9;
        color: #333;
    }

    .form-group input:focus {
        border-color: #4CAF50;
        outline: none;
    }

    .submit-btn {
        background-color: #4CAF50;
        color: white;
        border: none;
        padding: 12px;
        font-size: 16px;
        cursor: pointer;
        width: 100%;
        border-radius: 5px;
        transition: background-color 0.3s ease;
    }

    .submit-btn:hover {
        background-color: #45a049;
    }

    .overview-section {
        width: 100%;
        max-width: 900px;
        background-color: #fff;
        padding: 20px;
        border-radius: 8px;
        margin: 0 auto;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .overview-section h2 {
        font-size: 24px;
        color: #333;
        margin-bottom: 15px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    table thead {
        background-color: #4CAF50;
        color: white;
    }

    table th, table td {
        padding: 15px;
        text-align: left;
        border-bottom: 1px solid #ddd;
    }

    table tbody tr:nth-child(even) {
        background-color: #f9f9f9;
    }

    table tbody tr:hover {
        background-color: #f1f1f1;
    }

    .edit-btn, .delete-btn {
        color: #fff;
        padding: 6px 12px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s;
    }

    .edit-btn {
        background-color: #007bff;
    }

    .edit-btn:hover {
        background-color: #0056b3;
    }

    .delete-btn {
        background-color: #dc3545;
    }

    .delete-btn:hover {
        background-color: #c82333;
    }

    .form-container input[type="submit"], .form-container input[type="button"] {
        margin-top: 10px;
    }

    /* Responsive Styles */
    @media (max-width: 768px) {
        .header h1 {
            font-size: 28px;
        }

        .form-container, .overview-section {
            width: 90%;
            padding: 15px;
        }

        .form-group input {
            padding: 8px;
            font-size: 14px;
        }
    }
</style>
</head>
<body>

<div class="main-content">
    <div class="header">
        <h1>Manage Appointments</h1>
    </div>

    <!-- Appointment Form -->
    <section class="form-container" id="appointmentForm">
        <h2>Book New Appointment</h2>
        <form action="appointments.php" method="POST">
            <div class="form-group">
                <label for="clientName">Client Name</label>
                <input type="text" id="clientName" name="clientName" required>
            </div>

            <div class="form-group">
                <label for="petName">Pet Name</label>
                <input type="text" id="petName" name="petName" required>
            </div>

            <div class="form-group">
                <label for="appointmentDate">Date</label>
                <input type="date" id="appointmentDate" name="appointmentDate" required>
            </div>

            <div class="form-group">
                <label for="appointmentTime">Time</label>
                <input type="time" id="appointmentTime" name="appointmentTime" required>
            </div>

            <div class="form-group">
                <input type="submit" value="Book Appointment" class="submit-btn" name="book_appointment">
            </div>
        </form>
    </section>

    <!-- Appointment Overview Table -->
    <section class="overview-section">
        <h2>Upcoming Appointments</h2>
        <table>
            <thead>
                <tr>
                    <th>Client Name</th>
                    <th>Pet Name</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['client_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['pet_name']); ?></td>
                        <td><?php echo $row['appointment_date']; ?></td>
                        <td><?php echo $row['appointment_time']; ?></td>
                        <td>Confirmed</td>
                        <td>
                            <a href="appointments.php?edit=<?php echo $row['id']; ?>" class="edit-btn">Edit</a>
                            <a href="appointments.php?delete=<?php echo $row['id']; ?>" class="delete-btn">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </section>
</div>

<?php if (isset($appointment)): ?>
    <!-- Edit Appointment Form -->
    <section class="form-container">
        <h2>Edit Appointment</h2>
        <form action="appointments.php?edit=<?php echo $appointment['id']; ?>" method="POST">
            <div class="form-group">
                <label for="clientName">Client Name</label>
                <input type="text" id="clientName" name="clientName" value="<?php echo htmlspecialchars($appointment['client_name']); ?>" required>
            </div>

            <div class="form-group">
                <label for="petName">Pet Name</label>
                <input type="text" id="petName" name="petName" value="<?php echo htmlspecialchars($appointment['pet_name']); ?>" required>
            </div>

            <div class="form-group">
                <label for="appointmentDate">Date</label>
                <input type="date" id="appointmentDate" name="appointmentDate" value="<?php echo $appointment['appointment_date']; ?>" required>
            </div>

            <div class="form-group">
                <label for="appointmentTime">Time</label>
                <input type="time" id="appointmentTime" name="appointmentTime" value="<?php echo $appointment['appointment_time']; ?>" required>
            </div>

            <div class="form-group">
                <input type="submit" value="Update Appointment" class="submit-btn" name="update_appointment">
            </div>
        </form>
    </section>
<?php endif; ?>

</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
