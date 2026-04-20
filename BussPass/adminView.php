<?php
session_start();
$conn = new mysqli("localhost", "root", "", "buspass");

$usn = $_SESSION['admin_id'] ?? 1;

$result = $conn->query("SELECT * FROM admin WHERE email='$usn'");
if($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
} else {
    $row = null;
    echo "No admin found!";
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Admin</title>

    <style>
        body {
            font-family: Arial;
            background: #f4f6f9;
        }

        .box {
            width: 400px;
            margin: 60px auto;
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        .data {
            background: #ecf0f1;
            padding: 10px;
            border-radius: 5px;
        }

        a {
            display: block;
            margin-top: 15px;
            text-align: center;
            text-decoration: none;
            background: #2ecc71;
            color: white;
            padding: 10px;
            border-radius: 5px;
        }
    </style>
</head>

<body>

<div class="box">
    <h2>Admin Details</h2>

    <div class="data">
        Name: <?php echo $row['username'] ?? "Not Available";?><br>
        Email: <?php echo $row['email']?? "Not Available"; ?><br>
        Password: <?php echo $row['password'] ?? "Not Available";?>
    </div>

    <a href="adminUpdate.php">Update Details</a>
</div>

</body>
</html>