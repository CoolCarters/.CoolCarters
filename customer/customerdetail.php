<?php
// customer_profile.php
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Customer Profile - CoolCarters</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans">
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

  <main class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-3 gap-8 p-6">
    <!-- Sidebar -->
    <aside class="bg-white rounded-xl p-6 shadow">
      <div class="text-center mb-4">
        <div class="bg-gray-300 w-20 h-20 rounded-full mx-auto"></div>
        <p class="mt-2 font-semibold">Username</p>
        <p class="text-gray-500 text-sm">blablabla@gmail.com</p>
      </div>
      <nav class="space-y-2 text-gray-700">
        <a href="#" class="block hover:text-blue-600">My orders</a>
        <a href="#" class="block hover:text-blue-600">User Settings</a>
        <a href="#" class="block hover:text-blue-600">My cancellations</a>
        <a href="#" class="block hover:text-blue-600">My returns</a>
        <h4 class="mt-4 font-semibold text-red-600">Categories</h4>
        <ul class="text-sm space-y-1">
          <li>Baby products</li>
          <li>Cosmetics</li>
          <li>Kitchen appliance</li>
          <li>Clothes</li>
          <li>Food & Snacks</li>
          <li>Beverages</li>
        </ul>
      </nav>
      <div class="mt-6">
        <button class="w-full bg-gray-700 text-white py-2 rounded hover:bg-gray-800">Sign out</button>
        <button class="w-full mt-2 text-red-600 font-semibold hover:underline">Delete Account</button>
      </div>
    </aside>

    <!-- Profile Content -->
    <section class="lg:col-span-2 bg-white rounded-xl p-8 shadow">
      <div class="text-center mb-6">
        <div class="bg-gray-300 w-24 h-24 rounded-full mx-auto mb-2"></div>
        <h2 class="text-2xl font-bold">Hello, Username!</h2>
        <p class="text-gray-500">blablabla@gmail.com | +977 9812345678 | Female</p>
        <button class="mt-4 bg-gray-400 hover:bg-gray-500 text-white px-4 py-2 rounded">Change password</button>
      </div>

      <form action="#" method="POST" class="grid grid-cols-1 gap-4">
        <div class="flex items-center gap-2">
          <label class="w-32 font-medium">First name:</label>
          <input type="text" value="" class="flex-1 px-4 py-2 border rounded focus:ring-2 focus:ring-blue-500">
        </div>
        <div class="flex items-center gap-2">
          <label class="w-32 font-medium">Last name:</label>
          <input type="text" value="" class="flex-1 px-4 py-2 border rounded focus:ring-2 focus:ring-blue-500">
        </div>
        <div class="flex items-center gap-2">
          <label class="w-32 font-medium">Email:</label>
          <input type="email" value="" class="flex-1 px-4 py-2 border rounded focus:ring-2 focus:ring-blue-500">
        </div>
        <div class="flex items-center gap-2">
          <label class="w-32 font-medium">Gender:</label>
          <div class="flex gap-4">
            <label class="flex items-center"><input type="radio" name="gender" value="female" class="mr-2">Female</label>
            <label class="flex items-center"><input type="radio" name="gender" value="male" class="mr-2">Male</label>
          </div>
        </div>
        <div class="flex items-center gap-2">
          <label class="w-32 font-medium">Phone No:</label>
          <input type="text" placeholder="+977" class="flex-1 px-4 py-2 border rounded focus:ring-2 focus:ring-blue-500">
        </div>
        <div class="flex items-center gap-2">
          <label class="w-32 font-medium">Code:</label>
          <div class="flex gap-2">
            <?php for ($i = 0; $i < 6; $i++) echo '<input type="text" maxlength="1" class="w-10 h-10 border rounded text-center focus:ring-2 focus:ring-blue-500">'; ?>
          </div>
        </div>
        <div class="text-center">
          <button type="submit" class="mt-4 bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded">Confirm</button>
        </div>
      </form>
    </section>
  </main>
</body>
</html>