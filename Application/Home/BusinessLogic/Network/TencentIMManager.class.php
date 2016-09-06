<?php
namespace Home\BusinessLogic\Network;
use Home\Common\Param\KeyParam;
vendor("Tencent.XingeApp");

/**
 * Baidu云推送操作类
 *
 * @author apple
 */
class TencentIMManager {
    function pushSingleDeviceIOS($channelId, $body, $object, $extraMessage, $type)
    {
	$push = new \XingeApp(KeyParam::ACCESS_ID, KeyParam::SECRET_KEY);
	$message = new \MessageIOS();
	$message->setExpireTime(86400);
	$message->setAlert(array('body' => $body, 
            'loc-key' => "FRIEND_REQUEST_MESSAGE",
            'loc-args' => []));
	$message->setBadge(1);
	$message->setSound("beep.wav");
	$custom = array('type' => $type, 'object'=> $object, 
            'extra' => $extraMessage);
	$message->setCustom($custom);
	$acceptTime = new \TimeInterval(0, 0, 23, 59);
	$message->addAcceptTime($acceptTime);
	$raw = '{"xg_max_payload":1,"accept_time":[{"start":{"hour":"20","min":"0"},"end":{"hour":"23","min":"59"}}],"aps":{"alert":"="}}';
	$message->setRaw($raw);
	$ret = $push->PushSingleDevice($channelId, $message, \XingeApp::IOSENV_DEV);
	return $ret['ret_code'] == 0;
    }
}
