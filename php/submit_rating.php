<?php
session_start();
include 'db.php';

$data = json_decode(file_get_contents("php://input"), true);
$org_id = $data['org_id'];
$rating = $data['rating'];
$rater_id = $_SESSION['user_id'];  // current logged-in user

// Check if the user already rated this org
$check = $conn->prepare("SELECT * FROM ratings WHERE org_id = ? AND rater_id = ?");
$check->bind_param("ii", $org_id, $rater_id);
$check->execute();
$result = $check->get_result();

if ($result->num_rows > 0) {
    // Update existing rating
    $existing = $result->fetch_assoc();
    $old_rating = $existing['user_rating'];

    $update = $conn->prepare("UPDATE ratings SET user_rating = ? WHERE org_id = ? AND rater_id = ?");
    $update->bind_param("iii", $rating, $org_id, $rater_id);
    $update->execute();

    // Recalculate total rating
    $recalc = $conn->prepare("SELECT SUM(user_rating) AS total, COUNT(*) AS count FROM ratings WHERE org_id = ?");
    $recalc->bind_param("i", $org_id);
    $recalc->execute();
    $sumData = $recalc->get_result()->fetch_assoc();

    $new_avg = $sumData['total'] / $sumData['count'];

} else {
    // Insert new rating
    $insert = $conn->prepare("INSERT INTO ratings (org_id, rater_id, user_rating) VALUES (?, ?, ?)");
    $insert->bind_param("iii", $org_id, $rater_id, $rating);
    $insert->execute();

    // Update total and count
    $recalc = $conn->prepare("SELECT SUM(user_rating) AS total, COUNT(*) AS count FROM ratings WHERE org_id = ?");
    $recalc->bind_param("i", $org_id);
    $recalc->execute();
    $sumData = $recalc->get_result()->fetch_assoc();

    $new_avg = $sumData['total'] / $sumData['count'];
}

// Return result
echo json_encode([
    'success' => true,
    'avg_rating' => $new_avg,
    'rating_count' => $sumData['count']
]);
?>
