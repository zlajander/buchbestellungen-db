-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Erstellungszeit: 29. Jun 2026 um 15:14
-- Server-Version: 10.4.32-MariaDB
-- PHP-Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `buchbestellungen`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `bestellungen`
--

CREATE TABLE `bestellungen` (
  `bestellnummer` int(11) NOT NULL,
  `lesername` varchar(100) NOT NULL,
  `leseradresse` varchar(255) NOT NULL,
  `buchnummer` char(8) NOT NULL,
  `erstellt_am` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_german2_ci;

--
-- Daten für Tabelle `bestellungen`
--

INSERT INTO `bestellungen` (`bestellnummer`, `lesername`, `leseradresse`, `buchnummer`, `erstellt_am`) VALUES
(3, 'Max Mustermann', 'Musterstraße 1, 52062 Aachen', '12345678', '2026-06-29 12:58:28'),
(4, 'Anna Schmidt', 'Templergraben 55, 52062 Aachen', '23456789', '2026-06-29 12:58:28'),
(5, 'Lukas Weber', 'Pontstraße 12, 52062 Aachen', '34567890', '2026-06-29 12:58:28'),
(6, 'Lena Becker', 'Jakobstraße 8, 52064 Aachen', '45678901', '2026-06-29 12:58:28'),
(7, 'Tim Hoffmann', 'Theaterstraße 30, 52062 Aachen', '56789012', '2026-06-29 12:58:28');

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `bestellungen`
--
ALTER TABLE `bestellungen`
  ADD PRIMARY KEY (`bestellnummer`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `bestellungen`
--
ALTER TABLE `bestellungen`
  MODIFY `bestellnummer` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
