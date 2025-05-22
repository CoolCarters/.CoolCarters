<div class="bg-white rounded-xl shadow-lg w-full max-w-2xl p-0 relative">
  <!-- Header -->
  <div class="bg-blue-600 text-white px-6 py-4 flex justify-between items-center rounded-t-xl">
    <h2 class="text-lg font-semibold">My Orders</h2>
    <button onclick="this.closest('div[class*=max-w]').parentElement.remove()" class="text-2xl font-bold hover:text-gray-300">×</button>
  </div>

  <!-- Body -->
  <div class="p-4 max-h-[450px] overflow-y-auto space-y-4" id="order-body"></div>
</div>

<script>
  const orders = [
    { name: "Product Name", status: "Delivery pending", type: "ONGOING", rating: "★☆☆☆☆", quantity: 1, price: "NPR. 500 /-", date: "" },
    { name: "Product Name", status: "Delivered", type: "PAID", rating: "☆☆☆☆☆", quantity: 2, price: "NPR. 1000 /-", date: "2/12/2025" },
    { name: "Product Name", status: "Delivered", type: "PAID", rating: "☆☆☆☆☆", quantity: 1, price: "NPR. 600 /-", date: "1/20/2025" },
    { name: "Product Name", status: "Delivered", type: "PAID", rating: "☆☆☆☆☆", quantity: 3, price: "NPR. 1500 /-", date: "12/1/2024" },
    { name: "Product Name", status: "Delivered", type: "PAID", rating: "☆☆☆☆☆", quantity: 1, price: "NPR. 400 /-", date: "11/15/2024" },
  ];

  function renderOrders() {
    const orderBody = document.getElementById("order-body");
    orderBody.innerHTML = "";

    const ongoing = orders.filter(o => o.type === "ONGOING");
    const paid = orders.filter(o => o.type === "PAID");

    [...ongoing, ...paid].forEach((order, i) => {
      orderBody.innerHTML += `
        <div class="flex items-start border-b pb-4 relative">
          <div class="w-14 h-14 bg-gray-300 flex items-center justify-center text-xl rounded-md">🛒</div>
          <div class="ml-4 flex-1">
            <p class="font-semibold text-sm">${order.name}</p>
            <p class="text-xs text-gray-700 font-medium mt-1">${order.type}</p>
            <p class="text-xs text-gray-600">${order.status}${order.date ? ` | ${order.date}` : ""}</p>
            <p class="text-sm text-red-500">Rating: ${order.rating}</p>
          </div>
          <div class="text-xs text-right text-gray-700 flex flex-col items-end mt-1">
            <p>Status: ${order.status}</p>
            <p>Qty: ${order.quantity}</p>
            <p>Total: ${order.price}</p>
            ${order.type === "ONGOING" ? `<button class="mt-1 bg-red-500 text-white px-2 py-1 rounded text-xs hover:bg-red-600" onclick="cancelOrder(${i})">Cancel</button>` : ""}
          </div>
          <button class="absolute top-0 right-0 text-red-600 text-lg hover:text-red-800" onclick="removeOrder(${i})">×</button>
        </div>
      `;
    });
  }

  function removeOrder(index) {
    orders.splice(index, 1);
    renderOrders();
  }

  function cancelOrder(index) {
    if (confirm("Are you sure you want to cancel this order?")) {
      orders.splice(index, 1);
      renderOrders();
    }
  }

  renderOrders();
</script>
