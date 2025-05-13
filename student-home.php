<?php
session_start(); // Oturumu başlat

// Oturum ve rol kontrolü
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] != 'student') {
    header("Location: login.php");
    exit();
}

$student_name = isset($_SESSION['user_name']) ? htmlspecialchars($_SESSION['user_name']) : 'Öğrenci';
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Öğrenci Anasayfası</title>
    <link rel="stylesheet" href="style.css">
    <link rel="icon" type="image/x-icon" href="./images/study.png">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        /* student-home.php <style> bloğunun İÇERİĞİ olarak bunu kullanın veya uyarlayın */

/* --- TEMEL SAYFA VE ARKA PLAN (user-homepage.html'den) --- */
body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f2f2f2; /* Varsayılan arka plan rengi */
    background-image: url('./images/homepage-bg.jpg'); /* ARKA PLAN RESMİ */
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    color: #333;
    min-height: 100vh;
    display: flex;
    flex-direction: column;
}
.page-wrapper {
    flex: 1 0 auto;
    /* Arka plan resminin içeriğin arkasında kalması için,
       content-wrapper'a opak bir arka plan verilebilir. */
}

/* --- HEADER (Mevcut student-home.php stilini koruyabiliriz veya user-homepage'e benzetebiliriz) --- */
header {
    background-color: #007acc; /* user-homepage.html'deki gibi */
    /* background: linear-gradient(to right, #007bff, #0056b3); Mevcut gradient */
    color: white;
    padding: 25px 40px; /* Biraz daha fazla padding */
    text-align: center;
    box-shadow: 0 3px 7px rgba(0,0,0,0.15); /* Biraz daha belirgin gölge */
}
header h1 {
     margin: 0;
     font-size: 2em; /* Daha büyük başlık */
     font-weight: 600;
}

/* --- İÇERİK ALANI (user-homepage.html'deki .content gibi) --- */
.content-wrapper {
    max-width: 1000px; /* user-homepage.html ile aynı */
    margin: 60px auto; /* Üst boşluğu artır */
    padding: 40px;     /* İç boşluğu artır */
    background-color: rgba(255, 255, 255, 0.95); /* Hafif şeffaf beyaz, arka plan resmi görünsün diye */
    border-radius: 15px;
    box-shadow: 0 8px 25px rgba(0,0,0,0.1);
}
.content-wrapper h2 { /* content içindeki ana başlıklar */
    color: #0056b3; /* Koyu mavi */
    text-align: center;
    margin-top: 0; /* İlk başlığın üst boşluğunu kaldır */
    margin-bottom: 30px;
    font-size: 1.8em;
    font-weight: 600;
}
 .content-wrapper p.welcome-text { /* user-homepage.html'deki .content p gibi */
     text-align: center;
     margin-bottom: 40px; /* Boşluğu artır */
     color: #444; /* Biraz daha koyu */
     font-size: 1.15em;
     line-height: 1.6;
 }

/* --- KARTLAR (user-homepage.html'deki .cards ve .card gibi) --- */
.cards {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); /* Kart genişliği ayarlandı */
    gap: 30px;
    margin-top: 20px; /* .content-wrapper h2 ve p'den sonraki boşluk */
}
.card {
    background-color: #f8f9fa; /* user-homepage.html'deki gibi açık gri */
    border-radius: 12px; /* Daha yuvarlak */
    padding: 25px;
    box-shadow: 0 6px 12px rgba(0,0,0,0.08);
    cursor: pointer;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    text-align: center;
    border: none; /* Kenarlığı kaldır */
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    min-height: 160px; /* Biraz daha yüksek */
}
.card:hover {
    transform: translateY(-8px) scale(1.02); /* Daha belirgin hover efekti */
    box-shadow: 0 12px 24px rgba(0,0,0,0.12);
}
.card h3 {
    margin-top: 10px;
    margin-bottom: 10px;
    color: #007acc; /* Ana mavi */
    font-size: 1.25em;
    font-weight: 600;
}
.card p {
    color: #555; /* Biraz daha koyu açıklama */
    font-size: 0.9em;
    line-height: 1.6;
    margin-bottom: 0;
}

