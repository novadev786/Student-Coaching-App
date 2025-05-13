<?php
header('Content-Type: application/json'); // JSON yanıtı döndüreceğiz

$response = ['success' => false, 'message' => ''];

$conn = new mysqli("localhost", "root", "", "student_db");
if ($conn->connect_error) {
    $response['message'] = 'Veritabanı bağlantı hatası.';
    echo json_encode($response);
    exit();
}
$conn->set_charset("utf8mb4");

// Gelen verileri al ve doğrula
$goal_id = isset($_POST['goal_id']) ? intval($_POST['goal_id']) : 0;
$student_id = isset($_POST['student_id']) ? intval($_POST['student_id']) : 0;

if ($goal_id <= 0 || $student_id <= 0) {
    $response['message'] = 'Geçersiz hedef veya öğrenci IDsi.';
    echo json_encode($response);
    exit();
}

// Bu atama zaten var mı diye kontrol et (UNIQUE KEY sayesinde aslında hata verecektir ama ön kontrol iyi)
$stmt_check = $conn->prepare("SELECT id FROM student_goal_assignments WHERE student_id = ? AND goal_id = ?");
$stmt_check->bind_param("ii", $student_id, $goal_id);
$stmt_check->execute();
$result_check = $stmt_check->get_result();

if ($result_check->num_rows > 0) {
    $response['message'] = 'Bu hedef bu öğrenciye zaten atanmış.';
    // Başarılı kabul edebiliriz, çünkü zaten atanmış. Veya success=true diyebiliriz.
    // $response['success'] = true;
    $stmt_check->close();
    echo json_encode($response);
    exit();
}
$stmt_check->close();

// Atamayı ekle
$stmt_insert = $conn->prepare("INSERT INTO student_goal_assignments (student_id, goal_id) VALUES (?, ?)");
$stmt_insert->bind_param("ii", $student_id, $goal_id);

if ($stmt_insert->execute()) {
    $response['success'] = true;
    $response['message'] = 'Hedef başarıyla öğrenciye atandı!';
} else {
    // UNIQUE KEY ihlali veya başka bir veritabanı hatası
    if ($conn->errno == 1062) { // MySQL UNIQUE constraint error number
         $response['message'] = 'Bu hedef bu öğrenciye zaten atanmış (DB Kısıtlaması).';
         // $response['success'] = true; // İsteğe bağlı olarak başarı kabul edilebilir
    } else {
        $response['message'] = 'Atama yapılırken bir veritabanı hatası oluştu: ' . $conn->error;
        error_log("Assign Goal Error: " . $conn->error); // Hatayı logla
    }
}

$stmt_insert->close();
$conn->close();

echo json_encode($response);
?>