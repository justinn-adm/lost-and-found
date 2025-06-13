<?php
include 'db.php';

header('Content-Type: application/json');

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$sql = "SELECT * FROM lost_items WHERE id = ?";
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
    'claimed' => $row['claimed']
  ]);
} else {
  echo json_encode(['error' => 'Item not found']);
}
?>
