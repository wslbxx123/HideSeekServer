<?php

namespace Home\BusinessLogic\Manager;
use Home\Common\Util\BaseUtil;
use Home\Common\Param\CodeParam;
use Home\BusinessLogic\Network\TencentIMManager;
use Home\DataAccess\FriendRequestManager;
use Home\DataAccess\FriendManager;
use Home\DataAccess\AccountManager;
/**
 * 处理朋友控制器的逻辑类
 *
 * @author Two
 */
class FriendControllerManager {
    public function checkAddFriendInfo($sessionId, $accountId, $friendId, $message) {
        if(!isset($sessionId) || $accountId == 0) {
            BaseUtil::echoJson(CodeParam::NOT_LOGIN, null);
            return false;
        }
        
        if(!isset($friendId)) {
            BaseUtil::echoJson(CodeParam::FRIEND_ID_EMPTY, null);
            return false;
        }
        
        if(!isset($message)) {
            BaseUtil::echoJson(CodeParam::REQUEST_MESSAGE_EMPTY, null);
            return false;
        }
        
        return true;
    }
    
    public function sendFriendRequest($account, $friend, $message) {
        if($friend['channel_id'] == NULL) {
            FriendRequestManager::insertFriendRequest($account['pk_id'], 
                    $friend['pk_id'], $message, 0);
        } else {
            $account = BaseUtil::removeSecretInfo($account);
            if(!TencentIMManager::pushSingleAccountIOS($friend['phone'], 
                    "FRIEND_REQUEST_MESSAGE", [], 
                    $account, $message, 1)) {
                BaseUtil::echoJson(CodeParam::FAIL_SEND_MESSAGE, null);
                return false;
            }
        }
        
        return true;
    }
    
    public function acceptFriendRequest($account, $friend) {
        $friendNum = FriendManager::getFriendSum($friend['pk_id']);
        AccountManager::updateFriendNum($friend['pk_id'], $friendNum);
        
        if($friend['channel_id'] == NULL) {
            FriendRequestManager::insertFriendRequest($account['pk_id'], 
                    $friend['pk_id'], null, 1);
        } else {
            $account = BaseUtil::removeSecretInfo($account);
            if(!TencentIMManager::pushSingleAccountIOS($friend['phone'], 
                    "FRIEND_ACCEPT_MESSAGE", [$friend['nickname']],
                    $account, $friendNum, 2)) {
                BaseUtil::echoJson(CodeParam::FAIL_SEND_MESSAGE, null);
                return false;
            }
        }
        
        return true;
    }
    
    public function checkAcceptFriendInfo($sessionId, $accountId, $friendId) {
        if(!isset($sessionId) || $accountId == 0) {
            BaseUtil::echoJson(CodeParam::NOT_LOGIN, null);
            return false;
        }
        
        if(!isset($friendId)) {
            BaseUtil::echoJson(CodeParam::FRIEND_ID_EMPTY, null);
            return false;
        }
        
        return true;
    }
}
