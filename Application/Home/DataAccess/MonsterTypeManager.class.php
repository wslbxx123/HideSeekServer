<?php
namespace Home\DataAccess;
/**
 * 目标类型表(admin_monster_type)
 *
 * @author Two
 */
class MonsterTypeManager {
    public function getScore($typeId) {
        $Dao = M("monster_type");
        $condition['pk_id'] = $typeId;
        $monsterType = $Dao->where($condition)->find();
        return $monsterType['score'];
    }
}
