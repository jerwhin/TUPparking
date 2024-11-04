<?php
session_start();
include 'db_connect.php';

// Check if user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Function to park a vehicle
function parkVehicle($conn, $slot_id) {
    $user_id = $_SESSION['user_id'];
    $sql = "INSERT INTO parking_records (slot_id, user_id, time_in) VALUES (?, ?, NOW())";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ii', $slot_id, $user_id);

    if ($stmt->execute()) {
        return "<div class='alert success'>Vehicle parked successfully!</div>";
    } else {
        return "<div class='alert error'>Error parking vehicle: " . $stmt->error . "</div>";
    }
}

// Function to unpark a vehicle
function unparkVehicle($conn, $slot_id) {
    $user_id = $_SESSION['user_id'];
    $sql = "UPDATE parking_records SET time_out = NOW() WHERE slot_id = ? AND user_id = ? AND time_out IS NULL";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ii', $slot_id, $user_id);

    if ($stmt->execute()) {
        return "<div class='alert success'>Vehicle unparked successfully!</div>";
    } else {
        return "<div class='alert error'>Error unparking vehicle: " . $stmt->error . "</div>";
    }
}

// Handle form submission
$message = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['park'])) {
        $slot_id = $_POST['slot_id'];
        $message = parkVehicle($conn, $slot_id);
    } elseif (isset($_POST['unpark'])) {
        $slot_id = $_POST['slot_id'];
        $message = unparkVehicle($conn, $slot_id);
    }
}

// Fetch available parking slots
$sql = "SELECT * FROM parking_slots WHERE status = 'available'";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Parking</title>
    <link rel="stylesheet" href="CSS/manage_parking.css"> <!-- Use the same CSS for consistency -->
</head>
<body>
    <div class="admin-dashboard-container"> <!-- Changed this to match your CSS -->
        <header>
            <h1>Manage Parking Slots</h1>
            <div class="header-buttons">
                <a href="admin_dashboard.php" class="button">Dashboard</a>
                <a href="logout.php" class="button">Logout</a>
            </div>
        </header>

        <main>
            <?php if ($message): ?>
                <?php echo $message; ?>
            <?php endif; ?>

            <h2>Available Parking Slots</h2>
            <form method="POST">
                <label for="slot_id">Select a slot:</label>
                <select name="slot_id" id="slot_id" required>
                    <option value="">Select a slot</option>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <option value="<?= htmlspecialchars($row['slot_id']) ?>"><?= htmlspecialchars($row['slot_number']) ?></option>
                    <?php endwhile; ?>
                </select>
                <div class="button-group">
                    <input type="submit" name="park" value="Park Vehicle">
                    <input type="submit" name="unpark" value="Unpark Vehicle">
                </div>
            </form>

            <h3>Your Parking Records</h3>
            <?php
            // Fetch user's parking records
            $user_id = $_SESSION['user_id'];
            $sql = "SELECT pr.slot_id, ps.slot_number, pr.time_in, pr.time_out 
                    FROM parking_records pr 
                    JOIN parking_slots ps ON pr.slot_id = ps.slot_id 
                    WHERE pr.user_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('i', $user_id);
            $stmt->execute();
            $records = $stmt->get_result();

            if ($records->num_rows > 0): ?>
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
                <p>No parking records found.</p>
            <?php endif; ?>
        </main>
    </div>
</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
