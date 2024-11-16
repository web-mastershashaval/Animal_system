<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>

    <style>
    
body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    margin: 0;
    display: flex;
    background-color: #f4f4f9;
    color: #333;
}

.dashboard {
    width: 100%;
    display: flex;
}

.sidebar {
    width: 220px;
    background: linear-gradient(180deg, #2c3e50, #34495e);
    padding: 30px 20px;
    height: calc(100vh - 60px); 
    position: fixed;
    box-shadow: 2px 0 10px rgba(0, 0, 0, 0.2);
    top: 60px; 
}

.sidebar ul {
    list-style-type: none;
    padding: 0;
}

.sidebar li {
    margin: 25px 0;
}

.sidebar-link {
    color: #ecf0f1;
    text-decoration: none;
    font-size: 18px;
    transition: background-color 0.3s ease, color 0.3s ease;
    display: block;
    padding: 10px;
    border-radius: 4px;
}

.sidebar-link:hover {
    background-color: #1abc9c;
    color: #fff;
}

.main-content {
    margin-left: 220px; 
    padding: 40px;
    width: calc(100% - 220px); 
    display: flex;
    flex-direction: column;
    min-height: calc(100vh - 60px); 
    background-image: url('../Images/img.jpeg'); 
    background-size: cover;
    background-position: center;
    background-repeat:no-repeat;
}

.header {
    width: 100%;
    display: flex;
    justify-content: space-between;
    background-color: #1abc9c;
    align-items: center;
    padding: 20px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    position: fixed; 
    top: 0; 
    left: 0; 
    z-index: 1000; 
}

.header h1 {
    margin: 0;
    font-size: 28px;
    color: #fff;
}

.user-info {
    font-size: 16px;
    color: #fff;
}

.user-info a {
    color: #ecf0f1;
    text-decoration: none;
    margin-left: 10px;
    transition: color 0.3s ease;
}

.user-info a:hover {
    color: #ffdd57;
}

.content {
    margin-top: 80px; 
    text-align: left;
    width: 100%;
}

.content p {
    font-size: 20px;
    color: white;
}

.spinner {
    display: none;
    margin: auto;
    border: 6px solid #f3f3f3; 
    border-top: 6px solid #3498db; 
    border-radius: 50%;
    width: 30px;
    height: 30px;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

@media (max-width: 768px) {
    .sidebar {
        width: 100px;
    }
    .main-content {
        margin-left: 100px;
        width: calc(100% - 100px);
    }
    .sidebar-link {
        font-size: 14px;
    }
}

    </style>
</head>
<body>
    <div class="dashboard">
        <nav class="sidebar">
            <ul>
                <li><a href="home.php" class="sidebar-link" data-target="home.php">Dashboard</a></li>
                <li><a href="appointments.php" class="sidebar-link" data-target="appointments.php">Appointments</a></li>
                <li><a href="petinfor.php" class="sidebar-link" data-target="petinfor.php">Pet Information</a></li>
                <li><a href="client.php" class="sidebar-link" data-target="client.php">Customers</a></li>
                <li><a href="payments.php" class="sidebar-link" data-target="payments.php">Payments</a></li>
                <li><a href="feedback.php" class="sidebar-link" data-target="feedback.php">Feedbacks</a></li>
                <li><a href="invoice.php" class="sidebar-link" data-target="invoice.php">Invoices</a></li>
                <li><a href="animalproducts.php" class="sidebar-link" data-target="animalproducts.php">Animal Products</a></li>
                <li><a href="#" class="sidebar-link" id="logout-btn" onclick="confirmLogout(event)">Log out</a></li>
            </ul>
        </nav>

        <div class="main-content">
            <header class="header">
                <h1>Dashboard</h1>
                <div class="user-info">
                    <strong>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></strong>
                </div>
            </header>

            <section class="content" id="content-area">
                <div class="spinner" id="spinner"></div>
                <span><h2 style="color: whitesmoke;">Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h2></span>
                <p>Select a section from the sidebar to begin.</p>
            </section>
        </div>
    </div>

    <script>
        function confirmLogout(event) {
        event.preventDefault(); // Prevent the default action
        const confirmation = confirm("Are you sure you want to log out?");
        if (confirmation) {
            window.location.href = '../index.php'; // Redirect to logout.php if confirmed
        }
    }
        const links = document.querySelectorAll('.sidebar-link');
        const contentArea = document.getElementById('content-area');
        const spinner = document.getElementById('spinner');

        links.forEach(link => {
            link.addEventListener('click', function(event) {
                if (this.getAttribute('href') === 'logout.php') return; // Prevent loading if logout

                event.preventDefault();
                const targetPage = this.getAttribute('data-target');
                spinner.style.display = 'block';

                fetch(targetPage)
                    .then(response => {
                        if (!response.ok) throw new Error('Network response was not ok');
                        return response.text();
                    })
                    .then(data => {
                        contentArea.innerHTML = data;
                    })
                    .catch(error => {
                        console.error('Error loading page:', error);
                        contentArea.innerHTML = '<p>Error loading content.</p>';
                    })
                    .finally(() => {
                        spinner.style.display = 'none';
                    });
            });
        });

        function printInvoice(invoiceId) {
            console.log("printInvoice function called with ID:", invoiceId); // Debug log
            const printWindow = window.open(`print_invoices.php?id=${invoiceId}`, '', 'height=600,width=800');
            printWindow.onload = function() {
                printWindow.print();
            };
        }
       
    </script>
     <script src="../scripts/client.js" defer></script>
    <script src="../scripts/appointment.js" defer></script>
    <script src="../scripts/petinfor.js" defer></script>
    <script src="../scripts/animalfeed.js" defer></script>
</body>
</html>
