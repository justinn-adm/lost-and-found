<?php

include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = isset($_POST['username']) ? trim($_POST['username']) : null;
    $email = isset($_POST['email']) ? trim($_POST['email']) : null;
    $password = isset($_POST['password']) ? $_POST['password'] : null;

    if (!empty($username) && !empty($email) && !empty($password)) {
        $username_taken = false;
        $email_taken = false;

        $check_username_sql = "SELECT id FROM users WHERE username = ?";
        $check_username_stmt = $conn->prepare($check_username_sql);
        $check_username_stmt->bind_param("s", $username);
        $check_username_stmt->execute();
        $check_username_stmt->store_result();
        if ($check_username_stmt->num_rows > 0) {
            $username_taken = true;
        }
        $check_username_stmt->close();

        $check_email_sql = "SELECT id FROM users WHERE email = ?";
        $check_email_stmt = $conn->prepare($check_email_sql);
        $check_email_stmt->bind_param("s", $email);
        $check_email_stmt->execute();
        $check_email_stmt->store_result();
        if ($check_email_stmt->num_rows > 0) {
            $email_taken = true;
        }
        $check_email_stmt->close();

        if ($username_taken || $email_taken) {
            if ($username_taken && $email_taken) {
                echo "<script>alert('Both username and email are already taken.'); window.history.back();</script>";
            } elseif ($username_taken) {
                echo "<script>alert('Username is already taken.'); window.history.back();</script>";
            } elseif ($email_taken) {
                echo "<script>alert('Email is already taken.'); window.history.back();</script>";
            }
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);

            if ($stmt) {
                $stmt->bind_param("sss", $username, $email, $hashed_password);

                if ($stmt->execute()) {
                    echo "<script>alert('Registration successful!'); window.location.href='index.php';</script>";
                } else {
                    echo "<script>alert('Database error: " . $stmt->error . "');</script>";
                }
            } else {
                echo "<script>alert('Failed to prepare statement.');</script>";
            }
        }
    } else {
        echo "<script>alert('All fields are required.'); window.history.back();</script>";
    }
} else {
    echo "<script>alert('Invalid request method.');</script>";
}
?>
