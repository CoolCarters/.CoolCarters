<div class="bg-white rounded-xl shadow-lg w-full max-w-2xl p-0 relative">
  <!-- Header -->
  <div class="bg-blue-600 text-white px-6 py-4 flex justify-between items-center rounded-t-xl">
    <h2 class="text-lg font-semibold">Orders Cancelled</h2>
    <button onclick="this.closest('div[class*=max-w]').parentElement.remove()" class="text-2xl font-bold hover:text-gray-300">×</button>
  </div>

  <!-- Body -->
  <div class="p-4 max-h-[450px] overflow-y-auto space-y-4" id="cancel-body"></div>
</div>

<script>
  const cancelledOrders = [
    { name: "Product Name", reason: "Customer Cancelled", rating: "☆☆☆☆☆", date: "3/10/2025", total: "NPR. 800 /-" },
    { name: "Product Name", reason: "Payment Not Completed", rating: "★☆☆☆☆", date: "2/28/2025", total: "NPR. 450 /-" },
    { name: "Product Name", reason: "Order Timed Out", rating: "★★☆☆☆", date: "1/5/2025", total: "NPR. 999 /-" },
    { name: "Product Name", reason: "Delivery Address Invalid", rating: "☆☆☆☆☆", date: "3/2/2025", total: "NPR. 600 /-" },
    { name: "Product Name", reason: "Inventory Out of Stock", rating: "★★★☆☆", date: "3/5/2025", total: "NPR. 1200 /-" },
    { name: "Product Name", reason: "Change Payment Method", rating: "★☆☆☆☆", date: "2/18/2025", total: "NPR. 300 /-" },
    { name: "Product Name", reason: "Change of Mind", rating: "★★☆☆☆", date: "1/30/2025", total: "NPR. 550 /-" },
    { name: "Product Name", reason: "Product Mispriced", rating: "★★★☆☆", date: "1/15/2025", total: "NPR. 999 /-" }
  ];

  function renderCancelledOrders() {
    const body = document.getElementById("cancel-body");
    body.innerHTML = '';

    cancelledOrders.forEach((item, i) => {
      body.innerHTML += `
        <div class="flex items-start border-b pb-4 relative">
          <div class="w-14 h-14 bg-gray-300 flex items-center justify-center text-xl rounded-md">🛒</div>
          <div class="ml-4 flex-1">
            <p class="font-semibold text-sm">${item.name}</p>
            <p class="text-xs text-gray-700 font-medium mt-1">${item.reason}</p>
            <p class="text-sm text-red-500">Rating: ${item.rating}</p>
          </div>
          <div class="text-xs text-right text-gray-700 flex flex-col items-end mt-1">
            <p>Date: ${item.date}</p>
            <p>Total: ${item.total}</p>
          </div>
          <button class="absolute top-0 right-0 text-red-600 text-lg hover:text-red-800" onclick="removeCancelled(${i})">×</button>
        </div>
      `;
    });
  }

  function removeCancelled(index) {
    cancelledOrders.splice(index, 1);
    renderCancelledOrders();
  }

  renderCancelledOrders();
</script>
