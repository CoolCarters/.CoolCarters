<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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

    <div class="search-container">
        <input type="text" id="searchInput" placeholder="Search" class="p-2 rounded text-black" onkeyup="filterProducts()">
    </div>
    <h2 class="categories-header">Categories</h2>
    <div class="product-grid" id="productGrid">
        <?php
        $products = [
            ['id' => 1, 'filename' => 'images/steak.jpg', 'product' => 'Ribeye Steak', 'price' => '$20', 'sold' => 'No'],
            ['id' => 2, 'filename' => 'images/apples.jpg', 'product' => 'Fresh Apples', 'price' => '$5', 'sold' => 'No'],
            ['id' => 3, 'filename' => 'images/salmon.jpg', 'product' => 'Salmon Fillet', 'price' => '$15', 'sold' => 'Yes'],
            ['id' => 4, 'filename' => 'images/bread.jpg', 'product' => 'Artisan Bread', 'price' => '$8', 'sold' => 'No'],
            ['id' => 5, 'filename' => 'images/salami.jpg', 'product' => 'Sliced Salami', 'price' => '$12', 'sold' => 'Yes'],
            ['id' => 6, 'filename' => 'images/cheese.jpg', 'product' => 'Cheese Platter', 'price' => '$25', 'sold' => 'No']
        ];

        foreach ($products as $product) {
            echo '<div class="card" onclick="window.location.href=\'product.php?id=' . $product['id'] . '\'">';
            echo '<img src="' . htmlspecialchars($product['filename']) . '" alt="' . htmlspecialchars($product['product']) . '" class="card-image" onError="this.src=\'https://via.placeholder.com/150?text=Image+Not+Found\';">';
            echo '<div class="mt-2">';
            echo '<div class="rating"></div>';
            echo '<p><strong>Name:</strong> ' . htmlspecialchars($product['product']) . '</p>';
            echo '<p><strong>Price:</strong> ' . htmlspecialchars($product['price']) . '</p>';
            echo '<p><strong>Sold:</strong> ' . htmlspecialchars($product['sold']) . '</p>';
            echo '</div>';
            echo '</div>';
        }
        ?>
    </div>

    <?php include "footer.php"; ?>

    <script>
        function filterProducts() {
            let input = document.getElementById('searchInput').value.toLowerCase();
            let cards = document.getElementsByClassName('card');
            for (let i = 0; i < cards.length; i++) {
                let productName = cards[i].getElementsByTagName('p')[0].innerText.toLowerCase().replace('Name: ', '');
                if (productName.includes(input)) {
                    cards[i].style.display = '';
                } else {
                    cards[i].style.display = 'none';
                }
            }
        }
    </script>
    <script src="./js/card.js"></script>
</body>

</html>