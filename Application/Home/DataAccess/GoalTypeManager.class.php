<?php
namespace Home\DataAccess;
/**
 * 目标类型表(admin_goal_type)
 *
 * @author Two
 */
class GoalTypeManager {
    public function getScore($typeId) {
        $Dao = M("goal_type");
        $condition['pk_id'] = $typeId;
        $goalType = $Dao->where($condition)->find();
        return $goalType['score'];
    }
}
