<?php
session_start();
header('Content-Type: application/json');
$response = ['success' => false, 'entry' => null, 'message' => ''];

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'student') {
    $response['message'] = "Yetkisiz erişim.";
    http_response_code(401);
    echo json_encode($response);
    exit();
}
$student_id = intval($_SESSION['user_id']);
$task_id = isset($_GET['task_id']) ? intval($_GET['task_id']) : 0;

if ($task_id <= 0) {
    $response['message'] = "Geçersiz görev ID.";
    http_response_code(400);
    echo json_encode($response);
    exit();
}

$conn = new mysqli("localhost", "root", "", "student_db");
if ($conn->connect_error) {
    $response['message'] = "Veritabanı hatası.";
    http_response_code(500);
    echo json_encode($response);
    exit();
}
$conn->set_charset("utf8mb4");

$stmt = $conn->prepare("SELECT exam_name, correct_count, wrong_count, blank_count FROM student_exam_entries WHERE student_id = ? AND task_id = ?");
$stmt->bind_param("ii", $student_id, $task_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $response['success'] = true;
    $response['entry'] = $result->fetch_assoc();
} else {
    // Kayıt yoksa hata değil, sadece boş entry dönsün
    $response['success'] = true; // İşlem başarılı ama data yok
    $response['message'] = "Daha önce girilmiş sonuç bulunamadı.";
}

$stmt->close();
$conn->close();
echo json_encode($response);
?>