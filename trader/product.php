<?php
// detailed_product.php
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
        <a href="#" class="hover:text-blue-600">Home</a>
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
  <main class="max-w-7xl mx-auto p-6 grid grid-cols-1 md:grid-cols-2 gap-8">
    <!-- Image Slider -->
    <div class="flex flex-col items-center justify-center">
      <img src="https://via.placeholder.com/400" alt="Product" class="rounded-lg shadow w-full">
      <div class="flex justify-between w-full mt-4 px-4">
        <button class="text-xl">&#8592;</button>
        <button class="text-xl">&#8594;</button>
      </div>
    </div>

    <!-- Product Info -->
    <div class="space-y-4">
      <div>
        <p class="text-xl font-semibold">Price:</p>
        <p class="text-lg text-green-600 font-bold">$12.99</p>
      </div>
      <div class="flex items-center space-x-4">
        <p class="text-xl font-semibold">Quantity:</p>
        <div class="flex items-center border rounded">
          <button class="px-2">-</button>
          <input type="text" value="1" class="w-10 text-center border-l border-r">
          <button class="px-2">+</button>
        </div>
      </div>
      <div class="flex space-x-1">
        <?php for ($i = 0; $i < 5; $i++) echo '<span class="text-yellow-400 text-xl">&#9733;</span>'; ?>
      </div>
      <p class="text-gray-500">9 sold</p>
      <p class="text-sm text-red-500">Delivery by: 30th March, 2025<br>Delivery charge: Rs.100</p>

      <div>
        <h3 class="text-lg font-bold mb-1">Reviews:</h3>
        <div class="bg-white p-3 rounded shadow mb-3">
          <p>This product is so cool. Wow love it.<br><span class="text-sm text-gray-400">I will buy more of this. From: Username</span></p>
        </div>
        <div class="bg-white p-3 rounded shadow">
          <p>This is so good. Really worth the money.<br><span class="text-sm text-gray-400">From: Username</span></p>
        </div>
      </div>

      <div class="flex space-x-4 mt-4">
        <button class="flex-1 bg-yellow-500 hover:bg-yellow-600 text-white py-2 px-4 rounded">Add to Cart</button>
        <button class="flex-1 bg-green-600 hover:bg-green-700 text-white py-2 px-4 rounded">Buy Now</button>
      </div>
    </div>
  </main>

  <!-- Product Description -->
  <section class="max-w-7xl mx-auto px-6 py-6 bg-white mt-4 rounded shadow">
    <h2 class="text-xl font-bold mb-2">Product name</h2>
    <p class="text-gray-600">
      Description: jfourwgorwoghrwonvosnorwngiorwgiorw blabla and bla blablablablaohwofhowehfewipefioewhfioewhfiooehfioewhfio... <a href="#" class="text-blue-500 hover:underline">See more</a>
    </p>
    <p class="text-red-600 font-semibold mt-2">Company name:</p>
  </section>
</body>
</html>