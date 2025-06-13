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
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f4f6f9;
            margin: 0;
            padding: 40px;
        }

        h1 {
            text-align: center;
            color: #333;
        }

        .claims-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 30px;
            background: #fff;
            box-shadow: 0 0 10px rgba(0,0,0,0.05);
        }

        .claims-table th,
        .claims-table td {
            border: 1px solid #ddd;
            padding: 12px 15px;
            text-align: center;
        }

        .claims-table th {
            background-color: #007bff;
            color: #fff;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .claims-table tr:nth-child(even) {
            background: #f9f9f9;
        }

        .btn {
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            color: #fff;
            cursor: pointer;
            font-size: 0.9rem;
            transition: background 0.2s ease-in-out;
        }

        .btn.approve {
            background: #28a745;
        }

        .btn.approve:hover {
            background: #218838;
        }

        .btn.reject {
            background: #dc3545;
        }

        .btn.reject:hover {
            background: #c82333;
        }

        .actions form {
            display: inline-block;
        }

        @media (max-width: 768px) {
            .claims-table {
                font-size: 0.9rem;
            }

            .btn {
                padding: 6px 12px;
                font-size: 0.8rem;
            }
        }
    </style>
</head>
<body>

    <h1>Manage Claims</h1>

    <table class="claims-table">
        <thead>
            <tr>
                <th>Claim ID</th>
                <th>Item</th>
                <th>Claimant</th>
                <th>Message</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $row['claim_id']; ?></td>
                        <td><?= htmlspecialchars($row['item_name']); ?></td>
                        <td><?= htmlspecialchars($row['claimant_name']); ?></td>
                        <td><?= nl2br(htmlspecialchars($row['message'])); ?></td>
                        <td><?= htmlspecialchars($row['status']); ?></td>
                        <td class="actions">
                            <?php if (strtolower($row['status']) === 'pending'): ?>
                                <form method="POST" action="approve_claim.php">
                                    <input type="hidden" name="claim_id" value="<?= $row['claim_id']; ?>">
                                    <button type="submit" class="btn approve">Approve</button>
                                </form>
                                <form method="POST" action="reject_claim.php">
                                    <input type="hidden" name="claim_id" value="<?= $row['claim_id']; ?>">
                                    <button type="submit" class="btn reject">Reject</button>
                                </form>
                            <?php else: ?>
                                <em><?= htmlspecialchars(ucfirst($row['status'])); ?></em>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6">No claims found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

</body>
</html>
