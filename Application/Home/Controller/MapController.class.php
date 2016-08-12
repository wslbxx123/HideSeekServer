<?php
namespace Home\Controller;
use Home\Common\Util\BaseUtil;
use Home\Common\Param\CodeParam;
use Home\DataAccess\GoalManager;
use Home\DataAccess\PullVersionManager;
use Home\DataAccess\RecordManager;
use Home\DataAccess\AccountManager;

class MapController extends BaseController {
    public function refresh(){
        $code = "10000";
        $message = "地图刷新成功";
        
        if(isset($_POST['latitude']) && isset($_POST['longitude'])) {
            $goalDao = M("goal");
            $latitude = $_POST['latitude'];
            $longitude = $_POST['longitude'];
            $account_role = $_POST['account_role'];
            $update_timestamp = $_POST['update_time'];
            
            if(!isset($account_role)) {
                $account_role = 0;
            }
            
            if(!isset($update_timestamp)) {
                $update_timestamp = "null";
            }
            
            settype($latitude, "double");
            settype($longitude, "double");
            $sql = "call admin_monster_role_p($latitude, $longitude, "
                    . "$account_role, \"$update_timestamp\")";
            $goals = $goalDao->query($sql);
        } else{
            $code = "10005";
            $message = "经纬度不能为空";
        }
        
        $update_timestamp = $goalDao->max('update_time');
        
        $array = array ('code' => $code, 'message' => $message, 
            'result' => array (
                'update_time' => $update_timestamp,
                'goals' => $goals));
        echo json_encode($array);
    }
    
    public function getGoal() {
        self::setHeader();
        
        $sessionId = filter_input(INPUT_POST, 'session_id');
        $goalId = filter_input(INPUT_POST, 'goal_id');
        $goalType = filter_input(INPUT_POST, 'goal_type');
        $account = $this->getAccountFromToken($sessionId);
        
        if(!isset($goalId) || !isset($goalType)) {
            BaseUtil::echoJson(CodeParam::GOAL_ID_OR_TYPE_EMPTY, null);
            return;
        }
       
        GoalManager::updateGoal(0, $goalId);
        $version = PullVersionManager::updateRaceGroupVersion();
        $scoreSum = RecordManager::insertRecord($goalId, $goalType, $account['pk_id'], $version);
        AccountManager::updateScoreSum($account['pk_id'], $scoreSum);
        
        BaseUtil::echoJson(CodeParam::SUCCESS, $record);
    }
    
    public function hitMonster() {
        $code = "10000";
        $message = "打怪兽成功";
        
        $sessionId = $_POST['session_id'];
        $account = $this->getAccountFromToken($sessionId);
        $goal_id = $_POST['goal_id'];
        $account_role = $_POST['account_role'];
        if(isset($goal_id) && isset($account_role)) {
            $Dao = M("monster_temp_hit");
            $data['goal_id'] = $goal_id;
            $data['account_id'] = $account['pk_id'];
            $data['account_role'] = $account_role;
            $data['hit_time'] = date('y-m-d H:i:s',time());
            $Dao->add($data);
            
            $sql = "call admin_get_temp_hit($goal_id)";
            $accountArray = $Dao->query($sql);
            
            $versionDao = M("pull_version");
            $version = $versionDao->find();
            $version['race_group_version'] = $version['race_group_version'] + 1;
            
            $flag = false;
            foreach ($accountArray as $accountResult){ 
                if($accountResult['account_id'] == $account['pk_id']) {
                    $recordDao = M("record");
                    $record['goal_id'] = $goal_id;
                    $record['goal_type'] = 2;
                    $record['account_id'] = $account['pk_id'];
                    $record['score'] = 1;
                    $record['time'] = date('y-m-d H:i:s',time());
                    $condition['account_id'] = $account['pk_id'];
                    $record['score_sum'] = $recordDao->where($condition)->sum('score')
                            + $record['score'];
                    $record['version'] = $version['race_group_version'];
                    $recordDao->add($record);
                    $flag = true;
                    
                    $accountDao = M("account");
                    $account['record'] = $record['score_sum'];
                    $accountDao->where($condition)->save($account);
                }
            }
            
            $versionDao->where('1=1')->save($version);
                
            if(!$flag) {
                $code = "10007";
                $message = "目标已经消失";
            }
        } else{
            $code = "10009";
            $message = "目标ID或用户角色为空";
        }
        
        $array = array ('code' => $code, 'message' => $message,
            'result' => $account_array);
        echo json_encode($array);
    }
    
    public function setBomb() {
        $code = "10000";
        $message = "设置炸弹成功";
        $sessionId = $_POST['session_id'];
        $account = $this->getAccountFromToken($sessionId);
        
        if(isset($sessionId) && $sessionId != "") {
            $pkId = $account['pk_id'];
            if(isset($_POST['latitude']) && isset($_POST['longitude'])) {
                $latitude = $_POST['latitude'];
                $longitude = $_POST['longitude'];
                if(isset($_POST['orientation'])) {
                    $orientation = $_POST['orientation'];
                    $dao = M("goal");
                    $data['latitude'] = $latitude;
                    $data['longitude'] = $longitude;
                    $data['orientation'] = $orientation;
                    $data['create_by'] = $pkId;
                    $data['update_time'] = date('y-m-d H:i:s',time());
                    $data['valid'] = 1;
                    $data['type'] = 3;
                    $dao->add($data);
                    
                    $tempAccount['bomb_num'] = $account['bomb_num'] - 1;
                    $accountDao = M("account");
                    $accountDao->where("pk_id=$pkId")->save($tempAccount);
                } else {
                    $code = "10011";
                    $message = "方向值不能为空";
                }
            } else{
                $code = "10005";
                $message = "经纬度不能为空";
            }
        } else {
            $code = "10010";
            $message = "用户未登录";
        }
        
        $array = array ('code' => $code, 'message' => $message,
            'result' => $tempAccount['bomb_num']);
        echo json_encode($array);
    }
}


