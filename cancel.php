<?php
// cancel.php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Canceled</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans">
    <div class="max-w-7xl mx-auto p-6">
        <h1 class="text-2xl font-bold mb-4">Payment Canceled</h1>
        <p>Your payment was canceled. Please try again or contact support if you need assistance.</p>
        <a href="index.php" class="text-blue-500 hover:underline">Return to Home</a>
    </div>
</body>
</html>