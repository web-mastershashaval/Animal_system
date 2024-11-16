<?php
session_start();
include('config.php');

// Check if connection was successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to handle query execution with error reporting
function fetchQueryResult($conn, $query) {
    $result = $conn->query($query);
    if (!$result) {
        echo "Error executing query: " . $conn->error . "<br>";
        return false;
    }
    return $result;
}

// Queries with error handling
$payments_query = "SELECT * FROM payments";
$payments_result = fetchQueryResult($conn, $payments_query);

$products_query = "SELECT * FROM animal_products";
$products_result = fetchQueryResult($conn, $products_query);

$pets_query = "SELECT p.pet_name, c.client_name FROM pets p JOIN clients c ON p.owner_id = c.id";
$pets_result = fetchQueryResult($conn, $pets_query);

$clients_query = "SELECT * FROM clients";
$clients_result = fetchQueryResult($conn, $clients_query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Overview</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
            margin: 0;
            padding: 0;
        }

        .gridlayout {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            max-width: 800px;
            margin: 40px auto;
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h1 {
            grid-column: span 2;
            text-align: center;
            color: #333;
            font-size: 2em;
            margin-bottom: 20px;
        }

        .overview-section {
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            background-color: #f9f9f9;
        }

        .overview-section h2 {
            color: #007bff;
            font-size: 1.5em;
            margin-bottom: 10px;
        }

        ul {
            list-style-type: none;
            padding: 0;
            margin: 0;
        }

        li {
            font-size: 1em;
            padding: 8px 0;
            border-bottom: 1px solid #ddd;
        }

        li:last-child {
            border-bottom: none;
        }

        .button {
            display: inline-block;
            margin-top: 10px;
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            text-align: center;
            font-weight: bold;
            transition: background-color 0.3s;
        }

        .button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

<div class="gridlayout">
    <h1>Dashboard Overview</h1>

    <div class="overview-section">
        <h2>Current Payments</h2>
        <ul>
            <?php if ($payments_result && $payments_result->num_rows > 0): ?>
                <?php while ($row = $payments_result->fetch_assoc()): ?>
                    <li>Payment ID: <?php echo htmlspecialchars($row['id']); ?> - Amount: $<?php echo htmlspecialchars($row['amount']); ?> - Status: <?php echo htmlspecialchars($row['status']); ?></li>
                <?php endwhile; ?>
            <?php else: ?>
                <li>No payments found or failed to load payments data.</li>
            <?php endif; ?>
        </ul>
        <!-- <a href="payments.php" class="button">Manage Payments</a> -->
    </div>

    <div class="overview-section">
        <h2>Animal Products</h2>
        <ul>
            <?php if ($products_result && $products_result->num_rows > 0): ?>
                <?php while ($row = $products_result->fetch_assoc()): ?>
                    <li>Product: <?php echo htmlspecialchars($row['product_name']); ?> - Stock: <?php echo htmlspecialchars($row['stock']); ?> bags</li>
                <?php endwhile; ?>
            <?php else: ?>
                <li>No animal feeds found.</li>
            <?php endif; ?>
        </ul>
        <!-- <a href="animalproducts.php" class="button">View Animal Products</a> -->
    </div>

    <div class="overview-section">
        <h2>Your Pet Information</h2>
        <ul>
            <?php if ($pets_result && $pets_result->num_rows > 0): ?>
                <?php while ($row = $pets_result->fetch_assoc()): ?>
                    <li>Pet Name: <?php echo htmlspecialchars($row['pet_name']); ?> - Owner: <?php echo htmlspecialchars($row['client_name']); ?></li>
                <?php endwhile; ?>
            <?php else: ?>
                <li>No pets' information found.</li>
            <?php endif; ?>
        </ul>
        <!-- <a href="petinfo.php" class="button">View Pet Info</a> -->
    </div>

    <div class="overview-section">
        <h2>Profile Management</h2>
        <ul>
            <?php if ($clients_result && $clients_result->num_rows > 0): ?>
                <?php while ($row = $clients_result->fetch_assoc()): ?>
                    <li>Client: <?php echo htmlspecialchars($row['client_name']); ?> - Contact: <?php echo htmlspecialchars($row['contact']); ?></li>
                <?php endwhile; ?>
            <?php else: ?>
                <li>No profile data found.</li>
            <?php endif; ?>
        </ul>
        <!-- <a href="client.php" class="button">View Client Info</a> -->
    </div>
</div>

<?php $conn->close(); ?>
</body>
</html>
