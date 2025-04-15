<?php
include 'db.php';

$username = $_POST['username'];
$email = $_POST['email'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);

$sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $username, $email, $password);

if ($stmt->execute()) {
    echo "<script>
        window.location.href = 'index.html';
    setTimeout(function() { window.location.href = 'index.html';} , 1000); // 1000ms = 1 second delay
    </script>";
    }
 else {
    echo "Error: " . $stmt->error;
}
?>
