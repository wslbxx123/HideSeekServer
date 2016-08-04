<?php
namespace Home\BusinessLogic\Network;
use Home\Common\Util\FileUtil;
use Home\Common\Param\KeyParam;
vendor("Alipay.aop.AopClient");
/**
 * 第三方api操作类
 *
 * @author Two
 */
class ApiManager {
    
    /**
    * 请求接口返回内容
    * @param  string $url [请求的URL地址]
    * @param  string $params [请求的参数]
    * @param  int $isPost [是否采用POST形式]
    * @return  string
    */
    function juhecurl($url, $params = false, $isPost = 0) {
        $curl = self::setUpCurl($url, $params, $isPost);
        $response = curl_exec($curl);
        if ($response === FALSE) {
            return false;
        }
        curl_close($curl);
        return $response;
    }
    
    function setUpCurl($url, $params, $isPost) {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($curl, CURLOPT_USERAGENT, 
                'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/537.22 '
                . '(KHTML, like Gecko) Chrome/25.0.1364.172 Safari/537.22');
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT , 30);
        curl_setopt($curl, CURLOPT_TIMEOUT , 30);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER , true);
        if($isPost)
        {
            curl_setopt($curl, CURLOPT_POST , true);
            curl_setopt($curl, CURLOPT_POSTFIELDS , $params);
            curl_setopt($curl, CURLOPT_URL , $url);
        }
        else
        {
            curl_setopt($curl, CURLOPT_URL, 
                    $params? $url.'?'.http_build_query($params): $url);
        }
        return $curl;
    }
    
    public function rsaSign($productName, $amount) {
        $client = new \AopClient();
        $client->gatewayUrl = "https://openapi.alipay.com/gateway.do";
        $client->appId = KeyParam::ALIPAY_PARTNER;
        $client->rsaPrivateKeyFilePath = KeyParam::ALIPAY_PRIVATE_KEY_PATH;
        $client->format = "json";
        $client->charset = "utf-8";
        $client->alipayPublicKey = FileUtil::getAlipayKey(KeyParam::ALIPAY_PUBLIC_KEY_PATH);
        
        $params["service"] = "mobile.securitypay.pay";
        $params["partner"] = KeyParam::ALIPAY_PARTNER;
        $params["_input_charset"] = "utf-8";
        $params["notify_url"] = "http://notify.msp.hk/notify.htm";
        $params["out_trade_no"] = self::generateTradeNo(15);
        $params["subject"] = $productName;
        $params["payment_type"] = "1";
        $params["seller_id"] = "wslbxx@hotmail.com";
        $params["total_fee"] = $amount;
        return $client->rsaSign($params);
    }
    
    public function generateTradeNo($length) {
        $randomStr = null;
        $sourceStr = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $max = strlen($sourceStr) - 1;
        
        for ($i = 0; $i < $length; $i++)
	{
            $randomStr .= $sourceStr[rand(0, $max)];
	}
        
        return $randomStr;
    }
}
