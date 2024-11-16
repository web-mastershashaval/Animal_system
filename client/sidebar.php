<?php include('config.php');?>
<!DOCTYPE html>
<html lang="en">

        <nav class="sidebar">
            <ul>
                <li><a href="home.php" class="sidebar-link" data-target="overview.php">Dashboard</a></li>
                <li><a href="appointments.php" class="sidebar-link" data-target="appointments.php">Book Appointment</a></li>
                <li><a href="client.php" class="sidebar-link" data-target="client.php">Client</a></li>
                <li><a href="petinfo.php" class="sidebar-link" data-target="petinfo.php">Pet Information</a></li>
                <li><a href="payments.php" class="sidebar-link" data-target="managedebt.php">Payments</a></li>
                <li><a href="feedback.php" class="sidebar-link" data-target="feedback.php">Feedback</a></li>
                <li><a href="animalproducts.php" class="sidebar-link" data-target="billing.php">Animal Products</a></li>
                <li><a href="view_invoices.php" class="sidebar-link" data-target="view_invoices.php">Invoices</a></li>
                <li><a href="./login.html" class="sidebar-link" id="logout-btn" onclick="confirmLogout(event)">Log Out</a></li>
            </ul>
        </nav>


        <style>
        /* General Reset */
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; color: #333; display: flex; min-height: 100vh; flex-direction: column; }

        /* Main content styling */
        .main-content { padding: 40px; display: flex; flex-direction: column; align-items: center; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h1 { font-size: 28px;  }

        /* Form container styling */
        .form-container, .overview-section { width: 100%; max-width: 800px; background-color: #fff; padding: 20px; margin-bottom: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); display: none; }
        .form-group { margin-bottom: 15px; }
        .form-group label { color: #666; margin-bottom: 5px; font-weight: bold; }
        .form-group input[type="text"], .form-group input[type="number"] { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px; }
        .submit-btn { background-color: green; color: #fff; padding: 10px; font-size: 16px; cursor: pointer; width: 100%; border-radius: 5px; }

        /* Table styling */
        table { width: 100%; border-collapse: collapse; }
        table thead { background-color: #333; color: #fff; }
        table th, table td { padding: 10px; text-align: left; border-bottom: 1px solid #ddd; }
        table tbody tr:hover { background-color: #f1f1f1; }

        /* Toggle Button Styling */
        .toggle-btn { background-color: #007bff; color: #fff; padding: 10px; font-size: 16px; cursor: pointer; border: none; border-radius: 5px; margin-bottom: 10px; }
    </style>
        // Toggle the visibility of different sections
    function toggleSection(sectionId) {
        const sections = document.querySelectorAll('.form-container, .overview-section');
        sections.forEach(section => section.style.display = 'none');
        const activeSection = document.getElementById(sectionId);
        if (activeSection) {
            activeSection.style.display = 'block';
        } else {
            console.error("Section with ID " + sectionId + " not found.");
        }
    }

    // Populate the update form with the selected pet's details
    function populateUpdateForm(petId, petName, ownerName, petType, petBreed, petAge) {
        document.getElementById('pet_id').value = petId;
        document.getElementById('pet_name_update').value = petName;
        document.getElementById('owner_name_update').value = ownerName;
        document.getElementById('pet_type_update').value = petType;
        document.getElementById('pet_breed_update').value = petBreed;
        document.getElementById('pet_age_update').value = petAge;
        toggleSection('updatePetSection');
    }

        <?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include('config.php');

session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$userId = $_SESSION['user_id'];  // Get logged-in user ID
function getAppointments($conn, $userId) {
    $sql = "SELECT * FROM appointments WHERE user_id = ? ORDER BY appointment_date, appointment_time";
    $stmt = $conn->prepare($sql);

    // Check if statement preparation was successful
    if (!$stmt) {
        die("SQL Error: " . $conn->error);
    }

    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $appointments = [];
    
    while ($row = $result->fetch_assoc()) {
        $appointments[] = $row;
    }

    $stmt->close();
    return $appointments;
}


function saveAppointment($conn, $id, $userId, $clientName, $petName, $appointmentDate, $appointmentTime) {
    if ($id) {
        $sql = "UPDATE appointments SET client_name=?, pet_name=?, appointment_date=?, appointment_time=? WHERE id=? AND user_id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssii", $clientName, $petName, $appointmentDate, $appointmentTime, $id, $userId);
    } else {
        $sql = "INSERT INTO appointments (user_id, client_name, pet_name, appointment_date, appointment_time) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("issss", $userId, $clientName, $petName, $appointmentDate, $appointmentTime);
    }

    $stmt->execute();
    $stmt->close();
}

function deleteAppointment($conn, $id, $userId) {
    $sql = "DELETE FROM appointments WHERE id=? AND user_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $id, $userId);
    $stmt->execute();
    $stmt->close();
}

// Handle POST requests (Add or Edit Appointment)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $clientName = $_POST['clientName'];
    $petName = $_POST['petName'];
    $appointmentDate = $_POST['appointmentDate'];
    $appointmentTime = $_POST['appointmentTime'];
    $appointmentId = $_POST['appointmentId'];

    saveAppointment($conn, $appointmentId, $userId, $clientName, $petName, $appointmentDate, $appointmentTime);

    header('Location: ' . $_SERVER['PHP_SELF']);
    exit();
}

if (isset($_GET['delete'])) {
    $appointmentId = $_GET['delete'];
    deleteAppointment($conn, $appointmentId, $userId);

    header('Location: ' . $_SERVER['PHP_SELF']);
    exit();
}

$appointments = getAppointments($conn, $userId);

$appointmentToEdit = null;
if (isset($_GET['edit'])) {
    $appointmentId = $_GET['edit'];
    $stmt = $conn->prepare("SELECT * FROM appointments WHERE id=? AND user_id=?");
    $stmt->bind_param("ii", $appointmentId, $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $appointmentToEdit = $result->fetch_assoc();
    $stmt->close();
}
?>

<!-- Rest of your HTML code remains unchanged -->


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Appointments</title>
    <style>
        /* Basic styles */
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; color: #333; display: flex; min-height: 100vh; flex-direction: column; }
        .main-content { padding: 40px; display: flex; flex-direction: column; align-items: center; }
        .header { text-align: center; margin-bottom: 30px; }
        .header h1 { font-size: 28px; color: white; }
        
        /* Form and overview section */
        .form-container, .overview-section { width: 100%; max-width: 600px; background-color: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); margin-bottom: 20px; }
        
        /* Form styles */
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; color: #666; margin-bottom: 5px; font-weight: bold; }
        .form-group input[type="text"], .form-group input[type="date"], .form-group input[type="time"] { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px; font-size: 14px; }
        .submit-btn { background-color: green; color: #fff; border: none; padding: 12px; font-size: 16px; cursor: pointer; width: 100%; border-radius: 5px; transition: background-color 0.3s; }
        .submit-btn:hover { background-color: #555; }
        
        /* Table styles */
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        table thead { background-color: green; color: #fff; }
        table th, table td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
        table tbody tr:nth-child(even) { background-color: #f9f9f9; }
        
        /* Toggle button */
        .toggle-btn { background-color: #007bff; color: #fff; border: none; padding: 12px 20px; font-size: 16px; cursor: pointer; margin-bottom: 20px; border-radius: 5px; transition: background-color 0.3s; }
        .toggle-btn:hover { background-color: #0056b3; }
    </style>
</head>
<body>

<div class="main-content">
    <!-- Toggle Button -->
    <button class="toggle-btn" onclick="toggleForm()">Toggle Booking Form</button>

    <!-- Booking Form -->
    <section class="form-container" id="formContainer" style="display: none;">
        <h2><?= isset($appointmentToEdit) ? 'Edit Appointment' : 'Book Appointment' ?></h2>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
            <div class="form-group">
                <label for="clientName">Client Name</label>
                <input type="text" id="clientName" name="clientName" value="<?= $appointmentToEdit['client_name'] ?? '' ?>" required>
            </div>
            <div class="form-group">
                <label for="petName">Pet Name</label>
                <input type="text" id="petName" name="petName" value="<?= $appointmentToEdit['pet_name'] ?? '' ?>" required>
            </div>
            <div class="form-group">
                <label for="appointmentDate">Date</label>
                <input type="date" id="appointmentDate" name="appointmentDate" value="<?= $appointmentToEdit['appointment_date'] ?? '' ?>" required>
            </div>
            <div class="form-group">
                <label for="appointmentTime">Time</label>
                <input type="time" id="appointmentTime" name="appointmentTime" value="<?= $appointmentToEdit['appointment_time'] ?? '' ?>" required>
            </div>
            <input type="hidden" id="appointmentId" name="appointmentId" value="<?= $appointmentToEdit['id'] ?? '' ?>">
            <div class="form-group">
                <input type="submit" value="<?= isset($appointmentToEdit) ? 'Update Appointment' : 'Book Appointment' ?>" class="submit-btn">
            </div>
        </form>
    </section>

    <!-- Appointments Table -->
    <section class="overview-section">
        <h2>Upcoming Appointments</h2>
        <table>
            <thead>
                <tr>
                    <th>Pet Name</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($appointments as $appointment): ?>
                    <tr>
                        <td><?= htmlspecialchars($appointment['pet_name']) ?></td>
                        <td><?= htmlspecialchars($appointment['appointment_date']) ?></td>
                        <td><?= htmlspecialchars($appointment['appointment_time']) ?></td>
                        <td>
                            <a href="?edit=<?= $appointment['id'] ?>">Edit</a>
                            <a href="?delete=<?= $appointment['id'] ?>" onclick="return confirm('Are you sure you want to delete this appointment?')">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </section>
</div>

<script>
    // Function to toggle the appointment form
    function toggleForm() {
        const formContainer = document.getElementById('formContainer');
        formContainer.style.display = formContainer.style.display === 'none' ? 'block' : 'none';
    }
</script>

</body>
</html>


