<?php
session_start();
$conn = new mysqli("localhost", "root", "", "buspass");


// receive data
$userid=$_SESSION['user_id']??"";
$name = $_SESSION['name'] ?? "";
$age = $_SESSION['age'] ?? "";
$gender = $_SESSION['gender'] ?? "";
$passtype = $_SESSION['passtype'] ?? "";
$source = $_SESSION['source'] ?? "";
$destination = $_SESSION['destination'] ?? "";
$duration = $_SESSION['duration'] ?? "";
$amount = $_SESSION['amount'] ?? "";
$routeid=$_SESSION['routeid']??"";
$message = "";


if(isset($_POST['pay'])) {

    $mode = $_POST['payment_mode'] ?? "";

    $last4 = NULL;

if($mode == "Debit Card" || $mode == "Credit Card"){
    $card_number = $_POST['card_number'] ?? "";
    $last4 = substr($card_number, -4);
    $transid = "CARD" . rand(100000,999999);
     $method = $mode . " (XXXX-XXXX-XXXX-$last4)";
} else {
    $upi = $_POST['transid'] ?? "";

    $parts = explode("@", $upi);
    $masked = substr($parts[0], 0, 3) . "***@" . ($parts[1] ?? "");

    $transid = $masked;
    $method = "UPI ($masked)";
}

    $photo = $_SESSION['photo'] ?? "";
    // get route_id
 

    // dates
    $issue = date("Y-m-d");
    $expiry = NULL; // Will be set when approved by admin
    $res = $conn->query("SELECT routeid FROM route 
WHERE (source='$source' AND destination='$destination') 
OR (source='$destination' AND destination='$source')");

if($res->num_rows > 0){
    $row = $res->fetch_assoc();
    $routeid = $row['routeid'];

} else {
    die("❌ Route not found in database");
}

    // save payment
      $conn->query("INSERT INTO payment 
(userid, amount, payment_date, paystatus, method, transid)
VALUES 
('$userid', '$amount', '$issue', 'Success', '$method', '$transid')");
$payment_id = $conn->insert_id;

$conn->query("INSERT INTO pass 
(userid, routeid, name, age, gender, passtype, applydate, duration, expiry, amount, photo, payment_id, approved_date) 
VALUES 
('$userid', '$routeid', '$name', '$age', '$gender', '$passtype', '$issue', '$duration', '$expiry', '$amount', '$photo', '$payment_id', NULL)");    // save pass
 
$pass_id = $conn->insert_id;

// store in session
$_SESSION['pass_id'] = $pass_id;

    echo"<script>alert('Applied Successfully!✅');
            window.location.href='homeyyy.php';</script>";
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Payment</title>

<style>
body {
    font-family: Arial;
    background: linear-gradient(to right, #36d1dc, #5b86e5);
}

.container {
    width: 400px;
    margin: 60px auto;
    background: white;
    padding: 25px;
    border-radius: 10px;
    box-shadow: 0 0 15px rgba(0,0,0,0.3);
}

h2 {
    text-align: center;
}

.amount {
    text-align: center;
    font-size: 22px;
    color: green;
    margin: 10px 0;
}

label {
    display: block;
    margin-top: 10px;
}

input, button {
    width: 100%;
    padding: 8px;
    margin-top: 5px;
}

.radio-group {
    margin-top: 10px;
}

.radio-group input {
    width: auto;
    margin-right: 5px;
}

button {
    background: green;
    color: white;
    border: none;
    margin-top: 15px;
    cursor: pointer;
}

button:hover {
    background: darkgreen;
}

.success {
    text-align: center;
    color: green;
    margin-top: 15px;
}
</style>

</head>
<body>

<div class="container">

<h2>💳 Payment</h2>

<div class="amount">
Amount: ₹ <?php echo $amount; ?>
</div>

<form method="POST" enctype="multipart/form-data">

<!-- keep data -->
<input type="hidden" name="name" value="<?php echo $name; ?>">
<input type="hidden" name="source" value="<?php echo $source; ?>">
<input type="hidden" name="destination" value="<?php echo $destination; ?>">
<input type="hidden" name="duration" value="<?php echo $duration; ?>">
<input type="hidden" name="amount" value="<?php echo $amount; ?>">

<!-- Payment Mode -->
<label>Payment Method:</label>
<div class="radio-group">
    <input type="radio" name="payment_mode" value="UPI" required> UPI<br>
    <input type="radio" name="payment_mode" value="Debit Card"> Debit Card<br>
    <input type="radio" name="payment_mode" value="Credit Card"> Credit Card<br>
</div>

<!-- Transaction ID -->
<div id="upiField" style="display: none;">
    <label>Transaction ID:</label>
    <input type="text" name="transid"  placeholder="Enter Transaction ID">
</div>
<!-- Card Details -->
<div id="cardDetails" style="display:none;">

<label>Card Number:</label>
<input type="text" name="card_number" placeholder="Enter Card Number">

<label>Expiry Date:</label>
<input type="month" name="expiry_date">

<label>CVV:</label>
<input type="password" name="cvv" placeholder="Enter CVV">

<label>Name on Card:</label>
<input type="text" name="card_name" placeholder="Card Holder Name">

</div>


<button type="submit" name="pay">Pay Now</button>

</form>

<?php if($message != "") { ?>
<div class="success"><?php echo $message; ?></div>
<?php } ?>

</div>
<script>
const paymentModes = document.querySelectorAll('input[name="payment_mode"]');
const cardDetails = document.getElementById("cardDetails");
const upiField = document.getElementById("upiField");

const cardInputs = cardDetails.querySelectorAll("input");
const upiInput = upiField.querySelector("input");

paymentModes.forEach(mode => {
    mode.addEventListener("change", function() {

        if (this.value === "UPI") {
            // show UPI
            upiField.style.display = "block";
            upiInput.required = true;

            // hide card
            cardDetails.style.display = "none";
            cardInputs.forEach(input => input.required = false);

        } else if (this.value === "Debit Card" || this.value === "Credit Card") {
            // show card
            cardDetails.style.display = "block";
            cardInputs.forEach(input => input.required = true);

            // hide UPI
            upiField.style.display = "none";
            upiInput.required = false;
        }
    });
});
</script>
</body>

</html>