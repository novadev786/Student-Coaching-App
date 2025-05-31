<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "student_db";


$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Veritabanına bağlanılamadı: " . $conn->connect_error);
}


$code = $_POST['courseCode'];
$name = $_POST['courseName'];
$teacher = $_POST['courseTeacher'];
$department = $_POST['courseDept'];


$sql = "INSERT INTO courses (course_code, course_name, course_teacher, course_department) 
        VALUES ('$code', '$name', '$teacher', '$department')";

if ($conn->query($sql) === TRUE) {
    header("Location: admin-panel.html?success=1");
    exit();
} else {
    header("Location: admin-panel.html?success=0");
    exit();
}


$conn->close();
?>
