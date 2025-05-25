<?php
header('Content-Type: application/json');

// Veritabanı bağlantısı
$conn = new mysqli("localhost", "root", "", "student_db");
if ($conn->connect_error) {
    die(json_encode(['error' => 'Veritabanı bağlantısında bir sorun oluştu.']));
}
$conn->set_charset("utf8mb4");

// Task ID'yi al
$task_id = isset($_GET['task_id']) ? intval($_GET['task_id']) : 0;

if ($task_id <= 0) {
    die(json_encode(['error' => 'Geçersiz görev ID\'si.']));
}

// Görev detaylarını getir
$stmt = $conn->prepare("SELECT title, subject, question_count, topics FROM tasks WHERE id = ? AND task_type = 'exam_entry'");
$stmt->bind_param("i", $task_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die(json_encode(['error' => 'Görev bulunamadı veya bu bir sınav görevi değil.']));
}

$task = $result->fetch_assoc();
$stmt->close();
$conn->close();

echo json_encode($task);
?> 