<?php
  include "homeNavbar.php";

  // full 12-item array, matching index.php
  $products = [
    ['id'=>1,  'filename'=>'images/steak.jpg',     'product'=>'Ribeye Steak',    'price'=>'$20','sold'=>'No','category'=>'Butchers'],
    ['id'=>2,  'filename'=>'images/apples.jpg',    'product'=>'Fresh Apples',    'price'=>'$5', 'sold'=>'No','category'=>'Greengrocer'],
    ['id'=>3,  'filename'=>'images/salmon.jpg',    'product'=>'Salmon Fillet',   'price'=>'$15','sold'=>'Yes','category'=>'Fishmonger'],
    ['id'=>4,  'filename'=>'images/bread.jpg',     'product'=>'Artisan Bread',   'price'=>'$8', 'sold'=>'No','category'=>'Bakery'],
    ['id'=>5,  'filename'=>'images/salami.jpg',    'product'=>'Sliced Salami',   'price'=>'$12','sold'=>'Yes','category'=>'Delicatessen'],
    ['id'=>6,  'filename'=>'images/cheese.jpg',    'product'=>'Cheese Platter',  'price'=>'$25','sold'=>'No','category'=>'Delicatessen'],
    ['id'=>7,  'filename'=>'images/ham.jpg',       'product'=>'Prosciutto Ham',  'price'=>'$18','sold'=>'No','category'=>'Delicatessen'],
    ['id'=>8,  'filename'=>'images/lettuce.jpg',   'product'=>'Organic Lettuce', 'price'=>'$3', 'sold'=>'No','category'=>'Greengrocer'],
    ['id'=>9,  'filename'=>'images/pork.jpg',      'product'=>'Pork',            'price'=>'$15','sold'=>'No','category'=>'Butchers'],
    ['id'=>10, 'filename'=>'images/broccoli.jpg',  'product'=>'Broccoli',        'price'=>'$4', 'sold'=>'No','category'=>'Greengrocer'],
    ['id'=>11, 'filename'=>'images/prawn.jpg',     'product'=>'Prawns',          'price'=>'$12','sold'=>'No','category'=>'Fishmonger'],
    ['id'=>12, 'filename'=>'images/loaf.jpg',      'product'=>'Sourdough Loaf',  'price'=>'$6', 'sold'=>'No','category'=>'Bakery'],
  ];

  // capture selected category (if any)
  $selected = $_GET['category'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1.0">
  <title>
    <?= $selected 
        ? "Category: " . htmlspecialchars($selected) 
        : "All Products" 
    ?>
  </title>
  <link rel="stylesheet" href="./css/output.css">
  <link rel="stylesheet" href="./css/home.css">
  <style>
    .card { transition: transform .3s; }
    .card:hover { transform: scale(1.05); }
  </style>
</head>
<body>
  <main class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6">
      <?= $selected 
            ? "Products in “" . htmlspecialchars($selected) . "”" 
            : "All Products" 
      ?>
    </h1>

    <div class="product-grid flex flex-wrap gap-4" id="productGrid">
      <?php
        $foundAny = false;
        foreach ($products as $p) {
          // if no category chosen, or it matches, render
          if (!$selected || $p['category'] === $selected) {
            $foundAny = true;
            echo "
            <div class=\"card w-60 bg-white rounded shadow overflow-hidden cursor-pointer\" 
                 onclick=\"location.href='product.php?id={$p['id']}'\">
              <img 
                src=\"{$p['filename']}\" 
                alt=\"{$p['product']}\" 
                class=\"card-image w-full h-40 object-cover\" 
                onerror=\"this.src='https://via.placeholder.com/150?text=No+Image'\"
              >
              <div class=\"p-4\">
                <p class=\"font-semibold\">{$p['product']}</p>
                <p class=\"text-gray-600\">Price: {$p['price']}</p>
                <p class=\"text-gray-600\">Sold: {$p['sold']}</p>
              </div>
            </div>";
          }
        }

        if ($selected && !$foundAny) {
          echo "<p class=\"w-full text-center py-10 text-gray-600\">
                  No products found in the “" . htmlspecialchars($selected) . "” category.
                </p>";
        }
      ?>
    </div>
  </main>

  <?php include "footer.php"; ?>
</body>
</html>
