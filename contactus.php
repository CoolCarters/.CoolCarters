<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Contact Us</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans m-0 p-0">
  <?php include "homeNavbar.php"; ?>

  <!-- Container -->
  <div class="flex flex-wrap md:flex-nowrap p-10 gap-5">
    <!-- Left Panel -->
    <div class="flex-1 bg-gray-400 rounded-3xl p-8">
      <h2 class="text-2xl font-semibold mb-2">Reach Out - We’re listening!</h2>
      <p class="text-base leading-relaxed">
        Drop us a message, our team will get back to you<br />
        as soon as possible. We are here to help.
      </p>
      <img src="./images/contactusImage.png" alt=""
        class="w-48 md:w-60 lg:w-72 mt-6 md:mt-10 mx-auto md:ml-0" />
    </div>

    <!-- Right Panel -->
    <div class="flex-1 bg-gray-400 rounded-3xl p-8">
      <form class="flex flex-col">
        <label for="full-name" class="mt-2 mb-1 font-medium">Full Name:</label>
        <input type="text" id="full-name" name="full-name" class="p-2 rounded-md bg-white text-black border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500" />

        <label for="email" class="mt-2 mb-1 font-medium">Email:</label>
        <input type="email" id="email" name="email" class="p-2 rounded-md bg-white text-black border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500" />

        <label for="phone" class="mt-2 mb-1 font-medium">Ph no. :</label>
        <input type="tel" id="phone" name="phone" class="p-2 rounded-md bg-white text-black border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500" />

        <label for="message" class="mt-2 mb-1 font-medium">Your message:</label>
        <textarea id="message" name="message" class="p-2 rounded-md bg-white text-black border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>

        <button type="submit" id="sendbtn" class="mt-4 bg-blue-600 text-white py-2 rounded-md hover:bg-blue-700">
          Send
        </button>
      </form>
    </div>
  </div>

  <!-- Self-Service Hub -->
  <div class="bg-gray-400 rounded-xl mx-10 my-5 p-6">
    <h3 class="text-xl font-semibold mb-3">Your self-service hub:</h3>
    <div class="flex gap-8 flex-wrap items-center mt-2">
      <div class="text-center">
        <img src="https://img.icons8.com/ios-filled/50/box.png" class="w-10 h-10 mx-auto" />
        <a href="javascript:void(0);" onclick="openModal('my_Orders.php')" class="text-red-600 underline mt-1 block">My orders</a>
      </div>
      <div class="text-center">
        <img src="https://img.icons8.com/ios-filled/50/user-male-circle.png" class="w-10 h-10 mx-auto" />
        <a href="./customer/customer_profile.php" class="text-red-600 underline mt-1 block">My profile</a>
      </div>
      <div class="text-center">
        <img src="https://img.icons8.com/ios-filled/50/cancel.png" class="w-10 h-10 mx-auto" />
        <a href="javascript:void(0);" onclick="openModal('ordersCancelled.php')" class="text-red-600 underline mt-1 block">Orders cancelled</a>
      </div>
      <div class="text-center">
        <img src="https://img.icons8.com/ios-filled/50/return.png" class="w-10 h-10 mx-auto" />
        <a href="javascript:void(0);" onclick="openModal('packageReturned.php')" class="text-red-600 underline mt-1 block">Returned package</a>
      </div>
    </div>
  </div>

  <!-- Contact Details -->
  <div class="bg-gray-400 rounded-2xl px-10 py-6 mx-10 my-5">
    <h3 class="text-xl font-semibold mb-3">Need More Assistance? Here’s How to Reach us</h3>
    <p>Email: CoolCarters@gmail.com</p>
    <p>Contact Number:+977 9811111111</p>
  </div>

  <?php include "footer.php"; ?>

  <!-- Load Modal Popups -->
  <script>
    function openModal(file) {
      fetch(file)
        .then(response => response.text())
        .then(html => {
          const modalWrapper = document.createElement('div');
          modalWrapper.innerHTML = html;

          modalWrapper.style.position = 'fixed';
          modalWrapper.style.top = 0;
          modalWrapper.style.left = 0;
          modalWrapper.style.width = '100vw';
          modalWrapper.style.height = '100vh';
          modalWrapper.style.backgroundColor = 'rgba(0,0,0,0.6)';
          modalWrapper.style.display = 'flex';
          modalWrapper.style.alignItems = 'center';
          modalWrapper.style.justifyContent = 'center';
          modalWrapper.style.zIndex = 9999;

          // Close on outside click
          modalWrapper.addEventListener('click', function (e) {
            if (e.target === modalWrapper) {
              modalWrapper.remove();
            }
          });

          document.body.appendChild(modalWrapper);
        })
        .catch(error => {
          alert("Failed to load content: " + error);
        });
    }
  </script>

  <script src="./js/contactus.js"></script>
</body>
</html>
