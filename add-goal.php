<?php
$conn = new mysqli("localhost", "root", "", "student_db");
if ($conn->connect_error) {
    die("Bağlantı hatası: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $teacher_id = 1; // örnek sabit öğretmen ID (daha sonra giriş sisteminden çekebilirsin)
    $title = $_POST['title'];
    $description = $_POST['description'];

    $stmt = $conn->prepare("INSERT INTO goals (teacher_id, title, description) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $teacher_id, $title, $description);

    if ($stmt->execute()) {
        $success = true;
    } else {
        $error = $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Hedef Ekle</title>
    <link rel="icon" href="./images/study.png">
    <style>
        body {
            background: linear-gradient(to right, #e3f2fd, #ffffff);
            font-family: 'Segoe UI', sans-serif;
            padding: 40px;
        }
        .container {
            max-width: 600px;
            margin: auto;
            background-color: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 25px;
        }
        label {
            display: block;
            margin-top: 15px;
            font-weight: bold;
        }
        input, textarea {
            width: 100%;
            padding: 12px;
            margin-top: 5px;
            border-radius: 8px;
            border: 1px solid #ccc;
            font-size: 14px;
        }
        textarea {
            resize: vertical;
        }
        button {
            margin-top: 25px;
            background-color: #007bff;
            color: white;
            padding: 12px 18px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            width: 100%;
        }
        button:hover {
            background-color: #0056b3;
        }
        .success {
            background-color: #d4edda;
            padding: 15px;
            margin-bottom: 20px;
            border: 1px solid #c3e6cb;
            color: #155724;
            border-radius: 8px;
            font-weight: bold;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>🎯 Hedef Ekle</h2>

        <?php if (isset($success) && $success): ?>
            <div class="success">Hedef başarıyla eklendi! ✅</div>
        <?php endif; ?>

        <form method="POST" action="">
            <label>Hedef Başlığı:</label>
            <input type="text" name="title" required>

            <label>Açıklama:</label>
            <textarea name="description" rows="4"></textarea>

            <button type="submit">Hedefi Kaydet</button>
        </form>
    </div>
</body>
</html>
