document.addEventListener("DOMContentLoaded", function () {
  const signUpBtn = document.getElementById("signUpBtn");
  const welcomeSection = document.querySelector(".welcome-section");
  const welcomeContent = document.getElementById("welcomeContent");
  const traderForm = document.getElementById("trader-form");
  const customerForm = document.getElementById("customer-form");
  const signupSection = document.querySelector(".signup-section");
  const mobileSignupSection = document.querySelector(
    "#mobileSignupForm .signup-section"
  );

  // Password toggle functionality for all password fields
  document.querySelectorAll(".toggle-password").forEach((toggle) => {
    toggle.addEventListener("click", function () {
      const inputId = this.getAttribute("data-input");
      const input = document.getElementById(inputId);
      const isPassword = input.type === "password";
      input.type = isPassword ? "text" : "password";
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
                                <span class="text-gray-700 mr-2"><em><b>Already have an account?</b></em></span>
                                <button class="login-btn-alt" id="backToLogin">Log In</button>
                            </div>
                        </div>
                    `;
      welcomeContent.classList.remove("fade-out");
      const selectedRole =
        document.querySelector('input[name="role"]:checked')?.value || "trader";
      traderForm.classList.toggle("active", selectedRole === "trader");
      customerForm.classList.toggle("active", selectedRole === "customer");
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
    console.log("Selected Role:", selectedRole); // Debug log
    // Desktop forms
    traderForm.classList.toggle("active", selectedRole === "trader");
    customerForm.classList.toggle("active", selectedRole === "customer");
    document
      .querySelectorAll('input[name="role"][value="trader"]')
      .forEach((radio) => (radio.checked = selectedRole === "trader"));
    document
      .querySelectorAll('input[name="role"][value="customer"]')
      .forEach((radio) => (radio.checked = selectedRole === "customer"));

    // Mobile forms
    const mobileTraderForm = document.getElementById("mobile-trader-form");
    const mobileCustomerForm = document.getElementById("mobile-customer-form");
    if (mobileTraderForm && mobileCustomerForm) {
      mobileTraderForm.classList.toggle("active", selectedRole === "trader");
      mobileCustomerForm.classList.toggle(
        "active",
        selectedRole === "customer"
      );
    }
  }

  // Event listener for desktop signup section
  signupSection.addEventListener("change", function (event) {
    if (event.target.name === "role") {
      switchForm(event);
    }
  });

  // Event listener for mobile signup section
  if (mobileSignupSection) {
    mobileSignupSection.addEventListener("change", function (event) {
      if (event.target.name === "role") {
        switchForm(event);
      }
    });
  }

  // Mobile Interface Script
  const mobileLoginBtn = document.getElementById("mobileLoginBtn");
  const mobileSignupBtn = document.getElementById("mobileSignupBtn");
  const mobileLoginForm = document.getElementById("mobileLoginForm");
  const mobileSignupForm = document.getElementById("mobileSignupForm");

  mobileLoginBtn.addEventListener("click", () => {
    mobileLoginForm.classList.add("active");
    mobileSignupForm.classList.remove("active");
  });

  mobileSignupBtn.addEventListener("click", () => {
    mobileSignupForm.classList.add("active");
    mobileLoginForm.classList.remove("active");
    const selectedRole =
      document.querySelector('#mobileSignupForm input[name="role"]:checked')
        ?.value || "trader";
    document
      .getElementById("mobile-trader-form")
      .classList.toggle("active", selectedRole === "trader");
    document
      .getElementById("mobile-customer-form")
      .classList.toggle("active", selectedRole === "customer");
  });
});
