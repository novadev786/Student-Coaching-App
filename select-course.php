<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "student_db";

// Connect to database
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Bağlantı hatası: " . $conn->connect_error);
}

// Get data from POST
$courseCode = $_POST['courseCode'];
$courseName = $_POST['courseName'];
$courseTeacher = $_POST['courseTeacher'];
$courseDept = $_POST['courseDept'];

// Check if course already selected
$checkSql = "SELECT * FROM selected_courses WHERE course_code = '$courseCode'";
$result = $conn->query($checkSql);

if ($result->num_rows > 0) {
    echo "Bu dersi zaten seçtiniz!";
} else {
    // Insert if not selected
    $insertSql = "INSERT INTO selected_courses (course_code, course_name, course_teacher, course_department) 
                  VALUES ('$courseCode', '$courseName', '$courseTeacher', '$courseDept')";

    if ($conn->query($insertSql) === TRUE) {
        echo "Ders başarıyla seçildi!";
    } else {
        echo "Hata: " . $conn->error;
    }
}

$conn->close();
?>
