-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 09-05-2025 a las 07:02:42
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `songs_database`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `directorio_archivos`
--

CREATE TABLE `directorio_archivos` (
  `cancion_id` varchar(150) NOT NULL,
  `ruta_archivo` varchar(150) NOT NULL,
  `audioUrl` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `songs`
--

CREATE TABLE `songs` (
  `id` int(11) NOT NULL,
  `artist` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `year` int(11) DEFAULT NULL,
  `energy` float DEFAULT NULL,
  `danceability` float DEFAULT NULL,
  `happiness` float DEFAULT NULL,
  `instrumentalness` float DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `PPM` int(11) DEFAULT NULL,
  `audioUrl` varchar(512) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `songs`
--

INSERT INTO `songs` (`id`, `artist`, `name`, `year`, `energy`, `danceability`, `happiness`, `instrumentalness`, `created_at`, `PPM`, `audioUrl`) VALUES
(1, 'The Rolling Stones', 'Satisfaction', 1965, 0.85, 0.7, 0.75, 0.15, '2025-04-26 00:26:18', 135, 'SpotiDownloader.com - Las 100 mejores canciones de la historia del Rock/Satisfaction.mp3'),
(2, 'Pink Floyd', 'Another Brick In The Wall', 1979, 0.75, 0.65, 0.45, 0.25, '2025-04-26 00:26:18', 145, 'SpotiDownloader.com - Las 100 mejores canciones de la historia del Rock/Another Brick In The Wall, Pt. 2 - 2011 Remastered Version.mp3'),
(3, 'Queen', 'Another One Bites The Dust', 1980, 0.82, 0.85, 0.7, 0.1, '2025-04-26 00:26:18', 139, 'SpotiDownloader.com - Las 100 mejores canciones de la historia del Rock/Another One Bites The Dust - Remastered 2011.mp3'),
(4, 'Jet', 'Are You Gonna Be My Girl', 2003, 0.9, 0.75, 0.8, 0.2, '2025-04-26 00:26:18', 160, 'SpotiDownloader.com - Las 100 mejores canciones de la historia del Rock/Are You Gonna Be My Girl.mp3'),
(5, 'The Who', 'Baba O Riley', 1971, 0.88, 0.65, 0.7, 0.3, '2025-04-26 00:26:18', 124, 'SpotiDownloader.com - Las 100 mejores canciones de la historia del Rock/Baba O\'Riley.mp3'),
(6, 'AC/DC', 'Back In Black', 1980, 0.95, 0.7, 0.85, 0.15, '2025-04-26 00:26:18', 123, 'SpotiDownloader.com - Las 100 mejores canciones de la historia del Rock/Back In Black.mp3'),
(7, 'Bon Jovi', 'Bed Of Roses', 1992, 0.7, 0.45, 0.6, 0.2, '2025-04-26 00:26:18', 141, 'SpotiDownloader.com - Las 100 mejores canciones de la historia del Rock/Bed Of Roses.mp3'),
(8, 'Led Zeppelin', 'Black Dog', 1971, 0.9, 0.65, 0.75, 0.25, '2025-04-26 00:26:18', 159, 'SpotiDownloader.com - Las 100 mejores canciones de la historia del Rock/Black Dog - Remaster.mp3'),
(9, 'Ramones', 'Blitzkrieg Bop', 1976, 0.95, 0.85, 0.9, 0.1, '2025-04-26 00:26:18', 110, 'SpotiDownloader.com - Las 100 mejores canciones de la historia del Rock/Blitzkrieg Bop.mp3'),
(10, 'Queen', 'Bohemian Rhapsody', 1975, 0.8, 0.55, 0.65, 0.4, '2025-04-26 00:26:18', 133, 'SpotiDownloader.com - Las 100 mejores canciones de la historia del Rock/Bohemian Rhapsody - Remastered 2011.mp3'),
(11, 'Steppenwolf', 'Born To Be Wild', 1968, 0.88, 0.7, 0.85, 0.2, '2025-04-26 00:26:18', 155, 'SpotiDownloader.com - Las 100 mejores canciones de la historia del Rock/Born To Be Wild.mp3'),
(12, 'Green Day', 'Boulevard of Broken Dreams', 2004, 0.75, 0.6, 0.4, 0.25, '2025-04-26 00:26:18', 118, 'SpotiDownloader.com - Las 100 mejores canciones de la historia del Rock/Boulevard of Broken Dreams.mp3'),
(13, 'Supertramp', 'Breakfast In America', 1979, 0.7, 0.75, 0.8, 0.3, '2025-04-26 00:26:18', 104, 'SpotiDownloader.com - Las 100 mejores canciones de la historia del Rock/Breakfast In America.mp3'),
(14, 'Red Hot Chili Peppers', 'Californication', 1999, 0.75, 0.65, 0.6, 0.25, '2025-04-26 00:26:18', 147, 'SpotiDownloader.com - Las 100 mejores canciones de la historia del Rock/Californication.mp3'),
(15, 'Guns N Roses', 'Civil War', 1991, 0.85, 0.55, 0.5, 0.3, '2025-04-26 00:26:18', 162, 'SpotiDownloader.com - Las 100 mejores canciones de la historia del Rock/Civil War.mp3'),
(16, 'Eric Clapton', 'Cocaine', 1977, 0.8, 0.7, 0.65, 0.25, '2025-04-26 00:26:18', 111, 'SpotiDownloader.com - Las 100 mejores canciones de la historia del Rock/Cocaine.mp3'),
(17, 'Radiohead', 'Creep', 1992, 0.75, 0.5, 0.35, 0.2, '2025-04-26 00:26:18', 131, 'SpotiDownloader.com - Las 100 mejores canciones de la historia del Rock/Creep.mp3'),
(18, 'Aerosmith', 'Cryin', 1993, 0.8, 0.6, 0.65, 0.2, '2025-04-26 00:26:18', 140, 'SpotiDownloader.com - Las 100 mejores canciones de la historia del Rock/Cryin\'.mp3'),
(19, 'Oasis', 'Dont Look Back In Anger', 1995, 0.85, 0.65, 0.7, 0.25, '2025-04-26 00:26:18', 130, 'SpotiDownloader.com - Las 100 mejores canciones de la historia del Rock/Dont Look Back In Anger.mp3'),
(20, 'Aerosmith', 'Dream On', 1973, 0.75, 0.45, 0.6, 0.3, '2025-04-26 00:26:18', 130, 'SpotiDownloader.com - Las 100 mejores canciones de la historia del Rock/Dream On.mp3'),
(21, 'Kansas', 'Dust in the Wind', 1977, 0.6, 0.4, 0.45, 0.4, '2025-04-26 00:26:18', 160, 'SpotiDownloader.com - Las 100 mejores canciones de la historia del Rock/Dust in the Wind.mp3'),
(22, 'Metallica', 'Enter Sandman', 1991, 0.95, 0.65, 0.7, 0.15, '2025-04-26 00:26:18', 149, 'SpotiDownloader.com - Las 100 mejores canciones de la historia del Rock/Enter Sandman.mp3'),
(23, 'Survivor', 'Eye of the Tiger - 2006 Master', 1982, 0.9, 0.75, 0.85, 0.1, '2025-04-26 00:26:18', 168, 'SpotiDownloader.com - Las 100 mejores canciones de la historia del Rock/Eye of the Tiger.mp3'),
(24, 'Lynyrd Skynyrd', 'Free Bird', 1973, 0.85, 0.55, 0.7, 0.35, '2025-04-26 00:26:18', 131, 'SpotiDownloader.com - Las 100 mejores canciones de la historia del Rock/Free Bird.mp3'),
(25, 'Queen', 'Hammer To Fall', 1984, 0.88, 0.7, 0.75, 0.2, '2025-04-26 00:26:18', 134, 'SpotiDownloader.com - Las 100 mejores canciones de la historia del Rock/Hammer To Fall.mp3'),
(26, 'Creedence Clearwater Revival', 'Have You Ever Seen The Rain', 1970, 0.7, 0.65, 0.6, 0.25, '2025-04-26 00:26:18', 178, 'SpotiDownloader.com - Las 100 mejores canciones de la historia del Rock/Have You Ever Seen The Rain.mp3'),
(27, 'Ratones Paranoicos', 'Hay poco rock n roll', 1988, 0.85, 0.7, 0.75, 0.2, '2025-04-26 00:26:18', 147, 'SpotiDownloader.com - Las 100 mejores canciones de la historia del Rock/Hay poco rock\'n\'roll.mp3'),
(28, 'Bryan Adams', 'Heaven', 1983, 0.65, 0.5, 0.7, 0.25, '2025-04-26 00:26:18', 103, 'SpotiDownloader.com - Las 100 mejores canciones de la historia del Rock/Heaven.mp3'),
(29, 'Jimi Hendrix', 'Hey Joe', 1966, 0.8, 0.6, 0.65, 0.3, '2025-04-26 00:26:18', 133, 'SpotiDownloader.com - Las 100 mejores canciones de la historia del Rock/Hey Joe.mp3'),
(30, 'AC/DC', 'Highway to Hell', 1979, 0.95, 0.75, 0.85, 0.15, '2025-04-26 00:26:18', 176, 'SpotiDownloader.com - Las 100 mejores canciones de la historia del Rock/Highway to Hell.mp3'),
(31, 'Eagles', 'Hotel California', 1977, 0.8, 0.65, 0.7, 0.3, '2025-04-26 00:26:18', 142, 'SpotiDownloader.com - Las 100 mejores canciones de la historia del Rock/Hotel California.mp3'),
(32, 'The Animals', 'House Of The Rising Sun', 1964, 0.75, 0.55, 0.5, 0.35, '2025-04-26 00:26:18', 163, 'SpotiDownloader.com - Las 100 mejores canciones de la historia del Rock/House Of The Rising Sun.mp3'),
(33, 'Aerosmith', 'I Dont Want to Miss a Thing', 1998, 0.75, 0.5, 0.65, 0.25, '2025-04-26 00:26:18', 130, 'SpotiDownloader.com - Las 100 mejores canciones de la historia del Rock/I Dont Want to Miss a Thing.mp3'),
(34, 'Queen', 'Innuendo', 1991, 0.85, 0.6, 0.7, 0.35, '2025-04-26 00:26:18', 142, 'SpotiDownloader.com - Las 100 mejores canciones de la historia del Rock/Innuendo - Remastered 2011.mp3'),
(35, 'Black Sabbath', 'Iron Man', 1970, 0.9, 0.55, 0.65, 0.25, '2025-04-26 00:26:18', 140, 'SpotiDownloader.com - Las 100 mejores canciones de la historia del Rock/Iron Man.mp3'),
(36, 'Bon Jovi', 'Its My Life', 2000, 0.85, 0.7, 0.8, 0.2, '2025-04-26 00:26:18', 174, 'SpotiDownloader.com - Las 100 mejores canciones de la historia del Rock/Its My Life.mp3'),
(37, 'The Rolling Stones', 'Its Only Rock n Roll', 1974, 0.85, 0.75, 0.8, 0.2, '2025-04-26 00:26:18', 109, 'SpotiDownloader.com - Las 100 mejores canciones de la historia del Rock/Its Only Rock n Roll.mp3'),
(38, 'Elvis Presley', 'Jailhouse Rock', 1957, 0.85, 0.8, 0.85, 0.15, '2025-04-26 00:26:18', 165, 'SpotiDownloader.com - Las 100 mejores canciones de la historia del Rock/Jailhouse Rock.mp3'),
(39, 'Chuck Berry', 'Johnny B. Goode', 1958, 0.9, 0.85, 0.9, 0.1, '2025-04-26 00:26:18', 157, 'SpotiDownloader.com - Las 100 mejores canciones de la historia del Rock/Johnny B. Goode.mp3'),
(40, 'Van Halen', 'Jump', 1984, 0.9, 0.8, 0.85, 0.2, '2025-04-26 00:26:18', 110, 'SpotiDownloader.com - Las 100 mejores canciones de la historia del Rock/Jump.mp3'),
(41, 'ZZ Top', 'La Grange', 1973, 0.85, 0.7, 0.75, 0.25, '2025-04-26 00:26:18', 140, 'SpotiDownloader.com - Las 100 mejores canciones de la historia del Rock/La Grange - 2005 Remaster.mp3'),
(42, 'The Beatles', 'Let It Be', 1970, 0.65, 0.5, 0.7, 0.3, '2025-04-26 00:26:18', 110, 'SpotiDownloader.com - Las 100 mejores canciones de la historia del Rock/Let It Be.mp3'),
(43, 'The Clash', 'London Calling', 1979, 0.88, 0.75, 0.7, 0.2, '2025-04-26 00:26:18', 112, 'SpotiDownloader.com - Las 100 mejores canciones de la historia del Rock/London Calling.mp3'),
(44, 'R.E.M.', 'Losing My Religion', 1991, 0.7, 0.6, 0.55, 0.35, '2025-04-26 00:26:18', 131, 'SpotiDownloader.com - Las 100 mejores canciones de la historia del Rock/Losing My Religion.mp3'),
(45, 'Metallica', 'Nothing Else Matters', 1991, 0.75, 0.45, 0.55, 0.3, '2025-04-26 00:26:18', 137, 'SpotiDownloader.com - Las 100 mejores canciones de la historia del Rock/Nothing Else Matters.mp3'),
(46, 'Guns N Roses', 'November Rain', 1991, 0.8, 0.5, 0.6, 0.35, '2025-04-26 00:26:18', 116, 'SpotiDownloader.com - Las 100 mejores canciones de la historia del Rock/November Rain.mp3'),
(47, 'The Rolling Stones', 'Paint It Black', 1966, 0.85, 0.7, 0.65, 0.25, '2025-04-26 00:26:18', 146, 'SpotiDownloader.com - Las 100 mejores canciones de la historia del Rock/Paint It Black.mp3'),
(48, 'Guns N Roses', 'Paradise City', 1987, 0.9, 0.75, 0.85, 0.15, '2025-04-26 00:26:18', 126, 'SpotiDownloader.com - Las 100 mejores canciones de la historia del Rock/Paradise City.mp3'),
(49, 'Black Sabbath', 'Paranoid', 1970, 0.95, 0.7, 0.75, 0.2, '2025-04-26 00:26:18', 169, 'SpotiDownloader.com - Las 100 mejores canciones de la historia del Rock/Paranoid.mp3'),
(50, 'Alice Cooper', 'Poison', 1989, 0.85, 0.75, 0.8, 0.2, '2025-04-26 00:26:18', 131, 'SpotiDownloader.com - Las 100 mejores canciones de la historia del Rock/Poison.mp3'),
(51, 'The Beatles', 'Revolution', 1968, 0.85, 0.7, 0.75, 0.25, '2025-04-26 00:26:18', 125, 'SpotiDownloader.com - Las 100 mejores canciones de la historia del Rock/Revolution - Remastered.mp3'),
(52, 'KISS', 'Rock And Roll All Nite', 1975, 0.9, 0.8, 0.85, 0.15, '2025-04-26 00:26:18', 133, 'SpotiDownloader.com - Las 100 mejores canciones de la historia del Rock/Rock And Roll All Nite.mp3'),
(53, 'Scorpions', 'Rock You Like a Hurricane', 1984, 0.9, 0.75, 0.8, 0.2, '2025-04-26 00:26:18', 113, 'SpotiDownloader.com - Las 100 mejores canciones de la historia del Rock/Rock You Like a Hurricane.mp3'),
(54, 'The White Stripes', 'Seven Nation Army', 2003, 0.85, 0.7, 0.65, 0.25, '2025-04-26 00:26:18', 144, 'SpotiDownloader.com - Las 100 mejores canciones de la historia del Rock/Seven Nation Army.mp3'),
(55, 'Nirvana', 'Smells Like Teen Spirit', 1991, 0.95, 0.7, 0.75, 0.2, '2025-04-26 00:26:18', 122, 'SpotiDownloader.com - Las 100 mejores canciones de la historia del Rock/Smells Like Teen Spirit.mp3'),
(56, 'David Bowie', 'Space Oddity', 1969, 0.75, 0.55, 0.6, 0.35, '2025-04-26 00:26:18', 159, 'SpotiDownloader.com - Las 100 mejores canciones de la historia del Rock/Space Oddity.mp3'),
(57, 'Led Zeppelin', 'Stairway to Heaven', 1971, 0.75, 0.45, 0.65, 0.4, '2025-04-26 00:26:18', 169, 'SpotiDownloader.com - Las 100 mejores canciones de la historia del Rock/Stairway to Heaven - Remaster.mp3'),
(58, 'Scorpions', 'Still Loving You', 1984, 0.7, 0.45, 0.6, 0.3, '2025-04-26 00:26:18', 111, 'SpotiDownloader.com - Las 100 mejores canciones de la historia del Rock/Still Loving You.mp3'),
(59, 'Dire Straits', 'Sultans Of Swing', 1978, 0.8, 0.7, 0.75, 0.25, '2025-04-26 00:26:18', 106, 'SpotiDownloader.com - Las 100 mejores canciones de la historia del Rock/Sultans Of Swing.mp3'),
(60, 'Guns N Roses', 'Sweet Child O Mine', 1987, 0.85, 0.65, 0.75, 0.2, '2025-04-26 00:26:18', 179, 'SpotiDownloader.com - Las 100 mejores canciones de la historia del Rock/Sweet Child O\' Mine.mp3'),
(62, 'Lynyrd Skynyrd', 'Sweet Home Alabama', 1974, 0.85, 0.75, 0.85, 0.2, '2025-04-26 00:26:18', 142, 'SpotiDownloader.com - Las 100 mejores canciones de la historia del Rock/Sweet Home Alabama.mp3'),
(63, 'AC/DC', 'T.N.T.', 1975, 0.95, 0.75, 0.85, 0.15, '2025-04-26 00:26:18', 145, 'SpotiDownloader.com - Las 100 mejores canciones de la historia del Rock/T.N.T.mp3'),
(64, 'Bruce Springsteen', 'The River', 1980, 0.7, 0.55, 0.5, 0.3, '2025-04-26 00:26:18', 121, 'SpotiDownloader.com - Las 100 mejores canciones de la historia del Rock/The River.mp3'),
(65, 'AC/DC', 'Thunderstruck', 1990, 0.95, 0.75, 0.85, 0.15, '2025-04-26 00:26:18', 149, 'SpotiDownloader.com - Las 100 mejores canciones de la historia del Rock/Thunderstruck.mp3'),
(66, 'Aerosmith', 'Walk This Way', 1975, 0.9, 0.8, 0.85, 0.15, '2025-04-26 00:26:18', 121, 'SpotiDownloader.com - Las 100 mejores canciones de la historia del Rock/Walk This Way.mp3'),
(67, 'Queen', 'We Are The Champions', 1977, 0.85, 0.65, 0.8, 0.25, '2025-04-26 00:26:18', 140, 'SpotiDownloader.com - Las 100 mejores canciones de la historia del Rock/We Are The Champions.mp3'),
(68, 'Guns N Roses', 'Welcome To The Jungle', 1987, 0.95, 0.75, 0.85, 0.15, '2025-04-26 00:26:18', 158, 'SpotiDownloader.com - Las 100 mejores canciones de la historia del Rock/Welcome To The Jungle.mp3'),
(69, 'Led Zeppelin', 'Whole Lotta Love', 1969, 0.9, 0.7, 0.8, 0.2, '2025-04-26 00:26:18', 111, 'SpotiDownloader.com - Las 100 mejores canciones de la historia del Rock/Whole Lotta Love.mp3'),
(70, 'Scorpions', 'Wind Of Change', 1990, 0.75, 0.55, 0.7, 0.3, '2025-04-26 00:26:18', 140, 'SpotiDownloader.com - Las 100 mejores canciones de la historia del Rock/Wind Of Change.mp3'),
(71, 'U2', 'With Or Without You', 1987, 0.75, 0.6, 0.65, 0.3, '2025-04-26 00:26:18', 109, 'SpotiDownloader.com - Las 100 mejores canciones de la historia del Rock/With Or Without You.mp3'),
(72, 'AC/DC', 'You Shook Me All Night Long', 1980, 0.9, 0.8, 0.85, 0.15, '2025-04-26 00:26:18', 103, 'SpotiDownloader.com - Las 100 mejores canciones de la historia del Rock/You Shook Me All Night Long.mp3'),
(73, 'The Cranberries', 'Zombie', 1994, 0.85, 0.6, 0.55, 0.25, '2025-04-26 00:26:18', 170, 'SpotiDownloader.com - Las 100 mejores canciones de la historia del Rock/Zombie.mp3'),
(75, 'The Eagles', 'Hotel California', 1977, 0.8, 0.6, 0.65, 0.3, '2025-04-26 00:26:18', 165, 'SpotiDownloader.com - Las 100 mejores canciones de la historia del Rock/Hotel California - 2013 Remaster.mp3'),
(118, 'Unknown', 'Don\'t Look Back In Anger', NULL, NULL, NULL, NULL, NULL, '2025-04-30 17:54:07', 139, 'SpotiDownloader.com - Las 100 mejores canciones de la historia del Rock/Don\'t Look Back In Anger - Remastered.mp3'),
(124, 'Unknown', 'Hammer To Fall - Remastered 2011', NULL, NULL, NULL, NULL, NULL, '2025-04-30 17:54:07', 166, 'SpotiDownloader.com - Las 100 mejores canciones de la historia del Rock/Hammer To Fall - Remastered 2011.mp3'),
(132, 'Unknown', 'I Don\'t Want to Miss a Thing - From the Touchstone film, _Armageddon_', NULL, NULL, NULL, NULL, NULL, '2025-04-30 17:54:07', 126, 'SpotiDownloader.com - Las 100 mejores canciones de la historia del Rock/I Don\'t Want to Miss a Thing - From the Touchstone film, _Armageddon_.mp3'),
(135, 'Unknown', 'It\'s My Life', NULL, NULL, NULL, NULL, NULL, '2025-04-30 17:54:07', 143, 'SpotiDownloader.com - Las 100 mejores canciones de la historia del Rock/It\'s My Life.mp3'),
(150, 'Unknown', 'Remastered 2002', NULL, NULL, NULL, NULL, NULL, '2025-04-30 17:54:07', 172, 'SpotiDownloader.com - Las 100 mejores canciones de la historia del Rock/Remastered 2002.mp3'),
(153, 'Unknown', 'Rock You Like a Hurricane - 2011', NULL, NULL, NULL, NULL, NULL, '2025-04-30 17:54:07', 116, 'SpotiDownloader.com - Las 100 mejores canciones de la historia del Rock/Rock You Like a Hurricane - 2011.mp3'),
(167, 'Unknown', 'We Are The Champions - Remastered 2011', NULL, NULL, NULL, NULL, NULL, '2025-04-30 17:54:07', 158, 'SpotiDownloader.com - Las 100 mejores canciones de la historia del Rock/We Are The Champions - Remastered 2011.mp3');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `songs`
--
ALTER TABLE `songs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_artist` (`artist`),
  ADD KEY `idx_name` (`name`),
  ADD KEY `idx_year` (`year`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `songs`
--
ALTER TABLE `songs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=174;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
