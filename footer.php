<style>
  footer {
    position: relative;
    z-index: 10;
  }
  .social-box {
    position: absolute;
    bottom: 1rem; /* pushes to bottom within footer */
    left: 50%;
    transform: translateX(-50%);
  }
</style>

<footer class="bg-blue-600 text-white py-12 mt-auto">
  <div class="max-w-7xl mx-auto px-4 flex justify-between items-start relative min-h-[120px]">
    
    <!-- Company Section -->
    <div class="text-left">
      <h1 class="text-xl font-bold mb-3">Company</h1>
      <ul class="space-y-2">
        <li><a href="./index.php" class="hover:text-blue-200 transition-colors">Home</a></li>
        <li><a href="./aboutus.php" class="hover:text-blue-200 transition-colors">About Us</a></li>
      </ul>
      <p class="text-xs text-blue-100 mt-4">© 2025 CoolCarters. All rights reserved</p>
    </div>

    <!-- Social Media Section (Centered & Bottom-Aligned) -->
    <div class="social-box backdrop-blur-sm rounded-lg shadow-lg p-2">
      <h2 class="text-sm font-medium mb-3 text-center">TO KEEP US CONNECTED</h2>
      <div class="flex justify-center space-x-4">
        
        <!-- Instagram -->
        <a href="https://www.instagram.com" target="_blank">
          <img src="./images/instagram-svgrepo-com.svg" alt="Instagram" class="h-5 w-5"
               onerror="this.onerror=null;this.src='https://www.svgrepo.com/show/512120/instagram.svg';">
        </a>
        
        <!-- Facebook -->
        <a href="https://www.facebook.com" target="_blank">
          <img src="./images/facebook-svgrepo-com.svg" alt="Facebook" class="h-5 w-5"
               onerror="this.onerror=null;this.src='https://www.svgrepo.com/show/475645/facebook-color.svg';">
        </a>

        <!-- Twitter/X -->
        <a href="https://www.twitter.com" target="_blank">
          <img src="./images/x.svg" alt="Twitter" class="h-5 w-5"
               onerror="this.onerror=null;this.src='https://www.svgrepo.com/show/509313/twitter-x.svg';">
        </a>
        
      </div>
    </div>

  </div>
</footer>
