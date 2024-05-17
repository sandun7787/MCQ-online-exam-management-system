-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 17, 2024 at 06:25 AM
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
-- Database: `exam`
--

-- --------------------------------------------------------

--
-- Table structure for table `answer`
--

CREATE TABLE `answer` (
  `id` int(11) NOT NULL,
  `optionvalue` varchar(200) DEFAULT NULL,
  `questionId` int(11) DEFAULT NULL,
  `iscoorect` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `answer`
--

INSERT INTO `answer` (`id`, `optionvalue`, `questionId`, `iscoorect`) VALUES
(5, 'gdggd', 3, '0'),
(6, 'gdgd', 3, '0'),
(7, 'gdgdgd', 3, '1'),
(8, 'gddgg', 3, '0'),
(9, 'gdgddg', 4, '0'),
(10, 'gdgdgd', 4, '1'),
(11, 'gdgdgd', 4, '1'),
(12, 'gdgdgd', 4, '1'),
(13, 'fsfsfs', 5, '1'),
(14, 'asasa', 5, '0'),
(15, 'sasasfsf', 5, '0'),
(16, 'fsfsfs', 5, '1'),
(17, 'addad', 6, '0'),
(18, 'qeqeq', 6, '0'),
(19, 'erete', 6, '0'),
(20, 'gegrrhth', 6, '1'),
(25, 'cznczczmbncz', 8, '0'),
(26, 'nczmczmzcnzmc', 8, '0'),
(27, 'chjzhcjhdjsjhf', 8, '0'),
(28, 'fjksjksjfks', 8, '1'),
(33, 'nvmnmvnemvnemv', 10, '0'),
(34, 'ejvkjfkejfke', 10, '0'),
(35, 'fkejfkejkfj', 10, '1'),
(36, 'fkejfkejkfejkfejfe', 10, '0'),
(41, 'dandmamdmdnam', 12, '0'),
(42, 'ndamdmanmdan', 12, '0'),
(43, 'da,md,a,da', 12, '1'),
(44, 'dnmandmadm', 12, '0'),
(45, 'dandamdandam', 13, '0'),
(46, 'damndadam', 13, '0'),
(47, 'dnamdnmandam', 13, '1'),
(48, 'dnamndmandma', 13, '0'),
(57, 'dnandbandb', 16, '0'),
(58, 'ndandandma', 16, '1'),
(59, 'md,amd,ad,', 16, '0'),
(60, 'dmamdnmadam', 16, '0'),
(61, 'dnmandmad', 17, '0'),
(62, 'dma,dm,amd,a', 17, '1'),
(63, 'hwjhrwjhrjw', 17, '0'),
(64, 'eklwelwkel', 17, '0'),
(69, 'gfdgdgdgdgd', 19, '0'),
(70, 'dgdsds', 19, '0'),
(71, 'gdgd', 19, '0'),
(72, 'gdgdd', 19, '0');

-- --------------------------------------------------------

--
-- Table structure for table `examenrollment`
--

CREATE TABLE `examenrollment` (
  `id` int(11) NOT NULL,
  `student_id` int(30) DEFAULT NULL,
  `exam_id` int(30) DEFAULT NULL,
  `examstatus` varchar(60) DEFAULT NULL,
  `result` varchar(30) DEFAULT NULL,
  `grade` varchar(30) DEFAULT NULL,
  `examresult` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `examenrollment`
--

INSERT INTO `examenrollment` (`id`, `student_id`, `exam_id`, `examstatus`, `result`, `grade`, `examresult`) VALUES
(1, 2, 7, 'attended', '0', 'W', 'Failed'),
(2, 2, 6, 'attended', '100', 'A', 'Passed'),
(3, 3, 7, 'attended', '0', 'W', 'Failed'),
(4, 3, 6, 'attended', '0', 'W', 'Failed'),
(5, 2, 8, 'attended', '0', 'W', 'Failed'),
(6, 3, 8, 'attended', '100', 'A', 'Passed'),
(7, 2, 9, 'attended', '0', 'W', 'Failed'),
(8, 2, 10, 'attended', '50', 'C', 'Passed'),
(9, 2, 11, 'attended', '100', 'A', 'Passed'),
(10, 2, 12, 'attended', '0', 'W', 'Failed');

-- --------------------------------------------------------

--
-- Table structure for table `exams`
--

CREATE TABLE `exams` (
  `id` int(30) NOT NULL,
  `name` varchar(60) DEFAULT NULL,
  `dateandtime` datetime DEFAULT NULL,
  `duration` varchar(30) DEFAULT NULL,
  `teacherid` int(10) NOT NULL,
  `status` varchar(30) NOT NULL,
  `updatedate` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `exams`
--

INSERT INTO `exams` (`id`, `name`, `dateandtime`, `duration`, `teacherid`, `status`, `updatedate`) VALUES
(1, 'KCC-test', '2024-05-13 21:00:00', '60', 1, 'draft', '2024-05-13 15:18:29'),
(2, 'KCC-test-1', '2024-05-13 22:00:00', '90', 1, 'draft', '2024-05-13 16:30:02'),
(3, 'KCC', '2024-05-13 23:00:00', '80', 1, 'draft', '2024-05-13 16:43:58'),
(4, 'KCC-test', '2024-05-14 09:00:00', '80', 4, 'draft', '2024-05-14 03:13:37'),
(5, 'KCC', '2024-05-14 09:00:00', '80', 4, 'End', '2024-05-14 03:22:30'),
(6, 'KCC', '2024-05-14 09:30:00', '80', 4, 'published', '2024-05-14 03:54:36'),
(7, 'KCC-09', '2024-05-14 10:03:00', '50', 4, 'published', '2024-05-14 04:31:52'),
(8, 'http://localhost/mcq/Teacher/Teacher_home.php', '2024-05-14 16:22:00', '20', 4, 'published', '2024-05-14 10:51:12'),
(9, 'KCC-10', '2024-05-14 17:17:00', '60', 4, 'published', '2024-05-14 11:46:16'),
(10, 'KCC-TEST-10', '2024-05-16 09:00:00', '100', 4, 'published', '2024-05-16 03:27:28'),
(11, 'KCC', '2024-05-16 11:03:00', '50', 4, 'End', '2024-05-16 05:31:39'),
(12, 'KCC-10', '2024-05-17 09:41:00', '30', 4, 'End', '2024-05-17 04:08:43');

-- --------------------------------------------------------

--
-- Table structure for table `question`
--

CREATE TABLE `question` (
  `id` int(11) NOT NULL,
  `questionNo` varchar(10) DEFAULT NULL,
  `Question` varchar(200) DEFAULT NULL,
  `examid` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `question`
--

INSERT INTO `question` (`id`, `questionNo`, `Question`, `examid`) VALUES
(1, '1', ' gfgf', 4),
(3, '1', '  gdgddg', 5),
(4, '1', ' dgdgd', 6),
(5, '1', ' ffsfs', 7),
(6, '2', ' sfsfs', 7),
(8, '1', '  xbnzbnzc', 8),
(10, '1', '  jdkjkdfjkdfjdkf', 9),
(12, '1', '  ndnabdnabnda', 10),
(13, '2', ' dnabdanband', 10),
(16, '1', '  jdsdksjdks', 11),
(17, '2', '  dmbandbandbna', 11),
(19, '1', '  jfjf', 12);

-- --------------------------------------------------------

--
-- Table structure for table `role`
--

CREATE TABLE `role` (
  `id` int(60) NOT NULL,
  `usertype` varchar(60) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `role`
--

INSERT INTO `role` (`id`, `usertype`) VALUES
(1, 'Teacher'),
(2, 'Student');

-- --------------------------------------------------------

--
-- Table structure for table `student-answers`
--

CREATE TABLE `student-answers` (
  `id` int(11) NOT NULL,
  `option_id` int(11) DEFAULT NULL,
  `question_Id` int(10) DEFAULT NULL,
  `student_id` int(11) DEFAULT NULL,
  `exam_id` int(11) DEFAULT NULL,
  `question_result` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student-answers`
--

INSERT INTO `student-answers` (`id`, `option_id`, `question_Id`, `student_id`, `exam_id`, `question_result`) VALUES
(1, 15, 5, 2, 7, 'fail'),
(2, 18, 6, 2, 7, 'fail'),
(3, 10, 4, 2, 6, 'Pass'),
(4, 14, 5, 3, 7, 'Fail'),
(5, 17, 6, 3, 7, 'Fail'),
(6, 9, 4, 3, 6, 'Fail'),
(7, 27, 8, 2, 8, 'Fail'),
(8, 28, 8, 3, 8, 'Pass'),
(9, 34, 10, 2, 9, 'fail'),
(10, 41, 12, 2, 10, 'fail'),
(11, 47, 13, 2, 10, 'Pass'),
(12, 58, 16, 2, 11, 'Pass'),
(13, 62, 17, 2, 11, 'Pass'),
(14, 69, 19, 2, 12, 'fail');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(30) NOT NULL,
  `name` varchar(60) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(60) DEFAULT NULL,
  `usertype` varchar(60) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `usertype`) VALUES
(1, 'devika', 'devikateacher@gmail.com', 'e807f1fcf82d132f9bb018ca6738a19f', 'Teacher'),
(2, 'Sandun', 'sandunstudent@gmail.com', 'e807f1fcf82d132f9bb018ca6738a19f', 'Student'),
(3, 'Deshan', 'deshanstudent@gmail.com', 'e807f1fcf82d132f9bb018ca6738a19f', 'Student'),
(4, 'Hahsini', 'hashiniteacher@gmail.com', 'e807f1fcf82d132f9bb018ca6738a19f', 'Teacher');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `answer`
--
ALTER TABLE `answer`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `examenrollment`
--
ALTER TABLE `examenrollment`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `exams`
--
ALTER TABLE `exams`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `question`
--
ALTER TABLE `question`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `student-answers`
--
ALTER TABLE `student-answers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `answer`
--
ALTER TABLE `answer`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=73;

--
-- AUTO_INCREMENT for table `examenrollment`
--
ALTER TABLE `examenrollment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `exams`
--
ALTER TABLE `exams`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `question`
--
ALTER TABLE `question`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `role`
--
ALTER TABLE `role`
  MODIFY `id` int(60) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `student-answers`
--
ALTER TABLE `student-answers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
