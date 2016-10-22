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
    
    const XG_IOS_ACCESS_ID = 2200218505;
    const XG_IOS_SECRET_KEY = "ae636fc29df1f602df4e2de998bf5a18";
    const XG_ANDROID_ACCESS_ID = 2100218504;
    const XG_ANDROID_SECRET_KEY = "f97a74f46e7cee16e0f16ee46f1ef411";
}
