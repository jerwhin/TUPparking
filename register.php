<?php
include 'db_connect.php';

// Define a secret admin access code
$admin_access_code = "security";  // You can replace this with your own secure code

// Initialize role variable
$role = 'user';  // Default value

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    // Capture the selected role
    if (isset($_POST['role'])) {
        $role = $_POST['role'];
    }

    // If the user selected "admin", validate the admin access code
    if ($role == 'admin') {
        $input_admin_code = $_POST['admin_code'] ?? ''; // Use null coalescing operator to avoid undefined index
        if ($input_admin_code !== $admin_access_code) {
            echo "<div class='alert error'>Invalid Admin Access Code!</div>";
            exit();  // Stop the registration if the admin code is incorrect
        }
    }

    // Hash the password for security
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Check if the username already exists
    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<div class='alert error'>Username already exists!</div>";
    } else {
        // Insert new user into the database with the selected role
        $sql = "INSERT INTO users (username, password, role) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('sss', $username, $hashed_password, $role);
        
        if ($stmt->execute()) {
            echo "<div class='alert success'>Account created successfully! You can now <a href='login.php'>login here</a>.</div>";
        } else {
            echo "<div class='alert error'>Error: " . $stmt->error . "</div>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="CSS/register.css">
    <script>
        function toggleAdminCode() {
            var role = document.getElementById('role').value;
            var adminCodeField = document.getElementById('admin_code_field');
            adminCodeField.style.display = role === 'admin' ? 'block' : 'none';
        }
    </script>
</head>
<body>
    <div class="container">
        <h2>Create an Account</h2>
        <form action="register.php" method="POST">
            <label for="username">Username:</label>
            <input type="text" name="username" required>

            <label for="password">Password:</label>
            <input type="password" name="password" required>

            <label for="role">Select Role:</label>
            <select name="role" id="role" onchange="toggleAdminCode()" required>
                <option value="user">User</option>
                <option value="admin">Admin</option>
            </select>

            <div id="admin_code_field" style="display: none;">
                <label for="admin_code">Admin Access Code:</label>
                <input type="password" name="admin_code" placeholder="Enter Admin Code">
            </div>

            <input type="submit" value="Register">
        </form>

        <div class="button-group">
            <a href="login.php" class="button">Already have an account? Log in here</a>
        </div>
    </div>
</body>
</html>
