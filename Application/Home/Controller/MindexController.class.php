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
        
        $tradeStatus = filter_input(INPUT_GET, 'trade_status');
        $sessionId = filter_input(INPUT_GET, 'session_id');
        
        $this->assign("tradeStatus", $tradeStatus);
        $this->assign("sessionId", $sessionId);
        $this->display();
    }
    
    public function screen() {
        $this->display();
    }
}