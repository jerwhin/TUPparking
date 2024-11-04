    <?php
    session_start();
    include 'db_connect.php';

    // Check if user is logged in and is an admin
    if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
        header("Location: login.php");
        exit();
    }

    // Fetch all users from the database
    $sql = "SELECT id, username, role FROM users";
    $result = $conn->query($sql);
    ?>

    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>View Users</title>
        <link rel="stylesheet" href="CSS/view_users.css"> <!-- Link to your CSS -->
    </head>
    <body>
        <div class="dashboard-container">
            <header>
                <h1>View Users</h1>
                <nav>
                    <ul>
                        <li><a href="admin_dashboard.php">Dashboard</a></li> <!-- New Dashboard link -->
                        <li><a href="logout.php">Logout</a></li>
                    </ul>
                </nav>
            </header>

            <main>
                <h2>Registered Users</h2>
                <?php if ($result->num_rows > 0): ?>
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Username</th>
                                <th>Role</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['id']); ?></td>
                                    <td><?php echo htmlspecialchars($row['username']); ?></td>
                                    <td><?php echo htmlspecialchars($row['role']); ?></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>No users found.</p>
                <?php endif; ?>
            </main>
        </div>
    </body>
    </html>

    <?php
    // Close the database connection
    $conn->close();
    ?>
