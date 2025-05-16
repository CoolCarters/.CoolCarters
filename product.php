<?php
// product.php
session_start();

// Initialize cart if not set
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// PayPal Configuration
// For Sandbox (testing):
$paypalURL = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
$paypalID = 'sb-zscoe41152465@business.example.com'; // Sandbox Business Email

// For Live (real transactions), uncomment these and comment the Sandbox lines:
// $paypalURL = 'https://www.paypal.com/cgi-bin/webscr';
// $paypalID = 'your-real-business-email@example.com'; // Replace with your real PayPal business email

// Handle Add to Cart
$cartMessage = '';
if (isset($_POST['add_to_cart'])) {
    $productId = (int)$_POST['product_id'];
    $quantity = (int)$_POST['quantity'];

    // Calculate current total items in cart
    $currentTotal = array_sum(array_column($_SESSION['cart'], 'quantity'));

    // Check if adding the new quantity exceeds 20
    if ($currentTotal + $quantity <= 20) {
        $found = false;
        foreach ($_SESSION['cart'] as &$item) {
            if ($item['id'] == $productId) {
                $item['quantity'] += $quantity;
                $found = true;
                break;
            }
        }
        if (!$found) {
            $_SESSION['cart'][] = [
                'id' => $productId,
                'quantity' => $quantity,
                'name' => $_POST['product_name'],
                'price' => $_POST['product_price']
            ];
        }
        $cartMessage = "Added $quantity item(s) to cart!";
    } else {
        $cartMessage = "Cart Limit only 20 items";
    }
}

$products = [
    ['id' => 1, 'filename' => 'images/steak.jpg', 'product' => 'Ribeye Steak', 'price' => '20', 'sold' => 'No', 'delivery' => '30th March, 2025', 'delivery_charge' => 'Rs.100', 'reviews' => [['text' => 'This product is so cool. Wow love it.', 'extra' => 'I will buy more of this.', 'user' => 'Username'], ['text' => 'This is so good.', 'extra' => 'Really worth the money.', 'user' => 'Username']]],
    ['id' => 2, 'filename' => 'images/apples.jpg', 'product' => 'Fresh Apples', 'price' => '5', 'sold' => 'No', 'delivery' => '30th March, 2025', 'delivery_charge' => 'Rs.100', 'reviews' => [['text' => 'This product is so cool. Wow love it.', 'extra' => 'I will buy more of this.', 'user' => 'Username']]],
    ['id' => 3, 'filename' => 'images/salmon.jpg', 'product' => 'Salmon Fillet', 'price' => '15', 'sold' => 'Yes', 'delivery' => '30th March, 2025', 'delivery_charge' => 'Rs.100', 'reviews' => [['text' => 'This is so good.', 'extra' => 'Really worth the money.', 'user' => 'Username']]],
    ['id' => 4, 'filename' => 'images/bread.jpg', 'product' => 'Artisan Bread', 'price' => '8', 'sold' => 'No', 'delivery' => '30th March, 2025', 'delivery_charge' => 'Rs.100', 'reviews' => [['text' => 'This product is so cool. Wow love it.', 'extra' => 'I will buy more of this.', 'user' => 'Username']]],
    ['id' => 5, 'filename' => 'images/salami.jpg', 'product' => 'Sliced Salami', 'price' => '12', 'sold' => 'Yes', 'delivery' => '30th March, 2025', 'delivery_charge' => 'Rs.100', 'reviews' => [['text' => 'This is so good.', 'extra' => 'Really worth the money.', 'user' => 'Username']]],
    ['id' => 6, 'filename' => 'images/cheese.jpg', 'product' => 'Cheese Platter', 'price' => '25', 'sold' => 'No', 'delivery' => '30th March, 2025', 'delivery_charge' => 'Rs.100', 'reviews' => [['text' => 'This product is so cool. Wow love it.', 'extra' => 'I will buy more of this.', 'user' => 'Username']]]
];

$productId = isset($_GET['id']) ? (int)$_GET['id'] : 1;
$product = $products[$productId - 1];

