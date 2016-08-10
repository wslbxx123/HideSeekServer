<?php
namespace Home\DataAccess;
/**
 * 操作商品表(admin_store)
 *
 * @author Two
 */
class ProductManager {
    public function refreshProducts($version, $productMinId) {
        $Dao = M("product");
        $condition['version'] = array('gt',$version);
        $condition['pk_id'] = array('egt',$productMinId);
        $productList = $Dao->where($condition)->order('pk_id desc')
                ->limit(20)->select();
        
        if($productList != null && count($productList) > 0) {
            $tempProductMinId = end($productList)['pk_id'];
            
            if($tempProductMinId < $productMinId) {
                $productMinId = $tempProductMinId;
            }
        }
        
        return Array(
            "product_min_id" => $productMinId,
            "products" => $productList
        );
    }
    
    public function getProducts($version, $productMinId) {
        $Dao = M("product");
        $condition['version'] = array('elt',$version);
        $condition['pk_id'] = array('lt',$productMinId);
        $productList = $Dao->where($condition)->order('pk_id desc')
                ->limit(20)->select();
        
        if($productList != null && count($productList) > 0) {
            $tempProductMinId = end($productList)['pk_id'];
            
            if($tempProductMinId < $productMinId) {
                $productMinId = $tempProductMinId;
            }
        }
        
        return Array(
            "product_min_id" => $productMinId,
            "products" => $productList
        );
    }
    
    public function updatePurchaseCount($storeId) {
        $Dao = M("product");
        $condition["pk_id"] = $storeId;
        $store = $Dao->where($condition)->find();
        $store['purchase_count'] = $store['purchase_count'] + 1;
        $Dao->where($condition)->save($store);
    }
    
    public function getProduct($storeId) {
        $Dao = M("product");
        $condition["pk_id"] = $storeId;
        $store = $Dao->where($condition)->find();
        return $store;
    }
}
