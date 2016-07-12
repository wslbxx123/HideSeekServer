-- phpMyAdmin SQL Dump
-- version 4.1.6
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: 2016-07-12 04:14:33
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

DELIMITER $$
--
-- 存储过程
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `admin_get_friend`(
    input_account_id bigint)
BEGIN
    select * from(select a.* from admin_friend f 
            inner join admin_account a
            on f.account_a_id = a.pk_id
            where f.account_b_id = input_account_id 
        union all
        select a.* from admin_friend f 
            inner join admin_account a
            on f.account_b_id = a.pk_id
            where f.account_a_id = input_account_id) t
            order by t.version;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `admin_get_friend_record`(
    input_account_id bigint,
    input_version bigint,
    input_record_min_id bigint)
BEGIN
    select * from (
        select r.*, a.nickname, a.photo_url from admin_friend f 
            inner join admin_record r
            on f.account_a_id = r.account_id
            inner join admin_account a
            on f.account_a_id = a.pk_id
            where f.account_b_id = input_account_id 
                and r.version <= input_version
                and r.pk_id < input_record_min_id
        union all
        select r.*, a.nickname, a.photo_url from admin_friend f 
            inner join admin_record r
            on f.account_b_id = r.account_id
            inner join admin_account a
            on f.account_b_id = a.pk_id
            where f.account_a_id = input_account_id 
                and r.version <= input_version
                and r.pk_id < input_record_min_id
        union all
        select r.*, a.nickname, a.photo_url from admin_record r
            inner join admin_account a
            on r.account_id = a.pk_id
            where r.account_id = input_account_id 
                and r.version <= input_version
                and r.pk_id < input_record_min_id
    ) t order by t.time desc limit 20;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `admin_get_record`(
    input_account_id bigint,
    input_version bigint,
    input_record_min_id bigint)
BEGIN
    select r.* from admin_record r
            inner join admin_account a
            on r.account_id = a.pk_id
            where r.account_id = input_account_id 
                and r.version <= input_version
                and r.pk_id < input_record_min_id
    order by r.time desc limit 20;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `admin_get_temp_hit`(
    input_goal_id bigint)
BEGIN
    declare done int; 
    declare union_num int;
    declare account_role_id int;
    declare temp_monster_type_id int;
    declare temp_count int;
    declare temp_role_count int;
    declare monster_type_id bigint;
    declare monster_cursor CURSOR FOR 
        select account_role,  monster_type_id 
        from admin_monster_role;
    declare continue handler FOR SQLSTATE '02000' SET done = 1; 

    select t.union_type, g.monster_type into union_num, monster_type_id 
        from admin_goal g
        inner join admin_monster_type t
        on g.monster_type = t.pk_id
        where g.pk_id = input_goal_id;
    set temp_role_count = 0;
    open monster_cursor;
    repeat  
        fetch monster_cursor into account_role_id, temp_monster_type_id;  

        if temp_monster_type_id = monster_type_id then
            select count(*) into temp_count from admin_monster_temp_hit
                where goal_id = 4 and account_role = account_role_id;

            if temp_count >= 5 then
                set temp_role_count = temp_role_count + 1;
            end if;
        end if;
    until done end repeat; 
    close monster_cursor; 

    if temp_role_count >= union_num then
        update admin_goal set valid = 0, update_time = now() where pk_id = input_goal_id;

        select substring_index(group_concat(t.account_id), ',', 1) account_id from(
                select distinct 
                    h.goal_id, 
                    h.account_id, 
                    h.account_role, 
                    h.hit_time 
                    from admin_monster_role r
                    inner join admin_monster_temp_hit h
                    on r.account_role = h.account_role
                    where r.monster_type_id = monster_type_id 
                    order by h.hit_time, h.account_role) t
                    group by t.account_role limit union_num;
    else
        select null from dual;
    end if;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `admin_monster_role_p`(
    input_latitude double,
    input_longitude double,
    input_account_role int,
    input_update_time TEXT)
