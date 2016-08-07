<?php
namespace Home\BusinessLogic\Manager;

/**
 * 处理商城控制器的逻辑类
 *
 * @author Two
 */
class StoreControllerManager {
    public function __construct() {
        
    }
    
    public function getSignResult($storeId, $count, $accountId, $isFromWeb) {
        $orderVersion = PullVersionManager::updateOrderVersion();
        $product = StoreManager::getProduct($storeId);
        $tradeNo = ApiManager::generateTradeNo(5);
        
        if($isFromWeb) {
            $signResult = ApiManager::rsaWebSign($product['product_name'], 
                $product['introduction'],
                floatval($product['price']) * $count, $tradeNo);
        } else {
            $signResult = ApiManager::rsaSign($product['product_name'], 
                $product['introduction'],
                floatval($product['price']) * $count, $tradeNo);
        }
        
        $orderId = OrderManager::insertOrder($storeId, $accountId, $count, 
                $tradeNo, $orderVersion);
        
        $result = Array("order_id" => $orderId, "sign" => $signResult["sign"], 
            "trade_no" => $tradeNo, "params" => $signResult["params"]);
        
        return $result;
    }
    
    public function createAlipayFormHtml($result) {
        $param = $result["params"];
        $param['sign'] = $result["sign"];
        $param['sign_type'] = self::SIGN_TYPE;
        
        $html = "<form id='alipaysubmit' name='alipaysubmit' "
                . "action='".self::ALIPAY_GATEWAY_NEW
                ."_input_charset=utf-8 method='get'>";
        
        while (list ($key, $val) = each($result['params'])) {
            $html .= "<input type='hidden' name='".$key."' value='".$val."'/>";
        }
        
        $html = $html."<input type='submit' value='confirm' style='display:none;'></form>";
        
        return $html;
    }
}
