<?php
$servername = "localhost";
$username = "root"; // default for localhost
$password = "";     // empty by default
$dbname = "student_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get data from POST
$name = $_POST['name'];
$email = $_POST['email'];
$pass = $_POST['password'];

// Insert into DB
$sql = "INSERT INTO users (name, email, password) VALUES ('$name', '$email', '$pass')";

if ($conn->query($sql) === TRUE) {
    echo "Kayıt başarılı!";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
