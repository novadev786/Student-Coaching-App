<?php
$conn = new mysqli("localhost", "root", "", "student_db");
if ($conn->connect_error) {
    die("Bağlantı hatası: " . $conn->connect_error);
}

$sql = "SELECT * FROM selected_courses";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<table>
            <tr>
                <th>Ders Kodu</th>
                <th>Ders Adı</th>
                <th>Öğretmen</th>
                <th>Bölüm</th>
                <th>Sil</th>
            </tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>{$row['course_code']}</td>
                <td>{$row['course_name']}</td>
                <td>{$row['course_teacher']}</td>
                <td>{$row['course_department']}</td>
                <td><button onclick='deleteCourse({$row['id']})'>Sil</button></td>
              </tr>";
    }
    echo "</table>";
} else {
    echo "Seçtiğiniz ders bulunamadı.";
}
$conn->close();
?>
