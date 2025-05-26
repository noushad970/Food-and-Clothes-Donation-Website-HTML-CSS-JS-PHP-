<?php
session_start();
include 'db.php';
$sender_id = $_SESSION['user_id'];
$receiver_id = $_GET['user_id'];

$result = $conn->query("
    SELECT * FROM messages
    WHERE (sender_id = $sender_id AND receiver_id = $receiver_id)
       OR (sender_id = $receiver_id AND receiver_id = $sender_id)
    ORDER BY created_at ASC
");

$update_query = "UPDATE messages 
                 SET is_read = 1 
                 WHERE sender_id = $receiver_id 
                   AND receiver_id = $sender_id 
                   AND is_read = 0";
$conn->query($update_query);

while ($row = $result->fetch_assoc()) {
    $isMine = $row['sender_id'] == $sender_id;
    $bubbleClass = $isMine ? "message-bubble message-mine" : "message-bubble message-other";
    $icon = $isMine ? "☻" : "☺";

    echo "<div class='$bubbleClass'>
            <b>$icon</b> " . htmlspecialchars($row['message']) . "
        </div>";
}
?>
