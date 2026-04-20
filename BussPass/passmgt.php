<?php
$cn = mysqli_connect('localhost','root','','buspass');
if(!$cn){
    die("Connection failed: ".mysqli_connect_error());
}

// APPROVE / REJECT LOGIC (UPDATE PASS TABLE)
if(isset($_GET['action']) && isset($_GET['id'])) {
    $id = $_GET['id']; // this is passid
    $action = $_GET['action'];

    if($action == "approve") {
        mysqli_query($cn, "UPDATE pass SET status='Approved', expiry=DATE_ADD(CURDATE(), INTERVAL duration MONTH), approved_date=CURDATE() WHERE passid='$id'");
    } 
    elseif($action == "reject") {
        mysqli_query($cn, "UPDATE pass SET status='Rejected' WHERE passid='$id'");
    }
}

// FETCH DATA (JOIN pass + payment + user)
$query = "
SELECT p.passid, p.userid, pay.transid, pay.method, pay.paystatus, p.status
FROM pass p
JOIN payment pay ON p.payment_id = pay.payment_id
JOIN user u ON p.userid = u.userid
";

$result = mysqli_query($cn, $query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Pass management</title>
    <style>
        body {
            font-family: Arial;
            background-color: #87CEEB;
            text-align: center;
        }

        table {
            margin: 20px auto;
            border-collapse: collapse;
            width: 70%;
            background-color: white;
        }

        table, th, td {
            border: 1px solid #333;
        }

        th {
            background-color: #333;
            color: white;
            padding: 10px;
        }

        td {
            padding: 10px;
        }

        button {
            padding: 5px 10px;
            margin: 2px;
            border: none;
            cursor: pointer;
        }

        .approve { background-color: green; color: white; }
        .reject { background-color: red; color: white; }
    </style>
</head>
<body>

<h2>Pass Management</h2>

<table>
<tr>
    <th>User ID</th>
    <th>Transaction ID</th>
    <th>Payment Mode</th>
    <th>Payment Status</th>
    <th>Pass Status</th>
    <th>Action</th>
</tr>

<?php
while($row = mysqli_fetch_assoc($result)) {
    echo "<tr>
        <td>{$row['userid']}</td>
        <td>{$row['transid']}</td>
        <td>{$row['method']}</td>
        <td>{$row['paystatus']}</td>
        <td>{$row['status']}</td>
        <td>
            <a href='?action=approve&id={$row['passid']}'>
                <button class='approve'>Approve</button>
            </a>
            <a href='?action=reject&id={$row['passid']}'>
                <button class='reject'>Reject</button>
            </a>
        </td>
    </tr>";
}
?>

</table>

</body>
</html>