// Wait for the entire HTML document to be loaded
document.addEventListener('DOMContentLoaded', () => {

    // --- Slide Form Animation (on User Login Page) ---
    const signup = document.querySelector(".signup");
    const login = document.querySelector(".login");
    const slider = document.querySelector(".slider");
    const formSection = document.querySelector(".form-section");

    if (signup && login && slider && formSection) {
        // When user clicks "Signup", move the slider to signup form
        signup.addEventListener("click", () => {
            slider.classList.add("moveslider");
            formSection.classList.add("form-section-move");
        });

        // When user clicks "Login", move the slider back to login form
        login.addEventListener("click", () => {
            slider.classList.remove("moveslider");
            formSection.classList.remove("form-section-move");
        });
    }

    // --- User Signup (on index.html) ---
    const signupBtn = document.querySelector('.signup-box .clkbtn');
    if (signupBtn) {
        signupBtn.addEventListener('click', () => {
            // Get signup form values
            const name = document.querySelector('.signup-box .name').value;
            const email = document.querySelector('.signup-box .email').value;
            const password = document.querySelector('.signup-box .password').value;
            const confirm = document.querySelector('.signup-box .confirm').value;

            // Validate password match
            if (password !== confirm) {
                alert("Passwords do not match!");
                return;
            }

            // Prepare form data to send
            const formData = new FormData();
            formData.append('name', name);
            formData.append('email', email);
            formData.append('password', password);

            // Send signup data to server
            fetch('signup.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                alert(data);
                if (data.includes("Signup successful")) {
                    // If signup is successful, redirect to home page
                    window.location.href = "home.html";
                }
            })
            .catch(error => {
                alert('Signup Error: ' + error);
            });
        });
    }

    // --- User Login (on index.html) ---
    const loginBtn = document.querySelector('.login-box .clkbtn');
    if (loginBtn && !document.querySelector('.admin-login')) {
        loginBtn.addEventListener('click', () => {
            // Get login form values
            const email = document.querySelector('.login-box .email').value;
            const password = document.querySelector('.login-box .password').value;

            // Prepare form data to send
            const formData = new FormData();
            formData.append('email', email);
            formData.append('password', password);

            // Send login data to server
            fetch('login.php', {
                method: 'POST',
                body: formData
            })
            .then(res => res.text())
            .then(data => {
                if (data.includes("Giriş başarılı!")) {
                    alert("Giriş başarılı!"); // Login successful message
                    window.location.href = "user-homepage.html"; // Redirect to home
                } else {
                    alert(data); // Invalid credentials
                }
            })
            .catch(error => {
                alert("Login Error: " + error);
            });
        });
    }

    // --- Admin Login (on admin-login.html) ---
    const adminLoginBtn = document.querySelector('.admin-login .clkbtn');
    if (adminLoginBtn) {
        adminLoginBtn.addEventListener('click', () => {
            // Get admin form values
            const email = document.querySelector('.admin-login .email').value;
            const password = document.querySelector('.admin-login .password').value;

            // Prepare form data to send
            const formData = new FormData();
            formData.append('email', email);
            formData.append('password', password);

            // Send admin login data to server
            fetch('admin-login.php', {
                method: 'POST',
                body: formData
            })
            .then(res => res.text())
            .then(data => {
                if (data.includes("Yönetici girişi başarılı !!!")) {
                    alert("Admin giriş başarılı!!");
                    window.location.href = "admin-panel.html"; // Redirect to admin panel
                } else {
                    alert(data); // Invalid admin credentials
                }
            })
            .catch(error => {
                alert("Admin Login Error: " + error);
            });
        });
    }

    // --- Redirect to Admin Login Page (from index.html) ---
    const adminBtn = document.querySelector('.admin-btn');
    if (adminBtn) {
        adminBtn.addEventListener('click', () => {
            window.location.href = "admin-login.html"; // Go to admin login page
        });
    }

    // --- Back to User Login Page (from admin-login.html) ---
    const backBtn = document.querySelector('.back-btn');
    if (backBtn) {
        backBtn.addEventListener('click', () => {
            window.location.href = "index.html"; // Return to normal login/signup
        });
    }

});
