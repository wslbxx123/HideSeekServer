<?php
namespace Home\DataAccess;
/**
 * 操作战绩表(admin_record)
 *
 * @author Two
 */
class RecordManager {
    public function insertRecord($goalId, $goalType, $score, $accountId, $version) {
        $Dao = M("record");
        $record['goal_id'] = $goalId;
        $record['goal_type'] = $goalType;
        $record['account_id'] = $accountId;
        $record['score'] = $score;  
        $record['time'] = date('y-m-d H:i:s',time());
        
        $condition['account_id'] = $accountId;
        $record['score_sum'] = $Dao->where($condition)->sum('score')
                + $score;
        $record['version'] = $version;
        $Dao->add($record);
        return $record['score_sum'];
    }
}
