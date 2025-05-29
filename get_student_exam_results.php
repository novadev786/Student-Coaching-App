<?php
session_start();
require_once 'db_connection.php';

// Oturum ve rol kontrolü
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] != 'student') {
    error_log("Oturum hatası: user_id veya role bulunamadı");
    http_response_code(403);
    echo json_encode(['error' => 'Unauthorized access']);
    exit();
}

// Öğrenci ID'sini al
$student_id = $_SESSION['user_id'];
error_log("Öğrenci ID: " . $student_id);

try {
    // Öğrencinin deneme sonuçlarını getir
    $query = "SELECT 
                g.title as exam_title,
                s.subject_name,
                s.correct_count,
                s.wrong_count,
                s.blank_count,
                s.wrong_topics
            FROM student_exam_subject_results s
            JOIN tasks t ON s.task_id = t.id
            JOIN goals g ON t.goal_id = g.id
            WHERE s.student_id = ?
            ORDER BY g.title, FIELD(s.subject_name, 'Türkçe','Matematik','Fen','İnkılap','İngilizce','Din')";

    error_log("SQL Query: " . $query);
    error_log("Student ID: " . $student_id);

    $stmt = $conn->prepare($query);
    if (!$stmt) {
        error_log("Sorgu hazırlama hatası: " . $conn->error);
        throw new Exception("Sorgu hazırlanamadı: " . $conn->error);
    }

    $stmt->bind_param("i", $student_id);
    if (!$stmt->execute()) {
        error_log("Sorgu çalıştırma hatası: " . $stmt->error);
        throw new Exception("Sorgu çalıştırılamadı: " . $stmt->error);
    }

    $result = $stmt->get_result();
    if (!$result) {
        error_log("Sonuç alma hatası: " . $stmt->error);
        throw new Exception("Sonuç alınamadı: " . $stmt->error);
    }

    // Sonuçları grupla
    $grouped = [];
    while ($row = $result->fetch_assoc()) {
        $exam = $row['exam_title'];
        $subj = $row['subject_name'];
        if (!isset($grouped[$exam])) $grouped[$exam] = [];
        
        $wrong_topics = '';
        if (!empty($row['wrong_topics'])) {
            $decoded = json_decode($row['wrong_topics'], true);
            if (is_array($decoded)) $wrong_topics = implode(", ", $decoded);
        }
        
        $grouped[$exam][$subj] = [
            'D' => $row['correct_count'],
            'Y' => $row['wrong_count'],
            'B' => $row['blank_count'],
            'W' => $wrong_topics
        ];
    }

    error_log("Gruplanmış sonuçlar: " . print_r($grouped, true));

    // Sonuçları tek satırda döndür
    $rows = [];
    $fixed_subjects = ['Türkçe', 'Matematik', 'Fen', 'İnkılap', 'İngilizce', 'Din'];
    
    foreach ($grouped as $exam => $subjects) {
        $row = ['exam_title' => $exam];
        foreach ($fixed_subjects as $subj) {
            $r = isset($subjects[$subj]) ? $subjects[$subj] : ['D'=>'','Y'=>'','B'=>'','W'=>''];
            $row[$subj] = $r['D'] . "/" . $r['Y'] . "/" . $r['B'];
            $row[$subj.'_wrong_topics'] = $r['W'];
        }
        $rows[] = $row;
    }

    error_log("Final sonuçlar: " . print_r($rows, true));

    // JSON formatında sonuçları döndür
    header('Content-Type: application/json');
    echo json_encode($rows);

} catch (Exception $e) {
    error_log("Öğrenci sınav sonuçları hatası: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['error' => 'Veritabanı hatası: ' . $e->getMessage()]);
}

$stmt->close();
$conn->close();
?> 