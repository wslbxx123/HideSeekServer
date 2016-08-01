<?php
namespace Home\Controller;
use Think\Controller;

class BaseController extends Controller {
    public function setHeader() {
        header("Content-Type:text/html; charset=utf-8");
        $tempCallback = filter_input(INPUT_POST, 'callback');
        $callback = isset($tempCallback) ? trim($tempCallback) : '';
        
        return $callback;
    }
    
    public function getPkIdFromToken($sessionId){
        $account = $this->getAccountFromToken($sessionId);
        
        if(!isset($account)) {
            return 0;
        }
        
        return $account['pk_id'];
    }
    
    public function getAccountFromToken($sessionId){
        session_id($sessionId);
        session_start();
        
        $account_id = 0;
        if(isset($_SESSION['pk_id'])) {
            $account_id = $_SESSION['pk_id'];
            $account_condition['pk_id'] = $account_id;
        } else {
            if(isset($sessionId)) {
                $account_condition['SESSION_TOKEN'] = md5($sessionId);
            }
        }
        
        $account = M("account")->where($account_condition)->find();
        return $account;
    }
}

