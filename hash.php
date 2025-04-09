<?php
$password = "yourPassword123"; // change this to your desired password
$hashed = password_hash($password, PASSWORD_DEFAULT);
echo $hashed;
?>
