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
    <link rel="stylesheet" href="CSS/user_dashboard.css"> <!-- External CSS for styling -->
</head>
<body>
    <div class="dashboard-container">
        <header>    
            <h1>Welcome, <?php echo $_SESSION['username']; ?>!</h1>

            <!-- Navigation options (Logout, Generate QR) -->
            <nav class="header-right">
                <ul>
                    <li><a href="generate_qr.php">Generate QR Code</a></li>
                    <li><a href="user_chat.php">Chat</a></li> <!-- Chat page link -->
                    <li><a href="available_slot.php">Available Slot</a></li> 
                    <li><a href="logout.php">Logout</a></li> <!-- Log out button -->
                </ul>
            </nav>
        </header>

        <main>
            <p>Here you can generate a QR code for parking.</p>

            <!-- QR code generation form -->
            <form action="generate_qr.php" method="POST">
                <input type="submit" value="Generate QR Code">
            </form>
        </main>
    </div>
</body>
</html>
