<?php
// product.php
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CoolCarters | Product Detail</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans">
    <!-- Header -->
    <header class="bg-white shadow-sm sticky top-0 z-10">
        <div class="max-w-7xl mx-auto px-4 py-3 flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <div class="bg-gray-300 rounded-full w-10 h-10"></div>
                <span class="text-xl font-bold">CoolCarters</span>
            </div>
            <nav class="space-x-4">
                <a href="index.php" class="hover:text-blue-600">Home</a>
                <a href="#" class="hover:text-blue-600">About us</a>
                <a href="#" class="hover:text-blue-600">Contact us</a>
            </nav>
            <div class="flex items-center space-x-4">
                <div class="relative">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13l-1.4 5h11.8M7 13L5.4 7M17 13l1.6-6H5.4"></path></svg>
                    <span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs px-1 rounded-full">0</span>
                </div>
                <div class="flex items-center">
                    <svg class="w-6 h-6 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A9 9 0 1118.879 6.196a9 9 0 01-13.758 11.608z"></path></svg>
                    <span>Username</span>
                </div>
            </div>
        </div>
    </header>

    <!-- Product Detail Section -->
    <?php
    $products = [
        ['id' => 1, 'filename' => 'images/steak.jpg', 'product' => 'Ribeye Steak', 'price' => '$20', 'sold' => 'No', 'delivery' => '30th March, 2025', 'delivery_charge' => 'Rs.100', 'reviews' => [['text' => 'This product is so cool. Wow love it.', 'extra' => 'I will buy more of this.', 'user' => 'Username'], ['text' => 'This is so good.', 'extra' => 'Really worth the money.', 'user' => 'Username']]],
        ['id' => 2, 'filename' => 'images/apples.jpg', 'product' => 'Fresh Apples', 'price' => '$5', 'sold' => 'No', 'delivery' => '30th March, 2025', 'delivery_charge' => 'Rs.100', 'reviews' => [['text' => 'This product is so cool. Wow love it.', 'extra' => 'I will buy more of this.', 'user' => 'Username']]],
        ['id' => 3, 'filename' => 'images/salmon.jpg', 'product' => 'Salmon Fillet', 'price' => '$15', 'sold' => 'Yes', 'delivery' => '30th March, 2025', 'delivery_charge' => 'Rs.100', 'reviews' => [['text' => 'This is so good.', 'extra' => 'Really worth the money.', 'user' => 'Username']]],
        ['id' => 4, 'filename' => 'images/bread.jpg', 'product' => 'Artisan Bread', 'price' => '$8', 'sold' => 'No', 'delivery' => '30th March, 2025', 'delivery_charge' => 'Rs.100', 'reviews' => [['text' => 'This product is so cool. Wow love it.', 'extra' => 'I will buy more of this.', 'user' => 'Username']]],
        ['id' => 5, 'filename' => 'images/salami.jpg', 'product' => 'Sliced Salami', 'price' => '$12', 'sold' => 'Yes', 'delivery' => '30th March, 2025', 'delivery_charge' => 'Rs.100', 'reviews' => [['text' => 'This is so good.', 'extra' => 'Really worth the money.', 'user' => 'Username']]],
        ['id' => 6, 'filename' => 'images/cheese.jpg', 'product' => 'Cheese Platter', 'price' => '$25', 'sold' => 'No', 'delivery' => '30th March, 2025', 'delivery_charge' => 'Rs.100', 'reviews' => [['text' => 'This product is so cool. Wow love it.', 'extra' => 'I will buy more of this.', 'user' => 'Username']]]
    ];

    $productId = isset($_GET['id']) ? (int)$_GET['id'] : 1;
    $product = $products[$productId - 1];
    ?>

    <main class="max-w-7xl mx-auto p-6 grid grid-cols-1 md:grid-cols-2 gap-8">
        <!-- Image Slider -->
        <div class="flex flex-col items-center justify-center">
            <img src="<?php echo htmlspecialchars($product['filename']); ?>" alt="<?php echo htmlspecialchars($product['product']); ?>" class="rounded-lg shadow w-full max-w-[400px] h-[400px] object-cover" onError="this.src='https://via.placeholder.com/400?text=Image+Not+Found';">
            <div class="flex justify-between w-full mt-4 px-4">
                <button class="text-xl" onclick="history.back()">←</button>
                <button class="text-xl" onclick="window.location.href='product.php?id=<?php echo ($productId % count($products) + 1); ?>'">→</button>
            </div>
        </div>

        <!-- Product Info -->
        <div class="space-y-4">
            <div>
                <p class="text-xl font-semibold">Price:</p>
                <p class="text-lg text-green-600 font-bold"><?php echo htmlspecialchars($product['price']); ?></p>
            </div>
            <div class="flex items-center space-x-4">
                <p class="text-xl font-semibold">Quantity:</p>
                <div class="flex items-center border rounded">
                    <button class="px-2" onclick="this.nextElementSibling.stepDown();">-</button>
                    <input type="number" id="quantity" name="quantity" value="1" min="1" class="w-10 text-center border-l border-r">
                    <button class="px-2" onclick="this.previousElementSibling.stepUp();">+</button>
                </div>
            </div>
            <div class="flex space-x-1">
                <?php for ($i = 0; $i < 5; $i++) echo '<span class="text-yellow-400 text-xl">' . ($i < 3 ? '★' : '☆') . '</span>'; ?>
            </div>
            <p class="text-gray-500">9 sold</p>
            <p class="text-sm text-red-500">Delivery by: <?php echo htmlspecialchars($product['delivery']); ?><br>Delivery charge: <?php echo htmlspecialchars($product['delivery_charge']); ?></p>

            <div>
                <h3 class="text-lg font-bold mb-1">Reviews:</h3>
                <?php foreach ($product['reviews'] as $review) { ?>
                    <div class="bg-white p-3 rounded shadow mb-3">
                        <p><?php echo htmlspecialchars($review['text']); ?><br><span class="text-sm text-gray-400"><?php echo htmlspecialchars($review['extra']); ?> From: <?php echo htmlspecialchars($review['user']); ?></span></p>
                    </div>
                <?php } ?>
            </div>

            <div class="flex space-x-4 mt-4">
                <button class="flex-1 bg-yellow-500 hover:bg-yellow-600 text-white py-2 px-4 rounded">Add to Cart</button>
                <button class="flex-1 bg-green-600 hover:bg-green-700 text-white py-2 px-4 rounded">Buy Now</button>
            </div>
        </div>
    </main>

    <!-- Product Description -->
    <section class="max-w-7xl mx-auto px-6 py-6 bg-white mt-4 rounded shadow">
        <h2 class="text-xl font-bold mb-2"><?php echo htmlspecialchars($product['product']); ?></h2>
        <p class="text-gray-600">
            Description: Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat... <a href="#" class="text-blue-500 hover:underline">See more</a>
        </p>
        <p class="text-red-600 font-semibold mt-2">Company name: CoolCarters</p>
    </section>

    <!-- Footer -->
    <?php include "footer.php"; ?>

    <script>
        document.querySelectorAll('.quantity button').forEach(button => {
            button.addEventListener('click', () => {
                let input = button.parentElement.querySelector('input');
                let value = parseInt(input.value);
                if (button.textContent === '-' && value > 1) input.value = value - 1;
                if (button.textContent === '+' && value < 10) input.value = value + 1;
            });
        });
    </script>
</body>
</html>