<?php
session_start();
include 'db_connect.php';

// Function to park a vehicle
function parkVehicle($conn, $slot_id) {
    $user_id = $_SESSION['user_id'];

    // Insert parking record
    $sql = "INSERT INTO parking_records (slot_id, user_id) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ii', $slot_id, $user_id);

    if ($stmt->execute()) {
        echo "Vehicle parked successfully!";
    } else {
        echo "Error parking vehicle: " . $stmt->error;
    }
}

// Function to unpark a vehicle
function unparkVehicle($conn, $slot_id) {
    $user_id = $_SESSION['user_id'];

    // Update the time_out for the parking record
    $sql = "UPDATE parking_records SET time_out = NOW() WHERE slot_id = ? AND user_id = ? AND time_out IS NULL";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ii', $slot_id, $user_id);

    if ($stmt->execute()) {
        echo "Vehicle unparked successfully!";
    } else {
        echo "Error unparking vehicle: " . $stmt->error;
    }
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['park'])) {
        $slot_id = $_POST['slot_id'];
        parkVehicle($conn, $slot_id);
    } elseif (isset($_POST['unpark'])) {
        $slot_id = $_POST['slot_id'];
        unparkVehicle($conn, $slot_id);
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
    <link rel="stylesheet" href="CSS/styles.css">
</head>
<body>
    <div class="container">
        <h2>Manage Parking Slots</h2>

        <h3>Available Parking Slots</h3>
        <form method="POST">
            <select name="slot_id" required>
                <option value="">Select a slot</option>
                <?php while ($row = $result->fetch_assoc()) : ?>
                    <option value="<?= $row['slot_id'] ?>"><?= $row['slot_number'] ?></option>
                <?php endwhile; ?>
            </select>
            <input type="submit" name="park" value="Park Vehicle">
            <input type="submit" name="unpark" value="Unpark Vehicle">
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

        if ($records->num_rows > 0) {
            echo "<table>
                    <tr>
                        <th>Slot Number</th>
                        <th>Time In</th>
                        <th>Time Out</th>
                    </tr>";
            while ($record = $records->fetch_assoc()) {
                echo "<tr>
                        <td>{$record['slot_number']}</td>
                        <td>{$record['time_in']}</td>
                        <td>" . ($record['time_out'] ? $record['time_out'] : 'Still parked') . "</td>
                    </tr>";
            }
            echo "</table>";
        } else {
            echo "No parking records found.";
        }
        ?>
    </div>
</body>
</html>
