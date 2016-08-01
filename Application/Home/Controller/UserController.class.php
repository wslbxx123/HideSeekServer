<?php
namespace Home\Controller;
use Think\Controller;
use Home\Common\Util\FileUtil;
use Home\Common\Util\BaseUtil;
use Home\Common\Param\CodeParam;
use Home\DataAccess\AccountManager;
use Home\DataAccess\PullVersionManager;

class UserController extends Controller {
    public function login(){
        header("Content-Type:text/html; charset=utf-8");
        session_start();
        session(array('name'=>'pk_id','expire'=>3600));
        $phone = filter_input(INPUT_POST, 'phone');
        $password = filter_input(INPUT_POST, 'password');
        
        if(!isset($phone) || !isset($password)) {
            BaseUtil::echoJson(CodeParam::PHONE_OR_PASSWORD_EMPTY, null);
            return;
        }       
        $account = AccountManager::getAccountFromPhonePassword($phone, $password);
        if(!isset($account)) {
            BaseUtil::echoJson(CodeParam::PHONE_OR_PASSWORD_WRONG, null);
            return;
        }
        
        AccountManager::updateSessionToken($phone, $password);
        $_SESSION['pk_id'] = $account["pk_id"];
        $account["session_id"] = session_id();
        
        BaseUtil::echoJson(CodeParam::SUCCESS, $account);
    }
    
    public function register() {
        header("Content-Type:text/html; charset=utf-8");
        session_start();
        session(array('name'=>'pk_id','expire'=>3600));
        
        $phone = filter_input(INPUT_POST, 'phone');
        $password = filter_input(INPUT_POST, 'password');
        $nickname = filter_input(INPUT_POST, 'nickname');
        $role = filter_input(INPUT_POST, 'role');
        $sex = filter_input(INPUT_POST, 'sex');
        $region = filter_input(INPUT_POST, 'region');
        $photo = $_FILES['photo'];
        
        $accountId = self::setRegisterUserInfo($phone, $password, $nickname,
                $role, $sex, $region, $photo);
        
        if(!isset($accountId)) {
            return;
        }
        
        $_SESSION['pk_id'] = $accountId;
        $account["session_id"] = session_id();
        $account = AccountManager::getAccount($accountId);
        
        BaseUtil::echoJson(CodeParam::SUCCESS, $account); 
    }
    
    public function setRegisterUserInfo($phone, $password, $nickname, $role, 
            $sex, $region, $photo) {
        if(!self::checkUserBaseInfo()) {
            return null;
        }
        
        $tempFileName = "Upload_".session_id()."_".strtotime("now");
        $photoUrl = FileUtil::saveRealPhoto($photo, $tempFileName);
        
        if(isset($photo) && !isset($photoUrl)) {
            BaseUtil::echoJson(CodeParam::FAIL_UPLOAD_PHOTO, null);
            return null;
        }
        
        $smallPhotoUrl = FileUtil::saveSmallPhoto($photo, $photoUrl, 
                $tempFileName, 200, 200);
        $accountId = AccountManager::insertAccount($phone, $password, $nickname, 
                PullVersionManager::getFriendVersion(), $role, $sex, $region, 
                $photoUrl, $smallPhotoUrl);
        
        return $accountId;
    }
    
    public function checkUserBaseInfo($phone, $password, $nickname) {
        if(!isset($phone) || !isset($password)) {
            BaseUtil::echoJson(CodeParam::PHONE_OR_PASSWORD_EMPTY, null);
            return false;
        }
        
        if(!isset($nickname)) {
            BaseUtil::echoJson(CodeParam::NICKNAME_EMPTY, null);
            return false;
        }
        
        if(AccountManager::getAccountFromPhone != null) {
            BaseUtil::echoJson(CodeParam::USER_ALREADY_EXIST, null);
            return false;
        }
        
        return true;
    }
    
    public function getVerificationCode() {
        $options['accountsid']='63160b32bb938243508e2d11cb8f3a1d';
        $options['token']='d10ac1aec1d6f79fb8422ab9a7cb67be';
        
        $ucpass = new Ucpaas($options);
        $appId = "aecc47a2c56c4638a0253da64649a137";
        $to = "15673247044";
    }
}

