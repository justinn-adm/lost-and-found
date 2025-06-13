<?php
  include 'db.php';

  $users = 0;
  $total_items = 0;

  $query = "SELECT * FROM users WHERE role = 'user'";
  $stmt = $conn->prepare($query);
$stmt->execute();
$stmt->store_result();

$users = $stmt->num_rows;

  $query = "SELECT id FROM lost_items";
  $stmt = $conn->prepare($query);
$stmt->execute();
$stmt->store_result();
$total_items = $stmt->num_rows;
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">
  <title>Document</title>
  <style>
    *{
      font-family: 'Inter', sans-serif;
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
    </style>
</head>
<body>

    <div class="dashboard">
      <div class="card" style="background-color:#17a2b8; color:white;">
<h2><?php echo $users ?></h2>
        <p>Total Members</p>
       
      </div>
      <div class="card" style="background-color:#007bff; color:white;">
<h2><?php echo $total_items ?></h2>
        <p>Total Lost Items</p>
  
      </div>
      <div class="card" style="background-color:#28a745; color:white;">
        <h2>53</h2>
        <p>Total Found Items</p>
       
      </div>
    </div>

    <footer>
      <strong><a href="#" style="color:#007bff; text-decoration:none;">Lost and Found System</a></strong>.
    </footer>
</body>
</html>
