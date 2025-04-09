<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Password Hasher</title>
</head>
<body>
    <h2>Password Hasher Tool</h2>
    <form method="post">
        <label>Enter Password to Hash:</label><br>
        <input type="text" name="password" required>
        <button type="submit">Generate Hash</button>
    </form>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $password = $_POST['password'];
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        echo "<h3>Hashed Password:</h3>";
        echo "<textarea rows='3' cols='80' readonly>$hashed</textarea>";
    }
    ?>
</body>
</html>
