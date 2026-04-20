<?php
session_start();        // Start session
session_unset();        // Remove all session variables
session_destroy();      // Destroy session
echo "<script>alert('Successfully logged out');
        window.location.href='loginFront.php';</script>";
//header("Location: loginFront.php"); // Redirect after logout
exit();
?>
