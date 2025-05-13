<?php
session_start(); // Oturum başlat

// Yanıt türünü JSON olarak ayarla (AJAX isteği için)
header('Content-Type: application/json');

// Başlangıç yanıt objesi
$response = ['success' => false, 'message' => '', 'redirectUrl' => ''];

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "student_db";

// Veritabanı bağlantısı
$conn = new mysqli($servername, $username, $password, $dbname);

// Bağlantı hatası kontrolü
if ($conn->connect_error) {
    $response['message'] = "Veritabanı bağlantı hatası.";
    // Hata durumunda HTTP durum kodu ayarlamak iyi bir pratiktir
    http_response_code(500); // Internal Server Error
    error_log("Login DB Connect Error: " . $conn->connect_error); // Sunucu loguna yaz
    echo json_encode($response);
    exit(); // Script'i burada sonlandır
}
$conn->set_charset("utf8mb4"); // JSON ve Türkçe karakterler için önemli

// Formdan gelen e-posta ve şifreyi al ve kontrol et
if (!isset($_POST['email']) || empty(trim($_POST['email'])) || !isset($_POST['password']) || empty($_POST['password'])) {
     $response['message'] = "E-posta ve şifre alanları zorunludur.";
     http_response_code(400); // Bad Request
     echo json_encode($response);
     exit();
}
$email = trim($_POST['email']);
$pass = $_POST['password']; // Şifreyi trim etmeyelim, boşluk önemli olabilir (ama idealde hash kullanılmalı)


// --- GÜVENLİK UYARISI: ŞİFRE DOĞRULAMASI ---
// Gerçek bir uygulamada şifreler veritabanında ASLA düz metin olarak saklanmamalıdır.
// Kayıt sırasında password_hash() ile hashlenmeli, giriş sırasında password_verify() ile kontrol edilmelidir.
// Bu örnekte, mevcut yapıya uyum için düz metin karşılaştırması yapılıyor varsayılmıştır.
// Güvenli yöntem:
// $sql = "SELECT id, name, role, password FROM users WHERE email=?";
// $stmt = $conn->prepare($sql);
// $stmt->bind_param("s", $email);
// $stmt->execute();
// $result = $stmt->get_result();
// if ($result->num_rows > 0) {
//     $row = $result->fetch_assoc();
//     if (password_verify($pass, $row['password'])) {
//          // Şifre doğru, işleme devam et...
//     } else {
//          // Şifre yanlış...
//     }
// } else {
//      // Kullanıcı bulunamadı...
// }
// --- GÜVENLİK UYARISI SONU ---


// Kullanıcıyı veritabanında ara (Mevcut güvensiz yöntemle devam ediyoruz)
$sql = "SELECT id, name, role FROM users WHERE email=? AND password=?";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    $response['message'] = "Sorgu hazırlama hatası.";
    http_response_code(500);
    error_log("Login Prepare Error: " . $conn->error);
    echo json_encode($response);
    $conn->close(); // Bağlantıyı kapatmayı unutma
    exit();
}

$stmt->bind_param("ss", $email, $pass);
if (!$stmt->execute()) {
    $response['message'] = "Sorgu çalıştırma hatası.";
    http_response_code(500);
    error_log("Login Execute Error: " . $stmt->error);
    $stmt->close(); // Statement'ı kapat
    $conn->close(); // Bağlantıyı kapat
    exit();
}

$result = $stmt->get_result();

// Kullanıcı varsa, rolünü kontrol et ve oturum başlat
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();

    // Oturum değişkenlerini ayarla
    $_SESSION['user_id'] = $row['id'];
    $_SESSION['role'] = $row['role'];
    $_SESSION['user_name'] = $row['name']; // Kullanıcı adını da oturuma ekleyelim

    // Yanıtı hazırla
    $response['success'] = true;
    $response['message'] = 'Giriş başarılı!';

    // Kullanıcının rolüne göre yönlendirme URL'sini belirle
    if ($row['role'] == 'admin') {
        $response['redirectUrl'] = 'admin-panel.html'; // Admin paneline yönlendir (veya admin-home.php)
    } elseif ($row['role'] == 'teacher') {
        $response['redirectUrl'] = 'teacher-home.php';
    } else { // Varsayılan olarak öğrenci
        $response['redirectUrl'] = 'student-home.php'; // VEYA user-homepage.html hangisini kullanıyorsan
    }

} else {
    // Kullanıcı bulunamadı veya şifre yanlış
    $response['message'] = "Giriş başarısız! Lütfen e-posta ve şifrenizi kontrol edin.";
    // Başarısız girişte 401 Unauthorized göndermek daha doğru olabilir
    // http_response_code(401);
}

$stmt->close();
$conn->close();

// Sonucu JSON olarak ekrana bas
echo json_encode($response);
?>