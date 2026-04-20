<?php
session_start();
$conn = new mysqli("localhost", "root", "", "buspass");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_id = $_SESSION['user_id'] ?? 0;

if (!$user_id) {
    echo "<script>alert('Please login first'); window.location.href='loginFront.php';</script>";
    exit();
}

// Fetch user's passes
$query = "SELECT p.passid, p.status, p.applydate, p.approved_date, p.expiry, r.source, r.destination, p.duration
          FROM pass p
          LEFT JOIN route r ON p.routeid = r.routeid
          WHERE p.userid = ?
          ORDER BY p.applydate DESC";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$passes = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Bus Passes</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #879cbb;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 800px;
            margin: auto;
            background-color: rgba(215, 186, 186, 0.7);
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.7);
        }
        h2 {
            text-align: center;
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
        }
        .status-approved { color: green; }
        .status-rejected { color: red; }
        .status-waiting { color: orange; }
        .status-expired { color: gray; }
        .back-btn {
            display: inline-block;
            margin-bottom: 20px;
            padding: 10px 20px;
            background-color: #008000;
            color: white;
            text-decoration: none;
            border-radius: 4px;
        }
        .back-btn:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="homeyyy.php" class="back-btn">Back to Home</a>
        <h2>My Bus Passes</h2>
        <?php if (empty($passes)): ?>
            <p>You have not applied for any passes yet.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Pass ID</th>
                        <th>Source</th>
                        <th>Destination</th>
                        <th>Duration</th>
                        <th>Applied Date</th>
                        <th>Approved Date</th>
                        <th>Expiry</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($passes as $pass): ?>
                        <tr>
                            <td><?= htmlspecialchars($pass['passid']) ?></td>
                            <td><?= htmlspecialchars($pass['source'] ?? 'N/A') ?></td>
                            <td><?= htmlspecialchars($pass['destination'] ?? 'N/A') ?></td>
                            <td><?= htmlspecialchars($pass['duration']) ?> Month(s)</td>
                            <td><?= $pass['applydate'] ? date('d-m-Y', strtotime($pass['applydate'])) : 'N/A' ?></td>
                            <td><?= $pass['approved_date'] ? date('d-m-Y', strtotime($pass['approved_date'])) : 'N/A' ?></td>
                            <td><?= $pass['expiry'] ? date('d-m-Y', strtotime($pass['expiry'])) : 'N/A' ?></td>
                            <td class="status-<?= strtolower($pass['status'] ?? 'waiting') ?>">
                                <?= htmlspecialchars(ucfirst($pass['status'] ?? 'Waiting')) ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</body>
</html>