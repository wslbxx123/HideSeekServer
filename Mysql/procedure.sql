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
    from admin_goal_monster_v g
    where g.LATITUDE < input_latitude + 10 and g.LATITUDE > input_latitude - 10
        and g.LONGITUDE < input_longitude + 10 and g.LONGITUDE > input_longitude - 10
        and (input_update_time = 'null' and g.VALID = 1
            or g.UPDATE_TIME > input_update_time)
        and (input_account_role > 0 or g.Type = 1)
    order by g.UPDATE_TIME desc;
END