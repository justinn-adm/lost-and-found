<?php

require_once 'db.php'; 


if (isset($_GET['id'])) {
    $itemId = $_GET['id'];

    
    $query = "SELECT * FROM lost_items WHERE id = :itemId";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':itemId', $itemId, PDO::PARAM_INT);

   
    if ($stmt->execute()) {
        $item = $stmt->fetch(PDO::FETCH_ASSOC);

     
        if ($item) {
        
            echo json_encode([
                'id' => $item['id'],
                'name' => $item['name'],
                'date' => $item['date'],
                'location' => $item['location'],
                'description' => $item['description'],
                'image_path' => $item['image_path'],
            ]);
        } else {
          
            echo json_encode(['error' => 'Item not found']);
        }
    } else {
       
        echo json_encode(['error' => 'Failed to fetch item details']);
    }
} else {
  
    echo json_encode(['error' => 'Item ID is required']);
}
?>
