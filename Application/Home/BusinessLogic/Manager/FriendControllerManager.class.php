<?php

namespace Home\BusinessLogic\Manager;
use Home\Common\Util\BaseUtil;
use Home\Common\Param\CodeParam;
use Home\BusinessLogic\Network\TencentIMManager;
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
            $account['password'] = "";
            if(TencentIMManager::pushSingleDeviceIOS($friend['channel_id'], 
                    "你收到一个好友请求",
                    $account, $message, 1) != 0) {
                return false;
            }
        }
        
        return true;
    }
    
    public function acceptFriendRequest($account, $friend) {
        return BaiduIMManager::sendFriendRequest($friend['channel_id'], 
                    $friend['nickname']."接受你的好友请求",
                    $account, null, 2);
    }
}
