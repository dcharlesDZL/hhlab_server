
<?php 
    require_once "util.php";
    $util = new util();
    
    //重定向至home界面
    if($util->openid_upload() == false){
        $util->redirect_to_home();
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>我的物联网设备</title>
    <link rel="stylesheet" href="/css/bootstrap.min.css" media="screen">
    <script type="text/javascript" src="/js/jquery.min.js"></script>
    <script type="text/javascript" src="/js/bootstrap.min.js"></script>
</head>
<body>

<div class="panel panel-primary" style="border-radius: 0; margin-bottom: 0;">
    <div class="panel-heading" style="text-align: center; padding: 10px 5px;">
        <div class="panel-title " style="font-size: 1.6em;">
            我的设备
        </div>
    </div>
</div>
<div class="container">
    <style>
        .en .btn{
            width: 90px;
            height:90px;
        }
    </style>
    <script>
       function go(url){
          location.href = url;
       }
    </script>
    <div>
        <div class="page-header" style="margin: 5px; padding: 5px; clear: both;">
            <h3></h3>
        </div>
        <button class="btn btn-info" style="float: left; border: none; padding: 0.5em; margin: 0.5em;" type="button" onclick="go('/list.php')">
            <span class="glyphicon glyphicon-list" style="font-size: 2.8em;"></span>
            <h5 style="margin: 5px;">查看设备</h5>
        </button>
        <button class="btn btn-info" style="float: left; border: none; padding: 0.5em; margin: 0.5em;" type="button" onclick="go('/clist.php')">
            <span class="glyphicon glyphicon-import" style="font-size: 2.8em;"></span>
            <h5 style="margin: 5px;">高级设置</h5>
        </button>
        
        <!--
		<button class="btn btn-info" style="float: left; border: none; padding: 0.5em; margin: 0.5em;" type="button" onclick="go('/map.php')">
            <span class="glyphicon glyphicon-list" style="font-size: 2.8em;"></span>
            <h5 style="margin: 5px;">地图模式</h5>
        </button>
        -->
          <button class="btn btn-info" style="float: left; border: none; padding: 0.5em; margin: 0.5em;" type="button" onclick="go('/newband.php')">
            <span class="glyphicon glyphicon-list" style="font-size: 2.8em;"></span>
            <h5 style="margin: 5px;">添加设备</h5>
        </button>
        <!--
		  <button class="btn btn-info" style="float: left; border: none; padding: 0.5em; margin: 0.5em;" type="button" onclick="go('/hlist.php')">
            <span class="glyphicon glyphicon-list" style="font-size: 2.8em;"></span>
            <h5 style="margin: 5px;">历史数据</h5>
        </button>
        -->
</div>

</body>
</html>

