<?php
session_start();

// Form processing logic
function test_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

$email = isset($_SESSION['email']) ? $_SESSION['email'] : '';
$role = isset($_SESSION['role']) ? $_SESSION['role'] : '';
$emailErr = isset($_SESSION['emailErr']) ? $_SESSION['emailErr'] : '';
$passwordErr = isset($_SESSION['passwordErr']) ? $_SESSION['passwordErr'] : '';
$roleErr = isset($_SESSION['roleErr']) ? $_SESSION['roleErr'] : '';
$successMsg = isset($_SESSION['successMsg']) ? $_SESSION['successMsg'] : '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $emailErr = $passwordErr = $roleErr = "";
    $email = $password = $role = "";
    $successMsg = "";

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

    // Store variables in session
    $_SESSION['email'] = $email;
    $_SESSION['role'] = $role;
    $_SESSION['emailErr'] = $emailErr;
    $_SESSION['passwordErr'] = $passwordErr;
    $_SESSION['roleErr'] = $roleErr;
    $_SESSION['successMsg'] = $successMsg;
}

// Clear session variables after processing
unset($_SESSION['email'], $_SESSION['role'], $_SESSION['emailErr'], $_SESSION['passwordErr'], $_SESSION['roleErr'], $_SESSION['successMsg']);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CoolCarters</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./css/login.css">
</head>

