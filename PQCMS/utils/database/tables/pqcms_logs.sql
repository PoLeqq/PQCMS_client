-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 25, 2024 at 09:09 PM
-- Wersja serwera: 10.4.28-MariaDB
-- Wersja PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

--
-- Database: `pqcms`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `pqcms_logs`
--

CREATE TABLE `pqcms_logs` (
  `id` int(11) NOT NULL,
  `type` tinyint(4) NOT NULL,
  `date` datetime NOT NULL,
  `data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`data`))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indeksy dla zrzutów tabel
--

--
-- Indeksy dla tabeli `pqcms_logs`
--
ALTER TABLE `pqcms_logs`
    ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `pqcms_logs`
--
ALTER TABLE `pqcms_logs`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;
