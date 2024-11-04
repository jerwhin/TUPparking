<?php
session_start();
include 'db_connect.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch available parking slots
$query = "SELECT slot_id, slot_number FROM parking_slots WHERE status = 'available'";
$result = $conn->query($query);
$available_slots = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Available Parking Slots</title>
    <link rel="stylesheet" href="CSS/available_slot.css">
</head>
<body>
    <div class="slots-container">
        <h1>Available Parking Slots</h1>
        
        <?php if (empty($available_slots)): ?>
            <p>No available parking slots at the moment.</p>
        <?php else: ?>
            <ul>
                <?php foreach ($available_slots as $slot): ?>
                    <li>Slot Number: <?php echo htmlspecialchars($slot['slot_number']); ?></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>
</body>
</html>
