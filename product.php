<?php
session_start();
require_once 'connection.php';

// Get product ID from URL
$productId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($productId <= 0) die("❌ Invalid Product");

// Fetch product details
$sql = "SELECT PRODUCT_ID, PRODUCT_NAME, PRICE, CATEGORY, STOCK FROM PRODUCT WHERE PRODUCT_ID = :id";
$stid = oci_parse($conn, $sql);
oci_bind_by_name($stid, ":id", $productId);
oci_execute($stid);
$row = oci_fetch_assoc($stid);
if (!$row) die("❌ Product not found.");
$product = [
    'id'       => $row['PRODUCT_ID'],
    'filename' => "getImage.php?id=" . $row['PRODUCT_ID'],
    'product'  => $row['PRODUCT_NAME'],
    'price'    => $row['PRICE'],
    'category' => $row['CATEGORY'],
    'sold'     => $row['STOCK'] == 0 ? 'Yes' : 'No',
    'stock'    => $row['STOCK'],
];
oci_free_statement($stid);

// Fetch reviews
$reviewSql = "SELECT r.Rating, u.First_Name, u.Last_Name FROM REVIEW r JOIN USER_TABLE u ON r.fk2_User_ID = u.User_ID WHERE r.fk1_Product_ID = :pid";
$reviewStmt = oci_parse($conn, $reviewSql);
oci_bind_by_name($reviewStmt, ":pid", $productId);
oci_execute($reviewStmt);
$reviews = [];
while ($rev = oci_fetch_assoc($reviewStmt)) {
    $reviews[] = [
        'rating' => $rev['RATING'],
        'user'   => trim($rev['FIRST_NAME'] . ' ' . $rev['LAST_NAME']),
    ];
}
oci_free_statement($reviewStmt);

// ---- HANDLERS ----

// Add Review
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['review_submit'])) {
    if (!isset($_SESSION['user_id'])) {
        $review_message = "You must be logged in to leave a review.";
    } else {
        $userId = $_SESSION['user_id'];
        $rating = (int)$_POST['rating'];
        $insertRev = oci_parse($conn, "INSERT INTO REVIEW (Review_ID, Rating, fk1_Product_ID, fk2_User_ID) VALUES (NULL, :rating, :pid, :uid)");
        oci_bind_by_name($insertRev, ":rating", $rating);
        oci_bind_by_name($insertRev, ":pid", $productId);
        oci_bind_by_name($insertRev, ":uid", $userId);
        $result = oci_execute($insertRev);
        if ($result) {
            oci_free_statement($insertRev);
            header("Location: product.php?id=" . $productId); // refresh to show new review
            exit;
        } else {
            $review_message = "Failed to add review.";
            oci_free_statement($insertRev);
        }
    }
}