BEGIN
    DECLARE pkIds TEXT;

    select GROUP_CONCAT(g.PK_ID) into pkIds from admin_goal g join admin_monster_role r
    on g.MONSTER_TYPE = r.MONSTER_TYPE_ID
    where r.ACCOUNT_ROLE = input_account_role
        and g.TYPE = 2
        and g.LATITUDE < input_latitude + 10 and g.LATITUDE > input_latitude - 10
        and g.LONGITUDE < input_longitude + 10 and g.LONGITUDE > input_longitude - 10;

    select *,
        case when (FIND_IN_SET(g.PK_ID, pkIds) > 0 or g.TYPE = 1 
        or g.TYPE = 3) = 1 then 1 else 0 end as IS_ENABLED
    from admin_goal g
    where g.LATITUDE < input_latitude + 10 and g.LATITUDE > input_latitude - 10
        and g.LONGITUDE < input_longitude + 10 and g.LONGITUDE > input_longitude - 10
        and (input_update_time = 'null' and g.VALID = 1
            or g.UPDATE_TIME > input_update_time)
        and (input_account_role > 0 or g.Type = 1)
    order by g.UPDATE_TIME desc;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `admin_refresh_friend_record`(
    input_account_id bigint,
    input_version bigint,
    input_record_min_id bigint)
BEGIN
    select * from (
        select r.*, a.nickname, a.photo_url from admin_friend f 
            inner join admin_record r
            on f.account_a_id = r.account_id
            inner join admin_account a
            on f.account_a_id = a.pk_id
            where f.account_b_id = input_account_id 
                and r.version > input_version 
                    and (input_record_min_id = 0 or r.pk_id >= input_record_min_id)
        union all
        select r.*, a.nickname, a.photo_url from admin_friend f 
            inner join admin_record r
            on f.account_b_id = r.account_id
            inner join admin_account a
            on f.account_b_id = a.pk_id
            where f.account_a_id = input_account_id 
                and r.version > input_version 
                    and (input_record_min_id = 0 or r.pk_id >= input_record_min_id)
        union all
        select r.*, a.nickname, a.photo_url from admin_record r
            inner join admin_account a
            on r.account_id = a.pk_id
            where r.account_id = input_account_id
                and r.version > input_version 
                    and (input_record_min_id = 0 or r.pk_id >= input_record_min_id)
    ) t order by t.pk_id desc limit 20;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `admin_refresh_record`(
    input_account_id bigint,
    input_version bigint,
    input_record_min_id bigint)
BEGIN
    select r.* from admin_record r
            inner join admin_account a
            on r.account_id = a.pk_id
            where r.account_id = input_account_id
                and r.version > input_version 
                    and (input_record_min_id = 0 or r.pk_id >= input_record_min_id)
    order by r.pk_id desc limit 20;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- 表的结构 `admin_account`
--

