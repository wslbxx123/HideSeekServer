<?php

namespace Home\Controller;
use Home\Common\Util\BaseUtil;
use Home\Common\Param\CodeParam;
use Home\DataAccess\StoreManager;
use Home\DataAccess\PullVersionManager;
use Home\DataAccess\OrderManager;
use Home\BusinessLogic\Network\ApiManager;
vendor("Alipay.aop.AopClient");

class StoreController extends BaseController {
    public function refreshProducts() {
        self::setHeader();
        
        $version = filter_input(INPUT_POST, 'version');
        $productMinId = filter_input(INPUT_POST, 'product_min_id');
        
        $store_version = PullVersionManager::getStoreVersion();
        
        if(!isset($version) || !isset($productMinId)) {
            BaseUtil::echoJson(CodeParam::VERSION_OR_MIN_ID_EMPTY, null);
            return;
        }
        
        $productList = StoreManager::refreshProducts($version, $productMinId);
        
        if($productList != null && count($productList) > 0) {
            $tempProductMinId = end($productList)['pk_id'];
            
            if($tempProductMinId < $productMinId) {
                $productMinId = $tempProductMinId;
            }
        }
        
        $result = array (
                'version' => $store_version,
                'product_min_id' => $productMinId,
                'products' => $productList);
        
        BaseUtil::echoJson(CodeParam::SUCCESS, $result);
    }
    
    public function getProducts() {
        self::setHeader();
        
        $version = filter_input(INPUT_POST, 'version');
        $productMinId = filter_input(INPUT_POST, 'product_min_id');
        
        if(!isset($version) || !isset($productMinId)) {
            BaseUtil::echoJson(CodeParam::VERSION_OR_MIN_ID_EMPTY, null);
            return;
        }
        
        $productList = StoreManager::getProducts($version, $productMinId);
        
        $result = array (
                'version' => $version,
                'product_min_id' => $productMinId,
                'products' => $productList);
        BaseUtil::echoJson(CodeParam::SUCCESS, $result);
    }
    
    public function refreshReward() {
        self::setHeader();
        
        $version = filter_input(INPUT_POST, 'version');
        $rewardMinId = filter_input(INPUT_POST, 'reward_min_id');
        
        $reward_version = PullVersionManager::getRewardVersion();
        
        if(!isset($version) || !isset($rewardMinId)) {
            BaseUtil::echoJson(CodeParam::VERSION_OR_MIN_ID_EMPTY, null);
            return;
        }
        
        $rewardList = StoreManager::refreshRewards($version, $rewardMinId);
        
        if($rewardList != null && count($rewardList) > 0) {
            $tempRewardMinId = end($rewardList)['pk_id'];
            
            if($tempRewardMinId < $rewardMinId) {
                $rewardMinId = $tempRewardMinId;
            }
        }
        
        $result = array (
                'version' => $reward_version,
                'reward_min_id' => $rewardMinId,
                'reward' => $rewardList);
        
        echo BaseUtil::echoJson(CodeParam::SUCCESS, $result); 
    }
    
    public function getReward() {
        self::setHeader();
        
        $version = filter_input(INPUT_POST, 'version');
        $rewardMinId = filter_input(INPUT_POST, 'reward_min_id');
        
        if(!isset($version) || !isset($rewardMinId)) {
            BaseUtil::echoJson(CodeParam::VERSION_OR_MIN_ID_EMPTY, null);
            return;
        }
        
        $rewardList = StoreManager::getRewards($version, $rewardMinId);
        
        $result = array (
                'version' => $version,
                'reward_min_id' => $rewardMinId,
                'reward' => $rewardList);
        echo BaseUtil::echoJson(CodeParam::SUCCESS, $result); 
    }
    
