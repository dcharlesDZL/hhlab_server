<?php 
    session_start(); // 初始化session
	$appId = 'wx41b952dcef318471' ; //记得换成你自己测试号的信息
    $appSecret = '4df4346aa3f2167d6d86acb24c3b15e5';
    require_once "jssdk.php";
    $jssdk = new JSSDK($appId,$appSecret);//这里改成自己的
    //echo $user->getAccessToken();
      $signPackage= $jssdk->getSignPackage();
	$m = new MongoClient();    // 连接到mongodb
    $db = $m->test;            // 选择一个数据库
    $collection = $db->user; // 选择集合
	$cursor = $collection->find( array('_id' => $_SESSION['id']));
// 迭代显示文档标题
    $dev_array;
    foreach ($cursor as $document) {
		 $dev_array = $document['data'];
    }
?>
<!DOCTYPE html>
<html>
<head>  
    <title>智能交互</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="maximum-scale=1.0,user-scalable=no">
<style> 
body {

}
.qqBox{
	width: 100%;
	height: 100%;
	border: 1px solid #ccc;
	position: absolute;
	top: 0;
	bottom: 0;
	left: 0;
	right: 0;
	margin:auto;
	display: -webkit-box;
	-webkit-box-orient: vertical;
}
.BoxHead{
    background: #4682B4;
	width: 100%;
	height: 9%;
	font-size: 34px;
    border-radius:10px;
	display: -webkit-box;
	-webkit-box-orient: horizontal;
}
.headImg{
	width: 44px;
	height: 44px;
	border-radius: 50%;
	margin:0 10px;
}
.headImg img{
	width: 100%;
	height: 100%;
	border-radius:50%;
}
.internetName{
	width: auto;
	height: 52px;
	line-height: 52px;
	color: white;
}
.context{
   
    -webkit-box-flex: 1;
    display: -webkit-box;
    -webkit-box-orient: horizontal;
}
.conLeft{
    border-radius:10%;
	border-style:solid;
	border-color:#C0C0C0;
	width: 25%;
	height:100%;
	overflow: auto;
}
.conLeft::-webkit-scrollbar{
	width: 0;
}
.conLeft ul{
	list-style: none;
	margin: 0;
	padding: 0;
}
.conLeft li{
    border-radius:10%;
	border-style:solid;
	border-color:white;
	margin-top: 5px;
	width: 100%;
	height: 62px;
	display: -webkit-box;
	-webkit-box-orient: horizontal;
}
.conLeft li .liLeft{
	width:30%;
	height: 100%;
}
.liLeft img{
	margin: 10px;	
}
.liRight span{
	display:block;
	font-size: 30px;
	height: 31px;
	line-height: 31px;
}
.liRight span:last-child{
	font-size: 14px;
	color: #767676;
	line-height:15px;
	overflow: hidden;
}
.conRight{
	-webkit-box-flex: 1;
	display: -webkit-box;
	-webkit-box-orient: vertical;
}
.Righthead{
	width: 100%;
	height: 80px;
	border-bottom: 1px solid #ccc;
}
.headName{
	width: auto;
	height: 100%;
	line-height: 42px;
	margin-left: 20px;
	font-family: "微软雅黑";
	font-size: 35px;
	float: left;
}
.headConfig{
	width: 20%;
	float: right;
	height: 100%;
	
}
.headConfig ul{
	list-style: none;
	margin: 0;
	padding: 0;
	display: -webkit-box;
	-webkit-box-orient: horizontal;
}
.headConfig li{
	margin:10px 5px;
}
.RightCont{
	-webkit-box-flex: 1;
	overflow-y: scroll;
}

.RightCont::-webkit-scrollbar{
	width: 15px;
}
.RightCont ul{
	list-style: none;
	margin: 0;
	padding: 0;
}
.RightCont li{
	width: 100%;
	height: 50px;
	/*display: -webkit-box;
	-webkit-box-orient: horizontal;*/
	margin-top: 10px;
}
.nesHead{
	width: 44px;
	height: 44px;
	border-radius: 50%;
	margin-left:15px ;
	float: left;
}
.nesHead img{
	width: 44px;
	height: 44px;
	border-radius: 100%;
}
.news{
	width: auto;
	height: 40px;
	background: #4682B4;
	padding:5px 20px;
	margin: 4px;
	line-height: 40px;
	font-size: 34px;
	border-radius:10px;
	margin-left: 10px;
	position: relative;
	float: left;
}

.answerHead{
	width: 44px;
	height: 44px;
	border-radius: 50%;
	margin-left:15px ;
	float: right;
}
.answerHead img{
	width: 44px;
	height: 44px;
	border-radius: 50%;
}
.answers{
	width: auto;
	height: 30px;
	background: #4682B4;
	padding:5px 20px;
	margin: 4px;
	line-height: 30px;
	font-size: 34px;
	border-radius:10px;
	margin-left: 10px;
	position: relative;
	float: right;
}
.answers .jiao{
	position: absolute;
	right: -8px;
	top: 10px;
    
}
.RightFoot{
	width: 100%;
	height: 118px;
	border-top: 1px solid #ccc;
	position: relative;
}
.footTop{
	width: 100%;
	height: auto;
}
.footTop ul{
	list-style: none;
	margin: 0;
	padding: 0;
	display: -webkit-box;
	-webkit-box-orient: horizontal;
}
.footTop li{
	margin: 5px 10px;
}
.footTop li img{
	width:60px;
	height:60px;
}
.sendBtn{
	background: #4682B4;
	border: none;
	position: absolute;
	bottom: 2px;
	right: 10px;
	color: white;
	font-size: 34px;
}
.bg{
	background: #C0C0C0;
}
.newsList{
	
	
}
</style>
</head>
<body>
<div class="qqBox" style="height:100%;width: 100%"">
    <div class="BoxHead" >
        <div class="headImg">
            <img src="/imgs/user.jpg"/>
        </div>
        <div class="internetName">我的用户名</div>
    </div>
    <div class="context" >
        <div class="conLeft" >
            <ul >
