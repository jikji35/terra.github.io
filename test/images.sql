-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- 생성 시간: 23-05-30 23:12
-- 서버 버전: 10.4.27-MariaDB
-- PHP 버전: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 데이터베이스: `terraone`
--

-- --------------------------------------------------------

--
-- 테이블 구조 `images`
--

CREATE TABLE `images` (
  `idx` int(11) NOT NULL,
  `id` varchar(25) DEFAULT NULL,
  `password` varchar(25) DEFAULT NULL,
  `photo` blob DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  `notice` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 테이블의 덤프 데이터 `images`
--

INSERT INTO `images` (`idx`, `id`, `password`, `photo`, `date`, `notice`) VALUES
(66, NULL, NULL, 0x646174612f70726f66696c652f62622e676966, '2023-05-27 11:50:30', '대한민국-1'),
(71, NULL, NULL, 0x646174612f70726f66696c652f6a696b6a692e6a7067, '2023-04-27 15:58:28', '대한민국-2\r\n\r\n'),
(72, NULL, NULL, 0x646174612f70726f66696c652f6a696b6a6963686f2e706e67, '2023-05-27 15:58:43', '대한민국-2'),
(115, NULL, NULL, 0x646174612f70726f66696c652f6e6577312e676966, '2023-05-30 11:50:28', 'ㅎㅎㅎㅎ');

--
-- 덤프된 테이블의 인덱스
--

--
-- 테이블의 인덱스 `images`
--
ALTER TABLE `images`
  ADD PRIMARY KEY (`idx`);

--
-- 덤프된 테이블의 AUTO_INCREMENT
--

--
-- 테이블의 AUTO_INCREMENT `images`
--
ALTER TABLE `images`
  MODIFY `idx` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=116;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
