-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: May 13, 2025 at 06:37 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `student_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `courses`
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
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`id`, `course_code`, `course_name`, `course_teacher`, `course_department`, `created_at`) VALUES
(4, 'MTH5667', 'Sifreleme', 'Elif Demir', 'Bilgisayar Mühendisliği', '2025-04-25 15:31:46'),
(6, 'BLM4020', 'Siber Sistemler', 'Zeynep Öztürk', 'Bilgisayar Mühendisliği', '2025-04-27 08:00:16'),
(7, 'MTH8000', 'Algebra', 'Ahmet Yılmaz', 'Makine Mühendisliği', '2025-04-27 08:01:13'),
(8, 'BLM3000', 'Girisimcilik', 'Mehmet Kaya', 'Bilgisayar Mühendisliği', '2025-04-27 08:01:40'),
(10, 'BLM6002', 'Yazilim', 'Mehmet Kaya', 'Bilgisayar Mühendisliği', '2025-04-27 09:12:15');

-- --------------------------------------------------------

--
-- Table structure for table `goals`
--

CREATE TABLE `goals` (
  `id` int(16) NOT NULL,
  `teacher_id` int(16) NOT NULL,
  `title` varchar(250) NOT NULL,
  `description` text NOT NULL,
  `end_date` date DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `goals`
--

INSERT INTO `goals` (`id`, `teacher_id`, `title`, `description`, `end_date`, `start_date`, `created_at`) VALUES
(1, 1, 'dsadsadsa', 'dasdadsaddas', NULL, NULL, '2025-05-01 19:15:48'),
(2, 1, '3 Günde 70 Soru', 'dsadasdsadasdsa', NULL, NULL, '2025-05-01 19:20:42'),
(3, 1, 'fen 1000 soru', '', NULL, NULL, '2025-05-09 09:05:39');

-- --------------------------------------------------------

--
-- Table structure for table `old_tasks`
--

CREATE TABLE `old_tasks` (
  `id` int(16) NOT NULL COMMENT 'Primary Key – Otomatik artan',
  `goal_id` int(16) NOT NULL COMMENT 'Hangi hedefe ait olduğunu tutar',
  `title` varchar(20) NOT NULL COMMENT 'Görev başlığı (örnek: "30 soru çöz")',
  `description` text NOT NULL COMMENT '	(isteğe bağlı) daha fazla açıklama',
  `due_date` date NOT NULL COMMENT 'Son teslim tarihi',
  `is_completed` tinyint(1) NOT NULL COMMENT '0 = tamamlanmadı, 1 = tamamlandı'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `old_tasks`
--

INSERT INTO `old_tasks` (`id`, `goal_id`, `title`, `description`, `due_date`, `is_completed`) VALUES
(1, 1, 'Matematik', 'ssdsadasdsadas', '2025-05-08', 0),
(2, 1, 'Matematik', 'ssdsadasdsadas', '2025-05-08', 0),
(3, 1, 'dasds', 'sadsada', '2025-05-22', 0),
(4, 1, 'dasds', 'sadsada', '2025-05-22', 0),
(5, 1, 'dasds', 'sadsada', '2025-05-22', 0),
(6, 2, 'adsadsad', 'asdsdasdsadsad', '2025-05-09', 0),
(7, 2, 'adsadsad', 'asdsdasdsadsad', '2025-05-09', 0);

-- --------------------------------------------------------

--
-- Table structure for table `selected_courses`
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
-- Dumping data for table `selected_courses`
--

INSERT INTO `selected_courses` (`id`, `course_code`, `course_name`, `course_teacher`, `course_department`, `selected_at`) VALUES
(9, 'BLM4020', 'Siber Sistemler', 'Zeynep Öztürk', 'Bilgisayar Mühendisliği', '2025-04-27 08:03:50'),
(10, 'BLM5002', 'Yazilim', 'Ahmet Yılmaz', 'Bilgisayar Mühendisliği', '2025-04-27 08:04:19'),
(11, 'BLM3000', 'Girisimcilik', 'Mehmet Kaya', 'Bilgisayar Mühendisliği', '2025-04-27 08:04:22');

-- --------------------------------------------------------

--
-- Table structure for table `student_daily_tasks`
--

CREATE TABLE `student_daily_tasks` (
  `id` int(11) NOT NULL,
  `student_id` int(11) DEFAULT NULL,
  `task_title` varchar(255) NOT NULL,
  `task_description` text DEFAULT NULL,
  `points` int(11) DEFAULT 0,
  `start_date` date DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `is_done` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student_daily_tasks`
--

