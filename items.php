<?php
  include 'db.php';

  $total_items = 0;
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
  <title>Lost and Found - Items</title>

  <script>
    function fetchItems() {
      fetch('get_items.php')
        .then(res => res.json())
        .then(data => {
          const grid = document.getElementById('itemsGrid');
          grid.innerHTML = '';
          data.forEach(item => {
            const card = document.createElement('div');
            card.className = 'item-card';
            card.innerHTML = `
              <img src="${item.image_path}" alt="${item.name}">
              <p>${item.name}</p>
              <button onclick="showItemDetails('${item.id}')">Detail</button>
            `;
            grid.appendChild(card);
          });
        })
        .catch(error => console.error('Error fetching items:', error));
    }

    function showItemDetails(itemId) {
      fetch(`get_item_details.php?id=${itemId}`)
        .then(res => res.json())
        .then(item => {
          const modal = document.getElementById('itemModal');
          modal.style.display = 'flex';
          document.getElementById('modalItemName').innerText = item.name;
          document.getElementById('modalItemDate').innerText = item.date;
          document.getElementById('modalItemLocation').innerText = item.location;
          document.getElementById('modalItemDescription').innerText = item.description;
          document.getElementById('modalItemImage').src = item.image_path;
        })
        .catch(error => console.error('Error fetching item details:', error));
    }

    function closeModal() {
      document.getElementById('itemModal').style.display = 'none';
    }

    document.addEventListener('DOMContentLoaded', fetchItems);
  </script>

  <style>
    .summary {
      text-align: center;
      font-size: 1.2rem;
      padding: 20px;
      font-weight: bold;
    }

    .items-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
      gap: 20px;
      max-width: 1200px;
      margin: 0 auto;
    }

    .item-card {
      background: #ffffff;
      border-radius: 12px;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
      text-align: center;
      padding: 15px;
      transition: transform 0.3s ease;
    }

    .item-card img {
      width: 100%;
      height: 160px;
      object-fit: cover;
      border-bottom: 1px solid #eee;
    }

    .item-card p {
      margin: 10px 0;
      font-size: 18px;
      font-weight: bold;
      color: #333;
    }

    .item-card button {
      background-color: #16a085;
      border: none;
      color: white;
      padding: 10px 15px;
      margin-top: 10px;
      cursor: pointer;
      border-radius: 6px;
      transition: background-color 0.2s ease;
    }

    .item-card button:hover {
      background-color: #12876f;
    }

    .item-card:hover {
      transform: scale(1.03);
    }

    .modal {
      display: none;
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 40%;
      background-color: rgba(0, 0, 0, 0.7);
      z-index: 1;
      align-items: center;
      justify-content: center;
    }

    .modal-content {
      background-color: white;
      padding: 20px;
      border-radius: 10px;
      max-width: 600px;
      width: 80%;
      text-align: center;
    }

    .modal img {
      width: 100%;
      height: 50%;
      margin-bottom: 20px;
    }

    .modal .close {
      background-color: #16a085;
      color: white;
      border: none;
      padding: 10px 20px;
      margin-top: 20px;
      border-radius: 6px;
      cursor: pointer;
    }

    .modal .close:hover {
      background-color: #12876f;
    }
  </style>
</head>
<body>

  <div class="summary">
    Total Lost Items: <span style="color:#16a085;"><?php echo $total_items; ?></span>
  </div>

  <div class="items-grid" id="itemsGrid"></div>

  <div id="itemModal" class="modal">
    <div class="modal-content">
      <h3 id="modalItemName"></h3>
      <img id="modalItemImage" src="" alt="Item Image">
      <p><strong>Date:</strong> <span id="modalItemDate"></span></p>
      <p><strong>Location:</strong> <span id="modalItemLocation"></span></p>
      <p><strong>Description:</strong> <span id="modalItemDescription"></span></p>
      <button class="close" onclick="closeModal()">Close</button>
    </div>
  </div>
</body>
</html>
