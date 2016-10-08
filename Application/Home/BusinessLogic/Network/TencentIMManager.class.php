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
    function pushSingleAccountIOS($phone, $body, $args, $object, 
            $extraMessage, $type)
    {
	$push = new \XingeApp(KeyParam::ACCESS_ID, KeyParam::SECRET_KEY);
	$message = new \MessageIOS();
	$message->setExpireTime(86400);
	$message->setAlert(array(
            'loc-key' => $body,
            'loc-args' => $args));
	$message->setBadge(1);
	$message->setSound("beep.wav");
	$custom = array('type' => $type, 'object'=> $object, 
            'extra' => $extraMessage);
	$message->setCustom($custom);
	$acceptTime = new \TimeInterval(0, 0, 23, 59);
	$message->addAcceptTime($acceptTime);
        
	$ret = $push->PushSingleAccount(0, $phone, $message, \XingeApp::IOSENV_PROD);
	return $ret['ret_code'] == 0;
    }
    
    function deleteAllTokens($account) {
        $push = new \XingeApp(KeyParam::ACCESS_ID, KeyParam::SECRET_KEY);
        $push->DeleteAllTokensOfAccount($account);
    }
}
