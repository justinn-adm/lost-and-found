<?php
include 'db.php';
session_start();

// Count total items
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
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Lost and Found - Items</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f9f9f9;
      margin: 0;
      padding: 20px;
    }
    .summary {
      text-align: center;
      font-size: 1.2rem;
      margin-bottom: 20px;
    }
    .items-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
      gap: 20px;
      max-width: 1200px;
      margin: 0 auto;
    }
    .item-card {
      background: #fff;
      border-radius: 8px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
      padding: 10px;
      text-align: center;
      position: relative;
      transition: transform 0.2s ease;
    }
    .item-card:hover {
      transform: scale(1.03);
    }
    .item-card img {
      width: 100%;
      height: 160px;
      object-fit: cover;
      border-radius: 4px;
    }
    .item-card p {
      margin: 10px 0;
      font-weight: bold;
    }
    .item-card button {
      background: #16a085;
      color: #fff;
      border: none;
      padding: 8px 16px;
      border-radius: 4px;
      cursor: pointer;
    }
    .item-card button:hover {
      background: #12876f;
    }
    .claimed-badge {
      position: absolute;
      top: 10px;
      right: 10px;
      background: #e74c3c;
      color: #fff;
      padding: 4px 8px;
      font-size: 12px;
      border-radius: 4px;
    }
    .modal {
      display: none;
      position: fixed;
      top: 0; left: 0;
      width: 100%; height: 100%;
      background: rgba(0,0,0,0.6);
      justify-content: center;
      align-items: center;
      z-index: 1000;
    }
    .modal-content {
      background: #fff;
      padding: 20px;
      width: 90%;
      max-width: 500px;
      border-radius: 8px;
      position: relative;
      text-align: center;
    }
    .modal-content img {
      width: 100%;
      max-height: 250px;
      object-fit: cover;
      margin-bottom: 15px;
      border-radius: 4px;
    }
    .modal-content h3 {
      margin-top: 0;
    }
    .modal-content p {
      margin: 8px 0;
    }
    .modal-content button {
      margin-top: 15px;
      background: #16a085;
      color: #fff;
      border: none;
      padding: 10px 20px;
      border-radius: 4px;
      cursor: pointer;
    }
    .modal-content button:hover {
      background: #12876f;
    }
    #claimBtn {
      background: #e67e22;
    }
    #claimBtn:hover {
      background: #d35400;
    }
    #claimForm textarea {
      width: 100%;
      padding: 8px;
      border: 1px solid #ccc;
      border-radius: 4px;
      resize: vertical;
    }
    #claimForm button {
      background: #e67e22;
    }
    #claimForm button:hover {
      background: #d35400;
    }
    #alreadyClaimedNotice {
      color: #e74c3c;
      font-weight: bold;
      margin-top: 15px;
    }
  </style>
</head>
<body>

  <div class="summary">
    Total Lost Items: <strong style="color:#16a085;"><?php echo $total_items; ?></strong>
  </div>

  <div class="items-grid" id="itemsGrid"></div>

  <div class="modal" id="itemModal">
    <div class="modal-content">
      <h3 id="modalItemName"></h3>
      <img id="modalItemImage" src="" alt="Item Image">
      <p><strong>Date:</strong> <span id="modalItemDate"></span></p>
      <p><strong>Location:</strong> <span id="modalItemLocation"></span></p>
      <p><strong>Description:</strong> <span id="modalItemDescription"></span></p>
      <p><strong>Posted by:</strong> <span id="modalItemPoster"></span></p>

      <input type="hidden" id="currentItemId">

      <button onclick="closeModal()">Close</button>
      <button id="claimBtn" onclick="showClaimForm()">Claim This Item</button>
      <div id="alreadyClaimedNotice" style="display:none;">This item has already been claimed.</div>

      <form id="claimForm" style="margin-top: 15px; display:none;">
        <textarea id="claimMessage" placeholder="Explain why this is yours..." required></textarea>
        <button type="submit">Submit Claim</button>
      </form>
    </div>
  </div>

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
              ${item.claimed == 1 ? '<div class="claimed-badge">Claimed</div>' : ''}
              <img src="${item.image_path}" alt="${item.name}">
              <p>${item.name}</p>
              <button onclick="showItemDetails(${item.id})">Detail</button>
            `;
            grid.appendChild(card);
          });
        })
        .catch(err => console.error('Error fetching items:', err));
    }

    function showItemDetails(id) {
      fetch(`get_item_details.php?id=${id}`)
        .then(res => res.json())
        .then(item => {
          document.getElementById('modalItemName').innerText = item.name;
          document.getElementById('modalItemImage').src = item.image_path;
          document.getElementById('modalItemDate').innerText = item.date_found;
          document.getElementById('modalItemLocation').innerText = item.location;
          document.getElementById('modalItemDescription').innerText = item.description;
          const poster = item.anonymous == 1 ? "Anonymous" : (item.uploader_name || "Unknown");
          document.getElementById('modalItemPoster').innerText = poster;

          document.getElementById('currentItemId').value = item.id;

          // Reset claim form
          document.getElementById('claimForm').style.display = 'none';
          document.getElementById('claimMessage').value = '';

          if (item.claimed == 1) {
            document.getElementById('claimBtn').style.display = 'none';
            document.getElementById('alreadyClaimedNotice').style.display = 'block';
          } else {
            document.getElementById('claimBtn').style.display = 'inline-block';
            document.getElementById('alreadyClaimedNotice').style.display = 'none';
          }

          document.getElementById('itemModal').style.display = 'flex';
        })
        .catch(err => console.error('Error fetching details:', err));
    }

    function closeModal() {
      document.getElementById('itemModal').style.display = 'none';
    }

    function showClaimForm() {
      document.getElementById('claimForm').style.display = 'block';
      document.getElementById('claimBtn').style.display = 'none';
    }

    document.getElementById('claimForm').addEventListener('submit', function(e) {
      e.preventDefault();
      const itemId = document.getElementById('currentItemId').value;
      const message = document.getElementById('claimMessage').value;

      fetch('claim_item.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: `item_id=${encodeURIComponent(itemId)}&message=${encodeURIComponent(message)}`
      })
      .then(res => res.text())
      .then(response => {
        alert(response);
        closeModal();
        fetchItems();
      })
      .catch(err => {
        console.error(err);
        alert('Error submitting claim.');
      });
    });

    window.addEventListener('click', function(event) {
      const modal = document.getElementById('itemModal');
      if (event.target === modal) {
        closeModal();
      }
    });

    document.addEventListener('DOMContentLoaded', fetchItems);
  </script>

</body>
</html>
