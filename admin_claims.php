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
        claims.proof_image,
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-5">
    <h1 class="text-center text-primary mb-4">Manage Claims</h1>

    <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle">
            <thead class="table-primary text-center">
                <tr>
                    <th>Claim ID</th>
                    <th>Item</th>
                    <th>Claimant</th>
                    <th>Message</th>
                    <th>Proof Image</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr class="text-center">
                            <td><?= $row['claim_id']; ?></td>
                            <td><?= htmlspecialchars($row['item_name']); ?></td>
                            <td><?= htmlspecialchars($row['claimant_name']); ?></td>
                            <td class="text-start"><?= nl2br(htmlspecialchars($row['message'])); ?></td>
                            <td>
                                <?php if (!empty($row['proof_image'])): ?>
                                    <a href="<?= htmlspecialchars($row['proof_image']); ?>" target="_blank">
                                        <img src="<?= htmlspecialchars($row['proof_image']); ?>" style="max-width: 100px; max-height: 100px;" alt="Proof Image">
                                    </a>
                                <?php else: ?>
                                    <em>No image</em>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php
                                    $status = strtolower($row['status']);
                                    $badge = match ($status) {
                                        'approved' => 'success',
                                        'rejected' => 'danger',
                                        'pending' => 'warning',
                                        default => 'secondary'
                                    };
                                ?>
                                <span class="badge bg-<?= $badge; ?>"><?= ucfirst($status); ?></span>
                            </td>
                            <td>
                                <?php if ($status === 'pending'): ?>
                                    <form method="POST" action="approve_claim.php" class="d-inline">
                                        <input type="hidden" name="claim_id" value="<?= $row['claim_id']; ?>">
                                        <button type="submit" class="btn btn-sm btn-success">Approve</button>
                                    </form>
                                    <form method="POST" action="reject_claim.php" class="d-inline">
                                        <input type="hidden" name="claim_id" value="<?= $row['claim_id']; ?>">
                                        <button type="submit" class="btn btn-sm btn-danger">Reject</button>
                                    </form>
                                <?php else: ?>
                                    <em><?= ucfirst($row['status']); ?></em>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="text-center">No claims found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
