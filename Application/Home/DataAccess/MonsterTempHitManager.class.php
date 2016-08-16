<?php
namespace Home\DataAccess;
/**
 * 操作目标表(admin_monster_temp_hit)
 *
 * @author Two
 */
class MonsterTempHitManager {
    public function insertMonsterTempHit($goalId, $accountId, $accountRole) {
        $Dao = M("monster_temp_hit");
        $data['goal_id'] = $goalId;
        $data['account_id'] = $accountId;
        $data['account_role'] = $accountRole;
        $data['hit_time'] = date('y-m-d H:i:s',time());
        $Dao->add($data);

        $sql = "call admin_get_temp_hit($goalId)";
        $accountArray = $Dao->query($sql);
        
        return $accountArray;
    }
}
