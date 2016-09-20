<?php
namespace Home\Controller;
use Think\Controller;
use Home\Common\Util\RequestUtil;

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
        
        $serverName = $isMobile ? "m.hideseek.cn" : $server['SERVER_NAME'];
        $controller = $isMobile ? "Mindex" : "Index";
        
        if($isMobile || !RequestUtil::isSSL($server)) {
             header('Location: https://'.$serverName
                     .U($controller, ACTION_NAME).$server['QUERY_STRING']);
             exit();
        } 
    }
    
    public function setMobileHeader() {
        header("Content-Type:text/html; charset=utf-8");
        $server = filter_input_array(INPUT_SERVER);
        $isMobile = RequestUtil::isMobile($server);
        
        $serverName = $isMobile ? "m.hideseek.cn" : $server['SERVER_NAME'];
        
        if(!$isMobile || !RequestUtil::isSSL($server)) {
             header('Location: https://'.$serverName.U('Mindex/'
                     .ACTION_NAME.$server['QUERY_STRING']));
             exit();
        }
    }
    
    public function getPkIdFromToken($sessionId){
        $account = $this->getAccountFromToken($sessionId);
        
        if(!isset($account)) {
            return 0;
        }
        
        return $account['pk_id'];
    }
    
    public function getAccountFromToken($sessionId){
        $length = strlen($sessionId);
        session_id(subStr($sessionId, 0, $length - 10));
        session_start();
        
        $account_condition['session_token'] = md5($sessionId);
        
        $account = M("account")->where($account_condition)->find();
        return $account;
    }
}

