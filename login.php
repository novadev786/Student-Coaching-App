<?php
session_start();


header('Content-Type: application/json');


$response = ['success' => false, 'message' => '', 'redirectUrl' => ''];

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "student_db";


$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    $response['message'] = "Veritabanı bağlantı hatası.";
    
    http_response_code(500); 
    error_log("Login DB Connect Error: " . $conn->connect_error); 
    echo json_encode($response);
    exit(); 
}
$conn->set_charset("utf8mb4"); 


if (!isset($_POST['email']) || empty(trim($_POST['email'])) || !isset($_POST['password']) || empty($_POST['password'])) {
     $response['message'] = "E-posta ve şifre alanları zorunludur.";
     http_response_code(400); 
     echo json_encode($response);
     exit();
}
$email = trim($_POST['email']);
$pass = $_POST['password']; 




$sql = "SELECT id, name, role FROM users WHERE email=? AND password=?";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    $response['message'] = "Sorgu hazırlama hatası.";
    http_response_code(500);
    error_log("Login Prepare Error: " . $conn->error);
    echo json_encode($response);
    $conn->close(); 
    exit();
}

$stmt->bind_param("ss", $email, $pass);
if (!$stmt->execute()) {
    $response['message'] = "Sorgu çalıştırma hatası.";
    http_response_code(500);
    error_log("Login Execute Error: " . $stmt->error);
    $stmt->close(); 
    $conn->close(); 
    exit();
}

$result = $stmt->get_result();


if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();

    $_SESSION['user_id'] = $row['id'];
    $_SESSION['role'] = $row['role'];
    $_SESSION['user_name'] = $row['name']; 

    
    $response['success'] = true;
    $response['message'] = 'Giriş başarılı!';

    
    if ($row['role'] == 'admin') {
        $response['redirectUrl'] = 'admin-panel.html'; 
    } elseif ($row['role'] == 'teacher') {
        $response['redirectUrl'] = 'teacher-home.php';
    } else { 
        $response['redirectUrl'] = 'student-home.php'; 
    }

} else {
   
    $response['message'] = "Giriş başarısız! Lütfen e-posta ve şifrenizi kontrol edin.";
   
}

$stmt->close();
$conn->close();


echo json_encode($response);
?>