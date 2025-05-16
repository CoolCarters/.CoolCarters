<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Contact Us</title>
  <link rel="stylesheet" href="./css/style.css" />
</head>
<body>
  <?php include "homeNavbar.php"; ?>
  <div class="container">
    <div class="left-panel">
      <h2>Reach Out - We’re listening!</h2>
      <p>Drop us a message, our team will get back to you<br> as soon as possible. We are here to help.</p>
      <img src="desktop/ccimage.png" alt="" class="support-img" />
    </div>

    <div class="right-panel">
      <form>
        <label for="full-name">Full Name:</label>
        <input type="text" id="full-name" name="full-name" />

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" />

        <label for="phone">Ph no. :</label>
        <input type="tel" id="phone" name="phone" />

        <label for="message">Your message:</label>
        <textarea id="message" name="message"></textarea>
      </form>
    </div>
  </div>

  <div class="self-service">
    <h3>Your self-service hub:</h3>
    <div class="services">
      <div class="service">
        <img src="https://img.icons8.com/ios-filled/50/box.png" />
        <a href="#">My orders</a>
      </div>
      <div class="service">
        <img src="https://img.icons8.com/ios-filled/50/user-male-circle.png" />
        <a href="#">My profile</a>
      </div>
      <div class="service">
        <img src="https://img.icons8.com/ios-filled/50/cancel.png" />
        <a href="#">Orders cancelled</a>
      </div>
      <div class="service">
        <img src="https://img.icons8.com/ios-filled/50/return.png" />
        <a href="#">Returned package</a>
      </div>
    </div>
  </div>

  <div class="contact-details">
    <h3>Need More Assistance? Here’s How to Reach us</h3>
    <p>Email:</p>
    <p>Contact Number:</p>
  </div>

  <?php include "footer.php"; ?>
</body>
</html>
