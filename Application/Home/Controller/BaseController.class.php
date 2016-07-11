<?php
namespace Home\Controller;
use Think\Controller;

class BaseController extends Controller {
    public function getPkIdFromToken($sessionId){
        session_id($sessionId);
        session_start();
        
        $account_id = 0;
        if(isset($_SESSION['pk_id'])) {
            $account_id = $_SESSION['pk_id'];    
        } else {
            if(isset($sessionId)) {
                $account_condition['SESSION_TOKEN'] = md5($sessionId);
                $account = M("account")->where($account_condition)->find();
                $account_id = $account['pk_id'];
            }
        }
        
        return $account_id;
    }
}

