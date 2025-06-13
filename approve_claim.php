<?php
include 'db.php';
session_start();


if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['claim_id'])) {
    $claim_id = intval($_POST['claim_id']);


    $stmt = $conn->prepare("UPDATE claims SET status = 'approved' WHERE id = ?");
    $stmt->bind_param("i", $claim_id);
    $stmt->execute();
    $stmt->close();

    $stmt = $conn->prepare("SELECT item_id FROM claims WHERE id = ?");
    $stmt->bind_param("i", $claim_id);
    $stmt->execute();
    $stmt->bind_result($item_id);
    $stmt->fetch();
    $stmt->close();


    if ($item_id) {
        $stmt = $conn->prepare("UPDATE lost_items SET claimed = 1 WHERE id = ?");
        $stmt->bind_param("i", $item_id);
        $stmt->execute();
        $stmt->close();
    }

    header("Location: admin_claims.php");
    exit();
} else {

    header("Location: admin_claims.php");
    exit();
}
?>
