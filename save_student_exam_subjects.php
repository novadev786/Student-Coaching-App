<?php
session_start();
header('Content-Type: application/json'); // Yanıtın JSON olacağını belirt

$response = ['success' => false, 'message' => 'İşlem başlatılmadı.']; // Varsayılan yanıt

// 1. Oturum ve Rol Kontrolü
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] != 'student') {
    $response['message'] = "Yetkisiz erişim.";
    http_response_code(401); // Unauthorized
    echo json_encode($response);
    exit();
}
$student_id = intval($_SESSION['user_id']);

// 2. Gelen JSON Verisini Al ve PHP Dizisine Çevir
$raw_data = file_get_contents("php://input");
$decoded_data = json_decode($raw_data, true); // true ile associative array olur

// --- DEBUG: Gelen veriyi logla ---
error_log("----- SAVE EXAM SUBJECTS REQUEST -----");
error_log("Student ID: " . $student_id);
error_log("Raw JSON Data: " . $raw_data);
error_log("Decoded Data: " . print_r($decoded_data, true));
// --- DEBUG SONU ---

// 3. Gelen Veriyi Doğrula
if (is_null($decoded_data)) {
    $response['message'] = "Geçersiz JSON formatı veya boş veri.";
    http_response_code(400); // Bad Request
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

// Her bir dersin gerekli alanlara sahip olup olmadığını kontrol et
foreach ($subjects as $index => $subject) {
    if (!isset($subject['name']) || !isset($subject['correct']) || !isset($subject['wrong']) || !isset($subject['blank'])) {
        $response['message'] = ($index + 1) . ". sıradaki ders için eksik veri (isim, doğru, yanlış, boş).";
        http_response_code(400);
        echo json_encode($response);
        exit();
    }
    // Sayısal değerleri de kontrol et
    if (!is_numeric($subject['correct']) || !is_numeric($subject['wrong']) || !is_numeric($subject['blank'])) {
         $response['message'] = ($index + 1) . ". sıradaki ders için doğru/yanlış/boş sayıları geçerli değil.";
         http_response_code(400);
         echo json_encode($response);
         exit();
    }
}


// 4. Veritabanı Bağlantısı
$conn = new mysqli("localhost", "root", "", "student_db");
if ($conn->connect_error) {
    $response['message'] = "Veritabanı bağlantı hatası.";
    http_response_code(500);
    error_log("SaveExamSubjects DB Connect Error: " . $conn->connect_error);
    echo json_encode($response);
    exit();
}
$conn->set_charset("utf8mb4");

// 5. Güvenlik Kontrolü: Öğrencinin bu task'a erişimi var mı ve task_type 'exam_entry' mi?
// (Bu kontrolü save_student_exam_entry.php'den kopyalayabiliriz, birazdan eklerim)
// ŞİMDİLİK BU KONTROL ATLANDI, TEST AMAÇLI


// 6. Veritabanı İşlemleri (Önce eski kayıtları sil, sonra yenilerini ekle - UPSERT de olabilirdi)
$conn->begin_transaction(); // Transaction başlat

try {
    // Bu task_id ve student_id için mevcut ders kayıtlarını sil (güncelleme yapıyormuş gibi)
    $stmt_delete = $conn->prepare("DELETE FROM student_exam_subject_results WHERE student_id = ? AND task_id = ?");
    if (!$stmt_delete) throw new Exception("Silme sorgusu hazırlanamadı: " . $conn->error);
    $stmt_delete->bind_param("ii", $student_id, $task_id);
    if (!$stmt_delete->execute()) throw new Exception("Eski kayıtlar silinirken hata: " . $stmt_delete->error);
    $stmt_delete->close();

    // Yeni ders sonuçlarını ekle
    $stmt_insert = $conn->prepare("INSERT INTO student_exam_subject_results (student_id, task_id, exam_name, subject_name, correct_count, wrong_count, blank_count) VALUES (?, ?, ?, ?, ?, ?, ?)");
    if (!$stmt_insert) throw new Exception("Ekleme sorgusu hazırlanamadı: " . $conn->error);

    foreach ($subjects as $subject) {
        $subject_name_db = trim($subject['name']);
        $correct_db = intval($subject['correct']);
        $wrong_db = intval($subject['wrong']);
        $blank_db = intval($subject['blank']);
        // Net skoru veritabanı (GENERATED COLUMN) kendi hesaplayacak

        $stmt_insert->bind_param("iisssii", $student_id, $task_id, $exam_name, $subject_name_db, $correct_db, $wrong_db, $blank_db);
        if (!$stmt_insert->execute()) throw new Exception("Ders sonucu eklenirken hata (" . $subject_name_db . "): " . $stmt_insert->error);
    }
    $stmt_insert->close();

    $conn->commit(); // İşlemleri onayla
    $response['success'] = true;
    $response['message'] = "Tüm ders sonuçları başarıyla kaydedildi.";

} catch (Exception $e) {
    $conn->rollback(); // Hata olursa işlemleri geri al
    $response['message'] = "Veritabanı işlemi sırasında bir hata oluştu: " . $e->getMessage();
    http_response_code(500);
    error_log("SaveExamSubjects DB Error: " . $e->getMessage());
}

$conn->close();
echo json_encode($response);
?>