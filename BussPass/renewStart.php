<?php
session_start();
$conn = new mysqli("localhost", "root", "", "buspass");

$userid = $_SESSION['user_id'];


// check if user already has a pass
$check = $conn->query("SELECT * FROM pass WHERE userid='$userid'");

if($check->num_rows == 0){
    echo "<script>
        alert('You have not applied for any pass yet!');
        window.location.href='applyy.php';
    </script>";
    exit();
}
?>