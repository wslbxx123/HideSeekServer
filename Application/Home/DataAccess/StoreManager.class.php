<?php
namespace Home\DataAccess;
/**
 * 操作商品表(admin_store)
 *
 * @author Two
 */
class StoreManager {
    public function refreshProducts($version, $productMinId) {
        $Dao = M("store");
        $condition['version'] = array('gt',$version);
        $condition['pk_id'] = array('egt',$productMinId);
        $productList = $Dao->where($condition)->order('pk_id desc')
                ->limit(20)->select();
        return $productList;
    }
    
    public function getProducts($version, $productMinId) {
        $Dao = M("store");
        $condition['version'] = array('elt',$version);
        $condition['pk_id'] = array('lt',$productMinId);
        $productList = $Dao->where($condition)->order('pk_id desc')
                ->limit(20)->select();
        return $productList;
    }
    
    public function refreshRewards($version, $rewardMinId) {
        $Dao = M("reward");
        $condition['version'] = array('gt',$version);
        $condition['pk_id'] = array('egt',$rewardMinId);
        $rewardList = $Dao->where($condition)->order('pk_id desc')
                ->limit(20)->select();
        return $rewardList;
    }
    
    public function getRewards($version, $rewardMinId) {
        $Dao = M("reward");
        $condition['version'] = array('elt',$version);
        $condition['pk_id'] = array('lt',$rewardMinId);
        $rewardList = $Dao->where($condition)->order('pk_id desc')
                ->limit(20)->select();
        return $rewardList;
    }
    
    public function updatePurchaseCount($storeId) {
        $Dao = M("store");
        $condition["pk_id"] = $storeId;
        $store = $Dao->where($condition)->find();
        $store['purchase_count'] = $store['purchase_count'] + 1;
        $Dao->where($condition)->save($store);
    }
    
    public function getProduct($storeId) {
        $Dao = M("store");
        $condition["pk_id"] = $storeId;
        $store = $Dao->where($condition)->find();
        return $store;
    }
}
