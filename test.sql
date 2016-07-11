SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';

CREATE SCHEMA IF NOT EXISTS `hideseek` DEFAULT CHARACTER SET utf8 ;
USE `hideseek` ;

-- -----------------------------------------------------
-- Table `hideseek`.`admin_account`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `hideseek`.`admin_account` (
  `pk_id` BIGINT(20) NOT NULL AUTO_INCREMENT ,
  `phone` VARCHAR(45) NOT NULL ,
  `password` VARCHAR(45) NOT NULL ,
  `nickname` VARCHAR(45) NULL DEFAULT NULL ,
  `register_date` DATETIME NULL DEFAULT NULL ,
  `record` INT(11) NULL DEFAULT NULL ,
  `photo_url` VARCHAR(200) NULL DEFAULT NULL ,
  `session_token` VARCHAR(100) NULL DEFAULT NULL ,
  `sex` INT(11) NULL DEFAULT NULL ,
  `region` VARCHAR(100) NULL DEFAULT NULL ,
  `role` INT(11) NULL DEFAULT NULL ,
  `version` BIGINT(20) NULL DEFAULT NULL ,
  PRIMARY KEY (`pk_id`) ,
  INDEX `VERSION_KEY` (`version` ASC) )
ENGINE = InnoDB
AUTO_INCREMENT = 55
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `hideseek`.`admin_friend`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `hideseek`.`admin_friend` (
  `pk_id` BIGINT(20) NOT NULL AUTO_INCREMENT ,
  `account_a_id` BIGINT(20) NULL DEFAULT NULL ,
  `account_b_id` BIGINT(20) NULL DEFAULT NULL ,
  PRIMARY KEY (`pk_id`) ,
  INDEX `FRIEND_ACCOUNT_ID_KEY` (`account_a_id` ASC) )
ENGINE = InnoDB
AUTO_INCREMENT = 2
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `hideseek`.`admin_goal`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `hideseek`.`admin_goal` (
  `pk_id` BIGINT(20) NOT NULL AUTO_INCREMENT ,
  `longitude` DOUBLE NULL DEFAULT NULL ,
  `latitude` DOUBLE NULL DEFAULT NULL ,
  `orientation` INT(11) NULL DEFAULT NULL ,
  `create_by` BIGINT(20) NULL DEFAULT NULL ,
  `update_time` DATETIME NULL DEFAULT NULL ,
  `valid` INT(11) NULL DEFAULT NULL ,
  `type` INT(11) NULL DEFAULT NULL ,
  `monster_type` INT(11) NULL DEFAULT NULL ,
  PRIMARY KEY (`pk_id`) )
ENGINE = InnoDB
AUTO_INCREMENT = 20
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `hideseek`.`admin_monster_type`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `hideseek`.`admin_monster_type` (
  `PK_ID` BIGINT(20) NOT NULL AUTO_INCREMENT ,
  `UNION_TYPE` INT(11) NULL DEFAULT NULL ,
  PRIMARY KEY (`PK_ID`) )
