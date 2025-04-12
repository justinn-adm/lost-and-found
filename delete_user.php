<?php
include 'db.php';

$id = $_GET['id'];
$query = "DELETE FROM users WHERE id = $id";
mysqli_query($conn, $query);

header("Location: user_management.php");
exit();
?>
