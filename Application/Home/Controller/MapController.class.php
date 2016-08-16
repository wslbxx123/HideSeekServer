<?php
namespace Home\Controller;
use Home\Common\Util\BaseUtil;
use Home\Common\Param\CodeParam;
use Home\DataAccess\GoalManager;
use Home\DataAccess\PullVersionManager;
use Home\DataAccess\RecordManager;
use Home\DataAccess\AccountManager;
use Home\DataAccess\MonsterTempHitManager;
use Home\BusinessLogic\Manager\MapControllerManager;

class MapController extends BaseController {
    public function refresh(){
        self::setHeader();
        
        $latitude = filter_input(INPUT_POST, 'latitude');
        $longitude = filter_input(INPUT_POST, 'longitude');
        $accountRole = filter_input(INPUT_POST, 'account_role');
        $updateTime = filter_input(INPUT_POST, 'update_time');
        
        if(!isset($latitude) || !isset($longitude)) {
            BaseUtil::echoJson(CodeParam::LATITUDE_OR_LONGITUDE_EMPTY, null);
            return;
        }
        
        if(!isset($accountRole)) {
            $accountRole = 0;
        }

        if(!isset($updateTime)) {
            $updateTime = "null";
        }
        
        settype($latitude, "double");
        settype($longitude, "double");
        
        $result = GoalManager::getGoalInfo($latitude, $longitude, 
                $accountRole, $updateTime);
        BaseUtil::echoJson(CodeParam::SUCCESS, $result);
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
        $scoreSum = RecordManager::insertRecord($goalId, $goalType, 
                $account['pk_id'], $version);
        AccountManager::updateScoreSum($account['pk_id'], $scoreSum);
        
        BaseUtil::echoJson(CodeParam::SUCCESS, $scoreSum);
    }
    
    public function hitMonster() {
        self::setHeader();
        
        $sessionId = filter_input(INPUT_POST, 'session_id');
        $goalId = filter_input(INPUT_POST, 'goal_id');
        $accountRole = filter_input(INPUT_POST, 'account_role');
        $account = $this->getAccountFromToken($sessionId);
        
        if(!MapControllerManager::checkHitMonsterInfo($sessionId, $account['pk_id'], 
                $goalId, $accountRole)) {
            return;
        }    

        $accountArray = MonsterTempHitManager::insertMonsterTempHit($goalId, 
                $account['pk_id'], $accountRole);
        
        $result = MapControllerManager::checkUserInAccountArray($accountArray, 
                $account['pk_id'], $goalId);
                
        if(!$result['flag']) {
            BaseUtil::echoJson(CodeParam::GOAL_DISAPPEAR, $accountArray);
            return;
        }
        
        BaseUtil::echoJson(CodeParam::SUCCESS, $result['score_sum']);
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


