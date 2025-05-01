<?php
// Veritabanı bağlantısı
$conn = new mysqli("localhost", "root", "", "student_db");
if ($conn->connect_error) {
    die("Bağlantı hatası: " . $conn->connect_error);
}

// Form gönderildiyse
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $goal_id = $_POST['goal_id'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $due_date = $_POST['due_date'];

    $stmt = $conn->prepare("INSERT INTO tasks (goal_id, title, description, due_date, is_completed) VALUES (?, ?, ?, ?, 0)");
    $stmt->bind_param("isss", $goal_id, $title, $description, $due_date);
    $stmt->execute();

    echo "<div class='success'>Görev başarıyla eklendi! 🎉</div>";
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Görev Ekle</title>
    <link rel="icon" href="./images/study.png">
    <style>
        body {
            background: linear-gradient(to right, #f0f2f5, #cce1f0);
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
        <h2>Görev Ekle</h2>
        <form method="POST" action="">

        <label>Hedef Seç:</label>
<select name="goal_id" required>
    <option value="">-- Hedef Seçin --</option>
    <?php
    $result = $conn->query("SELECT id, title FROM goals");
    while ($row = $result->fetch_assoc()) {
        echo "<option value='" . $row['id'] . "'>" . htmlspecialchars($row['title']) . "</option>";
    }
    ?>
</select>

            <label>Görev Başlığı:</label>
            <input type="text" name="title" required>

            <label>Açıklama:</label>
            <textarea name="description" rows="4"></textarea>

            <label>Teslim Tarihi:</label>
            <input type="date" name="due_date" required>

            <button type="submit">Görevi Kaydet</button>
        </form>
    </div>
</body>
</html>
