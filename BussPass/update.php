CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(50),
    email VARCHAR(50),
    phone VARCHAR(15),
    address TEXT
);
<?php
$conn = mysqli_connect("localhost", "root", "", "buspass");

// Assume user id = 1 (you can use session later)
$id = 1;

$result = mysqli_query($conn, "SELECT * FROM users WHERE id=$id");
$row = mysqli_fetch_assoc($result);
?>






<!DOCTYPE html>
<html>
<head>
    <title>Settings</title>
</head>
<body>

<h2>User Settings</h2>

<form method="POST" action="update.php">
    <input type="hidden" name="id" value="<?php echo $row['id']; ?>">

    Name: <br>
    <input type="text" name="name" value="<?php echo $row['name']; ?>"><br><br>

    Email: <br>
    <input type="email" name="email" value="<?php echo $row['email']; ?>"><br><br>

    Phone: <br>
    <input type="text" name="phone" value="<?php echo $row['phone']; ?>"><br><br>

    Address: <br>
    <textarea name="address"><?php echo $row['address']; ?></textarea><br><br>

    <input type="submit" value="Update">
</form>

</body>
</html>




<?php
$conn = mysqli_connect("localhost", "root", "", "buspass");

$id = $_POST['id'];
$name = $_POST['name'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$address = $_POST['address'];

$query = "UPDATE users SET 
            name='$name',
            email='$email',
            phone='$phone',
            address='$address'
          WHERE id=$id";

if(mysqli_query($conn, $query)) {
    echo "Updated successfully!";
} else {
    echo "Error updating record";
}
?>