// Add to Cart
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    if (!isset($_SESSION['user_id']) || strtolower($_SESSION['role']) !== 'customer') {
        $cart_message = "Please log in as a customer to use cart.";
    } else {
        $userId = (int)$_SESSION['user_id'];
        $cartId = null;

        // 1. Check for existing cart for this user
        $sqlCheckCart = "SELECT CART_ID FROM CART WHERE FK1_USER_ID = :user_id_var";
        $stmtCheckCart = oci_parse($conn, $sqlCheckCart);
        oci_bind_by_name($stmtCheckCart, ":user_id_var", $userId);
        oci_execute($stmtCheckCart);
        if ($cartRow = oci_fetch_assoc($stmtCheckCart)) {
            $cartId = $cartRow['CART_ID'];
        }
        oci_free_statement($stmtCheckCart);

        // 2. If cart does not exist, create it
        if (!$cartId) {
            $sqlCreateCart = "INSERT INTO CART (CART_ID, TOTAL_PRICE, FK1_USER_ID) VALUES (NULL, 0, :user_id_var)";
            $stmtCreateCart = oci_parse($conn, $sqlCreateCart);
            oci_bind_by_name($stmtCreateCart, ":user_id_var", $userId);
            oci_execute($stmtCreateCart);
            oci_free_statement($stmtCreateCart);

            // Fetch the new cart id
            $sqlGetCart = "SELECT CART_ID FROM CART WHERE FK1_USER_ID = :user_id_var";
            $stmtGetCart = oci_parse($conn, $sqlGetCart);
            oci_bind_by_name($stmtGetCart, ":user_id_var", $userId);
            oci_execute($stmtGetCart);
            if ($cartRow2 = oci_fetch_assoc($stmtGetCart)) {
                $cartId = $cartRow2['CART_ID'];
            }
            oci_free_statement($stmtGetCart);
        }

        $productIdVar = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;
        if ($productIdVar <= 0) {
            $cart_message = "Invalid product ID.";
        } elseif ($cartId) {
            $cartIdVar = (int)$cartId;
            $qty = max(1, min(20, (int)($_POST['quantity'] ?? 1)));
            $sqlCheckProduct = "SELECT QUANTITY FROM PRODUCT_CART WHERE PRODUCT_ID = :product_id_var AND CART_ID = :cart_id_var";
            $stmtCheckProduct = oci_parse($conn, $sqlCheckProduct);
            oci_bind_by_name($stmtCheckProduct, ":product_id_var", $productIdVar);
            oci_bind_by_name($stmtCheckProduct, ":cart_id_var", $cartIdVar);
            oci_execute($stmtCheckProduct);
            if ($prodCartRow = oci_fetch_assoc($stmtCheckProduct)) {
                $newQty = min(20, $prodCartRow['QUANTITY'] + $qty);
                $sqlUpdateCart = "UPDATE PRODUCT_CART SET QUANTITY = :qty_var WHERE PRODUCT_ID = :product_id_var AND CART_ID = :cart_id_var";
                $stmtUpdateCart = oci_parse($conn, $sqlUpdateCart);
                oci_bind_by_name($stmtUpdateCart, ":qty_var", $newQty);
                oci_bind_by_name($stmtUpdateCart, ":product_id_var", $productIdVar);
                oci_bind_by_name($stmtUpdateCart, ":cart_id_var", $cartIdVar);
                oci_execute($stmtUpdateCart);
                oci_free_statement($stmtUpdateCart);
            } else {
                $sqlInsertCart = "INSERT INTO PRODUCT_CART (PRODUCT_ID, CART_ID, QUANTITY) VALUES (:product_id_var, :cart_id_var, :qty_var)";
                $stmtInsertCart = oci_parse($conn, $sqlInsertCart);
                oci_bind_by_name($stmtInsertCart, ":product_id_var", $productIdVar);
                oci_bind_by_name($stmtInsertCart, ":cart_id_var", $cartIdVar);
                oci_bind_by_name($stmtInsertCart, ":qty_var", $qty);
                oci_execute($stmtInsertCart);
                oci_free_statement($stmtInsertCart);
            }
            oci_free_statement($stmtCheckProduct);
            $cart_message = "Added to cart!";
        } else {
            $cart_message = "❌ Cart ID error!";
        }
    }
}

