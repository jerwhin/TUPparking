<?php
session_start();
include 'db_connect.php';
include 'phpqrcode/qrlib.php';  // Ensure this points to the correct qrlib.php location

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $qr_code_data = 'PARKING-' . uniqid();  // Generate unique QR code data

    // Insert QR code data into the database
    $sql = "INSERT INTO qr_codes (user_id, qr_code) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('is', $user_id, $qr_code_data);
    
    if ($stmt->execute()) {
        // Path where QR code image will be saved
        $qr_file = 'qr_codes/' . $user_id . '.png';
        
        // Generate QR code image
        QRcode::png($qr_code_data, $qr_file);

        // Debug lines to check the QR code generation
        echo "QR Code generated successfully! <br>";
        echo "QR File Path: " . $qr_file . "<br>"; // Check the file path
        
        if (file_exists($qr_file)) {
            echo "<img src='qr_codes/" . $user_id . ".png' alt='QR Code'><br>"; // Display the QR code
        } else {
            echo "QR Code file not found.";
        }
    } else {
        echo "Error generating QR code.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generate QR Code</title>
</head>
<body>
    <h2>Your QR Code</h2>
    <a href="user_dashboard.php">Back to Dashboard</a>
</body>
</html>
