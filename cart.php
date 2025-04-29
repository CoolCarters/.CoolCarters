<!-- Control Buttons -->
<!-- <button id="add-to-cart" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition">
            Add to Cart
        </button>
        <button id="remove-from-cart" class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600 transition">
            Remove from Cart
        </button> -->



<!-- js -->
// Initialize cart count
        let cartCount = 0;
        const cartCounter = document.getElementById('cart-counter');
        
        // Add to cart button
        document.getElementById('add-to-cart').addEventListener('click', function() {
            cartCount++;
            updateCartCounter();
        });
        
        // Remove from cart button
        document.getElementById('remove-from-cart').addEventListener('click', function() {
            if (cartCount > 0) {
                cartCount--;
                updateCartCounter();
            }
        });
        
        // Update counter display
        function updateCartCounter() {
            cartCounter.textContent = cartCount;
            
            // Animation effect
            cartCounter.classList.add('animate-ping');
            setTimeout(() => {
                cartCounter.classList.remove('animate-ping');
            }, 300);
            
            // Change color if empty
            if (cartCount === 0) {
                cartCounter.classList.remove('bg-red-500');
                cartCounter.classList.add('bg-gray-500');
            } else {
                cartCounter.classList.remove('bg-gray-500');
                cartCounter.classList.add('bg-red-500');
            }
        }