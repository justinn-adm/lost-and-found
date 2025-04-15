<?php

require_once 'db.php'; 


if (isset($_GET['id'])) {
    $itemId = $_GET['id'];


    $query = "SELECT * FROM lost_items WHERE id = ?";

    if($stmt = $conn->prepare($query)){
        $stmt->bind_param("i", $itemId);
        $stmt->execute();

        $result = $stmt->get_result();

        if($item = $result->fetch_assoc()){
            echo json_encode([
                'id' => $item['id'],
                'name' => $item['name'],
                'date' => $item['date_found'],
                'location' => $item['location'],
                'description' => $item['description'],
                'image_path' => $item['image_path'],
            ]);
        } else {
            echo json_encode(['error' => 'Item not Found']);
        }

        $stmt->close();

    } else {
        echo json_encode(['error' => 'Failed to prepare statement']);
    }
}else {
    echo json_encode(['error' => 'Item ID is required']);
}
