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
        $server = filter_input_array(INPUT_SERVER);
        $isMobile = RequestUtil::isMobile($server);
        
        if($isMobile || !RequestUtil::isSSL($server)) {
             header('Location: https://'.$server['SERVER_NAME'].U('Mindex/'.ACTION_NAME));
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

