<?php

$conn = new mysqli("localhost", "root", "", "student_db");
if ($conn->connect_error) {
    error_log("Database Connection Error: " . $conn->connect_error);
    die("Veritabanı bağlantısında bir sorun oluştu.");
}
$conn->set_charset("utf8mb4");

$goal_id_from_url = isset($_GET['goal_id']) ? intval($_GET['goal_id']) : 0; 
$goal_title = ''; 
$success_message = '';
$error_message = '';


if ($goal_id_from_url > 0) {
    $stmt_goal = $conn->prepare("SELECT title FROM goals WHERE id = ?");
    $stmt_goal->bind_param("i", $goal_id_from_url);
    $stmt_goal->execute();
    $result_goal = $stmt_goal->get_result();
    if ($result_goal->num_rows > 0) {
        $goal_row = $result_goal->fetch_assoc();
        $goal_title = $goal_row['title'];
    } else {
        
        $error_message = "Geçersiz hedef ID'si.";
        $goal_id_from_url = 0; 
    }
    $stmt_goal->close();
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $goal_id = isset($_POST['goal_id']) ? intval($_POST['goal_id']) : 0;
    $task_order = isset($_POST['task_order']) ? intval($_POST['task_order']) : 1; 
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $subject = trim($_POST['subject']);
    $topic = trim($_POST['topic']);
    $question_count = !empty($_POST['question_count']) ? intval($_POST['question_count']) : null;
    $task_date = !empty($_POST['task_date']) ? $_POST['task_date'] : null;
    $task_type = trim($_POST['task_type']);

 
    if (empty($title)) {
        $error_message = "Görev başlığı boş bırakılamaz.";
    } elseif ($goal_id <= 0) {
        $error_message = "Geçerli bir hedef seçilmelidir.";
    } elseif (empty($task_type)) {
        $error_message = "Görev türü seçilmelidir.";
    } else {
        
        $stmt = $conn->prepare("INSERT INTO tasks (goal_id, task_order, title, description, subject, topic, question_count, task_date, task_type, is_completed) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 0)");
        
        $stmt->bind_param("iissssiss", $goal_id, $task_order, $title, $description, $subject, $topic, $question_count, $task_date, $task_type);

        if ($stmt->execute()) {
            $success_message = "Görev başarıyla eklendi! 🎉";
            
             $_POST = array();
             
             $goal_id_from_url = $goal_id;
            
        } else {
            $error_message = "Görev eklenirken bir hata oluştu: " . $stmt->error;
            error_log("Task Insert Error: " . $stmt->error);
        }
        $stmt->close();
    }
}


