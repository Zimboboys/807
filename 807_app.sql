-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Sep 08, 2019 at 04:08 AM
-- Server version: 10.2.10-MariaDB
-- PHP Version: 7.2.17

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `807_app`
--
CREATE DATABASE IF NOT EXISTS `807_app` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `807_app`;

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--

CREATE TABLE `attendance` (
  `attendanceID` int(10) UNSIGNED NOT NULL,
  `eventID` int(10) UNSIGNED NOT NULL,
  `leaderID` int(10) UNSIGNED NOT NULL,
  `memberID` int(10) UNSIGNED NOT NULL,
  `attended` int(3) NOT NULL COMMENT '0-attended / 1-late / 2-noshow'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `attendance_groups`
--

CREATE TABLE `attendance_groups` (
  `groupID` int(10) UNSIGNED NOT NULL,
  `groupName` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `attendance_groups`
--

INSERT INTO `attendance_groups` (`groupID`, `groupName`) VALUES
(3, 'everyone'),
(5, 'alpha'),
(6, 'beta'),
(7, 'gamma');

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `eventID` int(10) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `datetime` datetime DEFAULT NULL,
  `memberGroups` varchar(255) DEFAULT NULL COMMENT 'period separated values'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`eventID`, `title`, `datetime`, `memberGroups`) VALUES
(1, 'Rehearsal', '2019-08-08 04:18:00', 'g_3.g_5.g_6.g_7.'),
(2, 'Summer Football Game', '2019-08-05 02:00:00', ''),
(3, 'Current Event', '2019-09-07 01:40:00', 'g_5.g_6.');

-- --------------------------------------------------------

--
-- Table structure for table `member_groups`
--

CREATE TABLE `member_groups` (
  `groupingID` int(10) UNSIGNED NOT NULL,
  `userID` int(10) UNSIGNED NOT NULL,
  `groupID` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `stations`
--

CREATE TABLE `stations` (
  `stationID` int(10) UNSIGNED NOT NULL,
  `name` varchar(120) NOT NULL,
  `number` int(11) NOT NULL,
  `maxFailed` smallint(5) UNSIGNED NOT NULL DEFAULT 0,
  `hidden` tinyint(11) NOT NULL DEFAULT 0,
  `olderSibling` int(11) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `stations`
--

INSERT INTO `stations` (`stationID`, `name`, `number`, `maxFailed`, `hidden`, `olderSibling`) VALUES
(63, 'Station 1: Stationary Basics', 1, 2, 0, 0),
(64, 'Station 2: Stationary Basics Pt. 2', 2, 2, 0, 1),
(65, 'Station 3: Beginnings of Movement', 3, 3, 0, 2),
(66, 'Station 4: Directions of Movement', 4, 2, 0, 3),
(67, 'Station 6: Advanced Marching Technique', 6, 2, 0, 5),
(68, 'Station 5: Intermediate Marching Technique', 5, 3, 0, 4);

-- --------------------------------------------------------

--
-- Table structure for table `station_attempts`
--

CREATE TABLE `station_attempts` (
  `evaluationID` int(10) UNSIGNED NOT NULL,
  `leaderID` int(10) UNSIGNED NOT NULL COMMENT 'evaluator',
  `memberID` int(10) UNSIGNED NOT NULL COMMENT 'evaluee',
  `stationID` int(10) UNSIGNED NOT NULL,
  `passedChecks` varchar(1024) DEFAULT NULL COMMENT 'array of passed',
  `failedChecks` varchar(1024) DEFAULT NULL COMMENT 'array of failed',
  `attemptTime` datetime NOT NULL DEFAULT current_timestamp(),
  `hidden` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `station_checklist_group`
--

CREATE TABLE `station_checklist_group` (
  `groupID` int(10) UNSIGNED NOT NULL,
  `stationID` int(11) UNSIGNED NOT NULL,
  `title` varchar(60) NOT NULL,
  `hidden` tinyint(4) NOT NULL DEFAULT 0,
  `olderSibling` int(11) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `station_checklist_group`
--

INSERT INTO `station_checklist_group` (`groupID`, `stationID`, `title`, `hidden`, `olderSibling`) VALUES
(42, 63, 'Relax', 0, NULL),
(43, 63, 'Attention', 0, NULL),
(44, 63, 'Parade Rest', 0, NULL),
(45, 63, 'Called Horns Up', 0, NULL),
(46, 63, 'Called Horns Down', 0, NULL),
(47, 63, 'Visual Horns Up', 0, NULL),
(48, 63, 'Visual Horns Down', 0, NULL),
(49, 63, 'Slow Horns Up', 0, NULL),
(50, 63, 'Slow Horns Down', 0, NULL),
(51, 63, 'Kick, Down, Up', 0, NULL),
(52, 63, 'Carry Position', 0, NULL),
(53, 64, 'Left Face', 0, NULL),
(54, 65, 'Forward March', 0, NULL),
(55, 66, 'Backward March', 0, NULL),
(57, 67, 'Cal Poly Attention', 0, NULL),
(58, 64, 'Right Face', 0, NULL),
(59, 64, 'About Face', 0, NULL),
(60, 64, 'Mark Time', 0, NULL),
(61, 64, 'Executed in Time with Verbal', 0, NULL),
(62, 64, 'Slow Turn, Left', 0, NULL),
(63, 64, 'Slow Turn, Right', 0, NULL),
(64, 65, 'Left Flank', 0, NULL),
(65, 65, 'Right Flank', 0, NULL),
(66, 65, 'To the Rear', 0, NULL),
(67, 65, 'Adjusted Step Size', 0, NULL),
(68, 65, 'Halt', 0, NULL),
(69, 66, 'Front-to-Back Transition', 0, NULL),
(70, 66, 'Back-to-Front Transition', 0, NULL),
(73, 68, 'Forward Left Slide', 0, NULL),
(74, 68, 'Forward Right Slide', 0, NULL),
(75, 68, 'Backward Left Slide', 0, NULL),
(76, 68, 'Backward Right Slide', 0, NULL),
(77, 67, 'Cal Poly High Step Mark Time', 0, NULL),
(78, 67, 'Cal Poly High Step Forward March', 0, NULL),
(79, 67, 'Run On Simulation', 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `station_checklist_item`
--

CREATE TABLE `station_checklist_item` (
  `checklistID` int(10) UNSIGNED NOT NULL,
  `stationGroupID` int(11) UNSIGNED NOT NULL,
  `item` text NOT NULL,
  `required` tinyint(4) NOT NULL DEFAULT 0,
  `hidden` tinyint(4) NOT NULL DEFAULT 0,
  `olderSibling` int(11) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `station_checklist_item`
--

INSERT INTO `station_checklist_item` (`checklistID`, `stationGroupID`, `item`, `required`, `hidden`, `olderSibling`) VALUES
(109, 42, 'Right Foot on Dot', 0, 0, 0),
(110, 43, 'Executed in Time', 0, 0, 0),
(111, 43, 'Verbal Loud and Clear', 0, 0, NULL),
(112, 43, 'Heels Together', 0, 0, NULL),
(114, 43, 'Toes Apart (45 Degrees)', 0, 0, NULL),
(115, 43, 'Knees Unlocked', 0, 0, NULL),
(116, 43, 'Hips Centered', 0, 0, NULL),
(117, 43, 'Arms Comfortably Bent', 0, 0, NULL),
(118, 43, 'Hands at Side in Fists', 0, 0, NULL),
(119, 43, 'Eyes Above Horizon', 0, 0, NULL),
(120, 44, 'Executed in Time', 0, 0, 0),
(121, 44, 'Verbal Clear and Loud', 0, 0, NULL),
(122, 44, 'Right Foot on Dot (Moved Left Foot)', 0, 0, NULL),
(124, 44, 'Feet Shoulder Width Apart', 0, 0, NULL),
(125, 44, 'Hands Clasped in Front', 0, 0, NULL),
(126, 45, 'Executed in Time', 0, 0, 0),
(127, 45, 'Verbal Loud and Clear', 0, 0, NULL),
(128, 45, 'Correct Horn Angle', 0, 0, NULL),
(129, 46, 'Executed in Time', 0, 0, 0),
(130, 46, 'Returned to Attention Position', 0, 0, NULL),
(131, 47, 'Executed in Time with Verbal', 0, 0, 0),
(132, 47, 'Correct Horn Angle', 0, 0, NULL),
(133, 48, 'Executed in Time with Verbal', 0, 0, 0),
(134, 48, 'Returned to Attention Position', 0, 0, NULL),
(135, 49, 'Executed in Time with Lead', 0, 0, 0),
(136, 49, 'Correct Horn Angle', 0, 0, NULL),
(137, 50, 'Executed in Time with Lead', 0, 0, 0),
(138, 50, 'Returned to Attention Position', 0, 0, NULL),
(139, 51, 'Executed in Time', 0, 0, 0),
(140, 51, 'Verbal Loud and Clear', 0, 0, NULL),
(141, 51, 'Foot Kicked Out for Legs forming 45 degree angle', 0, 0, NULL),
(142, 51, 'Correct Horn Angle', 0, 0, NULL),
(143, 51, 'Clear \"Down\" Position', 0, 0, NULL),
(144, 52, 'Correct Horn Placement', 0, 0, 0),
(145, 52, 'Correct Hand Placement', 0, 0, NULL),
(146, 53, 'Executed in Time with Verbal', 0, 0, 0),
(147, 54, 'Horn up on 1 with Verbal', 0, 0, 0),
(148, 55, 'Horn up on 1 with Verbal', 0, 0, 0),
(150, 57, 'Verbal Loud and Clear', 0, 0, 0),
(151, 53, 'Clean Pivot on Left Heel Right Toe', 0, 0, NULL),
(152, 53, 'Returned to Attention Position', 0, 0, NULL),
(153, 58, 'Executed in Time with Verbal', 0, 0, 0),
(154, 58, 'Clean Pivot on Right Heel Left Toe', 0, 0, NULL),
(155, 58, 'Returned to Attention Position', 0, 0, NULL),
(156, 59, 'Executed in Time with Verbal', 0, 0, 0),
(157, 59, 'Clean Pivot on Toes', 0, 0, NULL),
(158, 59, 'Returned to Attention Position', 0, 0, NULL),
(159, 60, 'Executed in Time with Verbal', 0, 0, 0),
(160, 60, 'Feet Parallel', 0, 0, NULL),
(161, 60, 'Toes on the Ground Entire Time', 0, 0, NULL),
(162, 60, 'Heels Come Up to Ankle Bone', 0, 0, NULL),
(163, 61, 'Executed in Time with Verbal', 0, 0, 0),
(164, 61, 'Body Steady (No extra swaying)', 0, 0, NULL),
(165, 61, 'Feet Back at 45 Degree Angle', 0, 0, NULL),
(166, 61, 'Returned to the Attention Position', 0, 0, NULL),
(167, 62, 'Executed in Time', 0, 0, 0),
(168, 62, 'Feet in Low Mark Time', 0, 0, NULL),
(169, 62, 'Rotated to Correct Position', 0, 0, NULL),
(170, 62, 'Returned to Attention Position', 0, 0, NULL),
(171, 63, 'Executed in Time', 0, 0, 0),
(172, 63, 'Feet in Low Mark Time', 0, 0, NULL),
(173, 63, 'Rotated to Correct Position', 0, 0, NULL),
(174, 63, 'Returned to Attention Position', 0, 0, NULL),
(175, 54, 'Feet in Time Throughout', 1, 0, NULL),
(176, 54, 'Clean Glide Step (high toes, foot rolled through, feet/heels not dragged)', 1, 0, NULL),
(177, 54, 'Arches hit yard lines', 1, 0, NULL),
(178, 54, 'Consistent Step Size', 0, 0, NULL),
(179, 64, 'Executed in Time', 0, 0, 0),
(180, 64, 'Clean Pivot', 0, 0, NULL),
(181, 64, 'Quick, Snappy Motion', 0, 0, NULL),
(182, 64, 'Body Stable Throughout', 0, 0, NULL),
(183, 65, 'Executed in Time', 0, 0, 0),
(184, 65, 'Clean Pivot', 0, 0, NULL),
(185, 65, 'Quick, Snappy Motion', 0, 0, NULL),
(186, 65, 'Body Stable Throughout', 0, 0, NULL),
(187, 66, 'Executed in Time', 0, 0, 0),
(188, 66, 'Clean Pivot', 0, 0, NULL),
(189, 66, 'Quick, Snappy Motion', 0, 0, NULL),
(190, 66, 'Body Stable Throughout', 0, 0, NULL),
(191, 67, 'Executed in Time', 0, 0, 0),
(192, 67, 'Consistent Step Size', 1, 0, NULL),
(193, 67, 'Body Stable Throughout', 0, 0, NULL),
(194, 67, '12-5 is a step (not a shuffle)', 0, 0, NULL),
(195, 68, 'Executed in Time with Verbal', 0, 0, 0),
(196, 68, 'Returned to the Attention Position', 0, 0, NULL),
(197, 68, 'Horn Down', 0, 0, NULL),
(198, 55, 'Feet in Time Throughout', 0, 0, NULL),
(199, 55, 'Up on Platform (soles of feet, heels off the ground)', 1, 0, NULL),
(200, 55, 'Arches hit yardlines', 0, 0, NULL),
(201, 55, 'Body Stable Throughout', 0, 0, NULL),
(202, 55, 'Step Size Consistent', 0, 0, NULL),
(203, 69, 'Rolled Through Final Step', 0, 0, 0),
(204, 69, 'Up on Platforms', 0, 0, NULL),
(205, 69, 'Clear Pause on Deadbeat', 1, 0, NULL),
(206, 69, 'Body Stable Throughout', 0, 0, NULL),
(207, 70, 'Rolled back down to full foot', 0, 0, 0),
(208, 70, 'Left Heel Replanted on 1', 0, 0, NULL),
(209, 70, 'Body Stable Throughout', 0, 0, NULL),
(210, 70, 'Ended manuever on correct yardline/splitting', 0, 0, NULL),
(213, 73, 'Upper Body and Hips Rotated', 1, 0, 0),
(214, 73, 'Clean Pivot', 0, 0, NULL),
(215, 73, 'Executed in Time', 0, 0, NULL),
(216, 73, 'Correct Step Size', 1, 0, NULL),
(217, 73, 'Body Stable Throughout', 0, 0, NULL),
(218, 73, 'Marched Straight (No Drift)', 0, 0, NULL),
(219, 74, 'Upper Body and Hips Rotated', 1, 0, 0),
(220, 74, 'Clean Pivot', 0, 0, NULL),
(221, 74, 'Executed in Time', 0, 0, NULL),
(222, 74, 'Correct Step Size', 1, 0, NULL),
(223, 74, 'Body Stable Throughout', 0, 0, NULL),
(224, 74, 'Marched Straight (No Drift)', 0, 0, NULL),
(225, 75, 'Upper Body and Hips Rotated', 1, 0, 0),
(226, 75, 'Clean Pivot', 0, 0, NULL),
(227, 75, 'Executed in Time', 0, 0, NULL),
(228, 75, 'Correct Step Size', 1, 0, NULL),
(229, 75, 'Body Stable Throughout', 0, 0, NULL),
(230, 75, 'Marched Straight (No Drift)', 0, 0, NULL),
(231, 76, 'Upper Body and Hips Rotated', 1, 0, 0),
(232, 76, 'Clean Pivot', 0, 0, NULL),
(233, 76, 'Executed in Time', 0, 0, NULL),
(234, 76, 'Correct Step Size', 1, 0, NULL),
(235, 76, 'Body Stable Throughout', 0, 0, NULL),
(236, 76, 'Marched Straight (No Drift)', 0, 0, NULL),
(237, 57, 'Executed in Time', 0, 0, NULL),
(238, 57, 'Knee Angle Set at 120 Degrees', 1, 0, NULL),
(239, 57, 'Thigh Parallel to the Ground', 0, 0, NULL),
(240, 57, 'Toes Pointed with Leg', 0, 0, NULL),
(241, 57, 'Body Stable Throughout', 0, 0, NULL),
(242, 77, 'Verbal Loud and Clear on Prep and Halt', 0, 0, 0),
(243, 77, 'Executed in Time', 0, 0, NULL),
(244, 77, 'Knee Angle Set at 120 Degrees', 1, 0, NULL),
(245, 77, 'Thigh Parallel to Ground', 0, 0, NULL),
(246, 77, 'Toes Pointed with Leg', 0, 0, NULL),
(247, 77, 'Body Stable Throughout', 0, 0, NULL),
(248, 78, 'Verbal Loud and Clear on Prep and Halt', 0, 0, 0),
(249, 78, 'Correct Step Size', 0, 0, NULL),
(250, 78, 'Executed in Time', 0, 0, NULL),
(251, 78, 'Body Stable Throughout', 0, 0, NULL),
(252, 78, 'Knee Angle Set at 120 Degrees', 1, 0, NULL),
(253, 79, 'Verbal Loud and Clear on Prep and Kick Down Up', 0, 0, 0),
(254, 79, 'Prep Step Clearly Visible', 0, 0, NULL),
(255, 79, 'Executed in Time with Tempo', 0, 0, NULL),
(256, 79, 'Thigh Parallel to Ground', 0, 0, NULL),
(257, 79, 'Consistent Step Size', 0, 0, NULL),
(258, 79, 'Knee Angle Set at 120 Degrees', 1, 0, NULL),
(259, 79, 'Body Stable Throughout', 0, 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `station_info`
--

CREATE TABLE `station_info` (
  `infoID` int(10) UNSIGNED NOT NULL,
  `stationID` int(10) UNSIGNED NOT NULL,
  `infoText` varchar(4096) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `station_info`
--

INSERT INTO `station_info` (`infoID`, `stationID`, `infoText`) VALUES
(5, 63, 'This station contains the basics of stationary movements. This station is the foundation for all stations moving forward and clean form is critical to marching success. '),
(6, 64, 'This station completes the stationary moves available to all members. Mark time is a critical move as it sets the foundation for forward march and therefore all moving commands. Faces/haces are used in the Pregame routine so be sure to perfect your form.'),
(7, 65, 'Station 3 forms the foundation of all moving commands. Pay close attention to the form of our forward march commands as it remains consistent in all of our maneuvers. Mastery of these moving basics will ease learning in more difficult on-the-move commands.'),
(8, 66, 'Mastery of Station 4 material comes from a deep knowledge of the basics found in station 3. Encourage marchers to engage their core to keep their form clear in backwards march maneuvers. Engaging the core will also help maintain form in the transitions between forwards and backwards marching.'),
(10, 67, 'This station contains the Cal Poly Specific techniques that create our amazing run on visual effects. Engage your core, and be sure to stretch before attempting this station!'),
(13, 68, 'This station focuses on the techniques necessary for field show performances. Lock in those sliding skills by focusing on hip and shoulder orientation and engaging the core to ensure back stability.');

-- --------------------------------------------------------

--
-- Table structure for table `station_packet`
--

CREATE TABLE `station_packet` (
  `instructionID` int(10) UNSIGNED NOT NULL,
  `stationID` int(10) UNSIGNED NOT NULL,
  `role` varchar(30) NOT NULL COMMENT 'instructor or evaluator',
  `purpose` varchar(30) NOT NULL COMMENT 'environment or script'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `station_packet`
--

INSERT INTO `station_packet` (`instructionID`, `stationID`, `role`, `purpose`) VALUES
(21, 63, 'instructor', 'environment'),
(22, 63, 'instructor', 'script'),
(23, 63, 'evaluator', 'environment'),
(24, 63, 'evaluator', 'script'),
(25, 64, 'instructor', 'environment'),
(26, 64, 'instructor', 'script'),
(27, 64, 'evaluator', 'environment'),
(28, 64, 'evaluator', 'script'),
(29, 65, 'instructor', 'environment'),
(30, 65, 'instructor', 'script'),
(31, 65, 'evaluator', 'environment'),
(32, 65, 'evaluator', 'script'),
(33, 66, 'instructor', 'environment'),
(34, 66, 'instructor', 'script'),
(35, 66, 'evaluator', 'environment'),
(36, 66, 'evaluator', 'script'),
(41, 67, 'instructor', 'environment'),
(42, 67, 'instructor', 'script'),
(43, 67, 'evaluator', 'environment'),
(44, 67, 'evaluator', 'script'),
(53, 68, 'instructor', 'environment'),
(54, 68, 'instructor', 'script'),
(55, 68, 'evaluator', 'environment'),
(56, 68, 'evaluator', 'script');

-- --------------------------------------------------------

--
-- Table structure for table `station_packet_content`
--

CREATE TABLE `station_packet_content` (
  `contentID` int(10) UNSIGNED NOT NULL,
  `instructionID` int(10) UNSIGNED NOT NULL,
  `commandText` varchar(4096) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `station_packet_content`
--

INSERT INTO `station_packet_content` (`contentID`, `instructionID`, `commandText`) VALUES
(17, 21, 'Teach your section in either a small block or circle. Have any available older members split between in the group and correcting others.'),
(18, 22, 'instructor script text'),
(19, 23, 'evaluator environment setup explanation'),
(20, 24, 'evaluator script'),
(21, 25, 'Set your marchers in a block. The lead instructor should be on the front facing side of the block while 2-4 assistants hover around AND THROUGH the block pulling marchers aside as necessary.'),
(22, 26, 'View the instructional cards for this station here.\n\nThe Commands to be taught are:\n\n1) Left Face\n2) Right Face\n3) About Face\n4) Mark Time\n5) Halt\n6) Slow Turn, Left\n7) Slow Turn, Right'),
(23, 27, 'Set marchers up in a block. Have evaluators directly in front or directly to the side of marchers. NOTE: Marchers arches should never wander from the yardline they began on.'),
(24, 28, 'The commands for this evaluation are:\n\n1) Left Hace\n2) Right Hace\n3) About Hace\n4) Left Hace\n5) About Hace\n6) Right Hace\n7) Your instructions are: \nMark time 16 \nand Halt\n8) Your instructions are: \nMark time 8\nSlow turn left for 180 degrees for 8 counts\nand Halt\n9) Your instructions are:\nMark time 8\nSlow turn right for 90 degrees for 8 counts\nand Halt'),
(25, 29, 'Set your marchers up in a block. If possible, do not have any marchers split at first. Once marchers have begun to feel the 8-5 size, you may begin to add splitting yardlines in. Be sure to pull marchers out of the block if they exhibit tendencies to stab into halts or flanks or any petal step or other alternative forms.'),
(26, 30, 'View the instructional cards for this station here.\n\nMoves to be taught in this station:\n\n1) Forward March\n2) Left Flank\n3) Right Flank\n4) To the Rear\n5) Adjusted Step Sizes (12-5 and 6-5)\n'),
(27, 31, 'Set marchers up in a straight line on a yardline. Evaluators should be facing marchers.'),
(28, 32, 'The commands for this station evaluation are:\n\n1) Your instructions are:\nForward March 8\nand Halt\n\n2) Your instructions are:\nForward March 8\nMark time 8\nForward March 8\nand Halt\n\n3) Your instructions are:\nForward March 8\nLeft Flank\nForward March 8\nRight Flank\nForward March 8\nand Halt\n\n4) Your instructions are:\nForward March 8\nTo the Rear\nForward March 8\nand Halt\n\n5) Your instructions are:\nForward March 6 at a 6 to 5\nForward March 12 at a 12 to 5\nand Halt'),
(29, 33, 'Set the group up in a block (with splitting). Make sure you have enough yardlines to march 15 yards forward.'),
(30, 34, 'View the instructional cards for this station here.\n\nThe commands to be taught in this station are:\n1) Backwards march\n2) Forwards march to backwards march transition\n3) Backwards march to forwards march transition'),
(31, 35, 'Set your marchers up in a straight line on a yardline. Evaluators should be facing their marchers.'),
(32, 36, 'The commands for this evaluation are:\n\n1) Your instructions are:\nForward March 8\nBackward March 8\nForward March 8\nBackward March 8\nand Halt'),
(37, 41, 'Set your marchers up in a block. You will require at least 4 yardlines for simulated run on. '),
(38, 42, 'You can find the instruction cards for this station here.\n\nThe commands to be taught in this station are:\n1) Cal Poly Attention\n2) Cal Poly High Step Mark Time\n3) Cal Poly High Step Forward March\n4) Simulated Run On\n'),
(39, 43, 'Set your marchers up in a line beginning on a yardline. Ensure that you have at least 4 yardlines of clear space for evaluation.'),
(40, 44, 'The commands for this station are:\n\n1) This will be a Cal Poly Attention:\nBand-Ten-Hut!\n2) Your instructions are:\nCal Poly High Step Mark Time for 16 Counts\nand Halt\n3) Your instructions are:\nCal Poly High Step Forward March for 16 Counts\nand Halt\n4) This is a simulation of our Run On Manuever:\nYou will High Step at an 8-5 for 32 counts at Run On Tempo\nYou will then High Step Mark time for 8 counts at Run On Tempo\nFinally You will execute the Kick-Down-Up in sync with the drum halt\n'),
(49, 53, 'Set your marchers up in a block. Try to avoid splitting for the first couple run throughs.'),
(50, 54, 'View the instructional cards for this station here.\n\nThe commands to be taught in this station are:\n1) Forward Left Slide\n2) Forward Right Slide\n3) Backward Left Slide\n4) Backward Right Slide'),
(51, 55, 'Set your marchers up in a line with evaluators facing them. Ensure that your evaluation path is clear before having marchers execute instructions.'),
(52, 56, 'Give the following commands for this station evaluation:\n\n1) Your instructions are:\nForward March 8\nForward Left Slide 8\nForward March 8\nand Halt\n2) Your instructions are:\nForward March 8\nForward Right Slide 8\nForward March 8\nBackward Right Slide 8\nand Halt\n3) Your instructions are:\nForward March 8\nBackward Left Slide 8\nand Halt');

-- --------------------------------------------------------

--
-- Table structure for table `station_progress`
--

CREATE TABLE `station_progress` (
  `progressID` int(10) UNSIGNED NOT NULL,
  `userID` int(10) UNSIGNED NOT NULL,
  `evaluationID` int(10) UNSIGNED NOT NULL,
  `stationID` int(10) UNSIGNED NOT NULL,
  `progress` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `userID` int(10) UNSIGNED NOT NULL,
  `username` varchar(255) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `section` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `attendance`
--
ALTER TABLE `attendance`
  ADD PRIMARY KEY (`attendanceID`);

--
-- Indexes for table `attendance_groups`
--
ALTER TABLE `attendance_groups`
  ADD PRIMARY KEY (`groupID`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`eventID`);

--
-- Indexes for table `member_groups`
--
ALTER TABLE `member_groups`
  ADD PRIMARY KEY (`groupingID`);

--
-- Indexes for table `stations`
--
ALTER TABLE `stations`
  ADD PRIMARY KEY (`stationID`);

--
-- Indexes for table `station_attempts`
--
ALTER TABLE `station_attempts`
  ADD PRIMARY KEY (`evaluationID`),
  ADD KEY `station_attempts_ibfk_1` (`leaderID`),
  ADD KEY `memberID` (`memberID`),
  ADD KEY `station_attempts_ibfk_3` (`stationID`);

--
-- Indexes for table `station_checklist_group`
--
ALTER TABLE `station_checklist_group`
  ADD PRIMARY KEY (`groupID`),
  ADD KEY `stationID` (`stationID`);

--
-- Indexes for table `station_checklist_item`
--
ALTER TABLE `station_checklist_item`
  ADD PRIMARY KEY (`checklistID`),
  ADD KEY `stationGroupID` (`stationGroupID`);

--
-- Indexes for table `station_info`
--
ALTER TABLE `station_info`
  ADD PRIMARY KEY (`infoID`),
  ADD KEY `stationID` (`stationID`);

--
-- Indexes for table `station_packet`
--
ALTER TABLE `station_packet`
  ADD PRIMARY KEY (`instructionID`),
  ADD KEY `stationID` (`stationID`);

--
-- Indexes for table `station_packet_content`
--
ALTER TABLE `station_packet_content`
  ADD PRIMARY KEY (`contentID`),
  ADD KEY `instructionID` (`instructionID`);

--
-- Indexes for table `station_progress`
--
ALTER TABLE `station_progress`
  ADD PRIMARY KEY (`progressID`),
  ADD KEY `userID` (`userID`),
  ADD KEY `station_progress_ibfk_3` (`stationID`),
  ADD KEY `station_progress_ibfk_2` (`evaluationID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`userID`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `attendance`
--
ALTER TABLE `attendance`
  MODIFY `attendanceID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `attendance_groups`
--
ALTER TABLE `attendance_groups`
  MODIFY `groupID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `eventID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `member_groups`
--
ALTER TABLE `member_groups`
  MODIFY `groupingID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stations`
--
ALTER TABLE `stations`
  MODIFY `stationID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=69;

--
-- AUTO_INCREMENT for table `station_attempts`
--
ALTER TABLE `station_attempts`
  MODIFY `evaluationID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=66;

--
-- AUTO_INCREMENT for table `station_checklist_group`
--
ALTER TABLE `station_checklist_group`
  MODIFY `groupID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=80;

--
-- AUTO_INCREMENT for table `station_checklist_item`
--
ALTER TABLE `station_checklist_item`
  MODIFY `checklistID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=260;

--
-- AUTO_INCREMENT for table `station_info`
--
ALTER TABLE `station_info`
  MODIFY `infoID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `station_packet`
--
ALTER TABLE `station_packet`
  MODIFY `instructionID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT for table `station_packet_content`
--
ALTER TABLE `station_packet_content`
  MODIFY `contentID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT for table `station_progress`
--
ALTER TABLE `station_progress`
  MODIFY `progressID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `userID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `station_attempts`
--
ALTER TABLE `station_attempts`
  ADD CONSTRAINT `station_attempts_ibfk_1` FOREIGN KEY (`leaderID`) REFERENCES `users` (`userID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `station_attempts_ibfk_2` FOREIGN KEY (`memberID`) REFERENCES `users` (`userID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `station_attempts_ibfk_3` FOREIGN KEY (`stationID`) REFERENCES `stations` (`stationID`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `station_checklist_group`
--
ALTER TABLE `station_checklist_group`
  ADD CONSTRAINT `station_checklist_group_ibfk_1` FOREIGN KEY (`stationID`) REFERENCES `stations` (`stationID`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `station_checklist_item`
--
ALTER TABLE `station_checklist_item`
  ADD CONSTRAINT `station_checklist_item_ibfk_1` FOREIGN KEY (`stationGroupID`) REFERENCES `station_checklist_group` (`groupID`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `station_info`
--
ALTER TABLE `station_info`
  ADD CONSTRAINT `station_info_ibfk_1` FOREIGN KEY (`stationID`) REFERENCES `stations` (`stationID`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `station_packet`
--
ALTER TABLE `station_packet`
  ADD CONSTRAINT `station_packet_ibfk_1` FOREIGN KEY (`stationID`) REFERENCES `stations` (`stationID`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `station_packet_content`
--
ALTER TABLE `station_packet_content`
  ADD CONSTRAINT `station_packet_content_ibfk_1` FOREIGN KEY (`instructionID`) REFERENCES `station_packet` (`instructionID`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `station_progress`
--
ALTER TABLE `station_progress`
  ADD CONSTRAINT `station_progress_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `users` (`userID`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `station_progress_ibfk_2` FOREIGN KEY (`evaluationID`) REFERENCES `station_attempts` (`evaluationID`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `station_progress_ibfk_3` FOREIGN KEY (`stationID`) REFERENCES `stations` (`stationID`) ON DELETE CASCADE ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
