<?php
session_start();
include 'db_connect.php';

// Check if user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="CSS/admin_dashboard.css">
    <link href="https://fonts.googleapis.com/css2?family=Lexend+Exa&display=swap" rel="stylesheet">

</head>
<body>
    <div class="dashboard-container">
        <header>
            <h1>Admin Dashboard</h1>
            <nav>
                <ul>
                    <li><a href="map.php">Map</a></li>
                    <li><a href="view_users.php">Users</a></li>
                    <li><a href="history.php">History</a></li>
                    <li><a href="admin_chat.php">Chats</a></li>
                    <li><a href="manage_parking.php">Manage Parking</a></li>
                    <li><a href="logout.php">Logout</a></li>
                </ul>
            </nav>
        </header>

        <main>
            <h2>Welcome, Admin!</h2>
            <p>Here, you can manage users, view parking details, and more.</p>

            <!-- Add more sections here as needed -->
        </main>
    </div>
</body>
</html>
