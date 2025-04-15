<?php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "lost_and_found"; 

$conn = new mysqli($host, $user, $pass, $dbname);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $targetDir = "uploads/";

    if (!file_exists($targetDir)) {
        mkdir($targetDir, 0777, true);
    }

    $itemName = $_POST['name'];
    $image = $_FILES['image'];
    $date = $_POST['date'];
    $location = $_POST['location'];
    $description = $_POST['description'];

    if ($image && $itemName) {
        $fileName = time() . '_' . basename($image["name"]);
        $targetFilePath = $targetDir . $fileName;

        if (move_uploaded_file($image["tmp_name"], $targetFilePath)) {
            $stmt = $conn->prepare("INSERT INTO lost_items (name, image_path, description, date_found, location) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssss", $itemName, $targetFilePath, $description, $date, $location);
            $stmt->execute();

            echo "success";
        } else {
            echo "error_uploading";
        }
    } else {
        echo "invalid_input";
    }
}
?>
