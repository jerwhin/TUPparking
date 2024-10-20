<?php
session_start();

// Check if a user is logged in
if (isset($_SESSION['user_id'])) {
    // Destroy the session and log out the user
    session_destroy();
    header("Location: login.php"); // Redirect back to login page
    exit();
} else {
    // If no session, redirect to the login page
    header("Location: login.php");
    exit();
}
?>
