<?php
session_start();
include 'db.php';

$user_id = $_SESSION['user_id'];

$sql = "SELECT profile_img, username FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($profile_image, $username);
$stmt->fetch();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Lost and Found</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    body {
      margin: 0;
      font-family: Arial, sans-serif;
      background-color: #ffffff;
      color: white;
      padding-top: 70px;
    }
    header {
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 10px 40px;
      background: rgb(53, 51, 51);
      z-index: 1;
    }
    .navbar-title {
      display: flex;
      align-items: center;
      gap: 15px;
    }
    .navbar-title img {
      width: 80px;
      height: 50px;
      object-fit: contain;
    }
    .icon-container {
      display: flex;
      align-items: center;
      gap: 10px;
    }
    .profile-container, .post-container {
      display: flex;
      align-items: center;
      gap: 5px;
      position: relative;
    }
    .profile-icon, .post-icon {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      object-fit: cover;
      cursor: pointer;
      display: flex;
      justify-content: center;
      align-items: center;
      font-size: 24px;
      background-color: #3b82f6;
      color: white;
    }
    .post-icon {
      background-color: gray;
      color: rgba(255, 255, 255, 0.6);
      font-size: 28px;
      border: none;
      line-height: 0;
      text-decoration: none;
    }
    .tooltip {
      visibility: hidden;
      background-color: rgba(0, 0, 0, 0.7);
      color: white;
      text-align: center;
      border-radius: 15px;
      padding: 5px 10px;
      position: absolute;
      top: 50px;
      left: -25px;
      font-size: 12px;
      z-index: 9999;
    }
    .profile-container:hover .tooltip, .post-container:hover .tooltip {
      visibility: visible;
    }
    .sidebar {
      position: fixed;
      top: 0;
      right: -250px;
      width: 250px;
      height: 100%;
      background-color: #1e293b;
      box-shadow: -2px 0 10px rgba(0, 0, 0, 0.5);
      padding-top: 80px;
      padding-left: 20px;
      z-index: 9999;
      opacity: 0;
      pointer-events: none;
      transition: right 0.4s ease, opacity 0.3s ease;
    }
    .sidebar.open {
      right: 0;
      opacity: 1;
      pointer-events: auto;
    }
    .sidebar a {
      display: flex;
      align-items: center;
      padding: 12px 20px;
      color: white;
      text-decoration: none;
      font-weight: normal;
      transition: background 0.3s ease;
      border-radius: 8px;
      margin: 10px 0;
      width: 200px;
      justify-content: flex-start;
    }
    .sidebar a:hover {
      background-color: #374151;
    }
    .sidebar a.active {
      background-color: #6e9cff;
      color: white;
    }
    .sidebar i {
      margin-right: 12px;
      font-size: 1.2rem;
    }
    .sidebar a span {
      font-size: 0.9rem;
      font-weight: 500;
    }
    .sidebar .greeting {
      font-size: 30px;
      font-weight: bold;
      color: white;
      text-align: center;
      margin-bottom: 30px;
    }
    @media (max-width: 600px) {
      header {
        flex-direction: column;
        align-items: flex-start;
      }
      .navbar-title {
        margin-bottom: 10px;
      }
      .profile-icon {
        align-self: flex-end;
      }
    }
  </style>
</head>
<body>

  <header>
    <div class="navbar-title">
      <img src="images/logo.png" alt="CvSU Logo">
    </div>
    <div class="icon-container">
      <div class="post-container">
        <a href="post_items.html" class="post-icon">📤</a>
        <div class="tooltip">Post Items</div>
      </div>
      <div class="profile-container">
        <img src="images/<?php echo htmlspecialchars($profile_image); ?>" alt="User Profile" class="profile-icon" onclick="toggleSidebar()">
        <div class="tooltip">Profile</div>
      </div>
    </div>
  </header>

  <div class="sidebar" id="sidebar">
    <div class="greeting">
      Hello!! <?php echo htmlspecialchars($username); ?>
    </div>
    <a href="#" class="active">
      <span>🏠 Home</span>
    </a>
    <a href="index.html">
      <span>🚪 Logout</span>
    </a>
  </div>

  <script>
    const sidebar = document.getElementById('sidebar');

    function toggleSidebar() {
      sidebar.classList.toggle('open');
    }

    document.addEventListener('click', function (e) {
      const isClickInside = sidebar.contains(e.target) || e.target.closest('.profile-icon');
      if (!isClickInside) {
        sidebar.classList.remove('open');
      }
    });

    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape') {
        sidebar.classList.remove('open');
      }
    });
  </script>

</body>
</html>
