
document.addEventListener('DOMContentLoaded', () => {

    
    const signup = document.querySelector(".signup");
    const login = document.querySelector(".login");
    const slider = document.querySelector(".slider");
    const formSection = document.querySelector(".form-section");

    if (signup && login && slider && formSection) {
       
        signup.addEventListener("click", () => {
            slider.classList.add("moveslider");
            formSection.classList.add("form-section-move");
        });

        
        login.addEventListener("click", () => {
            slider.classList.remove("moveslider");
            formSection.classList.remove("form-section-move");
        });
    }

    
    const signupBtn = document.querySelector('.signup-box .clkbtn');
    if (signupBtn) {
        signupBtn.addEventListener('click', () => {
            
            const name = document.querySelector('.signup-box .name').value;
            const email = document.querySelector('.signup-box .email').value;
            const password = document.querySelector('.signup-box .password').value;
            const confirm = document.querySelector('.signup-box .confirm').value;

            
            if (password !== confirm) {
                alert("Passwords do not match!");
                return;
            }

            
            const formData = new FormData();
            formData.append('name', name);
            formData.append('email', email);
            formData.append('password', password);

            
            fetch('signup.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                alert(data);
                if (data.includes("Signup successful")) {
                    
                    window.location.href = "home.html";
                }
            })
            .catch(error => {
                alert('Signup Error: ' + error);
            });
        });
    }


const loginBtn = document.querySelector('.login-box .clkbtn'); 


if (loginBtn && !document.querySelector('.admin-login')) {
    loginBtn.addEventListener('click', () => { 

        
        const emailInput = document.querySelector('.login-box .email');
        const passwordInput = document.querySelector('.login-box .password');

        
        if (!emailInput || !passwordInput) {
            console.error("Hata: Email veya şifre input alanı HTML'de bulunamadı! Class isimlerini kontrol et.");
            alert("Form elemanları bulunamadı! Lütfen sayfayı yenileyin veya geliştiriciye bildirin.");
            return;
        }
       

        
        const email = emailInput.value;
        const password = passwordInput.value;

        
        console.log("Alınan Email:", email); 
        console.log("Alınan Şifre:", password); 

        
        if (!email || !password) {
             alert("Lütfen e-posta ve şifre alanlarını doldurun!");
             return; 
        }
       

       
        const formData = new FormData();
        formData.append('email', email); 
        formData.append('password', password); 

        console.log("FormData içeriği gönderilmeden önce:", formData); 

        
        fetch('login.php', {
            method: 'POST', 
            body: formData 
        })
        .then(res => { 
           
             if (!res.ok) {
                 
                 throw new Error(`HTTP error! status: ${res.status} - ${res.statusText}`);
             }
             
            return res.text();
        })
        .then(data => { 
            console.log("Sunucudan gelen ham yanıt (data):", data); 
            try {
                
                const response = JSON.parse(data);
                console.log("JSON olarak ayrıştırılmış yanıt (response):", response);

                if (response.success) { 
                    
                    console.log("Giriş başarılı, yönlendiriliyor:", response.redirectUrl);
                    window.location.href = response.redirectUrl;
                } else {
                   
                    console.warn("Giriş başarısız, mesaj:", response.message);
                    alert(response.message || "Giriş başarısız!");
                }
            } catch (e) {
                
                console.error("Login response JSON parse error:", e);
                console.error("JSON parse edilemeyen ham veri:", data); 
                alert("Giriş sırasında sunucudan beklenmeyen bir yanıt alındı. Lütfen konsolu kontrol edin.");
            }
        })
        .catch(error => { 
            console.error("Login Fetch/Network Error:", error);
            alert("Giriş sırasında bir ağ hatası veya sunucu hatası oluştu: " + error.message);
        });
    });
}
   

    
    const adminLoginBtn = document.querySelector('.admin-login .clkbtn');
    if (adminLoginBtn) {
        adminLoginBtn.addEventListener('click', () => {
           
            const email = document.querySelector('.admin-login .email').value;
            const password = document.querySelector('.admin-login .password').value;

            
            const formData = new FormData();
            formData.append('email', email);
            formData.append('password', password);

           
            fetch('admin-login.php', {
                method: 'POST',
                body: formData
            })
            .then(res => res.text())
            .then(data => {
                if (data.includes("Yönetici girişi başarılı !!!")) {
                    alert("Admin giriş başarılı!!");
                    window.location.href = "admin-panel.html"; 
                } else {
                    alert(data); 
                }
            })
            .catch(error => {
                alert("Admin Login Error: " + error);
            });
        });
    }

   
    const adminBtn = document.querySelector('.admin-btn');
    if (adminBtn) {
        adminBtn.addEventListener('click', () => {
            window.location.href = "admin-login.html"; 
        });
    }

    
    const backBtn = document.querySelector('.back-btn');
    if (backBtn) {
        backBtn.addEventListener('click', () => {
            window.location.href = "index.html"; 
        });
    }

});