    public function createOrder() {
        self::setHeader();
        
        $sessionId = filter_input(INPUT_POST, 'session_id');
        $storeId = filter_input(INPUT_POST, 'store_id');
        $count = filter_input(INPUT_POST, 'count');
        
        $accountId = $this->getPkIdFromToken($sessionId);
        
        if(!isset($sessionId) || $accountId == 0) {
            BaseUtil::echoJson(CodeParam::NOT_LOGIN, null);
            return;
        }
        
        if(!self::checkOrderInfo($storeId, $count)) {
            return;
        }
       
        $product = StoreManager::getProduct($storeId);
        $tradeNo = ApiManager::generateTradeNo(5);
        $rsaSign = ApiManager::rsaSign($product['product_name'], $product['introduction'],
                floatval($product['price']) * $count, $tradeNo);
        
        $orderId = OrderManager::insertOrder($storeId, $accountId, $count, $tradeNo);
        
        $result = Array("order_id" => $orderId, "sign" => $rsaSign, 
            "trade_no" => $tradeNo);
        echo BaseUtil::echoJson(CodeParam::SUCCESS, $result); 
    }
    
    public function checkOrderInfo($storeId, $count) {    
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
    
    public function purchase() {
        self::setHeader();
        
        $sessionId = filter_input(INPUT_POST, 'session_id');
        $orderId = filter_input(INPUT_POST, 'order_id');
        $accountId = $this->getPkIdFromToken($sessionId);
        
        if(!isset($sessionId) || $accountId == 0) {
            BaseUtil::echoJson(CodeParam::NOT_LOGIN, null);
            return;
        }
        
        if(!isset($orderId)) {
            BaseUtil::echoJson(CodeParam::ORDER_ID_EMPTY, null);
            return;
        }
        
        $order = OrderManager::updateOrder($orderId, 1);
        
        if($order == null) {
            BaseUtil::echoJson(CodeParam::ORDER_ID_WRONG, null);
            return;
        }
              
        StoreManager::updatePurchaseCount($order['store_id']);
        PullVersionManager::updateStoreVersion();
        
        echo BaseUtil::echoJson(CodeParam::SUCCESS, $orderId); 
    }
    
    public function refreshOrders() {
        self::setHeader();
        
        $sessionId = filter_input(INPUT_POST, 'session_id');
        $version = filter_input(INPUT_POST, 'version');
        $orderMinId = filter_input(INPUT_POST, 'order_min_id');
        $accountId = $this->getPkIdFromToken($sessionId);
        
        if(!isset($sessionId) || $accountId == 0) {
            BaseUtil::echoJson(CodeParam::NOT_LOGIN, null);
            return;
        }
        
        if(!isset($version) || !isset($orderMinId)) {
            BaseUtil::echoJson(CodeParam::VERSION_OR_MIN_ID_EMPTY, null);
            return;
        }
        
        $orderList = OrderManager::refreshOrders($accountId, $version, $orderMinId);
        
        echo BaseUtil::echoJson(CodeParam::SUCCESS, $orderList); 
    }
    
    public function getOrders() {
        self::setHeader();
        
        $sessionId = filter_input(INPUT_POST, 'session_id');
        $version = filter_input(INPUT_POST, 'version');
        $orderMinId = filter_input(INPUT_POST, 'order_min_id');
        $accountId = $this->getPkIdFromToken($sessionId);
        
        if(!isset($sessionId) || $accountId == 0) {
            BaseUtil::echoJson(CodeParam::NOT_LOGIN, null);
            return;
        }
        
        if(!isset($version) || !isset($orderMinId)) {
            BaseUtil::echoJson(CodeParam::VERSION_OR_MIN_ID_EMPTY, null);
            return;
        }
        
        $orderList = OrderManager::getOrders($accountId, $version, $orderMinId);
        
        echo BaseUtil::echoJson(CodeParam::SUCCESS, $orderList); 
    }
    
    public function notifyUrl() {
        $param = filter_input(INPUT_POST);
        $sign = filter_input(INPUT_POST, 'sign');
        $notifyId = filter_input(INPUT_POST, 'notify_id');
        $tradeStatus = filter_input(INPUT_POST, 'trade_status');
        $tradeNo = filter_input(INPUT_POST, 'trade_no');
        
        $verifyResult = ApiManager::verifyNotify($param, $sign, $notifyId);
        
        if($verifyResult) {
            OrderManager::updateOrderVerifyStatus($tradeNo, $tradeStatus);
        }
    }
    
    public function test() {
        $rsaSign = ApiManager::rsaSign("怪兽图鉴", "可获得怪兽信息，并包含拿下怪兽的规则。",
                2.0, "YKMHR1462636800");
        echo "怪兽图鉴";
        echo $rsaSign;
    }
}