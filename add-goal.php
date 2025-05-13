<?php
// Veritabanı bağlantısı (güvenli bağlantı bilgilerini kullanın)
$conn = new mysqli("localhost", "root", "", "student_db");
if ($conn->connect_error) {
    // Kullanıcıya genel bir hata mesajı göster, detayı logla
    error_log("Database Connection Error: " . $conn->connect_error);
    die("Veritabanı bağlantısında bir sorun oluştu. Lütfen daha sonra tekrar deneyin.");
}
$conn->set_charset("utf8mb4"); // Türkçe karakterler için

$success_message = '';
$error_message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Formdan gelen verileri al ve temizle
    $teacher_id = 1; // Örnek: Oturumdan veya sabit bir değerden alınmalı
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    // Tarihleri alırken boş olup olmadığını kontrol et
    $start_date = !empty($_POST['start_date']) ? $_POST['start_date'] : null;
    $end_date = !empty($_POST['end_date']) ? $_POST['end_date'] : null;

    // Basit doğrulama (gerekirse daha kapsamlı yapılabilir)
    if (empty($title)) {
        $error_message = "Hedef başlığı boş bırakılamaz.";
    } else {
        // Prepared Statement kullanarak güvenli ekleme
        $stmt = $conn->prepare("INSERT INTO goals (teacher_id, title, description, start_date, end_date) VALUES (?, ?, ?, ?, ?)");

        // Tür belirteçleri: i = integer, s = string, d = double, b = blob
        // start_date ve end_date NULL olabileceği için 's' kullanıyoruz,
        // bind_param NULL değerleri doğru şekilde işler.
        $stmt->bind_param("issss", $teacher_id, $title, $description, $start_date, $end_date);

        if ($stmt->execute()) {
            $success_message = "Hedef başarıyla eklendi! ✅";
        } else {
            $error_message = "Hedef eklenirken bir hata oluştu: " . $stmt->error;
            // Geliştirme aşamasında $stmt->error loglanabilir
            error_log("Goal Insert Error: " . $stmt->error);
        }
        $stmt->close();
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Hedef Ekle</title>
    <link rel="icon" href="./images/study.png">
    <style>
        /* Stil tanımlamaları önceki cevaptaki gibi kalabilir */
        body { background: linear-gradient(to right, #e3f2fd, #ffffff); font-family: 'Segoe UI', sans-serif; padding: 40px; }
        .container { max-width: 600px; margin: auto; background-color: white; padding: 30px; border-radius: 12px; box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1); }
        h2 { text-align: center; color: #333; margin-bottom: 25px; }
        label { display: block; margin-top: 15px; font-weight: bold; }
        input[type="text"], textarea, input[type="date"] { width: 100%; box-sizing: border-box; padding: 12px; margin-top: 5px; border-radius: 8px; border: 1px solid #ccc; font-size: 14px; }
        textarea { resize: vertical; min-height: 80px; }
        button { margin-top: 25px; background-color: #007bff; color: white; padding: 12px 18px; border: none; border-radius: 8px; font-size: 16px; cursor: pointer; width: 100%; }
        button:hover { background-color: #0056b3; }
        .message { padding: 15px; margin-bottom: 20px; border: 1px solid transparent; border-radius: 8px; font-weight: bold; text-align: center; }
        .success { background-color: #d4edda; border-color: #c3e6cb; color: #155724; }
        .error { background-color: #f8d7da; border-color: #f5c6cb; color: #721c24; }
        .back-link { display: block; text-align: center; margin-top: 20px; color: #007bff; text-decoration: none; font-weight: bold; }
        .back-link:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <div class="container">
        <h2>🎯 Yeni Hedef Ekle</h2>

        <?php if (!empty($success_message)): ?>
            <div class="message success"><?php echo $success_message; ?></div>
        <?php endif; ?>
        <?php if (!empty($error_message)): ?>
            <div class="message error"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <form method="POST" action="add-goal.php">
            <label for="title">Hedef Başlığı:</label>
            <input type="text" id="title" name="title" required value="<?php echo isset($_POST['title']) ? htmlspecialchars($_POST['title']) : ''; ?>">

            <label for="description">Açıklama:</label>
            <textarea id="description" name="description" rows="4"><?php echo isset($_POST['description']) ? htmlspecialchars($_POST['description']) : ''; ?></textarea>

            <label for="start_date">Başlangıç Tarihi (Opsiyonel):</label>
            <input type="date" id="start_date" name="start_date" value="<?php echo isset($_POST['start_date']) ? htmlspecialchars($_POST['start_date']) : ''; ?>">

            <label for="end_date">Bitiş Tarihi (Opsiyonel):</label>
            <input type="date" id="end_date" name="end_date" value="<?php echo isset($_POST['end_date']) ? htmlspecialchars($_POST['end_date']) : ''; ?>">

            <button type="submit">Hedefi Kaydet</button>
        </form>
         <a href="admin-panel.html" class="back-link">← Admin Paneline Geri Dön</a>
    </div>
</body>
</html>