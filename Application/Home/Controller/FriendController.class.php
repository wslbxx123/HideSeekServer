<?php
namespace Home\Controller;
use Home\Common\Util\BaseUtil;
use Home\DataAccess\FriendManager;
use Home\DataAccess\AccountManager;
use Home\DataAccess\PullVersionManager;
use Home\Common\Param\CodeParam;
use Home\BusinessLogic\Manager\FriendControllerManager;

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
    
    public function searchFriends() {
        self::setHeader();
        
        $sessionId = filter_input(INPUT_POST, 'session_id');
        $searchWord = filter_input(INPUT_POST, 'search_word');
        $accountId = $this->getPkIdFromToken($sessionId);
        
        if(!isset($sessionId) || $accountId == 0) {
            BaseUtil::echoJson(CodeParam::NOT_LOGIN, null);
            return;
        }
        
        if(!isset($searchWord)) {
            BaseUtil::echoJson(CodeParam::SEARCH_WORD_EMPTY, null);
            return;
        }
        
        $accountList = AccountManager::searchAccounts($accountId, $searchWord);
        
        BaseUtil::echoJson(CodeParam::SUCCESS, $accountList);
    }
    
    public function addFriend() {
        self::setHeader();
        
        $sessionId = filter_input(INPUT_POST, 'session_id');
        $friendId = filter_input(INPUT_POST, 'friend_id');
        $message = filter_input(INPUT_POST, 'message');
        $account = $this->getAccountFromToken($sessionId);
        
        if(!FriendControllerManager::checkAddFriendInfo($sessionId, 
                $account['pk_id'], $friendId, $message)) {
            return;
        }
        
        $friend = AccountManager::getAccount($friendId);
        
        if(!FriendControllerManager::sendFriendRequest($account, $friend, 
                $message)) {
            return;
        }
        $friend['password'] = "";
        BaseUtil::echoJson(CodeParam::SUCCESS, $friend);
    }
}

