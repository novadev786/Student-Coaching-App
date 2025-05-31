<?php
session_start();
header('Content-Type: application/json');

$response = ['success' => false, 'message' => ''];


if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] != 'student') {
    $response['message'] = "Yetkisiz erişim.";
    http_response_code(401);
    echo json_encode($response);
    exit();
}
$student_id = intval($_SESSION['user_id']);


$data = json_decode(file_get_contents("php://input"), true);

if (!$data || !isset($data['task_id']) || !isset($data['is_completed'])) {
    $response['message'] = "Eksik veya geçersiz veri.";
    http_response_code(400);
    echo json_encode($response);
    exit();
}

$task_id = intval($data['task_id']);
$is_completed = intval($data['is_completed']) === 1 ? 1 : 0;

if ($task_id <= 0) {
    $response['message'] = "Geçersiz görev IDsi.";
    http_response_code(400);
    echo json_encode($response);
    exit();
}


$conn = new mysqli("localhost", "root", "", "student_db");
if ($conn->connect_error) {
    $response['message'] = "Veritabanı hatası.";
    http_response_code(500);
    error_log("DB Connection Error (update_status): " . $conn->connect_error);
    echo json_encode($response);
    exit();
}
$conn->set_charset("utf8mb4");

$sql_check_access = "SELECT COUNT(*) as count
                     FROM tasks t
                     JOIN student_goal_assignments sga ON t.goal_id = sga.goal_id
                     WHERE t.id = ? AND sga.student_id = ?";
$stmt_check = $conn->prepare($sql_check_access);
$stmt_check->bind_param("ii", $task_id, $student_id);
$stmt_check->execute();
$result_check = $stmt_check->get_result();
$row_check = $result_check->fetch_assoc();
$stmt_check->close();

if ($row_check['count'] == 0) {
    $response['message'] = "Bu göreve erişim yetkiniz yok.";
    http_response_code(403); 
    $conn->close();
    echo json_encode($response);
    exit();
}


$sql_upsert = "INSERT INTO student_task_status (student_id, task_id, is_completed, completion_date)
               VALUES (?, ?, ?, ?)
               ON DUPLICATE KEY UPDATE
               is_completed = VALUES(is_completed),
               completion_date = VALUES(completion_date)";

$stmt_upsert = $conn->prepare($sql_upsert);


$completion_date = ($is_completed === 1) ? date("Y-m-d H:i:s") : null;


$stmt_upsert->bind_param("iiis", $student_id, $task_id, $is_completed, $completion_date);

if ($stmt_upsert->execute()) {
    $response['success'] = true;
    $response['message'] = 'Görev durumu başarıyla güncellendi.';
} else {
    $response['message'] = 'Veritabanı hatası: Durum güncellenemedi.';
    http_response_code(500);
    error_log("Update Task Status Error: " . $stmt_upsert->error);
}

$stmt_upsert->close();
$conn->close();

echo json_encode($response);
?>