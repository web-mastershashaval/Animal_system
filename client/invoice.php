<?php
include('config.php');

session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$userId = $_SESSION['user_id'];  // Get logged-in user ID

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $clientId = $_POST['client_id'];
    $invoiceDate = $_POST['invoice_date'];
    $dueDate = $_POST['due_date'];
    $totalAmount = $_POST['total_amount'];

    $invoiceNumber = 'INV-' . date('Ymd') . '-' . rand(1000, 9999); 

    $sql = "INSERT INTO invoices (invoice_number, client_id, invoice_date, due_date, total_amount, status) 
            VALUES (?, ?, ?, ?, ?, 'Pending')";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sisds", $invoiceNumber, $clientId, $invoiceDate, $dueDate, $totalAmount);

    if ($stmt->execute()) {
        header("Location: view_invoice.php?invoice_number=" . $invoiceNumber . "&message=Invoice created successfully");
        exit();
    } else {
        echo "Error creating invoice: " . $stmt->error;
    }
    $stmt->close();
}

$clientQuery = "SELECT id, first_name, last_name FROM users";
$clientResult = $conn->query($clientQuery);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <title>Create Invoice</title>
</head>
<body>
    <div class="container">
        <h1 class="text-center mb-4">Create Invoice</h1>
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">New Invoice</div>
                    <div class="card-body">
                        <form action="" method="POST">
                            <div class="form-group">
                                <label for="client_id">Client:</label>
                                <select name="client_id" id="client_id" class="form-control" required>
                                    <option value="">Select Client</option>
                                    <?php while ($client = $clientResult->fetch_assoc()): ?>
                                        <option value="<?php echo $client['id']; ?>">
                                            <?php echo $client['first_name'] . ' ' . $client['last_name']; ?>
                                        </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="invoice_date">Invoice Date:</label>
                                <input type="date" class="form-control" id="invoice_date" name="invoice_date" value="<?php echo date('Y-m-d'); ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="due_date">Due Date:</label>
                                <input type="date" class="form-control" id="due_date" name="due_date" required>
                            </div>
                            <div class="form-group">
                                <label for="total_amount">Total