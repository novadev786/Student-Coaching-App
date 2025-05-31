<?php
session_start(); 

header('Content-Type: application/json'); 
$response = ['success' => false, 'message' => ''];

try {
   
    $_SESSION = array();

   
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }

   
    if (session_destroy()) {
        $response['success'] = true;
        $response['message'] = 'Başarıyla çıkış yapıldı.';
    } else {
        $response['message'] = 'Oturum sonlandırılamadı.';
        
    }

} catch (Exception $e) {
    $response['message'] = 'Çıkış işlemi sırasında bir hata oluştu: ' . $e->getMessage();
    http_response_code(500); 
    error_log("Logout Exception: " . $e->getMessage()); 
}

echo json_encode($response);
exit(); 
?>