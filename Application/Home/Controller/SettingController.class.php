<?php
namespace Home\Controller;
use Home\DataAccess\FeedbackManager;
use Home\Common\Util\BaseUtil;
use Home\Common\Param\CodeParam;

class SettingController extends BaseController {
    public function addFeedback() {
        self::setHeader();
        
        $type = filter_input(INPUT_POST, 'type');
        $content = filter_input(INPUT_POST, 'content');
        $contact = filter_input(INPUT_POST, 'contact');
        
        if(!isset($type)) {
            BaseUtil::echoJson(CodeParam::TYPE_EMPTY, null);
            return;
        }
        
        if(!isset($content)) {
            BaseUtil::echoJson(CodeParam::CONTENT_EMPTY, null);
            return;
        }
        
        if(!isset($contact)) {
            BaseUtil::echoJson(CodeParam::CONTACT_EMPTY, null);
            return;
        }
        
        $feedbackId = FeedbackManager::insertFeedback($type, $content, $contact);
        
        BaseUtil::echoJson(CodeParam::SUCCESS, $feedbackId);
    }
}