ENGINE = InnoDB
AUTO_INCREMENT = 3
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `hideseek`.`admin_monster_role`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `hideseek`.`admin_monster_role` (
  `pk_id` BIGINT(20) NOT NULL AUTO_INCREMENT ,
  `monster_type_id` BIGINT(20) NOT NULL ,
  `account_role` INT(11) NOT NULL ,
  PRIMARY KEY (`pk_id`) ,
  UNIQUE INDEX `MONSTER_TYPE_ROLE_KEY` (`monster_type_id` ASC, `account_role` ASC) ,
  INDEX `MONSTER_TYPE_KDY` (`monster_type_id` ASC) ,
  CONSTRAINT `MONSTER_TYPE_KEY`
    FOREIGN KEY (`monster_type_id` )
    REFERENCES `hideseek`.`admin_monster_type` (`PK_ID` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 8
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `hideseek`.`admin_monster_temp_hit`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `hideseek`.`admin_monster_temp_hit` (
  `pk_id` BIGINT(20) NOT NULL AUTO_INCREMENT ,
  `goal_id` BIGINT(20) NOT NULL ,
  `account_id` BIGINT(20) NOT NULL ,
  `account_role` INT(11) NULL DEFAULT NULL ,
  `hit_time` DATETIME NULL DEFAULT NULL ,
  PRIMARY KEY (`pk_id`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `hideseek`.`admin_pull_version`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `hideseek`.`admin_pull_version` (
  `race_group_version` BIGINT(20) NOT NULL ,
  `friend_version` BIGINT(20) NOT NULL ,
  PRIMARY KEY (`race_group_version`, `friend_version`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `hideseek`.`admin_record`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `hideseek`.`admin_record` (
  `pk_id` BIGINT(20) NOT NULL AUTO_INCREMENT ,
  `account_id` BIGINT(20) NOT NULL ,
  `score` INT(11) NULL DEFAULT NULL ,
  `goal_id` BIGINT(20) NULL DEFAULT NULL ,
  `goal_type` INT(11) NULL DEFAULT NULL ,
  `time` DATETIME NULL DEFAULT NULL ,
  `score_sum` INT(11) NULL DEFAULT NULL ,
  `version` BIGINT(20) NULL DEFAULT NULL ,
  PRIMARY KEY (`pk_id`) ,
  INDEX `USER_ID_KEY` (`account_id` ASC) ,
  INDEX `TIME_INDEX` (`time` ASC) )
ENGINE = InnoDB
AUTO_INCREMENT = 12
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Placeholder table for view `hideseek`.`admin_monster_role_v`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `hideseek`.`admin_monster_role_v` (`PK_ID` INT, `LONGITUDE` INT, `LATITUDE` INT, `ORIENTATION` INT, `CREATE_BY` INT, `UPDATE_TIME` INT, `VALID` INT, `TYPE` INT, `MONSTER_TYPE` INT, `ACCOUNT_ROLE` INT, `UNION_TYPE` INT);

-- -----------------------------------------------------
-- procedure admin_get_friend
-- -----------------------------------------------------

DELIMITER $$
USE `hideseek`$$
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

DELIMITER ;

-- -----------------------------------------------------
-- procedure admin_get_friend_record
-- -----------------------------------------------------

DELIMITER $$
USE `hideseek`$$
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

DELIMITER ;

-- -----------------------------------------------------
-- procedure admin_get_record
-- -----------------------------------------------------

DELIMITER $$
USE `hideseek`$$
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

DELIMITER ;

-- -----------------------------------------------------
-- procedure admin_get_temp_hit
-- -----------------------------------------------------

DELIMITER $$
USE `hideseek`$$
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

DELIMITER ;

-- -----------------------------------------------------
-- procedure admin_monster_role_p
-- -----------------------------------------------------

DELIMITER $$
USE `hideseek`$$
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

DELIMITER ;

-- -----------------------------------------------------
-- procedure admin_refresh_friend_record
-- -----------------------------------------------------

DELIMITER $$
USE `hideseek`$$
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

DELIMITER ;

-- -----------------------------------------------------
-- procedure admin_refresh_record
-- -----------------------------------------------------

DELIMITER $$
USE `hideseek`$$
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

-- -----------------------------------------------------
-- View `hideseek`.`admin_monster_role_v`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `hideseek`.`admin_monster_role_v`;
USE `hideseek`;
CREATE  OR REPLACE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `hideseek`.`admin_monster_role_v` AS select `hideseek`.`admin_goal`.`pk_id` AS `PK_ID`,`hideseek`.`admin_goal`.`longitude` AS `LONGITUDE`,`hideseek`.`admin_goal`.`latitude` AS `LATITUDE`,`hideseek`.`admin_goal`.`orientation` AS `ORIENTATION`,`hideseek`.`admin_goal`.`create_by` AS `CREATE_BY`,`hideseek`.`admin_goal`.`update_time` AS `UPDATE_TIME`,`hideseek`.`admin_goal`.`valid` AS `VALID`,`hideseek`.`admin_goal`.`type` AS `TYPE`,`hideseek`.`admin_goal`.`monster_type` AS `MONSTER_TYPE`,NULL AS `ACCOUNT_ROLE`,NULL AS `UNION_TYPE` from `hideseek`.`admin_goal` where ((`hideseek`.`admin_goal`.`type` = 1) or (`hideseek`.`admin_goal`.`type` = 2)) union select `hideseek`.`admin_goal`.`pk_id` AS `PK_ID`,`hideseek`.`admin_goal`.`longitude` AS `LONGITUDE`,`hideseek`.`admin_goal`.`latitude` AS `LATITUDE`,`hideseek`.`admin_goal`.`orientation` AS `ORIENTATION`,`hideseek`.`admin_goal`.`create_by` AS `CREATE_BY`,`hideseek`.`admin_goal`.`update_time` AS `UPDATE_TIME`,`hideseek`.`admin_goal`.`valid` AS `VALID`,`hideseek`.`admin_goal`.`type` AS `TYPE`,`hideseek`.`admin_goal`.`monster_type` AS `MONSTER_TYPE`,`hideseek`.`admin_monster_role`.`account_role` AS `ACCOUNT_ROLE`,`hideseek`.`admin_monster_type`.`UNION_TYPE` AS `UNION_TYPE` from ((`hideseek`.`admin_goal` left join `hideseek`.`admin_monster_role` on((`hideseek`.`admin_goal`.`monster_type` = `hideseek`.`admin_monster_role`.`monster_type_id`))) left join `hideseek`.`admin_monster_type` on((`hideseek`.`admin_goal`.`monster_type` = `hideseek`.`admin_monster_type`.`PK_ID`)));


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
