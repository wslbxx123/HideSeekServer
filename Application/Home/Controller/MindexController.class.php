<?php
namespace Home\Controller;
use Home\Controller\BaseController;

class MindexController extends BaseController {
    public function index(){
        self::setHeader();
        
        $tradeStatus = filter_input(INPUT_GET, 'trade_status');
        
        $this->assign("tradeStatus", $tradeStatus);
        $this->display();
    }
    
    public function sharePage(){
        self::setHeader();
        
        $goalId = filter_input(INPUT_GET, 'goal_id');
        $nickname = filter_input(INPUT_GET, 'nickname');
        $role = filter_input(INPUT_GET, 'role');
        
        $this->assign("goalId", $goalId);
        $this->assign("nickname", $nickname);
        $this->assign("role", $role);
        $this->display();
    }
    
    public function screen() {
        $this->display();
    }
}