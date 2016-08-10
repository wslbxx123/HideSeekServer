<?php

namespace Home\Controller;
use Home\Common\Util\BaseUtil;
use Home\Common\Param\CodeParam;
use Home\DataAccess\StoreManager;
use Home\DataAccess\RewardManager;
use Home\DataAccess\PullVersionManager;
use Home\DataAccess\PurchaseOrderManager;
use Home\DataAccess\ExchangeOrderManager;
use Home\BusinessLogic\Network\ApiManager;
use Home\BusinessLogic\Manager\StoreControllerManager;
vendor("Alipay.aop.AopClient");

class StoreController extends BaseController {
   
    public function refreshProducts() {
        self::setHeader();
        
        $version = filter_input(INPUT_POST, 'version');
        $productMinId = filter_input(INPUT_POST, 'product_min_id');
        
        $storeVersion = PullVersionManager::getStoreVersion();
        
        if(!isset($version) || !isset($productMinId)) {
            BaseUtil::echoJson(CodeParam::VERSION_OR_MIN_ID_EMPTY, null);
            return;
        }
        
        $productResult = StoreManager::refreshProducts($version, $productMinId);
        
        $result = array (
                'version' => $storeVersion,
                'product_min_id' => $productResult["product_min_id"],
                'products' => $productResult["products"]);
        
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
        
        $productResult = StoreManager::getProducts($version, $productMinId);
        
        $result = array (
                'version' => $version,
                'product_min_id' => $productResult["product_min_id"],
                'products' => $productResult["products"]);
        BaseUtil::echoJson(CodeParam::SUCCESS, $result);
    }
    
    public function refreshReward() {
        self::setHeader();
        
        $version = filter_input(INPUT_POST, 'version');
        $rewardMinId = filter_input(INPUT_POST, 'reward_min_id');
        
        $rewardVersion = PullVersionManager::getRewardVersion();
        
        if(!isset($version) || !isset($rewardMinId)) {
            BaseUtil::echoJson(CodeParam::VERSION_OR_MIN_ID_EMPTY, null);
            return;
        }
        
        $rewardResult = RewardManager::refreshRewards($version, $rewardMinId);
        
        $result = array (
                'version' => $rewardVersion,
                'reward_min_id' => $rewardResult["reward_min_id"],
                'reward' => $rewardResult["rewards"]);
        
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
        
        $rewardResult = RewardManager::getRewards($version, $rewardMinId);
        
        $result = array (
                'version' => $version,
                'reward_min_id' => $rewardResult["reward_min_id"],
                'reward' => $rewardResult["rewards"]);
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
        
        $result = StoreControllerManager::getSignResult($storeId, $count, $accountId, false);
        echo BaseUtil::echoJson(CodeParam::SUCCESS, $result); 
    }
    
    public function createOrderFromWeb() {
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
        
        $result = StoreControllerManager::getSignResult($storeId, $count, $accountId, true);
        $html = StoreControllerManager::createAlipayFormHtml($result);
        echo $html;
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
    
    public function refreshPurchaseOrders() {
        self::setHeader();
        
        $sessionId = filter_input(INPUT_POST, 'session_id');
        $version = filter_input(INPUT_POST, 'version');
        $orderMinId = filter_input(INPUT_POST, 'order_min_id');
        $accountId = $this->getPkIdFromToken($sessionId);
        
        $orderVersion = PullVersionManager::getProductOrderVersion();
        
        if(!isset($sessionId) || $accountId == 0) {
            BaseUtil::echoJson(CodeParam::NOT_LOGIN, null);
            return;
        }
        
        if(!isset($version) || !isset($orderMinId)) {
            BaseUtil::echoJson(CodeParam::VERSION_OR_MIN_ID_EMPTY, null);
            return;
        }
        
        $orderResult = PurchaseOrderManager::refreshOrders($accountId, $version, $orderMinId);
        
        $result = array ('version' => $orderVersion, 
            'order_min_id' => $orderResult["order_min_id"],
            'orders' => $orderResult["orders"]);
        
        echo BaseUtil::echoJson(CodeParam::SUCCESS, $result); 
    }
    
    public function getPurchaseOrders() {
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
        
        $orderResult = PurchaseOrderManager::getOrders($accountId, $version, $orderMinId);
        
        $result = array ('version' => $version, 
            'order_min_id' => $orderResult["order_min_id"],
            'orders' => $orderResult["orders"]);
        
        echo BaseUtil::echoJson(CodeParam::SUCCESS, $result); 
    }
    
    public function refreshExchangeOrders() {
        self::setHeader();
        
        $sessionId = filter_input(INPUT_POST, 'session_id');
        $version = filter_input(INPUT_POST, 'version');
        $orderMinId = filter_input(INPUT_POST, 'order_min_id');
        $accountId = $this->getPkIdFromToken($sessionId);
        
        $orderVersion = PullVersionManager::getRewardOrderVersion();
        
        if(!isset($sessionId) || $accountId == 0) {
            BaseUtil::echoJson(CodeParam::NOT_LOGIN, null);
            return;
        }
        
        if(!isset($version) || !isset($orderMinId)) {
            BaseUtil::echoJson(CodeParam::VERSION_OR_MIN_ID_EMPTY, null);
            return;
        }
        
        $orderResult = ExchangeOrderManager::refreshOrders($accountId, $version, $orderMinId);
        
        $result = array ('version' => $orderVersion, 
            'order_min_id' => $orderResult["order_min_id"],
            'orders' => $orderResult["orders"]);
        
        echo BaseUtil::echoJson(CodeParam::SUCCESS, $result); 
    }
    
    public function getExchangeOrders() {
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
        
        $orderResult = ExchangeOrderManager::getOrders($accountId, $version, $orderMinId);
        
        $result = array ('version' => $version, 
            'order_min_id' => $orderResult["order_min_id"],
            'orders' => $orderResult["orders"]);
        
        echo BaseUtil::echoJson(CodeParam::SUCCESS, $result); 
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