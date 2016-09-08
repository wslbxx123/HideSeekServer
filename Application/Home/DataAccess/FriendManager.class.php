<?php
namespace Home\DataAccess;
/**
 * 操作朋友表(admin_friend)
 *
 * @author Two
 */
class FriendManager {
    public function getFriends($accountId, $version) {
        $Dao = M("friend");
        $sql = "call admin_get_friends($accountId, $version)";
        $friends = $Dao->query($sql);
        return $friends;
    }
    
    public function refreshRaceGroup($accountId, $version, $recordMinId) {
        $Dao = M("friend");
        $sql = "call admin_refresh_friend_record($accountId, $version, "
                . "$recordMinId)";
        $raceGroupList = $Dao->query($sql);
        
        if($raceGroupList != null && count($raceGroupList) > 0) {
            $tempRecordMinId = end($raceGroupList)['pk_id'];
            
            if($recordMinId == 0 || $tempRecordMinId < $recordMinId) {
                $recordMinId = $tempRecordMinId;
            }
        }
        return Array(
            "record_min_id" => $recordMinId,
            "race_group" => $raceGroupList
        );
    }
    
    public function getRaceGroup($accountId, $version, $recordMinId) {
        $Dao = M("friend");
        $sql = "call admin_get_friend_record($accountId, $version, $recordMinId)";
        $raceGroupList = $Dao->query($sql);
        
        if($raceGroupList != null && count($raceGroupList) > 0) {
            $tempRecordMinId = end($raceGroupList)['pk_id'];
            
            if($recordMinId == 0 || $tempRecordMinId < $recordMinId) {
                $recordMinId = $tempRecordMinId;
            }
        }
        
        return Array(
            "record_min_id" => $recordMinId,
            "race_group" => $raceGroupList
        );
    }
    
    public function insertFriend($accountAId, $accountBId, $remark, $version) {
        $Dao = M("friend");
        $friend["account_a_id"] = $accountAId;
        $friend["account_b_id"] = $accountBId;
        $friend["remark"] = $remark;
        $friend["version"] = $version;
        $Dao->add($friend);
    }
    
    public function updateRemark($accountId, $friendId, $remark, $version) {
        $Dao = M("friend");
        $condition["account_id"] = $accountId;
        $condition["friend_id"] = $friendId;
        $friend['remark'] = $remark;
        $friend['version'] = $version;
        $Dao->where($condition)->save($friend);
    }
    
    public function getFriendSum($accountId) {
        $Dao = M("friend");
        $condition["account_id"] = $accountId;
        return $Dao->where($condition)->count();
    }
}
