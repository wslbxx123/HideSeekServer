<?php
namespace Home\Common\Util;
use Home\Common\Factory\MessageFactory;
/**
 * 基本设置
 *
 * @author Two
 */
class BaseUtil {
    /**
     * 生成JSON字符串
     * @param type $code 数据获得情况代码
     * @param type $result  数据结果
     */
    public function echoJson($code, $result) {
        $message = MessageFactory::get($code);
        $array = array ('code' => $code, 'message' => $message, 
            'result' => $result);
        
        echo json_encode($array);
    }
}
