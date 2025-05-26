<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$current_user_id = $_SESSION['user_id'];
$selected_user_id = isset($_GET['user_id']) ? $_GET['user_id'] : null;

// Fetch conversation users (who messaged me or I messaged)
$users_result = $conn->query("
    SELECT DISTINCT u.id, u.full_name
    FROM users u
    JOIN messages m ON (u.id = m.sender_id OR u.id = m.receiver_id)
    WHERE (m.sender_id = $current_user_id OR m.receiver_id = $current_user_id)
      AND u.id != $current_user_id
");

// Mark all messages from chat_user_id to current_user_id as read

?>

<!DOCTYPE html>
<html>
<head>
    <title>Chatroom</title>
    <style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f0f2f5;
        margin: 0;
        display: flex;
        height: 100vh;
    }
    .sidebar {
        width: 220px;
        background-color: #fff;
        border-right: 1px solid #ddd;
        overflow-y: auto;
        padding: 10px;
    }
    .sidebar h3 {
        margin-top: 0;
        text-align: center;
        color: #333;
    }
    .user-item {
        padding: 10px;
        margin: 5px 0;
        background-color: #f7f7f7;
        border-radius: 8px;
        cursor: pointer;
        transition: background 0.2s;
        text-align: center;
    }
    .user-item:hover {
        background-color: #e2e2e2;
    }
    .chat-container {
        flex: 1;
        display: flex;
        justify-content: center;
        align-items: center;
    }
    .chat-box {
        width: 500px;
        height: 600px;
        background-color: #fff;
        border: 1px solid #ddd;
        border-radius: 10px;
        display: flex;
        flex-direction: column;
        overflow: hidden;
    }
    
    .messages {
    overflow-y: scroll;
    background-color: #f9f9f9;
    display: flex;
    flex-direction: column;
    padding: 10px;
    row-gap: 8px;
}

.message-bubble {
    max-width: 70%;
    padding: 10px 15px;
    border-radius: 20px;
    word-wrap: break-word;
    opacity: 100;
    /* animation: fadeIn 0.3s forwards; */
}

.message-mine {
    align-self: flex-end;
    background-color: #6CF9FF;
}

.message-other {
    align-self: flex-start;
    background-color: #d0f0c0;
}

    form {
        display: flex;
        padding: 10px;
        border-top: 1px solid #ddd;
        background-color: #f7f7f7;
    }
    form input[type="text"] {
        flex: 1;
        padding: 10px 15px;
        border: 1px solid #ddd;
        border-radius: 20px;
        margin-right: 10px;
        outline: none;
    }
    form button {
        padding: 10px 20px;
        border: none;
        background-color: #007bff;
        color: #fff;
        border-radius: 20px;
        cursor: pointer;
        transition: background 0.2s;
    }
    form button:hover {
        background-color: #0056b3;
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    </style>
</head>
<body>
<div style="position: fixed; top: 10px; left: 10px;">
    <button onclick="goBack()" style="padding: 8px 12px; font-size: 14px; cursor: pointer;">
        ← Back
    </button>
</div>
<div class="sidebar">
    <h3>Chats</h3>
    <?php while ($u = $users_result->fetch_assoc()): ?>
        <a href="?user_id=<?php echo $u['id']; ?>" style="text-decoration: none; color: inherit; display: block;">
    <div class="user-item">
        <?php echo htmlspecialchars($u['full_name']); ?>
    </div>
</a>

    <?php endwhile; ?>
</div>

<div class="chat-container">
    <div class="chat-box" >
        <?php if ($selected_user_id): ?>
            <div id="messages" class="messages" ></div>

            <form id="sendForm">
                <input type="hidden" name="receiver_id" value="<?php echo $selected_user_id; ?>">
                <input type="text" name="message" placeholder="Type a message..." required>
                <button type="submit">Send</button>
            </form>
        <?php else: ?>
            <div style="text-align: center; margin: auto; color: #777;">
                Select a user on the left to start chatting.
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
function fetchMessages() {
    const xhr = new XMLHttpRequest();
    xhr.onload = function() {
        const messagesDiv = document.getElementById('messages');
        
        // Save current scroll position
        const scrollTopBefore = messagesDiv.scrollTop;
        const scrollHeightBefore = messagesDiv.scrollHeight;

        messagesDiv.innerHTML = this.responseText;

        // Calculate how much the scrollHeight changed
        const scrollHeightAfter = messagesDiv.scrollHeight;
        const scrollDifference = scrollHeightAfter - scrollHeightBefore;

        // Keep the scroll position stable (no automatic jump)
        messagesDiv.scrollTop = scrollTopBefore + scrollDifference;
    };
    xhr.open("GET", "fetch_messages.php?user_id=<?php echo $selected_user_id; ?>", true);
    xhr.send();
}

function goBack() {
    window.location.href = '../pages/home.html'; // change 'home.php' to your actual homepage file
}
<?php if ($selected_user_id): ?>
setInterval(fetchMessages, 200);
fetchMessages();

document.getElementById('sendForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const xhr = new XMLHttpRequest();
    xhr.onload = function() {
        fetchMessages();
        e.target.message.value = '';
    };
    xhr.open("POST", "send_message.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    const params = new URLSearchParams(new FormData(e.target)).toString();
    xhr.send(params);
});
<?php endif; ?>

</script>
</body>
</html>
