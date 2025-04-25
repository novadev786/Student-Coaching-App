<?php
$conn = new mysqli("localhost", "root", "", "student_db");
if ($conn->connect_error) {
    die("Bağlantı hatası: " . $conn->connect_error);
}

$id = $_POST['id'];
$sql = "DELETE FROM selected_courses WHERE id = $id";

if ($conn->query($sql) === TRUE) {
    echo "Ders başarıyla silindi!";
} else {
    echo "Silme hatası: " . $conn->error;
}

$conn->close();
?>
