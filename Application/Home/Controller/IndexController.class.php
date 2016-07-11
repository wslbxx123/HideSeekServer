<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller {
    public function index(){
        $array = array ('code' => 10001, 'message' => $message,
            'result' => array (
                'version' => $version,
                'friends' => $friends));
        
        echo json_encode($array);
    }
}