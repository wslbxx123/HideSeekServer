<?php
namespace Home\Controller;
use Think\Controller;
use Home\Common\Util\RequestUtil;
use Home\DataAccess\AccountManager;

class BaseController extends Controller {
    public function setHeader() {
        header("Content-Type:text/html; charset=utf-8");
        header('Access-Control-Allow-Origin: www.hideseek.cn');
        header('Access-Control-Allow-Methods: POST');
        header('Access-Control-Max-Age: 1000');
    }
    
    public function setWebHeader() {
        header("Content-Type:text/html; charset=utf-8");
        $server = filter_input_array(INPUT_SERVER);
        $isMobile = RequestUtil::isMobile($server);
        
        $serverName = $isMobile ? "m.hideseek.cn" : "www.hideseek.cn";
        $controller = $isMobile ? "Mindex" : "Index";
        if(!strtolower(ACTION_NAME) == "index") {
            $url = U($controller.'/'.ACTION_NAME);
        }
        
        if($isMobile || !RequestUtil::isSSL($server)) {
             header('Location: https://'.$serverName
                     .$url.$server['QUERY_STRING']);
             exit();
        } 
    }
    
    public function setMobileHeader() {
        header("Content-Type:text/html; charset=utf-8");
        $server = filter_input_array(INPUT_SERVER);
        $isMobile = RequestUtil::isMobile($server);
        
        $serverName = $isMobile ? "m.hideseek.cn" : "www.hideseek.cn";
        $controller = $isMobile ? "Mindex" : "Index";
        if(!strtolower(ACTION_NAME) == "index") {
            $url = U($controller.'/'.ACTION_NAME);
        }
        
        if(!$isMobile || !RequestUtil::isSSL($server)) {
             header('Location: https://'.$serverName
                     .$url.$server['QUERY_STRING']);
             exit();
        }
    }
    
    public function getPkIdFromToken($sessionId, $appVersion){
        $account = $this->getAccountFromToken($sessionId, $appVersion);
        
        if(!isset($account)) {
            return 0;
        }
        
        return $account['pk_id'];
    }
    
    public function getAccountFromToken($sessionId, $appVersion){
        $length = strlen($sessionId);
        session_id(subStr($sessionId, 0, $length - 10));
        session_start();
        
        $account = AccountManager::updateAppVersion(md5($sessionId), $appVersion);
        
        return $account;
    }
}

