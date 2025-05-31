<?php

header('Content-Type: application/json');

$conn = new mysqli("localhost", "root", "", "student_db");


if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(["error" => "Veritabanına bağlanılamadı."]);
    exit();
}


$data = json_decode(file_get_contents("php://input"), true);


if (!isset($data['id']) || !isset($data['is_done'])) {
    http_response_code(400);
    echo json_encode(["error" => "Eksik parametre."]);
    exit();
}

$task_id = intval($data['id']);
$is_done = intval($data['is_done']); 


$stmt = $conn->prepare("UPDATE student_daily_tasks SET is_done = ? WHERE id = ?");
$stmt->bind_param("ii", $is_done, $task_id);

if ($stmt->execute()) {
    echo json_encode(["success" => true]);
} else {
    http_response_code(500);
    echo json_encode(["error" => "Güncelleme başarısız."]);
}

$stmt->close();
$conn->close();
?>