/* --- POPUP STİLLERİ (Mevcut student-home.php stilleri iyi, sadece arka planı güncelleyebiliriz) --- */
.popup {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.7); /* user-homepage.html'deki gibi biraz daha koyu */
    display: none;
    justify-content: center;
    align-items: center;
    z-index: 1050;
    padding: 20px;
    box-sizing: border-box;
}
.popup-content { /* student-home.php'deki mevcut stil iyi, küçük ayarlar */
    background-color: white;
    padding: 35px; /* İç boşluk artırıldı */
    border-radius: 15px; /* Daha yuvarlak */
    max-width: 800px; /* Görev listesi için geniş olabilir */
    width: 90%;
    max-height: 85vh;
    overflow-y: auto;
    position: relative;
    color: #333;
    box-shadow: 0 10px 30px rgba(0,0,0,0.25); /* Daha belirgin gölge */
    display: flex;
    flex-direction: column;
}
#examEntryPopup .popup-content { /* Sınav sonucu popup'ı için özel, daha geniş olabilir */
    max-width: 750px; /* Bu zaten ayarlıydı */
}
#examEntryPopup {
     align-items: flex-start;
     padding-top: 5vh;
     padding-bottom: 5vh;
}
.popup-content h3 {
     margin-top: 0;
     margin-bottom: 30px; /* Boşluk artırıldı */
     color: #0056b3;
     text-align: center;
     border-bottom: 1px solid #dee2e6; /* Daha soluk çizgi */
     padding-bottom: 20px;
     font-size: 1.6em;
 }
.popup .close-btn {
    position: absolute;
    top: 15px;
    right: 15px;
    background-color: #e74c3c; /* user-homepage.html'deki gibi */
    color: white;
    border: none;
    width: 32px;
    height: 32px;
    border-radius: 50%;
    cursor: pointer;
    font-size: 18px;
    font-weight: bold;
    line-height: 32px;
    text-align: center;
    transition: background-color 0.2s ease, transform 0.2s ease;
}
.popup .close-btn:hover {
    background-color: #c0392b; /* Koyu kırmızı */
    transform: rotate(90deg);
}

/* --- GÖREV LİSTESİ STİLLERİ (Mevcut student-home.php stilleri iyi, küçük ayarlar) --- */
#studentTaskList {
    padding: 0;
    margin: 0;
}
#studentTaskList li {
    list-style-type: none;
    margin-bottom: 18px; /* Boşluk artırıldı */
}
#studentTaskList h4 { /* Hedef başlıkları */
     margin-top: 25px; /* Üst boşluk artırıldı */
     margin-bottom: 15px;
     padding-bottom: 10px;
     border-bottom: 2px solid #007acc; /* Ana mavi */
     color: #0056b3;
     font-size: 1.3em; /* Biraz daha büyük */
 }
 #studentTaskList li:first-child h4 {
     margin-top: 0;
 }
.task-box {
    border: 1px solid #e0e0e0; /* Daha soluk border */
    padding: 18px 22px; /* Padding ayarlandı */
    border-radius: 10px; /* Daha yuvarlak */
    background-color: #fff;
    transition: background-color 0.3s ease, opacity 0.3s ease, box-shadow 0.3s ease;
    position: relative;
    box-shadow: 0 3px 6px rgba(0,0,0,0.05);
}
.task-box:hover {
    box-shadow: 0 5px 10px rgba(0,0,0,0.08);
}
 .task-box strong.task-title {
     font-size: 1.15em; /* Biraz daha büyük */
     color: #2c3e50; /* Koyu gri-mavi */
     display: block;
     margin-bottom: 7px;
 }
 .task-box small.task-desc {
     display: block;
     margin: 8px 0 12px 0; /* Boşluklar ayarlandı */
     color: #555;
     font-size: 0.9em;
     line-height: 1.5;
 }
 .task-box .task-details {
     font-size: 0.88em;
     color: #666;
     margin-top: 12px;
     padding-top: 12px;
     border-top: 1px dashed #e0e0e0;
     display: flex;
     flex-wrap: wrap;
     gap: 12px 18px; /* Dikey ve yatay boşluk */
 }
  .task-box .task-details span {
     white-space: nowrap;
 }
  .task-box .task-details strong {
     color: #34495e; /* Koyu gri */
 }
 button.toggle-done-btn, button.exam-entry-btn { /* İki butona da ortak stil */
    margin-top: 15px;
    padding: 7px 14px; /* Buton boyutu */
    cursor: pointer;
    border-radius: 6px; /* Daha yuvarlak */
    font-size: 0.9em;
    font-weight: bold;
    transition: all 0.2s ease;
    margin-right: 8px; /* Butonlar arası boşluk */
 }
 button.toggle-done-btn:last-child, button.exam-entry-btn:last-child {
     margin-right: 0; /* Son butonun sağ boşluğunu kaldır */
 }

