<?php
namespace Home\Controller;
use Home\Controller\BaseController;
use Home\Common\Util\RequestUtil;

class IndexController extends BaseController {
    public function index(){
        self::setHeader();
        $server = filter_input_array(INPUT_SERVER);
        $isMobile = RequestUtil::isMobile($server);
        
        if($isMobile) {
             header('Location: '.U('Mindex/index'));
             exit();
        }
        
        $tradeStatus = filter_input(INPUT_GET, 'trade_status');
        
        $this->assign("tradeStatus", $tradeStatus);
        $this->display();
    }
}