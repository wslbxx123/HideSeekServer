<?php
namespace Home\Controller;
use Think\Controller;
class UserController extends Controller {
    public function login(){
        session_start();
        session(array('name'=>'pk_id','expire'=>3600));
        $code = "10000";
        
        if(isset($_POST['phone']) && isset($_POST['password'])) {
            $Dao = M("account");
            $condition['phone'] = $_POST['phone'];
            $condition['password'] = md5($_POST['password']);
            $account = $Dao->where($condition)->find();
            
            if($account == null) {
                $code = "10001";
                $message = "用户名密码错误";
            } else {
                $Dao->where($condition)->setField('session_token', md5(session_id()));
                $message = "登录成功！";
                $_SESSION['pk_id'] = $account["pk_id"];
                $account["session_id"] = session_id();
            }
        } else{
            $code = "10002";
            $message = "用户名密码不能为空";
        }
        
        $array = array ('code' => $code, 'message' => $message,
            'result' => $account);
        echo json_encode($array);
    }
    
    public function register() {
        header("Content-Type:text/html; charset=utf-8");
        session_start();
        session(array('name'=>'pk_id','expire'=>3600));
        $code = "10000";
        $message = "注册成功！";
        
        if(isset($_POST['phone']) && isset($_POST['password'])
            && isset($_POST['nickname'])) {
            $Dao = M("account");

            $account["phone"] = $_POST['phone'];
            $account["password"] = md5($_POST["password"]);
            $account["nickname"] = $_POST["nickname"];
            $account["register_date"] = date('y-m-d H:i:s',time());
            $account["session_token"] = md5(session_id());
            
            $versionDao = M("pull_version");
            $version = $versionDao->find();
            $account["version"] = $version['friend_version'];
            
            if(isset($_POST['role'])) {
                $account["role"] = $_POST['role'];
            }

            if(isset($_POST['sex'])) {
                $account["sex"] = $_POST['sex'];
            }

            if(isset($_POST['region'])) {
                $account["region"] = $_POST['region'];
            }
            
            if(isset($_FILES['photo'])) {
                $file = $_FILES['photo'];
                $filePath = "/Public/Image/".$file['name'];
                $fileName = "./Public/Image/".$file['name'];
            
                if(move_uploaded_file($file['tmp_name'], $fileName)) {
                    $account["photo_url"] = U($filePath."@192.168.1.106");
                } else {
                    $code = "10004";
                    $message = "上传图片失败";
                }
            }
            
            $lastInsId = $Dao->add($account);
            $_SESSION['pk_id'] = $lastInsId;
            $condition['pk_id'] = $lastInsId;
            $account = $Dao->where($condition)->find();
            $account["session_id"] = session_id();
        } else{
            $code = "10003";
            $message = "手机号、密码和昵称不能为空";
        }
        
        $array = array ('code' => $code, 'message' => $message,
            'result' => $account);
        echo json_encode($array);   
    }
}

