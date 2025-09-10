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
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
  <style>
    body {
      background: linear-gradient(180deg, #f9fafc 0%, #eef1f5 100%);
      font-family: 'Inter', sans-serif;
      color: #333;
    }

    .page-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      flex-wrap: wrap;
      margin-bottom: 20px;
    }

    .page-header h4 {
      font-weight: 700;
    }

    .items-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
      gap: 24px;
    }

    .item-card {
      background: #fff;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.08);
      padding: 12px;
      text-align: center;
      position: relative;
      transition: all 0.3s ease;
      cursor: pointer;
    }

    .item-card:hover {
      transform: translateY(-6px);
      box-shadow: 0 8px 20px rgba(0,0,0,0.15);
    }

    .item-card img {
      width: 100%;
      height: 180px;
      object-fit: cover;
      border-radius: 8px;
      margin-bottom: 10px;
    }

    .item-card p {
      margin: 0;
      font-weight: 600;
      color: #222;
    }

    .claimed-badge {
      position: absolute;
      top: 12px;
      right: 12px;
      background: #dc3545;
      color: #fff;
      padding: 4px 10px;
      font-size: 13px;
      font-weight: 600;
      border-radius: 8px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.2);
    }

    /* Modal Styling */
    .modal-custom {
      display: none;
      position: fixed;
      top: 0; left: 0;
      width: 100%; height: 100%;
      background: rgba(0,0,0,0.6);
      justify-content: center;
      align-items: center;
      z-index: 1050;
      padding: 20px;
    }

    .modal-content-custom {
      background: #fff;
      padding: 25px;
      width: 100%;
      max-width: 550px;
      border-radius: 16px;
      position: relative;
      animation: slideDown 0.35s ease;
    }

    @keyframes slideDown {
      from { transform: translateY(-40px); opacity: 0; }
      to { transform: translateY(0); opacity: 1; }
    }

    .modal-content-custom img {
      width: 100%;
      max-height: 260px;
      object-fit: cover;
      border-radius: 10px;
      margin-bottom: 15px;
    }

    .btn-close-custom {
      position: absolute;
      top: 12px;
      right: 12px;
      background: transparent;
      border: none;
      font-size: 1.3rem;
      cursor: pointer;
    }

    .btn-action {
      margin: 4px 0;
      width: 100%;
    }
  </style>
</head>
<body>

<div class="container py-4">
  <div class="page-header">
    <a href="lost.php" class="btn btn-outline-dark"><i class="fa fa-arrow-left"></i> Back</a>
    <h4 class="mb-0">Total Lost Items: <span class="text-primary fw-bold"><?= $total_items; ?></span></h4>
  </div>

  <div class="items-grid" id="itemsGrid"></div>
</div>

<!-- Custom Modal -->
<div class="modal-custom" id="itemModal">
  <div class="modal-content-custom">
    <button class="btn-close-custom" onclick="closeModal()">&times;</button>
    <h5 id="modalItemName" class="fw-bold mb-2"></h5>
    <img id="modalItemImage" src="" alt="Item Image">
    <p><i class="fa fa-calendar"></i> <strong>Date:</strong> <span id="modalItemDate"></span></p>
    <p><i class="fa fa-map-marker-alt"></i> <strong>Location:</strong> <span id="modalItemLocation"></span></p>
    <p><i class="fa fa-align-left"></i> <strong>Description:</strong> <span id="modalItemDescription"></span></p>
    <p><i class="fa fa-user"></i> <strong>Posted by:</strong> <span id="modalItemPoster"></span></p>
    <p id="claimantInfo" style="display:none;"><i class="fa fa-check-circle"></i> <strong>Claimed by:</strong> <span id="modalClaimant"></span></p>

    <input type="hidden" id="currentItemId">

    <div id="alreadyClaimedNotice" style="display:none;" class="text-danger fw-bold my-2">
      <i class="fa fa-exclamation-triangle"></i> This item has already been claimed.
    </div>

    <button class="btn btn-secondary btn-action" onclick="closeModal()">Close</button>
    <button class="btn btn-warning btn-action" id="claimBtn" onclick="showClaimForm()">Claim This Item</button>

    <form id="claimForm" class="mt-3" style="display:none;" enctype="multipart/form-data">
      <textarea id="claimMessage" class="form-control mb-2" placeholder="Explain why this is yours..." required></textarea>
      <input type="file" id="claimProof" accept="image/*" class="form-control mb-2" required>
      <button type="submit" class="btn btn-primary w-100">Submit Claim</button>
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
          card.onclick = () => showItemDetails(item.id);
          card.innerHTML = `
            ${item.claimed == 1 ? '<div class="claimed-badge">Claimed</div>' : ''}
            <img src="${item.image_path}" alt="${item.name}">
            <p>${item.name}</p>
            ${isAdmin ? `<button class="btn btn-danger btn-sm mt-2" onclick="event.stopPropagation(); deleteItem(${item.id})"><i class="fa fa-trash"></i> Delete</button>` : ''}
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
        if (item.error) return alert(item.error);

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
        headers: { 'Content-Type': 'application/json' },
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
    if (event.target === modal) closeModal();
  });

  document.addEventListener('DOMContentLoaded', fetchItems);
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
