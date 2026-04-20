<?php
session_start();

// 🔴 login check
if(!isset($_SESSION['login']) || $_SESSION['login'] !== TRUE){
    header("Location: loginFront.php");
    exit();
}

$conn = mysqli_connect("localhost","root","","buspass");

if(!$conn){
    die("DB connection failed");
}

// 🔵 get user from session (set in login)
$id = $_SESSION['user_id'] ?? '';

if(empty($id)){
    die("Session expired. Please login again.");
}



$qry = "SELECT * FROM user WHERE userid='$id'";
$result = mysqli_query($conn, $qry);

// 🔴 check user
if(!$result || mysqli_num_rows($result) == 0){
    die("User not found in database");
}

$row = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Profile</title>
    <style>
        body{
            font-family: Arial;
            background:#879cbb;
            display:flex;
            justify-content:center;
            align-items:center;
            height:100vh;
        }

        .box{
            background:white;
            padding:20px;
            width:300px;
            border-radius:10px;
            text-align:center;
        }

        h2{
            margin-bottom:15px;
        }

        .info{
            text-align:left;
            margin:10px 0;
        }

        a button{
            width:100%;
            padding:10px;
            background:#007bff;
            color:white;
            border:none;
            border-radius:5px;
            cursor:pointer;
        }

        a button:hover{
            background:#0056b3;
        }
    </style>
</head>
<body>

<div class="box">

    <h2>Your Profile</h2>

    <div class="info">
        <b>Username:</b> <?php echo $row['Username']; ?><br><br>
        <b>Email:</b> <?php echo $row['Email']; ?>
    </div>

    <br>
    <a href="homeyyy.php"><button>Back</button></a>

</div>

</body>
</html>