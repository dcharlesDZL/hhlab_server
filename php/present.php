<?php 
     require_once "util.php";
     $util = new util();
     $url_name = $util->wechat_url_name;	//域名	 
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8"> 
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
	<title>礼物优选计划</title>
	<link rel="stylesheet" href="/css/bootstrap.min.css">
        <link href="/css/weui.min.css" rel="stylesheet" />  
	<script src="/js/jquery.min.js"></script>
	<script src="/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="https://3gimg.qq.com/lightmap/components/geolocation/geolocation.min.js"></script>
</head>
<body>
 <div class="panel panel-primary" style="border-radius: 0; margin-bottom: 0;">
        <div class="panel-heading" style="text-align: center; padding: 10px 5px;">

            <div class="panel-title " style="font-size: 1.6em;">
             
    <button type="button" class="btn btn-primary" style="float: right; border: none; font-size: 0.8em;" onclick="location.href='/home.php';">
        <span class="glyphicon glyphicon-log-out"></span>
    </button>
           礼物优选计划
            </div>
        </div>
    </div>
    <div class="container">
   
<form class="form-horizontal" role="form">

	<div class="form-group has-success">
		<label class="col-sm-2 control-label" for="inputSuccess">
			
		</label>
		<div class="col-sm-10">
			<input type="text" class="form-control" id="presentid" placeholder="请输入礼物名称(必选)">
		</div>
	</div>
        	<div class="form-group has-success">
		<label class="col-sm-2 control-label" for="inputSuccess" >
			
		</label>
		<div class="col-sm-10">
			<input type="text" class="form-control" id="priceid" placeholder="请输入价格(可选)">
		</div>
	</div>
</form>
<a  class="weui_btn weui_btn_primary" id="band" style="margin-top:50%; background-color:#4682B4">检查是否满足条件</a>
<a  class="weui_btn weui_btn_primary" id="saoband" style="margin-top:7%; background-color:#4682B4" href="/band.php">确认提交订单</a>
<script>
	var server_name='<?php echo $url_name;?>';
    $(document).ready(function(){
        alert('Jim_sun为您服务');
    });
    //单击绑定按钮向服务器发送http请求
    $('#band').click(function(){
     if($("#deviceid").val()){
	    var present = $("#presentid").val();
		var price= $("#priceid").val();
		var lat = dposition.lat;
	    var lng = dposition.lng;
        var url='http://data.'+server_name+'/v1/present?present='+present+'&price='+price
        $.ajax({
            url: url,
            type: 'get',
            data: {
            },
            xhrFields: {
                 withCredentials: true // 携带跨域cookie
            },
            processData: false,
            success: function(data,status){
                alert(data)
            },
            error: function(data,err){
                alert("bug!!");
            }
        });
     }
    });
</script>
</body>
</html>
