<?php
$db_host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_name = 'buspass';

$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$pass_id = $_GET['passid'] ?? '';
$pass_data = null;
$status = 'EXPIRED'; // Default state

if ($pass_id) {
    $stmt = $conn->prepare("SELECT * FROM passes WHERE passid = ?");
    $stmt->bind_param("s", $passid);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $pass_data = $result->fetch_assoc();
      
        if (strtotime($pass_data['expiry_date']) > time()) {
            $status = 'ACTIVE';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        /* The Frosted Glass Card */
        .pass-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 25px;
            padding: 40px;
            width: 450px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
            color: white;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 40px;
        }
        .badges { display: flex; gap: 10px; }
        .badge {
            padding: 6px 14px;
            border-radius: 8px;
            font-weight: 800;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        /* Dynamic Opacity based on status */
        .active-btn { background: #48bb78; opacity: <?= ($status == 'ACTIVE') ? '1' : '0.3' ?>; }
        .expired-btn { background: #f56565; opacity: <?= ($status == 'EXPIRED') ? '1' : '0.3' ?>; }
        
        .info-group { margin: 15px 0; font-size: 20px; }
        .label { font-weight: 300; margin-right: 10px; color: rgba(255,255,255,0.8); }
        .footer-id { margin-top: 40px; font-size: 14px; color: rgba(255,255,255,0.6); }
    </style>
</head>
<body>

<div class="pass-card">
    <div class="header">
        <div class="badges">
            <div class="badge active-btn">Active</div>
            <div class="badge expired-btn">Expired</div>
        </div>
        <h2 style="margin: 0;">Bus Pass</h2>
    </div>

    <div class="info-group"><span class="label">Name:</span> <strong><?= htmlspecialchars($pass_data['name'] ?? '---') ?></strong></div>
    <div class="info-group"><span class="label">Route:</span> <strong><?= htmlspecialchars($pass_data['route'] ?? '---') ?></strong></div>
    <div class="info-group"><span class="label">Expiry:</span> <strong><?= htmlspecialchars($pass_data['expiry_date'] ?? '---') ?></strong></div>

    <div class="footer-id">Pass ID: <?= htmlspecialchars($pass_id) ?></div>
</div>

</body>
</html>