<?php 
  $id = $_GET["id"];
  session_start(); // 初始化session
  $user=$_SESSION['id'];
//echo $id;
  $url_name='yckz003.top';	//域名
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>物联网设备控制</title>
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
            物联网设备控制
        </div>
    </div>
</div>
﻿<div class="form-group has-success" style="display: inline-block;margin-top:10%;width:200px">
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
        <li id="1">设备运行时间</li>
        <li id="2">设备压力</li>
        <li id="3">设备浓度</li>
        <li id="4">设备速度</li>
        <li id="5">设备湿度</li>
        <li id="6">设备温度</li>
        <li id="7">删除设备</li>
    </ul>
</div>
<a  class="weui_btn weui_btn_primary" id="del" style="margin-top:50%; background-color:#4682B4">删除设备</a>        
</body>
<script>
    var dev_id = '<?php echo $id;?>';
    var user = '<?php echo $user;?>';
	var server_name='<?php echo $url_name;?>';
    //dev_id = '68C63AAC551E';
    //dev_id = '5CCF7F18EFD0';
    var element;
    var client = mqtt.connect("ws://121.40.189.126:3000");
     client.on("connect", function(topic, payload) {
        console.log("connect")
        client.subscribe(dev_id);
    });
      client.on("message", function(topic, payload) {
         //console.log(payload+'');
    });
     $(document).ready(function(){
            var Uarry=$("#choose li");//获取所有的li元素  
            $("#choose li").click(function(){//点击事件 
                   console.log("ddd")                
                   var count=$(this).index();//获取li的下标  
                   element=Uarry.eq(count).text();  
                     console.log($(this).index())
              })  
             $('#1').click(function(){send_data(1)})
             $('#2').click(function(){send_data(2)})
             $('#3').click(function(){send_data(3)})
             $('#4').click(function(){send_data(4)})
             $('#5').click(function(){send_data(5)})
             $('#6').click(function(){send_data(6)})
          //   $('#7').click(function(){send_data(7)})            
});
$('#del').click(function(){
    $.ajax({
                url: 'http://app.'+server_name+'/v1/del?'+'openid='+user+'&dev_id='+dev_id,
                type: 'post',
                data: {},
                success: function(data,status){
                    alert("删除设备成功");
					location.href='/clist.php';
                },
                error: function(data,err){
                    alert("删除设备失败");
                }
            });
});
var send_data=function(element){
    var json={"topic":"ci","id":dev_id};
     console.log(element)
        var json={"topic":"ci","id":dev_id};
        if(element==1){
             json.type=1;
         }else if(element==2){
             json.type=2;
         }else if(element==3){
             json.type=3;
         }else if(element==4){
             json.type=4;
         }else if(element==5){
             json.type=5;
         }else if(element==6){
             json.type=7;
         }else if(element==7){
/*
            $.ajax({
                url: 'http://app.jimsun.natapp1.cc/v1/del?'+'openid='+user+'&dev_id='+dev_id,
                type: 'post',
                data: {},
                success: function(data,status){
                    alert("Delete is OK");
                },
                error: function(data,err){
                    alert("erro");
                }
            });
*/
           //  json.type=7;
            return;
         }else{
            return;
         }
         json.data=parseFloat($('#number').val())*10;
         console.log(JSON.stringify(json))
         client.publish("co", JSON.stringify(json));
         alert('Set is OK');
}
/*
    $('#set').click(function(){
        console.log(element)
        var json={"topic":"ci","id":dev_id};
        if(element=='设备运行时间'){
             json.type=1;
         }else if(element=='设备压力'){
             json.type=2;
         }else if(element=='设备浓度'){
             json.type=3;
         }else if(element=='设备速度'){
             json.type=4;
         }else if(element=='设备湿度'){
             json.type=5;
         }else if(element=='设备温度'){
             json.type=7;
         }else{
            return;
         }
         json.data=parseFloat($('#number').val())*10;
         console.log(JSON.stringify(json))
         client.publish("co", JSON.stringify(json));
         alert('Set is OK');
    });
 */
    //client.subscribe('bo');
   // client.on("message", function(topic, payload) {
     
     //  if(topic == 'bo' && flag ==0){
     //     var a=payload+'';
      //    console.log(a);
      //   if(user_message == a){
    //         flag =1;
    //         alert('Bnad is OK!');
   //          client.end();
    //      }
   //    }
  //  });
</script>
</html>
