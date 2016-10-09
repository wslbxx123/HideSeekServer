<?php
namespace Home\DataAccess;
/**
 * 操作目标表(admin_monster_temp_hit)
 *
 * @author Two
 */
class MonsterTempHitManager {
    public function insertMonsterTempHit($goalId, $accountId, $accountRole, $valid) {
        $Dao = M("monster_temp_hit");
        
        if($valid == 1) {
            $data['goal_id'] = $goalId;
            $data['account_id'] = $accountId;
            $data['account_role'] = $accountRole;
            $data['hit_time'] = date('y-m-d H:i:s',time());
            $Dao->add($data);
        }

        $sql = "call admin_get_temp_hit($goalId)";
        $accountArray = $Dao->query($sql);
        
        return $accountArray;
    }
    
    public function getCount($accountId, $goalId) {
        $Dao = M("monster_temp_hit");
        
        $condition['goal_id'] = $goalId;
        $condition['account_id'] = $accountId;
        return $Dao->where($condition)->count();  
    }
}
