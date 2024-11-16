<?php include('config.php');?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Services</title>
    <style>
        
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Arial', sans-serif;
}

body {
    background-color: #f4f4f4;
    color: #333;
}

h1, h2 {
    color: #444;
    text-align: center;
}

.sidebar {
    background-color: #333;
    color: #fff;
    width: 200px;
    padding: 20px;
}

.sidebar ul {
    list-style-type: none;
}

.sidebar-link {
    color: #fff;
    text-decoration: none;
    display: block;
    padding: 10px;
    margin-bottom: 8px;
    border-radius: 4px;
    transition: background 0.3s;
}

.sidebar-link:hover {
    background-color: #555;
}
a {
    text-decoration: none;
    color: #fff;
}


.navbar {
    display: flex;
    align-items: center;
    background-color: #333;
    padding: 1rem 2rem;
}

.logo {
    font-size: 1.5rem;
    color: #fff;
    font-weight: bold;
}

.nav-links {
    list-style: none;
    display: flex;
    margin-left: auto; 
}

.nav-links li {
    margin-left: 20px;
}

.nav-links li a {
    color: #fff;
    font-size: 1rem;
    transition: color 0.3s ease;
}

.nav-links li a:hover {
    color: #ff9800;
}


.services {
    margin: 20px 0;
}

.container {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 20px;
    margin: 20px auto;
    padding: 20px;
    max-width: 1200px;
}

.room {
    background-color: #fff;
    padding: 15px;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    text-align: center;
    width: 300px;
    transition: transform 0.3s ease;
}

.room:hover {
    transform: translateY(-10px);
}

.room img {
    max-width: 100%;
    border-radius: 10px;
}

.room h2 {
    margin-top: 10px;
    font-size: 1.3rem;
}

.book a {
    display: inline-block;
    margin-top: 10px;
    background-color: #ff9800;
    padding: 10px 20px;
    border-radius: 20px;
    transition: background-color 0.3s ease;
}

.book a:hover {
    background-color: #e68900;
}


footer {
    background-color: #333;
    color: #fff;
    padding: 20px;
    text-align: center;
    margin-top: 40px;
    font-size: 1rem;
}

    </style>
</head>
<body> 
    <nav>
        <div class="navbar">
            <div class="logo">Castro Animal Care Shelter</div>
            <ul class="nav-links">
                <li><a href="index.php">Home</a></li>
                <li><a href="about.php">About</a></li>
                <li><a href="services.php">Services</a></li>
                <li><a href="login.php">Login</a></li>
                <li><a href="register.php">Register</a></li>
            </ul>
        </div>
    </nav>

    <div class="services">
        <h2>OUR SERVICES</h2>
    </div>
    <div class="container">
        <section class="room">
            <img class="images" src="./Images/animal.2.jpg" alt="Adoption" width="200"/>
            <h2>Adoption</h2>
            <div class="book">
                <p><a href="login.php">Book Now</a></p>
            </div>
        </section>
        <section class="room">
            <img class="images" src="./Images/animal.5.jpg" alt="Admission" width="200"/>
            <h2>Admission</h2>
            <div class="book">
                <p><a href="login.php">Book Now</a></p>
            </div>
        </section>
        <section class="room">
            <img class="images" src="./Images/animal.4.jpeg" alt="Immunisation" width="200"/>
            <h2>Immunisation</h2>
            <div class="book">
                <p><a href="login.php">Book Now</a></p>
            </div>
        </section>
    </div>   

    <div class="attendance">
        <header>
            <h1>Pet Attendance</h1>
        </header>
    </div>
    <div class="container">
        <section class="room">
            <img class="images" src="./Images/animal.6.jpeg" alt="check up" width="200"/>
            <h2>Check Up</h2>
            <div class="book">
                <p><a href="login.php">Book Now</a></p>
            </div>
        </section>
        <section class="room">
            <img class="images" src="./Images/animal.7.jpeg" alt="Grooming" width="200"/>
            <h2>Grooming</h2>
            <div class="book">
                <p><a href="login.php">Book Now</a></p>
            </div>
        </section>
    </div>

    <footer>
        <p>&copy; 2024 Castro Shelter</p>
    </footer>    
</body>
</html>
