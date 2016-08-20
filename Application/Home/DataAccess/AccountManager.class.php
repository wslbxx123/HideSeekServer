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
    
    public function updateScoreSum($accountId, $scoreSum) {
        $Dao = M("account");
        $account['record'] = $scoreSum;
        $condition['pk_id'] = $accountId;
        $Dao->where($condition)->save($account);
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
        $account["role"] = $role;
        
        $account = self::insertOptionalInfo($sex, $region, $photoUrl, 
                $smallPhotoUrl, $account);
        
        return $Dao->add($account);
    }
    
    private function insertOptionalInfo($sex, $region, $photoUrl, 
            $smallPhotoUrl, $account) {
        if(isset($sex)) {
            $account["sex"] = $sex;
        }
        
        if(isset($region)) {
            $account["region"] = $region;
        }
        
        if(isset($photoUrl)) {
            $account["photo_url"] = $photoUrl;
        }
        
        if(isset($smallPhotoUrl)) {
            $account["small_photo_url"] = $smallPhotoUrl;
        }
        
        return $account;
    }
    
    public function updateRecord($record, $count, $accountId) {
        $Dao = M("account");
        $condition["pk_id"] = $accountId;
        $account = $Dao->where($condition)->find();
        $newRecord = $account["record"] - $record * $count;
        $Dao->where($condition)->setField('record', 
                $newRecord);
        
        return $newRecord;
    }
    
    public function updateBombNum($accountId, $bombNum) {
        $Dao = M("account");
        $condition["pk_id"] = $accountId;
        $account['bomb_num'] = $bombNum;
        $Dao->where($condition)->save($account);
    }
    
    public function updatePhoto($accountId, $photoUrl, $smallPhotoUrl) {
        $Dao = M("account");
        $condition["pk_id"] = $accountId;
        $account['photo_url'] = $photoUrl;
        $account['small_photo_url'] = $smallPhotoUrl;
        $Dao->where($condition)->save($account);
    }
    
    public function updateNickname($accountId, $nickname) {
        $Dao = M("account");
        $condition["pk_id"] = $accountId;
        $account['nickname'] = $nickname;
        $Dao->where($condition)->save($account);
    }
    
    public function updateSex($accountId, $sex) {
        $Dao = M("account");
        $condition["pk_id"] = $accountId;
        $account['sex'] = $sex;
        $Dao->where($condition)->save($account);
    }
    
    public function updateRegion($accountId, $region) {
        $Dao = M("account");
        $condition["pk_id"] = $accountId;
        $account['region'] = $region;
        $Dao->where($condition)->save($account);
    }
}
