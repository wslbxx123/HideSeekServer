<?php
namespace Home\DataAccess;
/**
 * 操作目标表(admin_goal)
 *
 * @author Two
 */
class GoalManager {
    public function updateGoal($valid, $goalId, $version) {
        $Dao = M("goal");
        $data['valid'] = $valid;
        $data['update_time'] = date('y-m-d H:i:s',time());
        $data['version'] = $version;
        $Dao->where("pk_id=$goalId")->save($data);
    }
    
    public function getGoalInfo($latitude, $longitude, $accountRole, $version) {
        $Dao = M("goal");
        $sql = "call admin_get_goals($latitude, $longitude, "
                    . "$accountRole, $version)";
        $goals = $Dao->query($sql);
        
        return $goals;
    }
    
    public function getGoal($goalId) {
        $Dao = M("goal");
        $condition['pk_id'] = $goalId;
        $goal = $Dao->where($condition)->find();
        return $goal;
    }
    
    public function insertGoal($latitude, $longitude, $orientation, $accountId,
            $version) {
        $Dao = M("goal");
        $goal['latitude'] = $latitude;
        $goal['longitude'] = $longitude;
        $goal['orientation'] = $orientation;
        $goal['create_by'] = $accountId;
        $goal['update_time'] = date('y-m-d H:i:s',time());
        $goal['valid'] = 1;
        $goal['type'] = 3;
        $goal['version'] = $version;
        $Dao->add($goal);
    }
}