// Add to Wishlist
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_wishlist'])) {
    if (!isset($_SESSION['user_id']) || strtolower($_SESSION['role']) !== 'customer') {
        $wishlist_message = "Please log in as a customer to add to wishlist.";
    } else {
        $userId = (int)$_SESSION['user_id'];
        $wishlistId = null;

        // 1. Check for existing wishlist for this user
        $sqlCheckWishlist = "SELECT WISHLIST_ID FROM WISHLIST WHERE FK1_USER_ID = :user_id_var";
        $stmtCheckWishlist = oci_parse($conn, $sqlCheckWishlist);
        oci_bind_by_name($stmtCheckWishlist, ":user_id_var", $userId);
        oci_execute($stmtCheckWishlist);
        if ($wishlistRow = oci_fetch_assoc($stmtCheckWishlist)) {
            $wishlistId = $wishlistRow['WISHLIST_ID'];
        }
        oci_free_statement($stmtCheckWishlist);

        // 2. If wishlist does not exist, create it
        if (!$wishlistId) {
            $sqlCreateWishlist = "INSERT INTO WISHLIST (WISHLIST_ID, NO_OF_PRODUCTS, FK1_USER_ID) VALUES (NULL, 0, :user_id_var)";
            $stmtCreateWishlist = oci_parse($conn, $sqlCreateWishlist);
            oci_bind_by_name($stmtCreateWishlist, ":user_id_var", $userId);
            oci_execute($stmtCreateWishlist);
            oci_free_statement($stmtCreateWishlist);

            // Fetch the new wishlist id
            $sqlGetWishlist = "SELECT WISHLIST_ID FROM WISHLIST WHERE FK1_USER_ID = :user_id_var";
            $stmtGetWishlist = oci_parse($conn, $sqlGetWishlist);
            oci_bind_by_name($stmtGetWishlist, ":user_id_var", $userId);
            oci_execute($stmtGetWishlist);
            if ($wishlistRow2 = oci_fetch_assoc($stmtGetWishlist)) {
                $wishlistId = $wishlistRow2['WISHLIST_ID'];
            }
            oci_free_statement($stmtGetWishlist);
        }

        $productIdVar = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;
        if ($productIdVar <= 0) {
            $wishlist_message = "Invalid product ID.";
        } elseif ($wishlistId) {
            $wishlistIdVar = (int)$wishlistId;

            $sqlCheckProduct = "SELECT 1 FROM PRODUCT_WISHLIST WHERE PRODUCT_ID = :product_id_var AND WISHLIST_ID = :wishlist_id_var";
            $stmtCheckProduct = oci_parse($conn, $sqlCheckProduct);
            oci_bind_by_name($stmtCheckProduct, ":product_id_var", $productIdVar);
            oci_bind_by_name($stmtCheckProduct, ":wishlist_id_var", $wishlistIdVar);
            oci_execute($stmtCheckProduct);
            if (oci_fetch_assoc($stmtCheckProduct)) {
                $wishlist_message = "Already in wishlist!";
            } else {
                $sqlInsertWishlist = "INSERT INTO PRODUCT_WISHLIST (PRODUCT_ID, WISHLIST_ID) VALUES (:product_id_var, :wishlist_id_var)";
                $stmtInsertWishlist = oci_parse($conn, $sqlInsertWishlist);
                oci_bind_by_name($stmtInsertWishlist, ":product_id_var", $productIdVar);
                oci_bind_by_name($stmtInsertWishlist, ":wishlist_id_var", $wishlistIdVar);
                oci_execute($stmtInsertWishlist);
                oci_free_statement($stmtInsertWishlist);
                $wishlist_message = "Added to wishlist!";
            }
            oci_free_statement($stmtCheckProduct);
        } else {
            $wishlist_message = "❌ Wishlist ID error!";
        }
    }
}

