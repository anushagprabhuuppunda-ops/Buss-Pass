<?php
session_start();

if(!isset($_SESSION['login']) || $_SESSION['login'] !== TRUE){
    header("Location: loginFront.php");
    exit();
}

$conn = mysqli_connect("localhost","root","","buspass");

// 🔴 SAFE SESSION
$id = $_SESSION['user_id']  ?? '';
$username = $_SESSION['username'] ?? '';
// get user data safely
if(!empty($id)){
    $qry = "SELECT * FROM user WHERE userid='$id'";
} else {
    $qry = "SELECT * FROM admin WHERE username='$username'";
}

$result = mysqli_query($conn, $qry);

if(!$result || mysqli_num_rows($result) == 0){
    echo "User not found";
    exit();
}

$row = mysqli_fetch_assoc($result);

// 🔵 UPDATE LOGIC
if(isset($_POST['update'])){
    $newUsername = $_POST['username'];
    $newemail = $_POST['email'];

    if(!empty($email)){
        $update = "UPDATE user 
                   SET username='$newUsername', email='$newemail' 
                   WHERE userid='$id'";
    } else {
        $update = "UPDATE user 
                   SET username='$newUsername', email='$newemail' 
                   WHERE userid='$id'";
    }

    if(mysqli_query($conn, $update)){
        // update session also
        $_SESSION['user_em'] = $newemail;

        echo "<script>
            alert('Profile Updated Successfully');
            window.location.href='homeyyy.php';
        </script>";
    } else {
        echo "Update failed";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Profile</title>
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
            width:300px;
            background:white;
            padding:20px;
            border-radius:10px;
        }

        input {
    width: 100%;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;

    box-sizing: border-box;  /* 🔥 THIS FIXES IT */
}

        button{
            width:100%;
            padding:10px;
            background:green;
            color:white;
            border:none;
            cursor:pointer;
        }

        button:hover{
            background:darkgreen;
        }
    </style>
</head>
<body>

<div class="box">

    <h2>Edit Profile</h2>

    <form method="POST">

        <label>Username</label>
        <input type="text" name="username"
        value="<?php echo $row['Username'] ?? ''; ?>" required><br><br>

        <label>Email</label>
        <input type="email" name="email"
        value="<?php echo $row['Email'] ?? ''; ?>" required><br><br>

        <button type="submit" name="update">Update</button>

    </form>

</div>

</body>
</html>