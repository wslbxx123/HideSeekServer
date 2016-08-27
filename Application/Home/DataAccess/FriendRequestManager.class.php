<?php
namespace Home\DataAccess;
/**
 * 操作好友请求表(admin_friend_request)
 *
 * @author Two
 */
class FriendRequestManager {
    public function insertFriendRequest($accountAId, $accountBId) {
        $Dao = M("friend");
        $condition["account_a_id"] = $accountAId;
        $condition["account_b_id"] = $accountBId;
        $friendRequest = $Dao->where($condition)->find();
        $time = date('y-m-d H:i:s',time());
        
        if($friendRequest == NULL) {
            $friendRequest["account_a_id"] = $accountAId;
            $friendRequest["account_b_id"] = $accountBId;
            $friendRequest["request_time"] = $time;
            $Dao->add($friendRequest);
        } else {
            $Dao->where($condition)->setField('request_time', $time);
            $friendRequest['request_time'] = $time;
        }
           
        return $friendRequest;
    }
}
