-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 03, 2023 at 04:55 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

--
-- Database: `pqcms`
--

-- --------------------------------------------------------

--
-- Table structure for table `pqcms_site_text_group`
--

CREATE TABLE `pqcms_site_text_group` (
    `id` int(11) UNSIGNED NOT NULL,
    `name` varchar(50) NOT NULL COMMENT 'Nazwa grupy'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `pqcms_site_text_group`
--
ALTER TABLE `pqcms_site_text_group`
    ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `pqcms_site_text_group`
--
ALTER TABLE `pqcms_site_text_group`
    MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;
