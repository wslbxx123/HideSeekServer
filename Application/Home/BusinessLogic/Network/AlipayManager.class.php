<?php
namespace Home\BusinessLogic\Network;
use Home\Common\Util\FileUtil;
use Home\Common\Util\BaseUtil;
use Home\Common\Param\KeyParam;
vendor("Alipay.aop.AopClient");
/**
 * 第三方api操作类
 *
 * @author Two
 */
class AlipayManager {
    const ALIPAY_VERIFY_URL = 
            'https://mapi.alipay.com/gateway.do?service=notify_verify&';
    
    /**
    * 请求接口返回内容
    * @param  string $url [请求的URL地址]
    * @param  string $params [请求的参数]
    * @param  int $isPost [是否采用POST形式]
    * @return  string
    */
    function getHttpResponse($url, $params = false, $isPost = 0) {
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
    
    public function getAopClient() {
        $client = new \AopClient();
        $client->gatewayUrl = "https://openapi.alipay.com/gateway.do";
        $client->appId = KeyParam::ALIPAY_PARTNER;
        $client->rsaPrivateKeyFilePath = KeyParam::ALIPAY_PRIVATE_KEY_PATH;
        $client->format = "json";
        $client->charset = "utf-8";
        $client->alipayPublicKey = FileUtil::getAlipayKey
                (KeyParam::ALIPAY_PUBLIC_KEY_PATH);
        
        return $client;
    }
    
    public function rsaSign($productName, $introduction, $amount, $tradeNo) {
        $client = self::getAopClient();
        
        $params["service"] = "\""."mobile.securitypay.pay"."\"";
        $params["partner"] = "\"".KeyParam::ALIPAY_PARTNER."\"";
        $params["_input_charset"] = "\""."utf-8"."\"";
        $params["notify_url"] = "\""."http://www.hideseek.cn/index.php/home/store/notifyUrl"."\"";
        $params["out_trade_no"] = "\"".$tradeNo."\"";
        $params["subject"] = "\"".$productName."\"";
        $params["payment_type"] = "\""."1"."\"";
        $params["seller_id"] = "\""."wslbxx@hotmail.com"."\"";
        $params["total_fee"] = "\"".sprintf("%.2f", $amount)."\"";
        $params["body"] = "\"".$introduction."\"";
        $params["it_b_pay"] = "\""."30m"."\"";
        $params["show_url"] = "\""."m.alipay.com"."\"";
        
        return Array(
            "sign" => urlencode($client->rsaSign($params)), 
            "params" => $params);
    }
    
    public function rsaWebSign($productName, $introduction, $amount, $tradeNo) {
        $client = self::getAopClient();
        
        $params["service"] = "create_direct_pay_by_user";
        $params["partner"] = KeyParam::ALIPAY_PARTNER;
        $params["_input_charset"] = "utf-8";
        $params["notify_url"] = "http://www.hideseek.cn/index.php/home/store/notifyUrl";
        $params["return_url"] = "https://www.hideseek.cn/";
        $params["out_trade_no"] = $tradeNo;
        $params["subject"] = $productName;
        $params["payment_type"] = "1";
        $params["seller_id"] = KeyParam::ALIPAY_PARTNER;
        $params["total_fee"] = sprintf("%.2f", $amount);
        $params["body"] = $introduction;
        $params["it_b_pay"] = "30m";
        $params["show_url"] = "m.alipay.com";
        $sortParams = BaseUtil::paramSort($params);
        
        return Array(
            "sign" => $client->rsaSign($sortParams), 
            "params" => $sortParams);
    }
    
    public function rsaH5WebSign($productName, $introduction, $amount, $tradeNo) {
        $client = self::getAopClient();
        
        $params["service"] = "alipay.wap.create.direct.pay.by.user";
        $params["partner"] = KeyParam::ALIPAY_PARTNER;
        $params["_input_charset"] = "utf-8";
        $params["notify_url"] = "http://www.hideseek.cn/index.php/home/store/notifyUrl";
        $params["return_url"] = "https://m.hideseek.cn/";
        $params["out_trade_no"] = $tradeNo;
        $params["subject"] = $productName;
        $params["payment_type"] = "1";
        $params["seller_id"] = KeyParam::ALIPAY_PARTNER;
        $params["total_fee"] = sprintf("%.2f", $amount);
        $params["body"] = $introduction;
        $params["it_b_pay"] = "30m";
        $params["show_url"] = "m.alipay.com";
        $sortParams = BaseUtil::paramSort($params);
        
        return Array(
            "sign" => $client->rsaSign($sortParams), 
            "params" => $sortParams);
    }
    
    public function generateTradeNo($length) {
        $randomStr = null;
        $sourceStr = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $max = strlen($sourceStr) - 1;
        
        for ($i = 0; $i < $length; $i++)
	{
            $randomStr .= $sourceStr[rand(0, $max)];
	}
        
        return $randomStr.strtotime(date ("Y-m-d h:i:s"));
    }
    
    /**
     * 获取返回时的签名验证结果
     * @param $param 通知返回来的参数数组
     * @param $sign 返回的签名结果
     * @return 签名验证结果
     */
    public function verifyNotify($param, $sign, $notifyId) {
        $paramFilter = BaseUtil::paramFilter($param);
        
        $paramSort = BaseUtil::paramSort($paramFilter);
        $paramStr = http_build_query($paramSort);
        
        $isSign = self::rsaVerify($paramStr, 
                            trim(KeyParam::ALIPAY_PUBLIC_KEY_PATH), $sign);

        $responseText = 'false';
        if(isset($notifyId)) {
            $responseText = self::getAlipayResponse($notifyId);
        }

        return preg_match("/true$/i", $responseText) && $isSign;
    }
    
    /**
    * RSA验签
    * @param $data 待签名数据
    * @param $publicKeyPath 支付宝的公钥文件路径
    * @param $sign 要校对的的签名结果
    * return 验证结果
    */
    function rsaVerify($data, $publicKeyPath, $sign)  {
       $publicKey = file_get_contents($publicKeyPath);
       $res = openssl_get_publickey($publicKey);
       $result = (bool)openssl_verify($data, base64_decode($sign), $res);
       openssl_free_key($res);    
       return $result;
    }
    
    /**
     * 获取远程服务器ATN结果,验证返回URL
     * @param $notifyId 通知校验ID
     * @return 服务器ATN结果
     * 验证结果集：
     * invalid命令参数不对 出现这个错误，请检测返回处理中partner和key是否为空 
     * true 返回正确信息
     * false 请检查防火墙或者是服务器阻止端口问题以及验证时间是否超过一分钟
     */
    function getAlipayResponse($notifyId) {
        $partner = KeyParam::ALIPAY_PARTNER;
        $verifyUrl = self::ALIPAY_VERIFY_URL."partner=" . $partner . 
                "&notify_id=" . $notifyId;
        
        $responseText = self::getHttpResponseGET($verifyUrl, 
                KeyParam::ALIPAY_CACERT_PATH);

        return $responseText;
    }
    
    /**
    * 远程获取数据，GET模式
    * @param $url 指定URL完整路径地址
    * @param $cacert_url 指定当前工作目录绝对路径
    * return 远程输出的数据
    */
   function getHttpResponseGET($url, $cacert_url) {
           $curl = curl_init($url);
           curl_setopt($curl, CURLOPT_HEADER, 0 ); // 过滤HTTP头
           curl_setopt($curl,CURLOPT_RETURNTRANSFER, 1);// 显示输出结果
           curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);//SSL证书认证
           curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);//严格认证
           curl_setopt($curl, CURLOPT_CAINFO,$cacert_url);//证书地址
           $responseText = curl_exec($curl);
           //var_dump( curl_error($curl) );//如果执行curl过程中出现异常，可打开此开关，以便查看异常内容
           curl_close($curl);

           return $responseText;
   }
}
