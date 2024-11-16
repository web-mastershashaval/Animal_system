<?php include('config.php');?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us</title>
    <style>
        
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Arial', sans-serif;
}

body {
    background: url('./Images/animal.12.jpeg') no-repeat center center fixed; 
    background-size: cover;
    color: #333;
}


.navbar {
    display: flex;
    align-items: center;
    background-color: rgba(51, 51, 51, 0.8); 
    padding: 1rem 2rem;
}

.logo {
    font-size: 1.8rem; 
    color: #ff9800; 
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
    text-decoration: none; 
}

.nav-links li a:hover {
    color: #ff9800; 
}


.container {
    background-color: rgba(255, 255, 255, 0.85); 
    padding: 30px; 
    margin: 40px auto; 
    max-width: 800px;
    text-align: center;
    border-radius: 10px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.5);
}


h1 {
    font-size: 2.5rem;
    color: #333;
    margin-bottom: 20px;
    text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5); 
}


p {
    font-size: 1.1rem;
    color: #555;
    line-height: 1.6;
    margin-bottom: 15px;
    text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.4); 
}


@media (max-width: 768px) {
    .container {
        padding: 20px; 
        margin: 20px auto; 
    }

    h1 {
        font-size: 2rem; 
    }

    p {
        font-size: 1rem; 
    }

    .navbar {
        flex-direction: column; 
        align-items: flex-start; 
    }

    .nav-links {
        margin-top: 10px; 
    }

    .nav-links li {
        margin-left: 0; 
        margin-bottom: 10px; 
    }
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
    
    <div class="background"></div>

    <div class="container">
        <h1>About Us</h1>   
        <p>Castro Animal Shelter and Care Management is an adoption centre that was initiated in July 2023.</p>
        <p>The founder, Mama Achieng, started the shelter with the aim of taking care of pets in Kasarani.</p> 
        <p>The aim of this shelter is to provide services to pet owners living around Kasarani.</p>  
        <p>This shelter also aims at tending to stray pets abandoned on the streets.</p> 
        <p>Castro Animal Shelter and Care Management also provides pet owners with a facility to take care of their pets.</p>
        <p>Our services include: check-ups, immunisation, grooming, accommodation, and adoption.</p>
    </div>
</body>
</html>