<?php
namespace Home\Controller;
use Home\Controller\BaseController;
use Home\Common\Util\FileUtil;
use Home\Common\Util\BaseUtil;
use Home\Common\Param\CodeParam;
use Home\Common\Param\KeyParam;
use Home\DataAccess\AccountManager;
use Home\DataAccess\RecordManager;
use Home\DataAccess\PullVersionManager;
use Home\DataAccess\SettingManager;
use Home\BusinessLogic\Network\AlipayManager;
use Home\BusinessLogic\Manager\UserControllerManager;
use Home\DataAccess\FriendRequestManager;
use Home\BusinessLogic\Network\TencentIMManager;

class UserController extends BaseController {
    public function getSettings() {
        self::setHeader();
        
        $setting = SettingManager::getSetting();
        BaseUtil::echoJson(CodeParam::SUCCESS, $setting);
    }
    
    public function getFriendRequests() {
        self::setHeader();
        
        $sessionId = filter_input(INPUT_POST, 'session_id');
        $appVersion = filter_input(INPUT_POST, 'app_version');
        $accountId = $this->getPkIdFromToken($sessionId, $appVersion);
        
        if(!isset($sessionId) || $accountId == 0) {
            BaseUtil::echoJson(CodeParam::NOT_LOGIN, null);
            return;
        }
        
        $friendReqeusts = FriendRequestManager::getFriendRequests($accountId);
        
        BaseUtil::echoJson(CodeParam::SUCCESS, $friendReqeusts);
    }
    
    public function login(){
        self::setHeader();
        session_start();

        $phone = filter_input(INPUT_POST, 'phone');
        $password = filter_input(INPUT_POST, 'password');
        $channelId = filter_input(INPUT_POST, 'channel_id');
        $appPlatform = filter_input(INPUT_POST, 'app_platform');
        
        if(!UserControllerManager::checkUserInfo($phone, $password)) {
            return;
        }
        
        $account = AccountManager::getAccountFromPhonePassword($phone, $password, 
                $appPlatform);
        
        if(!isset($account)) {
            BaseUtil::echoJson(CodeParam::PHONE_OR_PASSWORD_WRONG, null);
            return;
        }
        
        $sessionId = UserControllerManager::updateUserInfo($account);
        $_SESSION['pk_id'] = $account["pk_id"];
        $account["session_id"] = $sessionId;
        $account["friend_requests"] = FriendRequestManager::getFriendRequests($account['pk_id']);  
        
        BaseUtil::echoJson(CodeParam::SUCCESS, $account);
    }
    
    public function logout(){
        self::setHeader();

        $sessionId = filter_input(INPUT_POST, 'session_id');
        $account = $this->getAccountFromToken($sessionId);
        
        if(!isset($sessionId) || $account['pk_id'] == 0) {
            BaseUtil::echoJson(CodeParam::NOT_LOGIN, null);
            return;
        }
        AccountManager::clearChannelId($account['pk_id']);
        AccountManager::clearSessionToken($account['pk_id']);
        TencentIMManager::deleteAllTokens($account['phone']);
        
        BaseUtil::echoJson(CodeParam::SUCCESS, null);
    }
    
    public function register() {
        self::setHeader();
        session_start();
        session(array('name'=>'pk_id','expire'=>3600));
        
        $phone = filter_input(INPUT_POST, 'phone');
        $password = filter_input(INPUT_POST, 'password');
        $nickname = filter_input(INPUT_POST, 'nickname');
        $role = filter_input(INPUT_POST, 'role');
        $sex = filter_input(INPUT_POST, 'sex');
        $region = filter_input(INPUT_POST, 'region');
        $channelId = filter_input(INPUT_POST, 'channel_id');
        $appPlatform = filter_input(INPUT_POST, 'app_platform');
        $photo = $_FILES['photo'];
        $photoDataUrl = filter_input(INPUT_POST, 'photo_url');
        
        $sessionId = session_id().strtotime(date ("Y-m-d h:i:s"));
        $accountId = UserControllerManager::setRegisterUserInfo($phone, 
                $password, $nickname, $role, $sex, $region, $channelId, 
                $photo, $photoDataUrl, $sessionId, $appPlatform);
        
        if(!isset($accountId)) { return; }
        
        $_SESSION['pk_id'] = $accountId;
        $account = AccountManager::getAccount($accountId);
        $account["session_id"] = $sessionId;
        
        BaseUtil::echoJson(CodeParam::SUCCESS, $account); 
    }
    
