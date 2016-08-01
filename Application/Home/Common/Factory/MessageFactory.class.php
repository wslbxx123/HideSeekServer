<?php
namespace Home\Common\Factory;

use Home\Common\Param\CodeParam;
use Home\Common\Param\MessageParam;
/**
 * 获得情况信息
 *
 * @author apple
 */
class MessageFactory {
    public function get($code) {
        if($code == CodeParam::SUCCESS) {
            return MessageParam::SUCCESS;
        }
        
        $message = self::getUserMessage($code);
        
        if(message != null) {
            return $message;
        }
    }
    
    public function getUserMessage($code) {
        switch($code) {
            case CodeParam::PHONE_OR_PASSWORD_WRONG:
                return MessageParam::PHONE_OR_PASSWORD_WRONG;
            case CodeParam::PHONE_OR_PASSWORD_EMPTY:
                return MessageParam::PHONE_OR_PASSWORD_EMPTY;
            case CodeParam::NICKNAME_EMPTY:
                return MessageParam::NICKNAME_EMPTY;
            case CodeParam::FAIL_UPLOAD_PHOTO:
                return MessageParam::FAIL_UPLOAD_PHOTO;
            case CodeParam::USER_ALREADY_EXIST:
                return MessageParam::USER_ALREADY_EXIST;
            default:
                return null;
        }
    }
}
