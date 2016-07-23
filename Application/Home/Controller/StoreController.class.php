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
    
    public function purchase() {
        $code = "10000";
        $message = "购买商品成功！";
    }
}