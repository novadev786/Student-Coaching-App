<?php
header('Content-Type: application/json');

$conn = new mysqli("localhost", "root", "", "student_db");
if ($conn->connect_error) {
    echo json_encode(['error' => 'Database connection failed']);
    exit();
}
$conn->set_charset("utf8mb4");

$goals = [];

$result = $conn->query("SELECT id, name FROM users WHERE role = 'student' ORDER BY name ASC");

if ($result) {
    while ($row = $result->fetch_assoc()) {
        
        $row['title'] = htmlspecialchars($row['title']);
        $goals[] = $row;
    }
    echo json_encode($goals);
} else {
    echo json_encode(['error' => 'Could not fetch goals']);
}

$conn->close();
?>