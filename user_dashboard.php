<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <link rel="stylesheet" href="CSS/dashboard.css"> <!-- Link to the CSS file -->
</head>
<body>
    <div class="dashboard-container">
        <header>
            <h1>Welcome, <?php echo $_SESSION['username']; ?>!</h1>
            <nav>
                <ul>
                    <li><a href="manage_parking.php">Manage Parking</a></li>
                    <li><a href="generate_qr.php">Generate QR Code</a></li>
                    <li><a href="logout.php">Logout</a></li>
                </ul>
            </nav>
        </header>

        <main>
            <p>Here you can generate a QR code for parking.</p>
        </main>
    </div>
</body>
</html>
