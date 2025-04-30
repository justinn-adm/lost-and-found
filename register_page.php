<?php 
session_start();

if(!empty($_SESSION['submission'])){
  if($_SESSION['submission']){
    echo "<script>alert('registration successful')</script>";
  }
  unset($_SESSION['submission']);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Login</title>
  <style>
    body {
      margin: 0;
      padding: 0;
      background: white;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }

    .login-container {
      width: 800px;
      background: rgb(255, 174, 217);
      border-radius: 12px;
      box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
      display: flex;
      overflow: hidden;
    }

    .left-side {
      flex: 1;
      padding: 3rem;
    }

    .left-side h2 {
      font-size: 2rem;
      margin-bottom: 2rem;
    }

    .form-group {
      margin-bottom: 1.5rem;
    }

    .form-group label {
      display: block;
      margin-bottom: 0.5rem;
      font-weight: 600;
    }

    .form-group input {
      width: 100%;
      padding: 0.9rem;
      border: 1px solid #ccc;
      border-radius: 6px;
      font-size: 1rem;
    }

    .btn {
      width: 100%;
      padding: 1rem;
      background-color: #f29c1f;
      color: white;
      border: none;
      border-radius: 6px;
      font-size: 1rem;
      cursor: pointer;
      margin-top: 1rem;
      transition: opacity 0.5s ease-in;
    }

    .btn:hover {
      background-color: #e38b12;
    }

    .links {
      text-align: center;
      margin-top: 1.5rem;
      font-size: 0.9rem;
    }

    .links a {
      text-decoration: none;
      color: #007BFF;
    }

    .links a:hover {
      text-decoration: underline;
    }


    .right-side {
      flex: 1;
      padding: 0;
      margin: 0;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .right-side img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      display: block;
    }
  </style>
  <script src="register.js">
  
  </script>
</head>
<body>
  <div class="login-container">
    <div class="left-side">
      <h2>Sign Up</h2>
      <form method="POST" action="register.php">
        <div class="form-group">
          <label for="user">Username</label>
          <input type="text" id="username" name="username" placeholder="Enter a Username"  required />
            <small id="username-status"></small>
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" placeholder="Enter your Email" required />
            <small id="email-status"></small>
        </div>
        <div class="form-group">
          <label for="password">Password</label>
          <input type="password" id="password" name="password" placeholder="Enter your password" required />
        </div>
        <button id="submit-btn" type="submit" class="btn">Create</button>
      </form>
    </div>
    <div class="right-side">
      <img src="p.jpg" alt="Illustration" />
    </div>
  </div>
</body>
</html>
