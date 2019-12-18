<?php 
     require_once "util.php";
     $util = new util();
    // session_start(); // 初始化session
	 $devid=0;
     $user=0;
     $res = $util->get_openid_session();
     //重定向至home界面
     if($res == ""){
       // $util->redirect_to_home();
     }
     $user=$res;
     
     //echo json_encode($_COOKIE);
     //echo "jim";
	 if(isset($_GET['devid']))
         $devid = $_GET['devid']; 
     $url_name = $util->wechat_url_name;	//域名	 
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8"> 
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
	<title>添加物联网设备</title>
	<link rel="stylesheet" href="/css/bootstrap.min.css">
        <link href="/css/weui.min.css" rel="stylesheet" />  
    <script src="../js/jquery.min.js"></script>
	<script src="../js/bootstrap.min.js"></script>
        <script type="text/javascript" src="https://3gimg.qq.com/lightmap/components/geolocation/geolocation.min.js"></script>
</head>
<body>
 <div class="panel panel-primary" style="border-radius: 0; margin-bottom: 0;">
        <div class="panel-heading" style="text-align: center; padding: 10px 5px;">

            <div class="panel-title " style="font-size: 1.6em;">
             
    <button type="button" class="btn btn-primary" style="float: right; border: none; font-size: 0.8em;" onclick="location.href='/home.php';">
        <span class="glyphicon glyphicon-log-out"></span>
    </button>
                添加物联网设备
            </div>
        </div>
    </div>
    <div class="container">
   
<form class="form-horizontal" role="form">

	<div class="form-group has-success">
		<label class="col-sm-2 control-label" for="inputSuccess">
			
		</label>
		<div class="col-sm-10">
			<input type="text" class="form-control" id="deviceid" placeholder="请输入设备识别码">
		</div>
	</div>
        	<div class="form-group has-success">
		<label class="col-sm-2 control-label" for="inputSuccess" >
			
		</label>
		<div class="col-sm-10">
			<input type="text" class="form-control" id="devicename" placeholder="请输入设备名称">
		</div>
	</div>
</form>
<a  class="weui_btn weui_btn_primary" id="band" style="margin-top:50%; background-color:#4682B4">确认添加</a>
<a  class="weui_btn weui_btn_primary" id="saoband" style="margin-top:7%; background-color:#4682B4" href="/band.php">扫描二维码添加设备</a>
<?php
    if($res == ""){
        echo '<script>alert("您需要先授权访问");window.location.href="https://open.weixin.qq.com/connect/oauth2/authorize?appid=wxa690edcf92ee8f2e&redirect_uri=http%3a%2f%2fphp.yckz003.top%2fhome.php&response_type=code&scope=snsapi_userinfo&state=login#wechat_redirect"</script>';
    }
?>
<script>
    var user_message='<?php echo $user;?>';
    var devid='<?php echo $devid;?>';
	var server_name='<?php echo $url_name;?>';
    var geolocation = new qq.maps.Geolocation("JGEBZ-ZSZ66-XPPSR-E7S4F-Q7I3T-NEBNX", "myapp");
    var dposition;
    //发送绑定设备标志位，1:表示已发送绑定请求
    var band_flag = 0;
	var query_count = 0;
    var device_id;
    function showPosition(position) {

            dposition=position;
        };
    $(document).ready(function(){
      if(user_message =='0'){
	    //alert('您尚未登录我的设备，请登录后重新尝试');
		location.href='https://open.weixin.qq.com/connect/oauth2/authorize?appid=wxa690edcf92ee8f2e&redirect_uri=http%3a%2f%2fphp.'+server_name+'%2fhome.php&response_type=code&scope=snsapi_userinfo&state=login#wechat_redirect';
	  }
      if(devid!=='0')
		$("#deviceid").val(devid);
			geolocation.getLocation(showPosition);
        if(user_message){}else{
			//alert("请关注公众号,登录后重新尝试!");
       }

    });
    //单击绑定按钮向服务器发送http请求
    $('#band').click(function(){
        
     if($("#deviceid").val()){
	    var device_name = $("#devicename").val().trim();
	    device_id = $("#deviceid").val().trim();
		//var lat = dposition.lat;
	   // var lng = dposition.lng;
       //alert(device_name);
        var url='http://php.'+server_name+'/v1/band_device?name='+device_name+'&device_id='+device_id;//+'&lat='+lat+'&lng='+lng
        //alert(url);
        $.ajax({
            url: url,
            type: 'get',
            data: {
            },
            success: function(data,status){
                //alert("OK")
                band_flag = 1;
            },
            error: function(data,err){
                alert(data)
                band_flag = 0;
                query_count = 0;
                alert("添加设备失败,请检查是否已添加该设备");
            }
        });
     }
    });
    setInterval(function() {
        if (1 == band_flag ){
		    ack = 0;
            var url='http://php.'+server_name+'/v1/band_query?device_id='+device_id;
            $.ajax({
                url: url,
                type: 'get',
                data: {
                },
                success: function(data,status){
                   band_flag = 0;
                   alert("绑定成功");
                   location.href='/list.php';
                },
                error: function(data,err){
                    
                }
            });
        }
    },1500);

</script>
</body>
</html>
