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
            text-align: center;
        }

        

        .sidebar ul {
            list-style-type: none;
            padding: 0;
        }

        .sidebar li {
            margin: 25px 0;
        }
        .sidebar-link li,a:active{
            background-color: green;
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
            background-image: url('../Images/topdog.jpeg'); 
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
            color: #7f8c8d;
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
            <!-- Sidebar logo -->
           
            <ul>
                <li><a href="home.php" class="sidebar-link" data-target="home.php">Dashboard</a></li>
                <li><a href="appointments.php" class="sidebar-link" data-target="appointments.php">Appointments</a></li>
                <li><a href="petinfor.php" class="sidebar-link" data-target="petinfor.php">Pet Information</a></li>
                <li><a href="payments.php" class="sidebar-link" data-target="payments.php">Payments</a></li>
                <li><a href="feedback.php" class="sidebar-link" data-target="feedback.php">Feedbacks</a></li>
                <li><a href="animalproduct.php" class="sidebar-link" data-target="animalproduct.php">Animal Products</a></li>
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
                <span><h2>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h2></span>
                <p>Select a section from the sidebar to begin.</p>
            </section>
        </div>
    </div>

    <script>
        function confirmLogout(event) {
            event.preventDefault();
            const confirmation = confirm("Are you sure you want to log out?");
            if (confirmation) {
                window.location.href = '../login.php'; // Redirect to index or login page if confirmed
            }
        }

        const links = document.querySelectorAll('.sidebar-link');
        const contentArea = document.getElementById('content-area');
        const spinner = document.getElementById('spinner');

        links.forEach(link => {
            link.addEventListener('click', function(event) {
                if (this.getAttribute('href') === 'logout.php') return;

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
            const printWindow = window.open(`print_invoices.php?id=${invoiceId}`, '', 'height=600,width=800');
            printWindow.onload = function() {
                printWindow.print();
            };
        }

        function editCustomer(id, name, contact, address, debt) {
            document.getElementById('customer-id').value = id;
            document.getElementById('name').value = name;
            document.getElementById('contact').value = contact;
            document.getElementById('address').value = address;
            document.getElementById('debt').value = debt;
        }

//script to hundle the appoiintments

         // Toggle the visibility of the booking form
    function toggleForm() {
        const formContainer = document.getElementById('formContainer');
        formContainer.style.display = formContainer.style.display === 'none' ? 'block' : 'none';
    }

    // Handle form submission
    document.getElementById('appointmentForm').onsubmit = function(event) {
        event.preventDefault();
        
        const clientName = document.getElementById('clientName').value;
        const petName = document.getElementById('petName').value;
        const appointmentDate = document.getElementById('appointmentDate').value;
        const appointmentTime = document.getElementById('appointmentTime').value;
        
        const appointmentId = document.getElementById('appointmentId').value;
        
        if (appointmentId) {
            updateAppointment(appointmentId, clientName, petName, appointmentDate, appointmentTime);
        } else {
            addNewAppointment(clientName, petName, appointmentDate, appointmentTime);
        }
        
        resetForm();
    };

    // Add new appointment
    function addNewAppointment(clientName, petName, date, time) {
        const table = document.getElementById('appointmentsTable');
        const row = table.insertRow();
        row.innerHTML = `
            <td>${clientName}</td>
            <td>${petName}</td>
            <td>${date}</td>
            <td>${time}</td>
            <td>
                <button onclick="editAppointment(this)">Edit</button>
                <button onclick="deleteAppointment(this)">Delete</button>
            </td>
        `;
    }

    // Edit appointment
    function editAppointment(button) {
        const row = button.parentNode.parentNode;
        const cells = row.getElementsByTagName('td');
        
        document.getElementById('clientName').value = cells[0].textContent;
        document.getElementById('petName').value = cells[1].textContent;
        document.getElementById('appointmentDate').value = cells[2].textContent;
        document.getElementById('appointmentTime').value = cells[3].textContent;
        
        document.getElementById('appointmentId').value = row.rowIndex; // Set row index as appointment ID
        
        toggleForm(); // Show form for editing
    }

    // Update appointment
    function updateAppointment(appointmentId, clientName, petName, date, time) {
        const table = document.getElementById('appointmentsTable');
        const row = table.rows[appointmentId - 1];
        
        row.cells[0].textContent = clientName;
        row.cells[1].textContent = petName;
        row.cells[2].textContent = date;
        row.cells[3].textContent = time;
    }

    // Delete appointment
    function deleteAppointment(button) {
        const row = button.parentNode.parentNode;
        row.parentNode.removeChild(row);
    }

    // Reset the form fields
    function resetForm() {
        document.getElementById('appointmentForm').reset();
        document.getElementById('appointmentId').value = '';
        toggleForm(); // Hide the form after submission
    }
//toggle functionality for petinformation page
function toggleSection(sectionId) {
        const sections = document.querySelectorAll('.form-container, .overview-section');
        sections.forEach(section => section.style.display = 'none');
        const activeSection = document.getElementById(sectionId);
        if (activeSection) {
            activeSection.style.display = 'block';
        } else {
            console.error("Section with ID " + sectionId + " not found.");
        }
    }

    // Populate the update form with the selected pet's details
    function populateUpdateForm(petId, petName, ownerName, petType, petBreed, petAge) {
        document.getElementById('pet_id').value = petId;
        document.getElementById('pet_name_update').value = petName;
        document.getElementById('owner_name_update').value = ownerName;
        document.getElementById('pet_type_update').value = petType;
        document.getElementById('pet_breed_update').value = petBreed;
        document.getElementById('pet_age_update').value = petAge;
        toggleSection('updatePetSection');
    }
    </script>
</body>
</html>
