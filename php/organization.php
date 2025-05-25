<?php
require 'db.php';

$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$search = isset($_GET['search']) ? "%" . $_GET['search'] . "%" : "%";
$limit = 10;
$offset = ($page - 1) * $limit;

// JOIN users table using organization.user_id = users.id
$stmt = $conn->prepare("
    SELECT 
        organization.org_name, 
        organization.org_location, 
        organization.org_phone,
        organization.user_id,
        users.org_image
    FROM organization
    JOIN users ON organization.user_id = users.id
    WHERE organization.org_location LIKE ?
    LIMIT ? OFFSET ?
");

$stmt->bind_param("sii", $search, $limit, $offset);
$stmt->execute();

$result = $stmt->get_result();
$organizations = [];

while ($row = $result->fetch_assoc()) {
    $organizations[] = $row;
}

echo json_encode($organizations);
