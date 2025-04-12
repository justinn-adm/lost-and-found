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
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f4f6f9;
      padding: 30px;
    }

    .header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 25px;
    }

    .header h1 {
      color: #333;
    }

    .home-btn {
      background-color: #28a745;
      color: white;
      padding: 8px 12px;
      text-decoration: none;
      border-radius: 5px;
      font-weight: bold;
      box-shadow: 0 2px 4px rgba(0,0,0,0.2);
      transition: background-color 0.3s ease;
    }

    .home-btn:hover {
      background-color: #218838;
    }

    .add-user-btn {
      background-color: #007bff;
      color: white;
      padding: 8px 12px;
      text-decoration: none;
      border-radius: 5px;
      font-weight: bold;
      box-shadow: 0 2px 4px rgba(0,0,0,0.2);
      transition: background-color 0.3s ease;
    }

    .add-user-btn:hover {
      background-color: #0056b3;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      background: #fff;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }

    th, td {
      padding: 12px 15px;
      border: 1px solid #ccc;
      text-align: left;
    }

    th {
      background-color: #007bff;
      color: white;
    }

    tr:hover {
      background-color: #f1f1f1;
    }

    .action-btn {
      padding: 6px 12px;
      font-size: 14px;
      border: none;
      border-radius: 5px;
      margin-right: 5px;
      text-decoration: none;
      font-weight: 600;
      display: inline-flex;
      align-items: center;
      gap: 6px;
      transition: all 0.3s ease;
    }

    .edit-btn {
      background-color: #f0ad4e;
      color: #fff;
    }

    .edit-btn:hover {
      background-color: #ec971f;
    }

    .delete-btn {
      background-color: #d9534f;
      color: #fff;
    }

    .delete-btn:hover {
      background-color: #c9302c;
    }
  </style>
</head>
<body>

  <div class="header">
    <h1>User Management</h1>
    <div>
      <a href="index.html" class="home-btn"><i class="fas fa-home"></i> Home</a>
      <a href="add_user.php" class="add-user-btn"><i class="fas fa-user-plus"></i> Add User</a>
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
