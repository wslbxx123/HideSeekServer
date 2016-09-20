<?php
namespace Home\Controller;
use Home\Controller\BaseController;

class IndexController extends BaseController {
    public function index(){
//        self::setWebHeader();
        
        $tradeStatus = filter_input(INPUT_GET, 'trade_status');
        
        $this->assign("tradeStatus", $tradeStatus);
        $this->display();
    }
}