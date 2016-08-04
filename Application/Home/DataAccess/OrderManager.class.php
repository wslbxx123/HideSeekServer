<?php
namespace Home\DataAccess;
/**
 * 操作订单表(admin_order)
 *
 * @author apple
 */
class OrderManager {
    public function insertOrder($storeId, $accountId, $count) {
        $Dao = M("order");
        $order["store_id"] = $storeId;
        $order['status'] = 0;
        $order['create_by'] = $accountId;
        $order['create_time'] = date('y-m-d H:i:s',time());
        $order['update_time'] = date('y-m-d H:i:s',time());
        $order['count'] = $count;
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
    
    public function getOrder($orderId) {
        $Dao = M("order");
        $condition['pk_id'] = $orderId;
        $order = $Dao->where($condition)->find();
        
        return $order;
    }
    
    public function refreshOrders($accountId) {
        $Dao = M("order");
        $sql = "call admin_get_orders($accountId)";
        $orderList = $Dao->query($sql);
        return $orderList;
    }
}
