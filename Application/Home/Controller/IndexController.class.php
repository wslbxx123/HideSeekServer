<?php
namespace Home\Controller;
use Home\Controller\BaseController;


class IndexController extends BaseController {
    public function index(){
        self::setHeader();
        
        $tradeStatus = filter_input(INPUT_GET, 'trade_status');
        
        $this->assign("tradeStatus", $tradeStatus);
        $this->display();
    }
    
    public function screen() {
        $this->display();
    }
    
    public function hideseek_m() {
        $tradeStatus = filter_input(INPUT_GET, 'trade_status');
        
        $this->assign("tradeStatus", $tradeStatus);
        $this->display();
    }
    
    public function sharePage() {
        $goalId = filter_input(INPUT_GET, 'goal_id');
        $sessionId = filter_input(INPUT_GET, 'session_id');
        
        $this->assign("goalId", $goalId);
        $this->assign("sessionId", $sessionId);
        $this->display();
    }
    
    public function haha() {
        echo "haha";
    }
}