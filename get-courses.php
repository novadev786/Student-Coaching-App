<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "student_db";


$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Bağlantı hatası: " . $conn->connect_error);
}


$sql = "SELECT course_code, course_name, course_teacher, course_department FROM courses";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<table>
            <tr>
                <th>Ders Kodu</th>
                <th>Ders Adı</th>
                <th>Öğretmen</th>
                <th>Bölüm</th>
                <th>Seç</th>
            </tr>";
    while ($row = $result->fetch_assoc()) {
        $data = htmlspecialchars(json_encode($row), ENT_QUOTES, 'UTF-8');
        echo "<tr>
                <td>{$row['course_code']}</td>
                <td>{$row['course_name']}</td>
                <td>{$row['course_teacher']}</td>
                <td>{$row['course_department']}</td>
                <td><button onclick='selectCourse({$data})'>Seç</button></td>
              </tr>";
    }
    echo "</table>";
} else {
    echo "Ders bulunamadı.";
}

$conn->close();
?>
