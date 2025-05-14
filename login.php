<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CoolCarters</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./css/Login.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            background-color: white;
            color: black;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 5%;
            background-color: #2e2eff;
            color: white;
        }

        .logo-container {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .logo-img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            border: 2px solid white;
            object-fit: contain;
        }

        .logo-text {
            font-size: 1.5rem;
            font-weight: bold;
        }

        nav {
            display: flex;
            gap: 20px;
        }

        nav a {
            background-color: white;
            color: black;
            text-decoration: underline;
            text-decoration-color: black;
            font-size: 0.9rem;
            padding: 8px 15px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        nav a:hover {
            background-color: #d3d3d3;
            text-decoration: underline;
        }

        .main-content {
            display: flex;
            justify-content: center;
            align-items: stretch;
            flex: 1;
            padding: 20px;
            position: relative;
        }

        .signup-section {
            background-color: rgb(163, 177, 211);
            width: 100%;
            max-width: 450px;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            align-items: center;
            padding: 40px;
            text-align: center;
            flex: 1;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
            z-index: 1;
        }

        .welcome-section {
            background-color: rgb(163, 177, 211);
            width: 100%;
            max-width: 450px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 40px;
            text-align: center;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
            position: absolute;
            top: 20px;
            left: calc(50% - 450px);
            height: calc(100% - 40px);
            z-index: 2;
            transition: transform 0.6s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .welcome-section.slid {
            transform: translateX(450px);
        }

        .welcome-content {
            opacity: 1;
            transition: opacity 0.3s ease-in-out;
        }

        .welcome-content.fade-out {
            opacity: 0;
        }

        .welcome-section h1 {
            font-size: 1.8rem;
            margin-bottom: 15px;
        }

        .welcome-section p {
            margin-bottom: 20px;
            font-size: 1rem;
        }

        .welcome-section p:last-child {
            display: flex;
            align-items: center;
            gap: 10px;
            white-space: nowrap;
        }

        .signup-btn, .login-btn-alt {
            background-color: rgb(42, 127, 218);
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 5px;
            font-size: 1rem;
            cursor: pointer;
            width: 100%;
            max-width: 100px;
            transition: background-color 0.3s ease;
        }

        .signup-btn:hover, .login-btn-alt:hover {
            background-color: rgb(33, 100, 172);
        }

        .login-form {
            background-color: rgb(224, 225, 226);
            width: 100%;
            max-width: 450px;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            padding: 40px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            flex: 1;
            z-index: 0;
        }

        .login-form h2,
        .signup-container h2 {
            margin-bottom: 20px;
            font-size: 1.5rem;
            text-align: left;
            width: 100%;
        }

        .form-group {
            margin-bottom: 15px;
            width: 100%;
            position: relative;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1rem;
            transition: border-color 0.3s ease;
        }

        .form-group input:focus,
        .form-group select:focus {
            border-color: #2e2eff;
            outline: none;
        }

        .login-btn {
            background-color: #2e2eff;
            color: white;
            border: none;
            padding: 12px;
            margin: 5px 30%;
            border-radius: 5px;
            font-size: 1.3rem;
            cursor: pointer;
            width: 35%;
            margin-top: 5px;
            transition: background-color 0.3s ease;
        }

        .login-btn:hover {
            background-color: black;
        }

        .terms {
            display: flex;
            align-items: center;
            margin: 20px 18%;
            font-size: 0.8rem;
        }

        .terms input {
            margin-left: 15px;
        }

        .divider {
            text-align: center;
            margin: 20px 0;
            position: relative;
            font-size: 0.9rem;
        }

        .divider::before,
        .divider::after {
            content: "";
            display: inline-block;
            width: 30%;
            height: 2px;
            background-color: black;
            position: absolute;
            top: 50%;
        }

        .divider::before {
            left: 0;
        }

        .divider::after {
            right: 0;
        }

        .social-login {
            text-align: center;
            margin-bottom: 20px;
        }

        .social-login .social-container {
            background-color: #fff;
            width: 70%;
            padding: 15px;
            border-radius: 15px;
            display: inline-flex;
            flex-direction: column;
            align-items: center;
            gap: 13px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .social-login p {
            font-size: 12px;
            color: black;
        }

        .social-buttons {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 30px;
        }

        .social-btn {
            padding: 5px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            display: flex;
            align-items: center;
            background-color: #fff;
            transition: background-color 0.3s ease;
        }

        .social-btn:hover {
            background-color: #f0f0f0;
        }

        .social-btn img {
            width: 34px;
            height: 34px;
        }

        footer {
            background-color: #0055ff;
            color: white;
            padding: 30px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 16px;
            position: relative;
        }

        .payment-method {
            font-size: 18px;
            margin-bottom: 10px;
        }

        .payment-method .top-row {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .paypal-icon {
            width: 50px;
            height: 50px;
            margin-left: 8px;
            padding: 2px;
        }

        .supports-paypal {
            margin: -10px 0;
            display: flex;
            align-items: center;
            font-size: 14px;
        }

        .follow-us {
            display: flex;
            align-items: flex-end;
            margin-left: auto;
        }

        .follow-us span {
            margin-right: 15px;
            margin-bottom: 5px;
        }

        .social-icons-footer {
            display: flex;
            align-items: center;
        }

        .social-icons-footer img {
            width: 28px;
            height: 28px;
            margin-left: 15px;
            transition: opacity 0.3s ease;
        }

        .social-icons-footer img:hover {
            opacity: 0.8;
        }

        @media (min-width: 768px) {
            .main-content {
                flex-direction: row;
                min-height: calc(100vh - 120px);
            }

            .signup-section {
                flex: 1;
                padding: 15px;
                order: 1;
            }

            .login-form {
                flex: 1;
                padding: 50px;
                order: 2;
            }

            .welcome-section {
                flex: 1;
                padding: 50px;
                order: 0;
                position: absolute;
                left: calc(50% - 450px);
                top: 20px;
                height: calc(100% - 40px);
                width: 100%;
                max-width: 450px;
            }

            .welcome-section.slid {
                transform: translateX(450px);
            }
        }

        .error {
            color: red;
            font-size: 0.8rem;
            margin-top: 5px;
        }

        .success {
            color: green;
            font-size: 0.9rem;
            margin-bottom: 15px;
            text-align: center;
        }

        .signup-container {
            background-color: rgb(163, 177, 211);
            padding: 40px;
            width: 100%;
            display: none;
            opacity: 0;
            transition: opacity 0.5s ease-in-out;
        }

        .signup-container.active {
            display: block;
            opacity: 1;
        }

        .form-group {
            margin-bottom: 15px;
            width: 100%;
            position: relative;
        }

        .form-group input[type="text"],
        .form-group input[type="email"],
        .form-group input[type="password"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
            font-size: 0.9em;
        }

        .role-group, .gender-group {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }

        .role-group label, .gender-group label {
            margin-left: 5px;
            color: #555;
            font-size: 0.9em;
        }

        .role-group input[type="radio"],
        .gender-group input[type="radio"] {
            margin-right: 5px;
        }

        .next-btn {
            background-color: #2e2eff;
            color: white;
            border: none;
            padding: 12px;
            margin: 5px 30%;
            border-radius: 5px;
            font-size: 1.3rem;
            cursor: pointer;
            width: 35%;
            margin-top: 5px;
            transition: background-color 0.3s ease;
        }

        .next-btn:hover {
            background-color:rgb(15, 15, 15)
        }

        #trader-form .gender-group {
            display: none;
        }

        #trader-form .form-group:nth-child(7) {
            margin-bottom: 20px;
        }

        #customer-form .form-group:nth-child(6) {
            display: none;
        }

        #customer-form .gender-group {
            margin-top: 20px;
        }

        #customer-form .form-group:nth-child(5) {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <header>
        <div class="logo-container">
            <img src="images/CoolCarters Sample 2.svg" alt="CoolCarters Logo" class="logo-img">
            <div class="logo-text">CoolCarters</div>
        </div>
        <nav>
            <a href="homenavbar.php">Home</a>
            <a href="aboutus.php">About us</a>
            <a href="contactus.php">Contact us</a>
        </nav>
    </header>
    
    <div class="main-content">
        <section class="login-form">
            <?php
            $email = $password = $role = "";
            $emailErr = $passwordErr = $roleErr = "";
            $successMsg = "";

            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                if (empty($_POST["email"])) {
                    $emailErr = "Email is required";
                } else {
                    $email = test_input($_POST["email"]);
                    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                        $emailErr = "Invalid email format";
                    }
                }

                if (empty($_POST["password"])) {
                    $passwordErr = "Password is required";
                } else {
                    $password = test_input($_POST["password"]);
                }

                if (empty($_POST["role"])) {
                    $roleErr = "Role is required";
                } else {
                    $role = test_input($_POST["role"]);
                }

                if (empty($emailErr) && empty($passwordErr) && empty($roleErr)) {
                    $successMsg = "Login successful! Redirecting...";
                }
            }

            function test_input($data) {
                $data = trim($data);
                $data = stripslashes($data);
                $data = htmlspecialchars($data);
                return $data;
            }
            ?>

            <h2>Log In</h2>

            <?php if (!empty($successMsg)): ?>
                <div class="success"><?php echo $successMsg; ?></div>
            <?php endif; ?>

            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <div class="form-group">
                    <input type="email" id="email" name="email" placeholder="Email" required>
                    <span class="error"><?php echo $emailErr; ?></span>
                </div>
                <div class="form-group" style="position: relative;">
                    <input type="password" id="password" name="password" placeholder="Password" required>
                    <img src="images/hide.png" alt="Toggle Password" id="togglePassword" 
                         style="position: absolute; top: 10px; right: 10px; width: 20px; height: 20px; cursor: pointer;">
                    <span class="error"><?php echo $passwordErr; ?></span>
                </div>

                <div class="form-group">
                    <select id="role" name="role">
                        <option value="" disabled selected>Select Role</option>
                        <option value="trader" <?php if ($role == "trader") echo "selected"; ?>>Trader</option>
                        <option value="customer" <?php if ($role == "customer") echo "selected"; ?>>Customer</option>
                    </select>
                    <span class="error"><?php echo $roleErr; ?></span>
                </div>

                <button type="submit" class="login-btn">Log In</button>
                <div class="terms">
                    <label for="terms">I accept the terms and conditions:</label>
                    <input type="checkbox" id="terms" name="terms" required>
                </div>

                <div class="divider">OR</div>

                <div class="social-login">
                    <div class="social-container">
                        <p>CONTINUE WITH:</p>
                        <div class="social-buttons">
                            <button class="social-btn">
                                <img src="images/icons8-gmail-48.png" class="Gmail">
                            </button>
                            <button class="social-btn">
                                <img src="images/icons8-facebook-48.png" class="Facebook">
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </section>

        <section class="signup-section">
            <div id="trader-form" class="signup-container active">
                <h2>Sign up</h2>
                <form>
                    <div class="form-group role-group">
                        <label>Role:</label>
                        <input type="radio" id="trader-role" name="role" value="trader" checked>
                        <label for="trader-role">Trader</label>
                        <input type="radio" id="customer-role" name="role" value="customer">
                        <label for="customer-role">Customer</label>
                    </div>
                    <div class="form-group">
                        <input type="text" id="trader-firstName" name="firstName" placeholder="First Name" required>
                    </div>
                    <div class="form-group">
                        <input type="text" id="trader-lastName" name="lastName" placeholder="Last Name" required>
                    </div>
                    <div class="form-group">
                        <input type="email" id="trader-email" name="email" placeholder="Email" required>
                    </div>
                    <div class="form-group" style="position: relative;">
                        <input type="password" id="trader-password" name="password" placeholder="Password" required>
                        <img src="images/hide.png" alt="Toggle Password" class="toggle-password" 
                             data-input="trader-password" style="position: absolute; top: 10px; right: 10px; width: 20px; height: 20px; cursor: pointer;">
                    </div>
                    <div class="form-group" style="position: relative;">
                        <input type="password" id="trader-confirmPassword" name="confirmPassword" placeholder="Confirm Password" required>
                         <img src="images/hide.png" alt="Toggle Password" class="toggle-password" 
                             data-input="trader-confirmPassword" style="position: absolute; top: 10px; right: 10px; width: 20px; height: 20px; cursor: pointer;">
                    </div>
                    <div class="form-group">
                        <input type="text" id="companyName" name="companyName" placeholder="Company name" required>
                    </div>
                    <button type="submit" class="next-btn">Next</button>
                </form>
            </div>

            <div id="customer-form" class="signup-container">
                <h2>Sign up</h2>
                <form>
                    <div class="form-group role-group">
                        <label>Role:</label>
                        <input type="radio" id="trader-role" name="role" value="trader">
                        <label for="trader-role">Trader</label>
                        <input type="radio" id="customer-role" name="role" value="customer" checked>
                        <label for="customer-role">Customer</label>
                    </div>
                    <div class="form-group">
                        <input type="text" id="customer-firstName" name="firstName" placeholder="First Name" required>
                    </div>
                    <div class="form-group">
                        <input type="text" id="customer-lastName" name="lastName" placeholder="Last Name" required>
                    </div>
                    <div class="form-group">
                        <input type="email" id="customer-email" name="email" placeholder="Email" required>
                    </div>
                    <div class="form-group gender-group">
                        <label>Gender:</label>
                        <input type="radio" id="female" name="gender" value="female">
                        <label for="female">Female</label>
                        <input type="radio" id="male" name="gender" value="male">
                        <label for="male">Male</label>
                    </div>
                    <div class="form-group" style= "position: relative;">
                        <input type="password" id="customer-password" name="password" placeholder="Password" required>
                        <img src="images/hide.png" alt="Toggle Password" class="toggle-password" 
                             data-input="trader-password" style="position: absolute; top: 10px; right: 10px; width: 20px; height: 20px; cursor: pointer;">
                    </div>
                    <div class="form-group">
                        <input type="password" id="customer-password" name="Password" placeholder=" Password" required>
                        <img src="images/hide.png" alt="Toggle Password" class="toggle-password" 
                            data-input="trader-password" style="position: absolute; top: 10px; right: 10px; width: 20px; height: 20px; cursor: pointer;">
                    </div>
                    <div class="form-group">
                        <input type="password" id="customer-confirmPassword" name="confirmPassword" placeholder="Confirm Password" required>
                        <img src="images/hide.png" alt="Toggle Password" class="toggle-password" 
                             data-input="trader-password" style="position: absolute; top: 10px; right: 10px; width: 20px; height: 20px; cursor: pointer;">
                    </div>
                    <button type="submit" class="next-btn">Next</button>
                </form>
            </div>
        </section>

        <section class="welcome-section">
            <div class="welcome-content" id="welcomeContent">
                <h1>WELCOME BACK!</h1>
                <p>We want you to join us to stay connected with the family.</p>
                <p><em><b>New to CoolCarters?</b></em><button class="signup-btn" id="signUpBtn">Sign Up</button></p>
            </div>
        </section>
    </div>
    <footer>
        <div class="payment-method">
            <div class="top-row">
                <div class="payment-method"><b>PAYMENT METHOD</b></div>
                <img src="images/paypal (1).png" class="paypal-icon">
            </div>
            <div class="supports-paypal">Supports Paypal</div>
        </div>
        <div class="follow-us">
            <span>FOLLOW US:</span>
            <div class="social-icons-footer">
                <img src="images/facebook-svgrepo-com.svg" alt="Facebook">
                <img src="images/instagram-svgrepo-com.svg" alt="Instagram">
                <img src="images/icons8-twitter-50.png" alt="Twitter">
            </div>
        </div>
    </footer>

    <script>
        const passwordInput = document.getElementById("password");
        const togglePassword = document.getElementById("togglePassword");
        const signUpBtn = document.getElementById("signUpBtn");
        const welcomeSection = document.querySelector(".welcome-section");
        const welcomeContent = document.getElementById("welcomeContent");
        const traderForm = document.getElementById("trader-form");
        const customerForm = document.getElementById("customer-form");
        const signupSection = document.querySelector(".signup-section");

        document.querySelectorAll('.toggle-password').forEach(toggle => {
        togglePassword.addEventListener("click", function () {
            const isPassword = passwordInput.type === "password";
            passwordInput.type = isPassword ? "text" : "password";
             this.src = isPassword ? "images/view.png" : "images/hide.png";
        });
    });
        function showSignup() {
            welcomeContent.classList.add("fade-out");
            setTimeout(() => {
                welcomeContent.innerHTML = `
                    <div class="text-center">
                        <h1 class="text-4xl text-gray-800 mb-4">NAMASTE!</h1>
                        <p class="text-gray-700 mb-6">We're excited to have you join us—sign up now and start exploring!</p>
                        <div>
                            <span class="text-gray-700 mr-2"><em><b>Already have an account?<b></em></span>
                            <button class="login-btn-alt" id="backToLogin">Log In</button>
                        </div>
                    </div>
                `;
                welcomeContent.classList.remove("fade-out");
                const selectedRole = document.querySelector('input[name="role"]:checked')?.value || 'trader';
                traderForm.classList.toggle('active', selectedRole === 'trader');
                customerForm.classList.toggle('active', selectedRole === 'customer');
                const backToLogin = document.getElementById("backToLogin");
                backToLogin.addEventListener("click", showLogin);
            }, 300);
            welcomeSection.classList.add("slid");
        }

        function showLogin() {
            welcomeContent.classList.add("fade-out");
            setTimeout(() => {
                welcomeContent.innerHTML = `
                    <h1>WELCOME BACK!</h1>
                    <p>We want you to join us to stay connected with the family.</p>
                    <p><em><b>New to CoolCarters?</b></em><button class="signup-btn" id="signUpBtn">Sign Up</button></p>
                `;
                welcomeContent.classList.remove("fade-out");
                traderForm.classList.remove("active");
                customerForm.classList.remove("active");
                const newSignUpBtn = document.getElementById("signUpBtn");
                newSignUpBtn.addEventListener("click", showSignup);
            }, 300);
            welcomeSection.classList.remove("slid");
        }

        signUpBtn.addEventListener("click", showSignup);

        function switchForm(event) {
            const selectedRole = event.target.value;
            traderForm.classList.toggle('active', selectedRole === 'trader');
            customerForm.classList.toggle('active', selectedRole === 'customer');
            document.querySelectorAll('input[name="role"][value="trader"]').forEach(radio => radio.checked = selectedRole === 'trader');
            document.querySelectorAll('input[name="role"][value="customer"]').forEach(radio => radio.checked = selectedRole === 'customer');
        }

        signupSection.addEventListener('change', function(event) {
            if (event.target.name === 'role') {
                switchForm(event);
            }
        });
    </script>
</body>
</html>
