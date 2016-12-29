-- phpMyAdmin SQL Dump
-- version 4.5.4.1deb2ubuntu2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Creato il: Dic 29, 2016 alle 22:30
-- Versione del server: 5.7.16-0ubuntu0.16.04.1
-- Versione PHP: 7.0.8-0ubuntu0.16.04.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `welcometool`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `languages`
--

CREATE TABLE `languages` (
  `iso` varchar(3) NOT NULL,
  `name` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struttura della tabella `new_user`
--

CREATE TABLE `new_user` (
  `user_id` bigint(20) NOT NULL,
  `username` varchar(200) NOT NULL,
  `registration_date` bigint(20) NOT NULL,
  `first_edit_date` bigint(20) NOT NULL,
  `first_edit_location` varchar(100) NOT NULL,
  `first_changeset_id` bigint(20) NOT NULL,
  `first_changeset_editor` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struttura della tabella `notes`
--

CREATE TABLE `notes` (
  `nid` bigint(20) NOT NULL,
  `uid` bigint(20) NOT NULL,
  `timestamp` bigint(20) NOT NULL,
  `author` varchar(200) NOT NULL,
  `type` varchar(10) NOT NULL,
  `note` varchar(2000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struttura della tabella `snippets`
--

CREATE TABLE `snippets` (
  `id` int(11) NOT NULL,
  `language` varchar(15) NOT NULL,
  `part` varchar(15) NOT NULL,
  `text` varchar(1000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struttura della tabella `welcome_user`
--

CREATE TABLE `welcome_user` (
  `uid` bigint(20) NOT NULL,
  `welcomed` tinyint(4) NOT NULL,
  `welcomed_by` varchar(50) NOT NULL,
  `welcomed_on` bigint(20) NOT NULL,
  `answered` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `languages`
--
ALTER TABLE `languages`
  ADD PRIMARY KEY (`iso`);

--
-- Indici per le tabelle `new_user`
--
ALTER TABLE `new_user`
  ADD PRIMARY KEY (`user_id`);

--
-- Indici per le tabelle `notes`
--
ALTER TABLE `notes`
  ADD PRIMARY KEY (`nid`);

--
-- Indici per le tabelle `snippets`
--
ALTER TABLE `snippets`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `welcome_user`
--
ALTER TABLE `welcome_user`
  ADD PRIMARY KEY (`uid`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `notes`
--
ALTER TABLE `notes`
  MODIFY `nid` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
--
-- AUTO_INCREMENT per la tabella `snippets`
--
ALTER TABLE `snippets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
