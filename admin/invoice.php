<?php
include('config.php');
session_start();

// Include libraries for PDF generation
require('fpdf.php'); // Ensure you have FPDF installed for PDF generation

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $clientId = $_POST['client_id'];
    $invoiceDate = $_POST['invoice_date'];
    $dueDate = $_POST['due_date'];
    $downloadFormat = $_POST['download_format']; // Get the selected download format

    // Fetch the total payments for the selected client
    $paymentQuery = "SELECT SUM(amount) AS total_payments FROM payments WHERE client_id = ?";
    $stmt = $conn->prepare($paymentQuery);
    $stmt->bind_param("i", $clientId);
    $stmt->execute();
    $stmt->bind_result($totalAmount);
    $stmt->fetch();
    $stmt->close();

    // If no payments found, set total amount to 0
    $totalAmount = $totalAmount ?? 0;

    // Generate invoice number
    $invoiceNumber = 'INV-' . date('Ymd') . '-' . rand(1000, 9999); 

    // Insert the new invoice record
    $sql = "INSERT INTO invoices (invoice_number, client_id, invoice_date, due_date, total_amount, status) 
            VALUES (?, ?, ?, ?, ?, 'Pending')";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sisds", $invoiceNumber, $clientId, $invoiceDate, $dueDate, $totalAmount);

    if ($stmt->execute()) {
        // Handle download format after invoice is created
        if ($downloadFormat === 'pdf') {
            generatePDF($invoiceNumber, $clientId, $invoiceDate, $dueDate, $totalAmount);
        } elseif ($downloadFormat === 'csv') {
            generateCSV($invoiceNumber, $clientId, $invoiceDate, $dueDate, $totalAmount);
        }
        exit();
    } else {
        echo "Error creating invoice: " . $stmt->error;
    }
    $stmt->close();
}

// Fetch clients for the dropdown
$clientQuery = "SELECT id, firstname, surname FROM users";
$clientResult = $conn->query($clientQuery);

// Function to generate PDF
function generatePDF($invoiceNumber, $clientId, $invoiceDate, $dueDate, $totalAmount) {
    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial', 'B', 12);

    $pdf->Cell(0, 10, 'Invoice Number: ' . $invoiceNumber, 0, 1, 'C');
    $pdf->Cell(0, 10, 'Client ID: ' . $clientId, 0, 1, 'C');
    $pdf->Cell(0, 10, 'Invoice Date: ' . $invoiceDate, 0, 1, 'C');
    $pdf->Cell(0, 10, 'Due Date: ' . $dueDate, 0, 1, 'C');
    $pdf->Cell(0, 10, 'Total Amount: $' . number_format($totalAmount, 2), 0, 1, 'C');

    $pdf->Output('D', 'Invoice_' . $invoiceNumber . '.pdf');
}

// Function to generate CSV
function generateCSV($invoiceNumber, $clientId, $invoiceDate, $dueDate, $totalAmount) {
    $filename = 'Invoice_' . $invoiceNumber . '.csv';
    $file = fopen('php://output', 'w');

    // Output headers for CSV file
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="' . $filename . '"');

    // Add the CSV columns
    fputcsv($file, ['Invoice Number', 'Client ID', 'Invoice Date', 'Due Date', 'Total Amount']);
    fputcsv($file, [$invoiceNumber, $clientId, $invoiceDate, $dueDate, number_format($totalAmount, 2)]);

    fclose($file);
}

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
                                            <?php echo $client['firstname'] . ' ' . $client['surname']; ?>
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
                                <label for="download_format">Download Format:</label>
                                <select name="download_format" id="download_format" class="form-control" required>
                                    <option value="pdf">PDF</option>
                                    <option value="csv">CSV</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary btn-block">Generate Invoice</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
