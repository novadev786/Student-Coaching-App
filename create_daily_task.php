<?php
// Connect to your MySQL database
$conn = new mysqli("localhost", "root", "", "student_db");

// Check connection
if ($conn->connect_error) {
    die("Bağlantı hatası: " . $conn->connect_error);
}

// Collect and sanitize POST data
$student_id = intval($_POST['student_id']);
$task_title = $conn->real_escape_string($_POST['task_title']);
$task_description = $conn->real_escape_string($_POST['task_description']);
$points = isset($_POST['points']) ? intval($_POST['points']) : 0;
$start_date = $_POST['start_date'];
$due_date = $_POST['due_date'];

// Prepare and execute SQL
$stmt = $conn->prepare("INSERT INTO student_daily_tasks (student_id, task_title, task_description, points, start_date, due_date) VALUES (?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ississ", $student_id, $task_title, $task_description, $points, $start_date, $due_date);

if ($stmt->execute()) {
    header("Location: admin-panel.html?success=1"); // Optional redirect
} else {
    echo "Hata: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
