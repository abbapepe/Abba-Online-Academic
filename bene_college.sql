-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 15, 2025 at 01:43 PM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.1.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bene_college`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_users`
--

CREATE TABLE `admin_users` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin_users`
--

INSERT INTO `admin_users` (`id`, `username`, `password`) VALUES
(1, 'admin', '$2y$10$G6OpubpKLS55RsFF5gh3u.AGL4mTZxPuwNbQrdtsqj/x9i3h3vP/6');

-- --------------------------------------------------------

--
-- Table structure for table `applications`
--

CREATE TABLE `applications` (
  `id` int(11) NOT NULL,
  `fullname` varchar(150) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `status` varchar(20) DEFAULT 'pending',
  `dob` date DEFAULT NULL,
  `gender` varchar(10) DEFAULT NULL,
  `state` varchar(50) DEFAULT NULL,
  `lga` varchar(50) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `school` varchar(150) DEFAULT NULL,
  `qualification` varchar(100) DEFAULT NULL,
  `course` varchar(100) DEFAULT NULL,
  `olevel_json` text DEFAULT NULL,
  `uploads_json` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `applications`
--

INSERT INTO `applications` (`id`, `fullname`, `email`, `phone`, `status`, `dob`, `gender`, `state`, `lga`, `address`, `school`, `qualification`, `course`, `olevel_json`, `uploads_json`, `created_at`) VALUES
(1, 'IDRIS ABUBAKAR ABDULAZIZ', 'idrisabdulazizabubakar@gmail.com', '7067321789', 'pending', '1992-01-01', 'Male', 'Plateau', 'Shendam', '07067327123', 'Gss Shendam', 'Secondary Certificate', 'Community Health', '{\"first_sitting\":{\"english\":\"A1\",\"maths\":\"B2\",\"chemistry\":\"B3\",\"physics\":\"C4\",\"biology\":\"C5\",\"others\":{\"subject\":\"Computer\",\"grade\":\"C6\"}},\"second_sitting\":{\"english\":\"\",\"maths\":\"\",\"chemistry\":\"\",\"physics\":\"\",\"biology\":\"\",\"others\":{\"subject\":\"\",\"grade\":\"\"}}}', '{\"passport\":\"uploads\\/1762687312_pass.jpg\",\"olevel\":\"uploads\\/1762687312_WaecResult.jpg\",\"primarycert\":\"uploads\\/1762687312_pri_cert.jpg\",\"indigene\":\"uploads\\/1762687312_ind_form.jpg\",\"birthcert\":\"uploads\\/1762687312_bd_cert.jpg\"}', '2025-11-09 11:21:52'),
(2, 'Musa', 'musa@gmail.com', '8012345678', 'pending', '2000-01-01', 'Male', 'Plateau', 'Kanke', 'State Lowcost', 'Gss Kanke', 'Secondary Certificate', 'Medical Laboratory Technology', '{\"first_sitting\":{\"english\":\"B2\",\"maths\":\"B3\",\"chemistry\":\"D7\",\"physics\":\"D7\",\"biology\":\"C5\",\"others\":{\"subject\":\"Computer\",\"grade\":\"C6\"}},\"second_sitting\":{\"english\":\"A1\",\"maths\":\"A1\",\"chemistry\":\"C5\",\"physics\":\"B2\",\"biology\":\"E8\",\"others\":{\"subject\":\"Computer\",\"grade\":\"C6\"}}}', '{\"passport\":\"uploads\\/1762688885_pass.jpg\",\"olevel\":\"uploads\\/1762688885_WaecResult.jpg\",\"primarycert\":\"uploads\\/1762688885_pri_cert.jpg\",\"indigene\":\"uploads\\/1762688885_ind_form.jpg\",\"birthcert\":\"uploads\\/1762688885_bd_cert.jpg\"}', '2025-11-09 11:48:05'),
(3, 'Musa', 'musa@gmail.com', '8012345678', 'pending', '2000-01-01', 'Male', 'Plateau', 'Kanke', 'State Lowcost', 'Gss Kanke', 'Secondary Certificate', 'Medical Laboratory Technology', '{\"first_sitting\":{\"english\":\"B2\",\"maths\":\"B3\",\"chemistry\":\"D7\",\"physics\":\"D7\",\"biology\":\"C5\",\"others\":{\"subject\":\"Computer\",\"grade\":\"C6\"}},\"second_sitting\":{\"english\":\"A1\",\"maths\":\"A1\",\"chemistry\":\"C5\",\"physics\":\"B2\",\"biology\":\"E8\",\"others\":{\"subject\":\"Computer\",\"grade\":\"C6\"}}}', '{\"passport\":\"uploads\\/1762690751_pass.jpg\",\"olevel\":\"uploads\\/1762690751_WaecResult.jpg\",\"primarycert\":\"uploads\\/1762690751_pri_cert.jpg\",\"indigene\":\"uploads\\/1762690751_ind_form.jpg\",\"birthcert\":\"uploads\\/1762690751_bd_cert.jpg\"}', '2025-11-09 12:19:11'),
(4, 'James Moses', 'Mosesjames@gmail.com', '9112345678', 'pending', '2005-11-27', 'Male', 'Plateau', 'Shendam', 'Kalong road shendam', 'Shendam Private', 'Secondary Certificate', 'Pharmacy Technician', '{\"first_sitting\":{\"english\":\"A1\",\"maths\":\"A1\",\"chemistry\":\"B3\",\"physics\":\"C5\",\"biology\":\"C6\",\"others\":{\"subject\":\"Computer\",\"grade\":\"A1\"}},\"second_sitting\":{\"english\":\"\",\"maths\":\"\",\"chemistry\":\"\",\"physics\":\"\",\"biology\":\"\",\"others\":{\"subject\":\"\",\"grade\":\"\"}}}', '{\"passport\":\"uploads\\/1762767705_pri_cert.jpg\",\"olevel\":\"uploads\\/1762767705_pri_cert.jpg\",\"primarycert\":\"uploads\\/1762767705_pri_cert.jpg\",\"indigene\":\"uploads\\/1762767705_pri_cert.jpg\",\"birthcert\":\"uploads\\/1762767705_pri_cert.jpg\"}', '2025-11-10 09:41:45'),
(5, 'Khadija Lawan', 'khadija@gmail.com', '1234567890', 'approved', '2009-11-11', 'Female', 'Plateay', 'Shendam', 'Kalong', 'Gss Kanke', 'Secondary Certificate', 'Health Information Management', '{\"first_sitting\":{\"english\":\"A1\",\"maths\":\"A1\",\"chemistry\":\"A1\",\"physics\":\"A1\",\"biology\":\"A1\",\"others\":{\"subject\":\"Computer\",\"grade\":\"A1\"}},\"second_sitting\":{\"english\":\"\",\"maths\":\"\",\"chemistry\":\"\",\"physics\":\"\",\"biology\":\"\",\"others\":{\"subject\":\"\",\"grade\":\"\"}}}', '{\"passport\":\"uploads\\/1762857831_ind_form.jpg\",\"olevel\":\"uploads\\/1762857831_ind_form.jpg\",\"primarycert\":\"uploads\\/1762857831_ind_form.jpg\",\"indigene\":\"uploads\\/1762857831_ind_form.jpg\",\"birthcert\":\"uploads\\/1762857831_ind_form.jpg\"}', '2025-11-11 10:43:51');

-- --------------------------------------------------------

--
-- Table structure for table `notices`
--

CREATE TABLE `notices` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `category` varchar(100) NOT NULL DEFAULT 'General',
  `attachments_json` text DEFAULT NULL,
  `status` enum('published','draft') NOT NULL DEFAULT 'published',
  `is_featured` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notices`
--

INSERT INTO `notices` (`id`, `title`, `content`, `category`, `attachments_json`, `status`, `is_featured`, `created_at`, `updated_at`) VALUES
(1, 'Admission List 2025', 'Attach is the List for 2025 Admission', 'Admissions', '[{\"path\":\"notice_uploads\\/1763206194_a3f626b695a1.png\",\"name\":\"Black and White Blank Note Document_20250923_153722_0000.png\"}]', 'published', 1, '2025-11-15 11:29:54', '2025-11-15 11:53:51');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_users`
--
ALTER TABLE `admin_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `applications`
--
ALTER TABLE `applications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notices`
--
ALTER TABLE `notices`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_notices_status` (`status`),
  ADD KEY `idx_notices_category` (`category`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_users`
--
ALTER TABLE `admin_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `applications`
--
ALTER TABLE `applications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `notices`
--
ALTER TABLE `notices`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
