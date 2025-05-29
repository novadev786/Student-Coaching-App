<?php
// Veritabanı bağlantı bilgileri
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'student_db';

// Veritabanı bağlantısını oluştur
$conn = new mysqli($host, $username, $password, $database);

// Bağlantı hatasını kontrol et
if ($conn->connect_error) {
    error_log("Veritabanı bağlantı hatası: " . $conn->connect_error);
    die("Veritabanı bağlantısı başarısız: " . $conn->connect_error);
}

// Türkçe karakter desteği için karakter setini ayarla
$conn->set_charset("utf8mb4");
?> 