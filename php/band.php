<?php 
	$appId = 'wxa690edcf92ee8f2e'; //记得换成你自己测试号的信息
    $appSecret = '7d4c2f2b7d014983cf1db8c9f34ac1e1';
    require_once "jssdk.php";
    $jssdk = new JSSDK($appId,$appSecret);//这里改成自己的
    $signPackage= $jssdk->getSignPackage();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>WIFI配置</title>
    <meta name="format-detection" content="telephone=no">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <script src="/js/jquery.min.js"></script>
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
      'scanQRCode' 
    ]
  });

  wx.ready(function () {
     wx.scanQRCode({
                // 默认为0，扫描结果由微信处理，1则直接返回扫描结果
                needResult : 1,
                desc : 'scanQRCode desc',
                success : function(res) {
                    //扫码后获取结果参数赋值给Input
                    var url = res.resultStr;
                    //商品条形码，取","后面的
                    if(url.indexOf(",")>=0){
                        var tempArray = url.split(',');
                        var tempNum = tempArray[1];
                        alert(tempNum);
                    }else{
                        location.href=url;
                        //alert(url);
                        //$("#id_securityCode_input").val(url);
                    }
                }
            });
    // 在这里调用 API

   // wx.invoke('configWXDeviceWiFi');
  });
  wx.error(function(res){
      alert("配置出错");
  });
</script>

</body>
</html>
