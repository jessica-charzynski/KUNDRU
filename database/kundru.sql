-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Erstellungszeit: 19. Aug 2024 um 00:01
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
-- Datenbank: `kundru`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `address`
--

CREATE TABLE `address` (
  `AddressID` int(11) NOT NULL,
  `UserID` int(11) NOT NULL,
  `Street` varchar(255) NOT NULL,
  `City` varchar(255) NOT NULL,
  `PostalCode` varchar(10) NOT NULL,
  `Country` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Daten für Tabelle `address`
--

INSERT INTO `address` (`AddressID`, `UserID`, `Street`, `City`, `PostalCode`, `Country`) VALUES
(1, 2, 'Künstlerweg 12', 'Berlin', '12345', 'Deutschland'),
(2, 9, 'Beispielstraße 1', 'Frankfurt am Main', '60322', 'Deutschland'),
(3, 10, 'Beispielstraße 22', 'Hamburg', '20099', 'Deutschland'),
(4, 11, 'Ottostraße 23', 'Köln', '50823', 'Deutschland');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `artist`
--

CREATE TABLE `artist` (
  `ArtistID` int(11) NOT NULL,
  `FirstName` varchar(50) DEFAULT NULL,
  `LastName` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Daten für Tabelle `artist`
--

INSERT INTO `artist` (`ArtistID`, `FirstName`, `LastName`) VALUES
(1, 'Gustav', 'Klimt'),
(2, 'Katsushika', 'Hokusai'),
(3, 'Kokichi', 'Tsunoi'),
(4, 'Caspar David', 'Friedrich'),
(5, 'John', 'Doe'),
(6, 'Jane', 'Doe');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `artwork`
--

CREATE TABLE `artwork` (
  `ArtworkID` int(11) NOT NULL,
  `ArtistID` int(11) DEFAULT NULL,
  `Title` varchar(100) DEFAULT NULL,
  `Description` text DEFAULT NULL,
  `Category` varchar(100) DEFAULT NULL,
  `PriceOfArtprint` decimal(10,2) DEFAULT NULL,
  `ImagePath` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Daten für Tabelle `artwork`
--

INSERT INTO `artwork` (`ArtworkID`, `ArtistID`, `Title`, `Description`, `Category`, `PriceOfArtprint`, `ImagePath`) VALUES
(1, 1, 'Der Kuss', 'Ein berühmtes Gemälde von Gustav Klimt, das die innige Umarmung eines Paares zeigt, umgeben von kunstvollen und dekorativen Elementen.', 'Painting', 15.00, '/KUNDRU/assets/images/kuss_klimt.jpeg'),
(2, 2, 'Die große Welle vor Kanagawa', 'Blatt 1 der Serie \"36 Ansichten des Fuji\". Die Darstellung der Welle erlangt in Europa beispiellose Berühmtheit und zahllose Nachahmung. Sie erscheint im Kunsthandwerk des Jugendstils als Motiv in vielfältigsten Varianten.', 'Painting', 14.00, '/KUNDRU/assets/images/welle_hokusai.jpg'),
(3, 3, 'Somei-yoshino', 'Dieses Aquarell zeigt die zarten Kirschblüten des Somei-yoshino, einer berühmten Kirschbaumart benannt nach Somei, einem Ort im heutigen Tokyo.', 'Painting', 13.00, '/KUNDRU/assets/images/somei-yoshino.jpg'),
(4, 4, 'Der Wanderer über dem Nebelmeer', '\"Der Wanderer über dem Nebelmeer\" ist ein berühmtes Gemälde von Caspar David Friedrich aus dem Jahr 1818. Es zeigt einen einsamen Wanderer auf einem Felsen, der über einem Tal liegt, das von einem Meer aus Nebel bedeckt ist.', 'Painting', 12.00, '/KUNDRU/assets/images/nebelmeer_friedrich.png'),
(5, 6, 'Inhale Exhale', 'Ein minimalistischer Typografie-Kunstdruck, der die beruhigende Bewegung des Ein- und Ausatmens einfängt. Schlicht und doch ausdrucksstark, betont es die Essenz von Ruhe und Gelassenheit.', 'Typography', 12.00, '/KUNDRU/assets/images/inhale-exhale.png'),
(6, 6, 'But First Coffee', 'Ein lebhafter Typografie-Kunstdruck, der die essentielle Rolle von Kaffee am Morgen hervorhebt.', 'Typography', 13.00, '/KUNDRU/assets/images/coffee.jpg'),
(7, 6, 'Good Vibes', 'Ein moderner Typografie-Kunstdruck, der positive Energie und optimistische Stimmung durch eine verspielte Schrift vermittelt.', 'Typography', 14.00, '/KUNDRU/assets/images/good_vibes.jpg'),
(8, 6, 'Wanderlust', 'Eine typografische Interpretation des Begriffs Wanderlust, die das Verlangen nach Reisen und Entdeckungen einfängt.', 'Typography', 15.00, '/KUNDRU/assets/images/wanderlust.png'),
(9, 5, 'Wildblumen', 'Eine idyllische Wildblumenwiese mit einer Vielfalt an lebendigen Farben, die die Schönheit der Natur einfängt.', 'Photography', 15.00, '/KUNDRU/assets/images/wildblumen.jpg'),
(10, 5, 'Eule', 'Ein eindrucksvolles Porträt einer Eule in Schwarz-Weiß, das die Tiefe ihres Blicks und ihre majestätische Erscheinung einfängt.', 'Photography', 14.00, '/KUNDRU/assets/images/eule.png'),
(11, 5, 'Regen', 'Ein stimmungsvolles Foto, das einen regnerischen Tag in einer Großstadt einfängt, mit glänzenden Straßen, leuchtenden Lichtern und Menschen, die unter Regenschirmen eilen.', 'Photography', 13.00, '/KUNDRU/assets/images/regen.jpg'),
(12, 5, 'Meer', 'Eine malerische Aufnahme, die einen weiten Blick auf das Meer und den Horizont zeigt, mit sanften Wellen und einem Himmel, der sich in verschiedenen Nuancen von Blau widerspiegelt.', 'Photography', 12.00, '/KUNDRU/assets/images/meer.jpg');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `order`
--

CREATE TABLE `order` (
  `id` int(11) NOT NULL,
  `UserID` int(11) NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `payment_status` enum('pending','completed') DEFAULT 'completed',
  `stripe_charge_id` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Daten für Tabelle `order`
--

INSERT INTO `order` (`id`, `UserID`, `total_amount`, `created_at`, `payment_status`, `stripe_charge_id`) VALUES
(27, 2, 13.00, '2024-08-05 17:50:38', 'completed', 'pi_3PkV3JJbpg1LH6H10SS4Dwn2'),
(28, 2, 39.00, '2024-08-05 18:38:28', 'completed', 'pi_3PkVncJbpg1LH6H10YRQEQrb'),
(29, 2, 71.00, '2024-08-05 20:33:31', 'completed', 'pi_3PkXaxJbpg1LH6H11JuvRtqO'),
(30, 10, 41.00, '2024-08-11 19:19:39', 'completed', 'pi_3PmhIfJbpg1LH6H11CCsaaVq'),
(31, 11, 151.00, '2024-08-11 19:25:26', 'completed', 'pi_3PmhOMJbpg1LH6H10tEhbQld'),
(32, 2, 44.00, '2024-08-18 16:10:10', 'completed', 'pi_3PpBgAJbpg1LH6H11t31Nh8h');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `artwork_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Daten für Tabelle `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `artwork_id`, `quantity`, `price`) VALUES
(25, 27, 3, 1, 13.00),
(26, 28, 6, 1, 13.00),
(27, 28, 7, 1, 14.00),
(28, 28, 12, 1, 12.00),
(29, 29, 9, 3, 15.00),
(30, 29, 11, 2, 13.00),
(31, 30, 6, 1, 13.00),
(32, 30, 10, 2, 14.00),
(33, 31, 11, 3, 13.00),
(34, 31, 5, 5, 12.00),
(35, 31, 3, 4, 13.00),
(36, 32, 7, 1, 14.00),
(37, 32, 9, 2, 15.00);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `user`
--

CREATE TABLE `user` (
  `UserID` int(11) NOT NULL,
  `FirstName` varchar(255) NOT NULL,
  `LastName` varchar(255) NOT NULL,
  `Email` varchar(255) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `Phone` varchar(20) NOT NULL,
  `Role` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Daten für Tabelle `user`
--

INSERT INTO `user` (`UserID`, `FirstName`, `LastName`, `Email`, `Password`, `Phone`, `Role`) VALUES
(1, 'Jessica', 'Charzynski', 's42891@bht-berlin.de', '$2y$10$4zHzn11BhXGUUAOgiJfoy.7kArAZDTThJNSLbjIdyMxKKNJ.UUa6m', '123456789', 'admin'),
(2, 'Frida', 'Kahlo', 'frida-kahlo@yopmail.com', '$2y$10$6dNZVGVNPo7QeyLRHAf2s.CosBTldC0haHnTfQjSTeiznW.fcEwg6', '123456789', 'user'),
(9, 'Pablo', 'Picasso', 'pablo-picasso@yopmail.com', '$2y$10$91cTppc5dFqw0CNXETvW3OBUcmJ3rGVmNQaSZ1xaboKbtPUByLjey', '123456789', 'user'),
(10, 'Erika', 'Musterfrau', 'erika-musterfrau@yopmail.com', '$2y$10$A5E9uH/ewtGwB9Xa.F85BuF5S/mSAwRLbuANYCLS.MNtP2o41AyUK', '123456789', 'user'),
(11, 'Paul', 'Klee', 'paul-klee@yopmail.com', '$2y$10$OmxjI6LfCWYY99dt5yFH.uLt3LsBq0vtKO3U77.oJUHoS61lr2JbG', '123456789', 'user');

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `address`
--
ALTER TABLE `address`
  ADD PRIMARY KEY (`AddressID`),
  ADD KEY `UserID` (`UserID`);

--
-- Indizes für die Tabelle `artist`
--
ALTER TABLE `artist`
  ADD PRIMARY KEY (`ArtistID`);

--
-- Indizes für die Tabelle `artwork`
--
ALTER TABLE `artwork`
  ADD PRIMARY KEY (`ArtworkID`),
  ADD KEY `ArtistID` (`ArtistID`);

--
-- Indizes für die Tabelle `order`
--
ALTER TABLE `order`
  ADD PRIMARY KEY (`id`),
  ADD KEY `UserID` (`UserID`);

--
-- Indizes für die Tabelle `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`);

--
-- Indizes für die Tabelle `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`UserID`),
  ADD UNIQUE KEY `Email` (`Email`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `address`
--
ALTER TABLE `address`
  MODIFY `AddressID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT für Tabelle `artist`
--
ALTER TABLE `artist`
  MODIFY `ArtistID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT für Tabelle `artwork`
--
ALTER TABLE `artwork`
  MODIFY `ArtworkID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=118;

--
-- AUTO_INCREMENT für Tabelle `order`
--
ALTER TABLE `order`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT für Tabelle `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT für Tabelle `user`
--
ALTER TABLE `user`
  MODIFY `UserID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Constraints der exportierten Tabellen
--

--
-- Constraints der Tabelle `address`
--
ALTER TABLE `address`
  ADD CONSTRAINT `address_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `user` (`UserID`) ON DELETE CASCADE;

--
-- Constraints der Tabelle `artwork`
--
ALTER TABLE `artwork`
  ADD CONSTRAINT `artwork_ibfk_1` FOREIGN KEY (`ArtistID`) REFERENCES `artist` (`ArtistID`);

--
-- Constraints der Tabelle `order`
--
ALTER TABLE `order`
  ADD CONSTRAINT `order_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `user` (`UserID`) ON DELETE CASCADE;

--
-- Constraints der Tabelle `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `order` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
