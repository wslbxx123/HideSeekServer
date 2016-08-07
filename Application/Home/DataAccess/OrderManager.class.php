<?php
namespace Home\DataAccess;
/**
 * 操作订单表(admin_order)
 *
 * @author apple
 */
class OrderManager {
    public function insertOrder($storeId, $accountId, $count, $tradeNo, $version) {
        $Dao = M("order");
        $order["store_id"] = $storeId;
        $order['status'] = 0;
        $order['create_by'] = $accountId;
        $order['create_time'] = date('y-m-d H:i:s',time());
        $order['update_time'] = date('y-m-d H:i:s',time());
        $order['count'] = $count;
        $order['trade_no'] = $tradeNo;
        $order['version'] = $version;
        return $Dao->add($order);
    }
    
    public function updateOrder($orderId, $status) {
        $Dao = M("order");
        $condition["pk_id"] = $orderId;
        $order["status"] = $status;
        $order['update_time'] = date('y-m-d H:i:s',time());
        $Dao->where($condition)->save($order);
        
        return getOrder($orderId);
    }
    
    public function updateOrderVerifyStatus($tradeNo, $verifyStatus) {
        $Dao = M("order");
        $condition["trade_no"] = $tradeNo;
        $order["verify_status"] = $verifyStatus;
        $Dao->where($condition)->save($order);
    }
    
    public function getOrder($orderId) {
        $Dao = M("order");
        $condition['pk_id'] = $orderId;
        $order = $Dao->where($condition)->find();
        
        return $order;
    }
    
    public function refreshOrders($accountId, $version, $orderMinId) {
        $Dao = M("order");
        $sql = "call admin_refresh_orders($accountId, $version, $orderMinId)";
        $orderList = $Dao->query($sql);
        
        if($orderList != null && count($orderList) > 0) {
            $tempOrderMinId = end($orderList)['pk_id'];
            
            if($tempOrderMinId < $orderMinId) {
                $orderMinId = $tempOrderMinId;
            }
        }
        
        return Array(
            "order_min_id" => $orderMinId,
            "orders" => $orderList
        );
    }
    
    public function getOrders($accountId, $version, $orderMinId) {
        $Dao = M("order");
        $sql = "call admin_get_orders($accountId, $version, $orderMinId)";
        $orderList = $Dao->query($sql);
        
        if($orderList != null && count($orderList) > 0) {
            $tempOrderMinId = end($orderList)['pk_id'];
            
            if($tempOrderMinId < $orderMinId) {
                $orderMinId = $tempOrderMinId;
            }
        }
        
        return Array(
            "order_min_id" => $orderMinId,
            "orders" => $orderList
        );
    }
}
