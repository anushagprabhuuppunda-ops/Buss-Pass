<?php
session_start();

$conn = new mysqli("localhost","root","","buspass");
if($conn->connect_error){
    die("Connection failed: ".$conn->connect_error);
}

$user_id = $_SESSION['user_id'] ?? 0;

$name = $_POST['name'] ?? "";
$age = $_POST['age'] ?? "";
$gender = $_POST['gen'] ?? "";
$source = $_POST['source'] ?? "";
$destination = $_POST['destination'] ?? "";
$duration = $_POST['duration'] ?? "";

$sources = $conn->query("SELECT DISTINCT source FROM route");
$destinations = $conn->query("SELECT DISTINCT destination FROM route");

$fare = "";
$msg = "";

// 🔵 CALCULATE FARE
if(isset($_POST['calculate'])){
    if($source && $destination && $duration){

        $res = $conn->query("SELECT routeid,amount FROM route 
        WHERE (source='$source' AND destination='$destination') 
        OR (source='$destination' AND destination='$source')");

        if($res->num_rows > 0){
            $row = $res->fetch_assoc();
            $fare = $row['amount'] * $duration;

            $_SESSION['amount']=$fare;
            $_SESSION['routeid']=$row['routeid'];
        } else {
            $fare = "Not Available";
        }
    }
}

// 🔵 APPLY
if(isset($_POST['apply'])){

    $passtype = $_POST['pass_type'] ?? "";

    $photo = $_FILES['photo']['name'];
    $tmp = $_FILES['photo']['tmp_name'];

    $error=[];

    if(empty($name)) $error[]="Name required";
    if(empty($age)) $error[]="Age required";
    if(empty($gender)) $error[]="Gender required";
    if(empty($passtype)) $error[]="Pass type required";
    if(empty($source)) $error[]="Source required";
    if(empty($destination)) $error[]="Destination required";
    if(empty($duration)) $error[]="Duration required";

    if(empty($error)){

        if($source === $destination){
            echo "<script>alert('Source and Destination cannot be same');</script>";
        } else {
            $routeQuery = $conn->query("
SELECT routeid FROM route 
WHERE (source='$source' AND destination='$destination') 
OR (source='$destination' AND destination='$source')
");

if($routeQuery->num_rows == 0){
    die("Route not found");
}

$routeData = $routeQuery->fetch_assoc();
$route_id = $routeData['routeid'];

            $today = date("Y-m-d");

$check = $conn->query("
SELECT * FROM pass 
WHERE userid='$user_id'
AND routeid='$route_id'
AND duration='$duration'
AND expiry >= '$today'
AND status = 'Approved'
");

            if($check->num_rows > 0){
                $row = $check->fetch_assoc();
                $passid = $row['passid'];

                echo "<script>
                if(confirm('⚠ You already have this pass. Do you want to renew?')){
                    window.location.href='renew.php?passid=$passid';
                } else {
                    window.location.href='applyy.php';
                }
                </script>";
                exit();
            }

            // 🔵 GET ROUTE + FARE
            $res = $conn->query("SELECT routeid,amount FROM route 
            WHERE (source='$source' AND destination='$destination') 
            OR (source='$destination' AND destination='$source')");

            if($res->num_rows > 0){
                $row = $res->fetch_assoc();

                $fare = $row['amount'] * $duration;
                $routeid = $row['routeid'];

                move_uploaded_file($tmp,"uploads/".$photo);

                $_SESSION['name']=$name;
                $_SESSION['age']=$age;
                $_SESSION['gender']=$gender;
                $_SESSION['passtype']=$passtype;
                $_SESSION['source']=$source;
                $_SESSION['destination']=$destination;
                $_SESSION['duration']=$duration;
                $_SESSION['amount']=$fare;
                $_SESSION['routeid']=$routeid;

                // upload photo
    $photo = $_FILES['photo']['name'] ?? "";
    $photo_tmp = $_FILES['photo']['tmp_name'] ?? "";
    $photo_filename = "";

    if ($photo && $photo_tmp) {
        $photo_filename = uniqid('pass_', true) . '_' . basename($photo);
        move_uploaded_file($photo_tmp, __DIR__ . '/uploads/' . $photo_filename);
        $_SESSION['photo']=$photo_filename;
    }

                

                echo "<script>window.location.href='paymenttt.php';</script>";
                exit();
            } else {
                echo "<script>alert('Route not found');</script>";
            }
        }

    } else {
        foreach($error as $e){
            $msg .= $e."<br>";
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
    font-family: Arial;
    background-color: #879cbb;
}

.container {
    width: 400px;
    margin: auto;
    background-color:rgba(215, 186, 186, 0.7);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.7);
    padding: 20px;
    border-radius: 10px;
    margin-top: 50px;
}

h2 {
    text-align: center;
}

label {
    display: block;
    margin-top: 10px;
}

input, textarea, select {
    width: 90%;
    padding: 8px;
    margin-top: 5px;
}

button {
    width: 100%;
    padding: 10px;
    margin-top: 15px;
    background: #008000;
    color: white;
    border: none;
    cursor: pointer;
}

button:hover {
    background: #218838;
}
        </style>
    <title>Bus Pass Application</title>
</head>
<body>

<div class="container">
    <h2>Bus Pass Application Form</h2>
    <?php if(!empty($msg)) { ?>
    <div style="color:red; background:#ffe0e0; padding:10px; margin-bottom:10px; border-radius:5px;">
        <?php echo $msg; ?>
    </div>
<?php } ?>

    <form  method="POST" enctype="multipart/form-data">

        <label>Full Name:</label>
        <input type="text" name="name" value="<?php echo $name; ?>" required>

        <label>Age:</label>
        <input type="number" name="age" id="age" value="<?php echo $age; ?>" oninput="handleAge();" required>
         <p id="ageError" style="color:red;"></p>

        <label>Gender:</label>
        <div style="display: flex; gap: 1px;">
           <input type="radio" name="gen"  value="Male" 
<?php if($gender=="Male") echo "checked"; ?>> Male

<input type="radio" name="gen" value="Female" 
<?php if($gender=="Female") echo "checked"; ?>> Female

<input type="radio" name="gen" value="Others" 
<?php if($gender=="Others") echo "checked"; ?>> Others
            </div>
        
       <label for="source">Source</label>
            <select name="source" id="source" onchange="checkDist();" required>
<option value="">Select Source</option>

<?php while($row = $sources->fetch_assoc()) { ?>
<option value="<?php echo $row['source']; ?>"
<?php if($source == $row['source']) echo "selected"; ?>>
<?php echo $row['source']; ?>
</option>
<?php } ?>

</select>
       
                <label for="desti">Destination</label>
              <select name="destination" id="destination" onchange="checkDist();" required>
<option value="">Select Destination</option>

<?php while($row = $destinations->fetch_assoc()) { ?>
<option value="<?php echo $row['destination']; ?>"
<?php if($destination == $row['destination']) echo "selected"; ?>>
<?php echo $row['destination']; ?>
</option>
<?php } ?>

</select>
<p id="error" style="color:red;" class="error"></p>

        <label>Pass Type:</label>
       <?php $typeSel = $_POST['pass_type'] ?? ""; ?>

<select name="pass_type" id="pass_type" onchange="ageType();" required>
    <option value="">Select Pass Type</option>
    <option value="Student" <?php if($typeSel=="Student") echo "selected"; ?>>Student</option>
    <option value="General" <?php if($typeSel=="General") echo "selected"; ?>>General</option>
    <option value="Senior citizen" <?php if($typeSel=="Senior citizen") echo "selected"; ?>>Senior Citizen</option>
</select>

        <label>Duration:</label>
<select name="duration">
<option value="">Select Duration</option>

<option value="1" <?php if($duration==1) echo "selected"; ?>>1 Month</option>
<option value="3" <?php if($duration==3) echo "selected"; ?>>3 Months</option>
<option value="6" <?php if($duration==6) echo "selected"; ?>>6 Months</option>

</select>

        <label>Upload Photo:</label>
        <input type="file" name="photo" <?php if(empty($_SESSION['photo'])) echo "required"; ?>>
        <?php if(!empty($photo)) { ?>
    <p>Previously uploaded Photo:</p>
    <img src="uploads/<?php echo $photo; ?>" width="100">
<?php } ?>
        
        
        <button type="submit" name="calculate">Check Fare</button>
       
        <h3 style="text-align:center;">Total Amount: ₹ <?php echo $fare; ?></h3>
        
    <button type="submit" name="apply">Apply & Pay</button>
</form>

</div>
<script>
function checkAge() {
    let age = document.getElementById("age").value;
    let error = document.getElementById("ageError");

    if (age <= 10) {
        error.innerText = "Age must be above 10";
    } else {
        error.innerText = "";
    }
}
function checkDist(){
let source = document.getElementById("source").value;
    let destination = document.getElementById("destination").value;
    let error = document.getElementById("error");

   if (source !== "" && destination !== "" && source === destination) {
        error.innerText = "Source and Destination cannot be same ";
        return;
    } else {
        error.innerText = "";
    }
}

function ageType() {
    let age = parseInt(document.getElementById("age").value);
    let passType = document.getElementById("pass_type");

    let seniorOption = passType.querySelector("option[value='Senior citizen']");

    if (age < 60) {

        // enable all
        for (let i = 0; i < passType.options.length; i++) {
            passType.options[i].disabled = false;
        }

        // disable senior
        seniorOption.disabled = true;

        // reset if selected
        if (passType.value === "Senior citizen") {
            passType.value = "";
            alert("Senior Citizen pass is only for age 60 and above.");
        }

    } else {

        // disable all except senior
        for (let i = 0; i < passType.options.length; i++) {
            if (passType.options[i].value !== "Senior citizen") {
                passType.options[i].disabled = true;
            }
        }

        // enable + auto select
        seniorOption.disabled = false;
        passType.value = "Senior Citizen";
    }
}
function handleAge() {
    checkAge();
    ageType();
}

</script>
</body>
</html>