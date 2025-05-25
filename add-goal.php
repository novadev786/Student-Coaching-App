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
    // Formdan gelen verileri al
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $start_date = !empty($_POST['start_date']) ? $_POST['start_date'] : null;
    $subjects = isset($_POST['subjects']) ? $_POST['subjects'] : [];
    $teacher_id = 1; // Admin ekliyor gibi sabitliyoruz

    // Doğrulamalar
    if (empty($title)) {
        $error_message = "Deneme sınavı adı boş bırakılamaz.";
    } elseif (empty($subjects)) {
        $error_message = "En az bir ders eklenmelidir.";
    } else {
        // Transaction başlat
        $conn->begin_transaction();

        try {
            // Hedefi ekle
            $stmt_goal = $conn->prepare("INSERT INTO goals (teacher_id, title, description, start_date) VALUES (?, ?, ?, ?)");
            $stmt_goal->bind_param("isss", $teacher_id, $title, $description, $start_date);
            
            if (!$stmt_goal->execute()) {
                throw new Exception("Hedef eklenirken hata oluştu: " . $stmt_goal->error);
            }
            
            $goal_id = $conn->insert_id;
            $stmt_goal->close();

            // Her ders için görev oluştur
            $task_order = 1;
            $stmt_task = $conn->prepare("INSERT INTO tasks (goal_id, task_order, title, subject, question_count, topics, task_type, task_date) VALUES (?, ?, ?, ?, ?, ?, 'exam_entry', ?)");
            
            foreach ($subjects as $subject) {
                $subject_name = trim($subject['name']);
                $question_count = intval($subject['question_count']);
                $topics = trim($subject['topics']);
                
                if (empty($subject_name) || $question_count <= 0) {
                    throw new Exception("Geçersiz ders bilgisi.");
                }

                $stmt_task->bind_param("iississ", $goal_id, $task_order, $subject_name, $subject_name, $question_count, $topics, $start_date);
                
                if (!$stmt_task->execute()) {
                    throw new Exception("Görev eklenirken hata oluştu: " . $stmt_task->error);
                }
                
                $task_order++;
            }
            
            $stmt_task->close();
            
            // Transaction'ı onayla
            $conn->commit();
            $success_message = "Deneme sınavı başarıyla eklendi! 🎉";
            
        } catch (Exception $e) {
            // Hata durumunda transaction'ı geri al
            $conn->rollback();
            $error_message = $e->getMessage();
            error_log("Error in add-goal.php: " . $e->getMessage());
        }
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
        <h2>🎯 Yeni Deneme Sınavı Ekle</h2>

        <?php if (!empty($success_message)): ?>
            <div class="message success"><?php echo $success_message; ?></div>
        <?php endif; ?>
        <?php if (!empty($error_message)): ?>
            <div class="message error"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <form method="POST" action="add-goal.php">
            <label for="title">Deneme Sınavı Adı:</label>
            <input type="text" id="title" name="title" required value="<?php echo isset($_POST['title']) ? htmlspecialchars($_POST['title']) : ''; ?>">

            <label for="description">Açıklama:</label>
            <textarea id="description" name="description" rows="4"><?php echo isset($_POST['description']) ? htmlspecialchars($_POST['description']) : ''; ?></textarea>

            <div id="subjects-container">
                <h3>Dersler</h3>
                <div class="subject-entry">
                    <div style="display: flex; gap: 10px; margin-bottom: 10px;">
                        <div style="flex: 1;">
                            <label for="subject_name_1">Ders Adı:</label>
                            <input type="text" id="subject_name_1" name="subjects[1][name]" required>
                        </div>
                        <div style="flex: 1;">
                            <label for="subject_question_count_1">Soru Sayısı:</label>
                            <input type="number" id="subject_question_count_1" name="subjects[1][question_count]" min="1" required>
                        </div>
                        <div style="flex: 1;">
                            <label for="subject_topics_1">Konu(lar):</label>
                            <input type="text" id="subject_topics_1" name="subjects[1][topics]" placeholder="Virgülle ayırın (örn: Üslü Sayılar, Kümeler)" required>
                        </div>
                    </div>
                </div>
            </div>

            <button type="button" onclick="addSubjectField()" style="background-color: #007bff; margin-top: 10px;">+ Ders Ekle</button>

            <label for="start_date">Sınav Tarihi:</label>
            <input type="date" id="start_date" name="start_date" required value="<?php echo isset($_POST['start_date']) ? htmlspecialchars($_POST['start_date']) : ''; ?>">

            <button type="submit" style="margin-top: 20px;">Deneme Sınavını Kaydet</button>
        </form>

        <script>
            let subjectCount = 6;
            const defaultSubjects = [
                {name: 'Türkçe', question_count: 20},
                {name: 'Matematik', question_count: 20},
                {name: 'Fen', question_count: 20},
                {name: 'İnkılap', question_count: 10},
                {name: 'İngilizce', question_count: 10},
                {name: 'Din', question_count: 10}
            ];
            window.addEventListener('DOMContentLoaded', () => {
                const container = document.getElementById('subjects-container');
                container.innerHTML = '<h3>Dersler</h3>';
                defaultSubjects.forEach((subj, i) => {
                    const idx = i+1;
                    container.innerHTML += `
                    <div class="subject-entry">
                        <div style="display: flex; gap: 10px; margin-bottom: 10px;">
                            <div style="flex: 1;">
                                <label for="subject_name_${idx}">Ders Adı:</label>
                                <input type="text" id="subject_name_${idx}" name="subjects[${idx}][name]" value="${subj.name}" required>
                            </div>
                            <div style="flex: 1;">
                                <label for="subject_question_count_${idx}">Soru Sayısı:</label>
                                <input type="number" id="subject_question_count_${idx}" name="subjects[${idx}][question_count]" min="1" value="${subj.question_count}" required>
                            </div>
                            <div style="flex: 1;">
                                <label for="subject_topics_${idx}">Konu(lar):</label>
                                <input type="text" id="subject_topics_${idx}" name="subjects[${idx}][topics]" placeholder="Virgülle ayırın (örn: Paragraf, Cümle)" required>
                            </div>
                            <div style="display: flex; align-items: flex-end; margin-bottom: 5px;">
                                <button type="button" onclick="this.parentElement.parentElement.parentElement.remove()" style="background-color: #dc3545; padding: 8px;">Sil</button>
                            </div>
                        </div>
                    </div>`;
                });
            });
        </script>

        <a href="admin-panel.html" class="back-link">← Admin Paneline Geri Dön</a>
    </div>
</body>
</html>