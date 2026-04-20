<?php
$db_host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_name = 'buspass';

$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$pass_id = trim($_POST['passid'] ?? $_GET['passid'] ?? '');
$pass_data = null;
$status = 'waiting'; 
$message = '';

if ($pass_id) {
    // Single optimized query using LEFT JOIN to get route details
    $stmt = $conn->prepare("SELECT p.*, r.source, r.destination FROM pass p LEFT JOIN route r ON p.routeid = r.routeid WHERE p.passid = ?");
    $stmt->bind_param("s", $pass_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $pass_data = $result->fetch_assoc();
        
        // Logic to determine status
        if (!empty($pass_data['status'])) {
            $status = strtolower($pass_data['status']); 
        } elseif (!empty($pass_data['expiry']) && strtotime($pass_data['expiry']) > time()) {
            $status = 'approved'; // Map active to approved for printing logic
        } else {
            $status = 'expired';
        }
    } else {
        $message = 'No pass found for the provided Pass ID.';
    }
} else {
    $message = 'Pass ID is required to check status.';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Bus Pass Status</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }
        .pass-card {
            background: rgba(39, 4, 4, 0.57);
            backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 25px;
            padding: 40px;
            width: 450px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
            color: white;
        }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 40px; }
        .badge {
            padding: 6px 14px;
            border-radius: 8px;
            font-weight: 800;
            font-size: 11px;
            text-transform: uppercase;
            display: none;
        }
        /* Status Color Logic */
        .active-btn { background: #48bb78; display: <?= ($status == 'approved' || $status == 'active') ? 'inline-block' : 'none' ?>; }
        .expired-btn { background: #f56565; display: <?= ($status == 'expired') ? 'inline-block' : 'none' ?>; }
        .waiting-btn { background: #ed8936; display: <?= ($status == 'waiting') ? 'inline-block' : 'none' ?>; }
        .rejected-btn { background: #e53e3e; display: <?= ($status == 'rejected') ? 'inline-block' : 'none' ?>; }
        
        .details { display: flex; gap: 24px; }
        .info-column { flex: 1; }
        .info-group { margin: 12px 0; font-size: 16px; }
        .label { font-weight: 500; color: rgba(255,255,255,0.7); display: block; font-size: 12px; }
        .pass-photo { width: 120px; height: 150px; object-fit: cover; border-radius: 12px; }

        /* PRINT STYLES */
        @media print {
            body { background: none !important; }
            .no-print { display: none !important; }
            .pass-card {
                background: lavender !important;
                color: black !important;
                border: 2px solid #000 !important;
                box-shadow: none !important;
                backdrop-filter: none !important;
                -webkit-print-color-adjust: exact !important; /* Chrome, Safari, Edge */
                print-color-adjust: exact !important;
            }
            .label { color: #333 !important; }
        }
    </style>
</head>
<body>

<div id="passArea">
    <?php if ($pass_data): ?>
    <div class="pass-card">
        <div class="header">
            <div class="badges">
                <div class="badge active-btn">Approved</div>
                <div class="badge expired-btn">Expired</div>
                <div class="badge waiting-btn">Waiting</div>
                <div class="badge rejected-btn">Rejected</div>
            </div>
            <h2 style="margin: 0;">Bus Pass</h2>
        </div>

        <div class="details">
            <div class="info-column">
                <div class="info-group"><span class="label">NAME</span> <strong><?= htmlspecialchars($pass_data['name']) ?></strong></div>
                <div class="info-group"><span class="label">ROUTE</span> <?= htmlspecialchars($pass_data['source']) ?> ➔ <?= htmlspecialchars($pass_data['destination']) ?></div>
                <div class="info-group"><span class="label">EXPIRY</span> <?= date('d-M-Y', strtotime($pass_data['expiry'])) ?></div>
            </div>
            <?php if (!empty($pass_data['photo'])): ?>
                <img src="uploads/<?= htmlspecialchars($pass_data['photo']) ?>" class="pass-photo">
            <?php endif; ?>
        </div>
        <div style="margin-top:20px; font-size: 10px; opacity: 0.6;">Pass ID: <?= htmlspecialchars($pass_id) ?></div>
    </div>
    <?php else: ?>
        <p style="color: white;"><?= $message ?></p>
    <?php endif; ?>
</div>

<?php if($status === 'approved' || $status === 'active'): ?>
    <button class="no-print" onclick="window.print()" style="background:#48bb78; margin-top: 20px; padding: 10px 30px; border-radius: 5px; border: none; cursor: pointer; font-weight: bold;">
        Print
    </button>
<?php endif; ?>

</body>
</html>