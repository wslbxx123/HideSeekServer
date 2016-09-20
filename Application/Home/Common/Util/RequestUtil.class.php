<?php

namespace Home\Common\Util;

/**
 * 请求操作类
 *
 * @author Two
 */
class RequestUtil {
    function isMobile($server) {
        // 如果有HTTP_X_WAP_PROFILE则一定是移动设备
        if (isset ($server['HTTP_X_WAP_PROFILE'])) {
            return true;
        }
    
        //此条摘自TPM智能切换模板引擎，适合TPM开发
        if(isset ($server['HTTP_CLIENT']) && 'PhoneClient'== $server['HTTP_CLIENT']) {
            return true;
        }
        
        //如果via信息含有wap则一定是移动设备,部分服务商会屏蔽该信息
        if (isset ($server['HTTP_VIA'])) {
            return stristr($server['HTTP_VIA'], 'wap') ? true : false;
        }
        
        if(self::checkClientFlag($server)) {
            return true;
        }     
        
        //协议法，因为有可能不准确，放到最后判断
        if (isset ($server['HTTP_ACCEPT'])) {
            // 如果只支持wml并且不支持html那一定是移动设备
            // 如果支持wml和html但是wml在html之前则是移动设备
            if ((strpos($server['HTTP_ACCEPT'], 'vnd.wap.wml') !== false) && 
                    (strpos($_SERVER['HTTP_ACCEPT'], 'text/html') === false || (strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') < strpos($_SERVER['HTTP_ACCEPT'], 'text/html')))) {
                return true;
            }
        }
        return false;
    }
    
    //判断手机发送的客户端标志,兼容性有待提高
    function checkClientFlag($server) {
        if (isset ($server['HTTP_USER_AGENT'])) {
            $clientkeywords = array(
                'nokia','sony','ericsson','mot','samsung','htc','sgh','lg','sharp','sie-','philips','panasonic','alcatel','lenovo','iphone','ipod','blackberry','meizu','android','netfront','symbian','ucweb','windowsce','palm','operamini','operamobi','openwave','nexusone','cldc','midp','wap','mobile'
            );
            //从HTTP_USER_AGENT中查找手机浏览器的关键字
            if (preg_match("/(" . implode('|', $clientkeywords) . ")/i", 
                    strtolower($_SERVER['HTTP_USER_AGENT']))) {
                return true;
            }
        }
    }
}
