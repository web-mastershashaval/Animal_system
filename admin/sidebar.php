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
