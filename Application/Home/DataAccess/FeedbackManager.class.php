<?php
namespace Home\DataAccess;
/**
 * 操作反馈表(admin_feedback)
 *
 * @author Two
 */
class FeedbackManager {
    public function insertFeedback($type, $content, $contact) {
        $Dao = M("feedback");
        $feedback["type"] = $type;
        $feedback["content"] = $content;
        $feedback["contact"] = $contact;
        
        return $Dao->add($feedback);
    }
}
