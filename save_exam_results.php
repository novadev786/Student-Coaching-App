<?php
header('Content-Type: application/json');


$conn = new mysqli("localhost", "root", "", "student_db");
if ($conn->connect_error) {
    die(json_encode(['success' => false, 'message' => 'Veritabanı bağlantısında bir sorun oluştu.']));
}
$conn->set_charset("utf8mb4");


$data = json_decode(file_get_contents('php://input'), true);

if (!$data) {
    die(json_encode(['success' => false, 'message' => 'Geçersiz veri formatı.']));
}


$required_fields = ['task_id', 'correct_count', 'wrong_count', 'blank_count'];
foreach ($required_fields as $field) {
    if (!isset($data[$field])) {
        die(json_encode(['success' => false, 'message' => 'Eksik alan: ' . $field]));
    }
}


$task_id = intval($data['task_id']);
$correct_count = intval($data['correct_count']);
$wrong_count = intval($data['wrong_count']);
$blank_count = intval($data['blank_count']);
$wrong_topics = isset($data['wrong_topics']) ? $data['wrong_topics'] : [];
$wrong_topics_json = json_encode($wrong_topics, JSON_UNESCAPED_UNICODE);


session_start();
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Oturum bulunamadı. Lütfen tekrar giriş yapın.']);
    exit();
}
$student_id = intval($_SESSION['user_id']);


$conn->begin_transaction();

try {
    
    $stmt_task = $conn->prepare("SELECT subject, question_count FROM tasks WHERE id = ? AND task_type = 'exam_entry'");
    $stmt_task->bind_param("i", $task_id);
    $stmt_task->execute();
    $result_task = $stmt_task->get_result();
    
    if ($result_task->num_rows === 0) {
        throw new Exception('Görev bulunamadı veya bu bir sınav görevi değil.');
    }
    
    $task = $result_task->fetch_assoc();
    $stmt_task->close();
    
    
    $total_questions = $correct_count + $wrong_count + $blank_count;
    if ($total_questions > $task['question_count']) {
        throw new Exception('Girilen toplam soru sayısı, görevde belirtilen soru sayısından fazla olamaz.');
    }
    
    
    $stmt = $conn->prepare("INSERT INTO student_exam_subject_results (student_id, task_id, subject_name, correct_count, wrong_count, blank_count, wrong_topics) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("iisiiis", $student_id, $task_id, $task['subject'], $correct_count, $wrong_count, $blank_count, $wrong_topics_json);
    
    if (!$stmt->execute()) {
        throw new Exception('Sonuçlar kaydedilirken bir hata oluştu: ' . $stmt->error);
    }
    
    $stmt->close();
    
    
    $stmt_status = $conn->prepare("INSERT INTO student_task_status (student_id, task_id, is_completed, completion_date) VALUES (?, ?, 1, NOW()) ON DUPLICATE KEY UPDATE is_completed = 1, completion_date = NOW()");
    $stmt_status->bind_param("ii", $student_id, $task_id);
    
    if (!$stmt_status->execute()) {
        throw new Exception('Görev durumu güncellenirken bir hata oluştu: ' . $stmt_status->error);
    }
    
    $stmt_status->close();
    
    
    $conn->commit();
    
    echo json_encode(['success' => true, 'message' => 'Sınav sonuçları başarıyla kaydedildi.']);
    
} catch (Exception $e) {
    
    $conn->rollback();
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}

$conn->close();
?> 