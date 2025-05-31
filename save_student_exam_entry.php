<?php
session_start();
header('Content-Type: application/json');
$response = ['success' => false, 'message' => ''];

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'student') {
    $response['message'] = "Yetkisiz erişim.";
    http_response_code(401);
    echo json_encode($response);
    exit();
}
$student_id = intval($_SESSION['user_id']);


$task_id = isset($_POST['task_id']) ? intval($_POST['task_id']) : 0;
$exam_name = isset($_POST['exam_name']) ? trim($_POST['exam_name']) : null;
$correct_count = isset($_POST['correct_count']) ? intval($_POST['correct_count']) : null;
$wrong_count = isset($_POST['wrong_count']) ? intval($_POST['wrong_count']) : null;
$blank_count = isset($_POST['blank_count']) ? intval($_POST['blank_count']) : null;

if ($task_id <= 0 || is_null($correct_count) || is_null($wrong_count) || is_null($blank_count)) {
    $response['message'] = "Eksik veri: Görev ID, Doğru, Yanlış ve Boş sayıları zorunludur.";
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


$stmt_check = $conn->prepare("SELECT t.task_type FROM tasks t JOIN student_goal_assignments sga ON t.goal_id = sga.goal_id WHERE t.id = ? AND sga.student_id = ?");
$stmt_check->bind_param("ii", $task_id, $student_id);
$stmt_check->execute();
$result_check = $stmt_check->get_result();
if ($result_check->num_rows > 0) {
    $task_row = $result_check->fetch_assoc();
    if ($task_row['task_type'] != 'exam_entry') {
        $response['message'] = "Bu görev türü için sonuç girilemez.";
        http_response_code(403);
        echo json_encode($response);
        $stmt_check->close(); $conn->close(); exit();
    }
} else {
    $response['message'] = "Bu göreve erişim yetkiniz yok.";
    http_response_code(403);
    echo json_encode($response);
    $stmt_check->close(); $conn->close(); exit();
}
$stmt_check->close();


$net_score = $correct_count - ($wrong_count / 4.0);


$sql_upsert = "INSERT INTO student_exam_entries (student_id, task_id, exam_name, correct_count, wrong_count, blank_count, net_score, entry_date)
               VALUES (?, ?, ?, ?, ?, ?, ?, NOW())
               ON DUPLICATE KEY UPDATE
               exam_name = VALUES(exam_name),
               correct_count = VALUES(correct_count),
               wrong_count = VALUES(wrong_count),
               blank_count = VALUES(blank_count),
               net_score = VALUES(net_score),
               entry_date = NOW()";

$stmt_upsert = $conn->prepare($sql_upsert);

$stmt_upsert->bind_param("iisiidd", $student_id, $task_id, $exam_name, $correct_count, $wrong_count, $blank_count, $net_score);

if ($stmt_upsert->execute()) {
    $response['success'] = true;
    $response['message'] = 'Sınav sonucu başarıyla kaydedildi/güncellendi.';

} else {
    $response['message'] = 'Veritabanı hatası: Sonuç kaydedilemedi. ' . $stmt_upsert->error;
    error_log("Save Exam Entry Error: " . $stmt_upsert->error);
    http_response_code(500);
}

$stmt_upsert->close();
$conn->close();
echo json_encode($response);
?>