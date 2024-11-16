<?php include('config.php');?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="../Styles/admin dashboard.css">
</head>
<body>
    <div class="dashboard">
        
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

        
        <div class="main-content">
            <header class="header">
                <h1>Dashboard</h1>
                <div class="user-info">
                    <strong>Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?></strong>
                </div>
            </header>
            <section class="content" id="content-area">
                <div class="spinner" id="spinner"></div>
                <h2>Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</h2>
                <p>Select a section from the sidebar to begin.</p>
            </section>

            
            <div class="modal" id="editCustomerModal">
                <div class="modal-content">
                    <h2>Edit Customer</h2>
                    <form id="edit-customer-form">
                        <input type="hidden" id="customer-id" name="customer_id">
                        <label for="name">Name:</label>
                        <input type="text" id="name" name="name" required>
                        
                        <label for="contact">Contact:</label>
                        <input type="text" id="contact" name="contact" required>
                        
                        <label for="address">Address:</label>
                        <input type="text" id="address" name="address" required>
                        
                        <label for="debt">Debt:</label>
                        <input type="number" id="debt" name="debt" required>
                        
                        <button type="submit">Save Changes</button>
                        <button type="button" onclick="closeEditModal()">Cancel</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="script.js"></script>
</body>
</html>
