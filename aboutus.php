<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us</title>
    <link rel="stylesheet" href="./css/output.css">
</head>

<body>
    <?php include "aboutusNavbar.php"; ?>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <h1 class="text-3xl font-bold text-center mb-12">ABOUT US</h1>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
            <!-- Image Card -->
            <div class="bg-black rounded-lg shadow-lg overflow-hidden p-6 flex justify-center">
                <img src="./images/CoolCarters Sample 2.svg" alt="CoolCarters Logo" class="max-w-full h-auto">
            </div>
            
            <!-- Text Card -->
            <div class="bg-white rounded-lg shadow-lg overflow-hidden p-6">
                <p class="text-gray-700 leading-relaxed">
                    Your trusted e-commerce platform that serves in uplifting local businesses in CleckHuddersFax. Our motive is to connect local businesses, helping them thrive together while preserving their uniqueness and integrity. This offers customers a seamless online shopping experience where they can discover unique and high quality local goods anytime. At CoolCarters, we are dedicated in offering simple and responsive platform for all users. Whether you might be searching for something unique, handmade with love or something from our trusted local providers, for we are there to make your experience worthwile and convenient. Join us and help us to build a successful local businesses together with you and supporting the local community to thrive!
                </p>
            </div>
        </div>

    </div>

    <div class="flex justify-between items-center w-full p-6 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl border border-blue-100">
        <p class="text-lg font-medium text-gray-800">Experience seamless shopping today on CoolCarters. Join us now!</p>
        <a href="./login.php">
            <button type="submit" class="px-6 py-3 bg-blue-600 text-white rounded-lg cursor-pointer transition-colors shadow-md hover:shadow-lg">
                Log In
            </button></a>
    </div>

    <?php include "aboutusFooter.php"; ?>
</body>

</html>