-- phpMyAdmin SQL Dump
-- version 4.1.6
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: 2016-07-14 02:30:06
-- 服务器版本： 5.6.16
-- PHP Version: 5.5.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `hideseek`
--

-- --------------------------------------------------------

--
-- 视图结构 `admin_goal_monster_v`
--

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `admin_goal_monster_v` AS select `g`.`pk_id` AS `pk_id`,`g`.`longitude` AS `longitude`,`g`.`latitude` AS `latitude`,`g`.`orientation` AS `orientation`,`g`.`create_by` AS `create_by`,`g`.`update_time` AS `update_time`,`g`.`valid` AS `valid`,`g`.`type` AS `type`,`g`.`monster_type` AS `monster_type`,`m`.`UNION_TYPE` AS `UNION_TYPE`,`m`.`SHOW_TYPE_NAME` AS `SHOW_TYPE_NAME` from (`admin_goal` `g` left join `admin_monster_type` `m` on((`g`.`monster_type` = `m`.`PK_ID`)));

--
-- VIEW  `admin_goal_monster_v`
-- Data: 无
--


/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