/* --- FOOTER STİLLERİ (Mevcut student-home.php'deki gibi) --- */
footer {
    background-color: #2c3e50; /* Koyu tema footer */
    color: #ecf0f1; /* Açık renk yazı */
    text-align: center;
    padding: 25px 0;
    flex-shrink: 0;
    border-top: 4px solid #3498db; /* Mavi üst çizgi */
}
.logout-btn-footer { /* Stil student-home.php'deki ile aynı kalabilir veya güncellenebilir */
    background-color: #e74c3c; /* Kırmızı */
    color: white;
    border: none;
    padding: 10px 28px;
    border-radius: 25px;
    cursor: pointer;
    font-weight: bold;
    font-size: 1.05em;
    transition: background-color 0.3s ease, transform 0.2s ease;
    box-shadow: 0 2px 5px rgba(0,0,0,0.2);
}
.logout-btn-footer:hover {
    background-color: #c0392b; /* Koyu kırmızı */
    transform: translateY(-2px);
}
footer p {
    margin-top: 15px; /* Boşluk artırıldı */
    font-size: 0.9em;
    color: #bdc3c7; /* Soluk gri */
}
/* --- Sınav Giriş Popup Form Stilleri (user-homepage.html'den uyarlanmış) --- */
#examEntryForm_new label {
    display: block;
    margin-bottom: 5px;
    font-weight: 600;
    color: #444;
}
#examEntryForm_new input[type="text"],
#examEntryForm_new input[type="number"] {
    width: calc(100% - 18px); /* Padding'i hesaba kat */
    padding: 10px;
    margin-bottom: 15px;
    border: 1px solid #ccc;
    border-radius: 6px;
    font-size: 0.95em;
}
#examEntryForm_new h4 {
    margin-top: 20px;
    margin-bottom: 10px;
    color: #0056b3;
}
#examEntryForm_new button[type="button"] { /* "Bu Dersi Listeye Ekle" butonu */
    padding: 10px 18px;
    background-color: #007bff;
    color: white;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    transition: background-color 0.2s;
}
#examEntryForm_new button[type="button"]:hover {
    background-color: #0056b3;
}
#subjectResultList { /* Ders listesi için daha iyi görünüm */
    border: 1px solid #e0e0e0;
    padding: 15px;
    border-radius: 8px;
    background-color: #f9f9f9;
}
#subjectResultList li {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px 0;
    border-bottom: 1px dotted #ccc;
    font-size: 0.9em;
}
#subjectResultList li:last-child {
    border-bottom: none;
}
#subjectResultList li button { /* Kaldır butonu */
    background: #e74c3c;
    color: white;
    border: none;
    padding: 4px 8px;
    border-radius: 4px;
    cursor: pointer;
    font-size: 0.8em;
}
#examEntryPopup button[onclick="saveAllSubjectResults()"] { /* Tüm Sonuçları Kaydet butonu */
    padding: 12px 30px;
    background-color: #28a745;
    color: white;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-size: 1.1em;
    transition: background-color 0.2s;
}
#examEntryPopup button[onclick="saveAllSubjectResults()"]:hover {
    background-color: #218838;
}
#examNetChart { /* Grafik canvası */
    margin-top: 20px;
}
    </style>
