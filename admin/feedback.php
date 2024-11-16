<?php 
include('config.php');
session_start();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $clientId = $_POST['client_id'];
    $feedbackText = $_POST['feedback_text'];

    // Insert feedback into the database
    $sql = "INSERT INTO feedback (client_id, feedback_text) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $clientId, $feedbackText);
    $stmt->execute();
    $stmt->close();

    header("Location: " . $_SERVER['PHP_SELF'] . "?message=Feedback submitted successfully");
    exit();
}

// Fetch existing feedback with client names
$query = "SELECT u.first_name, u.last_name, f.feedback_text, f.created_at 
          FROM feedback f 
          JOIN users u ON f.client_id = u.id 
          ORDER BY f.created_at DESC";
$result = $conn->query($query);

// Fetch clients for the dropdown
$clientsQuery = "SELECT id, first_name, last_name FROM users";
$clientsResult = $conn->query($clientsQuery);
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
            background: linear-gradient(to right, #6a11cb, #2575fc);
            color: #fff;
            font-family: Arial, sans-serif;
        }
      
        .form-container h2 {
            color: #f39c12;
        }
        .btn-primary {
            background-color: #2575fc;
            border-color: #2575fc;
        }
        .btn-primary:hover {
            background-color: #6a11cb;
            border-color: #6a11cb;
        }
        .table thead {
            background-color: #f39c12;
        }
        .table-hover tbody tr:hover {
            background-color: rgba(255, 255, 255, 0.2);
        }
    </style>
</head>
<body>
<div class="container dashboard">
    <h1 class="text-center mb-4">Client Feedback</h1>

    <?php if(isset($_GET['message'])): ?>
        <div class="alert alert-success">
            <?php echo htmlspecialchars($_GET['message']); ?>
        </div>
    <?php endif; ?>

    <!-- Feedback Submission Form -->
    <div class="form-container mb-4">
        <h2>Submit Feedback</h2>
        <form action="" method="POST">
            <div class="form-group">
                <label for="client_id">Client Name:</label>
                <select class="form-control" id="client_id" name="client_id" required>
                    <option value="">-- Select Client --</option>
                    <?php if ($clientsResult->num_rows > 0): ?>
                        <?php while ($client = $clientsResult->fetch_assoc()): ?>
                            <option value="<?php echo $client['id']; ?>">
                                <?php echo htmlspecialchars($client['first_name'] . ' ' . $client['last_name']); ?>
                            </option>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <option value="">No clients available</option>
                    <?php endif; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="feedback_text">Feedback:</label>
                <textarea class="form-control" id="feedback_text" name="feedback_text" rows="4" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary" name="submit_feedback">Submit Feedback</button>
        </form>
    </div>

    <!-- Existing Feedback -->
    <div class="overview-section">
        <h2>Existing Feedback</h2>
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>Client Name</th>
                    <th>Feedback</th>
                    <th>Date Submitted</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['first_name'] . ' ' . $row['last_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['feedback_text']); ?></td>
                            <td><?php echo htmlspecialchars(date("Y-m-d H:i:s", strtotime($row['created_at']))); ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3" class="text-center">No feedback available.</td>
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
