<?php
session_start();
include('config.php');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'User not logged in']);
    exit;
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // If it's a GET request, load appointments or specific appointment details
    if (isset($_GET['id'])) {
        // Fetch a specific appointment for the logged-in user
        $appointment_id = $_GET['id'];
        $sql = "SELECT * FROM appointments WHERE id = ? AND client_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ii', $appointment_id, $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            echo json_encode($result->fetch_assoc());
        } else {
            echo json_encode(['error' => 'Appointment not found']);
        }
    } else {
        // Fetch all appointments for the logged-in user
        $sql = "SELECT * FROM appointments WHERE client_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        $appointments = [];
        while ($row = $result->fetch_assoc()) {
            $appointments[] = $row;
        }

        echo json_encode(['appointments' => $appointments]);
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // If it's a POST request, handle booking, editing, or deleting an appointment

    if (isset($_POST['appointmentId']) && !empty($_POST['appointmentId'])) {
        // Edit an existing appointment
        $appointment_id = $_POST['appointmentId'];
        $pet_name = $_POST['petName'];
        $appointment_date = $_POST['appointmentDate'];
        $appointment_time = $_POST['appointmentTime'];

        $sql = "UPDATE appointments SET pet_name = ?, appointment_date = ?, appointment_time = ? WHERE id = ? AND client_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ssiii', $pet_name, $appointment_date, $appointment_time, $appointment_id, $user_id);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            echo json_encode(['success' => true, 'message' => 'Appointment updated']);
        } else {
            echo json_encode(['error' => 'Failed to update appointment']);
        }
    } else {
        // Book a new appointment
        $client_name = $_POST['clientName'];
        $pet_name = $_POST['petName'];
        $appointment_date = $_POST['appointmentDate'];
        $appointment_time = $_POST['appointmentTime'];

        $sql = "INSERT INTO appointments (client_id, client_name, pet_name, appointment_date, appointment_time) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('issss', $user_id, $client_name, $pet_name, $appointment_date, $appointment_time);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            echo json_encode(['success' => true, 'message' => 'Appointment booked']);
        } else {
            echo json_encode(['error' => 'Failed to book appointment']);
        }
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // If it's a DELETE request, delete an appointment
    if (isset($_GET['id'])) {
        $appointment_id = $_GET['id'];

        $sql = "DELETE FROM appointments WHERE id = ? AND client_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ii', $appointment_id, $user_id);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            echo json_encode(['success' => true, 'message' => 'Appointment deleted']);
        } else {
            echo json_encode(['error' => 'Failed to delete appointment']);
        }
    } else {
        echo json_encode(['error' => 'No appointment ID provided']);
    }
}
?>
