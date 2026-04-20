<?php
session_start();
$conn = new mysqli("localhost","root","","buspass");

$sourceQuery = $conn->query("SELECT DISTINCT source FROM route");
$destinationQuery = $conn->query("SELECT DISTINCT destination FROM route");

$passId = $_POST['passid'] ?? "";
$message = "";

// 🔴 CHECK PASS
if ($passId) {
    // Sanitize input to prevent SQL injection
    $passId = $conn->real_escape_string($passId);
    $res = $conn->query("SELECT * FROM pass WHERE passid='$passId'");

    if($res->num_rows > 0){
        $data = $res->fetch_assoc();
        $today = date("Y-m-d");
        
        // 1. Check if the pass is still active/valid
        // A pass is "Valid" if status is Approved AND expiry date is in the future
        if(strtolower($data['status']) == 'approved' && $data['expiry'] >= $today){
            echo "<script>alert('This pass is still valid until " . $data['expiry'] . ". Renewal is not required.'); window.location='homeyyy.php';</script>";
            exit();
        }

        // 2. Check for Rejections
        if(strtolower($data['status']) == 'rejected'){
            echo "<script>
            if(confirm('Pass was rejected. You cannot renew a rejected pass. Apply for a new one?')){
                window.location='applyy.php';
            } else {
                window.location='homeyyy.php';
            }
            </script>";
            exit();
        }
        if(strtolower($data['status']) == 'waiting'){
    echo "<script>alert('This pass application is still pending approval. You cannot renew it yet.'); window.location='homeyyy.php';</script>";
    exit();
}

        // 🔵 If we reached here, the pass is either Expired or the date has passed
        // GET OLD ROUTE DETAILS for the form
        $routeOld = $conn->query("SELECT source, destination FROM route WHERE routeid='".$data['routeid']."'");
        if($routeOld->num_rows > 0){
            $routeData = $routeOld->fetch_assoc();
            $source = $routeData['source'];
            $destination = $routeData['destination'];
        }
        $duration = $data['duration'];

    } else {
        echo "<script>alert('Invalid Pass ID'); window.location='renew.php';</script>";
        exit();
    }
}
// 🔴 RENEW BUTTON
if(isset($_POST['btn'])){

    $source = $_POST['source'];
    $destination = $_POST['destination'];
    $duration = $_POST['duration'];
 if($source === $destination){
            echo "<script>alert('Source and Destination cannot be same');</script>";
 }
 else{
    // 🔵 GET ROUTE ID
    $routeRes = $conn->query("
    SELECT routeid FROM route 
    WHERE (source='$source' AND destination='$destination') 
    OR (source='$destination' AND destination='$source')
    ");

    if($routeRes->num_rows == 0){
        die("Route not found");
    }

    $routeRow = $routeRes->fetch_assoc();
    $route_id = $routeRow['routeid'];

    // 🔴 SAVE ONLY REQUIRED DATA
    $_SESSION['renew_data'] = [
        'passid' => $passId,
        'routeid' => $route_id,
        'duration' => $duration
    ];

    header("Location: renewpaymenttt.php");
    exit();
}
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Bus Pass Renewal</title>
<style>
body {
    font-family: Arial, sans-serif;
    background-color:#879cbb;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    margin: 0;
}

.renewal-container {
    background-color:rgba(215, 186, 186, 0.7);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.7);
    padding: 20px 30px;
    border-radius: 8px;
    width: 300px;
}

h2 {
    text-align: center;
    color:#333;
}

.form-group {
    margin-bottom: 15px;
}

label {
    display: block;
    margin-bottom: 5px;
    color: #555;
}

input, select, button {
    width: 100%;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 4px;
    box-sizing: border-box;
}

button {
    background-color: #008000;
    color: white;
    border: none;
    cursor: pointer;
    margin-top: 10px;
}

button:hover {
    background: #218838;
}
</style>
</head>
</body>
<div class="renewal-container">
<?php if ($message): ?>
    <?= $message ?>
<?php elseif (!$passId): ?>
    <form action="renew.php" method="POST">
        <h2>Enter Pass ID to Renew</h2>
        <div class="form-group">
            <label for="passid">Pass ID:</label>
            <input type="text" name="passid" id="passid" placeholder="Enter Pass Number" required>
        </div>
        <button type="submit">Submit</button>
    </form>
<?php else: ?>
    <form action="renew.php" method="POST">
        <input type="hidden" name="passid" value="<?= htmlspecialchars($passId) ?>">
        <h2>Renew Your Bus Pass</h2>

 <div class="form-group">
 <label for="source">Source</label>
            <select name="source" id="source" onchange="checkDist();" required>
<option value="">Select Source</option>

<?php while($row = $sourceQuery->fetch_assoc()) { ?>
<option value="<?php echo $row['source']; ?>"
<?php if($source == $row['source']) echo "selected"; ?>>
<?php echo $row['source']; ?>
</option>
<?php } ?>

</select>
       
                <label for="desti">Destination</label>
              <select name="destination" id="destination" onchange="checkDist();" required>
<option value="">Select Destination</option>

<?php while($row = $destinationQuery->fetch_assoc()) { ?>
<option value="<?php echo $row['destination']; ?>"
<?php if($destination == $row['destination']) echo "selected"; ?>>
<?php echo $row['destination']; ?>
</option>
<?php } ?>

</select>
<p id="error" style="color:red;" class="error"></p>
</div>
<div class="form-group">
 <label for="duration">Renewal Duration(Months):</label>
 <select id="duration" name="duration">
 <option value="1">1 Month</option>
 <option value="3">3 Months</option>
 <option value="6">6 Months</option>
 <option value="12">12 Months</option>
 </select>
 </div>
 <button type="submit" name="btn">Proceed to Payment</button>
 </form>
<?php endif; ?>
 </div>
 <script>
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
</script>
</html>
