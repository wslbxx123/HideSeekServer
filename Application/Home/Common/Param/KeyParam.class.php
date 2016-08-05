<?php
namespace Home\Common\Param;

/**
 * 支付宝的基本配置
 *
 * @author Two
 */
class KeyParam {
    const ALIPAY_PARTNER = "2088421519055042";
    const ALIPAY_PRIVATE_KEY_PATH = "Application/Resources/key/rsa_private_key.pem";
    const ALIPAY_PUBLIC_KEY_PATH = "Application/Resources/key/rsa_public_key.pem";
    const ALIPAY_CACERT_PATH = "Application/Resources/key/cacert.pem";
    
    const SMS_KEY = "1ceb0303d0a985b2a64ae84f023481c5";
}