INSERT INTO `student_daily_tasks` (`id`, `student_id`, `task_title`, `task_description`, `points`, `start_date`, `due_date`, `is_done`, `created_at`, `updated_at`) VALUES
(1, 10, 'Özdebir TYT', 'Sınav netlerini giriniz.', 10, '2025-05-12', '2025-05-16', 0, '2025-05-12 08:16:23', '2025-05-12 08:16:23'),
(2, 10, '10 SORU BİYOLOJİ', 'ACİLİYETİ YOK ', 0, '2025-05-12', '2025-05-16', 0, '2025-05-12 11:46:24', '2025-05-12 11:46:24'),
(3, 10, 'Özdebir TYT', '1', 2, '2025-05-02', '2025-05-18', 0, '2025-05-12 13:41:22', '2025-05-12 13:41:22');

-- --------------------------------------------------------

--
-- Table structure for table `student_exam_entries`
--

CREATE TABLE `student_exam_entries` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `task_id` int(11) NOT NULL,
  `exam_name` varchar(150) DEFAULT NULL,
  `correct_count` int(11) DEFAULT NULL,
  `wrong_count` int(11) DEFAULT NULL,
  `blank_count` int(11) DEFAULT NULL,
  `net_score` decimal(5,2) DEFAULT NULL,
  `entry_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `student_exam_subject_results`
--

CREATE TABLE `student_exam_subject_results` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `task_id` int(11) NOT NULL,
  `exam_entry_id` int(11) DEFAULT NULL,
  `exam_name` varchar(150) DEFAULT NULL,
  `subject_name` varchar(100) NOT NULL,
  `correct_count` int(11) DEFAULT 0,
  `wrong_count` int(11) DEFAULT 0,
  `blank_count` int(11) DEFAULT 0,
  `net_score` decimal(5,2) GENERATED ALWAYS AS (`correct_count` - `wrong_count` / 4.0) STORED,
  `entry_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student_exam_subject_results`
--

INSERT INTO `student_exam_subject_results` (`id`, `student_id`, `task_id`, `exam_entry_id`, `exam_name`, `subject_name`, `correct_count`, `wrong_count`, `blank_count`, `entry_date`) VALUES
(1, 10, 4, NULL, '', 'mat', 37, 2, 1, '2025-05-12 19:12:12');

-- --------------------------------------------------------

--
-- Table structure for table `student_goal_assignments`
--

CREATE TABLE `student_goal_assignments` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `goal_id` int(11) NOT NULL,
  `assigned_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student_goal_assignments`
--

INSERT INTO `student_goal_assignments` (`id`, `student_id`, `goal_id`, `assigned_at`) VALUES
(1, 2, 3, '2025-05-12 11:48:29'),
(3, 10, 2, '2025-05-12 13:48:54'),
(4, 10, 3, '2025-05-12 14:17:34'),
(5, 10, 1, '2025-05-12 14:42:21');

-- --------------------------------------------------------

--
-- Table structure for table `student_task_status`
--

CREATE TABLE `student_task_status` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `task_id` int(11) NOT NULL,
  `is_completed` tinyint(1) DEFAULT 0,
  `completion_date` timestamp NULL DEFAULT NULL,
  `last_updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student_task_status`
--

INSERT INTO `student_task_status` (`id`, `student_id`, `task_id`, `is_completed`, `completion_date`, `last_updated`) VALUES
(1, 10, 2, 0, NULL, '2025-05-12 14:41:11'),
(11, 10, 3, 0, NULL, '2025-05-12 14:17:54'),
(15, 10, 4, 0, NULL, '2025-05-12 19:05:43');

-- --------------------------------------------------------

--
-- Table structure for table `tasks`
--

CREATE TABLE `tasks` (
  `id` int(11) NOT NULL,
  `goal_id` int(11) NOT NULL,
  `task_order` int(11) DEFAULT 1,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `subject` varchar(100) DEFAULT NULL,
  `topic` varchar(150) DEFAULT NULL,
  `question_count` int(11) DEFAULT NULL,
  `task_type` varchar(50) DEFAULT 'general',
  `task_date` date DEFAULT NULL,
  `is_completed` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tasks`
--

