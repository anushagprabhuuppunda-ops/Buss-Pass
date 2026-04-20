<?php
$cn = mysqli_connect('localhost','root','','buspass');
if(!$cn){
    die("Connection failed: ".mysqli_connect_error());
}

// APPROVE / REJECT LOGIC
if(isset($_GET['action']) && isset($_GET['id'])) {
    $id = intval($_GET['id']); // safe
    $action = $_GET['action'];

    if($action == "approve") {
        mysqli_query($cn, "UPDATE pass SET status='Approved', renew='False' WHERE passid=$id");
    } 
    elseif($action == "reject") {
        mysqli_query($cn, "UPDATE pass SET status='Rejected', renew='False' WHERE passid=$id");
    }

    // Prevent repeat action on refresh
    header("Location: ".$_SERVER['PHP_SELF']);
    exit();
}

// FETCH ONLY RENEWAL REQUESTS
$query = "
SELECT p.passid, p.userid, pay.transid, pay.method, pay.paystatus, p.status
FROM pass p
JOIN payment pay ON p.payment_id = pay.payment_id
JOIN user u ON p.userid = u.userid
WHERE p.renew = 'True'
";

$result = mysqli_query($cn, $query);

if(!$result){
    die("Query Failed: ".mysqli_error($cn));
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Renewal Management</title>
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

<h2>Renewal Management</h2>

<table>
<tr>
    <th>User ID</th>
    <th>Transaction ID</th>
    <th>Payment Mode</th>
    <th>Payment Status</th>
    <th>Action</th>
</tr>

<?php
while($row = mysqli_fetch_assoc($result)) {
    echo "<tr>
        <td>{$row['userid']}</td>
        <td>{$row['transid']}</td>
        <td>{$row['method']}</td>
        <td>{$row['paystatus']}</td>
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