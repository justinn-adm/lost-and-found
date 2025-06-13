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
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@800&family=Luckiest+Guy&family=Poppins:wght@700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
  body {
    margin: 0;
    font-family: 'Inter', sans-serif;
    height: 100vh;
    background: linear-gradient(180deg, #5C4ACF 0%, #DAD6F3 120%);
    overflow-x: hidden;
    position: relative;
    color: #fff;
  }
  .overlay, .blue-stripes {
    position: absolute;
    top: 0; left: 0;
    width: 100%; height: 100%;
  }
  .overlay {
    opacity: 0.1;
    z-index: 0;
  }
  .blue-stripes {
    background: linear-gradient(120deg, rgba(255,255,255,0.15) 20%, transparent 80%);
    clip-path: polygon(65% 0, 100% 0, 100% 100%, 80% 100%);
    z-index: 1;
  }
  .right-top {
    position: fixed;
    top: 20px;
    right: 40px;
    display: flex;
    align-items: center;
    gap: 20px;
    z-index: 10;
  }
  .nav-links {
    display: flex;
    gap: 12px;
  }
  .nav-links a {
    color: white;
    font-family: 'Poppins', sans-serif;
    text-decoration: none;
    font-weight: 600;
    font-size: 14px;
    padding: 8px 12px;
    background-color: #5C4ACF;
    border-radius: 8px;
    transition: background 0.3s ease;
  }
  .nav-links a:hover {
    background-color: #4a3bbb;
  }
  .profile-icon {
    width: 44px;
    height: 44px;
    border-radius: 50%;
    overflow: hidden;
    cursor: pointer;
    border: 2px solid transparent;
    transition: border-color 0.4s ease, box-shadow 0.4s ease;
    flex-shrink: 0;
    box-shadow: 0 0 8px rgba(92, 74, 207, 0);
  }
  .profile-icon:hover {
    border-color: #5C4ACF;
    box-shadow: 0 0 12px 3px rgba(92, 74, 207, 0.6);
    transform: scale(1.08);
    transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
  }
  .profile-icon img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.4s ease;
  }
  .profile-icon:hover img {
    transform: scale(1.1);
  }
  #sidebar {
    position: absolute;
    top: 60px;
    right: 0;
    width: 280px;
    background: #1e1e1e;
    color: white;
    box-shadow: 0 8px 16px rgba(0,0,0,0.3);
    padding: 20px;
    border-radius: 10px;
    display: none;
    z-index: 1000;
  }
  #sidebar.show {
    display: block;
  }
  #sidebar img {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    object-fit: cover;
    margin-bottom: 10px;
  }
  #sidebar p {
    font-size: 16px;
    font-weight: bold;
    margin-bottom: 20px;
  }
  #sidebar a {
    display: block;
    padding: 10px 0;
    border-top: 1px solid #444;
    color: white;
    text-decoration: none;
  }
  #sidebar a:last-child {
    border-bottom: 1px solid #444;
  }
  .main-layout {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    padding: 100px 40px 40px;
    z-index: 2;
    position: relative;
  }
  .main-title {
    font-family: 'Poppins', sans-serif;
    font-size: 3.5rem;
    line-height: 1.2;
    color: white;
    display: flex;
    flex-direction: column;
    align-items: flex-start;
  }
  .main-title span {
    opacity: 0;
    transform: translateY(20px);
    animation: fadeInUp 0.8s ease forwards;
  }
  @keyframes fadeInUp {
    to {
      opacity: 1;
      transform: translateY(0);
    }
  }
</style>
</head>
<body>
  <div class="overlay"></div>
  <div class="blue-stripes"></div>

  <div class="right-top">
    <div class="nav-links">
      <a href="post_items.html"><i class="fas fa-search"></i> Report Items</a>
      <a href="my_claims.php"><i class="fas fa-clipboard-check"></i> My Claims</a>
      <a href="items.php"><i class="fas fa-list"></i> View Items</a>
    </div>
    <div class="profile-icon" onclick="toggleSidebar()">
      <img src="images/<?php echo htmlspecialchars($profile_image); ?>" alt="Profile" />
    </div>
  </div>

  <div id="sidebar">
    <h2>My Profile</h2>
    <img src="images/<?php echo htmlspecialchars($profile_image); ?>" alt="Profile Picture" />
    <p>Welcome!! <?php echo htmlspecialchars($username); ?></p>
    <a href="index.html">Log Out</a>
  </div>

  <div class="main-layout">
    <div class="main-title" id="typingText">
      <span>Lost and Found:</span>
      <span>Have You Seen This?</span>
    </div>
  </div>

  <script>
    function toggleSidebar() {
      document.getElementById('sidebar').classList.toggle('show');
    }
    document.addEventListener('click', e => {
      const sidebar = document.getElementById('sidebar');
      const icon = document.querySelector('.profile-icon');
      if (!sidebar.contains(e.target) && !icon.contains(e.target)) {
        sidebar.classList.remove('show');
      }
    });

    const phrases = [
      ["Have You Seen This?", "Tell Us"],
      ["Missing Something?", "Let Us Help!"],
      ["Report", "a Lost Item"],
      ["Reclaim", "Your Items"],
      ["Found an Item?", "Post It Here"]
    ];
    const typingBox = document.getElementById('typingText');
    let index = 0;
    function updateTypingText() {
      const [line1, line2] = phrases[index];
      typingBox.innerHTML = `<span>${line1}</span><span>${line2}</span>`;
      const spans = typingBox.querySelectorAll('span');
      spans.forEach((span, i) => {
        span.style.opacity = 0;
        span.style.transform = 'translateY(20px)';
        span.style.animation = 'fadeInUp 0.8s ease forwards';
        span.style.animationDelay = `${0.4 + i * 0.4}s`;
      });
      index = (index + 1) % phrases.length;
    }
    updateTypingText();
    setInterval(updateTypingText, 2800);
  </script>
</body>
</html>