    public function checkIfUserExist() {
        self::setHeader();
        $phone = filter_input(INPUT_POST, 'phone');
        
        if(AccountManager::getAccountFromPhone($phone)) {
            BaseUtil::echoJson(CodeParam::USER_ALREADY_EXIST, null);
            return false;
        }
        
        BaseUtil::echoJson(CodeParam::SUCCESS, null);
    }
    
    public function sendVerificationCode() {
        self::setHeader();
        
        $phone = filter_input(INPUT_POST, 'phone');
        $sendUrl = 'http://v.juhe.cn/sms/send';
        $code = BaseUtil::getRandomNum(1000, 9999);
        $smsConf = array(
            'key'   => KeyParam::SMS_KEY, //您申请的APPKEY
            'mobile'    => $phone, //接受短信的用户手机号码
            'tpl_id'    => '18004', //您申请的短信模板ID，根据实际情况修改
            'tpl_value' => urlencode('#code#='.$code) //您设置的模板变量，根据实际情况修改
        );
        $content = AlipayManager::getHttpResponse($sendUrl, $smsConf, 0);
        if($content){
            $resultContent = json_decode($content);
        }else{
            $resultContent =  "请求发送短信失败";
        }
        
        $result = Array("sms_code" => $code, "content" => $resultContent);
        BaseUtil::echoJson(CodeParam::SUCCESS, $result);
    }
    
    public function updatePhotoUrl() {
        self::setHeader();
        
        $sessionId = filter_input(INPUT_POST, 'session_id');
        $photo = $_FILES['photo'];
        $photoDataUrl = filter_input(INPUT_POST, 'photo_url');
        $appVersion = filter_input(INPUT_POST, 'app_version');
        $accountId = $this->getPkIdFromToken($sessionId, $appVersion);
        
        if(!isset($sessionId) || $accountId == 0) {
            BaseUtil::echoJson(CodeParam::NOT_LOGIN, null);
            return;
        }
        
        $photoUrl = FileUtil::saveRealPhoto($photo, $photoDataUrl);
        
        if(isset($photo) && !isset($photoUrl)) {
            BaseUtil::echoJson(CodeParam::FAIL_UPLOAD_PHOTO, null);
            return null;
        }
        
        $smallPhotoUrl = FileUtil::saveSmallPhoto($photoUrl, 200, 200);
        
        AccountManager::updatePhoto($accountId, $photoUrl, $smallPhotoUrl);
        $version = PullVersionManager::updateRaceGroupVersion();
        RecordManager::updateVersion($accountId, $version);
        
        $result = Array("photo_url" => $photoUrl, "small_photo_url" => $smallPhotoUrl);
        BaseUtil::echoJson(CodeParam::SUCCESS, $result);
    }
    
    public function updateNickname() {
        self::setHeader();
        
        $sessionId = filter_input(INPUT_POST, 'session_id');
        $nickname = filter_input(INPUT_POST, 'nickname');
        $appVersion = filter_input(INPUT_POST, 'app_version');
        $accountId = $this->getPkIdFromToken($sessionId, $appVersion);
        
        if(!isset($sessionId) || $accountId == 0) {
            BaseUtil::echoJson(CodeParam::NOT_LOGIN, null);
            return;
        }
        
        if(!isset($nickname)) {
            BaseUtil::echoJson(CodeParam::NICKNAME_EMPTY, null);
            return null;
        }
        
        AccountManager::updateNickname($accountId, $nickname);
        $version = PullVersionManager::updateRaceGroupVersion();
        RecordManager::updateVersion($accountId, $version);
        
        $result = Array("nickname" => $nickname);
        BaseUtil::echoJson(CodeParam::SUCCESS, $result);
    }
    
