<?php
namespace Home\DataAccess;
/**
 * 操作好友请求表(admin_friend_request)
 *
 * @author Two
 */
class FriendRequestManager {
    public function insertFriendRequest($accountAId, $accountBId, $message) {
        $Dao = M("friend");
        $condition["account_a_id"] = $accountAId;
        $condition["account_b_id"] = $accountBId;
        $friendRequest = $Dao->where($condition)->find();
        $time = date('y-m-d H:i:s',time());
        
        if($friendRequest == NULL) {
            $friendRequest["account_a_id"] = $accountAId;
            $friendRequest["account_b_id"] = $accountBId;
            $friendRequest["request_time"] = $time;
            $friendRequest["message"] = $message;
            $Dao->add($friendRequest);
        } else {
            $data["request_time"] = $time;
            $data["message"] = $message;
            $Dao->where($condition)->save($data);
            $friendRequest['request_time'] = $time;
            $friendRequest['message'] = $message;
        }
           
        return $friendRequest;
    }
}
