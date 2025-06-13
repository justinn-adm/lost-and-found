<?php
include 'db.php';
session_start();


if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['claim_id'])) {
    $claim_id = intval($_POST['claim_id']);

 
    $stmt = $conn->prepare("UPDATE claims SET status = 'rejected' WHERE id = ?");
    $stmt->bind_param("i", $claim_id);
    $stmt->execute();
    $stmt->close();

    header("Location: admin_claims.php");
    exit();
} else {
    header("Location: admin_claims.php");
    exit();
}
?>
