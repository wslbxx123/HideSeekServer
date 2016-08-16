<?php
namespace Home\BusinessLogic\Manager;
use Home\DataAccess\PullVersionManager;
use Home\DataAccess\AccountManager;
use Home\DataAccess\RecordManager;
use Home\DataAccess\GoalManager;
use Home\Common\Util\BaseUtil;
use Home\Common\Param\CodeParam;
/**
 * 处理地图控制器的逻辑类
 *
 * @author Two
 */
class MapControllerManager {
    public function checkUserInAccountArray($accountArray, $accountId, $goalId) {
        $flag = true;
        if(count($accountArray) > 0 && !isset($accountArray[0]['result'])) {
            $flag = false;
            $version = PullVersionManager::updateRaceGroupVersion();
            foreach ($accountArray as $accountResult){ 
                if($accountResult['account_id'] == $accountId) {
                    $scoreSum = RecordManager::insertRecord($goalId, 2, 
                            $accountId, $version);         
                    AccountManager::updateScoreSum($accountId, $scoreSum);
                    $flag = true;
                }
            }
        }
        
        return Array("flag" => $flag, "score_sum" => $scoreSum);
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
        
        $goal = GoalManager::getGoal($goalId);
        if($goal['valid'] == 0) {
            BaseUtil::echoJson(CodeParam::GOAL_DISAPPEAR, null);
            return false;
        }
        
        return true;
    }
}
