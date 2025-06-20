<?php
include 'db.php';
session_start();

$total_items = 0;
$query = "SELECT id FROM lost_items";
$stmt = $conn->prepare($query);
$stmt->execute();
$stmt->store_result();
$total_items = $stmt->num_rows;

$isAdmin = isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Lost and Found - Items</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .items-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
      gap: 20px;
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
    .claimed-badge {
      position: absolute;
      top: 10px;
      right: 10px;
      background: #dc3545;
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
  </style>
</head>
<body class="bg-light">

<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <a href="lost.php" class="btn btn-outline-secondary">&larr; Home</a>
    <h4 class="mb-0">Total Lost Items: <span class="text-success fw-bold"><?= $total_items; ?></span></h4>
  </div>
  <div class="items-grid" id="itemsGrid"></div>
</div>

<div class="modal" id="itemModal">
  <div class="modal-content">
    <h5 id="modalItemName" class="fw-bold"></h5>
    <img id="modalItemImage" src="" alt="Item Image">
    <p><strong>Date:</strong> <span id="modalItemDate"></span></p>
    <p><strong>Location:</strong> <span id="modalItemLocation"></span></p>
    <p><strong>Description:</strong> <span id="modalItemDescription"></span></p>
    <p><strong>Posted by:</strong> <span id="modalItemPoster"></span></p>
    <p id="claimantInfo" style="display:none;"><strong>Claimed by:</strong> <span id="modalClaimant"></span></p>
    <input type="hidden" id="currentItemId">
    <div id="alreadyClaimedNotice" style="display:none;" class="text-danger fw-bold">This item has already been claimed.</div>
    <button class="btn btn-secondary mt-2" onclick="closeModal()">Close</button>
    <button class="btn btn-warning mt-2" id="claimBtn" onclick="showClaimForm()">Claim This Item</button>
    <form id="claimForm" class="mt-3" style="display:none;" enctype="multipart/form-data">
      <textarea id="claimMessage" class="form-control mb-2" placeholder="Explain why this is yours..." required></textarea>
      <input type="file" id="claimProof" accept="image/*" class="form-control mb-2" required>
      <button type="submit" class="btn btn-warning">Submit Claim</button>
    </form>
  </div>
</div>

<script>
  const isAdmin = <?= json_encode($isAdmin) ?>;

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
            <p class="fw-bold mt-2">${item.name}</p>
            <button class="btn btn-success btn-sm mt-1" onclick="showItemDetails(${item.id})">Detail</button>
            ${isAdmin ? `<button class="btn btn-danger btn-sm mt-1" onclick="deleteItem(${item.id})">Delete</button>` : ''}
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
        if (item.error) {
          alert(item.error);
          return;
        }
        document.getElementById('modalItemName').innerText = item.name;
        document.getElementById('modalItemImage').src = item.image_path;
        document.getElementById('modalItemDate').innerText = item.date_found;
        document.getElementById('modalItemLocation').innerText = item.location;
        document.getElementById('modalItemDescription').innerText = item.description;
        document.getElementById('modalItemPoster').innerText = item.anonymous == 1 ? "Anonymous" : (item.uploader_name || "Unknown");
        document.getElementById('currentItemId').value = item.id;
        document.getElementById('claimForm').style.display = 'none';
        document.getElementById('claimMessage').value = '';

        if (item.claimed == 1) {
          document.getElementById('claimBtn').style.display = 'none';
          document.getElementById('alreadyClaimedNotice').style.display = 'block';

          if (item.claimant_name) {
            document.getElementById('modalClaimant').innerText = item.claimant_name;
            document.getElementById('claimantInfo').style.display = 'block';
          } else {
            document.getElementById('claimantInfo').style.display = 'none';
          }
        } else {
          document.getElementById('claimBtn').style.display = 'inline-block';
          document.getElementById('alreadyClaimedNotice').style.display = 'none';
          document.getElementById('claimantInfo').style.display = 'none';
        }

        document.getElementById('itemModal').style.display = 'flex';
      })
      .catch(err => {
        console.error(err);
        alert('Failed to load item details.');
      });
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
    const formData = new FormData();
    formData.append('item_id', document.getElementById('currentItemId').value);
    formData.append('message', document.getElementById('claimMessage').value);
    formData.append('proof_image', document.getElementById('claimProof').files[0]);

    fetch('claim_item.php', {
      method: 'POST',
      body: formData
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

  function deleteItem(id) {
    if (confirm("Are you sure you want to delete this item?")) {
      fetch('delete_item.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({ item_id: id })
      })
      .then(res => res.text())
      .then(response => {
        alert(response);
        fetchItems();
      })
      .catch(err => {
        console.error(err);
        alert('Failed to delete item.');
      });
    }
  }

  window.addEventListener('click', function(event) {
    const modal = document.getElementById('itemModal');
    if (event.target === modal) {
      closeModal();
    }
  });

  document.addEventListener('DOMContentLoaded', fetchItems);
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
