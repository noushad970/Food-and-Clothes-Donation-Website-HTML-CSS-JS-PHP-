<?php
require 'db.php';

header('Content-Type: application/json');

if (!isset($_GET['org_id'])) {
    echo json_encode(['error' => 'Organization ID is required']);
    exit;
}

$org_id = intval($_GET['org_id']);

// Get average rating and total rating count
$stmt = $conn->prepare("
    SELECT 
        AVG(user_rating) AS avg_rating, 
        COUNT(*) AS total_ratings
    FROM ratings
    WHERE org_id = ?
");
$stmt->bind_param("i", $org_id);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    $avg_rating = round($row['avg_rating'], 1); // e.g., 4.3
    $total_ratings = $row['total_ratings'];

    echo json_encode([
        'avg_rating' => $avg_rating ? $avg_rating : 0,
        'total_ratings' => $total_ratings
    ]);
} else {
    echo json_encode([
        'avg_rating' => 0,
        'total_ratings' => 0
    ]);
}
?>
