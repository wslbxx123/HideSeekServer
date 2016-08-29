<?php
namespace Home\BusinessLogic\Network;
use Home\Common\Util\BaseUtil;
use Home\Common\Param\CodeParam;
vendor("Baidu.sdk");

/**
 * Baidu云推送操作类
 *
 * @author apple
 */
class BaiduIMManager {
    public function sendFriendRequest($channelId, $account, $extraMessage) {
        $account['password'] = "";
        $sdk = new \PushSDK();
        $message = array(
            'aps' => array (
                'alert' => array (
                    'loc_key' => "FRIEND_REQUEST_MESSAGE"
//                    'loc-args' => []
                )
            ),
            'type' => 1, 'account' => $account, 'extra' => $extraMessage
        );
        
        $opts = array (
            'msg_type' => 1,        // iOS不支持透传, 只能设置 msg_type:1, 即通知消息.
            'deploy_status' => 1,   // iOS应用的部署状态:  1：开发状态；2：生产状态； 若不指定，则默认设置为生产状态。
        );
        
        $result = $sdk ->pushMsgToSingleDevice($channelId, $message, $opts);
        
        if(!$result) {
            BaseUtil::echoJson(CodeParam::FAIL_SEND_MESSAGE, $sdk->getLastErrorMsg());
        }

        return $result;
    }
}
