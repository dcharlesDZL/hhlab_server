<?php 
  $id = $_GET["id"];
  require_once "util.php";
  $url_name='yckz003.top';	//域名
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>高级设置</title>
    <link rel="stylesheet" href="/css/bootstrap.min.css" media="screen">
     <link href="/css/weui.min.css" rel="stylesheet" /> 
    <script type="text/javascript" src="/js/jquery.min.js"></script>
    <script type="text/javascript" src="/js/bootstrap.min.js"></script>
    <script src="/js/browserMqtt.js"></script>
</head>
<body>
<div class="panel panel-primary" style="border-radius: 0; margin-bottom: 0;">
    <div class="panel-heading" style="text-align: center; padding: 10px 5px;">
        <div class="panel-title " style="font-size: 1.6em;">
		  <button type="button" class="btn btn-primary" style="float: right; border: none; font-size: 0.8em;" onclick="location.href='/clist.php';">
              <span class="glyphicon glyphicon-log-out"></span>
          </button>
            高级设置
        </div>
    </div>
</div>
<div class="form-group has-success" style="display: inline-block;margin-top:10%;width:200px">
    <div class="col-sm-10">
        <input type="text" class="form-control" id="number" placeholder="请输入设置数值">
    </div>
</div>
<div class="btn-group" style="display: inline-block;">
    <button type="button" class="btn btn-primary">请选择类型</button>
    <button type="button" class="btn btn-primary dropdown-toggle"
            data-toggle="dropdown">
        <span class="caret"></span>
        <span class="sr-only">切换下拉菜单</span>
    </button>
    <ul class="dropdown-menu" role="menu">
        <li id="1">设置运行时间</li>
        <li id="2">设置温度</li>
        <li id="3">设置湿度</li>
        <li id="4">设置速度</li>
        <li id="5">取消微信报警</li>
    </ul>
</div>

<a  class="weui_btn weui_btn_primary" id="wechat_alram" style="margin-top:50%; background-color:#4682B4">设置微信报警</a>   
<a  class="weui_btn weui_btn_primary" id="del" style="margin-top:10%; background-color:#4682B4">解绑设备</a>        
</body>
<script>
    var dev_id = '<?php echo $id;?>';
	var server_name='<?php echo $url_name;?>';
    var element;
    $(document).ready(function(){
        var Uarry=$("#choose li");//获取所有的li元素  
        $("#choose li").click(function(){//点击事件 
                console.log("ddd")                
                var count=$(this).index();//获取li的下标  
                element=Uarry.eq(count).text();  
                console.log($(this).index())
        })  
        //设置请求处理
        $('#1').click(function(){send_data(1)})
        $('#2').click(function(){send_data(2)})
        $('#3').click(function(){send_data(3)})
        $('#4').click(function(){send_data(4)})
        $('#5').click(function(){send_data(5)})              
    });
    //解绑设备
    $('#del').click(function(){
        $.ajax({
            url: 'http://php.'+server_name+'/v1/del?'+'&dev_id='+dev_id,
            type: 'get',
            data: {
            },
            success: function(data,status){
                alert("解绑设备成功");
                location.href='/clist.php';
            },
            error: function(data,err){
                alert("解绑设备失败");
            }
        });
    });
    //设置微信报警
    $('#wechat_alram').click(function(){
        $.ajax({
            url: 'http://php.'+server_name+'/v1/set?'+'&dev_id='+dev_id +"&set_type=wechat_alram",
            type: 'get',
            data: {
            },
            success: function(data,status){
                alert("设置微信报警成功");
            },
            error: function(data,err){
                alert("设置失败,请稍后再试");
            }
        });
    });
    //处理设置指令
    var send_data=function(element){
        var set_type;
        if(element==1){
            set_type='run_time'
        }else if(element==2){
            set_type='wd'
        }else if(element==3){
            set_type='sd'
        }else if(element==4){
            set_type='ud'
        }else if(element==5){
            set_type="cancel_alarm"
        }else{
            return;
        }
        var set_num = parseFloat($('#number').val());
        $.ajax({
            url: 'http://php.'+server_name+'/v1/set?'+'&dev_id='+dev_id +"&set_type=" + set_type + "&set_value="+set_num,
            type: 'get',
            data: {
            },
            success: function(data,status){
                if ("cancel_alarm" == set_type){
                    alert("取消微信报警成功");
                }else{
                    alert("设置指令发送成功");
                }
                
                //location.href='/clist.php';
            },
            error: function(data,err){
                alert("设置失败,请稍后再试");
            }
        });     
    }
    /**
      $('#del').click(function(){
        $.ajax({
            url: 'http://php.'+server_name+'/v1/del?'+'&dev_id='+dev_id,
            type: 'get',
            data: {
            },
            xhrFields: {
                 withCredentials: true // 携带跨域cookie
            },
            processData: false,
            success: function(data,status){
                alert("解绑设备成功");
                location.href='/clist.php';
            },
            error: function(data,err){
                alert("解绑设备失败");
            }
        });
    }); */
</script>
</html>
