<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
include 'db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username_input = trim($_POST['username'] ?? '');
    $password_input = $_POST['password'] ?? '';

    if (empty($username_input) || empty($password_input)) {
        echo "<script>
            alert('❌ Username or password cannot be empty.');
            window.location.href = 'index.html';
        </script>";
        exit();
    }

    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username_input);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        if (password_verify($password_input, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            $redirectPage = ($user['role'] === 'admin') ? 'dashb1.php' : 'lost.php';
            $username = $user['username'];

            if ($user['role'] === 'admin') {
                echo "<script>
                    alert('Welcome Admin $username!');
                    window.location.href = '$redirectPage';
                </script>";
            } else {
                echo "<script>
                    alert('Welcome $username!');
                    window.location.href = '$redirectPage';
                </script>";
            }
            exit();
        } else {
            echo "<script>
                alert('❌ Invalid Password.');
                window.location.href = 'index.html';
            </script>";
            exit();
        }
    } else {
        echo "<script>
            alert('❌ Username not found.');
            window.location.href = 'index.html';
        </script>";
        exit();
    }
}
?>
