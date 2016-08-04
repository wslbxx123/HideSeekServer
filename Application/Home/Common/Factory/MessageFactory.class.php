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
        if($code == CodeParam::NOT_LOGIN) {
            return MessageParam::NOT_LOGIN;
        }
        
        if($code == CodeParam::SUCCESS) {
            return MessageParam::SUCCESS;
        }
        
        $userMessage = self::getUserMessage($code);
        if($userMessage != null) {
            return $userMessage;
        }
        
        $storeMessage = self::getStoreMessage($code);
        if($storeMessage != null) {
            return $storeMessage;
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
    
    public function getStoreMessage($code) {
        switch($code) {
            case CodeParam::VERSION_OR_MIN_ID_EMPTY:
                return MessageParam::VERSION_OR_MIN_ID_EMPTY;
            case CodeParam::STORE_ID_EMPTY:
                return MessageParam::STORE_ID_EMPTY;
            case CodeParam::COUNT_EMPTY:
                return MessageParam::COUNT_EMPTY;
            case CodeParam::ORDER_ID_EMPTY:
                return MessageParam::ORDER_ID_EMPTY;
            case CodeParam::ORDER_ID_WRONG:
                return MessageParam::ORDER_ID_WRONG;
            default:
                return null;
        }
    }
}
