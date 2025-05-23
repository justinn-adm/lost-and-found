<?php
include 'db.php';
session_start();

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    header("Location: login.html");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
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
      margin-bottom: 10px;
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
   
    header a {
      margin-left: 20px;
      text-decoration: none;
      color: #fff;
      font-weight: 600;
      transition: color 0.2s ease;
    }
    header a:hover {
      color: #10b981;
    }
 iframe {
            width: 100%;
            height: 100vh;
            border: none;
            transition: transform 0.5s ease-in-out;
        }

    .dashboard {
      padding: 30px;
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
      gap: 20px;
    }

    .card {
      background-color: #fff;
      padding: 20px;
      border-radius: 8px;
      box-shadow: 0 2px 4px rgba(0,0,0,0.1);
      color: #000;
    }

    .card h2 {
      font-size: 2rem;
      font-weight: 800;
    }

    .card p {
      color: #555;
    }

    .card .more-info {
      display: block;
      margin-top: 10px;
      color: #fff;
      text-decoration: none;
    }

    footer {
      text-align: center;
      padding: 15px;
      background-color: #fff;
      color: #888;
      font-size: 0.9rem;
    }


        iframe.show {
            transform: translateY(0);
        }    
  </style>
    <script src="dashb.js">
    </script>
</head>
<body>
  <aside class="sidebar">
    <div class="logo">Lost and Found</div>
    <div class="profile">
      <img src="" alt="User" />
      <div>
          <span id="username-Container"></span></br>
        <small style="color: #1dd1a1">● Admin</small>
      </div>
    </div>
    <nav>
  
  <a href="#" class="active" id="dashboard"><i class="fas fa-tachometer-alt"></i>🏠 Dashboard</a>
  <a href="#" id="image-dashboard"><i class="fas fa-image"></i> 🖼️ Post Images</a>
  <a href="#" id="user_management"><i class="fas fa-users-cog"></i>👥 User Management</a>
  <a href="index.html"><i class="fas fa-power-off"></i>🚪 Logout</a>
</nav>

  </aside>

  <div class="main-content">
    <div class="topbar">


    </div>

    
    <iframe src="dashboard.php" class="main-content" id="main-content" style="height:100vh;"></iframe>
<script>
document.getElementById('username-Container').innerHTML = '<?php echo $_SESSION["username"]?>';
</script>
