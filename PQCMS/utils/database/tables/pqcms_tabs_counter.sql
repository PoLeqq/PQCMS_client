-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 18, 2024 at 10:14 PM
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
-- Struktura tabeli dla tabeli `pqcms_tabs_counter`
--

CREATE TABLE `pqcms_tabs_counter` (
  `id` int(11) NOT NULL,
  `tab_name` varchar(100) NOT NULL,
  `ip` varchar(39) NOT NULL,
  `date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indeksy dla zrzutów tabel
--

--
-- Indeksy dla tabeli `pqcms_tabs_counter`
--
ALTER TABLE `pqcms_tabs_counter`
    ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `pqcms_tabs_counter`
--
ALTER TABLE `pqcms_tabs_counter`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;
