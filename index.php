<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>CoolCarters | All Products</title>
  <link rel="stylesheet" href="./css/output.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;600&display=swap" rel="stylesheet" />
  <style>
    body { font-family: 'Poppins', sans-serif; background-color: #f9fafb; }
    .card:hover { transform: scale(1.03); transition: all 0.3s ease; }
    .card { transition: transform 0.3s ease; }
  </style>
</head>
<body class="text-gray-800">
<?php include "homeNavbar.php"; ?>

<?php
  $products = [
    ['id'=>1,  'filename'=>'images/steak.jpg',     'product'=>'Ribeye Steak',    'price'=>'$20','sold'=>'No','category'=>'Butchers'],
    ['id'=>2,  'filename'=>'images/apples.jpg',    'product'=>'Fresh Apples',    'price'=>'$5','sold'=>'No','category'=>'Greengrocer'],
    ['id'=>3,  'filename'=>'images/salmon.jpg',    'product'=>'Salmon Fillet',   'price'=>'$15','sold'=>'Yes','category'=>'Fishmonger'],
    ['id'=>4,  'filename'=>'images/bread.jpg',     'product'=>'Artisan Bread',   'price'=>'$8','sold'=>'No','category'=>'Bakery'],
    ['id'=>5,  'filename'=>'images/salami.jpg',    'product'=>'Sliced Salami',   'price'=>'$12','sold'=>'Yes','category'=>'Delicatessen'],
    ['id'=>6,  'filename'=>'images/cheese.jpg',    'product'=>'Cheese Platter',  'price'=>'$25','sold'=>'No','category'=>'Delicatessen'],
    ['id'=>7,  'filename'=>'images/ham.jpg',       'product'=>'Prosciutto Ham',  'price'=>'$18','sold'=>'No','category'=>'Delicatessen'],
    ['id'=>8,  'filename'=>'images/lettuce.jpg',   'product'=>'Organic Lettuce', 'price'=>'$3','sold'=>'No','category'=>'Greengrocer'],
    ['id'=>9,  'filename'=>'images/pork.jpg',      'product'=>'Pork',            'price'=>'$15','sold'=>'No','category'=>'Butchers'],
    ['id'=>10, 'filename'=>'images/broccoli.jpg',  'product'=>'Broccoli',        'price'=>'$4','sold'=>'No','category'=>'Greengrocer'],
    ['id'=>11, 'filename'=>'images/prawn.jpg',     'product'=>'Prawns',          'price'=>'$12','sold'=>'No','category'=>'Fishmonger'],
    ['id'=>12, 'filename'=>'images/loaf.jpg',      'product'=>'Sourdough Loaf',  'price'=>'$6','sold'=>'No','category'=>'Bakery'],
  ];
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
      class="p-3 border rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 w-full col-span-1 md:col-span-2"
    />
    <select 
      id="categoryFilter" 
      class="p-3 border rounded-md shadow-sm w-full"
    >
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
        onclick="location.href='product.php?id=<?= $p['id'] ?>'"
      >
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
