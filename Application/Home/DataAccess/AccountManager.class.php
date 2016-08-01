<?php
namespace Home\DataAccess;
/**
 * 操作用户表(admin_account)
 *
 * @author Two
 */
class AccountManager {
    public function getAccountFromPhonePassword($phone, $password) {
        $Dao = M("account");
        $condition['phone'] = $phone;
        $condition['password'] = md5($password);
        $account = $Dao->where($condition)->find();
        return $account;
    }
    
    public function updateSessionToken($phone, $password) {
        $Dao = M("account");
        $condition['phone'] = $phone;
        $condition['password'] = md5($password);
        $Dao->where($condition)->setField('session_token', md5(session_id()));
    }
    
    public function getAccount($accountId) {
        $Dao = M("account");
        $condition['pk_id'] = $accountId;
        $account = $Dao->where($condition)->find();
        return $account;
    }
    
    public function getAccountFromPhone($phone) {
        $Dao = M("account");
        $condition['phone'] = $phone;
        $account = $Dao->where($condition)->find();
        return $account;
    }
    
    public function insertAccount($phone, $password, $nickname, $version, $role, 
            $sex, $region, $photoUrl, $smallPhotoUrl) {
        $Dao = M("account");
        $account["phone"] = $phone;
        $account["password"] = md5($password);
        $account["nickname"] = $nickname;
        $account["register_date"] = date('y-m-d H:i:s',time());
        $account["session_token"] = md5(session_id());
        $account["version"] = $version;
        
        insertOptionalInfo($role, $sex, $region, $photoUrl, $smallPhotoUrl);
        
        return $Dao->add($account);
    }
    
    private function insertOptionalInfo($role, $sex, $region, $photoUrl, 
            $smallPhotoUrl) {
        if(isset($role)) {
            $account["role"] = $role;
        }
        
        if(isset($sex)) {
            $account["sex"] = $sex;
        }
        
        if(isset($region)) {
            $account["region"] = $region;
        }
        
        if(isset($photoUrl)) {
            $account["photo_url"] = $photoUrl;
        }
    }
}
