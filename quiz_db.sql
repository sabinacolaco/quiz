-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 21, 2021 at 07:40 PM
-- Server version: 10.4.17-MariaDB
-- PHP Version: 7.3.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `quiz_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `answers`
--

CREATE TABLE `answers` (
  `ans_id` int(11) NOT NULL,
  `answer` varchar(128) NOT NULL,
  `quest_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `answers`
--

INSERT INTO `answers` (`ans_id`, `answer`, `quest_id`) VALUES
(1, 'Php Hypertext Preprocessor', 1),
(2, 'Php Hypermarkup Preprocessor', 1),
(3, 'Php Hypermarkup Processor', 1),
(4, 'Php Hypertext Processor', 1),
(5, ' Server-side', 2),
(6, 'Client-side', 2),
(7, 'Middle-side', 2),
(8, 'Out-side', 2),
(9, 'True', 3),
(10, 'False', 3),
(11, 'True', 4),
(12, 'False', 4),
(13, 'Local', 5),
(14, 'Global', 5),
(15, 'Static', 5),
(16, 'Extern', 5),
(17, 'while', 6),
(18, 'do while', 6),
(19, 'for', 6),
(20, 'do for', 6),
(21, 'It indicates lines that are commented out.', 7),
(22, 'It indicates variable declaration.', 7),
(23, 'It indicates function declaration.', 7),
(24, 'No uses in PHP.', 7),
(25, 'True', 8),
(26, 'False', 8),
(27, 'info()', 9),
(28, 'sysinfo()', 9),
(29, 'phpinfo()', 9),
(30, 'php_info()', 9),
(31, 'It sends output to a variable', 10),
(32, 'It prints the output of program', 10),
(33, 'It sends output to a variable converting into string', 10),
(34, 'It prints the output of program converting into string', 10),
(35, 'Write', 11),
(36, 'Echo', 11),
(37, 'Display', 11),
(38, 'Out', 11),
(39, 'It returns the type of a string', 12),
(40, 'It returns the value of a string', 12),
(41, 'It returns the length of a string', 12),
(42, 'It returns the subset value of a string', 12);

-- --------------------------------------------------------

--
-- Table structure for table `questions`
--

CREATE TABLE `questions` (
  `quest_id` int(11) NOT NULL,
  `question` varchar(255) NOT NULL,
  `correct_answer` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `questions`
--

INSERT INTO `questions` (`quest_id`, `question`, `correct_answer`) VALUES
(1, 'PHP Stands for', 1),
(2, 'PHP is _______ scripting language.', 5),
(3, 'In PHP Language variables are case sensitive', 9),
(4, 'In PHP a variable needs to be declare before assign', 12),
(5, 'Which of the following is not the scope of Variable in PHP?', 16),
(6, 'Which of the following is not PHP Loops', 20),
(7, 'What does the hash (#) sign mean in PHP?', 21),
(8, 'Variables are case-sensitive in PHP?', 25),
(9, 'Which function displays the information about PHP?', 29),
(10, 'What does sprintf() function do in PHP?', 34),
(11, 'Which of the following statements prints in PHP?', 36),
(12, 'What is the use of strlen( ) function in PHP?', 41);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `answers`
--
ALTER TABLE `answers`
  ADD PRIMARY KEY (`ans_id`),
  ADD KEY `quest_id` (`quest_id`);

--
-- Indexes for table `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`quest_id`),
  ADD KEY `correct_answer` (`correct_answer`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `answers`
--
ALTER TABLE `answers`
  MODIFY `ans_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `questions`
--
ALTER TABLE `questions`
  MODIFY `quest_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `answers`
--
ALTER TABLE `answers`
  ADD CONSTRAINT `answers_ibfk_1` FOREIGN KEY (`quest_id`) REFERENCES `questions` (`quest_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `questions`
--
ALTER TABLE `questions`
  ADD CONSTRAINT `questions_ibfk_1` FOREIGN KEY (`correct_answer`) REFERENCES `answers` (`ans_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
