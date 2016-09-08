<?php
namespace Home\BusinessLogic\Manager;
use Home\DataAccess\PullVersionManager;
use Home\DataAccess\ProductManager;
use Home\DataAccess\RewardManager;
use Home\DataAccess\PurchaseOrderManager;
use Home\BusinessLogic\Network\AlipayManager;
use Home\Common\Param\CodeParam;
use Home\Common\Util\BaseUtil;
/**
 * 处理商城控制器的逻辑类
 *
 * @author Two
 */
class StoreControllerManager {
    const ALIPAY_GATEWAY_NEW = 'https://mapi.alipay.com/gateway.do?';
    const SIGN_TYPE = "RSA";
    
    public function getSignResultFromType($type, $product, $count, $tradeNo) {
        switch($type) {
            case 0:
                $signResult = AlipayManager::rsaSign($product['product_name'], 
                $product['introduction'],
                floatval($product['price']) * $count, $tradeNo);
                break;
            case 1:
                $signResult = AlipayManager::rsaWebSign($product['product_name'], 
                $product['introduction'],
                floatval($product['price']) * $count, $tradeNo);
                break;
            case 2:
                $signResult = AlipayManager::rsaH5WebSign($product['product_name'], 
                $product['introduction'],
                floatval($product['price']) * $count, $tradeNo);
                break;
        }
        return $signResult;
    }
    
    public function getSignResult($storeId, $count, $accountId, $type) {
        $orderVersion = PullVersionManager::updateProductOrderVersion();
        $product = ProductManager::getProduct($storeId);
        $tradeNo = AlipayManager::generateTradeNo(5);
        
        $signResult = self::getSignResultFromType($type, $product, $count, $tradeNo);
        
        $orderId = PurchaseOrderManager::insertOrder($storeId, $accountId, $count, 
                $tradeNo, $orderVersion);
        
        $result = Array("order_id" => $orderId, "sign" => $signResult["sign"], 
            "trade_no" => $tradeNo, "params" => $signResult["params"]);
        
        return $result;
    }
    
    public function getSignResultWithoutCreateOrder($storeId, $count, 
            $type, $orderId) {
        $product = ProductManager::getProduct($storeId);
        $tradeNo = AlipayManager::generateTradeNo(5);
        
        $signResult = self::getSignResultFromType($type, $product, $count, $tradeNo);
        
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
    
    public function checkExchangeOrderInfo($rewardId, $count, $account) {   
        if(!isset($rewardId)) {
            BaseUtil::echoJson(CodeParam::REWARD_ID_EMPTY, null);
            return false;
        }
        
        if(!isset($count)) {
            BaseUtil::echoJson(CodeParam::COUNT_EMPTY, null);
            return false;
        }
        
        $reward = RewardManager::getReward($rewardId);
        
        if(intval($reward['record']) * $count > $account['record']) {
            BaseUtil::echoJson(CodeParam::RECORD_NOT_ENOUGH, null);
            return false;
        }
        
        return true;
    }
    
    public function checkPurchaseInfo($sessionId, $accountId, $orderId) {
        if(!isset($sessionId) || $accountId == 0) {
            BaseUtil::echoJson(CodeParam::NOT_LOGIN, null);
            return false;
        }
        
        if(!isset($orderId)) {
            BaseUtil::echoJson(CodeParam::ORDER_ID_EMPTY, null);
            return false;
        }
        
        return true;
    }
}