CREATE TABLE IF NOT EXISTS `admin_account` (
  `pk_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `phone` varchar(45) NOT NULL,
  `password` varchar(45) NOT NULL,
  `nickname` varchar(45) DEFAULT NULL,
  `register_date` datetime DEFAULT NULL,
  `record` int(11) DEFAULT NULL,
  `photo_url` varchar(200) DEFAULT NULL,
  `session_token` varchar(100) DEFAULT NULL,
  `sex` int(11) DEFAULT NULL,
  `region` varchar(100) DEFAULT NULL,
  `role` int(11) DEFAULT NULL,
  `version` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`pk_id`),
  KEY `VERSION_KEY` (`version`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=55 ;

--
-- 转存表中的数据 `admin_account`
--

INSERT INTO `admin_account` (`pk_id`, `phone`, `password`, `nickname`, `register_date`, `record`, `photo_url`, `session_token`, `sex`, `region`, `role`, `version`) VALUES
(3, '13510239470', '202cb962ac59075b964b07152d234b70', '蜡笔象象', '2016-04-29 04:15:36', 100, 'http://www.hideseek.cn/Public/Image/Upload_4c2fc069cc26cd88e9bfc3b10eabb0be_1359556810913.jpg', '4c2fc069cc26cd88e9bfc3b10eabb0be', 1, '深圳', 3, 1),
(4, '13510239614', '202cb962ac59075b964b07152d234b70', '上官宛伊', '2016-04-29 16:16:06', 50, 'http://www.hideseek.cn/public/Image/Upload_1dfe43b9159183395f7f921b144448d9_1803946095.jpg', '1dfe43b9159183395f7f921b144448d9', 1, '深圳', 1, 1);

-- --------------------------------------------------------

--
-- 表的结构 `admin_friend`
--

CREATE TABLE IF NOT EXISTS `admin_friend` (
  `pk_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `account_a_id` bigint(20) DEFAULT NULL,
  `account_b_id` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`pk_id`),
  KEY `FRIEND_ACCOUNT_ID_KEY` (`account_a_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- 转存表中的数据 `admin_friend`
--

INSERT INTO `admin_friend` (`pk_id`, `account_a_id`, `account_b_id`) VALUES
(1, 3, 4);

-- --------------------------------------------------------

--
-- 表的结构 `admin_goal`
--

CREATE TABLE IF NOT EXISTS `admin_goal` (
  `pk_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `longitude` double DEFAULT NULL,
  `latitude` double DEFAULT NULL,
  `orientation` int(11) DEFAULT NULL,
  `create_by` bigint(20) DEFAULT NULL,
  `update_time` datetime DEFAULT NULL,
  `valid` int(11) DEFAULT NULL,
  `type` int(11) DEFAULT NULL,
  `monster_type` int(11) DEFAULT NULL,
  PRIMARY KEY (`pk_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=20 ;

--
-- 转存表中的数据 `admin_goal`
--

INSERT INTO `admin_goal` (`pk_id`, `longitude`, `latitude`, `orientation`, `create_by`, `update_time`, `valid`, `type`, `monster_type`) VALUES
(1, 113.892027, 22.560114, 0, 4, '2016-06-04 18:43:02', 0, 3, 1),
(2, 123.892027, 23.560114, 90, 4, '2016-05-18 14:15:36', 1, 2, 1),
(3, 113.892027, 23.060114, 180, 4, '2016-05-31 21:33:44', 1, 1, NULL),
(4, 113.892027, 22.560214, 270, 4, '2016-06-04 18:21:09', 0, 2, 1);

-- --------------------------------------------------------

--
-- 表的结构 `admin_monster_role`
--

CREATE TABLE IF NOT EXISTS `admin_monster_role` (
  `pk_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `monster_type_id` bigint(20) NOT NULL,
  `account_role` int(11) NOT NULL,
  PRIMARY KEY (`pk_id`),
  UNIQUE KEY `MONSTER_TYPE_ROLE_KEY` (`monster_type_id`,`account_role`),
  KEY `MONSTER_TYPE_KDY` (`monster_type_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

--
-- 转存表中的数据 `admin_monster_role`
--

INSERT INTO `admin_monster_role` (`pk_id`, `monster_type_id`, `account_role`) VALUES
(2, 1, 1),
(3, 1, 2),
(4, 1, 3),
(5, 1, 4),
(1, 1, 5),
(7, 2, 0),
(6, 2, 2);

-- --------------------------------------------------------

--
-- 替换视图以便查看 `admin_monster_role_v`
--
CREATE TABLE IF NOT EXISTS `admin_monster_role_v` (
`PK_ID` bigint(20)
,`LONGITUDE` double
,`LATITUDE` double
,`ORIENTATION` int(11)
,`CREATE_BY` bigint(20)
,`UPDATE_TIME` datetime
,`VALID` int(11)
,`TYPE` int(11)
,`MONSTER_TYPE` int(11)
,`ACCOUNT_ROLE` int(11)
,`UNION_TYPE` int(11)
);
-- --------------------------------------------------------

--
-- 表的结构 `admin_monster_temp_hit`
--

CREATE TABLE IF NOT EXISTS `admin_monster_temp_hit` (
  `pk_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `goal_id` bigint(20) NOT NULL,
  `account_id` bigint(20) NOT NULL,
  `account_role` int(11) DEFAULT NULL,
  `hit_time` datetime DEFAULT NULL,
  PRIMARY KEY (`pk_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `admin_monster_type`
--

CREATE TABLE IF NOT EXISTS `admin_monster_type` (
  `PK_ID` bigint(20) NOT NULL AUTO_INCREMENT,
  `UNION_TYPE` int(11) DEFAULT NULL,
  PRIMARY KEY (`PK_ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- 转存表中的数据 `admin_monster_type`
--

INSERT INTO `admin_monster_type` (`PK_ID`, `UNION_TYPE`) VALUES
(1, 1),
(2, 1);

-- --------------------------------------------------------

--
-- 表的结构 `admin_pull_version`
--

CREATE TABLE IF NOT EXISTS `admin_pull_version` (
  `race_group_version` bigint(20) NOT NULL,
  `friend_version` bigint(20) NOT NULL,
  PRIMARY KEY (`race_group_version`,`friend_version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `admin_pull_version`
--

INSERT INTO `admin_pull_version` (`race_group_version`, `friend_version`) VALUES
(1, 1);

-- --------------------------------------------------------

--
-- 表的结构 `admin_record`
--

CREATE TABLE IF NOT EXISTS `admin_record` (
  `pk_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `account_id` bigint(20) NOT NULL,
  `score` int(11) DEFAULT NULL,
  `goal_id` bigint(20) DEFAULT NULL,
  `goal_type` int(11) DEFAULT NULL,
  `time` datetime DEFAULT NULL,
  `score_sum` int(11) DEFAULT NULL,
  `version` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`pk_id`),
  KEY `USER_ID_KEY` (`account_id`),
  KEY `TIME_INDEX` (`time`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=12 ;

-- --------------------------------------------------------

--
-- 视图结构 `admin_monster_role_v`
--
DROP TABLE IF EXISTS `admin_monster_role_v`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `admin_monster_role_v` AS select `admin_goal`.`pk_id` AS `PK_ID`,`admin_goal`.`longitude` AS `LONGITUDE`,`admin_goal`.`latitude` AS `LATITUDE`,`admin_goal`.`orientation` AS `ORIENTATION`,`admin_goal`.`create_by` AS `CREATE_BY`,`admin_goal`.`update_time` AS `UPDATE_TIME`,`admin_goal`.`valid` AS `VALID`,`admin_goal`.`type` AS `TYPE`,`admin_goal`.`monster_type` AS `MONSTER_TYPE`,NULL AS `ACCOUNT_ROLE`,NULL AS `UNION_TYPE` from `admin_goal` where ((`admin_goal`.`type` = 1) or (`admin_goal`.`type` = 2)) union select `admin_goal`.`pk_id` AS `PK_ID`,`admin_goal`.`longitude` AS `LONGITUDE`,`admin_goal`.`latitude` AS `LATITUDE`,`admin_goal`.`orientation` AS `ORIENTATION`,`admin_goal`.`create_by` AS `CREATE_BY`,`admin_goal`.`update_time` AS `UPDATE_TIME`,`admin_goal`.`valid` AS `VALID`,`admin_goal`.`type` AS `TYPE`,`admin_goal`.`monster_type` AS `MONSTER_TYPE`,`admin_monster_role`.`account_role` AS `ACCOUNT_ROLE`,`admin_monster_type`.`UNION_TYPE` AS `UNION_TYPE` from ((`admin_goal` left join `admin_monster_role` on((`admin_goal`.`monster_type` = `admin_monster_role`.`monster_type_id`))) left join `admin_monster_type` on((`admin_goal`.`monster_type` = `admin_monster_type`.`PK_ID`)));

--
-- 限制导出的表
--

--
-- 限制表 `admin_monster_role`
--
ALTER TABLE `admin_monster_role`
  ADD CONSTRAINT `MONSTER_TYPE_KEY` FOREIGN KEY (`monster_type_id`) REFERENCES `admin_monster_type` (`PK_ID`) ON DELETE NO ACTION ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
