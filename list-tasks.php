<?php
$conn = new mysqli("localhost", "root", "", "student_db");
if ($conn->connect_error) {
    die("Bağlantı hatası: " . $conn->connect_error);
}

$result = $conn->query("SELECT * FROM goals ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Hedef Listesi</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f7fa;
            padding: 40px;
        }
        h2 {
            text-align: center;
            margin-bottom: 30px;
        }
        .goal {
            background-color: white;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        .goal h3 {
            margin: 0;
            color: #333;
        }
        .goal p {
            color: #555;
        }
        .goal small {
            color: gray;
        }
        .view-tasks {
            display: inline-block;
            margin-top: 10px;
            background-color: #007bff;
            color: white;
            padding: 8px 14px;
            border: none;
            border-radius: 6px;
            text-decoration: none;
            font-size: 14px;
        }
        .view-tasks:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

<h2>🎯 Tanımlı Hedefler</h2>

<?php if ($result->num_rows > 0): ?>
    <?php while ($row = $result->fetch_assoc()): ?>
        <div class="goal">
            <h3><?= htmlspecialchars($row['title']) ?></h3>
            <p><?= nl2br(htmlspecialchars($row['description'])) ?></p>
            <small>Oluşturulma: <?= $row['created_at'] ?></small><br>
            <a class="view-tasks" href="list-tasks.php?goal_id=<?= $row['id'] ?>">→ Görevleri Gör</a>
        </div>
    <?php endwhile; ?>
<?php else: ?>
    <p>Henüz hiçbir hedef tanımlanmamış.</p>
<?php endif; ?>

</body>
</html>
