<?php
session_start();
if(($_SESSION['login']!=TRUE)||(!isset($_SESSION['login'])))
    header('location:loginFront.php');
?>
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Bus Pass System</title>
        <style>
            body {
    margin: 0;
    font-family: Arial;
    }

/* Header */
body{
    background:#2a9abf;
}
header {
    background: #363655;
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
    background: #2a9abf;
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

.dropdown-content {
    display: none;
    position: absolute;
    background-color: #2c3e50;
    min-width: 160px;
    border-radius: 5px;
}

.dropdown-content a {
    color: white;
    padding: 10px;
    display: block;
    text-decoration: none;
    white-space: nowrap;
}

.dropdown-content a:hover {
    background-color: #34495e;
}

.dropdown:hover > .dropdown-content {
    display: block;
}



.logout-btn {
    padding: 10px 20px;
    background-color: #6c5ce7;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    margin-left:15px;
}

.logout-btn:hover {
    background-color: #2f238d;
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
    background: #f8e4e4;
    text-align: center;
    border-radius: 10px;
}

/* Footer */
footer {
    text-align: center;
    padding: 15px;
    background: #363655;
    color: white;
}
</style>
    
    </head>
<body>
<!-- Navbar -->
<header>
    <h1>Bus Pass System</h1>
    <nav>
    
         <div class="dropdown">
        <a href="#">Pass Management &#9662</a>
        <div class="dropdown-content">
        <a href="passmgt.php">Normal List Management </a>
        <a href="renewmgt.php">Renewal List Management</a>
</div>
    </div>
     <div class="dropdown">
        <a href="#">Pass List &#9662</a>
        <div class="dropdown-content">
        <a href="normallist.php">Normal List </a>
        <a href="approvedlist.php">Approved List</a>
        <a href="requestedlist.php">Requested List</a>
</div>
    </div>
     <div class="dropdown">
        <a href="#">Settings &#9662</a>
        <div class="dropdown-content">
        <a href="adminVieww.php">View Profile</a>
        <a href="adminUpdatee.php">Edit Profile</a>
</div>
     </div>
        <form action="logout.php" method="POST">
            <button class="logout-btn">Logout</button>
        </form>
    </nav>
</header>

<!-- Hero Section -->
<section class="hero">
    <h2>Welcome Admin!</h2>
    <p>Manage and view bus pass.</p>
    
    <div class="buttons">
        <a href="passmgt.php" class="btn">Pass Management</a>
        <a href="normallist.php" class="btn secondary">Pass List</a>
    </div>
</section>

<!-- Features -->
<section class="features">

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
