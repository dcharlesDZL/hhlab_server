<?php 
    session_start(); // 初始化session
	$dev_array=[];
	if(isset($_SESSION['id'])){
		$m = new MongoClient();    // 连接到mongodb
		$db = $m->Hh;            // 选择一个数据库
		$collection = $db->user; // 选择集合
		$cursor = $collection->find( array('_id' => $_SESSION['id']));
		// 迭代显示文档标题
		foreach ($cursor as $document) {
			$dev_array = $document['data'];
		}
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
    <title>我的设备</title>
</head>
<body>
    <div class="panel panel-primary" style="border-radius: 0; margin-bottom: 0;">
        <div class="panel-heading" style="text-align: center; padding: 10px 5px;">

            <div class="panel-title " style="font-size: 1.6em;">
             
    <button type="button" class="btn btn-primary" style="float: right; border: none; font-size: 0.8em;" onclick="location.href='/home.php?code=';">
        <span class="glyphicon glyphicon-log-out"></span>
    </button>
                我的设备
            </div>
        </div>
    </div>
    <div class="container">

        


<br />
<?php 
  $arrlength=count($dev_array);
   for($x=0;$x<$arrlength;$x++)
   {  
   $href="/control.php?id=".$dev_array[$x]['sn'];
   $devname=$dev_array[$x]['name'];        
   echo '<ul class="list-group">';
   echo '<a href='.$href.' class="list-group-item "><span class="badge"></span>';
   echo '<h4 class="list-group-item-heading"><span class="glyphicon glyphicon-eye-close" style="color: #5BC0DE;"></span>&nbsp;物联网设备</h4>';
   echo ' <p class="list-group-item-text" style="text-align: right;">'.$devname.'</p>';
   echo '</a></ul>';
   }
?>  
</body>
</html>

