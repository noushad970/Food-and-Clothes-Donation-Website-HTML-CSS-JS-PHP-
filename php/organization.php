<?php
require 'db.php';

$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$search = isset($_GET['search']) ? "%" . $_GET['search'] . "%" : "%";
$limit = 10;
$offset = ($page - 1) * $limit;

$stmt = $conn->prepare("SELECT org_name, org_location, org_phone FROM organization WHERE org_location LIKE ? LIMIT ? OFFSET ?");
$stmt->bind_param("sii", $search, $limit, $offset);
$stmt->execute();

$result = $stmt->get_result();
$organizations = [];

while ($row = $result->fetch_assoc()) {
    $organizations[] = $row;
}

echo json_encode($organizations);
