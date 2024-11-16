<?php
include('config.php');
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

$userId = $_SESSION['username'];  // Get logged-in user ID

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['process_payment'])) {
        $amount = $_POST['amount'];
        $paymentDate = $_POST['payment_date'];
        $status = $_POST['status'];

        // Validate the input
        if (empty($amount) || empty($paymentDate)) {
            $message = "Amount and payment date are required.";
        } elseif ($amount <= 0) {
            $message = "Amount must be greater than zero.";
        } else {
            // Use logged-in user's ID for client_id
            $sql = "INSERT INTO payments (client_id, amount, payment_date, status) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);

            if ($stmt === false) {
                // Output the error if the prepare() function fails
                die("Error preparing query: " . $conn->error);
            } else {
                $stmt->bind_param("idsi", $userId, $amount, $paymentDate, $status);
                if ($stmt->execute()) {
                    $message = "Payment processed successfully.";
                } else {
                    $message = "Error processing payment: " . $stmt->error;
                }
                $stmt->close();
            }
        }

        // Redirect with the message
        header("Location: " . $_SERVER['PHP_SELF'] . "?message=" . urlencode($message));
        exit();
    }
}

// Query to fetch payment overview
$query = "SELECT u.id, u.firstname, u.surname, 
                 IFNULL(SUM(p.amount), 0) AS total_payments,
                 IF(SUM(p.amount) IS NULL OR SUM(p.amount) < 100, 'Pending', 'Completed') AS payment_status
          FROM users u 
          LEFT JOIN payments p ON u.id = p.client_id 
          WHERE u.id = ? 
          GROUP BY u.id";

$stmt = $conn->prepare($query);

if ($stmt === false) {
    die("Error preparing query for overview: " . $conn->error);
}

$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payments</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
        }

        .container {
            margin-top: 30px;
        }

        .alert {
            background-color: #28a745;
            color: #fff;
        }

        .alert .close {
            color: #fff;
        }
        .main-content { 
             padding: 10px 2px; 
             display: flex;
             flex-direction: column; 
             align-items: center; 
            } 
        .form-container {
            margin-bottom: 30px;
        }

        .form-container h2 {
            color: #333;
            font-size: 24px;
            margin-bottom: 20px;
        }

        .form-group label {
            color: #555;
        }

        .form-control {
            border-radius: 5px;
            border: 1px solid #ddd;
            padding: 10px;
            font-size: 16px;
            margin-bottom: 15px;
        }

        .form-control:focus {
            border-color: #007bff;
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
        }

        .submit-btn {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 12px;
            font-size: 16px;
            cursor: pointer;
            width: 100%;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .submit-btn:hover {
            background-color: #0056b3;
        }

        .table-hover tbody tr:hover {
            background-color: #f0f0f0;
        }

        .table {
            margin-top: 30px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        th {
            background-color: green;
            color: white;
        }

        td, th {
            padding: 12px;
            text-align: left;
        }

        .btn {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .btn:hover {
            background-color: #218838;
        }
        label{
            color: white;
        }
    </style>
</head>
<body>
   <div class="main-content">
    <div class="container">
        <div class="dashboard">
            <h1 class="text-center mb-4"></h1>

            <?php if (isset($_GET['message'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?php echo htmlspecialchars($_GET['message']); ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php endif; ?>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-container">
                        <h2>Process Payment</h2>
                        <form action="" method="POST">
                            <div class="form-group">
                                <label for="amount">Amount:</label>
                                <input type="number" class="form-control" id="amount" name="amount" required min="0.01">
                            </div>
                            <div class="form-group">
                                <label for="payment_date">Payment Date:</label>
                                <input type="date" class="form-control" id="payment_date" name="payment_date" required>
                            </div>
                            <div class="form-group">
                                <label for="status">Payment Status:</label>
                                <select name="status" id="status" class="form-control">
                                    <option value="1">Completed</option>
                                    <option value="0">Pending</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <button type="submit" name="process_payment" class="submit-btn">Process Payment</button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="col-md-6">
                    <h2>Payment Overview</h2>
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Client Name</th>
                                <th>Total Payments</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $result->fetch_assoc()) { ?>
                                <tr>
                                    <td><?php echo $row['firstname'] . ' ' . $row['surname']; ?></td>
                                    <td><?php echo number_format($row['total_payments'], 2); ?></td>
                                    <td><?php echo $row['payment_status']; ?></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
   </div>
    
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
