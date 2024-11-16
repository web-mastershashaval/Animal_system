<?php 
include('config.php');

session_start();

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

$userId = $_SESSION['username'];  // Get logged-in user ID

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $feedbackText = $_POST['feedback_text'];

    // Insert feedback into the database with the logged-in user's ID
    $sql = "INSERT INTO feedback (client_id, feedback_text) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    
    if ($stmt === false) {
        die("Error preparing statement: " . $conn->error);
    }

    $stmt->bind_param("is", $userId, $feedbackText);  // Use logged-in user's ID for client_id
    $stmt->execute();
    $stmt->close();

    header("Location: " . $_SERVER['PHP_SELF'] . "?message=Feedback submitted successfully");
    exit();
}

// Query to fetch feedback only for the logged-in user
$query = "SELECT f.id, u.firstname, u.surname, f.feedback_text, f.created_at 
          FROM feedback f 
          JOIN users u ON f.client_id = u.id 
          WHERE f.client_id = ?  -- Only get feedback for the logged-in user
          ORDER BY f.created_at DESC";

$stmt = $conn->prepare($query);
if ($stmt === false) {
    die("Error preparing query: " . $conn->error);
}

$stmt->bind_param("i", $userId);  // Bind the logged-in user's ID
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <title>Client Feedback Dashboard</title>
    <style>
        body {
            background-color: #f4f7fc;
            font-family: 'Arial', sans-serif;
        }

        .dashboard {
            padding: 30px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: #333;
        }

        .alert {
            margin-top: 20px;
        }

        .form-container {
            background-color: #f9f9f9;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .form-group label {
            font-weight: bold;
        }

        .form-control {
            border-radius: 5px;
            box-shadow: none;
            border: 1px solid #ddd;
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
            padding: 10px 20px;
            font-size: 16px;
            width: 100%;
            border-radius: 5px;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }

        .table th, .table td {
            text-align: center;
        }

        .table-hover tbody tr:hover {
            background-color: rgba(0, 123, 255, 0.1);
        }

        .overview-section h2 {
            margin-top: 30px;
            color: #333;
        }

        .table-bordered {
            border: 1px solid #ddd;
        }

        .thead-dark {
            background-color: #343a40;
            color: white;
        }
    </style>
</head>
<body>
<div class="container dashboard">
    <h1 class="text-center">Client Feedback Dashboard</h1>

    <?php if(isset($_GET['message'])): ?>
        <div class="alert alert-success">
            <?php echo htmlspecialchars($_GET['message']); ?>
        </div>
    <?php endif; ?>

    <div class="form-container mb-4">
        <h2>Submit Feedback</h2>
        <form action="" method="POST">
            <div class="form-group">
                <label for="feedback_text">Feedback:</label>
                <textarea class="form-control" id="feedback_text" name="feedback_text" rows="4" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Submit Feedback</button>
        </form>
    </div>

    <div class="overview-section">
        <h2>Your Feedback</h2>
        <table class="table table-bordered table-hover">
            <thead class="thead-dark">
                <tr>
                    <th>Feedback ID</th>
                    <th>Client Name</th>
                    <th>Feedback</th>
                    <th>Date Submitted</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['id']); ?></td>
                            <td><?php echo htmlspecialchars($row['firstname'] . ' ' . $row['surname']); ?></td>
                            <td><?php echo htmlspecialchars($row['feedback_text']); ?></td>
                            <td><?php echo htmlspecialchars(date("Y-m-d H:i:s", strtotime($row['created_at']))); ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" class="text-center">No feedback available.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php 
$conn->close(); 
?>
</body>
</html>
