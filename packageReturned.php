<div class="bg-white rounded-xl shadow-lg w-full max-w-2xl p-0 relative">
  <!-- Header -->
  <div class="bg-blue-600 text-white px-6 py-4 flex justify-between items-center rounded-t-xl">
    <h2 class="text-lg font-semibold">Packages Returned</h2>
    <button onclick="this.closest('div[class*=max-w]').parentElement.remove()" class="text-2xl font-bold hover:text-gray-300">×</button>
  </div>

  <!-- Body -->
  <div class="p-4 max-h-[450px] overflow-y-auto space-y-4" id="return-body"></div>
</div>

<script>
  const returnedPackages = [
    { name: "Product Name", reason: "Wrong Item Delivered", rating: "★☆☆☆☆", date: "2/14/2025", total: "NPR. 500 /-" },
    { name: "Product Name", reason: "Item was Expired", rating: "☆☆☆☆☆", date: "1/10/2025", total: "NPR. 750 /-" },
    { name: "Product Name", reason: "Damaged Package", rating: "★★☆☆☆", date: "12/20/2024", total: "NPR. 350 /-" }
  ];

  function renderReturns() {
    const body = document.getElementById("return-body");
    body.innerHTML = '';

    returnedPackages.forEach((item, i) => {
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
          <button class="absolute top-0 right-0 text-red-600 text-lg hover:text-red-800" onclick="removeReturn(${i})">×</button>
        </div>
      `;
    });
  }

  function removeReturn(index) {
    returnedPackages.splice(index, 1);
    renderReturns();
  }

  renderReturns();
</script>
