 <?php
require 'db.php';
session_start();

$data = json_decode(file_get_contents("php://input"), true);
$sender = $_SESSION['user_id'];
$receiver = intval($data['receiver_id']);
$message = trim($data['message']);

if ($sender && $receiver && $message !== '' && $sender!==$receiver) {
  $stmt = $conn->prepare("INSERT INTO messages (sender_id, receiver_id, message) VALUES (?, ?, ?)");
  $stmt->bind_param("iis", $sender, $receiver, $message);
  $stmt->execute();
  echo json_encode(["success" => true]);
} else {
  echo json_encode(["success" => false]);
} 

