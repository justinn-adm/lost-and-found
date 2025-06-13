<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
  die("You must be logged in to claim an item.");
}

$user_id = $_SESSION['user_id'];
$item_id = $_POST['item_id'];
$message = $_POST['message'];

$stmt = $conn->prepare("INSERT INTO claims (item_id, user_id, message) VALUES (?, ?, ?)");
$stmt->bind_param("iis", $item_id, $user_id, $message);
$stmt->execute();

if ($stmt->affected_rows > 0) {
  echo "Your claim has been submitted and is pending approval.";
} else {
  echo "Something went wrong. Please try again.";
}

$stmt->close();
$conn->close();
?>