$all_goals = [];
if ($goal_id_from_url <= 0 && $_SERVER["REQUEST_METHOD"] != "POST") {
     $result_all_goals = $conn->query("SELECT id, title FROM goals ORDER BY title ASC");
     if ($result_all_goals) {
         while ($row = $result_all_goals->fetch_assoc()) {
             $all_goals[] = $row;
         }
     }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Görev Ekle</title>
    <link rel="icon" href="./images/study.png">
    <style>
      
        body { background: linear-gradient(to right, #f0f2f5, #cce1f0); font-family: 'Segoe UI', sans-serif; padding: 40px; }
        .container { max-width: 600px; margin: auto; background-color: white; padding: 30px; border-radius: 12px; box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1); }
        h2 { text-align: center; color: #333; margin-bottom: 10px; }
        .goal-subtitle { text-align: center; color: #007bff; margin-bottom: 25px; font-weight: bold;}
        label { display: block; margin-top: 15px; font-weight: bold; }
        input[type="text"], input[type="number"], textarea, input[type="date"], select { width: 100%; box-sizing: border-box; padding: 12px; margin-top: 5px; border-radius: 8px; border: 1px solid #ccc; font-size: 14px; }
        textarea { resize: vertical; min-height: 70px; }
        button { margin-top: 25px; background-color: #28a745; color: white; padding: 12px 18px; border: none; border-radius: 8px; font-size: 16px; cursor: pointer; width: 100%; }
        button:hover { background-color: #218838; }
        .message { padding: 15px; margin-bottom: 20px; border: 1px solid transparent; border-radius: 8px; font-weight: bold; text-align: center; }
        .success { background-color: #d4edda; border-color: #c3e6cb; color: #155724; }
        .error { background-color: #f8d7da; border-color: #f5c6cb; color: #721c24; }
        .back-link { display: block; text-align: center; margin-top: 20px; color: #007bff; text-decoration: none; font-weight: bold; }
        .back-link:hover { text-decoration: underline; }
        select[readonly] { background-color: #e9ecef; pointer-events: none; touch-action: none; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Yeni Görev Ekle</h2>
        <?php if (!empty($goal_title)): ?>
            <div class="goal-subtitle">"<?php echo htmlspecialchars($goal_title); ?>" Hedefi İçin</div>
        <?php endif; ?>

        <?php if (!empty($success_message)): ?>
            <div class="message success"><?php echo $success_message; ?></div>
        <?php endif; ?>
        <?php if (!empty($error_message)): ?>
            <div class="message error"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <?php?>
        <?php if ($goal_id_from_url > 0 || count($all_goals) > 0 || $_SERVER["REQUEST_METHOD"] == "POST"): ?>
            <form method="POST" action="add-task.php<?php echo $goal_id_from_url > 0 ? '?goal_id='.$goal_id_from_url : ''; ?>">

                <?php  ?>
                <?php if ($goal_id_from_url <= 0): ?>
                    <label for="goal_id">Hedef Seç:</label>
                    <select id="goal_id" name="goal_id" required>
                        <option value="">-- Lütfen Hedef Seçin --</option>
                        <?php foreach ($all_goals as $goal): ?>
                            <option value="<?php echo $goal['id']; ?>" <?php echo (isset($_POST['goal_id']) && $_POST['goal_id'] == $goal['id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($goal['title']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                <?php else: ?>
                    <?php  ?>
                     <label for="goal_id_display">Hedef:</label>
                     <select id="goal_id_display" readonly disabled>
                         <option><?php echo htmlspecialchars($goal_title); ?></option>
                     </select>
                    <input type="hidden" name="goal_id" value="<?php echo $goal_id_from_url; ?>">
                <?php endif; ?>

                <label for="task_order">Hedefteki Sırası (örn: 1. Gün için 1):</label>
                <input type="number" id="task_order" name="task_order" min="1" value="<?php echo isset($_POST['task_order']) ? htmlspecialchars($_POST['task_order']) : '1'; ?>" required>

                <label for="title">Görev Başlığı:</label>
                <input type="text" id="title" name="title" required value="<?php echo isset($_POST['title']) ? htmlspecialchars($_POST['title']) : ''; ?>">

                <label for="description">Açıklama (Opsiyonel):</label>
                <textarea id="description" name="description" rows="3"><?php echo isset($_POST['description']) ? htmlspecialchars($_POST['description']) : ''; ?></textarea>

                <label for="subject">Ders (Opsiyonel):</label>
                <input type="text" id="subject" name="subject" value="<?php echo isset($_POST['subject']) ? htmlspecialchars($_POST['subject']) : ''; ?>">

                <label for="topic">Konu (Opsiyonel):</label>
                <input type="text" id="topic" name="topic" value="<?php echo isset($_POST['topic']) ? htmlspecialchars($_POST['topic']) : ''; ?>">

                <label for="question_count">Soru Sayısı Hedefi (Opsiyonel):</label>
                <input type="number" id="question_count" name="question_count" min="0" value="<?php echo isset($_POST['question_count']) ? htmlspecialchars($_POST['question_count']) : ''; ?>">

                <label for="task_type">Görev Türü:</label>
                <select id="task_type" name="task_type" required>
                    <option value="">-- Görev Türü Seçin --</option>
                    <option value="exam_entry" <?php echo (isset($_POST['task_type']) && $_POST['task_type'] == 'exam_entry') ? 'selected' : ''; ?>>Deneme</option>
                    <option value="general" <?php echo (isset($_POST['task_type']) && $_POST['task_type'] == 'general') ? 'selected' : ''; ?>>Soru Çözümü</option>
                </select>

                <label for="task_date">Görevin Tarihi (Opsiyonel):</label>
                <input type="date" id="task_date" name="task_date" value="<?php echo isset($_POST['task_date']) ? htmlspecialchars($_POST['task_date']) : ''; ?>">

                <button type="submit">Görevi Kaydet</button>
            </form>
         <?php else: ?>
             <?php if (empty($error_message)) { ?>
                 <p class="message error">Önce bir hedef oluşturmalı veya seçmelisiniz.</p>
             <?php } ?>
         <?php endif; ?>

        <a href="list-goals.php" class="back-link">← Hedef Listesine Geri Dön</a>
        <a href="admin-panel.html" class="back-link">← Admin Paneline Geri Dön</a>
    </div>
</body>
</html>