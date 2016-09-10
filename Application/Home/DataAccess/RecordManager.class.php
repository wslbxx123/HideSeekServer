<?php
namespace Home\DataAccess;
/**
 * 操作战绩表(admin_record)
 *
 * @author Two
 */
class RecordManager {
    public function refreshRecords($accountId, $version, $recordMinId) {
        $Dao = M("record");
        $sql = "call admin_refresh_record($accountId, $version, $recordMinId)";
        $scoreList = $Dao->query($sql);
        
        if($scoreList != null && count($scoreList) > 0) {
            $tempRecordMinId = end($scoreList)['pk_id'];
            
            if($recordMinId == 0 || $tempRecordMinId < $recordMinId) {
                $recordMinId = $tempRecordMinId;
            }
        }
        
        return Array(
            "record_min_id" => $recordMinId,
            "scores" => $scoreList
        );
    }
    
    public function getRecords($accountId, $version, $recordMinId) {
        $Dao = M("record");
        $sql = "call admin_get_record($accountId, $version, $recordMinId)";
        $scoreList = $Dao->query($sql);
        
        if($scoreList != null && count($scoreList) > 0) {
            $tempRecordMinId = end($scoreList)['pk_id'];
            
            if($recordMinId == 0 || $tempRecordMinId < $recordMinId) {
                $recordMinId = $tempRecordMinId;
            }
        }
        
        return Array(
            'record_min_id' => $recordMinId,
            'scores' => $scoreList);
    }
    
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
    
    public function insertRewardRecord($account, $score, $version) {
        $Dao = M("record");
        $record['goal_id'] = 0;
        $record['goal_type'] = 0;
        $record['account_id'] = $account['pk_id'];
        $record['score'] = $score;  
        $record['time'] = date('y-m-d H:i:s',time());
        $record['score_sum'] = $account['record'] + $score;
        $record['version'] = $version;
        $Dao->add($record);
        return $record['score_sum'];
    }
}
