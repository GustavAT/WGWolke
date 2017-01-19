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
-- Datenbank: `phpmyadmin`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `pma__relation`
--

CREATE TABLE `pma__relation` (
  `master_db` varchar(64) COLLATE utf8_bin NOT NULL DEFAULT '',
  `master_table` varchar(64) COLLATE utf8_bin NOT NULL DEFAULT '',
  `master_field` varchar(64) COLLATE utf8_bin NOT NULL DEFAULT '',
  `foreign_db` varchar(64) COLLATE utf8_bin NOT NULL DEFAULT '',
  `foreign_table` varchar(64) COLLATE utf8_bin NOT NULL DEFAULT '',
  `foreign_field` varchar(64) COLLATE utf8_bin NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Relation table';

--
-- Daten für Tabelle `pma__relation`
--

INSERT INTO `pma__relation` (`master_db`, `master_table`, `master_field`, `foreign_db`, `foreign_table`, `foreign_field`) VALUES
('wg_wolke', 'dish_item', 'community_oid', 'wg_wolke', 'community', 'oid'),
('wg_wolke', 'dish_item_entry', 'dish_item_oid', 'wg_wolke', 'dish_item', 'oid'),
('wg_wolke', 'dish_item_entry', 'user_oid', 'wg_wolke', 'user', 'oid'),
('wg_wolke', 'dish_tag', 'community_oid', 'wg_wolke', 'community', 'oid'),
('wg_wolke', 'dish_tag_item', 'dish_item_id', 'wg_wolke', 'dish_item', 'oid'),
('wg_wolke', 'dish_tag_item', 'dish_tag_oid', 'wg_wolke', 'dish_tag', 'oid'),
('wg_wolke', 'entity_blob_entry', 'content_oid', 'wg_wolke', 'entity_blob_content', 'oid'),
('wg_wolke', 'finance_item', 'community_oid', 'wg_wolke', 'community', 'oid'),
('wg_wolke', 'finance_item', 'user_oid', 'wg_wolke', 'user', 'oid'),
('wg_wolke', 'finance_item_user', 'finance_item_oid', 'wg_wolke', 'finance_item', 'oid'),
('wg_wolke', 'finance_item_user', 'user_oid', 'wg_wolke', 'user', 'oid'),
('wg_wolke', 'module_community', 'community_oid', 'wg_wolke', 'community', 'oid'),
('wg_wolke', 'module_community', 'module_oid', 'wg_wolke', 'module', 'oid'),
('wg_wolke', 'news_feed_item', 'community_oid', 'wg_wolke', 'community', 'oid'),
('wg_wolke', 'news_feed_item', 'user_oid', 'wg_wolke', 'user', 'oid'),
('wg_wolke', 'todo_item', 'community_oid', 'wg_wolke', 'community', 'oid'),
('wg_wolke', 'todo_item', 'user_oid', 'wg_wolke', 'user', 'oid'),
('wg_wolke', 'user', 'community_oid', 'wg_wolke', 'community', 'oid');

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `pma__relation`
--
ALTER TABLE `pma__relation`
  ADD PRIMARY KEY (`master_db`,`master_table`,`master_field`),
  ADD KEY `foreign_field` (`foreign_db`,`foreign_table`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
