<?php
namespace Home\Controller;

class FriendController extends BaseController {
    public function getFriend(){
        $code = "10000";
        $message = "获得朋友成功";
        $sessionId = $_POST['session_id'];
        $account_id = $this->getPkIdFromToken($sessionId);
        
        if(isset($sessionId) && $sessionId != "") {
            $Dao = M("friend");
            $sql = "call admin_get_friend($account_id)";
            $friends = $Dao->query($sql);
        } else {
            $code = "10010";
            $message = "用户未登录";
        }
        
        if($friends != null && count($friends) > 0) {
            $version = end($friends)['version'];
        }
        
        $array = array ('code' => $code, 'message' => $message,
            'result' => array (
                'version' => $version,
                'friends' => $friends));
        
        echo json_encode($array);
    }
    
    public function refreshRaceGroup() {
        $code = "10000";
        $message = "获得部落圈成功";
        $sessionId = $_POST['session_id'];
        $version = $_POST['version'];
        $recordMinId = $_POST['record_min_id'];
        $accountId = $this->getPkIdFromToken($sessionId);
        
        $versionDao = M("pull_version");
        $pullVersion = $versionDao->find();
        
        if(isset($sessionId) && $sessionId != "") {
            if(isset($version) && isset($recordMinId)) {
                $Dao = M("friend");
                $sql = "call admin_refresh_friend_record($accountId, $version, "
                        . "$recordMinId)";
                $raceGroupArray = $Dao->query($sql);
            } else {
                $code = "10008";
                $message = "版本号或者记录最小ID值为空";
            }
        } else {
            $code = "10010";
            $message = "用户未登录";
        }  
        
        if($raceGroupArray != null && count($raceGroupArray) > 0) {
            $recordMinId = end($raceGroupArray)['pk_id'];
        }
        
        $array = array ('code' => $code, 'message' => $message, 
            'result' => array (
                'version' => $pullVersion['race_group_version'],
                'record_min_id' => $recordMinId,
                'race_group' => $raceGroupArray));
        
        echo json_encode($array);
    }
    
    public function getRaceGroup() {
        $code = "10000";
        $message = "获得部落圈成功";
        $sessionId = $_POST['session_id'];
        $version = $_POST['version'];
        $recordMinId = $_POST['record_min_id'];
        $accountId = $this->getPkIdFromToken($sessionId);
        
        if(isset($version) && isset($recordMinId)) {
            $Dao = M("friend");
            $sql = "call admin_get_friend_record($accountId, $version, $recordMinId)";
            $raceGroupArray = $Dao->query($sql);
        } else {
            $code = "10008";
            $message = "版本号或者记录最小ID值为空";
        } 
        
        if($raceGroupArray != null && count($raceGroupArray) > 0) {
            $tempRecordMinId = end($raceGroupArray)['pk_id'];
            
            if($tempRecordMinId < $recordMinId) {
                $recordMinId = $tempRecordMinId;
            }
        }
        
        $array = array ('code' => $code, 'message' => $message, 
            'result' => array (
                'record_min_id' => $recordMinId,
                'race_group' => $raceGroupArray));
        
        echo json_encode($array);
    }
}

