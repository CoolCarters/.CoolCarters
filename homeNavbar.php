<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once './connection.php';

// Path prefix detection for links
$isInCustomerFolder = strpos($_SERVER['PHP_SELF'], 'customer/') !== false;
$pathPrefix = $isInCustomerFolder ? '../' : './';

// Role and name detection
$role         = $_SESSION['role'] ?? null;
$customerName = '';
if ($role === 'customer' && isset($_SESSION['customer_name'])) {
    $customerName = htmlspecialchars($_SESSION['customer_name']);
} elseif ($role === 'trader') {
    header("Location: {$pathPrefix}trader/traderInterface.php");
    exit();
}

// Wishlist count (only for customers)
$wishlistCount = 0;
if (isset($_SESSION['user_id']) && $role === 'customer') {
    $userId = $_SESSION['user_id'];
    $sqlWishlist = "
        SELECT COUNT(*) AS CNT
        FROM PRODUCT_WISHLIST pw
        JOIN WISHLIST w ON pw.WISHLIST_ID = w.WISHLIST_ID
        WHERE w.fk1_User_ID = :USER_ID
    ";
    $st = oci_parse($conn, $sqlWishlist);
    oci_bind_by_name($st, ":USER_ID", $userId);
    oci_execute($st);
    if ($row = oci_fetch_assoc($st)) {
        $wishlistCount = (int)$row['CNT'];
    }
    oci_free_statement($st);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>CoolCarters</title>
    <link rel="stylesheet" href="<?= $pathPrefix ?>css/output.css" />
    <style>
        .icon-badge {
            position: relative;
            display: inline-block;
        }

        .icon-badge__count {
            position: absolute;
            top: -7px;
            right: -8px;
            background: #e53e3e;
            color: #fff;
            font-size: 11px;
            font-weight: bold;
            border-radius: 50%;
            padding: 2px 6px;
            min-width: 20px;
            text-align: center;
        }
    </style>
</head>

<body class="bg-gray-100">

    <!-- Navbar -->
    <nav class="bg-blue-600 shadow-lg sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 py-3 md:px-6 flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <a href="<?= $pathPrefix ?>index.php">
                    <img src="<?= $pathPrefix ?>images/CoolCarters Sample 2.svg" alt="Logo"
                        class="h-10 w-10 rounded-full border-2 border-white object-cover" />
                </a>
                <a href="<?= $pathPrefix ?>index.php"
                    class="text-white text-2xl font-semibold hover:text-blue-200 transition">CoolCarters</a>
            </div>

            <div class="hidden md:flex items-center space-x-8">
                <a href="<?= $pathPrefix ?>index.php" class="text-white hover:text-blue-200 px-2 font-medium transition">Home</a>
                <a href="<?= $pathPrefix ?>aboutus.php" class="text-white hover:text-blue-200 px-2 font-medium transition">About Us</a>
                <a href="<?= $pathPrefix ?>contactus.php" class="text-white hover:text-blue-200 px-2 font-medium transition">Contact Us</a>

                <!-- Cart and Wishlist (icons with PHP-powered badges) -->
                <a href="javascript:void(0);" onclick="toggleCart(true)" class="icon-badge text-white hover:text-blue-200 transition">
                    <?php include $pathPrefix . 'cart.php'; ?>
                    <!-- If you want to add cart count: <span class="icon-badge__count">1</span> -->
                </a>
                <a href="javascript:void(0);" onclick="toggleWishlist(true)" class="icon-badge text-white hover:text-blue-200 transition">
                    <?php include $pathPrefix . 'wishlist.php'; ?>
                    <?php if ($wishlistCount > 0): ?>
                        <span class="icon-badge__count"><?= $wishlistCount ?></span>
                    <?php endif; ?>
                </a>

                <?php if ($role === 'customer'): ?>
                    <a href="<?= $pathPrefix ?>customer/customer_profile.php" class="text-white px-3 py-1 font-medium hover:underline transition"><?= $customerName ?></a>
                    <a href="<?= $pathPrefix ?>logout.php" class="ml-4 bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-md font-medium transition">Logout</a>
                <?php else: ?>
                    <a href="<?= $pathPrefix ?>login.php" class="text-white hover:text-blue-200 px-3 py-2 rounded-md text-sm font-medium transition">Login / Signup</a>
                <?php endif; ?>
            </div>

            <!-- Mobile Menu Button -->
            <button id="mobile-menu-button" class="md:hidden text-white focus:outline-none">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
        </div>

        <!-- Mobile Menu -->
        <div id="mobile-menu" class="hidden md:hidden bg-blue-700">
            <a href="<?= $pathPrefix ?>index.php" class="block text-white px-4 py-2 hover:bg-blue-600">Home</a>
            <a href="<?= $pathPrefix ?>aboutus.php" class="block text-white px-4 py-2 hover:bg-blue-600">About Us</a>
            <a href="<?= $pathPrefix ?>contactus.php" class="block text-white px-4 py-2 hover:bg-blue-600">Contact Us</a>
            <a href="javascript:void(0);" onclick="toggleCart(true)" class="block text-white px-4 py-2 hover:bg-blue-600">Cart</a>
            <a href="javascript:void(0);" onclick="toggleWishlist(true)" class="block text-white px-4 py-2 hover:bg-blue-600">
                Wishlist <?php if ($wishlistCount > 0): ?><span class="icon-badge__count"><?= $wishlistCount ?></span><?php endif; ?>
            </a>
            <?php if ($role === 'customer'): ?>
                <a href="<?= $pathPrefix ?>customer/customer_profile.php" class="block text-white px-4 py-2 hover:bg-blue-600"><?= $customerName ?></a>
                <a href="<?= $pathPrefix ?>logout.php" class="block bg-red-500 text-white px-4 py-2 hover:bg-red-600 rounded-b-lg">Logout</a>
            <?php else: ?>
                <a href="<?= $pathPrefix ?>login.php" class="block text-white px-4 py-2 hover:bg-blue-600">Login / Signup</a>
            <?php endif; ?>
        </div>
    </nav>

    <!-- Wishlist Modal -->
    <div id="wishlist-modal"
        class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50 hidden">
        <div class="wishlist-panel bg-white rounded-xl shadow-lg p-6 max-w-md w-full">
            <div class="panel-header flex justify-between items-center mb-4">
                <h2 class="text-lg font-semibold text-blue-800">My Wishlist</h2>
                <button onclick="toggleWishlist(false)" class="text-gray-500 text-2xl font-bold">&times;</button>
            </div>
            <div class="panel-body space-y-4 max-h-[400px] overflow-y-auto" id="wishlist-body">
            </div>
        </div>
    </div>

    <!-- Cart Modal -->
    <div id="cart-modal"
        class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50 hidden">
        <div class="cart-panel bg-white rounded-xl shadow-lg p-6 w-[500px] h-[550px] flex flex-col overflow-hidden">
            <div class="cart-header bg-[#155DFC] text-white text-lg font-bold p-4 flex justify-between items-start">
                My Cart
                <button onclick="toggleCart(false)" class="close-btn bg-white text-black text-xl rounded px-2">&times;</button>
            </div>
            <div class="cart-body overflow-y-auto flex-1 py-2" id="cart-body">
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script>
        function toggleWishlist(show = true) {
            const modal = document.getElementById('wishlist-modal');
            modal.classList.toggle('hidden', !show);
            if (show) {
                fetchWishlist();
            }
        }

        function toggleCart(show = true) {
            const modal = document.getElementById('cart-modal');
            modal.classList.toggle('hidden', !show);
            if (show) {
                fetchCart();
            }
        }

        function fetchWishlist() {
            fetch('fetch_wishlist.php')
                .then(res => res.json())
                .then(data => {
                    const body = document.getElementById('wishlist-body');
                    body.innerHTML = '';
                    if (!data.success || !data.items.length) {
                        body.innerHTML = '<div class="text-center text-gray-500 py-8">No items in wishlist.</div>';
                        return;
                    }
                    data.items.forEach(item => {
                        body.innerHTML += `
                    <div class="flex items-center justify-between border-b pb-3 mb-3">
                        <img src="${item.image}" class="w-12 h-12 object-cover rounded shadow" alt="${item.name}">
                        <div class="flex-1 ml-4">
                            <div class="font-semibold">${item.name}</div>
                            <div class="text-gray-500 text-sm">${item.category}</div>
                            <div class="text-green-600 font-bold">$${parseFloat(item.price).toFixed(2)}</div>
                        </div>
                        <form method="post" action="remove_from_wishlist.php" class="ml-3">
                            <input type="hidden" name="product_id" value="${item.id}">
                            <button type="submit" class="bg-red-500 hover:bg-red-600 text-white rounded p-1 px-2 text-xs">Remove</button>
                        </form>
                    </div>`;
                    });
                });
        }

        function fetchCart() {
            fetch('fetch_cart.php')
                .then(res => res.json())
                .then(data => {
                    const body = document.getElementById('cart-body');
                    body.innerHTML = '';
                    if (!data.success || !data.items.length) {
                        body.innerHTML = '<div class="text-center text-gray-500 py-8">No items in cart.</div>';
                        return;
                    }
                    let total = 0;
                    data.items.forEach(item => {
                        total += item.price * item.quantity;
                        body.innerHTML += `
                    <div class="flex items-center justify-between border-b pb-3 mb-3">
                        <img src="${item.image}" class="w-12 h-12 object-cover rounded shadow" alt="${item.name}">
                        <div class="flex-1 ml-4">
                            <div class="font-semibold">${item.name}</div>
                            <div class="text-gray-500 text-sm">${item.category}</div>
                            <div class="text-green-600 font-bold">$${parseFloat(item.price).toFixed(2)} x ${item.quantity}</div>
                        </div>
                        <form method="post" action="remove_from_cart.php" class="ml-3">
                            <input type="hidden" name="product_id" value="${item.id}">
                            <button type="submit" class="bg-red-500 hover:bg-red-600 text-white rounded p-1 px-2 text-xs">Remove</button>
                        </form>
                    </div>`;
                    });
                    body.innerHTML += `<div class="font-bold text-right text-lg pt-4">Total: $${total.toFixed(2)}</div>`;
                });
        }

        // Mobile menu toggle
        document.getElementById('mobile-menu-button').addEventListener('click', () => {
            document.getElementById('mobile-menu').classList.toggle('hidden');
        });
    </script>
</body>

</html>