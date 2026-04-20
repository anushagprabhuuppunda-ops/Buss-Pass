<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>User Login - Bus Pass System</title>
    <link rel="stylesheet" href="style.css"> <!-- Link to your CSS file -->
    <style>
        body {
    /*font-family: Arial, sans-serif;
    background-color: #879cbb;
    background-image: url("bus2.jpg");
    background-size: cover;
    
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    margin: 0;*/
	font-family: Arial, sans-serif;
    background-color: #879cbb;
    /*background-image: url("bus2.jpg");*/
    background-size: cover;

    display: flex;
    flex-direction: column;   /* ADD THIS */
    align-items: center;

    margin: 0;
}
h1{
	text-align:center;
	margin-top:50px;
	
}

.login-container {
    background-color: #ffffff;
    padding: 2rem;
    border-radius: 8px;
    background: rgba(215, 186, 186, 0.7);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.7);
    width: 100%;
    max-width: 400px;
    text-align: center;
	margin-top:40px;
}

.login-form h2 {
    margin-bottom: 1rem;
    color: #333;
}

.login-form p {
    margin-bottom: 1.5rem;
    color: #666;
}

.input-group {
    margin-bottom: 1rem;
    text-align: left;
}

.input-group label {
    display: block;
    margin-bottom: 0.5rem;
    color: #333;
}

.input-group input {
    width: 100%;
    padding: 0.75rem;
    border: 1px solid #ddd;
    border-radius: 4px;
    box-sizing: border-box; /* Ensures padding doesn't affect width */
}

button {
    width: 100%;
    padding: 0.75rem;
    border: none;
    border-radius: 4px;
    background-color: #007bff; /* Bus pass system primary color */
    color: white;
    font-size: 1rem;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

button:hover {
    background-color: #0056b3;
}

.form-footer {
    margin-top: 1rem;
    font-size: 0.875rem;
    color: #666;
}

.admin{
    align-items:left;
}

header {
    padding: 15px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}
nav a {
    color: rgb(34, 34, 189);
    margin-right: 15px;;
    text-decoration: none;
}
.form-footer a {
    color: #007bff;
    text-decoration: none;
}

.form-footer a:hover {
    text-decoration: underline;
}
.admin-link {
    position: absolute;
    top: 130px;      /* distance from top */
    right: 180px;    /* change to left:20px if you want left side */
    margin-right:175px;
    text-decoration: none;
    color: blue;
    font-weight: bold;
}
.admin-link:hover{
    text-decoration: underline;
}
</style>

</head>
<body>
<h1>Bus Pass Generation System</h1><br>
<header>
    <nav>
<div class="admin">
<a href="adminLogin.php" class="admin-link">Admin Login</a>
</nav>
</header>
</div>
    <div class="login-container">
        <form class="login-form" action="login.php" method="POST">
            <h2>User Login</h2>
            <p>Please enter your ID and password to access your bus pass account.</p>
            <div class="input-group">
                <label for="user_id">Username / Email</label>
                <input type="text" id="user_id" name="username">
            </div>
            <div class="input-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" autocomplete="new-password">
            </div>
            <a href="forgotttt.php">Forgot Password?</a><br>
			<br><br>
            <button type="submit" name="lbtn">Login</button>
            <div class="form-footer">
                
                <span>Don't have an account? <a href="Regist.html">Register here</a></span>
            </div>
        </form>
    </div>
</body>
</html>