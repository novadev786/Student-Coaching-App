<?php
// Connect to the database
$conn = new mysqli("localhost", "root", "", "student_db");

// Check connection
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(["error" => "Database connection failed."]);
    exit();
}

// Get student_id from GET parameters
$student_id = isset($_GET['student_id']) ? intval($_GET['student_id']) : 0;

// Validate student ID
if ($student_id <= 0) {
    http_response_code(400);
    echo json_encode(["error" => "Invalid student ID."]);
    exit();
}

// Query the database
$sql = "SELECT id, task_title, task_description, points, start_date, due_date, is_done 
        FROM student_daily_tasks 
        WHERE student_id = $student_id 
        ORDER BY due_date ASC";

$result = $conn->query($sql);

$tasks = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $tasks[] = $row;
    }
}

// Return data as JSON
header('Content-Type: application/json');
echo json_encode($tasks);
?>
