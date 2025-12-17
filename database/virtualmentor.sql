-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 17, 2025 at 04:33 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `virtualmentor`
--

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE `courses` (
  `id` int(11) NOT NULL,
  `course_name` varchar(255) NOT NULL,
  `year` int(11) NOT NULL,
  `professor_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`id`, `course_name`, `year`, `professor_id`) VALUES
(1, 'Introduction to Programming', 1, 1),
(2, 'Mathematics 1', 1, 2),
(3, 'Computer Networks', 1, 3),
(4, 'Operating Systems', 1, 4),
(5, 'Digital Logic', 1, 5),
(6, 'Data Structures', 2, 6),
(7, 'Algorithms', 2, 7),
(8, 'Database Systems', 2, 8),
(9, 'Computer Architecture', 2, 9),
(10, 'Discrete Mathematics', 2, 10),
(11, 'Software Engineering', 3, 11),
(12, 'Artificial Intelligence', 3, 12),
(13, 'Computer Graphics', 3, 13),
(14, 'Web Development', 3, 14),
(15, 'Machine Learning', 3, 15);

-- --------------------------------------------------------

--
-- Table structure for table `course_announcements`
--

CREATE TABLE `course_announcements` (
  `id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `professor_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `text` text NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `forum_posts`
--

CREATE TABLE `forum_posts` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `post_text` text NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `forum_posts`
--

INSERT INTO `forum_posts` (`id`, `student_id`, `post_text`, `created_at`) VALUES
(1, 21, 'hey', '2025-07-18 23:19:35');

-- --------------------------------------------------------

--
-- Table structure for table `forum_replies`
--

CREATE TABLE `forum_replies` (
  `id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `reply_text` text NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `forum_reply_views`
--

CREATE TABLE `forum_reply_views` (
  `id` int(11) NOT NULL,
  `reply_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `view_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `forum_views`
--

CREATE TABLE `forum_views` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `viewed_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `forum_views`
--

INSERT INTO `forum_views` (`id`, `student_id`, `post_id`, `viewed_at`) VALUES
(1, 21, 1, '2025-07-18 23:19:35'),
(2, 16, 1, '2025-07-18 23:19:56');

-- --------------------------------------------------------

--
-- Table structure for table `notes`
--

CREATE TABLE `notes` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `note_text` text NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `student_courses`
--

CREATE TABLE `student_courses` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `student_courses`
--

INSERT INTO `student_courses` (`id`, `student_id`, `course_id`) VALUES
(1, 16, 1),
(2, 17, 1),
(3, 18, 1),
(4, 16, 2),
(5, 17, 2),
(6, 18, 2),
(7, 16, 3),
(8, 17, 3),
(9, 18, 3),
(10, 16, 4),
(11, 17, 4),
(12, 18, 4),
(13, 16, 5),
(14, 17, 5),
(15, 18, 5),
(16, 19, 6),
(17, 20, 6),
(18, 21, 6),
(19, 19, 7),
(20, 20, 7),
(21, 21, 7),
(22, 19, 8),
(23, 20, 8),
(24, 21, 8),
(25, 19, 9),
(26, 20, 9),
(27, 21, 9),
(28, 19, 10),
(29, 20, 10),
(30, 21, 10),
(31, 22, 11),
(32, 23, 11),
(33, 24, 11),
(34, 22, 12),
(35, 23, 12),
(36, 24, 12),
(37, 22, 13),
(38, 23, 13),
(39, 24, 13),
(40, 22, 14),
(41, 23, 14),
(42, 24, 14),
(43, 22, 15),
(44, 23, 15),
(45, 24, 15);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('student','professor') NOT NULL,
  `fakulteti` varchar(255) DEFAULT NULL,
  `dega` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `fakulteti`, `dega`) VALUES
(1, 'John Smith', 'johnsmith@uamd.edu.al', '$2y$10$PjVR5qz7e6uIM4Kof.cAmemg4zP/RhcPWiIButNcXZkq2rcjgEgZK', 'professor', 'FTI', NULL),
(2, 'Emma Johnson', 'emmajohnson@uamd.edu.al', '$2y$10$FyguapgoU/8laGrbquhj5Oz3n3bz67XK8xMb/aTkgo1nqO54KM8OK', 'professor', 'FTI', NULL),
(3, 'Michael Brown', 'michaelbrown@uamd.edu.al', '$2y$10$9vCHUD.79cYAu55TVUVa4.Pt7I5jANdZpc/RUMdDBKR0UTBd4t4cC', 'professor', 'FTI', NULL),
(4, 'Sarah Davis', 'sarahdavis@uamd.edu.al', '$2y$10$c81pZYe2clJDEs0Eab97BusJVGHKjrm54pTNMa3Yvqz9Cn1dZulB2', 'professor', 'FTI', NULL),
(5, 'David Wilson', 'davidwilson@uamd.edu.al', '$2y$10$HtVNHdSLOlCHdOapQnMoM.TrN1ukUlo2cMlzjDuMB05kaOkoaUy8S', 'professor', 'FTI', NULL),
(6, 'Olivia Martinez', 'oliviamartinez@uamd.edu.al', '$2y$10$k4ynJpHT4kRpNUPfIF0TsOShC9oGvPNYcc/wlnsvjWUDutIbmCZua', 'professor', 'FTI', NULL),
(7, 'James Anderson', 'jamesanderson@uamd.edu.al', '$2y$10$JDe9o6bi/dUukywHFhS0mu7dmb71zAKRbBLxGeYMeZ.grG.9tcBKG', 'professor', 'FTI', NULL),
(8, 'Sophia Thomas', 'sophiathomas@uamd.edu.al', '$2y$10$NdHGdg7q926DcYEfOzDHSulOuZCZuIkQgoCwYeYuebPf8Y0Ychs7G', 'professor', 'FTI', NULL),
(9, 'Daniel Taylor', 'danieltaylor@uamd.edu.al', '$2y$10$NnltZQoNRxTi1ZNGF8/Ncuvq9z.RlJUcDHva3jlun8uoynycQtqOG', 'professor', 'FTI', NULL),
(10, 'Isabella Moore', 'isabellamoore@uamd.edu.al', '$2y$10$F3rThsRQA/yfi.CtGGUJp.HjvlCPLYiXr5O7wQNZPMNUxYQdSWkf2', 'professor', 'FTI', NULL),
(11, 'Matthew Jackson', 'matthewjackson@uamd.edu.al', '$2y$10$nycThHBtH27cBjmzMYWJ8OCL/grxie0QshcMF3g/eyiATvDQsR0gK', 'professor', 'FTI', NULL),
(12, 'Mia White', 'miawhite@uamd.edu.al', '$2y$10$7Rcq3/rKz.0UTI6Ww1sezex0dtCd5JajqQ0dLqbMvdzZ8bLxW55yK', 'professor', 'FTI', NULL),
(13, 'Ethan Harris', 'ethanharris@uamd.edu.al', '$2y$10$UT9OqitOPfFTx8btlzOOd.OrwUMBz1mGeU7zmVnEIi/K3iF.6AO6.', 'professor', 'FTI', NULL),
(14, 'Ava Clark', 'avaclark@uamd.edu.al', '$2y$10$.x9UkWatYfN0IjWiBadPgO3.2brtCg3.Gil8ROqYVoozC7k7Foa5y', 'professor', 'FTI', NULL),
(15, 'William Lewis', 'williamlewis@uamd.edu.al', '$2y$10$Wa5Swjn1uEYEgmWl8rQoN.vfvsUnJdjWcalyoC9KagjpO0WkbMkA.', 'professor', 'FTI', NULL),
(16, 'Liam Carter', 'liamcarter@students.uamd.edu.al', '$2y$10$AeF6N0FXgE0KnytU9DkOvubNKdxakZs4X9ezt1DS5tJEe0PynA6BK', 'student', 'FTI', 'Shkenca Kompjuterike'),
(17, 'Emma Walker', 'emmawalker@students.uamd.edu.al', '$2y$10$UQmA..vNKX/d2Fyv3Y5v2eRwL2/vAZLKxnjTz8QBSLB1uBPjKHnO2', 'student', 'FTI', 'Shkenca Kompjuterike'),
(18, 'Noah Robinson', 'noahrobinson@students.uamd.edu.al', '$2y$10$K0wUGh/bJVTA1xbAsOWJ1.b8POKUKZXvTR3VsYHU.YaO1eYwCrnXG', 'student', 'FTI', 'Shkenca Kompjuterike'),
(19, 'Olivia Scott', 'oliviascott@students.uamd.edu.al', '$2y$10$gJNI8rqgmJBgT7RCb0q69.M8h5CWB0oG4daXUfMds8bI3/4xzGh/6', 'student', 'FTI', 'Shkenca Kompjuterike'),
(20, 'Lucas Adams', 'lucasadams@students.uamd.edu.al', '$2y$10$9x8fikL9.wX8Zw77ozwK4.kq2Xi/GSwnyq9lwwTNzV8G31yuC3amq', 'student', 'FTI', 'Shkenca Kompjuterike'),
(21, 'Sophia Baker', 'sophiabaker@students.uamd.edu.al', '$2y$10$0KggI7T6op2Oj9RCSDQsbuy6bAT6ue/Ekt99nPMT/YpiiOSpa0Sam', 'student', 'FTI', 'Shkenca Kompjuterike'),
(22, 'Mason Green', 'masongreen@students.uamd.edu.al', '$2y$10$ygAInaIz.LmgQ6aps7Sh9.TIiWbT4qrmzvQTFqfkTl/l18mswGDfC', 'student', 'FTI', 'Shkenca Kompjuterike'),
(23, 'Ava Hall', 'avahall@students.uamd.edu.al', '$2y$10$qCLgPf66i7ExotYalk0/OOOJC0Bi8Ti6l7z7RvReFYeEYA8QllCem', 'student', 'FTI', 'Shkenca Kompjuterike'),
(24, 'Elijah Wright', 'elijahwright@students.uamd.edu.al', '$2y$10$VYX2e0A8kHAqycaibCKKn.wQ5s/PMxYWLcJ6OIsZiMh6Ty/8n1B4m', 'student', 'FTI', 'Shkenca Kompjuterike');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `professor_id` (`professor_id`);

--
-- Indexes for table `course_announcements`
--
ALTER TABLE `course_announcements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `course_id` (`course_id`),
  ADD KEY `professor_id` (`professor_id`);

--
-- Indexes for table `forum_posts`
--
ALTER TABLE `forum_posts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `forum_replies`
--
ALTER TABLE `forum_replies`
  ADD PRIMARY KEY (`id`),
  ADD KEY `post_id` (`post_id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `forum_reply_views`
--
ALTER TABLE `forum_reply_views`
  ADD PRIMARY KEY (`id`),
  ADD KEY `reply_id` (`reply_id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `forum_views`
--
ALTER TABLE `forum_views`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `post_id` (`post_id`);

--
-- Indexes for table `notes`
--
ALTER TABLE `notes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `course_id` (`course_id`);

--
-- Indexes for table `student_courses`
--
ALTER TABLE `student_courses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `course_id` (`course_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `courses`
--
ALTER TABLE `courses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `course_announcements`
--
ALTER TABLE `course_announcements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `forum_posts`
--
ALTER TABLE `forum_posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `forum_replies`
--
ALTER TABLE `forum_replies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `forum_reply_views`
--
ALTER TABLE `forum_reply_views`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `forum_views`
--
ALTER TABLE `forum_views`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `notes`
--
ALTER TABLE `notes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `student_courses`
--
ALTER TABLE `student_courses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `courses`
--
ALTER TABLE `courses`
  ADD CONSTRAINT `courses_ibfk_1` FOREIGN KEY (`professor_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `course_announcements`
--
ALTER TABLE `course_announcements`
  ADD CONSTRAINT `course_announcements_ibfk_1` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `course_announcements_ibfk_2` FOREIGN KEY (`professor_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `forum_posts`
--
ALTER TABLE `forum_posts`
  ADD CONSTRAINT `forum_posts_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `forum_replies`
--
ALTER TABLE `forum_replies`
  ADD CONSTRAINT `forum_replies_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `forum_posts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `forum_replies_ibfk_2` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `forum_reply_views`
--
ALTER TABLE `forum_reply_views`
  ADD CONSTRAINT `forum_reply_views_ibfk_1` FOREIGN KEY (`reply_id`) REFERENCES `forum_replies` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `forum_reply_views_ibfk_2` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `forum_views`
--
ALTER TABLE `forum_views`
  ADD CONSTRAINT `forum_views_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `forum_views_ibfk_2` FOREIGN KEY (`post_id`) REFERENCES `forum_posts` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `notes`
--
ALTER TABLE `notes`
  ADD CONSTRAINT `notes_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `notes_ibfk_2` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `student_courses`
--
ALTER TABLE `student_courses`
  ADD CONSTRAINT `student_courses_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `student_courses_ibfk_2` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
