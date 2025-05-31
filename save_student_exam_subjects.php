<?php
session_start();
header('Content-Type: application/json'); 

$response = ['success' => false, 'message' => 'İşlem başlatılmadı.']; 


if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] != 'student') {
    $response['message'] = "Yetkisiz erişim.";
    http_response_code(401); 
    echo json_encode($response);
    exit();
}
$student_id = intval($_SESSION['user_id']);


$raw_data = file_get_contents("php://input");
$decoded_data = json_decode($raw_data, true); 


error_log("----- SAVE EXAM SUBJECTS REQUEST -----");
error_log("Student ID: " . $student_id);
error_log("Raw JSON Data: " . $raw_data);
error_log("Decoded Data: " . print_r($decoded_data, true));



if (is_null($decoded_data)) {
    $response['message'] = "Geçersiz JSON formatı veya boş veri.";
    http_response_code(400); 
    echo json_encode($response);
    exit();
}

$task_id = isset($decoded_data['task_id']) ? intval($decoded_data['task_id']) : 0;
$exam_name = isset($decoded_data['exam_name']) ? trim($decoded_data['exam_name']) : null;
$subjects = isset($decoded_data['subjects']) && is_array($decoded_data['subjects']) ? $decoded_data['subjects'] : [];

if ($task_id <= 0) {
    $response['message'] = "Geçersiz veya eksik görev ID'si.";
    http_response_code(400);
    echo json_encode($response);
    exit();
}

if (empty($subjects)) {
    $response['message'] = "Kaydedilecek ders sonucu bulunamadı.";
    http_response_code(400);
    echo json_encode($response);
    exit();
}


foreach ($subjects as $index => $subject) {
    if (!isset($subject['name']) || !isset($subject['correct']) || !isset($subject['wrong']) || !isset($subject['blank'])) {
        $response['message'] = ($index + 1) . ". sıradaki ders için eksik veri (isim, doğru, yanlış, boş).";
        http_response_code(400);
        echo json_encode($response);
        exit();
    }
    
    if (!is_numeric($subject['correct']) || !is_numeric($subject['wrong']) || !is_numeric($subject['blank'])) {
         $response['message'] = ($index + 1) . ". sıradaki ders için doğru/yanlış/boş sayıları geçerli değil.";
         http_response_code(400);
         echo json_encode($response);
         exit();
    }
}



$conn = new mysqli("localhost", "root", "", "student_db");
if ($conn->connect_error) {
    $response['message'] = "Veritabanı bağlantı hatası.";
    http_response_code(500);
    error_log("SaveExamSubjects DB Connect Error: " . $conn->connect_error);
    echo json_encode($response);
    exit();
}
$conn->set_charset("utf8mb4");


$conn->begin_transaction(); 

try {
    
    $stmt_delete = $conn->prepare("DELETE FROM student_exam_subject_results WHERE student_id = ? AND task_id = ?");
    if (!$stmt_delete) throw new Exception("Silme sorgusu hazırlanamadı: " . $conn->error);
    $stmt_delete->bind_param("ii", $student_id, $task_id);
    if (!$stmt_delete->execute()) throw new Exception("Eski kayıtlar silinirken hata: " . $stmt_delete->error);
    $stmt_delete->close();

    
    $stmt_insert = $conn->prepare("INSERT INTO student_exam_subject_results (student_id, task_id, exam_name, subject_name, correct_count, wrong_count, blank_count) VALUES (?, ?, ?, ?, ?, ?, ?)");
    if (!$stmt_insert) throw new Exception("Ekleme sorgusu hazırlanamadı: " . $conn->error);

    foreach ($subjects as $subject) {
        $subject_name_db = trim($subject['name']);
        $correct_db = intval($subject['correct']);
        $wrong_db = intval($subject['wrong']);
        $blank_db = intval($subject['blank']);
    

        $stmt_insert->bind_param("iisssii", $student_id, $task_id, $exam_name, $subject_name_db, $correct_db, $wrong_db, $blank_db);
        if (!$stmt_insert->execute()) throw new Exception("Ders sonucu eklenirken hata (" . $subject_name_db . "): " . $stmt_insert->error);
    }
    $stmt_insert->close();

    $conn->commit();
    $response['success'] = true;
    $response['message'] = "Tüm ders sonuçları başarıyla kaydedildi.";

} catch (Exception $e) {
    $conn->rollback();
    $response['message'] = "Veritabanı işlemi sırasında bir hata oluştu: " . $e->getMessage();
    http_response_code(500);
    error_log("SaveExamSubjects DB Error: " . $e->getMessage());
}

$conn->close();
echo json_encode($response);
?>