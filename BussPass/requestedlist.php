<?php
$conn = mysqli_connect("localhost", "root", "", "buspass");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Fetch requested users with all required columns
$query = "
SELECT 
    p.passid,
    p.userid,
    u.Username,
    p.passtype,
    p.routeid,
    r.source,
    r.destination,
    p.duration,
    r.amount
FROM pass p
JOIN user u ON p.userid = u.`userid`
JOIN route r ON p.routeid = r.routeid
WHERE p.status = 'waiting'
";

$result = mysqli_query($conn, $query);

if (!$result) {
    die("Query Failed: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Requested List</title>
    <style>
        body {
            font-family: Arial;
            background-color: #87CEEB;
            text-align: center;
        }

        h2 {
            margin-top: 20px;
        }

        table {
            width: 90%;
            margin: 20px auto;
            border-collapse: collapse;
            background-color: white;
        }

        th, td {
            border: 1px solid #333;
            padding: 10px;
        }

        th {
            background-color: #333;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
		 .back-btn {
            margin-top: 20px;
        }

        button {
            padding: 10px 20px;
            background-color: #000080;
            color: white;
            border: none;
            cursor: pointer;
        }

        button:hover {
            background-color: #555;
        }
    </style>
</head>
<body>

<h2>Requested Pass List</h2>

<table>
<tr>
    <th>Pass ID</th>
    <th>User ID</th>
    <th>Name</th>
    <th>Pass Type</th>
    <th>Route ID</th>
    <th>Source</th>
    <th>Destination</th>
    <th>Duration</th>
    <th>Amount</th>
</tr>

<?php
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>
                <td>".$row['passid']."</td>
                <td>".$row['userid']."</td>
                <td>".$row['Username']."</td>
                <td>".$row['passtype']."</td>
                <td>".$row['routeid']."</td>
                <td>".$row['source']."</td>
                <td>".$row['destination']."</td>
                <td>".$row['duration']." months</td>
                <td>".$row['amount']."</td>
              </tr>";
    }
} else {
    echo "<tr><td colspan='9'>No Requested Users Found</td></tr>";
}
?>

</table>
<div class="back-btn">
    <a href="adminHome.php"><button>Back</button></a>
</body>
</html>