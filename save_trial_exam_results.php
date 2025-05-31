<?php
header('Content-Type: application/json');
$conn = new mysqli("localhost", "root", "", "student_db");

if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(["error" => "Database connection failed: " . $conn->connect_error]);
    exit();
}
$conn->set_charset("utf8mb4");

$data = json_decode(file_get_contents("php://input"), true);

$student_task_id = isset($data['task_id']) ? intval($data['task_id']) : 0;
$subjects_data = isset($data['subjects']) && is_array($data['subjects']) ? $data['subjects'] : [];

if ($student_task_id <= 0) {
    http_response_code(400);
    echo json_encode(["error" => "Invalid input. Task ID is required."]);
    exit();
}

$conn->begin_transaction();

try {
    
    $stmt_delete = $conn->prepare("DELETE FROM trial_exam_subject_results WHERE student_task_id = ?");
    if (!$stmt_delete) throw new Exception("Delete statement preparation failed: " . $conn->error);
    $stmt_delete->bind_param("i", $student_task_id);
    if (!$stmt_delete->execute())  throw new Exception("Failed to delete existing results: " . $stmt_delete->error);
    $stmt_delete->close();

    
    if (!empty($subjects_data)) {
        $stmt_insert = $conn->prepare("INSERT INTO trial_exam_subject_results (student_task_id, subject_name, correct_count, incorrect_count, blank_count, net_score) VALUES (?, ?, ?, ?, ?, ?)");
        if (!$stmt_insert) throw new Exception("Insert statement preparation failed: " . $conn->error);

        foreach ($subjects_data as $subject) {
            if (!isset($subject['name']) || !isset($subject['correct']) || !isset($subject['incorrect']) || !isset($subject['blank'])) {
                 
                continue; 
            }
            $subject_name = $conn->real_escape_string($subject['name']);
            $correct = intval($subject['correct']);
            $incorrect = intval($subject['incorrect']);
            $blank = intval($subject['blank']);
            
            
            $net_score = $correct - ($incorrect / 4.0);

            $stmt_insert->bind_param("isiiid", $student_task_id, $subject_name, $correct, $incorrect, $blank, $net_score);
            if (!$stmt_insert->execute()) {
                throw new Exception("Failed to insert subject: " . $subject_name . " - " . $stmt_insert->error);
            }
        }
        $stmt_insert->close();
    }





    $conn->commit();
    echo json_encode(["success" => true, "message" => "Deneme sonuçları başarıyla kaydedildi."]);

} catch (Exception $e) {
    $conn->rollback();
    http_response_code(500);
    echo json_encode(["error" => "Sonuçlar kaydedilemedi.", "details" => $e->getMessage()]);
}

$conn->close();
?>