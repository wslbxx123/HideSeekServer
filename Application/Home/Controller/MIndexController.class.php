<?php
namespace Home\Controller;
use Home\Controller\BaseController;

/**
 * M站的控制器
 *
 * @author apple
 */
class MIndexController extends BaseController{
    public function index() {
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
    
    public function screen() {
        $this->display();
    }
}
