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
}
