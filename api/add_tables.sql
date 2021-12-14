-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Anamakine: 127.0.0.1
-- Üretim Zamanı: 14 Ara 2021, 22:04:36
-- Sunucu sürümü: 10.4.14-MariaDB
-- PHP Sürümü: 7.4.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Veritabanı: `autocreate`
--

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `hascoding_api_auth`
--

CREATE TABLE `hascoding_api_auth` (
  `id` int(11) NOT NULL,
  `auth_token` text NOT NULL,
  `last_date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Tablo döküm verisi `hascoding_api_auth`
--

INSERT INTO `hascoding_api_auth` (`id`, `auth_token`, `last_date`) VALUES
(1, '3bb5e585b3b20a089ba46b7d55c74b50', '2022-12-16 23:54:36'),
(2, '9cb70fec1989bc656eb262a5a4164cd1', '2021-12-14 00:03:02');

--
-- Dökümü yapılmış tablolar için indeksler
--

--
-- Tablo için indeksler `hascoding_api_auth`
--
ALTER TABLE `hascoding_api_auth`
  ADD PRIMARY KEY (`id`);

--
-- Dökümü yapılmış tablolar için AUTO_INCREMENT değeri
--

--
-- Tablo için AUTO_INCREMENT değeri `hascoding_api_auth`
--
ALTER TABLE `hascoding_api_auth`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
