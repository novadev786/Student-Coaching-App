<?php
session_start();
require_once 'db_connection.php';

// Oturum ve rol kontrolü
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] != 'student') {
    http_response_code(403);
    echo json_encode(['error' => 'Unauthorized access']);
    exit();
}

// Öğrenci ID'sini al
$student_id = $_SESSION['user_id'];

header('Content-Type: application/json');

try {
    // Öğrencinin tüm deneme sınavlarındaki yanlış konularını getir
    // student_exam_subject_results tablosunda wrong_topics JSON formatında saklanıyor.
    // Bu sorgu tüm yanlış konuları çekecek ve subject_name'e göre gruplama yapacağız.
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

        // wrong_topics JSON dizesini PHP dizisine dönüştür
        $wrong_topics_arr = json_decode($wrong_topics_json, true);

        // Eğer json_decode başarılı olursa ve dizi dönerse konuları işle
        if (json_last_error() === JSON_ERROR_NONE && is_array($wrong_topics_arr)) {
            if (!isset($study_topics[$subject])) {
                $study_topics[$subject] = [];
            }
            // Mevcut konularla birleştir ve benzersiz konuları al
            $study_topics[$subject] = array_unique(array_merge($study_topics[$subject], $wrong_topics_arr));
        } else {
             // JSON decode hatası veya boş/geçersiz JSON
            error_log("JSON decode hatası veya geçersiz wrong_topics verisi: " . $wrong_topics_json . " Hata: " . json_last_error_msg());
        }
    }

    // Konuları derslere göre alfabetik sırala (isteğe bağlı)
    // ksort($study_topics);

    // JSON formatında sonuçları döndür
    echo json_encode($study_topics);

} catch (Exception $e) {
    error_log("Çalışılacak konular hatası: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['error' => 'Veritabanı hatası: ' . $e->getMessage()]);
}

$stmt->close();
$conn->close();
?> 