<?php
namespace Home\DataAccess;
/**
 * 操作购买订单表(admin_purchase_order)
 *
 * @author Two
 */
class PurchaseOrderManager {
    public function insertOrder($storeId, $accountId, $count, $tradeNo, $version) {
        $Dao = M("purchase_order");
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
        $Dao = M("purchase_order");
        $condition["pk_id"] = $orderId;
        $order["status"] = $status;
        $order['update_time'] = date('y-m-d H:i:s',time());
        $Dao->where($condition)->save($order);
        
        return getOrder($orderId);
    }
    
    public function updateOrderVerifyStatus($tradeNo, $verifyStatus) {
        $Dao = M("purchase_order");
        $condition["trade_no"] = $tradeNo;
        $order["verify_status"] = $verifyStatus;
        $Dao->where($condition)->save($order);
    }
    
    public function getOrder($orderId) {
        $Dao = M("purchase_order");
        $condition['pk_id'] = $orderId;
        $order = $Dao->where($condition)->find();
        
        return $order;
    }
    
    public function refreshOrders($accountId, $version, $orderMinId) {
        $Dao = M("purchase_order");
        $sql = "call admin_refresh_purchase_orders($accountId, $version, $orderMinId)";
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
        $Dao = M("purchase_order");
        $sql = "call admin_get_purchase_orders($accountId, $version, $orderMinId)";
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