<body>
    <?php
    include "homeNavbar.php";
    ?>
    <div class="main-content">
        <section class="login-form">
            <h2>Log In</h2>
            <?php if (!empty($successMsg)): ?>
                <div class="success"><?php echo htmlspecialchars($successMsg); ?></div>
            <?php endif; ?>

            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <div class="form-group">
                    <input type="email" id="email" name="email" placeholder="Email" value="<?php echo htmlspecialchars($email); ?>" required>
                    <span class="error"><?php echo htmlspecialchars($emailErr); ?></span>
                </div>
                <div class="form-group" style="position: relative;">
                    <input type="password" id="password" name="password" placeholder="Password" required>
                    <img src="images/hide.png" alt="Toggle Password" class="toggle-password"
                        data-input="password" style="position: absolute; top: 10px; right: 10px; width: 20px; height: 20px; cursor: pointer;">
                    <span class="error"><?php echo htmlspecialchars($passwordErr); ?></span>
                </div>

                <div class="form-group">
                    <select id="role" name="role">
                        <option value="" disabled <?php if (empty($role)) echo 'selected'; ?>>Select Role</option>
                        <option value="trader" <?php if ($role == 'trader') echo 'selected'; ?>>Trader</option>
                        <option value="customer" <?php if ($role == 'customer') echo 'selected'; ?>>Customer</option>
                    </select>
                    <span class="error"><?php echo htmlspecialchars($roleErr); ?></span>
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
                <form method="POST" action="signup_process.php">
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
                <form method="POST" action="signup_process.php">
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
                    </div><br>
                    <div class="form-group" style="position: relative;">
                        <input type="password" id="customer-password" name="password" placeholder="Password" required>
                        <img src="images/hide.png" alt="Toggle Password" class="toggle-password"
                            data-input="customer-password" style="position: absolute; top: 10px; right: 10px; width: 20px; height: 20px; cursor: pointer;">
                    </div>
                    <div class="form-group" style="position: relative;">
                        <input type="password" id="customer-confirmPassword" name="confirmPassword" placeholder="Confirm Password" required>
                        <img src="images/hide.png" alt="Toggle Password" class="toggle-password"
                            data-input="customer-confirmPassword" style="position: absolute; top: 10px; right: 10px; width: 20px; height: 20px; cursor: pointer;">
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

    <div class="mobile-interface">
        <div class="mobile-logo-container">
            <img src="images/CoolCarters Sample 2.svg" alt="CoolCarters Logo" class="mobile-logo-img">
            <div class="mobile-logo-text">CoolCarters</div>
        </div>
        <div class="mobile-button-container">
            <button class="mobile-option-btn" id="mobileLoginBtn">Log In</button>
            <button class="mobile-option-btn" id="mobileSignupBtn">Sign Up</button>
        </div>
        <div class="mobile-form-container" id="mobileLoginForm">
            <section class="login-form">
                <h2>Log In</h2>

                <?php if (!empty($successMsg)): ?>
                    <div class="success"><?php echo htmlspecialchars($successMsg); ?></div>
                <?php endif; ?>

                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                    <div class="form-group">
                        <input type="email" id="mobile-email" name="email" placeholder="Email" value="<?php echo htmlspecialchars($email); ?>" required>
                        <span class="error"><?php echo htmlspecialchars($emailErr); ?></span>
                    </div>
                    <div class="form-group" style="position: relative;">
                        <input type="password" id="mobile-password" name="password" placeholder="Password" required>
                        <img src="images/hide.png" alt="Toggle Password" class="toggle-password"
                            data-input="mobile-password" style="position: absolute; top: 10px; right: 10px; width: 20px; height: 20px; cursor: pointer;">
                        <span class="error"><?php echo htmlspecialchars($passwordErr); ?></span>
                    </div>

                    <div class="form-group">
                        <select id="mobile-role" name="role">
                            <option value="" disabled <?php if (empty($role)) echo 'selected'; ?>>Select Role</option>
                            <option value="trader" <?php if ($role == 'trader') echo 'selected'; ?>>Trader</option>
                            <option value="customer" <?php if ($role == 'customer') echo 'selected'; ?>>Customer</option>
                        </select>
                        <span class="error"><?php echo htmlspecialchars($roleErr); ?></span>
                    </div>

                    <button type="submit" class="login-btn">Log In</button>
                    <div class="terms">
                        <label for="mobile-terms">I accept the terms and conditions:</label>
                        <input type="checkbox" id="mobile-terms" name="terms" required>
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
        </div>
        <div class="mobile-form-container" id="mobileSignupForm">
            <section class="signup-section">
                <div id="mobile-trader-form" class="signup-container active">
                    <h2>Sign up</h2>
                    <form>
                        <div class="form-group role-group">
                            <label>Role:</label>
                            <input type="radio" id="mobile-trader-role" name="role" value="trader" checked>
                            <label for="mobile-trader-role">Trader</label>
                            <input type="radio" id="mobile-customer-role" name="role" value="customer">
                            <label for="mobile-customer-role">Customer</label>
                        </div>
                        <div class="form-group">
                            <input type="text" id="mobile-trader-firstName" name="firstName" placeholder="First Name" required>
                        </div>
                        <div class="form-group">
                            <input type="text" id="mobile-trader-lastName" name="lastName" placeholder="Last Name" required>
                        </div>
                        <div class="form-group">
                            <input type="email" id="mobile-trader-email" name="email" placeholder="Email" required>
                        </div>
                        <div class="form-group" style="position: relative;">
                            <input type="password" id="mobile-trader-password" name="password" placeholder="Password" required>
                            <img src="images/hide.png" alt="Toggle Password" class="toggle-password"
                                data-input="mobile-trader-password" style="position: absolute; top: 10px; right: 10px; width: 20px; height: 20px; cursor: pointer;">
                        </div>
                        <div class="form-group" style="position: relative;">
                            <input type="password" id="mobile-trader-confirmPassword" name="confirmPassword" placeholder="Confirm Password" required>
                            <img src="images/hide.png" alt="Toggle Password" class="toggle-password"
                                data-input="mobile-trader-confirmPassword" style="position: absolute; top: 10px; right: 10px; width: 20px; height: 20px; cursor: pointer;">
                        </div>
                        <div class="form-group">
                            <input type="text" id="mobile-companyName" name="companyName" placeholder="Company name" required>
                        </div>
                        <button type="submit" class="next-btn">Next</button>
                    </form>
                </div>

                <div id="mobile-customer-form" class="signup-container">
                    <h2>Sign up</h2>
                    <form>
                        <div class="form-group role-group">
                            <label>Role:</label>
                            <input type="radio" id="mobile-trader-role-customer" name="role" value="trader">
                            <label for="mobile-trader-role-customer">Trader</label>
                            <input type="radio" id="mobile-customer-role-customer" name="role" value="customer" checked>
                            <label for="mobile-customer-role-customer">Customer</label>
                        </div>
                        <div class="form-group">
                            <input type="text" id="mobile-customer-firstName" name="firstName" placeholder="First Name" required>
                        </div>
                        <div class="form-group">
                            <input type="text" id="mobile-customer-lastName" name="lastName" placeholder="Last Name" required>
                        </div>
                        <div class="form-group">
                            <input type="email" id="mobile-customer-email" name="email" placeholder="Email" required>
                        </div>
                        <div class="form-group gender-group">
                            <label>Gender:</label>
                            <input type="radio" id="mobile-female" name="gender" value="female">
                            <label for="mobile-female">Female</label>
                            <input type="radio" id="mobile-male" name="gender" value="male">
                            <label for="mobile-male">Male</label>
                        </div>
                        <div class="form-group" style="position: relative;">
                            <input type="password" id="mobile-customer-password" name="password" placeholder="Password" required>
                            <img src="images/hide.png" alt="Toggle Password" class="toggle-password"
                                data-input="mobile-customer-password" style="position: absolute; top: 10px; right: 10px; width: 20px; height: 20px; cursor: pointer;">
                        </div>
                        <div class="form-group" style="position: relative;">
                            <input type="password" id="mobile-customer-confirmPassword" name="confirmPassword" placeholder="Confirm Password" required>
                            <img src="images/hide.png" alt="Toggle Password" class="toggle-password"
                                data-input="mobile-customer-confirmPassword" style="position: absolute; top: 10px; right: 10px; width: 20px; height: 20px; cursor: pointer;">
                        </div>
                        <button type="submit" class="next-btn">Next</button>
                    </form>
                </div>
            </section>
        </div>
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

    <script src="./js/login.js"></script>
</body>

</html>