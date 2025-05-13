<?php
session_start();

// Kullanıcı oturumda değilse veya rolü 'admin' değilse, giriş sayfasına yönlendir
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Yönetici Anasayfası</title>
    <link rel="stylesheet" href="style.css">
    <link rel="icon" type="image/x-icon" href="./images/study.png">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background: #f2f2f2;
            background-image: url('./images/homepage-bg.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }
        header {
            background-color: #007acc;
            color: white;
            padding: 20px;
            text-align: center;
            position: relative;
        }
        .logout-btn {
            position: absolute;
            top: 20px;
            right: 20px;
            background-color: white;
            color: #007acc;
            border: none;
            padding: 10px 16px;
            border-radius: 6px;
            cursor: pointer;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }
        .logout-btn:hover {
            background-color: #f44336;
            color: white;
        }
        .content {
            max-width: 1000px;
            margin: 100px auto;
            padding: 30px;
            background-color: white;
            border-radius: 12px;
            box-shadow: 0 4px 16px rgba(0,0,0,0.1);
            text-align: center;
        }
        .content h2 {
            color: #007acc;
        }
        .cards {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            margin-top: 30px;
            gap: 20px;
        }
        .card {
            flex: 1 1 45%;
            min-width: 200px;
            background-color: #f4f4f4;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            cursor: pointer;
            transition: transform 0.2s;
        }
        .card:hover {
            transform: scale(1.03);
        }
        .popup {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background-color: rgba(0,0,0,0.6);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 999;
        }
        .popup-content {
            background-color: white;
            padding: 20px;
            border-radius: 12px;
            max-width: 800px;
            width: 90%;
            max-height: 80vh;
            overflow-y: auto;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #007acc;
            color: white;
        }
        .close-btn {
            background-color: #f44336;
            color: white;
            border: none;
            padding: 6px 10px;
            border-radius: 5px;
            float: right;
            cursor: pointer;
        }
        #statusMessage {
            position: fixed;
            top: 80px;
            left: 50%;
            transform: translateX(-50%);
            background-color: #4caf50;
            color: white;
            padding: 12px 24px;
            border-radius: 8px;
            display: none;
            z-index: 1100;
            font-weight: bold;
        }
        #statusMessage.error {
            background-color: #f44336;
        }
    </style>
</head>
<body>
    <header>
        <h1>👋 Hoşgeldiniz Yönetici!</h1>
        <button class="logout-btn" onclick="window.location.href='logout.php'">Çıkış Yap</button>
    </header>

    <div class="content">
        <h2>Yönetici Paneli</h2>
        <p>Sisteme yönetici olarak giriş yaptınız. Burada yöneticiye özel içerikler yer alacaktır.</p>

        <div class="cards">
            <div class="card">Kullanıcı Yönetimi</div>
            <div class="card">Raporlar</div>
            </div>
    </div>
</body>
</html>