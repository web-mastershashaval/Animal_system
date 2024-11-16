<?php
// Database connection
include('config.php');
session_start();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['process_payment'])) {
        // Process Payment
        $client_id = $_POST['client_name'];
        $amount = $_POST['amount'];
        $paymentDate = $_POST['payment_date'];
        $status = $_POST['status'];

        if (empty($client_id) || empty($amount) || empty($paymentDate)) {
            $message = "All fields are required.";
        } elseif ($amount <= 0) {
            $message = "Amount must be greater than zero.";
        } else {
            $sql = "INSERT INTO payments (client_id, amount, payment_date, status) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            if ($stmt === false) {
                $message = "Error preparing query: " . $conn->error;
            } else {
                $stmt->bind_param("idis", $client_id, $amount, $paymentDate, $status);
                if ($stmt->execute()) {
                    $message = "Payment processed successfully";
                } else {
                    $message = "Error processing payment: " . $stmt->error;
                }
                $stmt->close();
            }
        }
        header("Location: " . $_SERVER['PHP_SELF'] . "?message=" . urlencode($message));
        exit();
    }

    if (isset($_POST['delete_payment'])) {
        // Delete Payment
        $paymentId = $_POST['payment_id'];
        $sql = "DELETE FROM payments WHERE id = ?";
        $stmt = $conn->prepare($sql);
        if ($stmt === false) {
            $message = "Error preparing delete query: " . $conn->error;
        } else {
            $stmt->bind_param("i", $paymentId);
            if ($stmt->execute()) {
                $message = "Payment deleted successfully";
            } else {
                $message = "Error deleting payment: " . $stmt->error;
            }
            $stmt->close();
        }
        header("Location: " . $_SERVER['PHP_SELF'] . "?message=" . urlencode($message));
        exit();
    }
}

// Fetch user payments overview
$query = "SELECT u.id, u.firstname, u.surname, 
                 IFNULL(SUM(p.amount), 0) AS total_payments,
                 IF(SUM(p.amount) IS NULL OR SUM(p.amount) < 100, 'Pending', 'Completed') AS payment_status
          FROM users u 
          LEFT JOIN payments p ON u.id = p.client_id 
          GROUP BY u.id";
$result = $conn->query($query);

if (!$result) {
    die("Error executing query: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payments</title>
    <style>
        /* General Styling */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f7fa;
            margin: 0;
            padding: 0;
        }

        .main-container {
            display: flex;
            justify-content: space-between;
            padding: 30px;
            box-sizing: border-box;
        }

        .alert {
            background-color: #28a745;
            color: #fff;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            width: 100%;
        }

        .alert .close {
            color: #fff;
        }

        .form-container {
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 500px;
        }

        .form-container h2 {
            color: #333;
            font-size: 24px;
            margin-bottom: 20px;
            text-align: center;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            color: #555;
            font-weight: 600;
            font-size: 14px;
            display: block;
        }

        .form-control {
            width: 100%;
            padding: 12px;
            font-size: 16px;
            border-radius: 5px;
            border: 1px solid #ddd;
            box-sizing: border-box;
            transition: border-color 0.3s ease;
        }

        .form-control:focus {
            border-color: #007bff;
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
        }

        .submit-btn {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 15px;
            font-size: 16px;
            cursor: pointer;
            width: 100%;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .submit-btn:hover {
            background-color: #0056b3;
        }

        .table {
            margin-top: 30px;
            width: 100%;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .table th, .table td {
            padding: 12px;
            text-align: left;
            font-size: 16px;
        }

        .table th {
            background-color: #28a745;
            color: white;
        }

        .table tr:hover {
            background-color: #f0f0f0;
        }

        .btn {
            background-color: #dc3545;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .btn:hover {
            background-color: #c82333;
        }

        .container {
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
        }

        .row {
    display: flex;
    justify-content: space-between;
    gap: 20px; /* Adds spacing between the two sections */
}

.col-md-6 {
    flex: 1; /* Makes both sections equal in width */
    max-width: 45%; /* Limits the width of the sections */
}

.payment-overview {
    margin-left: auto; /* Pushes the Payment Overview to the far right */
}

        .dashboard h1 {
            font-size: 28px;
            color: #333;
            margin-bottom: 10px;
            text-align: center;
        }
    </style>
</head>
<body>

<div class="main-container">
    <div class="container">
        <div class="dashboard">
            <!-- Success Message -->
            <?php if (isset($_GET['message'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?php echo htmlspecialchars($_GET['message']); ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php endif; ?>

            <!-- Row to display Payment Form and Overview -->
            <div class="row">

                <!-- Payment Form Section -->
                <div class="col-md-6">
                    <div class="form-container">
                        <h2>Process Payment</h2>
                        <form action="" method="POST">
                            <div class="form-group">
                                <label for="client_name">Client Name:</label>
                                <input type="text" class="form-control" id="client_name" name="client_name" required>
                            </div>
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

                <!-- Payment Overview Section -->
                <div class="col-md-6">
                    <h2>Payment Overview</h2>
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Client Name</th>
                                <th>Total Payments</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $result->fetch_assoc()) { ?>
                                <tr>
                                    <td><?php echo $row['firstname'] . ' ' . $row['surname']; ?></td>
                                    <td><?php echo number_format($row['total_payments'], 2); ?></td>
                                    <td><?php echo $row['payment_status']; ?></td>
                                    <td>
                                        <form method="POST" action="" style="display:inline;">
                                            <input type="hidden" name="payment_id" value="<?php echo $row['id']; ?>">
                                            <button type="submit" name="delete_payment" class="btn">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>

</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
