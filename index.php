<!DOCTYPE html>
<html lang="en" class="h-full">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>CoolCarters | All Products</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background-color: #f9fafb;
    }

    .card:hover {
      transform: scale(1.03);
      transition: all 0.3s ease;
    }

    .card {
      transition: transform 0.3s ease;
    }
  </style>
</head>

<body class="text-gray-800 flex flex-col min-h-screen">
  <?php

  include "homeNavbar.php";

  ?>

  <?php
  require_once "./connection.php";

  $sql = "SELECT PRODUCT_ID, PRODUCT_NAME, PRICE, CATEGORY, STOCK FROM PRODUCT";
  $stid = oci_parse($conn, $sql);
  oci_execute($stid);

  $products = [];
  while ($row = oci_fetch_assoc($stid)) {
    $products[] = [
      'id' => $row['PRODUCT_ID'],
      'filename' => "getImage.php?id=" . $row['PRODUCT_ID'],
      'product' => $row['PRODUCT_NAME'],
      'price' => '$' . number_format($row['PRICE'], 2),
      'sold' => $row['STOCK'] == 0 ? 'Yes' : 'No',
      'category' => $row['CATEGORY']
    ];
  }

  oci_free_statement($stid);
  oci_close($conn);

  $categories = array_unique(array_map(fn($p) => $p['category'], $products));
  ?>

  <main class="container mx-auto px-6 py-8">
    <h1 class="text-4xl font-bold text-center mb-10">🛒 Our Fresh Picks</h1>

    <!-- Filters -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-center mb-10">
      <input
        id="searchInput"
        type="text"
        placeholder="🔍 Search products..."
        class="p-3 border rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 w-full col-span-1 md:col-span-2" />
      <select
        id="categoryFilter"
        class="p-3 border rounded-md shadow-sm w-full">
        <option value="">All Categories</option>
        <?php foreach ($categories as $cat): ?>
          <option value="<?= $cat ?>"><?= $cat ?></option>
        <?php endforeach; ?>
      </select>
      <div class="flex gap-2">
        <input id="minPrice" type="number" placeholder="Min $" class="p-3 border rounded-md w-full" />
        <input id="maxPrice" type="number" placeholder="Max $" class="p-3 border rounded-md w-full" />
      </div>
    </div>

    <!-- Product Grid -->
    <div id="productGrid" class="grid sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
      <?php foreach ($products as $p):
        $priceNum = floatval(substr($p['price'], 1));
      ?>
        <div
          class="card bg-white rounded-lg shadow-md overflow-hidden cursor-pointer hover:shadow-xl"
          data-category="<?= $p['category'] ?>"
          data-price="<?= $priceNum ?>"
          onclick="location.href='product.php?id=<?= $p['id'] ?>'">
          <img src="<?= $p['filename'] ?>" alt="<?= $p['product'] ?>"
            class="w-full h-48 object-cover"
            onerror="this.src='https://via.placeholder.com/300x200?text=No+Image'" />
          <div class="p-4">
            <h2 class="font-semibold text-lg"><?= $p['product'] ?></h2>
            <p class="text-gray-600 mb-1">💷 <?= $p['price'] ?></p>
            <p class="text-sm text-gray-500">Sold: <?= $p['sold'] ?></p>
          </div>
        </div>
      <?php endforeach; ?>
    </div>

    <p id="noResults" class="hidden mt-10 text-center text-gray-500 text-lg">No products match your filters.</p>
  </main>

  <?php include "footer.php"; ?>

  <!-- JS FILTER LOGIC -->
  <script>
    function filterProducts() {
      const search = document.getElementById('searchInput').value.toLowerCase();
      const category = document.getElementById('categoryFilter').value;
      const min = parseFloat(document.getElementById('minPrice').value) || 0;
      const max = parseFloat(document.getElementById('maxPrice').value) || Infinity;

      let anyVisible = false;
      document.querySelectorAll('#productGrid .card').forEach(card => {
        const title = card.querySelector('h2').textContent.toLowerCase();
        const cat = card.dataset.category;
        const price = parseFloat(card.dataset.price);

        const matchesSearch = title.includes(search);
        const matchesCategory = !category || cat === category;
        const matchesPrice = price >= min && price <= max;

        if (matchesSearch && matchesCategory && matchesPrice) {
          card.style.display = 'block';
          anyVisible = true;
        } else {
          card.style.display = 'none';
        }
      });

      document.getElementById('noResults').classList.toggle('hidden', anyVisible);
    }

    document.getElementById('searchInput').addEventListener('input', filterProducts);
    document.getElementById('categoryFilter').addEventListener('change', filterProducts);
    document.getElementById('minPrice').addEventListener('input', filterProducts);
    document.getElementById('maxPrice').addEventListener('input', filterProducts);
  </script>
</body>

</html>