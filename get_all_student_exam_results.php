<?php
header('Content-Type: application/json');
$conn = new mysqli("localhost", "root", "", "student_db");
if ($conn->connect_error) {
    echo json_encode([]); exit();
}
$conn->set_charset("utf8mb4");

$fixed_subjects = ['Türkçe', 'Matematik', 'Fen', 'İnkılap', 'İngilizce', 'Din'];


$sql = "SELECT u.name AS student_name, g.title AS exam_title, s.subject_name, s.correct_count, s.wrong_count, s.blank_count, s.wrong_topics
        FROM student_exam_subject_results s
        JOIN users u ON s.student_id = u.id
        JOIN tasks t ON s.task_id = t.id
        JOIN goals g ON t.goal_id = g.id
        WHERE s.subject_name IN ('" . implode("','", $fixed_subjects) . "')
        ORDER BY u.name, g.title, FIELD(s.subject_name, 'Türkçe','Matematik','Fen','İnkılap','İngilizce','Din')";
$res = $conn->query($sql);


$grouped = [];
while ($row = $res->fetch_assoc()) {
    $stu = $row['student_name'];
    $exam = $row['exam_title'];
    $subj = $row['subject_name'];
    if (!isset($grouped[$stu])) $grouped[$stu] = [];
    if (!isset($grouped[$stu][$exam])) $grouped[$stu][$exam] = [];
    $wrong_topics = '';
    if (!empty($row['wrong_topics'])) {
        $decoded = json_decode($row['wrong_topics'], true);
        if (is_array($decoded)) $wrong_topics = implode(", ", $decoded);
    }
    $grouped[$stu][$exam][$subj] = [
        'D' => $row['correct_count'],
        'Y' => $row['wrong_count'],
        'B' => $row['blank_count'],
        'W' => $wrong_topics
    ];
}


$rows = [];
foreach ($grouped as $stu => $exams) {
    foreach ($exams as $exam => $subjects) {
        $row = [
            'student_name' => $stu,
            'exam_title' => $exam
        ];
        foreach ($fixed_subjects as $subj) {
            $r = isset($subjects[$subj]) ? $subjects[$subj] : ['D'=>'','Y'=>'','B'=>'','W'=>''];
            $row[$subj] = $r['D'] . "/" . $r['Y'] . "/" . $r['B'];
            $row[$subj.'_wrong_topics'] = $r['W'];
        }
        $rows[] = $row;
    }
}
echo json_encode($rows);
$conn->close(); 