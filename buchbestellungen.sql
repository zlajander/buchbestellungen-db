-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Erstellungszeit: 06. Jul 2026 um 09:37
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
  `buch_id` int(11) NOT NULL,
  `erstellt_am` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_german2_ci;

--
-- Daten für Tabelle `bestellungen`
--

INSERT INTO `bestellungen` (`bestellnummer`, `lesername`, `leseradresse`, `buch_id`, `erstellt_am`) VALUES
(1017, 'Lukas Weber', 'Pontstraße 12, 52062 Aachen', 1, '2026-07-06 06:51:16'),
(1018, 'Marie Schmidt', 'Adalbertstraße 126, 52064 Aachen', 6, '2026-07-06 06:51:16'),
(1019, 'Jonas Peters', 'Kockerellstraße 56, 52062 Aachen', 3, '2026-07-06 06:51:16'),
(1020, 'Sophie Müller', 'Vaalser Straße 91, 52080 Aachen', 10, '2026-07-06 06:51:16'),
(1021, 'Finn Meyer', 'Templergraben 142, 52064 Aachen', 7, '2026-07-06 06:51:16'),
(1022, 'Anna Peters', 'Roermonder Straße 159, 52078 Aachen', 2, '2026-07-06 06:51:16'),
(1023, 'David Hofmann', 'Templergraben 172, 52078 Aachen', 15, '2026-07-06 06:51:16'),
(1024, 'Max Fischer', 'Roermonder Straße 143, 52080 Aachen', 9, '2026-07-06 06:51:16'),
(1025, 'Hannah Huber', 'Monheimsallee 27, 52068 Aachen', 4, '2026-07-06 06:51:16'),
(1026, 'Leon Schneider', 'Monheimsallee 30, 52080 Aachen', 12, '2026-07-06 06:51:16'),
(1027, 'Emma Mayer', 'Turmstraße 9, 52068 Aachen', 8, '2026-07-06 06:51:16'),
(1028, 'Paul Fuchs', 'Lousbergstraße 85, 52070 Aachen', 5, '2026-07-06 06:51:16'),
(1029, 'Nina König', 'Hansemannplatz 35, 52064 Aachen', 11, '2026-07-06 06:51:16'),
(1030, 'Tim Sommer', 'Hansemannplatz 175, 52074 Aachen', 13, '2026-07-06 06:51:16'),
(1031, 'Carla Weber', 'Wilhelmstraße 22, 52078 Aachen', 14, '2026-07-06 06:51:16');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `buecher`
--

CREATE TABLE `buecher` (
  `buch_id` int(11) NOT NULL,
  `isbn` varchar(13) NOT NULL,
  `titel` varchar(255) NOT NULL,
  `autor` varchar(255) NOT NULL,
  `verlag` varchar(255) NOT NULL,
  `veroeffentlichungsdatum` date NOT NULL,
  `erstellt_am` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_german2_ci;

--
-- Daten für Tabelle `buecher`
--

INSERT INTO `buecher` (`buch_id`, `isbn`, `titel`, `autor`, `verlag`, `veroeffentlichungsdatum`, `erstellt_am`) VALUES
(1, '9787328075392', 'Die Verwandlung', 'Franz Kafka', 'Reclam', '1915-10-01', '2026-07-06 06:51:16'),
(2, '9785764396613', 'Der Steppenwolf', 'Hermann Hesse', 'Suhrkamp', '1927-06-15', '2026-07-06 06:51:16'),
(3, '9785376895788', 'Faust I', 'Johann Wolfgang von Goethe', 'Reclam', '1808-04-12', '2026-07-06 06:51:16'),
(4, '9787967229385', 'Buddenbrooks', 'Thomas Mann', 'S. Fischer', '1901-10-26', '2026-07-06 06:51:16'),
(5, '6997702732', 'Im Westen nichts Neues', 'Erich Maria Remarque', 'Ullstein', '1929-01-29', '2026-07-06 06:51:16'),
(6, '9786094338045', 'Das Parfum', 'Patrick Süskind', 'Diogenes', '1985-03-01', '2026-07-06 06:51:16'),
(7, '9785649009683', 'Die unendliche Geschichte', 'Michael Ende', 'Thienemann', '1979-09-01', '2026-07-06 06:51:16'),
(8, '9785182354424', 'Momo', 'Michael Ende', 'Thienemann', '1973-01-01', '2026-07-06 06:51:16'),
(9, '9782558470726', 'Der Vorleser', 'Bernhard Schlink', 'Diogenes', '1995-09-01', '2026-07-06 06:51:16'),
(10, '8394094902', 'Tschick', 'Wolfgang Herrndorf', 'Rowohlt', '2010-09-17', '2026-07-06 06:51:16'),
(11, '9783140852692', 'Der Prozess', 'Franz Kafka', 'Die Schmiede', '1925-04-26', '2026-07-06 06:51:16'),
(12, '9785801251516', 'Effi Briest', 'Theodor Fontane', 'F. Fontane & Co.', '1896-10-01', '2026-07-06 06:51:16'),
(13, '9787732203787', 'Die Blechtrommel', 'Günter Grass', 'Luchterhand', '1959-09-01', '2026-07-06 06:51:16'),
(14, '9782588473032', 'Homo Faber', 'Max Frisch', 'Suhrkamp', '1957-10-01', '2026-07-06 06:51:16'),
(15, '2047741793', 'Der Schwarm', 'Frank Schätzing', 'Kiepenheuer & Witsch', '2004-03-04', '2026-07-06 06:51:16');

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `bestellungen`
--
ALTER TABLE `bestellungen`
  ADD PRIMARY KEY (`bestellnummer`),
  ADD KEY `fk_bestellung_buch` (`buch_id`);

--
-- Indizes für die Tabelle `buecher`
--
ALTER TABLE `buecher`
  ADD PRIMARY KEY (`buch_id`),
  ADD UNIQUE KEY `isbn` (`isbn`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `bestellungen`
--
ALTER TABLE `bestellungen`
  MODIFY `bestellnummer` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1032;

--
-- AUTO_INCREMENT für Tabelle `buecher`
--
ALTER TABLE `buecher`
  MODIFY `buch_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- Constraints der exportierten Tabellen
--

--
-- Constraints der Tabelle `bestellungen`
--
ALTER TABLE `bestellungen`
  ADD CONSTRAINT `fk_bestellung_buch` FOREIGN KEY (`buch_id`) REFERENCES `buecher` (`buch_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
