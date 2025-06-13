<?php
include 'db.php';

header('Content-Type: application/json');

$sql = "SELECT id, name, image_path, claimed FROM lost_items";
$result = $conn->query($sql);

$items = [];

while ($row = $result->fetch_assoc()) {
  $items[] = [
    'id' => $row['id'],
    'name' => $row['name'],
    'image_path' => $row['image_path'],
    'claimed' => $row['claimed']
  ];
}

echo json_encode($items);
?>