    public function updateSex() {
        self::setHeader();
        
        $sessionId = filter_input(INPUT_POST, 'session_id');
        $sex = filter_input(INPUT_POST, 'sex');
        $appVersion = filter_input(INPUT_POST, 'app_version');
        $accountId = $this->getPkIdFromToken($sessionId, $appVersion);
        
        if(!isset($sessionId) || $accountId == 0) {
            BaseUtil::echoJson(CodeParam::NOT_LOGIN, null);
            return;
        }
        
        if(!isset($sex)) {
            BaseUtil::echoJson(CodeParam::SEX_EMPTY, null);
            return null;
        }
        
        AccountManager::updateSex($accountId, $sex);
        
        $result = Array("sex" => $sex);
        BaseUtil::echoJson(CodeParam::SUCCESS, $result);
    }
    
    public function updateRegion() {
        self::setHeader();
        
        $sessionId = filter_input(INPUT_POST, 'session_id');
        $region = filter_input(INPUT_POST, 'region');
        $appVersion = filter_input(INPUT_POST, 'app_version');
        $accountId = $this->getPkIdFromToken($sessionId, $appVersion);
        
        if(!isset($sessionId) || $accountId == 0) {
            BaseUtil::echoJson(CodeParam::NOT_LOGIN, null);
            return;
        }
        
        if(!isset($region)) {
            BaseUtil::echoJson(CodeParam::SEX_EMPTY, null);
            return null;
        }
        
        AccountManager::updateRegion($accountId, $region);
        
        $result = Array("region" => $region);
        BaseUtil::echoJson(CodeParam::SUCCESS, $result);
    }
    
    public function updatePassword() {
        self::setHeader();
        
        $phone = filter_input(INPUT_POST, 'phone');
        $password = filter_input(INPUT_POST, 'password');
        
        if(!isset($phone) || !isset($password)) {
            BaseUtil::echoJson(CodeParam::PHONE_OR_PASSWORD_EMPTY, null);
            return null;
        }
        
        AccountManager::updatePassword($phone, $password);
        
        BaseUtil::echoJson(CodeParam::SUCCESS, null);
    }
    
    public function refreshAccountData() {
        self::setHeader();
        
        $sessionId = filter_input(INPUT_POST, 'session_id');
        $account = $this->getAccountFromToken($sessionId);
        
        if(!isset($sessionId) || $account['pk_id'] == 0) {
            BaseUtil::echoJson(CodeParam::NOT_LOGIN, null);
            return;
        }
        
        BaseUtil::echoJson(CodeParam::SUCCESS, $account);
    }
    
    public function updateChannelId() {
        self::setHeader();
        
        $sessionId = filter_input(INPUT_POST, 'session_id');
        $channelId = filter_input(INPUT_POST, 'channel_id');
        $appVersion = filter_input(INPUT_POST, 'app_version');
        $accountId = $this->getPkIdFromToken($sessionId, $appVersion);
        
        if(!isset($sessionId) || $accountId == 0) {
            BaseUtil::echoJson(CodeParam::NOT_LOGIN, null);
            return;
        }
        
        if(!isset($channelId)) {
            BaseUtil::echoJson(CodeParam::CHANNEL_ID_EMPTY, null);
            return;
        }
        
        AccountManager::updateChannelId($accountId, $channelId);
        BaseUtil::echoJson(CodeParam::SUCCESS, $channelId);
    }
    
    public function updateUserInfo() {
        self::setHeader();
        
        $sessionId = filter_input(INPUT_POST, 'session_id');
        $appVersion = filter_input(INPUT_POST, 'app_version');
        $account = $this->getAccountFromToken($sessionId, $appVersion);
        
        if(!isset($sessionId) || $account['pk_id'] == 0) {
            BaseUtil::echoJson(CodeParam::NOT_LOGIN, null);
            return;
        }
        
        $result = array (
            'bomb_num' => $account['bomb_num'], 
            'has_guide' => $account['has_guide']);
        BaseUtil::echoJson(CodeParam::SUCCESS, $result);
    }
}

