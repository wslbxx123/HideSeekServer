<?php

namespace Home\BusinessLogic\Manager;
use Home\Common\Util\BaseUtil;
use Home\Common\Param\CodeParam;
use Home\BusinessLogic\Network\BaiduIMManager;
use Home\DataAccess\FriendRequestManager;
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
                    $friend['pk_id'], $message);
        } else {
            if(!BaiduIMManager::sendFriendRequest($friend['channel_id'], 
                    $account, $message)) {
                return false;
            }
        }
        
        return true;
    }
}
