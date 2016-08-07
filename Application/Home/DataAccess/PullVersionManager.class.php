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
    
    public function getOrderVersion() {
        $Dao = M("pull_version");
        $version = $Dao->find();
        return $version['order_version'];
    }
    
    public function updateStoreVersion() {
        $Dao = M("pull_version");
        $version = $Dao->find();
        $version['store_version'] = $version['store_version'] + 1;
        $Dao->where('1=1')->save($version);
    }
    
    public function updateOrderVersion() {
        $Dao = M("pull_version");
        $version = $Dao->find();
        $version['order_version'] = $version['order_version'] + 1;
        $Dao->where('1=1')->save($version);
        
        return $version['order_version'];
    }
}