// Slot Booking
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['slot_submit'])) {
    if (!isset($_SESSION['user_id']) || strtolower($_SESSION['role']) !== 'customer') {
        $slot_message = "Please log in as a customer to book a slot.";
    } else {
        $userId = $_SESSION['user_id'];
        $slotDate = trim($_POST['collection_date']);
        if (strpos($slotDate, 'T') !== false) {
            $slotDate = explode('T', $slotDate)[0];
        }
        $slotTimeLabel = $_POST['time_slot']; // e.g. "10:00–13:00"
        $address = $_POST['address'];
        $slotStartTime = substr($slotTimeLabel, 0, 5);
        $slotDateTime = $slotDate . ' ' . $slotStartTime . ':00';
        $slotInsert = oci_parse($conn, "
            INSERT INTO COLLECTION_SLOT (Slot_ID, Slot_Time, Slot_Date)
            VALUES (NULL, TO_TIMESTAMP(:slot_datetime, 'YYYY-MM-DD HH24:MI:SS'), TO_DATE(:slot_date, 'YYYY-MM-DD'))
            RETURNING Slot_ID INTO :slotid
        ");
        oci_bind_by_name($slotInsert, ":slot_datetime", $slotDateTime);
        oci_bind_by_name($slotInsert, ":slot_date", $slotDate);
        oci_bind_by_name($slotInsert, ":slotid", $slotId, 32);
        if (oci_execute($slotInsert)) {
            $slot_message = "Slot booked successfully!";
        } else {
            $e = oci_error($slotInsert);
            $slot_message = "Failed to book slot: " . $e['message'] . " | Debug slot_datetime: $slotDateTime, slot_date: $slotDate";
        }
        oci_free_statement($slotInsert);
    }
}

// 8. PayPal configs (as is)
$paypalURL = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
$paypalID = 'sb-zscoe41152465@business.example.com';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CoolCarters | <?= htmlspecialchars($product['product']) ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .popup { position: fixed; top: 20px; left: 50%; transform: translateX(-50%); background-color: #fff; padding: 15px 30px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); z-index: 1000; display: none; }
        .popup.success { background-color: #d4edda; color: #155724; }
        .popup.error { background-color: #f8d7da; color: #721c24; }
    </style>
</head>
<body class="bg-gray-100 font-sans">
    <?php include "homeNavbar.php"; ?>

    <main class="max-w-7xl mx-auto p-6 grid grid-cols-1 md:grid-cols-2 gap-8">
        <!-- Product Image/Details -->
        <div class="flex flex-col items-center justify-center mt-20px">
            <img src="<?= htmlspecialchars($product['filename']) ?>"
                alt="<?= htmlspecialchars($product['product']) ?>"
                class="rounded-lg shadow w-full max-w-[600px] h-[450px] object-cover"
                onerror="this.src='https://via.placeholder.com/400?text=Image+Not+Found';">
            <section class="w-full max-w-xl px-6 py-6 bg-white rounded shadow mt-6">
                <h2 class="text-2xl font-bold mb-3"><?= htmlspecialchars($product['product']) ?></h2>
                <p class="text-gray-700 leading-relaxed">
                    <span class="font-semibold">Description:</span>
                    <?= htmlspecialchars($product['product']) ?> is one of our top picks in <?= htmlspecialchars($product['category']) ?>.
                </p>
                <p class="text-red-600 font-semibold mt-3">Company Name: CoolCarters</p>
            </section>
            <!-- Slot Booking -->
            <section class="w-full max-w-xl px-6 py-6 bg-white shadow-lg rounded-lg mt-4 mb-2">
                <h2 class="text-2xl font-bold text-center text-gray-800 mb-4">🕒 Choose a Collection Slot</h2>
                <?php if (!empty($slot_message)): ?>
                    <div class="popup <?= (strpos($slot_message, 'successfully') !== false) ? 'success' : 'error' ?>" style="display:block;">
                        <?= htmlspecialchars($slot_message) ?>
                    </div>
                <?php endif; ?>
                <form method="POST" class="grid gap-4">
                    <div>
                        <label for="collection_date" class="block text-sm font-medium text-gray-700 mb-1">Select Date</label>
                        <input type="date" id="collection_date" name="collection_date" required
                            class="w-full border border-gray-300 rounded-md p-2 focus:ring-blue-500 focus:border-blue-500 shadow-sm">
                    </div>
                    <div>
                        <label for="time_slot" class="block text-sm font-medium text-gray-700 mb-1">Select Time Slot</label>
                        <select id="time_slot" name="time_slot" required
                            class="w-full border border-gray-300 rounded-md p-2 focus:ring-blue-500 focus:border-blue-500 shadow-sm">
                            <option value="">-- Choose a Time Slot --</option>
                            <option value="10:00–13:00">10:00 – 13:00</option>
                            <option value="13:00–16:00">13:00 – 16:00</option>
                            <option value="16:00–19:00">16:00 – 19:00</option>
                        </select>
                    </div>
                    <div>
                        <label for="address" class="block text-sm font-medium text-gray-700 mb-1">Pickup Address</label>
                        <textarea id="address" name="address" rows="2" required
                            class="w-full border border-gray-300 rounded-md p-2 focus:ring-blue-500 focus:border-blue-500 shadow-sm"
                            placeholder="Enter your pickup location or note for shop"></textarea>
                    </div>
                    <div class="text-center">
                        <button type="submit" name="slot_submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-6 rounded-lg shadow-md transition">
                            Confirm Slot
                        </button>
                    </div>
                </form>
            </section>
        </div>

        <!-- Info Section (Cart, Reviews, Payment) -->
        <div class="space-y-4 sticky top-20 self-start">
            <div class="bg-white rounded-lg shadow p-6 min-h-screen pb-1">
                <div>
                    <p class="text-xl font-semibold">Price:</p>
                    <p class="text-lg text-green-600 font-bold">$<?= htmlspecialchars(number_format($product['price'], 2)) ?></p>
                </div>
                <div class="flex items-center space-x-4 mt-4">
                    <p class="text-xl font-semibold">Quantity:</p>
                    <div class="flex items-center border rounded">
                        <button type="button" id="decreaseQty" class="px-2">−</button>
                        <input type="number" id="quantity" name="quantity" value="1" min="1" class="w-10 text-center border-l border-r" readonly>
                        <button type="button" id="increaseQty" class="px-2">+</button>
                    </div>
                </div>
                <div class="flex space-x-1 mt-4">
                    <span class="text-yellow-400 text-xl">★</span>
                    <span class="text-yellow-400 text-xl">★</span>
                    <span class="text-yellow-400 text-xl">★</span>
                    <span class="text-yellow-400 text-xl">☆</span>
                    <span class="text-yellow-400 text-xl">☆</span>
                </div>
                <p class="text-gray-500 mt-4"><?= htmlspecialchars($product['stock']) ?> in stock</p>
                <p class="text-sm text-red-500 mt-2">Delivery charge: Rs.100</p>
                <!-- Add to Cart -->
                <?php if (!empty($cart_message)): ?>
                    <div class="popup <?= (strpos($cart_message, 'cart!') !== false) ? 'success' : 'error' ?>" style="display:block;"><?= $cart_message ?></div>
                <?php endif; ?>
                <form id="addToCartForm" method="post" class="w-full mt-3">
                    <input type="hidden" name="product_id" value="<?= htmlspecialchars($product['id']) ?>">
                    <input type="hidden" name="quantity" id="cartQuantity" value="1">
                    <button type="submit" name="add_to_cart" class="bg-yellow-500 hover:bg-yellow-600 text-white py-2 px-4 rounded w-full">Add to Cart</button>
                </form>
                <form action="<?= htmlspecialchars($paypalURL) ?>" method="post" class="w-full mt-3">
                    <input type="hidden" name="business" value="<?= htmlspecialchars($paypalID) ?>">
                    <input type="hidden" name="cmd" value="_xclick">
                    <input type="hidden" name="item_name" value="<?= htmlspecialchars($product['product']) ?>">
                    <input type="hidden" name="amount" value="<?= htmlspecialchars($product['price']) ?>">
                    <input type="hidden" name="currency_code" value="USD">
                    <input type="hidden" id="paypalQuantity" name="quantity" value="1">
                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white py-2 px-4 rounded w-full">Buy Now with PayPal</button>
                </form>
                <?php if (!empty($wishlist_message)): ?>
                    <div class="popup <?= (strpos($wishlist_message, 'wishlist!') !== false) ? 'success' : 'error' ?>" style="display:block;"><?= $wishlist_message ?></div>
                <?php endif; ?>
                <!-- Add to Wishlist Button -->
                <form id="addToWishlistForm" method="post" class="w-full mt-3">
                    <input type="hidden" name="product_id" value="<?= htmlspecialchars($product['id']) ?>">
                    <button type="submit" name="add_to_wishlist"
                        class="bg-pink-500 hover:bg-pink-600 text-white py-2 px-4 rounded w-full flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            class="w-6 h-6 mr-2 text-white">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4.318 6.318a4.5 4.5 0 016.364 0L12 7.636l1.318-1.318a4.5 4.5 0 116.364 6.364L12 21.364l-7.682-7.682a4.5 4.5 0 010-6.364z" />
                        </svg>
                        Add to Wishlist
                    </button>
                </form>
                <!-- Reviews Section -->
                <div class="mt-6" id="reviewsSection">
                    <h3 class="text-lg font-bold mb-1">Reviews:</h3>
                    <?php foreach ($reviews as $rev): ?>
                        <div class="bg-white p-3 rounded shadow mb-3">
                            <p>Rating: <?= htmlspecialchars($rev['rating']) ?>★<br>
                                <span class="text-sm text-gray-400">From: <?= htmlspecialchars($rev['user']) ?></span>
                            </p>
                        </div>
                    <?php endforeach; ?>
                </div>
                <!-- Add Review Box -->
                <?php if (isset($_SESSION['user_id'])): ?>
                    <?php if (!empty($review_message)): ?>
                        <div class="popup <?= (strpos($review_message, 'added') !== false) ? 'success' : 'error' ?>" style="display:block;"><?= $review_message ?></div>
                    <?php endif; ?>
                    <div class="bg-gray-50 p-4 rounded shadow mt-6">
                        <h4 class="text-lg font-semibold mb-2">Leave a Review</h4>
                        <form method="post" class="space-y-4">
                            <select name="rating" required class="w-full px-4 py-2 border rounded">
                                <option value="5">★★★★★</option>
                                <option value="4">★★★★☆</option>
                                <option value="3">★★★☆☆</option>
                                <option value="2">★★☆☆☆</option>
                                <option value="1">★☆☆☆☆</option>
                            </select>
                            <button type="submit" name="review_submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded">Submit Review</button>
                        </form>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </main>
    <?php include "footer.php"; ?>
    <script>
        // Quantity JS logic
        const decreaseBtn = document.getElementById('decreaseQty');
        const increaseBtn = document.getElementById('increaseQty');
        const qtyInput = document.getElementById('quantity');
        const cartQtyInput = document.getElementById('cartQuantity');
        const paypalQtyInput = document.getElementById('paypalQuantity');
        if (decreaseBtn && increaseBtn && qtyInput) {
            decreaseBtn.addEventListener('click', () => {
                let currentValue = parseInt(qtyInput.value);
                if (currentValue > 1) {
                    qtyInput.value = currentValue - 1;
                    cartQtyInput.value = qtyInput.value;
                    paypalQtyInput.value = qtyInput.value;
                }
            });
            increaseBtn.addEventListener('click', () => {
                let currentValue = parseInt(qtyInput.value);
                if (currentValue >= 20) {
                    alert('NO MORE THAN 20 ITEMS ARE ALLOWED');
                    return;
                }
                qtyInput.value = currentValue + 1;
                cartQtyInput.value = qtyInput.value;
                paypalQtyInput.value = qtyInput.value;
            });
        }
        document.addEventListener('DOMContentLoaded', function() {
            const dateInput = document.getElementById('collection_date');
            const now = new Date();
            let tomorrow = new Date();
            tomorrow.setDate(now.getDate() + 1);
            dateInput.min = tomorrow.toISOString().split("T")[0];
            dateInput.addEventListener('input', function() {
                const selected = new Date(this.value);
                const day = selected.getDay();
                if (![3, 4, 5].includes(day)) {
                    alert('Only Wednesday, Thursday, and Friday are available for pickup.');
                    this.value = '';
                }
            });
        });
    </script>
</body>
</html>
<?php oci_close($conn); ?>
