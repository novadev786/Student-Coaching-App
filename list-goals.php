<?php
$conn = new mysqli("localhost", "root", "", "student_db");
if ($conn->connect_error) {
    error_log("Database Connection Error: " . $conn->connect_error);
    die("Veritabanı bağlantısında bir sorun oluştu.");
}
$conn->set_charset("utf8mb4");

// Hedefleri en yeniden eskiye doğru çek, tarihleri de al
$result = $conn->query("SELECT id, title, description, start_date, end_date, created_at FROM goals ORDER BY created_at DESC");

?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Hedef Listesi</title>
    <link rel="icon" href="./images/study.png">
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #f4f7fa; padding: 40px; color: #333; }
        .container { max-width: 800px; margin: auto; }
        h2 { text-align: center; margin-bottom: 30px; color: #0056b3; }
        .goal { background-color: white; border-radius: 12px; padding: 25px; margin-bottom: 25px; box-shadow: 0 5px 15px rgba(0,0,0,0.08); border-left: 5px solid #007bff; }
        .goal h3 { margin-top: 0; margin-bottom: 10px; color: #0056b3; }
        .goal p { color: #555; margin-bottom: 15px; line-height: 1.6; }
        .goal .dates { font-size: 0.9em; color: #777; margin-bottom: 15px; }
        .goal .dates span { font-weight: bold; color: #555; }
        .goal small { color: gray; font-size: 0.85em; display: block; margin-top: 10px; }
        .view-tasks { display: inline-block; margin-top: 15px; background-color: #28a745; color: white; padding: 10px 18px; border: none; border-radius: 6px; text-decoration: none; font-size: 14px; transition: background-color 0.2s ease; }
        .view-tasks:hover { background-color: #218838; }
         .no-goals { text-align: center; color: #777; font-size: 1.1em; margin-top: 40px; }
         .back-link { display: block; text-align: center; margin-top: 30px; margin-bottom: 20px; color: #007bff; text-decoration: none; font-weight: bold; }
         .back-link:hover { text-decoration: underline; }
    </style>
</head>
<body>

<div class="container">
    <a href="admin-panel.html" class="back-link">← Admin Paneline Geri Dön</a>
    <h2>🎯 Tanımlı Hedefler</h2>

    <?php if ($result && $result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="goal">
                <h3><?php echo htmlspecialchars($row['title']); ?></h3>
                <p><?php echo nl2br(htmlspecialchars($row['description'])); ?></p>
                <div class="dates">
                    <?php if (!empty($row['start_date'])): ?>
                        <span>Başlangıç:</span> <?php echo date("d.m.Y", strtotime($row['start_date'])); ?>
                    <?php endif; ?>
                    <?php if (!empty($row['start_date']) && !empty($row['end_date'])): ?>
                         | 
                    <?php endif; ?>
                     <?php if (!empty($row['end_date'])): ?>
                         <span>Bitiş:</span> <?php echo date("d.m.Y", strtotime($row['end_date'])); ?>
                     <?php endif; ?>
                </div>
                <small>Oluşturulma: <?php echo date("d.m.Y H:i", strtotime($row['created_at'])); ?></small>
                <a class="view-tasks" href="add-task.php?goal_id=<?php echo $row['id']; ?>">+ Bu Hedefe Görev Ekle</a>
                 <!-- Şimdilik görevleri listeleme linki yerine ekleme linki koydum, listelemeyi sonra yaparız -->
                 <!-- <a class="view-tasks" href="list-tasks.php?goal_id=<?php echo $row['id']; ?>">→ Görevleri Gör</a> -->
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p class="no-goals">Henüz hiçbir hedef tanımlanmamış.</p>
    <?php endif; ?>
</div>

<?php
$conn->close();
?>
</body>
</html>