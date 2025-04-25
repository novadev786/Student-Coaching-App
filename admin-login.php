<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "student_db";

// Connect to MySQL
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get email and password from POST
$email = $_POST['email'];
$pass = $_POST['password'];

// Check if admin exists
$sql = "SELECT * FROM users WHERE email='admin@novadev.com' AND password='$pass'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "Yönetici girişi başarılı !!!";
} else {
    echo "Geçersiz yönetici kimlik bilgileri";
}

$conn->close();
?>
