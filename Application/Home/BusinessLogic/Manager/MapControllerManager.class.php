<?php
namespace Home\BusinessLogic\Manager;
use Home\DataAccess\PullVersionManager;
use Home\DataAccess\AccountManager;
use Home\DataAccess\RecordManager;
use Home\DataAccess\GoalManager;
use Home\DataAccess\MonsterTempHitManager;
use Home\DataAccess\MonsterTypeManager;
use Home\Common\Util\BaseUtil;
use Home\Common\Param\CodeParam;
/**
 * 处理地图控制器的逻辑类
 *
 * @author Two
 */
class MapControllerManager {
    public function checkUserInAccountArray($accountArray, $accountId, $goalId) {
        $goal = GoalManager::getGoal($goalId);
        $flag = true;
        if(count($accountArray) > 0 && !isset($accountArray[0]['result'])) {
            $flag = false;
            $version = PullVersionManager::updateRaceGroupVersion();
            foreach ($accountArray as $accountResult){ 
                if($accountResult['account_id'] == $accountId) {
                    $score = MonsterTypeManager::getScore($goal['monster_type']);
                    $scoreSum = RecordManager::insertRecord($goalId, 2, $score,
                            $accountId, $version);         
                    AccountManager::updateRecord($accountId, $scoreSum);
                    $flag = true;
                }
            }
        }
        
        $count = MonsterTempHitManager::getCount($accountId, $goalId);
        $ifCanSuccess = $count > 5 ? 0 : 1;
        return Array("flag" => $flag, "score_sum" => $scoreSum, 
            "if_can_success" => $ifCanSuccess);
    }
    
    public function checkRefreshMapInfo($latitude, $longitude, $version) {
        if(!isset($latitude) || !isset($longitude)) {
            BaseUtil::echoJson(CodeParam::LATITUDE_OR_LONGITUDE_EMPTY, null);
            return false;
        }
        
        if(!isset($version)) {
            BaseUtil::echoJson(CodeParam::VERSION_EMPTY, null);
            return false;
        }
        
        return true;
    }
    
    public function checkHitMonsterInfo($sessionId, $accountId, $goalId, $accountRole) {
        if(!isset($sessionId) || $accountId == 0) {
            BaseUtil::echoJson(CodeParam::NOT_LOGIN, null);
            return false;
        }
        
        if(!isset($goalId) || !isset($accountRole)) {
            BaseUtil::echoJson(CodeParam::GOAL_ID_OR_ROLE_EMPTY, null);
            return false;
        }
        
        return true;
    }
    
    public function checkGetGoalInfo($sessionId, $accountId, $goalId, $goalType) {
        if(!isset($sessionId) || $accountId == 0) {
            BaseUtil::echoJson(CodeParam::NOT_LOGIN, null);
            return false;
        }
        
        if(!isset($goalId) || !isset($goalType)) {
            BaseUtil::echoJson(CodeParam::GOAL_ID_OR_TYPE_EMPTY, null);
            return false;
        }
        
        return true;
    }
}
