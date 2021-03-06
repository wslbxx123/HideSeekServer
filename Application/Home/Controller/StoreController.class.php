<?php

namespace Home\Controller;
use Home\Common\Util\BaseUtil;
use Home\Common\Param\CodeParam;
use Home\DataAccess\ProductManager;
use Home\DataAccess\RewardManager;
use Home\DataAccess\PullVersionManager;
use Home\DataAccess\PurchaseOrderManager;
use Home\DataAccess\ExchangeOrderManager;
use Home\DataAccess\AccountManager;
use Home\BusinessLogic\Network\AlipayManager;
use Home\BusinessLogic\Manager\StoreControllerManager;
use Home\Common\Util\RequestUtil;

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
        
        $productResult = ProductManager::refreshProducts($version, $productMinId);
        
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
        
        $productResult = ProductManager::getProducts($version, $productMinId);
        
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
        
        BaseUtil::echoJson(CodeParam::SUCCESS, $result); 
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
        BaseUtil::echoJson(CodeParam::SUCCESS, $result); 
    }
    
    public function createOrder() {
        self::setHeader();
        
        $sessionId = filter_input(INPUT_POST, 'session_id');
        $storeId = filter_input(INPUT_POST, 'store_id');
        $count = filter_input(INPUT_POST, 'count');
        $appVersion = filter_input(INPUT_POST, 'app_version');
        $accountId = $this->getPkIdFromToken($sessionId, $appVersion);
        
        if(!isset($sessionId) || $accountId == 0) {
            BaseUtil::echoJson(CodeParam::NOT_LOGIN, null);
            return;
        }
        
        if(!StoreControllerManager::checkPurchaseOrderInfo($storeId, $count)) {
            return;
        }
        
        $result = StoreControllerManager::getSignResult($storeId, $count, 
                $accountId, 0);
        BaseUtil::echoJson(CodeParam::SUCCESS, $result); 
    }
    
    public function getPurchaseOrder() {
        self::setHeader();
        
        $sessionId = filter_input(INPUT_POST, 'session_id');
        $orderId = filter_input(INPUT_POST, 'order_id');
        $storeId = filter_input(INPUT_POST, 'store_id');
        $count = filter_input(INPUT_POST, 'count');
        $appVersion = filter_input(INPUT_POST, 'app_version');
        $accountId = $this->getPkIdFromToken($sessionId, $appVersion);
        
        if(!isset($sessionId) || $accountId == 0) {
            BaseUtil::echoJson(CodeParam::NOT_LOGIN, null);
            return;
        }
        
        if(!StoreControllerManager::checkPurchaseOrderInfoFromWeb($orderId, 
                $storeId, $count)) {
            return;
        }
        
        $result = StoreControllerManager::getSignResultWithoutCreateOrder(
                $storeId, $count, 0, $orderId);
        BaseUtil::echoJson(CodeParam::SUCCESS, $result); 
    }
    
    public function createOrderFromWeb() {
        self::setHeader();
        
        $sessionId = filter_input(INPUT_POST, 'session_id');
        $storeId = filter_input(INPUT_POST, 'store_id');
        $count = filter_input(INPUT_POST, 'count');
        $appVersion = filter_input(INPUT_POST, 'app_version');
        $accountId = $this->getPkIdFromToken($sessionId, $appVersion);
        
        if(!isset($sessionId) || $accountId == 0) {
            BaseUtil::echoJson(CodeParam::NOT_LOGIN, null);
            return;
        }
        
        if(!StoreControllerManager::checkPurchaseOrderInfo($storeId, $count)) {
            return;
        }
        
        $result = StoreControllerManager::getSignResult($storeId, $count, 
                $accountId, 1);
        $html = StoreControllerManager::createAlipayFormHtml($result);
        $result['html'] = $html;
        header("Content-Type: application/json; charset=utf-8");
        BaseUtil::echoJson(CodeParam::SUCCESS, $result);
    }
    
    public function getPurchaseOrderFromWeb() {
        self::setHeader();
        
        $sessionId = filter_input(INPUT_POST, 'session_id');
        $orderId = filter_input(INPUT_POST, 'order_id');
        $storeId = filter_input(INPUT_POST, 'store_id');
        $count = filter_input(INPUT_POST, 'count');
        $appVersion = filter_input(INPUT_POST, 'app_version');
        $accountId = $this->getPkIdFromToken($sessionId, $appVersion);
        
        if(!isset($sessionId) || $accountId == 0) {
            BaseUtil::echoJson(CodeParam::NOT_LOGIN, null);
            return;
        }
        
        if(!StoreControllerManager::checkPurchaseOrderInfoFromWeb($orderId, 
                $storeId, $count)) {
            return;
        }
        
        $result = StoreControllerManager::getSignResultWithoutCreateOrder(
                $storeId, $count, 1, $orderId);
        $html = StoreControllerManager::createAlipayFormHtml($result);
        $result['html'] = $html;
        header("Content-Type: application/json; charset=utf-8");
        BaseUtil::echoJson(CodeParam::SUCCESS, $result); 
    }
    
    public function createOrderFromH5() {
        self::setHeader();
        
        $sessionId = filter_input(INPUT_POST, 'session_id');
        $storeId = filter_input(INPUT_POST, 'store_id');
        $count = filter_input(INPUT_POST, 'count');
        $appVersion = filter_input(INPUT_POST, 'app_version');
        $accountId = $this->getPkIdFromToken($sessionId, $appVersion);
        
        if(!isset($sessionId) || $accountId == 0) {
            BaseUtil::echoJson(CodeParam::NOT_LOGIN, null);
            return;
        }
        
        if(!StoreControllerManager::checkPurchaseOrderInfo($storeId, $count)) {
            return;
        }
        
        $result = StoreControllerManager::getSignResult($storeId, $count, 
                $accountId, 2);
        $html = StoreControllerManager::createAlipayFormHtml($result);
        
        $result['html'] = $html;
        header("Content-Type: application/json; charset=utf-8");
        BaseUtil::echoJson(CodeParam::SUCCESS, $result);
    }
    
    public function getPurchaseOrderFromH5() {
        self::setHeader();
        
        $sessionId = filter_input(INPUT_POST, 'session_id');
        $orderId = filter_input(INPUT_POST, 'order_id');
        $storeId = filter_input(INPUT_POST, 'store_id');
        $count = filter_input(INPUT_POST, 'count');
        $appVersion = filter_input(INPUT_POST, 'app_version');
        $accountId = $this->getPkIdFromToken($sessionId, $appVersion);
        
        if(!isset($sessionId) || $accountId == 0) {
            BaseUtil::echoJson(CodeParam::NOT_LOGIN, null);
            return;
        }
        
        if(!StoreControllerManager::checkPurchaseOrderInfoFromWeb($orderId, 
                $storeId, $count)) {
            return;
        }
        
        $result = StoreControllerManager::getSignResultWithoutCreateOrder(
                $storeId, $count, 2, $orderId);
        $html = StoreControllerManager::createAlipayFormHtml($result);
        $result['html'] = $html;
        header("Content-Type: application/json; charset=utf-8");
        BaseUtil::echoJson(CodeParam::SUCCESS, $result); 
    }
    
    public function purchase() {
        self::setHeader();
        
        $sessionId = filter_input(INPUT_POST, 'session_id');
        $orderId = filter_input(INPUT_POST, 'order_id');
        $appVersion = filter_input(INPUT_POST, 'app_version');
        $accountId = $this->getPkIdFromToken($sessionId, $appVersion);
        
        if(!StoreControllerManager::checkPurchaseInfo($sessionId, $accountId, $orderId)) {
            return;
        }
        
        $orderVersion = PullVersionManager::updateProductOrderVersion();
        $order = PurchaseOrderManager::updateOrder($orderId, 1, $orderVersion);
        
        if($order == null) {
            BaseUtil::echoJson(CodeParam::ORDER_ID_WRONG, null);
            return;
        }
        
        $storeVersion = PullVersionManager::updateStoreVersion();
        ProductManager::updatePurchaseCount($order['store_id'], $storeVersion);
        $account = AccountManager::updateAccountAfterPurchase($orderId);
        
        $result = array ("bomb_num" => $account['bomb_num'],
                "has_guide" => $account['has_guide']);
        BaseUtil::echoJson(CodeParam::SUCCESS, $result); 
    }
    
    public function refreshPurchaseOrders() {
        self::setHeader();
        
        $sessionId = filter_input(INPUT_POST, 'session_id');
        $version = filter_input(INPUT_POST, 'version');
        $orderMinId = filter_input(INPUT_POST, 'order_min_id');
        $appVersion = filter_input(INPUT_POST, 'app_version');
        $accountId = $this->getPkIdFromToken($sessionId, $appVersion);
        
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
        
        BaseUtil::echoJson(CodeParam::SUCCESS, $result); 
    }
    
    public function getPurchaseOrders() {
        self::setHeader();
        
        $sessionId = filter_input(INPUT_POST, 'session_id');
        $version = filter_input(INPUT_POST, 'version');
        $orderMinId = filter_input(INPUT_POST, 'order_min_id');
        $appVersion = filter_input(INPUT_POST, 'app_version');
        $accountId = $this->getPkIdFromToken($sessionId, $appVersion);
        
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
        
        BaseUtil::echoJson(CodeParam::SUCCESS, $result); 
    }
    
    public function refreshExchangeOrders() {
        self::setHeader();
        
        $sessionId = filter_input(INPUT_POST, 'session_id');
        $version = filter_input(INPUT_POST, 'version');
        $orderMinId = filter_input(INPUT_POST, 'order_min_id');
        $appVersion = filter_input(INPUT_POST, 'app_version');
        $accountId = $this->getPkIdFromToken($sessionId, $appVersion);
        
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
        
        BaseUtil::echoJson(CodeParam::SUCCESS, $result); 
    }
    
    public function getExchangeOrders() {
        self::setHeader();
        
        $sessionId = filter_input(INPUT_POST, 'session_id');
        $version = filter_input(INPUT_POST, 'version');
        $orderMinId = filter_input(INPUT_POST, 'order_min_id');
        $appVersion = filter_input(INPUT_POST, 'app_version');
        $accountId = $this->getPkIdFromToken($sessionId, $appVersion);
        
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
        
        BaseUtil::echoJson(CodeParam::SUCCESS, $result); 
    }
    
    public function notifyUrl() {
        $notifyId = filter_input(INPUT_POST, 'notify_id');
        $tradeStatus = filter_input(INPUT_POST, 'trade_status');
        $outTradeNo = filter_input(INPUT_POST, 'out_trade_no');
        
        $verifyResult = AlipayManager::verifyNotify($notifyId);
        
        if(!$verifyResult) {
           return;
        }
        
        if($tradeStatus == 'TRADE_SUCCESS') {
            $order = PurchaseOrderManager::getOrderFromTradeNo($outTradeNo);
            
            if($order['status'] == 0) {
                $orderVersion = PullVersionManager::updateProductOrderVersion();
                PurchaseOrderManager::updateOrderFromTradeNo($outTradeNo, 1, $orderVersion);
                $storeVersion = PullVersionManager::updateStoreVersion();
                ProductManager::updatePurchaseCount($order['store_id'], $storeVersion);
                AccountManager::updateAccountAfterPurchase($order['pk_id']);
            }
        }

        echo "success";
    }
    
    public function createExchangeOrder() {
        self::setHeader();
        
        $sessionId = filter_input(INPUT_POST, 'session_id');
        $rewardId = filter_input(INPUT_POST, 'reward_id');
        $count = filter_input(INPUT_POST, 'count');
        $area = filter_input(INPUT_POST, 'area');
        $address = filter_input(INPUT_POST, 'address');
        $setDefault = filter_input(INPUT_POST, 'set_default');
        
        $account = $this->getAccountFromToken($sessionId);
        
        if(!isset($sessionId) || $account['pk_id'] == 0) {
            BaseUtil::echoJson(CodeParam::NOT_LOGIN, null);
            return;
        }
        
        if(!StoreControllerManager::checkExchangeOrderInfo($rewardId, $count, 
                $account, $area, $address, $setDefault)) {
            return;
        }
        
        $record = StoreControllerManager::updateAfterExchange($rewardId, 
                $account, $count, $area, $address, $setDefault);
        
        BaseUtil::echoJson(CodeParam::SUCCESS, $record); 
    }
    
    public function test() {
        $rsaSign = AlipayManager::rsaSign("怪兽图鉴", "可获得怪兽信息，并包含拿下怪兽的规则。",
                2.0, "YKMHR1462636800");
        echo "怪兽图鉴";
        $server = filter_input_array(INPUT_SERVER);
        echo RequestUtil::isMobile($server) ? 1 : 0;
        $isSSL = RequestUtil::isSSL($server);
        echo $isSSL ? 1 : 0;
        echo $server['HTTP_USER_AGENT'];
    }
}