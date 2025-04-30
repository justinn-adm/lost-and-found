<?php 
  session_start();
  if(!isset($_SESSION['logged_in'])){
  header('Location: index.php');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Lost and Found CvSU Naic</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@800&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@1,600&display=swap" rel="stylesheet">
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Inter', sans-serif;
      color: #333;
      display: flex;
      height: 100vh;
      overflow: hidden;
      transition: background 0.3s, color 0.3s;
    }

    .sidebar {
      width: 250px;
      background-color: #004225;
      color: white;
      padding: 30px 20px;
      display: flex;
      flex-direction: column;
      gap: 30px;
    }

    .sidebar h1 {
      font-size: 20px;
      margin-bottom: 10px;
      line-height: 1.4;
    }

    .sidebar nav a {
      color: #fff;
      text-decoration: none;
      font-size: 16px;
      margin: 10px 0;
      padding: 10px 15px;
      border-radius: 8px;
      transition: background-color 0.2s ease;
      display: block;
      background-color: #226644;
    }

    .sidebar nav a:hover {
      background-color: #3BAF75;
    }

    .main-content {
      flex: 1;
      padding: 30px;
      overflow-y: auto;
      background: linear-gradient(to bottom right, #f0f4f3, #e9f7f1);
      background-image: url('https://www.transparenttextures.com/patterns/paper-fibers.png');
      position: relative;
    }

    .switch {
      position: absolute;
      top: 20px;
      right: 30px;
      display: inline-block;
      width: 80px;  
      height: 40px;  
      background-color: #ccc;
      border-radius: 50px;
      transition: 0.4s;
    }

    .switch input {
      opacity: 0;
      width: 0;
      height: 0;
    }

    .slider {
      position: absolute;
      cursor: pointer;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-color: #ccc;
      border-radius: 50px;
      transition: 0.4s;
    }

    .slider .icon {
      position: absolute;
      top: 50%;
      transform: translateY(-50%);
      font-size: 22px;
      color: white;
      transition: 0.4s;
    }

    .slider .sun {
      right: 10px;
      opacity: 1;
    }

    .slider .moon {
      left: 10px;
      opacity: 0;
    }

    input:checked + .slider {
      background-color: #2ecc71;
    }

    input:checked + .slider .sun {
      opacity: 0;
    }

    input:checked + .slider .moon {
      opacity: 1;
    }

    input:checked + .slider:before {
      transform: translateX(40px);
    }

    .slider:before {
      content: "";
      position: absolute;
      height: 30px;
      width: 30px;
      left: 5px;
      bottom: 5px;
      background-color: white;
      transition: 0.4s;
      border-radius: 50%;
    }

    .header {
      text-align: center;
      margin-bottom: 25px;
    }

    .header h2 {
      font-size: 28px;
      color: #004225;
    }

    .header p {
      font-size: 16px;
      color: #555;
      margin-top: 10px;
    }

    .quote-box {
      background-color: #dff5e3;
      border-left: 5px solid #2ecc71;
      padding: 30px;
      margin: 20px auto 30px;
      max-width: 700px;
      border-radius: 12px;
      color: #2c3e50;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
      display: flex;
      align-items: center;
      justify-content: center;
      text-align: center;
      min-height: 120px;
      font-family: 'Playfair Display', serif;
      font-style: italic;
      font-size: 20px;
      line-height: 1.6;
      font-weight: 600;
      text-shadow: 1px 1px 1px rgba(0,0,0,0.05);
    }

    body.dark-mode {
      background: #1e1e1e;
      color: #eee;
    }

    body.dark-mode .main-content {
      background: #2c2c2c;
    }

    body.dark-mode .header h2 {
      color: #b8f5cb;
    }

    body.dark-mode .header p {
      color: #ccc;
    }

    body.dark-mode .quote-box {
      background-color: #3a3a3a;
      border-left-color: #27ae60;
      color: #d0f0dd;
    }
  </style>
</head>
<body>

  <div class="sidebar">
    <h1>Welcome to CvSU Naic<br>Lost and Found</h1>
    <nav>
      <a href="logout.php">🔙 Back to Login</a>
      <a href="items.html">📦 View Lost Items</a>
      <a href="post_items.html">📝 Post Lost Items</a>
      <a href="#">📞 Talk to an Admin</a>
    </nav>
  </div>

  <div class="main-content">
    <label class="switch">
      <input type="checkbox" onchange="toggleTheme()" id="themeSwitch">
      <span class="slider">
        <span class="icon sun">☀️</span>
        <span class="icon moon">🌙</span>
      </span>
    </label>

    <div class="header">
      <h2>Burara ka ba? Tanga ka kasi!!</h2>
      <p>This platform allows CvSU Naic students to report and find lost items quickly and easily.</p>
    </div>

    <div class="quote-box">
      “It’s not about the item, it’s about the value it holds for someone.”
    </div>
  </div>

  <script>
    function toggleTheme() {
      document.body.classList.toggle("dark-mode");
    }
  </script>

</body>
</html>
