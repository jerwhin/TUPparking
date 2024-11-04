<?php
session_start();
include 'db_connect.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch user's parking records
$user_id = $_SESSION['user_id'];
$sql = "SELECT pr.slot_id, ps.slot_number, pr.time_in, pr.time_out 
        FROM parking_records pr 
        JOIN parking_slots ps ON pr.slot_id = ps.slot_id 
        WHERE pr.user_id = ? 
        ORDER BY pr.time_in DESC"; // Sort by time_in
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$records = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parking History</title>
    <link rel="stylesheet" href="CSS/history.css"> <!-- Link to the new CSS file -->
</head>
<body>
<div class="common-container">
    <header>
        <h1>Your Parking History</h1>
        <nav>
            <ul>
                <li><a href="admin_dashboard.php">Dashboard</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <?php if ($records->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Slot Number</th>
                        <th>Time In</th>
                        <th>Time Out</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($record = $records->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($record['slot_number']) ?></td>
                            <td><?= htmlspecialchars($record['time_in']) ?></td>
                            <td><?= $record['time_out'] ? htmlspecialchars($record['time_out']) : 'Still parked' ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No parking history found.</p>
        <?php endif; ?>

        <div class="button-group">
            <a href="admin_dashboard.php" class="button">Back to Dashboard</a>
        </div>
    </main>
</div>
</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