<?php 
 $arrlength=count($dev_array);
for($x=0;$x<$arrlength;$x++){
    $id=$dev_array[$x]['sn'];
    $devname=$dev_array[$x]['name'];       	
    echo '<li><div class="liLeft"><img src="/imgs/20170926103645_04.jpg"/></div>';
    echo ' <div class="liRight">';
    echo '<span class="intername" id="'.$id.'">'.$devname.'</span>';
    echo ' <span class="infor"></span>';
    echo '</li>';
}
?> 
            </ul>
        </div>
        <div class="conRight">
            <div class="Righthead">
                <div class="headName"></div>
                <div class="headConfig">
                  
                </div>
            </div>
            <div class="RightCont">
                <ul class="newsList" >
                   
                </ul>
            </div>
            <div class="RightFoot"  style="height:250px">
                <div class="emjon">
               
                </div>
                <div class="footTop" >
                        <ul>
                            <li class="voice"><img src="/imgs/20170926103645_39.jpg"/></li>
                        </ul>       
                </div>
                <div class="inputBox" >
                    <textarea id="dope" style="width: 99%;height: 75px;background-color: rgba(0,0,0,0); border: none;outline: none;	font-size: 34px;
" name="" rows="" cols=""></textarea>
                    <button class="sendBtn" style="width:50%;height:40%;background-color:#4682B4;border-radius:15px;">发送(s)</button>
                </div>
            </div>
        </div>
    </div>
	
</div>

<script type="text/javascript" src="/js/jquery.min.js"></script>
<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
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
   
      'startRecord',  
      'stopRecord',  
      'playVoice' ,
	  'translateVoice'
    ]
  });
</script>
<script>
var dev_id;
var voice_flag=0;

$('.conLeft li').on('click',function(){
		$(this).addClass('bg').siblings().removeClass('bg');
		var intername=$(this).children('.liRight').children('.intername').text();
		dev_id = $(this).children('.liRight').children('.intername').attr("id");
		$('.headName').text(intername);
		$('.newsList').html('');
	})
	$('.sendBtn').on('click',function(){
	if(voice_flag){
	   voice_flag=0;
	   //hideLoading();
	    wx.stopRecord({  
          success: function (res) {  
            localId = res.localId; 
			
           // uploadVoice();	
			wx.translateVoice({
               localId: localId, // 需要识别的音频的本地Id，由录音相关接口获得
               isShowProgressTips: 0, // 默认为1，显示进度提示
               success: function (res) {
				  message_handle(res.translateResult);
                       //alert(res.translateResult); // 语音识别的结果
               }
           });
          },
         	 fail: function(res) {
                alert(JSON.stringify(res));
            }
	  
      }); 
	}else{
        var news=$('#dope').val();
		if(news==''){
			alert('不能为空');
		}else{
			$('#dope').val('');
			message_handle(news);
	    }
	}
		
	
	})
	$('.voice').on("click",function(){
	  voice_flag=1;
	 // showLoading()
      // alert("向左滑动!");
	   wx.startRecord();
    });
      
    /*
	$('.sendBtn').on("swipeleft",function(){
      // alert("向左滑动!");
	   wx.startRecord();
    });
	$(".sendBtn").on("swiperight",function(){
	   wx.stopRecord({  
          success: function (res) {  
            localId = res.localId; 
			
           // uploadVoice();	
			wx.translateVoice({
               localId: localId, // 需要识别的音频的本地Id，由录音相关接口获得
               isShowProgressTips: 0, // 默认为1，显示进度提示
               success: function (res) {
				  message_handle(res.translateResult);
                       //alert(res.translateResult); // 语音识别的结果
               }
           });
          },
         	 fail: function(res) {
                alert(JSON.stringify(res));
            }
	  
      });  
	  */
	  /*
	  wx.playVoice({  
          localId: localId // 需要播放的音频的本地ID，由stopRecord接口获得  
      }); */
       ///alert("向右滑动!");
  //  });
	function message_handle(str){
	    sendmessage(str);
	}
		function sendmessage(news){
	    var str='';
		str+='<li>'+
				'<div class="nesHead"><img src="/imgs/user.jpg"/></div>'+
				'<div class="news">'+news+'</div>'+
			'</li>';
		$('.newsList').append(str);
		//setTimeout(answers,1000); 
		$('.conLeft').find('li.bg').children('.liRight').children('.infor').text(news);
	}
	function sendanswers(str){
	    var answer='';
		answer+='<li>'+
					'<div class="answerHead"><img src="/imgs/user.jpg"/></div>'+
					'<div class="answers">'+str+'</div>'+
				'</li>';
		$('.newsList').append(answer);	
		$('.RightCont').scrollTop($('.RightCont')[0].scrollHeight );
	}


	/*
	$('.ExP').on('mouseenter',function(){
		$('.emjon').show();
	})
	$('.emjon').on('mouseleave',function(){
		$('.emjon').hide();
	})
	$('.emjon li').on('click',function(){
		var imgSrc=$(this).children('img').attr('src');
		var str="";
		str+='<li>'+
				'<div class="nesHead"><img src="img/6.jpg"/></div>'+
				'<div class="news"><img class="jiao" src="img/20170926103645_03_02.jpg"><img class="Expr" src="'+imgSrc+'"></div>'+
			'</li>';
		$('.newsList').append(str);
		$('.emjon').hide();
		$('.RightCont').scrollTop($('.RightCont')[0].scrollHeight );
	})*/
</script>

</body>
</html>