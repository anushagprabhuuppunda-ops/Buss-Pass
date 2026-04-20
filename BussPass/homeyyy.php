<?php
session_start();
if(($_SESSION['login']!=TRUE)||(!isset($_SESSION['login'])))
    header('location:loginFront.php');
?>
<html>
    <head>
        
        <title>Bus Pass System</title>
        <style>
            body {
    margin: 0;
    font-family: Arial;
    }

/* Header */
header {
    background: #2c3e50;
    color: white;
    padding: 15px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

header h1 {
    margin: 0;
}

nav a {
    color: white;
    margin-right: 15px;;
    text-decoration: none;
}
nav form{
    display:inline;
}


/* Hero */
.hero {
    text-align: center;
    padding: 80px;
    background: #2770a1;
    color: white;
}

.hero h2 {
    font-size: 36px;
}



.btn {
    padding: 10px 20px;
    background: white;
    color: #333;
    text-decoration: none;
    margin: 5px;
    border-radius: 5px;
}

.btn.secondary {
    background: #2c3e50;
    color: white;
}
/* Dropdown container */
.dropdown {
    position: relative;
    display: inline-block;
}

/* Dropdown links */
.dropdown-content {
    display: none;
    position: absolute;
    background-color: #2c3e50; /* SAME navbar color */
    min-width: 160px;
    border-radius: 5px;
}

/* Dropdown items */
.dropdown-content a {
    color: white;
    padding: 10px;
    display: block;
    text-decoration: none;
}

/* Hover effect */
.dropdown-content a:hover {
    background-color: #34495e;
}

/* Show dropdown on hover */
.dropdown:hover .dropdown-content {
    display: block;
}
.logout-btn {
    padding: 10px 20px;
    background-color: #e74c3c;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    margin-left:15px;
}

.logout-btn:hover {
    background-color: #c0392b;
}

/* Features */
.features {
    display: flex;
    justify-content: space-around;
    padding: 40px;
}

.card {
    width: 250px;
    padding: 20px;
    background: #f4f4f4;
    text-align: center;
    border-radius: 10px;
}

/* Footer */
footer {
    text-align: center;
    padding: 15px;
    background: #2c3e50;
    color: white;
}
</style>
    
    </head>
<body>
<!-- Navbar -->
<header>
    <h1>Bus Pass System</h1>
    <nav>
        <a href="applyy.php">Apply Pass</a>
        <a href="renew.php">Renew pass</a>
        <a href="myPasses.php">My Passes</a>
        <div class="dropdown">
     <a href="#">Settings &#9662</a>
        <div class="dropdown-content">
        <a href="vieww.php">View Profile</a>
        <a href="updatee.php">Edit Profile</a>
        </div>
</div>
        <form action="logout.php" method="POST">
            <button class="logout-btn">Logout</button>
        </form>
    </nav>
</header>

<!-- Hero Section -->
<section class="hero">
    <h2>Easy Bus Pass Generation</h2>
    <p>Apply for your bus pass online quickly and easily.</p>
    
    <div class="buttons">
        <a href="applyy.php" class="btn">Apply Now</a>
        <a href="passstatus.html" class="btn secondary">Generated bus pass</a>
    </div>
</section>

<!-- Features -->
<section class="features">
    <div class="card">
        <h3>Online Application</h3>
        <p>Fill your details and apply from anywhere.</p>
    </div>

    <div class="card">
        <h3>Quick Approval</h3>
        <p>Fast processing of bus pass requests.</p>
    </div>

    <div class="card">
        <h3>Secure System</h3>
        <p>Your data is safe and protected.</p>
    </div>
</section>

<!-- Footer -->
<footer>
    <p>© 2026 Bus Pass System | All Rights Reserved</p>
</footer>

</body>
</html>
