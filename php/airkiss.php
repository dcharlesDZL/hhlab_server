<?php 
	$appId = 'wxa690edcf92ee8f2e'; //记得换成你自己测试号的信息
    $appSecret = '7d4c2f2b7d014983cf1db8c9f34ac1e1';
    require_once "jssdk.php";
    $jssdk = new JSSDK($appId,$appSecret);//这里改成自己的
    //echo $user->getAccessToken();
    $signPackage= $jssdk->getSignPackage();
	   //echo $signPackage['url$signPackage'];
	   //echo $signPackage['url$signPackage'];
      //echo $signPackage;
     //echo $signPackage["appId"];
	 //echo $signPackage["timestamp"];
     //echo $signPackage["nonceStr"];
     //echo $signPackage["signature"];
	// echo $protocol;
	// echo $_SERVER[HTTP_HOST];
	// echo $_SERVER[REQUEST_URI];
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>WIFI配置</title>
    <meta name="format-detection" content="telephone=no">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <link href="/css/weui.min.css" rel="stylesheet" />
    <script src="/js/jquery.min.js"></script>

    <style>
        body {
            background-color: #fbf9fe;
        }

        .page_title {
            text-align: center;
            font-size: 24px;
            color: #3cc51f;
            font-weight: bold;
            margin: 20px;
        }

        .weui_label {
            width: 4em;
        }

        .btnDiv {
            margin: 20px auto;
            width: 80%;
        }
    </style>
</head>
<body>
<script src="http://res.wx.qq.com/open/js/jweixin-1.4.0.js"></script>
<script>
    wx.config({
    beta:true,//开启内测接口调用，注入wx.invoke方法
    debug:false,//关闭调试模式
    appId:'<?php echo $signPackage["appId"];?>',//AppID
    timestamp: <?php echo $signPackage["timestamp"];?>,//时间戳
    nonceStr: '<?php echo $signPackage["nonceStr"];?>',//随机串
    signature:'<?php echo $signPackage["signature"];?>',//签名
    jsApiList: [
      // 所有要调用的 API 都要加到这个列表中
      'configWXDeviceWiFi'
    ]
  });
    function configWiFi() {

            wx.invoke('configWXDeviceWiFi', {}, function (res) {
                if (res.err_msg == 'configWXDeviceWiFi:ok') {
                    alert('配置成功!');
                    wx.closeWindow();
                } else if(res.err_msg == 'configWXDeviceWiFi:fail') {
                    //alert(res.err_msg);
                    alert('配置失败！请重试');
                }
            });
        }
  wx.ready(function () {
    // 在这里调用 API

    $('#action').click(function(){
	  //alert("2223");
      configWiFi();
     });
   // wx.invoke('configWXDeviceWiFi');
  });
  wx.error(function(res){
      alert("配置出错");
  });
</script>
<div class="page">
    <div class="hd">
        <h1 class="page_title">配置设备上网</h1>
    </div>
    <div class="bd">
        <div class="weui_cells">
            <div class="weui_cell">
                <div class="weui_cell_bd weui_cell_primary">
                    <p>1. 确定手机已连接到WiFi</p>
                </div>
            </div>
            <div class="weui_cell">
                <div class="weui_cell_bd weui_cell_primary">
                    <p>2. 请长按设备上的配置按钮3秒</p>
                </div>
            </div>
            <div class="weui_cell">
                <div class="weui_cell_bd weui_cell_primary">
                    <p>3. 配置灯间隔1秒闪1次进入设置状态</p>
                </div>
            </div>
        </div>
    </div>
    <div class="btnDiv">
        <div id="action" class="weui_btn weui_btn_primary">
            开始配置
        </div>
        
    </div>
      
</div>
</body>
</html>
