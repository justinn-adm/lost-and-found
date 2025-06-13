<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $gender = $_POST['gender'];
    $profile_img = $_POST['profile_img'];

    if (!empty($username) && !empty($email) && !empty($password) && !empty($gender) && !empty($profile_img)) {
        $username_taken = false;
        $email_taken = false;

        $check_username = $conn->prepare("SELECT id FROM users WHERE username = ?");
        $check_username->bind_param("s", $username);
        $check_username->execute();
        $check_username->store_result();
        if ($check_username->num_rows > 0) {
            $username_taken = true;
        }
        $check_username->close();

        $check_email = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $check_email->bind_param("s", $email);
        $check_email->execute();
        $check_email->store_result();
        if ($check_email->num_rows > 0) {
            $email_taken = true;
        }
        $check_email->close();

        if ($username_taken || $email_taken) {
            if ($username_taken && $email_taken) {
                echo "<script>alert('Both username and email are already taken.'); window.history.back();</script>";
            } elseif ($username_taken) {
                echo "<script>alert('Username is already taken.'); window.history.back();</script>";
            } else {
                echo "<script>alert('Email is already taken.'); window.history.back();</script>";
            }
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO users (username, email, password, gender, profile_img) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssss", $username, $email, $hashed_password, $gender, $profile_img);
            if ($stmt->execute()) {
                echo "<script>alert('Registration successful!'); window.location.href='SignIn_SignUp.html';</script>";
            } else {
                echo "<script>alert('Error: " . $stmt->error . "');</script>";
            }
            $stmt->close();
        }
    } else {
        echo "<script>alert('All fields are required.'); window.history.back();</script>";
    }
} else {
    echo "<script>alert('Invalid request method.');</script>";
}
?>
