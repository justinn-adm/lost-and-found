<?php
include 'db.php';
header('Content-Type: application/json');

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$sql = "SELECT l.*, c.user_id AS claimant_id, u.username AS claimant_name
        FROM lost_items l
        LEFT JOIN claims c ON l.id = c.item_id AND c.status = 'approved'
        LEFT JOIN users u ON c.user_id = u.id
        WHERE l.id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
  echo json_encode([
    'id' => $row['id'],
    'name' => $row['name'],
    'image_path' => $row['image_path'],
    'date_found' => $row['date_found'],
    'location' => $row['location'],
    'description' => $row['description'],
    'anonymous' => $row['anonymous'],
    'uploader_name' => $row['uploader_name'],
    'claimed' => $row['claimed'],
    'claimant_name' => $row['claimant_name'] ?? null
  ]);
} else {
  echo json_encode(['error' => 'Item not found']);
}
?>
