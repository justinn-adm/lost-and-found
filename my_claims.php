<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.html");
    exit();
}

$user_id = $_SESSION['user_id'];

$sql = "SELECT 
            c.id, c.status, c.message, c.claim_date, c.proof_image,
            l.name AS item_name, l.image_path
        FROM claims c
        JOIN lost_items l ON c.item_id = l.id 
        WHERE c.user_id = ?
        ORDER BY c.claim_date DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Claims</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&family=Poppins:wght@600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(to right, #e0f2f1, #f1f5f9);
            min-height: 100vh;
            padding-top: 60px;
        }
        .container {
            max-width: 1000px;
        }
        h1 {
            font-family: 'Poppins', sans-serif;
            font-weight: 600;
            color: #0f172a;
        }
        .btn-back {
            background: linear-gradient(to right, #3b82f6, #1e3a8a);
            color: white;
            border: none;
            padding: 10px 24px;
            border-radius: 25px;
            font-weight: 600;
            transition: 0.3s ease;
        }
        .btn-back:hover {
            background: linear-gradient(to right, #2563eb, #1d4ed8);
            box-shadow: 0 6px 16px rgba(37, 99, 235, 0.3);
        }
        .table {
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        }
        .table thead {
            background-color: #3b82f6;
            color: white;
        }
        .badge {
            padding: 0.5em 0.75em;
            font-size: 0.9rem;
            border-radius: 0.5rem;
        }
        .no-claims {
            font-style: italic;
            color: #6b7280;
        }
        .thumbnail {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 6px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="text-center mb-5">My Claims</h1>

        <div class="text-center mb-4">
            <a href="javascript:history.back()" class="btn btn-back">← Back</a>
        </div>

        <div class="table-responsive">
            <table class="table table-hover text-center align-middle">
                <thead>
                    <tr>
                        <th>Item</th>
                        <th>Item Image</th>
                        <th>Status</th>
                        <th>Message</th>
                        <th>Claimed On</th>
                        <th>Proof Image</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result->num_rows > 0): ?>
                        <?php while ($row = $result->fetch_assoc()) : ?>
                            <tr>
                                <td><?= htmlspecialchars($row['item_name']); ?></td>
                                <td>
                                    <img src="<?= htmlspecialchars($row['image_path']); ?>" class="thumbnail" alt="Item Image">
                                </td>
                                <td>
                                    <?php
                                        $status = strtolower($row['status']);
                                        $badge_class = match ($status) {
                                            'approved' => 'success',
                                            'pending' => 'warning',
                                            'rejected' => 'danger',
                                            default => 'secondary'
                                        };
                                    ?>
                                    <span class="badge bg-<?= $badge_class; ?>">
                                        <?= ucfirst(htmlspecialchars($status)); ?>
                                    </span>
                                </td>
                                <td class="text-start"><?= nl2br(htmlspecialchars($row['message'])); ?></td>
                                <td><?= date('F j, Y', strtotime($row['claim_date'])); ?></td>
                                <td>
                                    <?php if (!empty($row['proof_image'])): ?>
                                        <a href="<?= htmlspecialchars($row['proof_image']); ?>" target="_blank">
                                            <img src="<?= htmlspecialchars($row['proof_image']); ?>" class="thumbnail" alt="Proof Image">
                                        </a>
                                    <?php else: ?>
                                        <em>No image</em>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="no-claims">You haven't submitted any claims yet.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
