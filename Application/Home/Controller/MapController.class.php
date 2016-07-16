<?php
namespace Home\Controller;

class MapController extends BaseController {
    public function refresh(){
        $code = "10000";
        $message = "地图刷新成功";
        
        if(isset($_POST['latitude']) && isset($_POST['longitude'])) {
            $Dao = M("goal");
            $latitude = $_POST['latitude'];
            $longitude = $_POST['longitude'];
            $account_role = $_POST['account_role'];
            $update_timestamp = $_POST['update_time'];
            
            if(!isset($account_role)) {
                $account_role = 0;
            }
            
            if(!isset($update_timestamp)) {
                $update_timestamp = "null";
            }
            
            settype($latitude, "double");
            settype($longitude, "double");
            $sql = "call admin_monster_role_p($latitude, $longitude, "
                    . "$account_role, \"$update_timestamp\")";
            $goals = $Dao->query($sql);
        } else{
            $code = "10005";
            $message = "经纬度不能为空";
        }
        
        if(count($goals) > 0) {
            $goal = $goals[0];
            $update_timestamp = $goal['update_time'];
        }
        
        $array = array ('code' => $code, 'message' => $message, 
            'result' => array (
                'update_time' => $update_timestamp,
                'goals' => $goals));
        echo json_encode($array);
    }
    
    public function getGoal() {
        $code = "10000";
        $message = "获得目标成功";
        $sessionId = $_POST['session_id'];
        $account_id = $this->getPkIdFromToken($sessionId);
        
        $goal_id = $_POST['goal_id'];
        $goal_type = $_POST['goal_type'];
        if(isset($goal_id) && isset($goal_type)) {
            $Dao = M("goal");
            $data['valid'] = 0;
            $data['update_time'] = date('y-m-d H:i:s',time());
            $Dao->where("pk_id=$goal_id")->save($data);
            
            $versionDao = M("pull_version");
            $version = $versionDao->find();
            $version['race_group_version'] = $version['race_group_version'] + 1;
            
            $recordDao = M("record");
            $record['goal_id'] = $goal_id;
            $record['goal_type'] = $goal_type;
            $record['account_id'] = $account_id;
            if($goal_type == 1) {
                $record['score'] = 1;
            } else {
                $record['score'] = -1;
            }   
            $record['time'] = date('y-m-d H:i:s',time());
            $condition['account_id'] = $account_id;
            $record['score_sum'] = $recordDao->where($condition)->sum('score')
                    + $record['score'];
            $record['version'] = $version['race_group_version'];
            $recordDao->add($record);
            
            $versionDao->where('1=1')->save($version);
        } else{
            $code = "10006";
            $message = "目标ID或目标类型为空";
        }
        
        $array = array ('code' => $code, 'message' => $message, 
            'result' => $record);
        echo json_encode($array);
    }
    
    public function hitMonster() {
        $code = "10000";
        $message = "打怪兽成功";
        
        $sessionId = $_POST['session_id'];
        $account_id = $this->getPkIdFromToken($sessionId);
        $goal_id = $_POST['goal_id'];
        $account_role = $_POST['account_role'];
        if(isset($goal_id) && isset($account_role)) {
            $Dao = M("monster_temp_hit");
            $data['goal_id'] = $goal_id;
            $data['account_id'] = $account_id;
            $data['account_role'] = $account_role;
            $data['hit_time'] = date('y-m-d H:i:s',time());
            $Dao->add($data);
            
            $sql = "call admin_get_temp_hit($goal_id)";
            $account_array = $Dao->query($sql);
            
            $versionDao = M("pull_version");
            $version = $versionDao->find();
            $version['race_group_version'] = $version['race_group_version'] + 1;
            
            $flag = false;
            foreach ($account_array as $account){ 
                if($account['account_id'] == $account_id) {
                    $recordDao = M("record");
                    $record['goal_id'] = $goal_id;
                    $record['goal_type'] = 2;
                    $record['account_id'] = $account_id;
                    $record['score'] = 1;
                    $record['time'] = date('y-m-d H:i:s',time());
                    $condition['account_id'] = $account_id;
                    $record['score_sum'] = $recordDao->where($condition)->sum('score')
                            + $record['score'];
                    $record['version'] = $version['race_group_version'];
                    $recordDao->add($record);
                    $flag = true;
                }
            }
            
            $versionDao->where('1=1')->save($version);
                
            if(!$flag) {
                $code = "10007";
                $message = "目标已经消失";
            }
        } else{
            $code = "10009";
            $message = "目标ID或用户角色为空";
        }
        
        $array = array ('code' => $code, 'message' => $message,
            'result' => $account_array);
        echo json_encode($array);
    }
    
    public function setBomb() {
        $code = "10000";
        $message = "设置炸弹成功";
        $sessionId = $_POST['session_id'];
        $account = $this->getAccountFromToken($sessionId);
        
        if(isset($sessionId) && $sessionId != "") {
            if(isset($_POST['latitude']) && isset($_POST['longitude'])) {
                $latitude = $_POST['latitude'];
                $longitude = $_POST['longitude'];
                if(isset($_POST['orientation'])) {
                    $orientation = $_POST['orientation'];
                    $Dao = M("goal");
                    $data['latitude'] = $latitude;
                    $data['longitude'] = $longitude;
                    $data['orientation'] = $orientation;
                    $data['create_by'] = $account_id;
                    $data['update_time'] = date('y-m-d H:i:s',time());
                    $data['valid'] = 1;
                    $data['type'] = 3;
                    $Dao->add($data);
                    
                    $tempAccount['bomb_num'] = $account['bomb_num'] - 1;
                    $pkId = $tempAccount['pk_id'];
                    $Dao->where("pk_id=$pkId")->save($data);
                } else {
                    $code = "10011";
                    $message = "方向值不能为空";
                }
            } else{
                $code = "10005";
                $message = "经纬度不能为空";
            }
        } else {
            $code = "10010";
            $message = "用户未登录";
        }
        
        $array = array ('code' => $code, 'message' => $message,
            'result' => $tempAccount['bomb_num']);
        echo json_encode($array);
    }
}


