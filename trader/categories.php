<?php
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Shop by Category</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
  <div class="min-h-screen flex">
    <aside class="w-64 bg-gray-200 p-6 overflow-y-auto">
      <h2 class="text-lg font-semibold mb-4">Shop by Category</h2>
      <ul class="space-y-2 text-gray-800">
        <?php
        $categories = [
          "Kitchen appliances", "Baby Products", "Food and Snacks", "Beverages", "Home decor",
          "Furnitures", "Cosmetics", "Clothings", "Stationaries", "Electronics",
          "Jewelries", "Socks and Belts", "Chocolates and sweets", "Hair Products"
        ];
        foreach ($categories as $cat) {
          echo "<li class='flex items-center justify-between hover:text-blue-600 cursor-pointer'>"
             . htmlspecialchars($cat) . "<span>&#9662;</span></li>";
        }
        ?>
      </ul>
    </aside>

    <main class="flex-1 p-10">
      <h1 class="text-3xl font-bold mb-8 text-gray-800">Shop By Category</h1>
      <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
        <?php
        $products = [
          ["name" => "Butchers", "image" => "https://upload.wikimedia.org/wikipedia/commons/1/19/Butcher_shop_in_Market_Hall%2C_Budapest.jpg"],
          ["name" => "Greengrocer", "image" => "https://upload.wikimedia.org/wikipedia/commons/4/42/Greengrocer_Shop_London.jpg"],
          ["name" => "Fishmonger", "image" => "https://upload.wikimedia.org/wikipedia/commons/d/d4/Fishmonger.jpg"],
          ["name" => "Bakery", "image" => "https://upload.wikimedia.org/wikipedia/commons/e/e2/Bakery_in_London.jpg"],
          ["name" => "Delicatessen", "image" => "https://upload.wikimedia.org/wikipedia/commons/e/ef/Deli_Counter.jpg"]
        ];

        foreach ($products as $product) {
          echo "<div class='bg-white rounded-lg overflow-hidden shadow hover:shadow-lg transition'>";
          echo "<img src='" . htmlspecialchars($product['image']) . "' alt='" . htmlspecialchars($product['name']) . "' class='w-full h-48 object-cover'>";
          echo "<div class='p-4 text-center font-semibold text-gray-700'>" . htmlspecialchars($product['name']) . "</div>";
          echo "</div>";
        }
        ?>
      </div>
    </main>
  </div>
</body>
</html>