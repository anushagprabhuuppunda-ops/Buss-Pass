<?php
session_start();
$conn = new mysqli("localhost","root","","buspass");

$data = $_SESSION['renew_data'] ?? null;

if(!$data){
    die("No renewal data");
}

$userid  = $_SESSION['user_id'];
$passId  = $data['passid'];
$routeid = $data['routeid'];
$duration= $data['duration'];

// 🔵 GET AMOUNT
$res = $conn->query("SELECT amount FROM route WHERE routeid='$routeid'");
$row = $res->fetch_assoc();
$amount = $row['amount'] * $duration;

$message = "";

if(isset($_POST['pay'])){

    $mode = $_POST['payment_mode'];
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


    $today = date("Y-m-d");

    // 🔵 INSERT PAYMENT
    $conn->query("INSERT INTO payment 
    (userid, amount, payment_date, paystatus, method, transid)
    VALUES 
    ('$userid','$amount','$today','Success','$method','$transid')");

    $payment_id = $conn->insert_id;

    // 🔴 UPDATE PASS (RENEWAL)
    $update = $conn->query("
    UPDATE pass SET
        routeid='$routeid',
        duration='$duration',
        applydate='$today',
        expiry=DATE_ADD('$today', INTERVAL $duration MONTH),
        amount='$amount',
        payment_id='$payment_id',
        status='Waiting',
        renew='true'
    WHERE passid='$passId'
    ");

    if($update){
        echo "<script>alert('Renewed Successfully');window.location='homeyyy.php';</script>";
    } else {
        echo "Error: ".$conn->error;
    }

    unset($_SESSION['renew_data']);
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
<input type="text" name="transid" placeholder="Enter Transaction ID" required>
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