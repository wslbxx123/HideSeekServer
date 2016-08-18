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
        $version = filter_input(INPUT_POST, 'version');
        
        if(!MapControllerManager::checkRefreshMapInfo($latitude, $longitude, 
                $version)) {
            return;
        }
        
        if(!isset($accountRole)) {
            $accountRole = 0;
        }
        
        settype($latitude, "double");
        settype($longitude, "double");
        
        $goals = GoalManager::getGoalInfo($latitude, $longitude, $accountRole, 
                $version);
        $newVersion = PullVersionManager::getGoalVersion();
        $result = array ('version' => $newVersion, 'goals' => $goals);
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
       
        $goalVersion = PullVersionManager::updateGoalVersion();
        GoalManager::updateGoal(0, $goalId, $goalVersion);
        $raceGroupversion = PullVersionManager::updateRaceGroupVersion();
        $scoreSum = RecordManager::insertRecord($goalId, $goalType, 
                $account['pk_id'], $raceGroupversion);
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
            BaseUtil::echoJson(CodeParam::GOAL_DISAPPEAR, $result);
            return;
        }
        
        BaseUtil::echoJson(CodeParam::SUCCESS, $result);
    }
    
    public function setBomb() {
        self::setHeader();
        
        $sessionId = filter_input(INPUT_POST, 'session_id');
        $latitude = filter_input(INPUT_POST, 'latitude');
        $longitude = filter_input(INPUT_POST, 'longitude');
        $orientation = filter_input(INPUT_POST, 'orientation');
        $account = $this->getAccountFromToken($sessionId);
        
        if(!isset($sessionId) || $account['pk_id'] == 0) {
            BaseUtil::echoJson(CodeParam::NOT_LOGIN, null);
            return false;
        }
        
        if(!isset($orientation)) {
            BaseUtil::echoJson(CodeParam::ORIENTATION_EMPTY, null);
            return false;
        }
        
        $version = PullVersionManager::updateGoalVersion();
        GoalManager::insertGoal($latitude, $longitude, $orientation, 
                $account['pk_id'], $version);
        AccountManager::updateBombNum($account['pk_id'], 
                $account['bomb_num'] - 1);
        
        BaseUtil::echoJson(CodeParam::SUCCESS, $account['bomb_num'] - 1);
    }
}


