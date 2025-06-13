<?php
include 'db.php';
session_start();


if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Lost and Found Dashboard</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">
  <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
  <style>
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
      font-family: 'Inter', sans-serif;
    }
    body {
      display: flex;
      height: 100vh;
      overflow: hidden;
      background-color: #f4f6f9;
    }
    .sidebar {
      width: 260px;
      background-color: #343a40;
      color: #fff;
      display: flex;
      flex-direction: column;
      padding: 20px;
    }
    .sidebar .logo {
      font-size: 1.5rem;
      font-weight: 800;
      margin-bottom: 20px;
    }
    .sidebar .profile {
      display: flex;
      align-items: center;
      margin-bottom: 30px;
    }
    .sidebar .profile img {
      width: 50px;
      height: 50px;
      border-radius: 50%;
      margin-right: 15px;
    }
    .sidebar .profile span {
      font-weight: 600;
    }
    .sidebar nav a {
      display: flex;
      align-items: center;
      text-decoration: none;
      color: #ddd;
      padding: 10px;
      margin-bottom: 8px;
      border-radius: 5px;
      transition: background 0.2s;
    }
    .sidebar nav a.active,
    .sidebar nav a:hover {
      background-color: #007bff;
      color: #fff;
    }
    .sidebar nav i {
      margin-right: 10px;
    }
    .main-content {
      flex-grow: 1;
      display: flex;
      flex-direction: column;
    }
    iframe {
      width: 100%;
      height: 100vh;
      border: none;
    }
  </style>
</head>
<body>
  <aside class="sidebar">
    <div class="logo">Lost and Found</div>
    <div class="profile">
      <img src="tin.jpg" alt="Admin" />
      <div>
        <span id="username-Container"></span><br/>
        <small style="color: #1dd1a1">● Admin</small>
      </div>
    </div>
    <nav>
      <a href="#" class="active" id="dashboard"><i class="fas fa-tachometer-alt"></i>🏠 Dashboard</a>
      <a href="#" id="post_items"><i class="fas fa-image"></i> 🖼️ Post Items</a>
      <a href="#" id="user_management"><i class="fas fa-users-cog"></i>👥 User Management</a>
      <a href="#" id="manage_claims"><i class="fas fa-check-circle"></i>✅ Manage Claims</a>
      <a href="index.html"><i class="fas fa-power-off"></i>🚪 Logout</a>
    </nav>
  </aside>

  <iframe src="dashboard.php" id="main-content"></iframe>

  <script>
  
    document.getElementById('username-Container').innerHTML = '<?php echo $_SESSION["username"]; ?>';

    
    document.getElementById('dashboard').addEventListener('click', function(e) {
      e.preventDefault();
      document.getElementById('main-content').src = 'dashboard.php';
    });

    document.getElementById('post_items').addEventListener('click', function(e) {
      e.preventDefault();
      document.getElementById('main-content').src = 'post_items.html';
    });

    document.getElementById('user_management').addEventListener('click', function(e) {
      e.preventDefault();
      document.getElementById('main-content').src = 'user_management.php';
    });

    document.getElementById('manage_claims').addEventListener('click', function(e) {
      e.preventDefault();
      document.getElementById('main-content').src = 'admin_claims.php';
    });
  </script>
</body>
</html>
