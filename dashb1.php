<?php
include 'db.php';
session_start();

// protect admin route
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// handle logout in same file
if (isset($_GET['logout'])) {
    session_unset();
    session_destroy();
    header("Location: index.html");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Lost & Found | Admin Dashboard</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">
  <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Inter', sans-serif; }

    body {
      display: flex;
      height: 100vh;
      background: #f7f9fc;
      overflow: hidden;
    }

    /* Sidebar */
    .sidebar {
      width: 260px;
      background: linear-gradient(180deg, #2d3436, #1e272e);
      color: #fff;
      display: flex;
      flex-direction: column;
      padding: 20px;
      box-shadow: 3px 0 10px rgba(0,0,0,0.1);
      transition: width 0.3s ease;
    }

    .sidebar .logo {
      font-size: 1.7rem;
      font-weight: 800;
      text-align: center;
      margin-bottom: 25px;
      color: #74b9ff;
      letter-spacing: 1px;
    }

    .sidebar .profile {
      display: flex;
      align-items: center;
      margin-bottom: 30px;
      padding: 12px;
      border-radius: 12px;
      background: rgba(255,255,255,0.05);
      transition: background 0.3s ease;
    }
    .sidebar .profile:hover { background: rgba(255,255,255,0.12); }
    .sidebar .profile img {
      width: 50px; height: 50px; border-radius: 50%; margin-right: 15px; object-fit: cover;
      border: 2px solid #74b9ff;
    }
    .sidebar .profile span { font-weight: 600; font-size: 0.95rem; }

    /* Navigation */
    .sidebar nav a {
      display: flex;
      align-items: center;
      text-decoration: none;
      color: #dcdde1;
      padding: 12px 15px;
      margin-bottom: 10px;
      border-radius: 10px;
      font-weight: 500;
      transition: all 0.25s ease;
    }
    .sidebar nav a i { margin-right: 12px; font-size: 1.1rem; }
    .sidebar nav a.active,
    .sidebar nav a:hover {
      background: #74b9ff;
      color: #fff;
      box-shadow: 0 3px 8px rgba(116,185,255,0.3);
      transform: translateX(6px);
    }

    /* Main Content */
    .main-content {
      flex-grow: 1;
      display: flex;
      flex-direction: column;
      background: #f7f9fc;
    }
    iframe {
      width: 100%;
      height: 100vh;
      border: none;
      background: #fff;
      border-radius: 15px 0 0 15px;
      box-shadow: inset 0 0 10px rgba(0,0,0,0.05);
    }

    /* Responsive */
    @media (max-width: 768px) {
      .sidebar {
        width: 70px;
        padding: 15px 10px;
      }
      .sidebar .logo,
      .sidebar .profile span,
      .sidebar small {
        display: none;
      }
      .sidebar nav a { justify-content: center; padding: 14px; }
      .sidebar nav a i { margin: 0; }
    }

    /* Smooth animation */
    .fade {
      animation: fadeIn 0.4s ease;
    }
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(8px); }
      to { opacity: 1; transform: translateY(0); }
    }
  </style>
</head>
<body>
  <!-- Sidebar -->
  <aside class="sidebar">
    <div class="logo">Lost & Found</div>

    <div class="profile">
      <img src="tin.jpg" alt="Admin" />
      <div>
        <span id="username-Container"></span><br/>
        <small style="color: #55efc4">● Admin</small>
      </div>
    </div>

    <nav>
      <a href="#" class="active" id="dashboard"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
      <a href="#" id="post_items"><i class="fas fa-image"></i> Post Items</a>
      <a href="#" id="user_management"><i class="fas fa-users-cog"></i> User Management</a>
      <a href="#" id="manage_claims"><i class="fas fa-check-circle"></i> Manage Claims</a>
      <a href="?logout=true" id="logout"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </nav>
  </aside>

  <!-- Main content -->
  <iframe src="dashboard.php" id="main-content" class="fade"></iframe>

  <script>
    // Set username
    document.getElementById('username-Container').innerHTML = '<?php echo $_SESSION["username"]; ?>';

    // Navigation with smooth fade
    function loadPage(linkId, page, navLinks) {
      document.getElementById(linkId).addEventListener('click', function(e) {
        e.preventDefault();
        const iframe = document.getElementById('main-content');
        iframe.classList.remove('fade');
        void iframe.offsetWidth; // restart animation
        iframe.src = page;
        iframe.classList.add('fade');

        navLinks.forEach(l => l.classList.remove('active'));
        this.classList.add('active');
      });
    }

    const navLinks = document.querySelectorAll('.sidebar nav a');
    loadPage('dashboard', 'dashboard.php', navLinks);
    loadPage('post_items', 'post_items.html', navLinks);
    loadPage('user_management', 'user_management.php', navLinks);
    loadPage('manage_claims', 'admin_claims.php', navLinks);

    // Logout confirmation
    document.getElementById('logout').addEventListener('click', function(e) {
      e.preventDefault();
      if (confirm("Are you sure you want to logout?")) {
        window.location.href = this.getAttribute('href');
      }
    });
  </script>
</body>
</html>
