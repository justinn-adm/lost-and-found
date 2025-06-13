<?php
session_start();
include 'db.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: index.html"); // or your login page
    exit();
}

$user_id = $_SESSION['user_id'];

// Get all claims by this user
$sql = "SELECT c.id, c.status, l.name 
        FROM claims c 
        JOIN lost_items l ON c.item_id = l.id 
        WHERE c.user_id = ?";
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
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, sans-serif;
            background: #f4f6f9;
            margin: 0;
            padding: 40px;
            color: #333;
        }
        h1 {
            text-align: center;
            color: #5C4ACF;
        }
        table {
            width: 80%;
            margin: 40px auto;
            border-collapse: collapse;
            background: #fff;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            border-radius: 8px;
            overflow: hidden;
        }
        th, td {
            padding: 15px 20px;
            text-align: left;
        }
        th {
            background: #5C4ACF;
            color: #fff;
            text-transform: uppercase;
            font-size: 14px;
        }
        tr:nth-child(even) {
            background: #f9f9f9;
        }
        tr:hover {
            background: #f1f1f1;
        }
        td {
            font-size: 15px;
        }
    </style>
</head>
<body>
    <h1>My Claims</h1>
    <table>
        <thead>
            <tr>
                <th>Item Name</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()) : ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['name']); ?></td>
                        <td><?php echo ucfirst(htmlspecialchars($row['status'])); ?></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="2" style="text-align: center;">No claims found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>
