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

    // --- User Login (on index.html) ---
// Yorum işaretleri kaldırıldı
// --- User Login (on index.html) ---
const loginBtn = document.querySelector('.login-box .clkbtn'); // Giriş yap butonunu seç

// Buton bulunduysa ve burası admin login sayfası değilse dinleyici ekle
if (loginBtn && !document.querySelector('.admin-login')) {
    loginBtn.addEventListener('click', () => { // Butona tıklanınca çalışacak fonksiyon

        // Input alanlarını class isimleriyle bul
        const emailInput = document.querySelector('.login-box .email');
        const passwordInput = document.querySelector('.login-box .password');

        // --- Hata Ayıklama 1: Inputlar bulundu mu? ---
        if (!emailInput || !passwordInput) {
            console.error("Hata: Email veya şifre input alanı HTML'de bulunamadı! Class isimlerini kontrol et.");
            alert("Form elemanları bulunamadı! Lütfen sayfayı yenileyin veya geliştiriciye bildirin.");
            return; // Hata varsa işlemi durdur
        }
        // --- Bitiş: Hata Ayıklama 1 ---

        // Input alanlarından değerleri al
        const email = emailInput.value;
        const password = passwordInput.value;

        // --- Hata Ayıklama 2: Değerler alındı mı? ---
        console.log("Alınan Email:", email); // Tarayıcı konsoluna yazdır
        console.log("Alınan Şifre:", password); // Tarayıcı konsoluna yazdır

        // Değerler boş mu diye kontrol et
        if (!email || !password) {
             alert("Lütfen e-posta ve şifre alanlarını doldurun!");
             return; // Boşsa işlemi durdur
        }
        // --- Bitiş: Hata Ayıklama 2 ---

        // Gönderilecek veriyi hazırla (FormData kullanarak)
        const formData = new FormData();
        formData.append('email', email); // 'email' adıyla ekle
        formData.append('password', password); // 'password' adıyla ekle

        console.log("FormData içeriği gönderilmeden önce:", formData); // Konsola FormData'yı yazdır (genelde boş görünür ama obje vardır)

        // Veriyi sunucuya (login.php) gönder
        fetch('login.php', {
            method: 'POST', // POST metoduyla
            body: formData  // Hazırlanan veriyi body'ye ekle
        })
        .then(res => { // Sunucudan yanıt gelince
            // Önce yanıtın başarılı olup olmadığını kontrol et (örn: 404, 500 hataları)
             if (!res.ok) {
                 // HTTP hata kodu varsa, hatayı fırlat ki catch bloğu yakalasın
                 throw new Error(`HTTP error! status: ${res.status} - ${res.statusText}`);
             }
             // Yanıt başarılıysa, metin olarak oku (login.php JSON döndürecek ama önce text alalım)
            return res.text();
        })
        .then(data => { // Metin yanıtı işleme
            console.log("Sunucudan gelen ham yanıt (data):", data); // Ham yanıtı konsola yazdır
            try {
                // Gelen metni JSON olarak ayrıştırmayı dene
                const response = JSON.parse(data);
                console.log("JSON olarak ayrıştırılmış yanıt (response):", response); // Ayrıştırılmış yanıtı konsola yaz

                if (response.success) { // PHP'den gelen yanıtta success true ise
                    // Başarılı ise PHP'den gelen yönlendirme URL'sine git
                    console.log("Giriş başarılı, yönlendiriliyor:", response.redirectUrl);
                    window.location.href = response.redirectUrl;
                } else {
                    // Başarısız ise PHP'den gelen hata mesajını göster
                    console.warn("Giriş başarısız, mesaj:", response.message);
                    alert(response.message || "Giriş başarısız!");
                }
            } catch (e) {
                // JSON ayrıştırma hatası olursa (PHP tarafında JSON olmayan bir çıktı varsa)
                console.error("Login response JSON parse error:", e);
                console.error("JSON parse edilemeyen ham veri:", data); // Ham veriyi tekrar yazdır
                alert("Giriş sırasında sunucudan beklenmeyen bir yanıt alındı. Lütfen konsolu kontrol edin.");
            }
        })
        .catch(error => { // fetch sırasında veya .then içinde hata olursa burası çalışır
            console.error("Login Fetch/Network Error:", error);
            alert("Giriş sırasında bir ağ hatası veya sunucu hatası oluştu: " + error.message);
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