// Calculate current total items in cart for JavaScript validation
$currentCartTotal = array_sum(array_column($_SESSION['cart'], 'quantity'));
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CoolCarters | Product Detail</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .popup {
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            background-color: #fff;
            padding: 15px 30px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            display: none;
        }
        .popup.success { background-color: #d4edda; color: #155724; }
        .popup.error { background-color: #f8d7da; color: #721c24; }
    </style>
</head>
<body class="bg-gray-100 font-sans">
   <?php include "homeNavbar.php"; ?>
    <!-- Popup Container -->
    <div id="popup" class="popup"></div>

    <!-- Product Detail Section -->
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
                <p class="text-lg text-green-600 font-bold">$<?php echo htmlspecialchars($product['price']); ?></p>
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
                <!-- Add to Cart Form -->
                <form id="addToCartForm" method="post" class="flex-1">
                    <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                    <input type="hidden" name="product_name" value="<?php echo htmlspecialchars($product['product']); ?>">
                    <input type="hidden" name="product_price" value="<?php echo htmlspecialchars($product['price']); ?>">
                    <input type="hidden" id="cartQuantity" name="quantity" value="1">
                    <button type="submit" name="add_to_cart" class="flex-1 bg-yellow-500 hover:bg-yellow-600 text-white py-2 px-4 rounded w-full">Add to Cart</button>
                </form>

                <!-- PayPal Buy Now Form -->
                <form action="<?php echo $paypalURL; ?>" method="post" class="flex-1">
                    <input type="hidden" name="business" value="<?php echo $paypalID; ?>">
                    <input type="hidden" name="cmd" value="_xclick">
                    <input type="hidden" name="item_name" value="<?php echo htmlspecialchars($product['product']); ?>">
                    <input type="hidden" name="item_number" value="<?php echo $product['id']; ?>">
                    <input type="hidden" name="amount" value="<?php echo htmlspecialchars($product['price']); ?>">
                    <input type="hidden" name="currency_code" value="USD">
                    <input type="hidden" id="paypalQuantity" name="quantity" value="1">
                    <input type='hidden' name='cancel_return' value='http://localhost/cc/cancel.php'>
                    <input type='hidden' name='return' value='http://localhost/cc/success.php'>
                    <input type="hidden" name="no_shipping" value="1">
                    <input type="hidden" name="rm" value="2">
                    <p class="text-sm text-gray-600 mb-2">Pay with your PayPal account by logging in with your personal email.</p>
                    <button type="submit" class="flex-1 bg-green-600 hover:bg-green-700 text-white py-2 px-4 rounded w-full">Buy Now with PayPal</button>
                    <img alt="" border="0" width="1" height="1" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif">
                </form>
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
        // Current total items in cart (passed from PHP)
        const currentCartTotal = <?php echo $currentCartTotal; ?>;
        const cartLimit = 20;

        // Popup functionality
        const popup = document.getElementById('popup');

        function showPopup(message, type) {
            popup.className = `popup ${type}`;
            popup.textContent = message;
            popup.style.display = 'block';
            setTimeout(() => {
                popup.style.display = 'none';
            }, 3000); // Hide after 3 seconds
        }

        // Update hidden quantity inputs for both forms
        document.querySelectorAll('.quantity button').forEach(button => {
            button.addEventListener('click', () => {
                let input = button.parentElement.querySelector('input');
                let value = parseInt(input.value);
                if (button.textContent === '-' && value > 1) input.value = value - 1;
                if (button.textContent === '+' && value < 10) input.value = value + 1;
                document.getElementById('cartQuantity').value = input.value;
                document.getElementById('paypalQuantity').value = input.value;
            });
        });

        // Update hidden quantity inputs when the input field changes directly
        document.getElementById('quantity').addEventListener('input', function() {
            let value = parseInt(this.value);
            if (value < 1) this.value = 1;
            if (value > 10) this.value = 10;
            document.getElementById('cartQuantity').value = this.value;
            document.getElementById('paypalQuantity').value = this.value;
        });

        // Validate cart limit before submitting the form
        document.getElementById('addToCartForm').addEventListener('submit', function(event) {
            const newQuantity = parseInt(document.getElementById('quantity').value);
            const totalQuantity = currentCartTotal + newQuantity;

            if (totalQuantity > cartLimit) {
                event.preventDefault();
                showPopup('Cart Limit only 20 items', 'error');
            } else {
                // Allow form submission and show success popup after successful addition
                showPopup('Add to Cart Success', 'success');
                // Note: The actual success message will be handled by the PHP response
            }
        });
    </script>
</body>
</html>