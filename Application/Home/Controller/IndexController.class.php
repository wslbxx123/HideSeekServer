<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller {
    public function index(){
        $this->display();
    }
    
    public function screen() {
        $this->display();
    }
    
    public function hideseek_m() {
        $this->display();
    }
}