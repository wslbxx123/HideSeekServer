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
        
        $mapMessage = self::getMapMessage($code);
        if($mapMessage != null) {
            return $mapMessage;
        }
        
        $friendMessage = self::getFriendMessage($code);
        if($friendMessage != null) {
            return $friendMessage;
        }
        
        $settingMessage = self::getSettingMessage($code);
        if($settingMessage != null) {
            return $settingMessage;
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
            case CodeParam::SEX_EMPTY:
                return MessageParam::SEX_EMPTY;
            case CodeParam::CHANNEL_ID_EMPTY:
                return MessageParam::CHANNEL_ID_EMPTY;
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
            case CodeParam::REWARD_ID_EMPTY:
                return MessageParam::REWARD_ID_EMPTY;
            case CodeParam::RECORD_NOT_ENOUGH:
                return MessageParam::RECORD_NOT_ENOUGH;
            case CodeParam::AREA_EMPTY:
                return MessageParam::AREA_EMPTY;
            case CodeParam::ADDRESS_EMPTY:
                return MessageParam::ADDRESS_EMPTY;
            default:
                return null;
        }
    }
    
    public function getMapMessage($code) {
        switch($code) {
            case CodeParam::GOAL_ID_OR_TYPE_EMPTY:
                return MessageParam::GOAL_ID_OR_TYPE_EMPTY;
            case CodeParam::LATITUDE_OR_LONGITUDE_EMPTY:
                return MessageParam::LATITUDE_OR_LONGITUDE_EMPTY;
            case CodeParam::GOAL_ID_OR_ROLE_EMPTY:
                return MessageParam::GOAL_ID_OR_ROLE_EMPTY;
            case CodeParam::GOAL_DISAPPEAR:
                return MessageParam::GOAL_DISAPPEAR;
            case CodeParam::VERSION_EMPTY:
                return MessageParam::VERSION_EMPTY;
            case CodeParam::ORIENTATION_EMPTY:
                return MessageParam::ORIENTATION_EMPTY;
            case CodeParam::GOAL_ID_EMPTY:
                return MessageParam::GOAL_ID_EMPTY;
            default:
                return null;
        }
    }
    
    public function getFriendMessage($code) {
        switch($code) {
            case CodeParam::SEARCH_WORD_EMPTY:
                return MessageParam::SEARCH_WORD_EMPTY;
            case CodeParam::FRIEND_ID_EMPTY:
                return MessageParam::FRIEND_ID_EMPTY;
            case CodeParam::FAIL_SEND_MESSAGE:
                return MessageParam::FAIL_SEND_MESSAGE;
            case CodeParam::REQUEST_MESSAGE_EMPTY:
                return MessageParam::REQUEST_MESSAGE_EMPTY;
            case CodeParam::SEARCH_MYSELF:
                return MessageParam::SEARCH_MYSELF;
            case CodeParam::FRIEND_REMARK_EMPTY:
                return MessageParam::FRIEND_REMARK_EMPTY;
            default:
                return null;
        }
    }
    
    public function getSettingMessage($code) {
        switch($code) {
            case CodeParam::TYPE_EMPTY:
                return MessageParam::TYPE_EMPTY;
            case CodeParam::CONTENT_EMPTY:
                return MessageParam::CONTENT_EMPTY;
            case CodeParam::CONTACT_EMPTY:
                return MessageParam::CONTACT_EMPTY;    
            default:
                return null;
        }
    }
}
