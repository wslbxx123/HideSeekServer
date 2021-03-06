<?php
namespace Home\Controller;
use Home\Common\Util\BaseUtil;
use Home\Common\Param\CodeParam;
use Home\DataAccess\PullVersionManager;
use Home\DataAccess\RecordManager;
use Home\BusinessLogic\Manager\RecordControllerManager;

class RecordController extends BaseController {
    public function refreshRecords(){
        self::setHeader();
        
        $sessionId = filter_input(INPUT_POST, 'session_id');
        $version = filter_input(INPUT_POST, 'version');
        $recordMinId = filter_input(INPUT_POST, 'record_min_id');
        $account = $this->getAccountFromToken($sessionId);
        
        if(!RecordControllerManager::checkRefreshRecordInfo($sessionId, $account['pk_id'], 
                $version, $recordMinId)) {
            return;
        }

        $raceGroupVersion = PullVersionManager::getRaceGroupVersion();
        $recordResult = RecordManager::refreshRecords($account['pk_id'], $version, 
                $recordMinId);
        
        $result = array ('version' => $raceGroupVersion,
                'record_min_id' => $recordResult['record_min_id'],
                'score_sum' => $account['record'],
                'scores' => $recordResult['scores']);
        
        BaseUtil::echoJson(CodeParam::SUCCESS, $result); 
    }
    
    public function getRecords() {
        self::setHeader();
        
        $sessionId = filter_input(INPUT_POST, 'session_id');
        $version = filter_input(INPUT_POST, 'version');
        $recordMinId = filter_input(INPUT_POST, 'record_min_id');
        $appVersion = filter_input(INPUT_POST, 'app_version');
        $accountId = $this->getPkIdFromToken($sessionId, $appVersion);
        
        if(!RecordControllerManager::checkRefreshRecordInfo($sessionId, $accountId, 
                $version, $recordMinId)) {
            return;
        }
        
        $result = RecordManager::getRecords($accountId, $version, $recordMinId);
        
        BaseUtil::echoJson(CodeParam::SUCCESS, $result); 
    }
}

