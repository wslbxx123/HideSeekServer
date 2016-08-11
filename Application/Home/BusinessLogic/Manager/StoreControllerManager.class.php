<?php
namespace Home\BusinessLogic\Manager;
use Home\DataAccess\PullVersionManager;
use Home\DataAccess\ProductManager;
use Home\DataAccess\PurchaseOrderManager;
use Home\BusinessLogic\Network\ApiManager;
use Home\Common\Param\CodeParam;
/**
 * 处理商城控制器的逻辑类
 *
 * @author Two
 */
class StoreControllerManager {
    const ALIPAY_GATEWAY_NEW = 'https://mapi.alipay.com/gateway.do?';
    const SIGN_TYPE = "RSA";
    
    public function getSignResult($storeId, $count, $accountId, $isFromWeb) {
        $orderVersion = PullVersionManager::updateProductOrderVersion();
        $product = ProductManager::getProduct($storeId);
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
        
        $orderId = PurchaseOrderManager::insertOrder($storeId, $accountId, $count, 
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
                ."_input_charset=utf-8' method='get'>";
        
        while (list ($key, $val) = each($param)) {
            $html .= "<input type='hidden' name='".$key."' value='".$val."'/>";
        }
        
        $html = $html."<input type='submit' value='confirm' style='display:none;'></form>";
        
        return $html;
    }
    
    public function checkPurchaseOrderInfo($storeId, $count) {   
        if(!isset($storeId)) {
            BaseUtil::echoJson(CodeParam::STORE_ID_EMPTY, null);
            return false;
        }
        
        if(!isset($count)) {
            BaseUtil::echoJson(CodeParam::COUNT_EMPTY, null);
            return false;
        }
        
        return true;
    }
    
    public function checkPurchaseOrderInfo($rewardId, $count) {   
        if(!isset($rewardId)) {
            BaseUtil::echoJson(CodeParam::REWARD_ID_EMPTY, null);
            return false;
        }
        
        if(!isset($count)) {
            BaseUtil::echoJson(CodeParam::COUNT_EMPTY, null);
            return false;
        }
        
        return true;
    }
}
