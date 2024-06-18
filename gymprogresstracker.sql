-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Jun 16, 2024 at 06:34 PM
-- Server version: 8.2.0
-- PHP Version: 8.2.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `gymprogresstracker`
--

DELIMITER $$
--
-- Procedures
--
DROP PROCEDURE IF EXISTS `create_exercise_data_tables`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `create_exercise_data_tables` ()   BEGIN
    DECLARE done INT DEFAULT FALSE;
    DECLARE user_id INT;
    DECLARE username VARCHAR(50);
    DECLARE cur CURSOR FOR SELECT `id`, `username` FROM `users`;
    DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;

    OPEN cur;
    read_loop: LOOP
        FETCH cur INTO user_id, username;
        IF done THEN
            LEAVE read_loop;
        END IF;
        SET @table_name = CONCAT('ExerciseData_', username);

        SET @sql = CONCAT('
            CREATE TABLE IF NOT EXISTS `', @table_name, '` (
                `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
                `exercise_name` VARCHAR(100) NOT NULL,
                `best_weight` DECIMAL(10,2),
                `reps` INT,
                `sets` INT,
                `special_notes` TEXT
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        ');

        PREPARE stmt FROM @sql;
        EXECUTE stmt;
        DEALLOCATE PREPARE stmt;
    END LOOP;
    CLOSE cur;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `exercises`
--

DROP TABLE IF EXISTS `exercises`;
CREATE TABLE IF NOT EXISTS `exercises` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `target_muscle_group` varchar(100) NOT NULL,
  `exercise_type` varchar(50) NOT NULL,
  `equipment_required` varchar(100) NOT NULL,
  `mechanics` varchar(50) NOT NULL,
  `force_type` varchar(50) NOT NULL,
  `experience_level` varchar(50) NOT NULL,
  `secondary_muscles` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `exercises`
--

INSERT INTO `exercises` (`id`, `name`, `description`, `target_muscle_group`, `exercise_type`, `equipment_required`, `mechanics`, `force_type`, `experience_level`, `secondary_muscles`) VALUES
(1, 'Barbell Back Squat', 'The squat is the king of all exercises, working over 256 muscles in one movement! From bodybuilders to powerlifters to competitive athletes, the squat is a staple compound exercise and should be in every workout plan.\n\nFor powerlifters, it is known as one of the “big three” lifts which includes the squat, deadlift, and bench press. For athletes, having an explosive squat is a good indicator for on field/court performance. And for bodybuilders, the squat is a compound exercise that targets nearly every muscle of your lower body and core.\n\nThe squat directly targets the muscles of the quads, but also involves the hamstrings, glutes, back, and core as well as muscles of the shoulders and arms to a lesser degree.\n\nNot everyone is built to perform the traditional barbell back squat and it can result in some pain for certain individuals. Over the years, several squatting variations have been developed to help everyone be able to train this critical movement pattern safely.', 'Quads', 'Strength', 'Barbell', 'Compound', 'Push', 'Intermediate', 'Calves, Glutes, Hamstrings, Lower Back'),
(2, 'Military Press', 'The military press is a complete shoulder building exercise perfect for building shoulder muscle. The military press is an exercise with many names and is often referred to as the shoulder press, overhead press, and strict press.\r\n\r\nThe military press is used primarily to build the deltoid muscle. It also indirectly targets the other muscles of the shoulder, your triceps, and your core. Since the military press is completed standing up, it involves a lot of core strength to help stabilize the spine while pressing weight overhead.\r\n\r\nThere are a number of variations to the military press you can use to target the deltoids from different angles and different ways.\r\n\r\nThese military press variations include:\r\n\r\n-Seated Military Press\r\n-Dumbbell Military Press\r\n-Seated Dumbbell Press\r\n-Arnold Press\r\n-Behind The Neck Military Press\r\n-Smith Machine Military Press\r\n\r\nBe sure to add a variation of the military press to your shoulder workout to get the benefits from one of the most complete shoulder exercises out there.', 'Shoulders', 'Strength', 'Barbell', 'Compound', 'Push (Bilateral)', 'Intermediate', 'Abs, Traps, Triceps'),
(3, 'Barbell Bench Press', 'The barbell bench press is a classic exercise popular among all weight lifting circles. From bodybuilders to powerlifters, the bench press is a staple chest exercise in nearly every workout program.\r\n\r\nFor powerlifters, it is known as one of the “big three” lifts which includes the squat, deadlift, and bench press. For athletes, 1 rep max on bench press is a good indicator for on field/court performance. And for bodybuilders, the bench press is a compound exercise that targets many of the muscles in your upper body.\r\n\r\nBy performing the bench press, you primarily work your pectoralis major (your chest). Other muscles which assist in moving the barbell during a bench press are other muscles of the chest, triceps, and shoulders.\r\n\r\nNot everyone is built to perform the traditional barbell bench press, so several variations have been created to ensure people can train this crucial movement pattern in a safe and comfortable way.\r\n\r\nSome of these variations include:\r\n\r\nIncline bench press\r\nDecline bench press,\r\nDumbbell bench press\r\nDumbbell incline bench press\r\nDumbbell decline bench press\r\nSmith machine bench press\r\nThose with shoulder injuries can also utilize many of the alternative barbells out there that place less pressure on the shoulder to bench press. Some of these variations include hex bar bench press, football bar bench press, and Swiss bar bench press.', 'Chest', 'Strength', 'Barbell', 'Compound', 'Push (Bilateral)', 'Intermediate', ' Shoulders, Triceps');

-- --------------------------------------------------------

--
-- Table structure for table `history`
--

DROP TABLE IF EXISTS `history`;
CREATE TABLE IF NOT EXISTS `history` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `date` date NOT NULL,
  `reps` int NOT NULL,
  `sets` int NOT NULL,
  `weight` decimal(10,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `exercise_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_exercise_id` (`exercise_id`),
  KEY `fk_user_id` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `history`
--

INSERT INTO `history` (`id`, `user_id`, `date`, `reps`, `sets`, `weight`, `created_at`, `exercise_id`) VALUES
(1, 2, '2024-06-16', 12, 1, 0.00, '2024-06-16 17:03:55', 1),
(2, 2, '2024-06-16', 12, 3, 0.00, '2024-06-16 17:09:13', 1),
(3, 2, '2024-06-16', 13, 4, 45.00, '2024-06-16 17:11:40', 3),
(4, 2, '2024-06-16', 12, 3, 45.00, '2024-06-16 17:17:36', 1),
(5, 2, '2024-06-16', 12, 3, 55.00, '2024-06-16 17:27:19', 3),
(6, 2, '2024-06-16', 12, 3, 56.00, '2024-06-16 18:19:50', 3),
(7, 2, '2024-06-16', 34, 4, 67.00, '2024-06-16 18:31:45', 3);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(50) CHARACTER SET utf8mb3 NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb3 NOT NULL,
  `email` varchar(100) NOT NULL,
  `date_of_birth` date DEFAULT NULL,
  `weight` decimal(10,2) DEFAULT NULL,
  `sex` enum('Male','Female','Other') DEFAULT NULL,
  `date_of_registration` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`, `date_of_birth`, `weight`, `sex`, `date_of_registration`) VALUES
(2, 'admin', '$2y$10$fsVjGAy4yLH5XA4A3ZbXkeOv56cI2eZOr7ajdNVYFyKwBfBy6HjXu', '', NULL, NULL, NULL, '2024-06-16 14:14:59'),
(3, 'test1', '$2y$10$vv0rKz4XwbGDJ4gcG2YlcOPPHWXmQmktbDd7wpPYZ0EMJlWzWH7jK', '', NULL, NULL, NULL, '2024-06-16 14:14:59'),
(4, 'test2', '$2y$10$dLKO2KUfD2a6GxaCzlKDOusWL3Uw.HwsrpnE8Qq7Di5Xaaj5PrUgS', '', NULL, NULL, NULL, '2024-06-16 14:14:59'),
(5, 'as', '$2y$10$vCVVQsKTXkle7Oy3JuzyoOGrJ00QzVz2u9DIhCbx6kfnydDMgkf5m', '', NULL, NULL, NULL, '2024-06-16 14:40:14');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
