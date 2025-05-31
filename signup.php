<?php
$servername = "localhost";
$username = "root"; 
$password = "";     
$dbname = "student_db";


$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$name = $_POST['name'];
$email = $_POST['email'];
$pass = $_POST['password'];


$sql = "INSERT INTO users (name, email, password) VALUES ('$name', '$email', '$pass')";

if ($conn->query($sql) === TRUE) {
    echo "Kayıt başarılı!";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
