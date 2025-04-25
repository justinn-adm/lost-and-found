<?php
  include 'db.php';
  header('Content-type: application/json');

if($_SERVER['REQUEST_METHOD'] === 'POST'){

  $field = $_POST['field'];
  $value = $_POST['value'];


  $sql = "SELECT 1 FROM users WHERE $field = ?";
  $check_username_stmt = $conn->prepare($sql);
  $check_username_stmt->bind_param("s", $value);
  $check_username_stmt->execute();
  $check_username_stmt->store_result();

  $isAvailable = $check_username_stmt->num_rows > 0;

  echo json_encode(['available' => !$isAvailable]);
}
