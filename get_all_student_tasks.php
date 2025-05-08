<?php
$conn = new mysqli("localhost", "root", "", "student_db");
header('Content-Type: application/json');

if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(["error" => "Database error"]);
    exit();
}

$result = $conn->query("
    SELECT 
        t.id, 
        t.student_id, 
        u.name as student_name,
        t.task_title, 
        t.task_description, 
        t.points, 
        t.start_date, 
        t.due_date, 
        t.is_done
    FROM student_daily_tasks t
    LEFT JOIN users u ON t.student_id = u.id
    ORDER BY t.student_id, t.due_date ASC
");

$tasks = [];

while ($row = $result->fetch_assoc()) {
    $tasks[] = $row;
}

echo json_encode($tasks);
?>
