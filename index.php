<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1.0">
  <title>Home</title>
  <link rel="stylesheet" href="./css/output.css">
  <link rel="stylesheet" href="./css/home.css">
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <style>
    .card { transition: transform 0.3s; }
    .card:hover { transform: scale(1.05); }
  </style>
</head>
<body>
  <?php include "homeNavbar.php"; ?>

  <div class="search-container my-4 text-center">
    <input 
      type="text" 
      id="searchInput" 
      placeholder="Search products…" 
      class="p-2 rounded text-black w-1/2" 
      onkeyup="filterProducts()"
    >
  </div>

  <h2 class="categories-header text-2xl font-semibold mb-2 px-4">Shop by Category</h2>
  <div class="px-4 mb-6 flex flex-wrap">
    <?php 
      $cats = ['Butchers','Greengrocer','Fishmonger','Bakery','Delicatessen'];
      foreach($cats as $c){ 
    ?>
      <a 
        href="categories.php?category=<?= urlencode($c) ?>"
        class="px-4 py-2 mr-2 mb-2 bg-blue-600 text-white rounded hover:bg-blue-700"
      ><?= $c ?></a>
    <?php } ?>
    <a 
      href="index.php" 
      class="px-4 py-2 mb-2 bg-gray-300 text-gray-800 rounded hover:bg-gray-400"
    >All</a>
  </div>

  <div class="product-grid px-4 flex flex-wrap gap-4" id="productGrid">
    <?php
      // now 12 products total:
      $products = [
        // original six
        ['id'=>1, 'filename'=>'images/steak.jpg',  'product'=>'Ribeye Steak',   'price'=>'$20','sold'=>'No','category'=>'Butchers'],
        ['id'=>2, 'filename'=>'images/apples.jpg', 'product'=>'Fresh Apples',   'price'=>'$5', 'sold'=>'No','category'=>'Greengrocer'],
        ['id'=>3, 'filename'=>'images/salmon.jpg', 'product'=>'Salmon Fillet',  'price'=>'$15','sold'=>'Yes','category'=>'Fishmonger'],
        ['id'=>4, 'filename'=>'images/bread.jpg',  'product'=>'Artisan Bread',  'price'=>'$8', 'sold'=>'No','category'=>'Bakery'],
        ['id'=>5, 'filename'=>'images/salami.jpg', 'product'=>'Sliced Salami',  'price'=>'$12','sold'=>'Yes','category'=>'Delicatessen'],
        ['id'=>6, 'filename'=>'images/cheese.jpg', 'product'=>'Cheese Platter', 'price'=>'$25','sold'=>'No','category'=>'Delicatessen'],

        // your six new items
        ['id'=>7,  'filename'=>'images/ham.jpg',     'product'=>'Prosciutto Ham', 'price'=>'$18','sold'=>'No','category'=>'Delicatessen'],
        ['id'=>8,  'filename'=>'images/lettuce.jpg','product'=>'Organic Lettuce', 'price'=>'$3', 'sold'=>'No','category'=>'Greengrocer'],
        ['id'=>9,  'filename'=>'images/pork.jpg', 'product'=>'Pork',   'price'=>'$15','sold'=>'No','category'=>'Fishmonger'],
        ['id'=>10, 'filename'=>'images/broccoli.jpg','product'=>'Broccoli',       'price'=>'$4', 'sold'=>'No','category'=>'Greengrocer'],
        ['id'=>11, 'filename'=>'images/prawn.jpg',  'product'=>'Prawns',         'price'=>'$12','sold'=>'No','category'=>'Fishmonger'],
        ['id'=>12, 'filename'=>'images/loaf.jpg',   'product'=>'Sourdough Loaf', 'price'=>'$6', 'sold'=>'No','category'=>'Bakery'],
      ];

      foreach ($products as $p) {
        echo "<div class=\"card w-60 bg-white rounded shadow overflow-hidden cursor-pointer\" onclick=\"location.href='product.php?id={$p['id']}'\">";
        echo   "<img src=\"{$p['filename']}\" alt=\"{$p['product']}\" class=\"card-image w-full h-40 object-cover\" onerror=\"this.src='https://via.placeholder.com/150?text=No+Image'\">";
        echo   "<div class=\"p-4\">";
        echo     "<p class=\"font-semibold\">{$p['product']}</p>";
        echo     "<p class=\"text-gray-600\">Price: {$p['price']}</p>";
        echo     "<p class=\"text-gray-600\">Sold: {$p['sold']}</p>";
        echo   "</div>";
        echo "</div>";
      }
    ?>
  </div>

  <?php include "footer.php"; ?>
</body>

<script>
  function filterProducts() {
    const q = document.getElementById('searchInput').value.toLowerCase();
    document.querySelectorAll('#productGrid .card').forEach(card => {
      // grab only the name <p>, which has the font-semibold class
      const nameEl = card.querySelector('p.font-semibold');
      const name = nameEl ? nameEl.textContent.toLowerCase() : '';
      card.style.display = name.includes(q) ? '' : 'none';
    });
  }
</script>

</html>
