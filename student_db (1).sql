-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Anamakine: localhost
-- Üretim Zamanı: 01 May 2025, 18:32:33
-- Sunucu sürümü: 10.4.28-MariaDB
-- PHP Sürümü: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Veritabanı: `student_db`
--

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `courses`
--

CREATE TABLE `courses` (
  `id` int(11) NOT NULL,
  `course_code` varchar(20) NOT NULL,
  `course_name` varchar(100) NOT NULL,
  `course_teacher` varchar(100) NOT NULL,
  `course_department` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `courses`
--

INSERT INTO `courses` (`id`, `course_code`, `course_name`, `course_teacher`, `course_department`, `created_at`) VALUES
(4, 'MTH5667', 'Sifreleme', 'Elif Demir', 'Bilgisayar Mühendisliği', '2025-04-25 15:31:46'),
(6, 'BLM4020', 'Siber Sistemler', 'Zeynep Öztürk', 'Bilgisayar Mühendisliği', '2025-04-27 08:00:16'),
(7, 'MTH8000', 'Algebra', 'Ahmet Yılmaz', 'Makine Mühendisliği', '2025-04-27 08:01:13'),
(8, 'BLM3000', 'Girisimcilik', 'Mehmet Kaya', 'Bilgisayar Mühendisliği', '2025-04-27 08:01:40'),
(10, 'BLM6002', 'Yazilim', 'Mehmet Kaya', 'Bilgisayar Mühendisliği', '2025-04-27 09:12:15');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `goals`
--

CREATE TABLE `goals` (
  `id` int(16) NOT NULL,
  `teacher_id` int(16) NOT NULL,
  `title` varchar(250) NOT NULL,
  `description` text NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `goals`
--

INSERT INTO `goals` (`id`, `teacher_id`, `title`, `description`, `created_at`) VALUES
(1, 1, 'dsadsadsa', 'dasdadsaddas', '2025-05-01 19:15:48'),
(2, 1, '3 Günde 70 Soru', 'dsadasdsadasdsa', '2025-05-01 19:20:42');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `selected_courses`
--

CREATE TABLE `selected_courses` (
  `id` int(11) NOT NULL,
  `course_code` varchar(20) NOT NULL,
  `course_name` varchar(100) NOT NULL,
  `course_teacher` varchar(100) NOT NULL,
  `course_department` varchar(100) NOT NULL,
  `selected_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `selected_courses`
--

INSERT INTO `selected_courses` (`id`, `course_code`, `course_name`, `course_teacher`, `course_department`, `selected_at`) VALUES
(9, 'BLM4020', 'Siber Sistemler', 'Zeynep Öztürk', 'Bilgisayar Mühendisliği', '2025-04-27 08:03:50'),
(10, 'BLM5002', 'Yazilim', 'Ahmet Yılmaz', 'Bilgisayar Mühendisliği', '2025-04-27 08:04:19'),
(11, 'BLM3000', 'Girisimcilik', 'Mehmet Kaya', 'Bilgisayar Mühendisliği', '2025-04-27 08:04:22');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `tasks`
--

CREATE TABLE `tasks` (
  `id` int(16) NOT NULL COMMENT 'Primary Key – Otomatik artan',
  `goal_id` int(16) NOT NULL COMMENT 'Hangi hedefe ait olduğunu tutar',
  `title` varchar(20) NOT NULL COMMENT 'Görev başlığı (örnek: "30 soru çöz")',
  `description` text NOT NULL COMMENT '	(isteğe bağlı) daha fazla açıklama',
  `due_date` date NOT NULL COMMENT 'Son teslim tarihi',
  `is_completed` tinyint(1) NOT NULL COMMENT '0 = tamamlanmadı, 1 = tamamlandı'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `tasks`
--

INSERT INTO `tasks` (`id`, `goal_id`, `title`, `description`, `due_date`, `is_completed`) VALUES
(1, 1, 'Matematik', 'ssdsadasdsadas', '2025-05-08', 0),
(2, 1, 'Matematik', 'ssdsadasdsadas', '2025-05-08', 0),
(3, 1, 'dasds', 'sadsada', '2025-05-22', 0),
(4, 1, 'dasds', 'sadsada', '2025-05-22', 0),
(5, 1, 'dasds', 'sadsada', '2025-05-22', 0),
(6, 2, 'adsadsad', 'asdsdasdsadsad', '2025-05-09', 0),
(7, 2, 'adsadsad', 'asdsdasdsadsad', '2025-05-09', 0);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`) VALUES
(1, 'Admin', 'admin@novadev.com', 'test123'),
(2, 'Test1', 'test1@gmail.com', 'test1'),
(3, 'Zehra', 'zehra@gmail.com', 'zehra'),
(5, 'mehmet koc', 'mehmet@gmail.com', ''),
(6, 'Mehmet Fatih', 'mehmet@gmail.com', 'mehmet'),
(7, 'ibrahim', 'ibrahim@gmail.com', '1234'),
(8, 'Zehraaa', 'zehraemull@gmail.com', 'Zehrabengu.9');

--
-- Dökümü yapılmış tablolar için indeksler
--

--
-- Tablo için indeksler `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `goals`
--
ALTER TABLE `goals`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `selected_courses`
--
ALTER TABLE `selected_courses`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Dökümü yapılmış tablolar için AUTO_INCREMENT değeri
--

--
-- Tablo için AUTO_INCREMENT değeri `courses`
--
ALTER TABLE `courses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Tablo için AUTO_INCREMENT değeri `goals`
--
ALTER TABLE `goals`
  MODIFY `id` int(16) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Tablo için AUTO_INCREMENT değeri `selected_courses`
--
ALTER TABLE `selected_courses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Tablo için AUTO_INCREMENT değeri `tasks`
--
ALTER TABLE `tasks`
  MODIFY `id` int(16) NOT NULL AUTO_INCREMENT COMMENT 'Primary Key – Otomatik artan', AUTO_INCREMENT=8;

--
-- Tablo için AUTO_INCREMENT değeri `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
