<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Detect current path to handle relative paths
$isInCustomerFolder = strpos($_SERVER['PHP_SELF'], 'customer/') !== false;
$pathPrefix = $isInCustomerFolder ? '../' : './';

$role         = $_SESSION['role'] ?? null;
$customerName = '';
if ($role === 'customer' && isset($_SESSION['customer_name'])) {
    $customerName = htmlspecialchars($_SESSION['customer_name']);
} elseif ($role === 'trader') {
    header("Location: {$pathPrefix}trader/traderInterface.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>CoolCarters</title>
    <link rel="stylesheet" href="<?= $pathPrefix ?>css/output.css" />
</head>

<body>
    <nav class="bg-blue-600 shadow-lg sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 py-3 md:px-6 flex items-center justify-between">
            <!-- Brand -->
            <div class="flex items-center space-x-4">
                <a href="<?= $pathPrefix ?>index.php">
                    <img src="<?= $pathPrefix ?>images/CoolCarters Sample 2.svg" alt="Logo"
                        class="h-10 w-10 rounded-full border-2 border-white object-cover" />
                </a>
                <a href="<?= $pathPrefix ?>index.php" class="text-white text-2xl font-semibold hover:text-blue-200 transition">
                    CoolCarters
                </a>
            </div>

            <!-- Desktop Nav -->
            <div class="hidden md:flex items-center space-x-8">

                <!-- User Login Info -->
                <?php if ($role === 'customer'): ?>
                    <a href="<?= $pathPrefix ?>customer/customer_profile.php"
                        class="text-white px-3 py-1 font-medium hover:underline transition">
                        <?= $customerName ?>
                    </a>
                    <a href="<?= $pathPrefix ?>logout.php"
                        class="ml-4 bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-md font-medium transition">
                        Logout
                    </a>
                <?php else: ?>
                    <a href="<?= $pathPrefix ?>login.php"
                        class="text-white hover:text-blue-200 px-3 py-2 rounded-md text-sm font-medium transition duration-300">
                        Login / Signup
                    </a>
                <?php endif; ?>
            </div>

            <!-- Mobile Hamburger -->
            <button id="mobile-menu-button" class="md:hidden text-white focus:outline-none">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
        </div>

        <!-- Mobile Menu -->
        <div id="mobile-menu" class="hidden md:hidden bg-blue-700">
            <?php if ($role === 'customer'): ?>
                <a href="<?= $pathPrefix ?>customer/customer_profile.php" class="block text-white px-4 py-2 hover:bg-blue-600"><?= $customerName ?></a>
                <a href="<?= $pathPrefix ?>logout.php" class="block bg-red-500 text-white px-4 py-2 hover:bg-red-600 rounded-b-lg">Logout</a>
            <?php else: ?>
                <a href="<?= $pathPrefix ?>login.php" class="block text-white px-4 py-2 hover:bg-blue-600">Login / Signup</a>
            <?php endif; ?>
        </div>
    </nav>

    <!-- Toggle Script -->
    <script>
        document.getElementById('mobile-menu-button').addEventListener('click', () => {
            document.getElementById('mobile-menu').classList.toggle('hidden');
        });
    </script>
</body>

</html>