<?php
namespace Home\BusinessLogic\Manager;
use Home\Common\Param\CodeParam;
use Home\Common\Util\BaseUtil;
use Home\DataAccess\PullVersionManager;
use Home\Common\Util\FileUtil;
use Home\DataAccess\AccountManager;

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
    
    public function setRegisterUserInfo($phone, $password, $nickname, $role, 
            $sex, $region, $channelId, $photo, $photoDataUrl) {
        if(!self::checkUserBaseInfo($phone, $password, $nickname, $channelId)) {
            return null;
        }
        
        $tempFileName = "Upload_".session_id()."_".strtotime("now");
        $photoUrl = FileUtil::saveRealPhoto($photo, $photoDataUrl, $tempFileName);
        
        if(isset($photo) && !isset($photoUrl)) {
            BaseUtil::echoJson(CodeParam::FAIL_UPLOAD_PHOTO, null);
            return null;
        }
        
        $smallPhotoUrl = FileUtil::saveSmallPhoto($photoUrl, 
                $tempFileName, 200, 200);
        $accountId = AccountManager::insertAccount($phone, $password, $nickname, 
                PullVersionManager::getFriendVersion(), $role, $sex, $region, 
                $photoUrl, $smallPhotoUrl);
        
        return $accountId;
    }
    
    public function checkUserBaseInfo($phone, $password, $nickname, $channelId) {
        if(!isset($phone) || !isset($password)) {
            BaseUtil::echoJson(CodeParam::PHONE_OR_PASSWORD_EMPTY, null);
            return false;
        }
        
        if(!isset($nickname)) {
            BaseUtil::echoJson(CodeParam::NICKNAME_EMPTY, null);
            return false;
        }
        
        if(!isset($channelId)) {
            BaseUtil::echoJson(CodeParam::CHANNEL_ID_EMPTY, null);
            return false;
        }
        
        if(AccountManager::getAccountFromPhone($phone)) {
            BaseUtil::echoJson(CodeParam::USER_ALREADY_EXIST, null);
            return false;
        }
        
        return true;
    }
}
