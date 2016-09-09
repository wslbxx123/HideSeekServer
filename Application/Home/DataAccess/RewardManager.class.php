<?php
namespace Home\DataAccess;
/**
 * 操作奖品表(admin_award)
 *
 * @author Two
 */
class RewardManager {
    public function refreshRewards($version, $rewardMinId) {
        $Dao = M("reward");
        $condition['version'] = array('gt',$version);
        $condition['pk_id'] = array('egt',$rewardMinId);
        $rewardList = $Dao->where($condition)->order('pk_id desc')
                ->limit(20)->select();
        
        if($rewardList != null && count($rewardList) > 0) {
            $tempRewardMinId = end($rewardList)['pk_id'];
            
            if($rewardMinId == 0 || $tempRewardMinId < $rewardMinId) {
                $rewardMinId = $tempRewardMinId;
            }
        }
        return Array(
            "reward_min_id" => $rewardMinId,
            "rewards" => $rewardList
        );
    }
    
    public function getRewards($version, $rewardMinId) {
        $Dao = M("reward");
        $condition['version'] = array('elt',$version);
        $condition['pk_id'] = array('lt',$rewardMinId);
        $rewardList = $Dao->where($condition)->order('pk_id desc')
                ->limit(20)->select();
        
        if($rewardList != null && count($rewardList) > 0) {
            $tempRewardMinId = end($rewardList)['pk_id'];
            
            if($rewardMinId == 0 || $tempRewardMinId < $rewardMinId) {
                $rewardMinId = $tempRewardMinId;
            }
        }
        return Array(
            "reward_min_id" => $rewardMinId,
            "rewards" => $rewardList
        );
    }
    
    public function getReward($rewardId) {
        $Dao = M("reward");
        $condition["pk_id"] = $rewardId;
        $reward = $Dao->where($condition)->find();
        return $reward;
    }
    
    public function updateExchangeCount($rewardId, $version) {
        $Dao = M("reward");
        $condition["pk_id"] = $rewardId;
        $reward = $Dao->where($condition)->find();
        $reward['exchange_count'] = $reward['exchange_count'] + 1;
        $reward['version'] = $version;
        $Dao->where($condition)->save($reward);
        
        return $reward;
    }
}

