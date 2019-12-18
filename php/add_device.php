<?php
    //echo $_POST["name"];
    require_once "util.php";
    $util = new util();
    
    if(isset($_POST["name"]) and isset($_POST["device_id"])){
        $url="http://$util->server_ip/device/device_operation?op=1";
        $openid = $util->get_openid_session();
        //$openid = "123456jimsun";
        if($openid == ""){
            echo "parm is error";
            header("HTTP/1.1 405 Method Not Allowed");  
        }
        $device_id = $_POST["device_id"];
        $name = $_POST["name"];
        $type = $util->get_device_type();
        $arr=array("openid"=>$openid,"device_id"=>$device_id,"name"=>$name,"type"=>$type);
        
        $res = $util->httpPost($url,$arr);
        if($res){
            if($res->status == 1){
                echo "ok";
            }else{
                header("HTTP/1.1 405 Method Not Allowed");  
            }
           // echo json_encode($res);
        }else{
            echo "parm is error";
            header("HTTP/1.1 405 Method Not Allowed");  
        }
        
    }
?>