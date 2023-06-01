-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- 생성 시간: 23-05-30 19:56
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
-- 테이블 구조 `income_table`
--

CREATE TABLE `income_table` (
  `id` int(11) NOT NULL,
  `date` date NOT NULL,
  `category` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `amount` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 테이블의 덤프 데이터 `income_table`
--

INSERT INTO `income_table` (`id`, `date`, `category`, `description`, `amount`) VALUES
(42, '2023-01-01', '2022년 잔액 이월', '전체회원: 명', 7000000),
(44, '2023-01-29', '1월 회비', '전원 입금완료!', 200000),
(58, '0000-00-00', '2월 회비', '전원 입금완료!', 200000),
(61, '2023-05-16', '오이', '', 1000);

--
-- 덤프된 테이블의 인덱스
--

--
-- 테이블의 인덱스 `income_table`
--
ALTER TABLE `income_table`
  ADD PRIMARY KEY (`id`);

--
-- 덤프된 테이블의 AUTO_INCREMENT
--

--
-- 테이블의 AUTO_INCREMENT `income_table`
--
ALTER TABLE `income_table`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
