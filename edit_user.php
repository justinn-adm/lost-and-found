<?php
include 'db.php';

$id = $_GET['id'];

$query = "SELECT * FROM users WHERE id = $id";
$result = mysqli_query($conn, $query);
$user = mysqli_fetch_assoc($result);

if (isset($_POST['update'])) {
  $username = $_POST['username'];
  $email = $_POST['email'];
  $query = "UPDATE users SET username='$username', email='$email' WHERE id=$id";
  mysqli_query($conn, $query);
  header("Location: user_management.php");
  exit();
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Edit User</title>
</head>
<body>
  <h2>Edit User</h2>
  <form method="post">
    Username: <input type="text" name="username" value="<?= $user['username'] ?>"><br><br>
    Email: <input type="email" name="email" value="<?= $user['email'] ?>"><br><br>
    <button type="submit" name="update">Update</button>
  </form>
</body>
</html>
