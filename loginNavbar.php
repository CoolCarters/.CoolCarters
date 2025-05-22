<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home-NavBar</title>
    <link rel="stylesheet" href="./css/output.css">
</head>

<body>
    <!-- Navbar with blue background -->
    <nav class="bg-blue-600 shadow-lg sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between items-center h-16">
                <!-- Logo, brand name and hamburger menu -->
                <div class="flex items-center">
                    <!-- Hamburger menu (always visible) -->
                    <button id="mobile-menu-button" class="mr-20 text-white hover:text-blue-200 focus:outline-none">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>

                    <!-- Logo and brand name -->
                    <div class="flex items-center space-x-2">
                        <img src="./images/CoolCarters Sample 2.svg" alt="CoolCarters Logo" class="h-10 w-10 rounded-full object-cover border-2 border-white">
                        <span class="text-xl font-bold text-white">CoolCarters</span>
                    </div>
                </div>

                <div class="md:flex md:items-center md:space-x-8">
                    <!-- Vertical line separator -->
                    <div class="h-6 border-l border-gray-300"></div>
                    <a href="./index.php" class="text-white hover:text-blue-200 px-3 py-2 rounded-md text-sm font-medium transition duration-300">Home</a>
                    <!-- Rest of your nav links -->
                </div>

                <!-- Desktop Navigation -->
                <div class="hidden md:flex items-center space-x-8">
                    <a href="./aboutus.php" class="text-white hover:text-blue-200 px-3 py-2 rounded-md text-sm font-medium transition duration-300">About Us</a>
                    <a href="./contactus.php" class="text-white hover:text-blue-200 px-3 py-2 rounded-md text-sm font-medium transition duration-300">Contact Us</a>

                   
            </div>
        </div>
        </div>

        <!-- Mobile Navigation (hidden by default) -->
        <div id="mobile-menu" class="md:hidden hidden bg-blue-700">
            <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
                <a href="#about" class="text-white hover:bg-blue-800 block px-3 py-2 rounded-md text-base font-medium">About Us</a>
                <a href="#contact" class="text-white hover:bg-blue-800 block px-3 py-2 rounded-md text-base font-medium">Contact Us</a>
                
            </div>
        </div>
    </nav>

    <!-- JavaScript for mobile menu toggle -->
    <script src="./js/home.js"></script>
</body>
</html>