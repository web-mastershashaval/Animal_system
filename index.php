<?php include('config.php');?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Page</title>
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
body {
    background: url('./Images/animal.8.jpg') no-repeat center center/cover;
    background-attachment: fixed;
    color: #333;
    font-family: 'Arial', sans-serif;
}



.hero-section {
    height: 400px;
    display: flex;
    justify-content: center;
    align-items: center;
    text-align: center;
    color: #fff;
}




.hero-text {
    background-color: rgba(0, 0, 0, 0.6);
    padding: 20px;
    border-radius: 10px;
}

.hero-text h1 {
    font-size: 2.5rem;
    margin-bottom: 10px;
}

.hero-text p {
    font-size: 1.2rem;
}

.hero-btn {
    margin-top: 20px;
    padding: 10px 20px;
    background-color: #ff9800;
    color: #fff;
    border-radius: 5px;
    transition: background-color 0.3s ease;
}

.hero-btn:hover {
    background-color: #e68900;
}


.mission-section {
    text-align: center;
    margin: 40px auto;
    padding: 20px;
    max-width: 800px;
    background-color: rgba(255, 255, 255, 0.8); /* Light background for visibility */
    color: #333; /* Darker text color for contrast */
    border-radius: 10px;
}

.mission-section h2 {
    font-size: 2rem;
    margin-bottom: 20px;
}

.mission-section p {
    font-size: 1.1rem;
    line-height: 1.6;
}


.services-preview {
    background-color: #fff;
    padding: 20px;
    margin: 40px auto;
    max-width: 1000px;
    text-align: center;
}

.services-preview h2 {
    font-size: 2rem;
    margin-bottom: 20px;
}

.service-items {
    display: flex;
    justify-content: space-around;
    margin-top: 20px;
}

.service-item {
    background-color: #f9f9f9;
    padding: 20px;
    border-radius: 10px;
    width: 30%;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.service-item h3 {
    font-size: 1.5rem;
    margin-bottom: 10px;
}

.service-item p {
    font-size: 1rem;
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


footer {
    background-color: #333;
    color: #fff;
    padding: 20px;
    text-align: center;
    margin-top: 40px;
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

    <header class="hero-section">
        <div class="hero-text">
            <h1>Welcome to Castro Animal Care Shelter</h1>
            <p>Providing care and love for animals in need since 2023</p>
            <a href="services.php" class="hero-btn">Explore Our Services</a>
        </div>
    </header>

    <div class="container">
        <section class="mission-section">
            <h2>Our Mission</h2>
            <p>At Castro Animal Care Shelter, our mission is to rescue, rehabilitate, and find forever homes for pets in need. We provide comprehensive services to ensure every animal gets the love and care they deserve.</p>
        </section>

        <section class="services-preview">
            <h2>Our Services</h2>
            <div class="service-items">
                <div class="service-item">
                    <h3>Adoption</h3>
                    <p>Find a new furry friend and give them a loving home. Our shelter offers various pets for adoption.</p>
                </div>
                <div class="service-item">
                    <h3>Grooming</h3>
                    <p>Keep your pet looking their best with our professional grooming services.</p>
                </div>
                <div class="service-item">
                    <h3>Veterinary Care</h3>
                    <p>From vaccinations to regular check-ups, our expert team is here to care for your pet’s health.</p>
                </div>
            </div>
        </section>
    </div>

    <footer>
        <p>&copy; 2024 Castro Animal Care Shelter. All Rights Reserved.</p>
    </footer>
</body>
</html>
