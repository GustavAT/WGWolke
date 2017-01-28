-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Erstellungszeit: 19. Jan 2017 um 21:34
-- Server-Version: 10.1.19-MariaDB
-- PHP-Version: 5.6.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `wg_wolke`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `community`
--

CREATE TABLE `community` (
  `oid` varchar(36) NOT NULL DEFAULT '00000000-0000-0000-0000-000000000000',
  `date_created` DATETIME DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL,
  `description` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Community';

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `dish_item`
--

CREATE TABLE `dish_item` (
  `oid` varchar(36) NOT NULL DEFAULT '00000000-0000-0000-0000-000000000000',
  `date_created` DATETIME DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL,
  `community_oid` varchar(36) NOT NULL DEFAULT '00000000-0000-0000-0000-000000000000'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `dish_item_entry`
--

CREATE TABLE `dish_item_entry` (
  `oid` varchar(36) NOT NULL DEFAULT '00000000-0000-0000-0000-000000000000',
  `date_created` DATETIME DEFAULT NULL,
  `dish_date` DATETIME DEFAULT NULL,
  `dish_item_oid` varchar(36) NOT NULL DEFAULT '00000000-0000-0000-0000-000000000000',
  `user_oid` varchar(36) NOT NULL DEFAULT '00000000-0000-0000-0000-000000000000'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `dish_tag`
--

CREATE TABLE `dish_tag` (
  `oid` varchar(36) NOT NULL DEFAULT '00000000-0000-0000-0000-000000000000',
  `date_created` DATETIME DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL,
  `color` varchar(7) NOT NULL DEFAULT '#FF0000',
  `community_oid` varchar(36) NOT NULL DEFAULT '00000000-0000-0000-0000-000000000000'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `dish_tag_item`
--

CREATE TABLE `dish_tag_item` (
  `oid` varchar(36) NOT NULL DEFAULT '00000000-0000-0000-0000-000000000000',
  `dish_tag_oid` varchar(36) NOT NULL DEFAULT '00000000-0000-0000-0000-000000000000',
  `dish_item_oid` varchar(36) NOT NULL DEFAULT '00000000-0000-0000-0000-000000000000'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `entity_blob_content`
--

CREATE TABLE `entity_blob_content` (
  `oid` varchar(36) NOT NULL DEFAULT '00000000-0000-0000-0000-000000000000',
  `date_created` DATETIME DEFAULT NULL,
  `data` blob
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `entity_blob_entry`
--

CREATE TABLE `entity_blob_entry` (
  `oid` varchar(36) NOT NULL DEFAULT '00000000-0000-0000-0000-000000000000',
  `date_created` DATETIME DEFAULT NULL,
  `file_type` varchar(10) NOT NULL DEFAULT '*.*',
  `file_size` int(11) NOT NULL DEFAULT '0',
  `entity_oid` varchar(36) NOT NULL DEFAULT '00000000-0000-0000-0000-000000000000',
  `content_oid` varchar(36) NOT NULL DEFAULT '00000000-0000-0000-0000-000000000000'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `finance_item`
--

CREATE TABLE `finance_item` (
  `oid` varchar(36) NOT NULL DEFAULT '00000000-0000-0000-0000-000000000000',
  `date_created` DATETIME DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL,
  `date_accrued` DATETIME DEFAULT NULL,
  `date_completed` DATETIME DEFAULT NULL,
  `amount` decimal(10,0) NOT NULL DEFAULT '0',
  `completed` tinyint(1) NOT NULL DEFAULT '0',
  `edited` tinyint(1) NOT NULL DEFAULT '0',
  `user_oid` varchar(36) DEFAULT NULL,
  `community_oid` varchar(36) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `finance_item_user`
--

CREATE TABLE `finance_item_user` (
  `oid` varchar(36) NOT NULL DEFAULT '00000000-0000-0000-0000-000000000000',
  `finance_item_oid` varchar(36) NOT NULL DEFAULT '00000000-0000-0000-0000-000000000000',
  `user_oid` varchar(36) NOT NULL DEFAULT '00000000-0000-0000-0000-000000000000'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `module`
--

CREATE TABLE `module` (
  `oid` varchar(36) NOT NULL DEFAULT '00000000-0000-0000-0000-000000000000',
  `date_created` DATETIME DEFAULT NULL,
  `type` int(11) NOT NULL DEFAULT '0',
  `name` varchar(50) DEFAULT NULL,
  `price` decimal(10,0) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `module`
--

INSERT INTO `module` (`oid`, `date_created`, `type`, `name`, `price`) VALUES
('195fd0d9-dc09-11e6-9082-1c1b0d05ba41', '2017-01-16', 2, 'Finances', '0'),
('195fd938-dc09-11e6-9082-1c1b0d05ba41', '2017-01-16', 3, 'Menuplan', '0'),
('195fe056-dc09-11e6-9082-1c1b0d05ba41', '2017-01-16', 4, 'Shopping List', '0'),
('195fe768-dc09-11e6-9082-1c1b0d05ba41', '2017-01-16', 1, 'Member', '0');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `module_community`
--

CREATE TABLE `module_community` (
  `oid` varchar(36) NOT NULL DEFAULT '00000000-0000-0000-0000-000000000000',
  `module_oid` varchar(36) NOT NULL DEFAULT '00000000-0000-0000-0000-000000000000',
  `community_oid` varchar(36) NOT NULL DEFAULT '00000000-0000-0000-0000-000000000000'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `news_feed_item`
--

CREATE TABLE `news_feed_item` (
  `oid` varchar(36) NOT NULL DEFAULT '00000000-0000-0000-0000-000000000000',
  `date_created` DATETIME DEFAULT NULL,
  `title` varchar(50) DEFAULT NULL,
  `message` varchar(500) DEFAULT NULL,
  `expiration_date` DATETIME DEFAULT NULL,
  `community_oid` varchar(36) NOT NULL DEFAULT '00000000-0000-0000-0000-000000000000',
  `user_oid` varchar(36) NOT NULL DEFAULT '00000000-0000-0000-0000-000000000000'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `todo_item`
--

CREATE TABLE `todo_item` (
  `oid` varchar(36) NOT NULL DEFAULT '00000000-0000-0000-0000-000000000000',
  `date_created` DATETIME DEFAULT NULL,
  `description` varchar(50) NOT NULL DEFAULT '',
  `is_finished` tinyint(1) NOT NULL DEFAULT '0',
  `community_oid` varchar(36) NOT NULL DEFAULT '00000000-0000-0000-0000-000000000000',
  `user_oid` varchar(36) NOT NULL DEFAULT '00000000-0000-0000-0000-000000000000'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `user`
--

CREATE TABLE `user` (
  `oid` varchar(36) NOT NULL DEFAULT '00000000-0000-0000-0000-000000000000',
  `date_created` DATETIME DEFAULT NULL,
  `email` varchar(200) DEFAULT NULL,
  `password` varchar(32) DEFAULT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) NOT NULL,
  `is_locked` tinyint(1) NOT NULL DEFAULT '1',
  `reg_hash` varchar(32) DEFAULT NULL,
  `community_oid` varchar(36) NOT NULL DEFAULT '00000000-0000-0000-0000-000000000000',
  `is_owner` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `community`
--
ALTER TABLE `community`
  ADD PRIMARY KEY (`oid`);

--
-- Indizes für die Tabelle `dish_item`
--
ALTER TABLE `dish_item`
  ADD PRIMARY KEY (`oid`);

--
-- Indizes für die Tabelle `dish_item_entry`
--
ALTER TABLE `dish_item_entry`
  ADD PRIMARY KEY (`oid`);

--
-- Indizes für die Tabelle `dish_tag`
--
ALTER TABLE `dish_tag`
  ADD PRIMARY KEY (`oid`);

--
-- Indizes für die Tabelle `dish_tag_item`
--
ALTER TABLE `dish_tag_item`
  ADD PRIMARY KEY (`oid`);

--
-- Indizes für die Tabelle `entity_blob_content`
--
ALTER TABLE `entity_blob_content`
  ADD PRIMARY KEY (`oid`);

--
-- Indizes für die Tabelle `entity_blob_entry`
--
ALTER TABLE `entity_blob_entry`
  ADD PRIMARY KEY (`oid`);

--
-- Indizes für die Tabelle `finance_item`
--
ALTER TABLE `finance_item`
  ADD PRIMARY KEY (`oid`);

--
-- Indizes für die Tabelle `finance_item_user`
--
ALTER TABLE `finance_item_user`
  ADD PRIMARY KEY (`oid`);

--
-- Indizes für die Tabelle `module`
--
ALTER TABLE `module`
  ADD PRIMARY KEY (`oid`);

--
-- Indizes für die Tabelle `module_community`
--
ALTER TABLE `module_community`
  ADD PRIMARY KEY (`oid`),
  ADD UNIQUE KEY `module_oid` (`module_oid`,`community_oid`);

--
-- Indizes für die Tabelle `news_feed_item`
--
ALTER TABLE `news_feed_item`
  ADD PRIMARY KEY (`oid`);

--
-- Indizes für die Tabelle `todo_item`
--
ALTER TABLE `todo_item`
  ADD PRIMARY KEY (`oid`);

--
-- Indizes für die Tabelle `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`oid`),
  ADD UNIQUE KEY `email` (`email`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
