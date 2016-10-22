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
    function pushSingleAccount($platform, $phone, $body, $args, $object, 
            $extraMessage, $type) {
        if($platform == 0) {
            return self::pushSingleAccountIOS($phone, $body, $args, $object, 
                $extraMessage, $type);
        } else {
            return self::pushSingleAccountAndroid($phone, $body, $args, $object, 
                $extraMessage, $type);
        }
    }
    
    function pushSingleAccountIOS($phone, $body, $args, $object, 
            $extraMessage, $type)
    {
	$push = new \XingeApp(KeyParam::XG_IOS_ACCESS_ID, KeyParam::XG_IOS_SECRET_KEY);
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
    
    function pushSingleAccountAndroid($phone, $body, $args, $object, 
            $extraMessage, $type)
    {
	$push = new \XingeApp(KeyParam::XG_ANDROID_ACCESS_ID, KeyParam::XG_ANDROID_SECRET_KEY);
	$message = new \Message();
	$message->setExpireTime(86400);
        $message->setTitle("寻宝部落");
        $message->setContent($body);
        $message->setExpireTime(86400);
	$custom = array('type' => $type, 'object'=> $object, 
            'extra' => $extraMessage, 'args'=>$args, 'body'=>$body);
	$message->setCustom($custom);
        $message->setType(\Message::TYPE_MESSAGE);
	$acceptTime = new \TimeInterval(0, 0, 23, 59);
	$message->addAcceptTime($acceptTime);
        
	$ret = $push->PushSingleAccount(0, $phone, $message);
	return $ret['ret_code'] == 0;
    }
    
    function deleteAllTokens($account) {
        $push = new \XingeApp(KeyParam::ACCESS_ID, KeyParam::SECRET_KEY);
        $push->DeleteAllTokensOfAccount($account);
    }
}
