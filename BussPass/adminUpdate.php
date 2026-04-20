<?php
session_start();
$conn = new mysqli("localhost", "root", "", "buspass");

$em= $_SESSION['admin_id'] ?? 1;

// UPDATE
if(isset($_POST['update'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    $conn->query("UPDATE admin SET 
        username='$name',
        email='$email',
        password='$password'
        WHERE email='$em'
    ");

    echo "Updated Successfully!";
}

// GET DATA
$result = $conn->query("SELECT * FROM admin WHERE email='$em'");
if($result && $result->num_rows > 0){
    $row = $result->fetch_assoc();
} else {
    echo "No admin data found!";
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Update Admin</title>

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

        input {
            width: 100%;
            padding: 8px;
            margin: 8px 0;
        }

        button {
            width: 100%;
            padding: 10px;
            background: #e67e22;
            color: white;
            border: none;
            border-radius: 5px;
        }

        a {
            display: block;
            margin-top: 10px;
            text-align: center;
            text-decoration: none;
            background: #3498db;
            color: white;
            padding: 10px;
            border-radius: 5px;
        }
    </style>
</head>

<body>

<div class="box">
    <h2>Update Admin</h2>

    <form method="POST">
        <input type="text" name="name" value="<?php echo $row['username']; ?>">
        <input type="text" name="email" value="<?php echo $row['email']; ?>">
        <input type="text" name="password" value="<?php echo $row['password']; ?>">

        <button type="submit" name="update">Save</button>
    </form>

    <a href="admin_view.php">Back to View</a>
</div>

</body>
</html>