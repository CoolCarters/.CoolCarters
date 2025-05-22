<?php
session_start();
require_once '../connection.php';

// Ensure trader_id is set
$trader_id = $_SESSION['trader_id'] ?? 0;
$shops = [];

if ($trader_id) {
    $query = "SELECT * FROM SHOP WHERE TRADER_ID = :trader_id ORDER BY SHOP_ID DESC";
    $stmt = oci_parse($conn, $query);
    oci_bind_by_name($stmt, ":trader_id", $trader_id);
    
    if (oci_execute($stmt)) {
        while ($row = oci_fetch_assoc($stmt)) {
            $shops[] = $row;
        }
    } else {
        $e = oci_error($stmt);
        echo "❌ SQL Error: " . $e['message'];
    }

    oci_free_statement($stmt);
}
?>
<?php include 'navbar.php'; ?>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const toggleBtn = document.getElementById('toggleBtn');
    if (toggleBtn) {
        toggleBtn.addEventListener('click', function () {
            document.body.classList.toggle('sidebar-collapsed');
        });
    }
});
</script>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  <style>
    body.sidebar-collapsed #layoutWrapper {
      margin-left: 4rem;
    }

    @media (min-width: 1024px) {
      body:not(.sidebar-collapsed) #layoutWrapper {
        margin-left: 16rem;
      }
    }

    @media (max-width: 768px) {
      #sidebar {
        transform: translateX(-100%);
        transition: transform 0.3s ease;
      }

      body.sidebar-open #sidebar {
        transform: translateX(0);
      }

      body.sidebar-open #layoutWrapper {
        margin-left: 0;
      }
    }

    .action-buttons button {
      padding: 0.4rem 0.6rem;
      border: none;
      border-radius: 4px;
      color: white;
      cursor: pointer;
    }

    .action-buttons .edit {
      background-color: #4e73df;
    }

    .action-buttons .delete {
      background-color: #dc3545;
    }
  </style>
</head>
<body class="bg-gray-100 font-sans">

<!-- WRAPPER -->
<div id="layoutWrapper" class="transition-all duration-300">

  <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-24 transition-all duration-300">

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
      <h1 class="text-xl font-semibold text-gray-800 mb-4 sm:mb-0">My Shops</h1>
      <a href="traderAddShop.php" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
        <i class="fas fa-plus"></i> Add Shop
      </a>
    </div>

    <div class="mb-6">
      <div class="relative max-w-sm w-full">
        <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
        <input type="text" name="search" placeholder="Search shops..."
               class="w-full pl-10 pr-4 py-2 border rounded-md shadow-sm focus:ring-2 focus:ring-blue-500 focus:outline-none" />
      </div>
    </div>

    <div class="overflow-x-auto">
      <table class="min-w-full bg-white border border-gray-200 shadow-md rounded-lg overflow-hidden text-sm">
        <thead class="bg-gray-100 text-gray-700">
          <tr>
            <th class="px-4 py-3 text-left">Shop ID</th>
            <th class="px-4 py-3 text-left">Shop Name</th>
            <th class="px-4 py-3 text-left">Image</th>
            <th class="px-4 py-3 text-left">Location</th>
            <th class="px-4 py-3 text-left">Description</th>
            <th class="px-4 py-3 text-left">Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!empty($shops)): ?>
            <?php foreach ($shops as $shop): ?>
              <tr class="border-t">
                <td class="px-4 py-3"><?= htmlspecialchars($shop['SHOP_ID']) ?></td>
                <td class="px-4 py-3"><?= htmlspecialchars($shop['SHOP_NAME']) ?></td>
                <td class="px-4 py-3">
                  <img src="../images/default-shop.png" alt="<?= htmlspecialchars($shop['SHOP_NAME']) ?>" class="w-14 h-14 object-cover rounded" />
                </td>
                <td class="px-4 py-3"><?= htmlspecialchars($shop['LOCATION']) ?></td>
                <td class="px-4 py-3"><?= htmlspecialchars($shop['DESCRIPTION']) ?></td>
                <td class="px-4 py-3">
                  <div class="action-buttons flex gap-2">
                    <button class="edit" onclick="editShop(<?= $shop['SHOP_ID'] ?>)">
                      <i class="fas fa-edit"></i>
                    </button>
                    <button class="delete" onclick="deleteShop(<?= $shop['SHOP_ID'] ?>)">
                      <i class="fas fa-trash"></i>
                    </button>
                  </div>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr>
              <td colspan="6" class="px-4 py-6 text-center text-gray-500">No shops found. Add your first shop!</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>

  </main>
</div>

<script>
  const searchInput = document.querySelector('input[name="search"]');
  searchInput.addEventListener('input', function () {
    const filter = this.value.toLowerCase();
    document.querySelectorAll('table tbody tr').forEach(row => {
      const text = row.innerText.toLowerCase();
      row.style.display = text.includes(filter) ? '' : 'none';
    });
  });

  function editShop(shopId) {
    window.location.href = 'traderEditShop.php?id=' + shopId;
  }

  function deleteShop(shopId) {
    if (confirm('Are you sure you want to delete this shop?')) {
      fetch('delete_shop.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'shop_id=' + shopId
      })
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          alert('Shop deleted successfully');
          location.reload();
        } else {
          alert('Error: ' + data.message);
        }
      })
      .catch(err => {
        console.error(err);
        alert('Error deleting shop');
      });
    }
  }
</script>

<?php include 'traderFooter.php'; ?>
</body>
</html>
