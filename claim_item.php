<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
  die("You must be logged in to claim an item.");
}

$user_id = $_SESSION['user_id'];
$item_id = $_POST['item_id'];
$message = $_POST['message'];

$proof_path = null;

if (isset($_FILES['proof_image']) && $_FILES['proof_image']['error'] === 0) {
  $upload_dir = 'proof_uploads/';
  if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0755, true);
  }

  $file_tmp = $_FILES['proof_image']['tmp_name'];
  $file_name = time() . '_' . basename($_FILES['proof_image']['name']);
  $proof_path = $upload_dir . $file_name;

  if (!move_uploaded_file($file_tmp, $proof_path)) {
    die("Failed to upload the proof image.");
  }
} else {
  die("Proof image is required.");
}

$stmt = $conn->prepare("INSERT INTO claims (item_id, user_id, message, proof_image) VALUES (?, ?, ?, ?)");
$stmt->bind_param("iiss", $item_id, $user_id, $message, $proof_path);
$stmt->execute();

if ($stmt->affected_rows > 0) {
  echo "Your claim has been submitted and is pending approval.";
} else {
  echo "Something went wrong. Please try again.";
}

$stmt->close();
$conn->close();
?>
