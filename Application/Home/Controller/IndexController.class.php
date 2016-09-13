<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller {
    public function index(){
        $tradeStatus = filter_input(INPUT_GET, 'trade_status');
        
        $this->assign("tradeStatus", $tradeStatus);
        $this->display();
    }
    
    public function screen() {
        $this->display();
    }
    
    public function hideseek_m() {
        $tradeStatus = filter_input(INPUT_GET, 'trade_status');
        $goalId = filter_input(INPUT_GET, 'goal_id');
        
        $this->assign("tradeStatus", $tradeStatus);
        $this->assign("goalId", $goalId);
        $this->display();
    }
    
    public function haha() {
        echo "haha";
    }
}