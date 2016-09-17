<?php
namespace Home\DataAccess;
/**
 * 操作好友请求表(admin_friend_request)
 *
 * @author Two
 */
class FriendRequestManager {
    public function insertFriendRequest($accountAId, $accountBId, $message, $status) {
        $Dao = M("friend_request");
        $condition["account_a_id"] = $accountAId;
        $condition["account_b_id"] = $accountBId;
        $friendRequest = $Dao->where($condition)->find();
        $time = date('y-m-d H:i:s',time());
        
        if($friendRequest == NULL) {
            $friendRequest["account_a_id"] = $accountAId;
            $friendRequest["account_b_id"] = $accountBId;
            $friendRequest["request_time"] = $time;
            $friendRequest["message"] = $message;
            $friendRequest["status"] = $status;
            $Dao->add($friendRequest);
        } else {
            $data["request_time"] = $time;
            $data["message"] = $message;
            $data["status"] = $status;
            $Dao->where($condition)->save($data);
        }
           
        return $Dao->where($condition)->find();
    }
    
    public function getFriendRequests($accountId) {
        $Dao = M("friend_request");
        $sql = "call admin_get_friend_requests($accountId)";
        $friendRequests = $Dao->query($sql);
        return $friendRequests;
    }
}
