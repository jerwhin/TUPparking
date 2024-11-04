<?php
session_start();
include 'db_connect.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Handle sending messages
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['message'], $_POST['receiver_id'])) {
    $message = $_POST['message'];
    $receiver_id = intval($_POST['receiver_id']); // Ensure receiver_id is an integer
    $sender_id = $_SESSION['user_id'];

    $stmt = $conn->prepare("INSERT INTO chat_messages (sender_id, receiver_id, message, timestamp) VALUES (?, ?, ?, NOW())");
    $stmt->bind_param("iis", $sender_id, $receiver_id, $message);
    if ($stmt->execute()) {
        // Optionally, handle success
    } else {
        // Optionally, handle error
        echo "Error: " . $stmt->error;
    }
}

// Fetch users for the admin to chat with
$user_id = $_SESSION['user_id'];
$users_query = $conn->query("SELECT id, username FROM users WHERE id != $user_id");
$users = $users_query->fetch_all(MYSQLI_ASSOC);

// Handle chat loading
$current_user_id = isset($_GET['chat_with']) ? intval($_GET['chat_with']) : null;
$messages = [];
$username = '';
if ($current_user_id) {
    // Get the username for the current user to display
    $user_stmt = $conn->prepare("SELECT username FROM users WHERE id = ?");
    $user_stmt->bind_param("i", $current_user_id);
    $user_stmt->execute();
    $user_stmt->bind_result($username);
    $user_stmt->fetch();
    $user_stmt->close();

    // Fetch messages
    $stmt = $conn->prepare("SELECT sender_id, message, timestamp FROM chat_messages WHERE (sender_id = ? AND receiver_id = ?) OR (sender_id = ? AND receiver_id = ?) ORDER BY timestamp");
    $stmt->bind_param("iiii", $user_id, $current_user_id, $current_user_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $messages = $result->fetch_all(MYSQLI_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat</title>
    <link rel="stylesheet" href="CSS/user_chat.css">
</head>
<body>
    <!-- Navigation -->
    <header>
        <h1>Your Chat</h1>
        <nav>
            <ul>
                <li><a href="user_dashboard.php">Dashboard</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>

    <div class="chat-container">
        <div class="user-list">
            <h2>Users</h2>
            <ul>
                <?php foreach ($users as $user): ?>
                    <li><a href="?chat_with=<?php echo $user['id']; ?>"><?php echo htmlspecialchars($user['username']); ?></a></li>
                <?php endforeach; ?>
            </ul>
        </div>

        <div class="chat-box">
            <?php if ($current_user_id): ?>
                <h2>Chat with <?php echo htmlspecialchars($username); ?></h2>
                <div class="messages">
                    <?php foreach ($messages as $msg): ?>
                        <div class="<?php echo ($msg['sender_id'] == $user_id) ? 'message-sent' : 'message-received'; ?>">
                            <span class="timestamp"><?php echo date('H:i', strtotime($msg['timestamp'])); ?></span>
                            <p><?php echo htmlspecialchars($msg['message']); ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>

                <form method="POST">
                    <input type="hidden" name="receiver_id" value="<?php echo $current_user_id; ?>">
                    <input type="text" name="message" required placeholder="Type your message...">
                    <input type="submit" value="Send">
                </form>
            <?php else: ?>
                <h2>Select a user to chat</h2>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