INSERT INTO `tasks` (`id`, `goal_id`, `task_order`, `title`, `description`, `subject`, `topic`, `question_count`, `task_type`, `task_date`, `is_completed`, `created_at`, `updated_at`) VALUES
(1, 1, 4, 'TYT DENEME-ACİL MATEMATİK', '', '', '', NULL, 'exam_entry', NULL, 0, '2025-05-12 11:49:21', '2025-05-12 14:43:25'),
(2, 2, 1, 'haftalık mat', '', '', '', NULL, 'general', NULL, 0, '2025-05-12 13:52:36', '2025-05-12 13:52:36'),
(3, 3, 1, 'denemetest', '', '', '', NULL, 'exam_entry', NULL, 0, '2025-05-12 14:14:46', '2025-05-12 14:15:04'),
(4, 1, 1, 'denemetest2', '', '', '', NULL, 'exam_entry', NULL, 0, '2025-05-12 14:42:06', '2025-05-12 14:43:33');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `role` varchar(20) NOT NULL DEFAULT 'student'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`) VALUES
(1, 'Admin', 'admin@novadev.com', 'test123', 'admin'),
(2, 'Test1', 'test1@gmail.com', 'test1', 'student'),
(3, 'Zehra', 'zehra@gmail.com', 'zehra', 'student'),
(5, 'mehmet koc', 'mehmet@gmail.com', '', 'student'),
(6, 'Mehmet Fatih', 'mehmet@gmail.com', 'mehmet', 'student'),
(7, 'ibrahim', 'ibrahim@gmail.com', '1234', 'student'),
(8, 'Zehraaa', 'zehraemull@gmail.com', 'Zehrabengu.9', 'student'),
(9, 'gokay', 'gokaygunbak@gmail.com', '123', 'student'),
(10, 'özlem', 'ozlemkoc37@hotmail.com', '123123', 'student'),
(11, 'ofbıktım', 'ob@gmail.com', '123', 'student');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `goals`
--
ALTER TABLE `goals`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `old_tasks`
--
ALTER TABLE `old_tasks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `selected_courses`
--
ALTER TABLE `selected_courses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `student_daily_tasks`
--
ALTER TABLE `student_daily_tasks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `student_exam_entries`
--
ALTER TABLE `student_exam_entries`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_student_task_exam` (`student_id`,`task_id`),
  ADD KEY `task_id` (`task_id`);

--
-- Indexes for table `student_exam_subject_results`
--
ALTER TABLE `student_exam_subject_results`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `task_id` (`task_id`);

--
-- Indexes for table `student_goal_assignments`
--
ALTER TABLE `student_goal_assignments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_assignment` (`student_id`,`goal_id`),
  ADD KEY `goal_id` (`goal_id`);

--
-- Indexes for table `student_task_status`
--
ALTER TABLE `student_task_status`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_student_task` (`student_id`,`task_id`),
  ADD KEY `task_id` (`task_id`);

--
-- Indexes for table `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `goal_id` (`goal_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `courses`
--
ALTER TABLE `courses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `goals`
--
ALTER TABLE `goals`
  MODIFY `id` int(16) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `old_tasks`
--
ALTER TABLE `old_tasks`
  MODIFY `id` int(16) NOT NULL AUTO_INCREMENT COMMENT 'Primary Key – Otomatik artan', AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `selected_courses`
--
ALTER TABLE `selected_courses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `student_daily_tasks`
--
ALTER TABLE `student_daily_tasks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `student_exam_entries`
--
ALTER TABLE `student_exam_entries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `student_exam_subject_results`
--
ALTER TABLE `student_exam_subject_results`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `student_goal_assignments`
--
ALTER TABLE `student_goal_assignments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `student_task_status`
--
ALTER TABLE `student_task_status`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `tasks`
--
ALTER TABLE `tasks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `student_exam_entries`
--
ALTER TABLE `student_exam_entries`
  ADD CONSTRAINT `student_exam_entries_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `student_exam_entries_ibfk_2` FOREIGN KEY (`task_id`) REFERENCES `tasks` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `student_exam_subject_results`
--
ALTER TABLE `student_exam_subject_results`
  ADD CONSTRAINT `student_exam_subject_results_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `student_exam_subject_results_ibfk_2` FOREIGN KEY (`task_id`) REFERENCES `tasks` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `student_goal_assignments`
--
ALTER TABLE `student_goal_assignments`
  ADD CONSTRAINT `student_goal_assignments_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `student_goal_assignments_ibfk_2` FOREIGN KEY (`goal_id`) REFERENCES `goals` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `student_task_status`
--
ALTER TABLE `student_task_status`
  ADD CONSTRAINT `student_task_status_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `student_task_status_ibfk_2` FOREIGN KEY (`task_id`) REFERENCES `tasks` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tasks`
--
ALTER TABLE `tasks`
  ADD CONSTRAINT `tasks_ibfk_1` FOREIGN KEY (`goal_id`) REFERENCES `goals` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
