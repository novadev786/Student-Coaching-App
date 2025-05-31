<?php
session_start();
header('Content-Type: application/json');

$response = ['tasks' => [], 'error' => null];


if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] != 'student') {
    $response['error'] = "Yetkisiz erişim veya oturum bulunamadı.";
    http_response_code(401); 
    echo json_encode($response);
    exit();
}
$student_id = intval($_SESSION['user_id']);


$conn = new mysqli("localhost", "root", "", "student_db");
if ($conn->connect_error) {
    $response['error'] = "Veritabanı bağlantı hatası.";
    http_response_code(500); 
    error_log("DB Connection Error (get_student_tasks): " . $conn->connect_error);
    echo json_encode($response);
    exit();
}
$conn->set_charset("utf8mb4");


$assigned_goal_ids = [];
$sql_goals = "SELECT goal_id FROM student_goal_assignments WHERE student_id = ?";
$stmt_goals = $conn->prepare($sql_goals);
$stmt_goals->bind_param("i", $student_id);
$stmt_goals->execute();
$result_goals = $stmt_goals->get_result();
while ($row_goal = $result_goals->fetch_assoc()) {
    $assigned_goal_ids[] = $row_goal['goal_id'];
}
$stmt_goals->close();


if (empty($assigned_goal_ids)) {
    echo json_encode($response); 
    $conn->close();
    exit();
}


$goal_ids_string = implode(',', $assigned_goal_ids);


$sql_tasks = "SELECT
                t.id AS task_id,
                t.goal_id,
                g.title AS goal_title,
                t.task_order,
                t.title AS task_title,
                t.description AS task_description,
                t.subject,
                t.topic,
                t.question_count,
                t.task_date,
                t.task_type,  
                COALESCE(sts.is_completed, 0) AS is_done
            FROM tasks t
            JOIN goals g ON t.goal_id = g.id
            LEFT JOIN student_task_status sts ON t.id = sts.task_id AND sts.student_id = ?
            WHERE t.goal_id IN ($goal_ids_string)
            ORDER BY g.title, t.task_order ASC, t.task_date ASC";

$stmt_tasks = $conn->prepare($sql_tasks);

$stmt_tasks->bind_param("i", $student_id);
$stmt_tasks->execute();
$result_tasks = $stmt_tasks->get_result();

if ($result_tasks) {
    while ($row_task = $result_tasks->fetch_assoc()) {
        
        $row_task['task_date_formatted'] = !empty($row_task['task_date']) ? date("d.m.Y", strtotime($row_task['task_date'])) : 'Tarih Yok';
        
        $row_task['is_done'] = (bool)$row_task['is_done'];
        $response['tasks'][] = $row_task;
    }
} else {
    $response['error'] = "Görevler çekilirken hata oluştu.";
    error_log("Get Student Tasks Error: " . $conn->error);
}

$stmt_tasks->close();
$conn->close();

echo json_encode($response);
?>