<?php
namespace Home\Controller;
use Home\Common\Util\BaseUtil;
use Home\DataAccess\FriendManager;
use Home\DataAccess\PullVersionManager;
use Home\Common\Param\CodeParam;

class FriendController extends BaseController {
    public function getFriends(){
        self::setHeader();
        
        $sessionId = filter_input(INPUT_POST, 'session_id');
        $version = filter_input(INPUT_POST, 'version');
        $accountId = $this->getPkIdFromToken($sessionId);
        
        if(!isset($sessionId) || $accountId == 0) {
            BaseUtil::echoJson(CodeParam::NOT_LOGIN, null);
            return;
        }
        
        if(!isset($version)) {
            BaseUtil::echoJson(CodeParam::VERSION_EMPTY, null);
            return;
        }
        
        $friends = FriendManager::getFriends($accountId, $version);
        $newVersion = PullVersionManager::getFriendVersion();
        
        $result = array ('version' => $newVersion, 'friends' => $friends);
        
        BaseUtil::echoJson(CodeParam::SUCCESS, $result);
    }
    
    public function refreshRaceGroup() {
        self::setHeader();
        
        $sessionId = filter_input(INPUT_POST, 'session_id');
        $version = filter_input(INPUT_POST, 'version');
        $recordMinId = filter_input(INPUT_POST, 'record_min_id');
        $accountId = $this->getPkIdFromToken($sessionId);
        
        if(!isset($sessionId) || $accountId == 0) {
            BaseUtil::echoJson(CodeParam::NOT_LOGIN, null);
            return;
        }
        
        if(!isset($version) || !isset($recordMinId)) {
            BaseUtil::echoJson(CodeParam::VERSION_OR_MIN_ID_EMPTY, null);
            return;
        }
        
        $raceGroupVersion = PullVersionManager::getRaceGroupVersion();
        $raceGroupResult = FriendManager::refreshRaceGroup($accountId, $version,
                $recordMinId); 
        
        $result = array ('version' => $raceGroupVersion,
                'record_min_id' => $raceGroupResult["record_min_id"],
                'race_group' => $raceGroupResult["race_group"]);
        BaseUtil::echoJson(CodeParam::SUCCESS, $result);
    }
    
    public function getRaceGroup() {
        self::setHeader();
        
        $sessionId = filter_input(INPUT_POST, 'session_id');
        $version = filter_input(INPUT_POST, 'version');
        $recordMinId = filter_input(INPUT_POST, 'record_min_id');
        $accountId = $this->getPkIdFromToken($sessionId);
        
        if(!isset($sessionId) || $accountId == 0) {
            BaseUtil::echoJson(CodeParam::NOT_LOGIN, null);
            return;
        }
        
        if(!isset($version) || !isset($recordMinId)) {
            BaseUtil::echoJson(CodeParam::VERSION_OR_MIN_ID_EMPTY, null);
            return;
        }
        
        $raceGroupResult = FriendManager::getRaceGroup($accountId, $version, 
                $recordMinId);

        BaseUtil::echoJson(CodeParam::SUCCESS, $raceGroupResult);
    }
}

