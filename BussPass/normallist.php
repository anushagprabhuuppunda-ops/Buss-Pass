<!DOCTYPE html>
<html>
<head>
    <title>Normal List</title>
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
            margin: 20px auto;
            border-collapse: collapse;
            width: 80%;
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
<h2>Normal Pass List</h2>
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
    <th>Status</th>
    </tr>


<div class="back-btn">
    <a href="adminHome.php"><button>Back</button></a>
</div>

<?php
$cn = mysqli_connect('localhost','root','','buspass');
if(!$cn){
    die("Connection failed: ".mysqli_connect_error());
}
$pass_query = "SELECT passid, userid, routeid, passtype, status, duration FROM pass";
$pass_result = mysqli_query($cn, $pass_query);

while($pass = mysqli_fetch_assoc($pass_result))
{
    $userid = $pass['userid'];
    $routeid = $pass['routeid'];

    $user_query = "SELECT Username FROM user WHERE `userid` = $userid";
    $user_result = mysqli_query($cn, $user_query);
    $user_data = mysqli_fetch_assoc($user_result);

    $route_query = "SELECT source, destination, amount FROM route WHERE routeid = $routeid";
    $route_result = mysqli_query($cn, $route_query);
    $route = mysqli_fetch_assoc($route_result);

$status = $pass['status'];
$color = "black";

if($status == "Approved"){
    $color = "green";
} elseif($status == "Rejected"){
    $color = "red";
}


  echo "<tr>
    <td>".$pass['passid']."</td>
    <td>".$pass['userid']."</td>
    <td>".$user_data['Username']."</td>
    <td>".$pass['passtype']."</td>
    <td>".$pass['routeid']."</td>
    <td>".$route['source']."</td>
    <td>".$route['destination']."</td>
    <td>".$pass['duration']." months</td>
    <td>".$route['amount']."</td>
    <td style='color: ".$color."; font-weight:bold;'>".$status."</td>
</tr>";
}

?>
</table>
</body>
</html>