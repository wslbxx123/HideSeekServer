<?php
namespace Home\BusinessLogic\Manager;
use Home\Common\Param\CodeParam;
use Home\Common\Util\BaseUtil;

/**
 * 处理用户控制器的逻辑类
 *
 * @author Two
 */
class UserControllerManager {
    public function checkUserInfo($phone, $password, $channelId) {
        if(!isset($phone) || !isset($password)) {
            BaseUtil::echoJson(CodeParam::PHONE_OR_PASSWORD_EMPTY, null);
            return false;
        }
        
        if(!isset($channelId)) {
            BaseUtil::echoJson(CodeParam::CHANNEL_ID_EMPTY, null);
            return false;
        }
        
        return true;
    }
}
