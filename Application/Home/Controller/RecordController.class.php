<?php
namespace Home\Controller;
use Think\Page;

class RecordController extends BaseController {
    public function refreshRecords(){
        $code = "10000";
        $message = "获得战绩列表成功！";
        $sessionId = $_POST['session_id'];
        $version = $_POST['version'];
        $recordMinId = $_POST['record_min_id'];
        $accountId = $this->getPkIdFromToken($sessionId);
        
        $versionDao = M("pull_version");
        $pullVersion = $versionDao->find();
        
        if(isset($sessionId) && $sessionId != "") {
            if(isset($version) && isset($recordMinId)) {
                $Dao = M("record");
                $condition['account_id'] = $accountId;
                $scoreSum = $Dao->where($condition)->sum('SCORE') | 0;
                
                $sql = "call admin_refresh_record($accountId, $version, "
                        . "$recordMinId)";
                $scoreList = $Dao->query($sql);
            } else {
                $code = "10008";
                $message = "版本号或者记录最小ID值为空";
            }
        } else {
            $code = "10010";
            $message = "用户未登录";
        }
        
        if($scoreList != null && count($scoreList) > 0) {
            $recordMinId = end($scoreList)['pk_id'];
        }
        
        $array = array (
            'code' => $code, 
            'message' => $message, 
            'pk_id' => $accountId,
            'result' => array (
                'version' => $pullVersion['race_group_version'],
                'record_min_id' => $recordMinId,
                'score_sum' => $scoreSum,
                'scores' => $scoreList));
        echo json_encode($array);
    }
    
    public function getRecords() {
        $code = "10000";
        $message = "获得部落圈成功";
        $sessionId = $_POST['session_id'];
        $version = $_POST['version'];
        $recordMinId = $_POST['record_min_id'];
        $accountId = $this->getPkIdFromToken($sessionId);
        
        if(isset($version) && isset($recordMinId)) {
            $Dao = M("record");
            $sql = "call admin_get_record($accountId, $version, $recordMinId)";
            $scoreList = $Dao->query($sql);
        } else {
            $code = "10008";
            $message = "版本号或者记录最小ID值为空";
        } 
        
        if($scoreList != null && count($scoreList) > 0) {
            $tempRecordMinId = end($scoreList)['pk_id'];
            
            if($tempRecordMinId < $recordMinId) {
                $recordMinId = $tempRecordMinId;
            }
        }
        
        $array = array ('code' => $code, 'message' => $message, 
            'result' => array (
                'account_id' => $sessionId,
                'record_min_id' => $recordMinId,
                'scores' => $scoreList));
        
        echo json_encode($array);
    }
}

