<?php
// Start the session if it's not already started
session_start();

// Check if the user is logged in (optional, adjust as needed)
if (!isset($_SESSION['username'])) {
    header('Location: login.php'); // Redirect to login if not logged in
    exit();
}

// Set the message
$message = "";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Map</title>
    <link rel="stylesheet" href="CSS/map.css"> <!-- Link to your CSS file -->
</head>
<body>
    <div class="dashboard-container">
        <header>
            <h1>Map View</h1>
            <nav>
                <ul>
                    <li><a href="admin_dashboard.php">Dashboard</a></li>
                    <li><a href="logout.php">Logout</a></li>
                </ul>
            </nav>
        </header>
        
        <main>
        
            <p class="map-message"><?php echo $message; ?></p>
        </main>
    </div>
</body>
</html>
