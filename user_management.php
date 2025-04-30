<?php
include 'db.php';
session_start();


$sql = "SELECT * FROM users"; 
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>User Management</title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta2/css/all.min.css" rel="stylesheet">
  <link href="user_management.css" rel="stylesheet">
</head>
<body>

  <div class="header">
    <h1>User Management</h1>
    <div>
      <a href="add_admin.php" class="add-user-btn"><i class="fas fa-user-plus"></i> Add Admin</a>
    </div>
  </div>

  <table>
    <tr>
      <th>ID</th>
      <th>Username</th>
      <th>Email</th>
      <th>Role</th>
      <th>Actions</th>
    </tr>

    <?php if ($result->num_rows > 0): ?>
      <?php while($row = $result->fetch_assoc()): ?>
        <tr>
          <td><?= htmlspecialchars($row['id']) ?></td>
          <td><?= htmlspecialchars($row['username']) ?></td>
          <td><?= htmlspecialchars($row['email']) ?></td>
          <td><?= htmlspecialchars($row['role']) ?></td>
          <td>
            <a href="edit_user.php?id=<?= $row['id'] ?>" class="action-btn edit-btn">
              <i class="fas fa-edit"></i> Edit
            </a>
            <a href="delete_user.php?id=<?= $row['id'] ?>" class="action-btn delete-btn" onclick="return confirm('Are you sure you want to delete this user?');">
              <i class="fas fa-trash-alt"></i> Delete
            </a>
          </td>
        </tr>
      <?php endwhile; ?>
    <?php else: ?>
      <tr><td colspan="5">No users found.</td></tr>
    <?php endif; ?>
  </table>

</body>
</html>
