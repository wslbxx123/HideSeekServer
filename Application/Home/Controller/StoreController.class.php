<?php

namespace Home\Controller;

class StoreController extends BaseController {
    public function refreshProducts() {
        $code = "10000";
        $message = "刷新商品列表成功！";
        
        $version = $_POST['version'];
        $productMinId = $_POST['product_min_id'];
        
        $versionDao = M("pull_version");
        $pullVersion = $versionDao->find();
        
        if(isset($version) && isset($productMinId)) {
            $storeDao = M("store");
            $condition['version'] = array('gt',$version);
            $condition['pk_id'] = array('egt',$productMinId);
            $productList = $storeDao->where($condition)->order('pk_id desc')->limit(20)->select();
        } else {
            $code = "10008";
            $message = "版本号或者记录最小ID值为空";
        }
        
        if($productList != null && count($productList) > 0) {
            $tempProductMinId = end($productList)['pk_id'];
            
            if($tempProductMinId < $productMinId) {
                $productMinId = $tempProductMinId;
            }
        }
        
        $array = array ('code' => $code, 'message' => $message, 
            'result' => array (
                'version' => $pullVersion['store_version'],
                'product_min_id' => $productMinId,
                'products' => $productList));
        
        echo json_encode($array);
    }
    
    public function getProducts() {
        $code = "10000";
        $message = "获得商品列表成功！";
        
        $version = $_POST['version'];
        $productMinId = $_POST['product_min_id'];
        
        $versionDao = M("pull_version");
        $pullVersion = $versionDao->find();
        
        if(isset($version) && isset($productMinId)) {
            $storeDao = M("store");
            $condition['version'] = array('elt',$version);
            $condition['pk_id'] = array('lt',$productMinId);
            $productList = $storeDao->where($condition)->order('pk_id desc')->limit(20)->select();
        } else {
            $code = "10008";
            $message = "版本号或者记录最小ID值为空";
        }
        
        $array = array (
            'code' => $code, 
            'message' => $message, 
            'result' => array (
                'version' => $pullVersion['store_version'],
                'product_min_id' => $productMinId,
                'products' => $productList));
        echo json_encode($array);
    }
    
    public function refreshReward() {
        $code = "10000";
        $message = "刷新兑换奖品列表成功！";
        
        $version = $_POST['version'];
        $rewardMinId = $_POST['reward_min_id'];
        
        $versionDao = M("pull_version");
        $pullVersion = $versionDao->find();
        
        if(isset($version) && isset($rewardMinId)) {
            $rewardDao = M("reward");
            $condition['version'] = array('gt',$version);
            $condition['pk_id'] = array('egt',$rewardMinId);
            $rewardList = $rewardDao->where($condition)->order('pk_id desc')->limit(20)->select();
        } else {
            $code = "10008";
            $message = "版本号或者记录最小ID值为空";
        }
        
        if($rewardList != null && count($rewardList) > 0) {
            $tempRewardMinId = end($rewardList)['pk_id'];
            
            if($tempRewardMinId < $rewardMinId) {
                $rewardMinId = $tempRewardMinId;
            }
        }
        
        $array = array ('code' => $code, 'message' => $message, 
            'result' => array (
                'version' => $pullVersion['reward_version'],
                'reward_min_id' => $rewardMinId,
                'reward' => $rewardList));
        
        echo json_encode($array);
    }
    
    public function getReward() {
        $code = "10000";
        $message = "获得兑换奖品列表成功！";
        
        $version = $_POST['version'];
        $rewardMinId = $_POST['reward_min_id'];
        
        $versionDao = M("pull_version");
        $pullVersion = $versionDao->find();
        
        if(isset($version) && isset($rewardMinId)) {
            $rewardDao = M("reward");
            $condition['version'] = array('elt',$version);
            $condition['pk_id'] = array('lt',$rewardMinId);
            $rewardList = $rewardDao->where($condition)->order('pk_id desc')->limit(20)->select();
        } else {
            $code = "10008";
            $message = "版本号或者记录最小ID值为空";
        }
        
        $array = array (
            'code' => $code, 
            'message' => $message, 
            'result' => array (
                'version' => $pullVersion['reward_version'],
                'reward_min_id' => $rewardMinId,
                'reward' => $rewardList));
        echo json_encode($array);
    }
    
    public function createOrder() {
        $code = "10000";
        $message = "生成订单成功！";
        $sessionId = $_POST['session_id'];
        $accountId = $this->getPkIdFromToken($sessionId);
        $storeId = $_POST['store_id'];
        $count = $_POST['count'];
        
        if(isset($sessionId) && $sessionId != "") {
            if(isset($storeId)) {
                if(isset($count)) {
                    $Dao = M("order");
                    $order["store_id"] = $storeId;
                    $order['status'] = 0;
                    $order['create_by'] = $accountId;
                    $order['create_time'] = date('y-m-d H:i:s',time());
                    $order['update_time'] = date('y-m-d H:i:s',time());
                    $order['count'] = $count;
                    $lastInsId = $Dao->add($order);
                } else {
                    $code = "10015";
                    $message = "商品数量为空";
                }   
            } else {
                $code = "10014";
                $message = "商品ID值为空";
            }
        } else {
            $code = "10010";
            $message = "用户未登录";
        }
        
        $array = array ('code' => $code, 'message' => $message,
            'result' => $lastInsId);
        echo json_encode($array);   
    }
    
    public function purchase() {
        $code = "10000";
        $message = "购买商品成功！";
        $sessionId = $_POST['session_id'];
        $accountId = $this->getPkIdFromToken($sessionId);
        $orderId = $_POST['order_id'];
        
        if(isset($sessionId) && $sessionId != "") {
            if(isset($orderId)) {
                $Dao = M("order");
                $condition["pk_id"] = $orderId;
                $order["status"] = 1;
                $order['update_time'] = date('y-m-d H:i:s',time());
                $Dao->where($condition)->save($order);
                $condition['pk_id'] = $orderId;
                $order = $Dao->where($condition)->find();
                
                if($order == null) {
                    $code = "10016";
                    $message = "订单编号错误";
                } else {
                    $storeDao = M("store");
                    $storeCondition["pk_id"] = $order['store_id'];
                    $store = $storeDao->where($storeCondition)->find();
                    $store['purchase_count'] = $store['purchase_count'] + 1;
                    $storeDao->where($storeCondition)->save($store);
                    
                    $versionDao = M("pull_version");
                    $version = $versionDao->find();
                    $version['store_version'] = $version['store_version'] + 1;
                    $versionDao->where('1=1')->save($version);
                }
            } else {
                $code = "10015";
                $message = "订单ID值为空";
            }
        } else {
            $code = "10010";
            $message = "用户未登录";
        }
        
        $array = array ('code' => $code, 'message' => $message,
            'result' => $orderId);
        echo json_encode($array); 
    }
    
    public function getOrders() {
        $code = "10000";
        $message = "生成订单成功！";
        $sessionId = $_POST['session_id'];
        $accountId = $this->getPkIdFromToken($sessionId);
        
        if(isset($sessionId) && $sessionId != "") {
            $Dao = M("order");
            $sql = "call admin_get_orders($accountId)";
            $orderList = $Dao->query($sql);
        } else {
            $code = "10010";
            $message = "用户未登录";
        }
        
        $array = array ('code' => $code, 'message' => $message,
            'result' => $orderList);
        echo json_encode($array); 
    }
}