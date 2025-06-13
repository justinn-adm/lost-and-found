<?php
include 'db.php';
session_start();


if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}


$sql = "
    SELECT 
        claims.id AS claim_id,
        claims.item_id,
        claims.user_id,
        claims.message,
        claims.status,
        users.username AS claimant_name,
        lost_items.name AS item_name
    FROM claims
    JOIN users ON claims.user_id = users.id
    JOIN lost_items ON claims.item_id = lost_items.id
    ORDER BY claims.id DESC
";

$result = $conn->query($sql);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Claims</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 30px;
            background: #f4f6f9;
        }
        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
        }
        th {
            background: #007bff;
            color: #fff;
        }
        .btn {
            padding: 6px 12px;
            border: none;
            cursor: pointer;
            border-radius: 4px;
            color: #fff;
        }
        .approve {
            background: #28a745;
        }
        .reject {
            background: #dc3545;
        }
    </style>
</head>
<body>

    <h1>Manage Claims</h1>

    <table>
        <tr>
            <th>Claim ID</th>
            <th>Item</th>
            <th>Claimant</th>
            <th>Message</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
        <?php while($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['claim_id']; ?></td>
                <td><?php echo htmlspecialchars($row['item_name']); ?></td>
                <td><?php echo htmlspecialchars($row['claimant_name']); ?></td>
                <td><?php echo htmlspecialchars($row['message']); ?></td>
                <td><?php echo htmlspecialchars($row['status']); ?></td>
                <td>
                    <?php if (strtolower($row['status']) === 'pending'): ?>
                        <form method="POST" action="approve_claim.php" style="display:inline;">
                            <input type="hidden" name="claim_id" value="<?php echo $row['claim_id']; ?>">
                            <button type="submit" class="btn approve">Approve</button>
                        </form>
                        <form method="POST" action="reject_claim.php" style="display:inline;">
                            <input type="hidden" name="claim_id" value="<?php echo $row['claim_id']; ?>">
                            <button type="submit" class="btn reject">Reject</button>
                        </form>
                    <?php else: ?>
                        <em><?php echo htmlspecialchars($row['status']); ?></em>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>

</body>
</html>
