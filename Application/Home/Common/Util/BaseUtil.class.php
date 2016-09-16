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
    
    /**
     * 生成随机数
     * @param type $from 随机数最小值
     * @param type $to  随机数最大值
     * @return type 随机数
     */
    public function getRandomNum($from, $to) {
        return rand($from, $to);
    }
    
    /**
    * 除去数组中的空值和签名参数
    * @param $param 签名参数组
    * return 去掉空值与签名参数后的新签名参数组
    */
    public function paramFilter($param) {
	$param_filter = array();
	while (list ($key, $val) = each($param)) {
            if($key != "sign" && $key != "sign_type" && $val != "") {
                $param_filter[$key] = $param[$key];
            }	
	}
	return $param_filter;
    }
    
    /**
    * 对数组排序
    * @param $param 排序前的数组
    * return 排序后的数组
    */
    public function paramSort($param) {
        ksort($param);
        reset($param);
        return $param;
    }
    
    /**
     * 去除用户的敏感信息
     * @param type $account
     */
    public function removeSecretInfo($account) {
        $account['password'] = "";
        $account['session_token'] = "";
        $account['register_date'] = "";
        $account['channel_id'] = "";
        return $account;
    }
}
