<?php
session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $item_name = $_POST['item_name'];
    $description = $_POST['description'];
    $location = $_POST['location'];
    $date_found = $_POST['date_found'];
    $user_id = $_SESSION['user_id'];

    // Handle file upload
    $image_path = "";
    if (!empty($_FILES["item_image"]["name"])) {
        $target_dir = "uploads/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        $image_path = $target_dir . time() . "_" . basename($_FILES["item_image"]["name"]);
        move_uploaded_file($_FILES["item_image"]["tmp_name"], $image_path);
    }

    $sql = "INSERT INTO found_items (user_id, item_name, description, location, date_found, image_path) 
            VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isssss", $user_id, $item_name, $description, $location, $date_found, $image_path);

    if ($stmt->execute()) {
        echo "<script>alert('Found item reported successfully!'); window.location.href='items.php';</script>";
    } else {
        echo "<script>alert('Error reporting found item.');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Report Found Item</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background: linear-gradient(135deg, #00c6ff, #0072ff, #92fe9d);
      background-size: 400% 400%;
      animation: gradientBG 12s ease infinite;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      margin: 0;
    }
    @keyframes gradientBG {
      0% { background-position: 0% 50%; }
      50% { background-position: 100% 50%; }
      100% { background-position: 0% 50%; }
    }
    .form-container {
      background: rgba(255, 255, 255, 0.1);
      backdrop-filter: blur(15px);
      border-radius: 15px;
      padding: 30px;
      width: 100%;
      max-width: 450px;
      color: #fff;
      box-shadow: 0 10px 25px rgba(0,0,0,0.3);
    }
    .form-container h2 {
      text-align: center;
      margin-bottom: 20px;
      font-weight: 600;
    }
    .form-group {
      margin-bottom: 15px;
    }
    .form-group label {
      display: block;
      margin-bottom: 6px;
      font-size: 0.9rem;
    }
    .form-group input, .form-group textarea {
      width: 100%;
      padding: 10px;
      border: none;
      border-radius: 8px;
      background: rgba(255,255,255,0.15);
      color: #fff;
      font-size: 1rem;
    }
    .form-group input[type="file"] {
      padding: 4px;
    }
    .form-group input:focus, .form-group textarea:focus {
      outline: 2px solid #00ffc6;
    }
    .submit-btn {
      width: 100%;
      padding: 12px;
      border: none;
      border-radius: 10px;
      background: linear-gradient(135deg, #00c6ff, #0072ff, #00ffc6);
      color: #fff;
      font-weight: bold;
      cursor: pointer;
      transition: 0.3s ease;
      font-size: 1rem;
    }
    .submit-btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 20px rgba(0,0,0,0.3);
    }
  </style>
</head>
<body>
  <div class="form-container">
    <h2>Report Found Item</h2>
    <form method="POST" enctype="multipart/form-data">
      <div class="form-group">
        <label for="item_name">Item Name</label>
        <input type="text" id="item_name" name="item_name" required>
      </div>
      <div class="form-group">
        <label for="description">Description</label>
        <textarea id="description" name="description" rows="3" required></textarea>
      </div>
      <div class="form-group">
        <label for="location">Location Found</label>
        <input type="text" id="location" name="location" required>
      </div>
      <div class="form-group">
        <label for="date_found">Date Found</label>
        <input type="date" id="date_found" name="date_found" required>
      </div>
      <div class="form-group">
        <label for="item_image">Upload Photo</label>
        <input type="file" id="item_image" name="item_image" accept="image/*">
      </div>
      <button type="submit" class="submit-btn">Submit</button>
    </form>
  </div>
</body>
</html>
