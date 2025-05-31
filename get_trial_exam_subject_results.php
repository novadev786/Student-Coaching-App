<?php
header('Content-Type: application/json');
$conn = new mysqli("localhost", "root", "", "student_db");

if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(["error" => "Database connection failed: " . $conn->connect_error]);
    exit();
}
$conn->set_charset("utf8mb4");


$student_task_id = isset($_GET['task_id']) ? intval($_GET['task_id']) : 0;

if ($student_task_id <= 0) {
    http_response_code(400);
    echo json_encode(["error" => "Invalid task ID."]);
    exit();
}



$stmt = $conn->prepare("SELECT subject_name, correct_count, incorrect_count, blank_count, net_score FROM trial_exam_subject_results WHERE student_task_id = ? ORDER BY subject_name");
if (!$stmt) {
    http_response_code(500);
    echo json_encode(["error" => "Statement preparation failed: " . $conn->error]);
    exit();
}

$stmt->bind_param("i", $student_task_id);
if (!$stmt->execute()) {
    http_response_code(500);
    echo json_encode(["error" => "Statement execution failed: " . $stmt->error]);
    exit();
}

$result = $stmt->get_result();
$subjects = [];
while ($row = $result->fetch_assoc()) {
    $subjects[] = $row;
}

echo json_encode($subjects);

$stmt->close();
$conn->close();
?>