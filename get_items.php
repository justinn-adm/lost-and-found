<?php
include 'db.php';

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

header('Content-Type: application/json');

$result = $conn->query("SELECT * FROM lost_items ORDER BY date_found");

$items = [];

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $items[] = $row;
    }
}

echo json_encode($items);

$conn->close();