</head>
<body>

    <div class="page-wrapper">
        <header>
            <h1>👋 Hoş Geldin, <?php echo $student_name; ?>!</h1>
      
        </header>

        <div class="content-wrapper">
            <h2>Öğrenci Paneli</h2>
            <p class="welcome-text">Sana özel içerikler ve görevler burada yer alıyor.</p>

            <div class="cards">
                <div class="card" onclick="openTaskPopup()">
                    <h3>📅 Görevlerim</h3>
                    <p>Sana atanan hedefleri ve günlük görevlerini görüntüle.</p>
                </div>
                <div class="card" onclick="alert('Bu özellik yakında eklenecek!');">
                    <h3>📊 İstatistiklerim</h3>
                    <p>Görev tamamlama ve performans analizlerini incele.</p>
                </div>
                 <div class="card" onclick="alert('Bu özellik yakında eklenecek!');">
                    <h3>📚 Derslerim</h3>
                    <p>Seçtiğin veya kayıtlı olduğun dersleri yönet.</p>
                 </div>
                 <div class="card" onclick="alert('Bu özellik yakında eklenecek!');">
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


    <div class="popup" id="examEntryPopup"> 
        <div class="popup-content">
            <button class="close-btn" onclick="closeExamEntryPopup()">X</button>
            <h3 id="examEntryPopupTitle">Sınav Sonucu Gir</h3>
            <div style="display: flex; gap: 20px;">
                <div style="flex: 1;">
                    <form id="examEntryForm_new">
                        <input type="hidden" id="examEntryTaskId_new" name="task_id_new">
                        {/* Genel Sınav Adı input'u kaldırılmıştı, istersen geri ekleyebilirsin */}
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
                <div style="flex: 1.5; display: flex; flex-direction: column; min-height: 350px;">
                     <h4>Eklenen Ders Sonuçları:</h4>
                     <ul id="subjectResultList" style="list-style: none; padding: 0; margin-bottom: 15px; max-height: 150px; overflow-y: auto; border: 1px solid #eee; padding:10px;">
                         <li class="no-subjects">Henüz ders eklenmedi.</li>
                     </ul>
                     <div style="flex-grow: 1; position: relative; min-height: 200px;">
                        <canvas id="examNetChart" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%;"></canvas>
                     </div>
                </div>
            </div>
            <div style="text-align: center; margin-top: 25px; border-top: 1px solid #eee; padding-top: 15px;">
                <button type="button" onclick="saveAllSubjectResults()" style="padding: 12px 25px; background-color: #28a745; color: white; border: none; border-radius: 5px; cursor: pointer; font-size: 1.1em;">Tüm Sonuçları Kaydet</button>
            </div>
            <div id="examEntryMessage_new" style="margin-top: 15px; text-align: center; font-weight: bold;"></div>
        </div>
    </div>


    <footer style="text-align: center; padding: 25px 0; background-color: #212529; color: #dee2e6; margin-top: auto; border-top: 3px solid #007bff;">
        <button type="button" class="logout-btn-footer" onclick="logoutUser()">Çıkış Yap</button>
        <p style="margin-top: 12px; font-size: 0.9em;">© <?php echo date("Y"); ?> Nova Dev Koçluk Merkezi. Tüm Hakları Saklıdır.</p>
    </footer>

    <script>
        // --- Popup Açma/Kapama Fonksiyonları ---
        function openTaskPopup() {
            loadStudentTasks();
            document.getElementById("studentTaskPopup").style.display = "flex";
        }
        function closeTaskPopup() {
            document.getElementById("studentTaskPopup").style.display = "none";
        }
        // --- Görevleri Yükleme Fonksiyonu ---
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
        // --- Görev Durumunu Güncelleme Fonksiyonu ---
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
        // --- Çıkış Yapma Fonksiyonu ---
          // --- student-home.php <script> bloğu içinde ---
    function logoutUser() {
        if (confirm("Çıkış yapmak istediğinize emin misiniz?")) {
            fetch('logout.php', { // 'ajax_logout.php' yerine 'logout.php' olarak değiştirildi
                method: 'POST' // POST metodu kullanılabilir, veri göndermese de olur.
                               // VEYA GET de olabilir: method: 'GET'
            })
            .then(res => {
                // HTTP yanıtının başarılı olup olmadığını kontrol et (örn: 404, 500 gibi hatalar)
                if (!res.ok) {
                    // Başarısızsa, bir hata fırlat ki .catch() bloğu yakalasın
                    // Sunucudan gelen hata mesajını da ekleyebiliriz (eğer varsa)
                    return res.text().then(text => { // Hata mesajını text olarak almayı dene
                        throw new Error(`Sunucu hatası: ${res.status} - ${res.statusText}. Yanıt: ${text}`);
                    });
                }
                // Yanıt başarılıysa (2xx durum kodu), JSON olarak ayrıştırmayı dene
                return res.json();
            })
            .then(data => {
                // JSON verisini işle
                if (data.success) {
                    // Çıkış başarılı mesajı (PHP'den gelen veya varsayılan)
                    alert(data.message || "Başarıyla çıkış yapıldı. Giriş sayfasına yönlendiriliyorsunuz.");
                    // Başarılı çıkış sonrası kullanıcıyı giriş sayfasına yönlendir
                    window.location.href = "index.html"; // Giriş sayfanızın adı
                } else {
                    // Çıkış başarısız mesajı (PHP'den gelen veya varsayılan)
                    alert("Çıkış işlemi başarısız: " + (data.message || "Bilinmeyen bir hata oluştu."));
                }
            })
            .catch(error => {
                // Fetch hatası (ağ sorunu) veya yukarıda fırlatılan HTTP hatası
                console.error("Çıkış Hatası Detayları:", error);
                alert("Çıkış işlemi sırasında bir sorun oluştu. Lütfen konsolu kontrol edin.\nHata: " + error.message);
            });
        }
    }
        // --- Sınav Sonuç Girişi Fonksiyonları ---
        let currentExamSubjects = [];
        let examNetChartInstance = null;
        function openExamEntryPopup(taskId, taskTitle) {
            console.log("openExamEntryPopup çağrıldı. Task ID:", taskId, "Başlık:", taskTitle);
            document.getElementById('examEntryTaskId_new').value = taskId;
            document.getElementById('examEntryPopupTitle').textContent = `Sonuç Gir: ${taskTitle}`;
            document.getElementById('examEntryForm_new').reset();
            document.getElementById('examEntryMessage_new').textContent = '';
            document.getElementById('subjectResultList').innerHTML = '<li class="no-subjects">Henüz ders eklenmedi.</li>';
            currentExamSubjects = [];
            if (examNetChartInstance) {
                examNetChartInstance.destroy();
                examNetChartInstance = null;
            }
            const examNetChartCanvas = document.getElementById('examNetChart');
            if(examNetChartCanvas) examNetChartCanvas.style.display = 'none';

            document.getElementById('examEntryPopup').style.display = 'flex';
            console.log("Popup gösterilmeye çalışılıyor.");
            // loadPreviousSubjectResults(taskId); // Bu fonksiyonu daha sonra ekleyebiliriz
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
            generateExamNetChart();
            document.getElementById('subjectName_new').value = '';
            document.getElementById('correctCount_new').value = '0';
            document.getElementById('wrongCount_new').value = '0';
            document.getElementById('blankCount_new').value = '0';
            document.getElementById('subjectName_new').focus();
        }
        function renderSubjectResultList() {
            const listElement = document.getElementById('subjectResultList');
            listElement.innerHTML = '';
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
            currentExamSubjects.splice(indexToRemove, 1);
            renderSubjectResultList();
            generateExamNetChart();
        }
        function generateExamNetChart() {
            const canvas = document.getElementById('examNetChart');
            if(!canvas) { console.error("Canvas elemanı bulunamadı!"); return; }

            if (currentExamSubjects.length === 0) {
                canvas.style.display = 'none';
                if (examNetChartInstance) {
                    examNetChartInstance.destroy();
                    examNetChartInstance = null;
                }
                return;
            }
            canvas.style.display = 'block';
            const ctx = canvas.getContext('2d');
            if (examNetChartInstance) {
                examNetChartInstance.destroy();
            }
            const labels = currentExamSubjects.map(s => s.name);
            const netScores = currentExamSubjects.map(s => s.correct - (s.wrong / 4.0));
            const backgroundColors = ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40', '#C9CBCF'];
            examNetChartInstance = new Chart(ctx, {
                type: 'bar',
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
                    maintainAspectRatio: false,
                    scales: { y: { beginAtZero: true } },
                    plugins: {
                        legend: { display: true, position: 'top' },
                        title: { display: true, text: 'Ders Bazlı Net Dağılımı' }
                    }
                }
            });
        }
        function saveAllSubjectResults() {
            const taskId = document.getElementById('examEntryTaskId_new').value;
            const examNameInput = document.getElementById('examName_new');
            const examName = examNameInput ? examNameInput.value.trim() : null; // examName_new input'u olmayabilir, kontrol et
            const messageDiv = document.getElementById('examEntryMessage_new');

            if (currentExamSubjects.length === 0) {
                alert("Kaydedilecek ders sonucu bulunmamaktadır.");
                return;
            }
            messageDiv.textContent = 'Kaydediliyor...';
            messageDiv.style.color = 'orange';
            const dataToSend = {
                task_id: taskId,
                exam_name: examName, // Bu değer null olabilir
                subjects: currentExamSubjects
            };
            fetch('save_student_exam_subjects.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
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