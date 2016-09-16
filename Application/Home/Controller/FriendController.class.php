<?php
namespace Home\Controller;
use Home\Common\Util\BaseUtil;
use Home\DataAccess\FriendManager;
use Home\DataAccess\RecordManager;
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
        $account = $this->getAccountFromToken($sessionId);
        
        if(!isset($sessionId) || $account['pk_id'] == 0) {
            BaseUtil::echoJson(CodeParam::NOT_LOGIN, null);
            return;
        }
        
        if(!isset($searchWord)) {
            BaseUtil::echoJson(CodeParam::SEARCH_WORD_EMPTY, null);
            return;
        }
        
        if($searchWord == $account['phone'] || $searchWord == $account['nickname']) {
            BaseUtil::echoJson(CodeParam::SEARCH_MYSELF, null);
            return;
        }
        
        $accountList = AccountManager::searchAccounts($account['pk_id'], $searchWord);
        
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
    
    public function acceptFriend() {
        self::setHeader();
        
        $sessionId = filter_input(INPUT_POST, 'session_id');
        $friendId = filter_input(INPUT_POST, 'friend_id');
        $account = $this->getAccountFromToken($sessionId);
        
        if(!FriendControllerManager::checkAcceptFriendInfo($sessionId, 
                $account['pk_id'], $friendId)) {
            return;
        }
        
        $friend = AccountManager::getAccount($friendId);
        $version = PullVersionManager::updateFriendVersion();
        FriendManager::insertFriend($account['pk_id'], $friendId, $version);
        FriendManager::insertFriend($friendId, $account['pk_id'], $version);
        $friendNum = FriendManager::getFriendSum($account['pk_id']);
        AccountManager::updateFriendNum($account['pk_id'], $friendNum);
        
        if(!FriendControllerManager::acceptFriendRequest($account, $friend)) {
            return;
        }
        
        BaseUtil::echoJson(CodeParam::SUCCESS, $friendNum);
    }
    
    public function updateRemark() {
        self::setHeader();
        
        $sessionId = filter_input(INPUT_POST, 'session_id');
        $friendId = filter_input(INPUT_POST, 'friend_id');
        $remark = filter_input(INPUT_POST, 'remark');
        $accountId = $this->getPkIdFromToken($sessionId);
        
        if(!isset($sessionId) || $accountId == 0) {
            BaseUtil::echoJson(CodeParam::NOT_LOGIN, null);
            return false;
        }
        
        if(!isset($remark)) {
            BaseUtil::echoJson(CodeParam::FRIEND_REMARK_EMPTY, null);
            return false;
        }
        
        $version = PullVersionManager::updateFriendVersion();
        FriendManager::updateRemark($accountId, $friendId, $remark, $version);
        $raceGroupVersion = PullVersionManager::updateRaceGroupVersion();
        RecordManager::updateVersion($friendId, $raceGroupVersion);
        
        BaseUtil::echoJson(CodeParam::SUCCESS, $remark);
    }
    
    public function removeFriend() {
        self::setHeader();
        
        $sessionId = filter_input(INPUT_POST, 'session_id');
        $friendId = filter_input(INPUT_POST, 'friend_id');
        $accountId = $this->getPkIdFromToken($sessionId);
        
        if(!isset($sessionId) || $accountId == 0) {
            BaseUtil::echoJson(CodeParam::NOT_LOGIN, null);
            return false;
        }
        
        if(!isset($friendId)) {
            BaseUtil::echoJson(CodeParam::FRIEND_ID_EMPTY, null);
            return false;
        }
        
        FriendManager::deleteFriend($accountId, $friendId);
        
        BaseUtil::echoJson(CodeParam::SUCCESS, null);
    }
}

