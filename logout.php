<?php
session_start(); // Oturumu başlat

header('Content-Type: application/json'); // Yanıt türünü JSON olarak ayarla
$response = ['success' => false, 'message' => ''];

try {
    // Tüm oturum değişkenlerini temizle
    $_SESSION = array();

    // Oturum çerezini sil
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }

    // Oturumu sonlandır
    if (session_destroy()) {
        $response['success'] = true;
        $response['message'] = 'Başarıyla çıkış yapıldı.';
    } else {
        $response['message'] = 'Oturum sonlandırılamadı.';
        // Hata durumunda uygun bir HTTP kodu gönderebiliriz,
        // ancak genellikle session_destroy nadiren başarısız olur.
    }

} catch (Exception $e) {
    $response['message'] = 'Çıkış işlemi sırasında bir hata oluştu: ' . $e->getMessage();
    http_response_code(500); // Sunucu hatası
    error_log("Logout Exception: " . $e->getMessage()); // Sunucu loguna yaz
}

echo json_encode($response);
exit(); // Scriptin burada bitmesini garanti et
?>