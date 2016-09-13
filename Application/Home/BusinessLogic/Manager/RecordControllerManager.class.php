<?php
namespace Home\BusinessLogic\Manager;
use Home\Common\Util\BaseUtil;
use Home\Common\Param\CodeParam;
/**
 * 处理记录控制器的逻辑类
 *
 * @author Two
 */
class RecordControllerManager {
    public function checkRefreshRecordInfo($sessionId, $accountId, $version, 
            $recordMinId) {
        if(!isset($sessionId) || $accountId == 0) {
            BaseUtil::echoJson(CodeParam::NOT_LOGIN, null);
            return false;
        }
        
        if(!isset($version) || !isset($recordMinId)) {
            BaseUtil::echoJson(CodeParam::VERSION_OR_MIN_ID_EMPTY, null);
            return false;
        }
        
        return true;
    }
}
