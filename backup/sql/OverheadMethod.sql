-- phpMyAdmin SQL Dump
-- version 4.6.6deb4
-- https://www.phpmyadmin.net/
--
-- 主機: localhost:3306
-- 產生時間： 2018 年 09 月 07 日 02:22
-- 伺服器版本: 10.1.26-MariaDB-0+deb9u1
-- PHP 版本： 7.0.30-0+deb9u1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 資料庫： `accounting`
--

-- --------------------------------------------------------

--
-- 資料表結構 `OverheadMethod`
--

CREATE TABLE `OverheadMethod` (
  `user_id` varchar(64) NOT NULL,
  `type_id` int(11) NOT NULL COMMENT '開銷種類編號',
  `type_name` varchar(64) NOT NULL COMMENT '開銷種類名稱',
  `overhead_category` varchar(64) NOT NULL DEFAULT '支出' COMMENT '收入或支出',
  `valid` varchar(1) NOT NULL DEFAULT 'F' COMMENT '是否啟用',
  `CheckoutDay` varchar(2) DEFAULT NULL COMMENT '結帳日',
  `PaymentDay` varchar(2) DEFAULT NULL COMMENT '繳費日'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 資料表的匯出資料 `OverheadMethod`
--

INSERT INTO `OverheadMethod` (`user_id`, `type_id`, `type_name`, `overhead_category`, `valid`, `CheckoutDay`, `PaymentDay`) VALUES
('miaw52777@gmail.com', 1, '現金', '支出', 'T', NULL, NULL),
('miaw52777@gmail.com', 2, '中國信託', '支出', 'T', '12', '1'),
('miaw52777@gmail.com', 3, '第一銀行', '收入', 'T', NULL, NULL),
('miaw52777@gmail.com', 4, '中國信托銀行', '收入', 'T', NULL, NULL),
('miaw52777@gmail.com', 5, '中國信託附卡', '支出', 'T', '12', '1'),
('miaw52777@gmail.com', 6, '匯豐銀行', '收入', 'T', NULL, NULL);

--
-- 已匯出資料表的索引
--

--
-- 資料表索引 `OverheadMethod`
--
ALTER TABLE `OverheadMethod`
  ADD PRIMARY KEY (`type_id`);

--
-- 在匯出的資料表使用 AUTO_INCREMENT
--

--
-- 使用資料表 AUTO_INCREMENT `OverheadMethod`
--
ALTER TABLE `OverheadMethod`
  MODIFY `type_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '開銷種類編號', AUTO_INCREMENT=7;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
