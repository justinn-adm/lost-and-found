<?php
// Include database connection
require_once 'db.php'; // Adjust to your actual DB connection file

// Check if ID is passed as a GET parameter
if (isset($_GET['id'])) {
    $itemId = $_GET['id'];

    // Prepare SQL query to fetch item details from the 'lost_items' table
    $query = "SELECT * FROM lost_items WHERE id = :itemId";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':itemId', $itemId, PDO::PARAM_INT);

    // Execute the query
    if ($stmt->execute()) {
        $item = $stmt->fetch(PDO::FETCH_ASSOC);

        // Check if the item was found
        if ($item) {
            // Return the item details as a JSON response
            echo json_encode([
                'id' => $item['id'],
                'name' => $item['name'],
                'date' => $item['date'],
                'location' => $item['location'],
                'description' => $item['description'],
                'image_path' => $item['image_path'],
            ]);
        } else {
            // If the item is not found, return an error message
            echo json_encode(['error' => 'Item not found']);
        }
    } else {
        // If query execution fails, return an error message
        echo json_encode(['error' => 'Failed to fetch item details']);
    }
} else {
    // If no ID is provided, return an error message
    echo json_encode(['error' => 'Item ID is required']);
}
?>
