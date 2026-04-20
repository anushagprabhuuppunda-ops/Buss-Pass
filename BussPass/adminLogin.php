<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Login - Bus Pass System</title>
    <link rel="stylesheet" href="style.css"> <!-- Link to your CSS file -->
</head>
<body>
<h1>Bus Pass Generation System</h1><br>
    <div class="login-container">
        <form class="login-form" action="adminLoginVeri.php" method="POST">
            <h2>Admin Login</h2>
            <p>Please enter your ID and password to access your bus pass account.</p>
            <div class="input-group">
                <label for="admin_id">Admin_name / Email</label>
                <input type="text" id="admin_id" name="admin_id">
            </div>
            <div class="input-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" autocomplete="new-password">
            </div>
            <a href="adminforgottt.php">Forgot Password?</a><br>
			<br><br>
            <button type="submit" name="lbtn">Login</button>
        </form>
    </div>
</body>
</html>
