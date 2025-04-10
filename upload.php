<?php
$servername = "localhost";
$username = "root";  /
$password = "";     
$dbname = "lost_and_found";  


$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $image = $_FILES['image'];

   
    if (empty($name) || empty($image['name'])) {
        echo "Please provide an item name and select an image.";
        exit;
    }

 
    $imagePath = "uploads/" . basename($image['name']);
    if (!move_uploaded_file($image['tmp_name'], $imagePath)) {
        echo "Error uploading the image.";
        exit;
    }

    $stmt = $conn->prepare("INSERT INTO items (name, image_path) VALUES (?, ?)");
    $stmt->bind_param("ss", $name, $imagePath);

    if ($stmt->execute()) {
        echo "Item added successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>
