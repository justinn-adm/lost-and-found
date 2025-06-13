<?php
session_start();

$host = "localhost";
$user = "root";
$pass = "";
$dbname = "Lost_and_found";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $targetDir = "uploads/";

    if (!file_exists($targetDir)) {
        mkdir($targetDir, 0777, true);
    }

    $itemName = $_POST['name'] ?? '';
    $date = $_POST['date'] ?? '';
    $location = $_POST['location'] ?? '';
    $description = $_POST['description'] ?? '';
    $anonymous = isset($_POST['anonymous']) ? intval($_POST['anonymous']) : 0;
    $uploaderName = isset($_SESSION['username']) ? $_SESSION['username'] : '';

    $image = $_FILES['image'] ?? null;

    if ($image && $itemName && $date) {
        $fileName = time() . '_' . basename($image["name"]);
        $targetFilePath = $targetDir . $fileName;

        if (move_uploaded_file($image["tmp_name"], $targetFilePath)) {
            if ($anonymous === 1) {
                $stmt = $conn->prepare(
                    "INSERT INTO lost_items (name, image_path, description, date_found, location, anonymous, uploader_name)
                     VALUES (?, ?, ?, ?, ?, ?, NULL)"
                );
                $stmt->bind_param("sssssi",
                    $itemName, $targetFilePath, $description, $date, $location, $anonymous
                );
            } else {
                $stmt = $conn->prepare(
                    "INSERT INTO lost_items (name, image_path, description, date_found, location, anonymous, uploader_name)
                     VALUES (?, ?, ?, ?, ?, ?, ?)"
                );
                $stmt->bind_param("sssssis",
                    $itemName, $targetFilePath, $description, $date, $location, $anonymous, $uploaderName
                );
            }

            if ($stmt->execute()) {
                echo "success";
            } else {
                echo "error_db: " . $stmt->error;
            }

            $stmt->close();
        } else {
            echo "error_uploading";
        }
    } else {
        echo "invalid_input";
    }
}

$conn->close();
?>
