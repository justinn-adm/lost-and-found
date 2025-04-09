<?php
include 'db.php';

session_start(); // Start a session to save login status

$username = $_POST['username'];
$password = $_POST['password'];

$sql = "SELECT * FROM users WHERE username=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    if (password_verify($password, $row['password'])) {
        $_SESSION['username'] = $row['username'];
        header("Location: dashb.html"); // 🔁 Redirect after login
        exit();
    } else {
        echo "❌ Invalid password.";
    }
} else {
    echo "❌ User not found.";
}
?>
