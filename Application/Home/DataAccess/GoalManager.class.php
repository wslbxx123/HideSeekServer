<?php
namespace Home\DataAccess;
/**
 * 操作目标表(admin_goal)
 *
 * @author Two
 */
class GoalManager {
    public function updateGoal($valid, $goalId) {
        $Dao = M("goal");
        $data['valid'] = $valid;
        $data['update_time'] = date('y-m-d H:i:s',time());
        $Dao->where("pk_id=$goalId")->save($data);
    }
    
    public function getGoalInfo($latitude, $longitude, $accountRole, $updateTime) {
        $Dao = M("goal");
        $sql = "call admin_monster_role_p($latitude, $longitude, "
                    . "$accountRole, \"$updateTime\")";
        $goals = $Dao->query($sql);
        $pos = array_search(max($goals['update_time']), $goals);
        
        if(count($goals) > 0) {
            $updateTimestamp = $goals[$pos]['update_time'];
        } else {
            $updateTimestamp = $updateTime;
        }
        
        return array (
                'update_time' => $updateTimestamp,
                'goals' => $goals);
    }
    
    public function getGoal($goalId) {
        $Dao = M("goal");
        $condition['pk_id'] = $goalId;
        $goal = $Dao->where($condition)->find();
        return $goal;
    }
}
