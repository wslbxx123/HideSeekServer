<?php
namespace Home\DataAccess;
/**
 * 操作怪兽看见表(admin_monster_temp_see)
 *
 * @author Two
 */
class MonsterTempSeeManager {
    public function insertMonsterTempSee($accountId, $goalId) {
        $Dao = M("monster_temp_see");
        $tempSee["account_id"] = $accountId;
        $tempSee["goal_id"] = $goalId;
        $tempSee["create_time"] = date('y-m-d H:i:s',time());
        return $Dao->add($tempSee);
    }
    
    public function getMonsterTempSee($accountId, $goalId) {
        $Dao = M("monster_temp_see");
        $condition["account_id"] = $accountId;
        $condition["goal_id"] = $goalId;
        return $Dao->where($condition)->find();
    }
}
