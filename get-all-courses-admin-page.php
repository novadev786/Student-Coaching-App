<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "student_db";


$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_code'])) {
    $codeToDelete = $conn->real_escape_string($_POST['delete_code']);
    if ($conn->query("DELETE FROM courses WHERE course_code = '$codeToDelete'") === TRUE) {
        echo "success";
    } else {
        echo "error";
    }
    exit;
}

$sql = "SELECT course_code, course_name, course_teacher, course_department FROM courses";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<table border='1' style='width:100%; border-collapse: collapse;'>";
    echo "<tr>
            <th>Ders Kodu</th>
            <th>Ders Adı</th>
            <th>Öğretmen</th>
            <th>Bölüm</th>
            <th>Sil</th>
          </tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr id='row_" . htmlspecialchars($row['course_code']) . "'>
                <td>" . htmlspecialchars($row['course_code']) . "</td>
                <td>" . htmlspecialchars($row['course_name']) . "</td>
                <td>" . htmlspecialchars($row['course_teacher']) . "</td>
                <td>" . htmlspecialchars($row['course_department']) . "</td>
                <td><button onclick=\"deleteCourse('" . htmlspecialchars($row['course_code']) . "')\">Sil</button></td>
              </tr>";
    }
    echo "</table>";
} else {
    echo "<p>Hiçbir ders bulunamadı.</p>";
}

$conn->close();
?>

