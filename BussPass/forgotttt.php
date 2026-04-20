<?php
session_start();
$conn = new mysqli("localhost", "root", "", "buspass");

$showReset = false;

// Step 1: Check email
if (isset($_POST['check_email'])) {
    $email = $_POST['email'];

    $result = $conn->query("SELECT * FROM user WHERE Email='$email'");

    if ($result->num_rows > 0) {
        $_SESSION['email'] = $email;
        $showReset = true; // show reset form
    } else {
        echo"<script>alert('Email not found')
    window.location.href='forgotttt.php';</script>";
    }
}
// Step 2: Update password
if (isset($_POST['update_password'])) {
    $new_password = $_POST['new_password'];
    $email = $_SESSION['email'];

    $conn->query("UPDATE user SET Password='$new_password' WHERE Email='$email'");

    echo "<script>alert('Password updated successfully!')
    window.location.href='loginFront.php';</script>";
    session_destroy();
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
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
	/*font-family: Arial, sans-serif;
    background-color: #879cbb;
    background-image: url("bus2.jpg");
    background-size: cover;

    display: flex;
    justify-content:center;
    align-items: center;*/
    
    font-family: Arial, sans-serif;
    background:#3498db;
    
    margin: 0;
    height: 100vh;

    display: flex;
    justify-content: center;  /* horizontal center */
    align-items: center;      /* vertical center */
    
}
h1{
	text-align:center;
	margin-top:50px;
	
}

.container {
    /*background-color: #ffffff;
    padding: 2rem;
    border-radius: 8px;
    background: rgba(215, 186, 186, 0.7);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.7);
    width: 100%;
    max-width: 400px;
    text-align: center;*/
    width: 350px;
    background: white;
    padding: 25px;
    border-radius: 10px;
   box-shadow: 0 4px 8px rgba(0, 0, 0, 0.7);
    text-align: center;

}

h2 {
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

.input-goup label {
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

.form-footer a {
    color: #007bff;
    text-decoration: none;
}

.form-footer a:hover {
    text-decoration: underline;
}
</style>
</head>
<body>



<?php if (!$showReset && !isset($_SESSION['email'])) { ?>

    <!-- Email Form -->
     <div class="container">
        <h2>Forgot Password</h2>
    <form method="POST">
        <div class="input-group">
        <label>Enter Email:</label>
        <input type="email" name="email" required><br><br>
        <button type="submit" name="check_email">Next</button>
        </div>
    </form>
    </div>

<?php } else { ?>

    <!-- Reset Password Form -->
     <div class="container">
        <h2>Forgot Password</h2>
    <form method="POST">
        <div class="input-group">
        <label>New Password:</label>
        <input type="password" name="new_password" required><br><br>
        <button type="submit" name="update_password">Update</button>
    </div>
    </form>
    </div>

<?php } ?>

</body>
</html>