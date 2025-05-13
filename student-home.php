<?php
session_start(); // Oturumu başlat

// Oturum ve rol kontrolü
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] != 'student') {
    // Eğer oturum yoksa veya rol öğrenci değilse login sayfasına yönlendir
    header("Location: login.php");
    exit(); // Scriptin çalışmasını durdur
}

// Oturumdan öğrenci adını alalım (varsa, yoksa varsayılan bir değer kullan)
$student_name = isset($_SESSION['user_name']) ? htmlspecialchars($_SESSION['user_name']) : 'Öğrenci';

?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    
    <title>Öğrenci Anasayfası</title>
    <!-- Ana stil dosyası (style.css varsa) -->
    <link rel="stylesheet" href="style.css">
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="./images/study.png">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Sayfa içi stiller -->
    <style>
       
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f7fa; 
            color: #333; 
            min-height: 100vh;
            display: flex; /
            flex-direction: column; 
        }
        .page-wrapper { 
            flex: 1;
        }

        
        header {
            background: linear-gradient(to right, #007bff, #0056b3); 
            color: white;
            padding: 18px 40px;
            text-align: center;
            position: relative;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        header h1 {
             margin: 0;
             font-size: 1.7em;
             font-weight: 600; 
        }
        .logout-btn {
            position: absolute;
            top: 50%;
            right: 30px; 
            transform: translateY(-50%);
            background-color: rgba(255, 255, 255, 0.9); 
            color: #0056b3; 
            border: none;
            padding: 9px 15px; 
            border-radius: 20px; 
            cursor: pointer;
            font-weight: bold;
            transition: background-color 0.3s ease, color 0.3s ease, box-shadow 0.3s ease;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        .logout-btn:hover {
            background-color: #dc3545; 
            color: white;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        }

        
        .content-wrapper {
            max-width: 1100px; 
            margin: 40px auto;
            padding: 35px; 
            background-color: white; 
            border-radius: 15px; 
            box-shadow: 0 8px 25px rgba(0,0,0,0.08); 
        }
        .content-wrapper h2 {
            color: #0056b3;
            text-align: center;
            margin-bottom: 25px;
            font-weight: 600;
        }
         .content-wrapper p.welcome-text { 
             text-align: center;
             margin-bottom: 35px;
             color: #555;
             font-size: 1.1em;
         }

    
        .cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); 
            gap: 30px; 
            margin-top: 30px;
        }
        .card {
            background-color: #fff; 
            border-radius: 12px;
            padding: 30px; 
            box-shadow: 0 5px 15px rgba(0,0,0,0.06);
            cursor: pointer;
            transition: transform 0.25s ease, box-shadow 0.25s ease;
            text-align: center;
            border: 1px solid #e9ecef; 
            display: flex; 
            flex-direction: column;
            justify-content: center;
            align-items: center;
            min-height: 150px; 
        }
        .card:hover {
            transform: translateY(-6px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        .card h3 {
            margin-top: 15px; 
            margin-bottom: 8px;
            color: #007bff; 
            font-size: 1.2em;
            font-weight: 600;
        }
        .card p { 
            color: #666;
            font-size: 0.95em;
            line-height: 1.5;
            margin-bottom: 0; 
        }
         
         .card i {
             font-size: 2.5em; 
             color: #007bff;
             margin-bottom: 15px;
         }

       
        .popup { 
            position: fixed;
            top: 0;
            left: 0;
            width: 100%; 
            height: 100%; 
            background-color: rgba(0, 0, 0, 0.65); 
            display: none; 
            justify-content: center;
            align-items: center;
            z-index: 1050; 
            padding: 20px; 
            box-sizing: border-box; 
        }

.popup-content {
    background-color: white;
    padding: 30px;
    border-radius: 12px;
    max-width: 750px; 
    width: 95%;
    max-height: 90vh; 
    overflow-y: auto; 
    position: relative;
    color: #333;
    box-shadow: 0 5px 20px rgba(0,0,0,0.2);
    display: flex;
    flex-direction: column;
}
         .popup-content h3 { 
             margin-top: 0;
             margin-bottom: 25px;
             color: #0056b3;
             text-align: center;
             border-bottom: 1px solid #eee;
             padding-bottom: 15px;
         }
        .popup .close-btn { 
            position: absolute;
            top: 15px; 
            right: 15px;
            background-color: #dc3545; 
            color: white;
            border: none;
            width: 30px; 
            height: 30px;
            border-radius: 50%; 
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
            line-height: 30px; 
            text-align: center;
            transition: background-color 0.2s ease;
        }
        .popup .close-btn:hover {
            background-color: #c82333;
        }

       
        #studentTaskList {
            padding: 0;
            margin: 0; 
        }
        #studentTaskList li {
            list-style-type: none; 
            margin-bottom: 15px; 
        }
        #studentTaskList h4 { 
             margin-top: 20px;
             margin-bottom: 15px;
             padding-bottom: 8px;
             border-bottom: 2px solid #007bff; 
             color: #0056b3;
             font-size: 1.2em;
         }
         #studentTaskList li:first-child h4 { 
             margin-top: 0;
         }
        .task-box {
            border: 1px solid #ddd;
            padding: 15px 20px;
            border-radius: 8px;
            background-color: #fff;
            transition: background-color 0.3s ease, opacity 0.3s ease, box-shadow 0.3s ease;
            position: relative; 
             box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
         .task-box strong.task-title {
             font-size: 1.1em;
             color: #333;
             display: block; 
             margin-bottom: 5px;
         }
         .task-box small.task-desc {
             display: block;
             margin: 8px 0;
             color: #555;
             font-size: 0.9em;
             line-height: 1.5;
         }
         .task-box .task-details {
             font-size: 0.85em; 
             color: #666;
             margin-top: 12px;
             padding-top: 10px;
             border-top: 1px dashed #eee; 
             display: flex; 
             flex-wrap: wrap; 
             gap: 15px; 
         }
          .task-box .task-details span {
             white-space: nowrap; 
         }
          .task-box .task-details strong {
             color: #444;
         }

         
         button.toggle-done-btn {
            margin-top: 15px;
            padding: 6px 12px; 
            cursor: pointer;
            border-radius: 5px;
            font-size: 0.9em; 
            font-weight: bold;
            transition: all 0.2s ease;
         }
         
         button.toggle-done-btn:not([style*="background-color: rgb(255, 255, 255)"]) { 
             border: 1px solid #28a745;
             background-color: #28a745;
             color: white;
         }
         button.toggle-done-btn:not([style*="background-color: rgb(255, 255, 255)"]):hover {
            background-color: #218838;
            border-color: #1e7e34;
         }
          button.toggle-done-btn[style*="background-color: rgb(255, 255, 255)"] { 
             border: 1px solid #dc3545;
             background-color: #fff;
             color: #dc3545;
         }
           button.toggle-done-btn[style*="background-color: rgb(255, 255, 255)"]:hover {
             background-color: #f8d7da; 
         }


      
         .task-box[style*="opacity: 0.7"] {
             background-color: #f8f9fa; 
         }
         .task-box [style*="text-decoration: line-through"] {
             color: #888 !important; 
         }


    </style>
</head>
<body>

    <div class="page-wrapper"> 
        <header>
            <h1>👋 Hoş Geldin, <?php echo $student_name; ?>!</h1>
  
            <a href="logout.php" class="logout-btn">Çıkış Yap</a>
        </header>

        <div class="content-wrapper">
            <h2>Öğrenci Paneli</h2>
            <p class="welcome-text">Sana özel içerikler ve görevler burada yer alıyor.</p>

            <div class="cards">
                <div class="card" onclick="openTaskPopup()">
                  
                   <i class="fas fa-tasks"></i> 
                    <h3>📅 Görevlerim</h3>
                    <p>Sana atanan hedefleri ve günlük görevlerini görüntüle.</p>
                </div>
                <div class="card" onclick="alert('Bu özellik yakında eklenecek!');">
                   <i class="fas fa-chart-line"></i> 
                    <h3>📊 İstatistiklerim</h3>
                    <p>Görev tamamlama ve performans analizlerini incele.</p>
                </div>
                 <div class="card" onclick="alert('Bu özellik yakında eklenecek!');">
                    <i class="fas fa-book-open"></i> 
                    <h3>📚 Derslerim</h3>
                    <p>Seçtiğin veya kayıtlı olduğun dersleri yönet.</p>
                 </div>
                 <div class="card" onclick="alert('Bu özellik yakında eklenecek!');">
                    <i class="fas fa-bullhorn"></i> 
                    <h3>📢 Duyurular</h3>
                    <p>Öğretmenlerinden ve yöneticilerden gelen duyurular.</p>
                 </div>
            </div>

        </div> 

    </div> 


   
    <div class="popup" id="studentTaskPopup">
        <div class="popup-content">
            <button class="close-btn" onclick="closeTaskPopup()">X</button>
            <h3>📋 Görev Listem</h3>
            <ul id="studentTaskList">
             
                <li>Yükleniyor...</li>
            </ul>
        </div>
    </div>
    <!-- student-home.php içinde, mevcut popup'lardan sonra -->

<!-- Sınav Sonuç Giriş Popup'ı -->
<!-- student-home.php içinde, #examEntryPopup div'ini güncelle -->
<div class="popup" id="examEntryPopup" style="align-items: flex-start; padding-top: 5vh;"> 
    <div class="popup-content" style="max-width: 700px; display: flex; flex-direction: column;"> 
        <button class="close-btn" onclick="closeExamEntryPopup()">X</button>
        <h3 id="examEntryPopupTitle">Sınav Sonucu Gir</h3>

        <div style="display: flex; gap: 20px;"> 
           
            <div style="flex: 1;">
                <form id="examEntryForm_new"> 
                    <input type="hidden" id="examEntryTaskId_new" name="task_id_new">

                    <div>
                        <label for="examName_new">Genel Sınav Adı (Opsiyonel):</label>
                        <input type="text" id="examName_new" name="exam_name_new" style="width: 95%; padding: 8px; margin-bottom: 10px; border: 1px solid #ccc; border-radius: 4px;">
                    </div>
                    <hr style="margin: 15px 0;">
                    <h4>Ders Ekle:</h4>
                    <div>
                        <label for="subjectName_new">Ders Adı:</label>
                        <input type="text" id="subjectName_new" name="subject_name_new" style="width: 95%; padding: 8px; margin-bottom: 10px; border: 1px solid #ccc; border-radius: 4px;">
                    </div>
                    <div>
                        <label for="correctCount_new">Doğru Sayısı:</label>
                        <input type="number" id="correctCount_new" name="correct_count_new" min="0" value="0" style="width: 95%; padding: 8px; margin-bottom: 10px; border: 1px solid #ccc; border-radius: 4px;">
                    </div>
                    <div>
                        <label for="wrongCount_new">Yanlış Sayısı:</label>
                        <input type="number" id="wrongCount_new" name="wrong_count_new" min="0" value="0" style="width: 95%; padding: 8px; margin-bottom: 10px; border: 1px solid #ccc; border-radius: 4px;">
                    </div>
                    <div>
                        <label for="blankCount_new">Boş Sayısı:</label>
                        <input type="number" id="blankCount_new" name="blank_count_new" min="0" value="0" style="width: 95%; padding: 8px; margin-bottom: 10px; border: 1px solid #ccc; border-radius: 4px;">
                    </div>
                    <button type="button" onclick="addSubjectResult()" style="padding: 8px 15px; background-color: #007bff; color: white; border: none; border-radius: 5px; cursor: pointer;">Bu Dersi Listeye Ekle</button>
                </form>
            </div>

            
<div style="flex: 1.5; display: flex; flex-direction: column; min-height: 350px; /* Popup'ın bu bölümü için minimum bir yükseklik */">
     <h4>Eklenen Ders Sonuçları:</h4>
     <ul id="subjectResultList" style="list-style: none; padding: 0; margin-bottom: 15px; /* Boşluğu ayarla */ max-height: 150px; /* Liste için maksimum yükseklik */ overflow-y: auto; border: 1px solid #eee; padding:10px;">
         <li class="no-subjects">Henüz ders eklenmedi.</li>
     </ul>

     <div style="flex-grow: 1; position: relative; min-height: 200px; /* Grafiğin minimum yüksekliği */">
        <canvas id="examNetChart" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%;"></canvas>
     </div>
</div>

        <div style="text-align: center; margin-top: 25px; border-top: 1px solid #eee; padding-top: 15px;">
            <button type="button" onclick="saveAllSubjectResults()" style="padding: 12px 25px; background-color: #28a745; color: white; border: none; border-radius: 5px; cursor: pointer; font-size: 1.1em;">Tüm Sonuçları Kaydet</button>
        </div>
        <div id="examEntryMessage_new" style="margin-top: 15px; text-align: center; font-weight: bold;"></div>
    </div>
</div>

   
    <script>
     
        function openTaskPopup() {
            loadStudentTasks();
            document.getElementById("studentTaskPopup").style.display = "flex";
        }

        function closeTaskPopup() {
            document.getElementById("studentTaskPopup").style.display = "none";
        }

        function loadStudentTasks() {
            const listContainer = document.getElementById("studentTaskList");
            listContainer.innerHTML = "<li>Görevler yükleniyor...</li>";
            fetch('get_student_tasks.php')
                .then(res => {
                    if (!res.ok) { throw new Error(`HTTP error! status: ${res.status}`); }
                    return res.json();
                 })
                .then(data => {
                    listContainer.innerHTML = "";
                    if (data.error) {
                        listContainer.innerHTML = `<li>Hata: ${data.error}</li>`;
                        console.error("Görevleri alırken sunucu hatası:", data.error);
                        return;
                    }
                    if (!data.tasks || data.tasks.length === 0) {
                        listContainer.innerHTML = "<li>Henüz size atanmış bir görev bulunmamaktadır.</li>";
                        return;
                    }
                    const tasksByGoal = data.tasks.reduce((acc, task) => {
                        const goalId = task.goal_id;
                        if (!acc[goalId]) {
                            acc[goalId] = { goalTitle: task.goal_title || 'Genel Görevler', tasks: [] };
                        }
                        acc[goalId].tasks.push(task);
                        return acc;
                    }, {});
                    for (const goalId in tasksByGoal) {
                        const goalData = tasksByGoal[goalId];
                        const goalHeader = document.createElement('h4');
                        goalHeader.textContent = `🎯 Hedef: ${goalData.goalTitle}`;
                        listContainer.appendChild(goalHeader);
                        goalData.tasks.forEach(task => {
                            const li = document.createElement("li");
                            const isDone = task.is_done;
                            const taskBox = document.createElement('div');
                            taskBox.className = 'task-box';
                            taskBox.dataset.taskId = task.task_id;
                            taskBox.style.backgroundColor = isDone ? '#f8f9fa' : '#fff';
                            taskBox.style.opacity = isDone ? 0.7 : 1;
                            taskBox.innerHTML = `
                                <strong class="task-title" style="text-decoration: ${isDone ? 'line-through' : 'none'};">
                                    ${task.task_order || ''}${task.task_order ? '. ' : ''}${task.task_title || 'Başlıksız Görev'}
                                </strong>
                                ${task.task_description ? `<small class="task-desc" style="text-decoration: ${isDone ? 'line-through' : 'none'};">${task.task_description}</small>` : ''}
                                <div class="task-details" style="text-decoration: ${isDone ? 'line-through' : 'none'};">
                                    ${task.subject ? `<span><strong>Ders:</strong> ${task.subject}</span>` : ''}
                                    ${task.topic ? `<span><strong>Konu:</strong> ${task.topic}</span>` : ''}
                                    ${task.question_count != null ? `<span><strong>Soru:</strong> ${task.question_count}</span>` : ''}
                                    ${task.task_date ? `<span><strong>Tarih:</strong> ${task.task_date_formatted || task.task_date}</span>` : ''}
                                    ${task.task_type ? `<span style="color: ${task.task_type === 'exam_entry' ? '#007bff' : 'inherit'};"><strong>Tür:</strong> ${task.task_type === 'exam_entry' ? 'Sınav Sonuç Girişi' : 'Genel'}</span>` : ''}
                                </div>
                                <button class="toggle-done-btn"
                                        onclick="toggleDone(${task.task_id}, this)"
                                        style="border: 1px solid ${isDone ? '#dc3545' : '#28a745'}; background-color: ${isDone ? '#fff' : '#28a745'}; color: ${isDone ? '#dc3545' : '#fff'};">
                                    ${isDone ? '↩️ Geri Al' : '✔️ Tamamladım'}
                                </button>
                            `;
                            if (task.task_type === 'exam_entry' && !isDone) {
                                const examEntryBtn = document.createElement('button');
                                examEntryBtn.textContent = '📝 Sınav Sonucu Gir';
                                examEntryBtn.className = 'exam-entry-btn';
                                examEntryBtn.style.marginLeft = '10px';
                                examEntryBtn.style.padding = '6px 12px';
                                examEntryBtn.style.cursor = 'pointer';
                                examEntryBtn.style.borderRadius = '5px';
                                examEntryBtn.style.border = '1px solid #007bff';
                                examEntryBtn.style.backgroundColor = '#007bff';
                                examEntryBtn.style.color = 'white';
                                examEntryBtn.style.transition = 'background-color 0.2s ease';
                                examEntryBtn.onmouseover = function() { this.style.backgroundColor = '#0056b3'; };
                                examEntryBtn.onmouseout = function() { this.style.backgroundColor = '#007bff'; };
                                examEntryBtn.onclick = function() {
                                    openExamEntryPopup(task.task_id, task.task_title);
                                };
                                taskBox.appendChild(examEntryBtn);
                            }
                            li.appendChild(taskBox);
                            listContainer.appendChild(li);
                        });
                    }
                })
                .catch(err => {
                    listContainer.innerHTML = `<li>Görevler yüklenirken bir hata oluştu. Lütfen tekrar deneyin veya sayfayı yenileyin.</li>`;
                    console.error("Görevleri fetch ederken hata:", err);
                });
        }

     
         function toggleDone(taskId, btn) {
            const taskBox = btn.closest(".task-box");
            const isCurrentlyDone = btn.textContent.includes('Geri Al');
            const newStatus = isCurrentlyDone ? 0 : 1;
            btn.disabled = true;
            btn.textContent = 'İşleniyor...';
            fetch('update_student_task_status.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ task_id: taskId, is_completed: newStatus })
            })
            .then(res => {
                 if (!res.ok) { throw new Error(`HTTP error! status: ${res.status}`); }
                 return res.json()
            })
            .then(data => {
                if (data.success) {
                    const isNowDone = newStatus === 1;
                    taskBox.style.backgroundColor = isNowDone ? '#f8f9fa' : '#fff';
                    taskBox.style.opacity = isNowDone ? 0.7 : 1;
                    taskBox.querySelectorAll('.task-title, .task-desc, .task-details').forEach(el => {
                        el.style.textDecoration = isNowDone ? 'line-through' : 'none';
                    });
                    btn.textContent = isNowDone ? '↩️ Geri Al' : '✔️ Tamamladım';
                    btn.style.border = `1px solid ${isNowDone ? '#dc3545' : '#28a745'}`;
                    btn.style.backgroundColor = isNowDone ? '#fff' : '#28a745';
                    btn.style.color = isNowDone ? '#dc3545' : '#fff';
                } else {
                    alert("Görev durumu güncellenemedi: " + (data.message || 'Bilinmeyen bir sunucu hatası.'));
                    btn.textContent = isCurrentlyDone ? '↩️ Geri Al' : '✔️ Tamamladım';
                }
            })
            .catch((error) => {
                console.error("Toggle Done Error:", error);
                alert("Görev durumu güncellenirken bir hata oluştu: " + error.message);
                btn.textContent = isCurrentlyDone ? '↩️ Geri Al' : '✔️ Tamamladım';
            })
            .finally(() => {
                btn.disabled = false;
            });
        }

       
        let currentExamSubjects = []; 
        let examNetChartInstance = null; 

        function openExamEntryPopup(taskId, taskTitle) {
            console.log("openExamEntryPopup çağrıldı. Task ID:", taskId, "Başlık:", taskTitle);
            document.getElementById('examEntryTaskId_new').value = taskId; // ID'Yİ _new İLE AL
            document.getElementById('examEntryPopupTitle').textContent = `Sonuç Gir: ${taskTitle}`;
            document.getElementById('examEntryForm_new').reset(); // _new ID'Lİ FORMU TEMİZLE
            document.getElementById('examEntryMessage_new').textContent = ''; // _new ID'Lİ MESAJI TEMİZLE
            document.getElementById('subjectResultList').innerHTML = '<li class="no-subjects">Henüz ders eklenmedi.</li>'; // Ders listesini temizle
            currentExamSubjects = []; // Geçici ders listesini sıfırla

            // Grafik varsa yok et
            if (examNetChartInstance) {
                examNetChartInstance.destroy();
                examNetChartInstance = null;
            }
            document.getElementById('examNetChart').style.display = 'none'; // Başlangıçta grafiği gizle

            document.getElementById('examEntryPopup').style.display = 'flex';
            console.log("Popup gösterilmeye çalışılıyor.");

            // Bu görev için daha önce girilmiş ders bazlı sonuçları yükle (henüz PHP scripti yok)
            // loadPreviousSubjectResults(taskId); // ŞİMDİLİK BU FONKSİYON YORUMDA KALSIN
        }

        function closeExamEntryPopup() {
            document.getElementById('examEntryPopup').style.display = 'none';
        }

        function addSubjectResult() {
            const subjectName = document.getElementById('subjectName_new').value.trim();
            const correct = parseInt(document.getElementById('correctCount_new').value) || 0;
            const wrong = parseInt(document.getElementById('wrongCount_new').value) || 0;
            const blank = parseInt(document.getElementById('blankCount_new').value) || 0;

            if (!subjectName) {
                alert("Lütfen ders adını girin.");
                return;
            }
            if (currentExamSubjects.find(s => s.name === subjectName)) {
                alert("Bu ders zaten listeye eklendi. Düzenlemek için önce listeden kaldırın.");
                return;
            }

            currentExamSubjects.push({ name: subjectName, correct: correct, wrong: wrong, blank: blank });
            renderSubjectResultList();
            generateExamNetChart(); // Her ders eklendiğinde grafiği güncelle

            // Formu temizle (ders ekleme kısmı için)
            document.getElementById('subjectName_new').value = '';
            document.getElementById('correctCount_new').value = '0';
            document.getElementById('wrongCount_new').value = '0';
            document.getElementById('blankCount_new').value = '0';
            document.getElementById('subjectName_new').focus();
        }

        function renderSubjectResultList() {
            const listElement = document.getElementById('subjectResultList');
            listElement.innerHTML = ''; // Listeyi temizle

            if (currentExamSubjects.length === 0) {
                listElement.innerHTML = '<li class="no-subjects">Henüz ders eklenmedi.</li>';
                return;
            }

            currentExamSubjects.forEach((subject, index) => {
                const net = subject.correct - (subject.wrong / 4.0);
                const li = document.createElement('li');
                li.style.display = 'flex';
                li.style.justifyContent = 'space-between';
                li.style.alignItems = 'center';
                li.style.padding = '8px 0';
                li.style.borderBottom = '1px solid #f0f0f0';
                li.innerHTML = `
                    <span><strong>${subject.name}:</strong> D:${subject.correct}, Y:${subject.wrong}, B:${subject.blank} (Net: ${net.toFixed(2)})</span>
                    <button onclick="removeSubjectResult(${index})" style="background: #ff4d4d; color: white; border: none; padding: 3px 7px; border-radius: 3px; cursor: pointer;">Kaldır</button>
                `;
                listElement.appendChild(li);
            });
        }

        function removeSubjectResult(indexToRemove) {
            currentExamSubjects.splice(indexToRemove, 1); // İlgili indeksteki dersi sil
            renderSubjectResultList();
            generateExamNetChart(); // Listeden ders silindiğinde grafiği güncelle
        }

        function generateExamNetChart() {
            const canvas = document.getElementById('examNetChart');
            if (currentExamSubjects.length === 0) {
                canvas.style.display = 'none'; // Ders yoksa grafiği gizle
                if (examNetChartInstance) {
                    examNetChartInstance.destroy();
                    examNetChartInstance = null;
                }
                return;
            }
            canvas.style.display = 'block'; // Ders varsa grafiği göster

            const ctx = canvas.getContext('2d');
            if (examNetChartInstance) {
                examNetChartInstance.destroy(); // Önceki grafiği yok et
            }

            const labels = currentExamSubjects.map(s => s.name);
            const netScores = currentExamSubjects.map(s => s.correct - (s.wrong / 4.0));
            // Basit bir renk paleti
            const backgroundColors = ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40', '#C9CBCF'];

            examNetChartInstance = new Chart(ctx, {
                type: 'bar', // Çubuk grafik veya 'pie' (pasta) olabilir
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Ders Bazlı Netler',
                        data: netScores,
                        backgroundColor: labels.map((_, i) => backgroundColors[i % backgroundColors.length]),
                        borderColor: labels.map((_, i) => backgroundColors[i % backgroundColors.length]),
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false, // Boyutu daha iyi kontrol etmek için
                    scales: {
                        y: {
                            beginAtZero: true, // Y ekseni sıfırdan başlasın
                            // İsteğe bağlı: Maksimum nete göre ayarlanabilir
                            // suggestedMax: Math.max(...netScores, 0) + 5
                        }
                    },
                    plugins: {
                        legend: {
                            display: true, // Etiketi göster
                            position: 'top',
                        },
                        title: {
                            display: true,
                            text: 'Ders Bazlı Net Dağılımı'
                        }
                    }
                }
            });
        }

        function saveAllSubjectResults() {
            const taskId = document.getElementById('examEntryTaskId_new').value;
            const examName = document.getElementById('examName_new').value.trim();
            const messageDiv = document.getElementById('examEntryMessage_new');

            if (currentExamSubjects.length === 0) {
                alert("Kaydedilecek ders sonucu bulunmamaktadır. Lütfen önce ders ekleyin.");
                return;
            }

            messageDiv.textContent = 'Kaydediliyor...';
            messageDiv.style.color = 'orange';

            // Her bir ders sonucunu PHP'ye gönderecek şekilde hazırla
            const dataToSend = {
                task_id: taskId,
                exam_name: examName,
                subjects: currentExamSubjects // {name, correct, wrong, blank} objelerinden oluşan dizi
            };

            fetch('save_student_exam_subjects.php', { // YENİ PHP DOSYASI OLUŞTURULACAK
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(dataToSend)
            })
            .then(res => {
                if (!res.ok) { throw new Error('Sunucu hatası: ' + res.status); }
                return res.json();
            })
            .then(data => {
                if (data.success) {
                    messageDiv.textContent = data.message || 'Tüm sınav sonuçları başarıyla kaydedildi!';
                    messageDiv.style.color = 'green';
                    // İsteğe bağlı: Formu sıfırla, listeyi temizle, grafiği temizle
                    // document.getElementById('examEntryForm_new').reset();
                    // currentExamSubjects = [];
                    // renderSubjectResultList();
                    // if (examNetChartInstance) examNetChartInstance.destroy();
                    // document.getElementById('examNetChart').style.display = 'none';
                    // setTimeout(closeExamEntryPopup, 2000); // 2 saniye sonra popup'ı kapat
                } else {
                    messageDiv.textContent = 'Hata: ' + (data.message || 'Bilinmeyen bir hata oluştu.');
                    messageDiv.style.color = 'red';
                }
            })
            .catch(error => {
                console.error('Tüm sınav sonuçlarını kaydetme hatası:', error);
                messageDiv.textContent = 'Bir ağ hatası oluştu: ' + error.message;
                messageDiv.style.color = 'red';
            });
        }

     
    </script>

</body>
</html>