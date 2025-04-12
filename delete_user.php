<?php
include 'db.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

   
    $id = intval($id);

   
    $query = "DELETE FROM users WHERE id = $id";
    $result = mysqli_query($conn, $query);

    if ($result) {
        header("Location: user_management.php?message=deleted");
        exit();
    } else {
        echo "Error deleting user: " . mysqli_error($conn);
    }
} else {
    echo "Invalid request.";
}
?>
