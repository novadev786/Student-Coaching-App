<?php

$host = 'localhost';
$username = 'root';
$password = '';
$database = 'student_db';


$conn = new mysqli($host, $username, $password, $database);


if ($conn->connect_error) {
    error_log("Veritabanı bağlantı hatası: " . $conn->connect_error);
    die("Veritabanı bağlantısı başarısız: " . $conn->connect_error);
}


$conn->set_charset("utf8mb4");
?> 