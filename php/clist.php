<?php 
   // session_start(); // 初始化session
    require_once "util.php";
    $dev_array=[];
    $util = new util();
    $res = $util->get_openid_session();
    //重定向至home界面
    //cho $res;
    if($res == ""){
       $util->redirect_to_home();
    }
    //获取设备列表
    $url="http://$util->server_ip/device/get_device?openid=$res";
    $data = json_decode($util->httpGet($url));
    
    if($data->status == 1){
        $dev_array = $data->data->list;
    }
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <link href="/css/bootstrap.min.css" rel="stylesheet" />
    <script src="/js/jquery.min.js"></script>
    <script src="/js/bootstrap.min.js"></script>
    <title>设备列表</title>
</head>
<body>
    <div class="panel panel-primary" style="border-radius: 0; margin-bottom: 0;">
        <div class="panel-heading" style="text-align: center; padding: 10px 5px;">

            <div class="panel-title " style="font-size: 1.6em;">        
    <button type="button" class="btn btn-primary" style="float: right; border: none; font-size: 0.8em;" onclick="location.href='/home.php?code=';">
        <span class="glyphicon glyphicon-log-out"></span>
    </button>
                设备列表
            </div>
        </div>
    </div>
    <div class="container">
<br />
<?php 
$arrlength=count($dev_array);

for($x=0;$x<$arrlength;$x++){
    $href="/control.php?id=".$dev_array[$x]->device_id;
    $devname=$dev_array[$x]->device_name;       	
    echo '<ul class="list-group">';
    echo '<a href='.$href.' class="list-group-item "><span class="badge"></span>';
    echo '<h4 class="list-group-item-heading"><span class="glyphicon glyphicon-eye-close" style="color: #5BC0DE;"></span>&nbsp;物联网设备</h4>';
    echo ' <p class="list-group-item-text" style="text-align: right;">'.$devname.'</p>';
    echo '</a></ul>';
}
?>  
</body>
</html>

