<?php
namespace Home\DataAccess;
/**
 * 操作拉数据版本表(admin_pull_version)
 *
 * @author Two
 */
class PullVersionManager {
    public function getFriendVersion() {
        $Dao = M("pull_version");
        $version = $Dao->find();
        return $version['friend_version'];
    }
    
    public function getStoreVersion() {
        $Dao = M("pull_version");
        $version = $Dao->find();
        return $version['store_version'];
    }
    
    public function getRewardVersion() {
        $Dao = M("pull_version");
        $version = $Dao->find();
        return $version['reward_version'];
    }
    
    public function getProductOrderVersion() {
        $Dao = M("pull_version");
        $version = $Dao->find();
        return $version['product_order_version'];
    }
    
    public function getRewardOrderVersion() {
        $Dao = M("pull_version");
        $version = $Dao->find();
        return $version['reward_order_version'];
    }
    
    public function updateStoreVersion() {
        $Dao = M("pull_version");
        $version = $Dao->find();
        $version['store_version'] = $version['store_version'] + 1;
        $Dao->where('1=1')->save($version);
    }
    
    public function updateProductOrderVersion() {
        $Dao = M("pull_version");
        $version = $Dao->find();
        $version['product_order_version'] = $version['product_order_version'] + 1;
        $Dao->where('1=1')->save($version);
        
        return $version['product_order_version'];
    }
    
    public function updateRewardOrderVersion() {
        $Dao = M("pull_version");
        $version = $Dao->find();
        $version['reward_order_version'] = $version['reward_order_version'] + 1;
        $Dao->where('1=1')->save($version);
        
        return $version['reward_order_version'];
    }
    
    public function updateRaceGroupVersion() {
        $Dao = M("pull_version");
        $version = $Dao->find();
        $version['race_group_version'] = $version['race_group_version'] + 1;
        $Dao->where('1=1')->save($version);
        
        return $version['race_group_version'];
    }
}
