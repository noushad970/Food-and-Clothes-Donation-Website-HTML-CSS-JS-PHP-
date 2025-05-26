

<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "donation_website";

// Connect to MySQL
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die(json_encode(['error' => 'Connection failed']));
}

// Get receiver ID from GET or POST
$receiver_id = isset($_GET['receiver_id']) ? intval($_GET['receiver_id']) : 0;
$user = $_SESSION['user_id'];

$sql = "SELECT sender_id, message FROM messages 
WHERE (sender_id=? AND receiver_id=?) OR (sender_id=? AND receiver_id=?) 
ORDER BY created_at";
$stmt = $conn->prepare($sql);

$stmt->bind_param("iiii", $user, $receiver, $receiver, $user);
$stmt->execute();
$res = $stmt->get_result();

$messages = [];
while ($row = $result->fetch_assoc()) {
  
    $messages[] = [
      'message' => $row['message'],
      'is_sender' => $row['sender_id'] == $user
    ];
}

echo json_encode($messages);
$conn->close();
?>
