<?php
session_start();
require_once 'db_connection.php';


if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] != 'student') {
    http_response_code(403);
    echo json_encode(['error' => 'Unauthorized access']);
    exit();
}


$student_id = $_SESSION['user_id'];

header('Content-Type: application/json');

try {
    
    $query = "SELECT subject_name, wrong_topics FROM student_exam_subject_results WHERE student_id = ?";

    $stmt = $conn->prepare($query);
    if (!$stmt) {
        throw new Exception("Sorgu hazırlanamadı: " . $conn->error);
    }

    $stmt->bind_param("i", $student_id);
    if (!$stmt->execute()) {
        throw new Exception("Sorgu çalıştırılamadı: " . $stmt->error);
    }

    $result = $stmt->get_result();
    if (!$result) {
        throw new Exception("Sonuç alınamadı: " . $stmt->error);
    }

    $study_topics = [];

    while ($row = $result->fetch_assoc()) {
        $subject = $row['subject_name'];
        $wrong_topics_json = $row['wrong_topics'];

        
        $wrong_topics_arr = json_decode($wrong_topics_json, true);

        
        if (json_last_error() === JSON_ERROR_NONE && is_array($wrong_topics_arr)) {
            if (!isset($study_topics[$subject])) {
                $study_topics[$subject] = [];
            }
            
            $study_topics[$subject] = array_unique(array_merge($study_topics[$subject], $wrong_topics_arr));
        } else {
             
            error_log("JSON decode hatası veya geçersiz wrong_topics verisi: " . $wrong_topics_json . " Hata: " . json_last_error_msg());
        }
    }



    
    echo json_encode($study_topics);

} catch (Exception $e) {
    error_log("Çalışılacak konular hatası: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['error' => 'Veritabanı hatası: ' . $e->getMessage()]);
}

$stmt->close();
$conn->close();
?> 