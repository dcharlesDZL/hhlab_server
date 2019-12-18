<?php 
  require_once "util.php";
  $util = new util();
  $openid = $util->get_openid_session();
  $id = $_GET["id"];
  $device_type = $_GET["device_type"];
  $url_name='yckz003.top';	//域名	
  //$id = '5CCF7F83C402';
 // $url= 'http://'.$_SERVER['SERVER_NAME'].'/history.php?id='.$id;
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>查看设备 </title>
	
	<script src="/js/bootstrap.min.js"></script>
    <script src="/js/jquery.min.js"></script>
    <script src="http://code.highcharts.com/highcharts.js"></script>
    <script src="/js/jsencrypt.min.js"></script>
	<link href="/css/bootstrap.min.css" rel="stylesheet" />
    <script src="/js/browserMqtt.js"></script>
    <link href="/css/styles.css" rel="stylesheet" />
</head>
<body>
<style>
    li.cur {
        font-weight: bold;
        background-color: #2D6FB3;
    }
</style>
<script>
    var dev_id = '<?php echo $id;?>';
    var device_type = '<?php echo $device_type;?>';
    var openid = '<?php echo $openid;?>';
    var time1=0;
    var time_flag=0;
    var my_chart;
    var start,end;
    var server_name='<?php echo $url_name;?>';
    var chart_type = 0;//曲线图类型:0:历史数据,1:实时数据
    $(document).ready(function(){ 
	   $('#myModal').modal('hide');
       //连接mqtt服务器
      
    });
    var history_load=function(json_arr){
      var series = new Array();
      var flag=0;
      //var name;
     // var symbl;
      //获取list数组
      var data = json_arr["list"]
      //var device_type = json_arr["device_type"]
　　　 var name;
      var symbl;
      var arr = create_name_symbl(data[0])
      name = arr[0] , symbl = arr[1];
      /*
      switch(device_type){
          case 1:{
            name = ["湿度"];
            symbl =['%RH','°C'];
            break;
          }
          default:{name = ["温度","湿度"];symbl =['%RH','°C'];}
      }
      */
      for(item in name){
            series[item]= {
                name: name[item],
                yAxis: 1,
                data: []
            }
      }
      //alert(JSON.stringify(series))
      for(a in data){
         if(data[a]){
            var c =data[a];
            //alert(c)
            c = JSON.parse(JSON.parse(c))
            var time = c.time*1000
            var i = 0
            for (b in c){   
                if ("time" !== b){
                    series[i++].data.push({
                        x: new Date(time),
                        y: c[b]
                    });
                }
            }
         }
      }
      my_chart=create_chart('chartDiv','历史数据',name,symbl,2,series);
      
    }
    // 
    //函数功能：获取历史数据接口
    //author:jim_sun
    var showLine = function (i) {
       time1=i;
       chart_type = 0;
       $("#timeSelect li a").removeClass('cur');
       $("#timeSelect li:eq(" + i + ") a").addClass('cur');
	   if(i !== 4){
        　//计算获取历史数据结束时间戳(单位ms)
          end=new Date().getTime();
	   }
       if(i==0){ 
	      // var start1=document.getElementById('timestart');
		   //var start_time=new Date(start1.value).getTime();
		   $('#myModal').modal('show');
	   }else if(i==1){
           //获取一小时历史数据
           start=end-1000*60*60;
       }else if(i==2){
           //获取一天历史数据
           start=end-1000*60*60*24;
       }else if(i==3){
           //获取一周历史数据
           start=end-1000*60*60*24*7;
       }else if(i==4){
          
       }else{
           $("#chartDiv").empty();
       }
       if(i !==0){   
         //向服务器请求历史数据     
         $.ajax({
            url: 'http://php.'+server_name+'/v1/get?'+'start='+parseInt(start/1000)+'&end='+parseInt(end/1000)+'&id='+dev_id,
                type: 'get',
                data: {},
                success: function(data,status){
                   //alert(data)   
                   //alert(data)     
                   var json = JSON.parse(JSON.parse(data))
                   if(1 == json["status"]){
                       //对获取的历史数据进行显示处理
                       if (json["data"]["list"].length){
                        history_load(json["data"]);  
                       }else{
                         alert("该时间段数据缺失")
                       }
                       
                   }else{
                       alert("请求数据失败");  
                   }
                },
                error: function(data,err){
                    alert("请求数据失败");
                }
            });
       }
    }
var deviceoperato = function(type){
    if (0 == type){
        //实时查看数据
        //alert(openid)
        chart_type = 1;
        show_data_now()
    }
}
//实时查看数据函数
var show_data_now = function(){
    var name = ["温度","湿度","速度"]
    var symbl = ["C","%RH","SD"]
    my_chart=create_chart('chartDiv','实时数据',name,symbl,1);
    chart_refresh(my_chart,[0,0,0],new Date().getTime());
}
//定时任务
setInterval(function() {
    if (1 == chart_type){
        var url='http://php.'+server_name+'/v1/get_data?device_id='+dev_id;
            $.ajax({
                url: url,
                type: 'get',
                data: {
                },
                success: function(data,status){
                   //alert(data)
                   var json_data = JSON.parse(data)
                   chart_refresh(my_chart, json_data.data,new Date().getTime());
                },
                error: function(data,err){
                    
                }
            });
       // chart_refresh(my_chart,[12],new Date().getTime());
    }
},1500);
 //生成name和syml数组
 var create_name_symbl = function(data){
      var name = new Array()
      var symbl = new Array()
      var first_arr = JSON.parse(JSON.parse(data))
      for(e in first_arr){
        if ("s" == e){
            name.push('湿度') 
            symbl.push('%RH')
        }
        if ("sd" == e){
            name.push('速度') 
            symbl.push('%su')
        }
        if ("w" == e){
            name.push('温度') 
            symbl.push('C.')
        }
        if ("g" == e){
            name.push('光照度') 
            symbl.push('%sun')
        }
        if ("n" == e){
            name.push('浓度') 
            symbl.push('%no')
        }
        if ("y" == e){
            name.push('压力') 
            symbl.push('%ya')
        }
        if ("z" == e){
            name.push('紫外') 
            symbl.push('%su')
        }
      }
      return [name,symbl];
 }
//创建曲线图函数
var create_chart=function(container,chart_title,line,symbol,kind,serie){
        var chart;
        var json={
            chart: {
                zoomType: 'x',
                renderTo: container,
                type: 'spline',
                animation: Highcharts.svg,
                backgroundColor: '#F5F5F5',
                marginRight: 80,
                events: {
                    load: function() {}
                } 
            },
          
            title: {
                text: chart_title
            },
            xAxis: {
                type: 'datetime',
                tickPixelInterval: 200,
                gridLineWidth: 1//不显示表格线
            },
            yAxis: [{
                gridLineWidth: 1,
                title: {
                    text: line[0]
                },
                labels: {
                   // format: '{value} '+symbol[0]
                },
                plotLines: [{
                    value: 0,
                    width: 1,
                    color: '#808080'
                }],
                opposite:true
            },
                {
                    title: {
                        text: line[1]
                    },
                    labels: {
                        //format: '{value} '+symbol[1]
                    },
                    plotLines: [{
                        value:0,
                        width: 1,
                        color: '#808080'
                    }]
                }],
            tooltip: {
                formatter: function() {
                    return '<b>' + this.series.name + '</b><br/>' + Highcharts.dateFormat('%Y-%m-%d %H:%M:%S', this.x) + '<br/>' + Highcharts.numberFormat(this.y, 2);
                }
            },
            legend: {
                align: 'left',
                verticalAlign: 'top',
                y: 20,
                floating: true,
                borderWidth: 0,
                backgroundColor: (Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#F5F5F5'
            },
            exporting: {
                enabled: false
            }
        }
        Highcharts.setOptions({
            global: {
                useUTC: false
            }
        });
        var plotOptions= {
                      series: {
                marker: {
                    enabled: false
                }
            },
         }
        if(kind == 1){
            var series =  new Array();
            for(a in line){
                series[a]={
                    name: line[a],
                    yAxis: 1,
                    data: (function() { // generate an array of random data
                        var data = [],
                            time = (new Date()).getTime(),
                            i;
                        for (i = -4; i <= 0; i++) {
                            data.push({
                                x: time + i * 1000,
                                y: 0
                            });
                        }
                        return data;
                    })()};
            }
            json.series=series;
        }else if(kind == 2){
            json.series=serie;
        }else{

        }
        json.plotOptions=plotOptions;
        chart = new Highcharts.Chart(json); // set up the updating of the chart each second
        return chart;
    };
    //跳转到设备列表界面
    var goBackToIndex=function(){
        location.href='/list.php';
    };
    //实时查看，实时刷新曲线图
    var chart_refresh = function(chart,data_array,time){
        var i=0;
        for(a in data_array) {
            chart.series[i].addPoint([time, data_array[i]], true, true);
            i++;
        }
    };
</script>

<link href="/css/style4.css" rel="stylesheet" />
<main id="main" class="panel">
    <!------------------图表数据--------------->
    <div id="table_date">
        <div class="toolbar">
            <a href="javascript:goBackToIndex()" class="close_button">
                <img src="/imgs/icon3.png" />
            </a>
        </div>
        <!--------------图表导航----------------->
        <div class="table_nav">
            <ul id="timeSelect">
                <li><a class="cur " id="time0A" href="javascript:showLine(0)" data-toggle="modal" data-target="#myModal">自定义</a></li>
                <li><a href="javascript:showLine(1)">一小时</a></li>
                <li><a href="javascript:showLine(2)">一天</a></li>
                <li><a href="javascript:showLine(3)">一周</a></li>
                <div class="clear"></div>
            </ul>
        </div>
        <!--------------图表----------------->
        <div class="table_con">
            <div id="chartDiv" style="width:100%;height:220px;text-align:center;padding-top:20px;"></div>
        </div>
         <div class="three_data">
            <ul id="sensors">
                    <li id="ds0" onclick="deviceoperato(3)" class=""><p>
                          <br />
                         </p></li>
                    <li id="ds3" onclick="deviceoperato(0)" class=""><span></span><p>
                    实时查看    <br/>
                         </p></li>
                    <li id="ds1" onclick="deviceoperato(1)" class=""><span></span><p>
                            <br />
                         </p></li>             
                <div class="clear"></div>
            </ul>
        </div>
         <!--------------自定义时间组件----------------->
        <div id="timetable"  hidden style="width:100%;height:auto;text-align:center;padding-top:20px;">
		    	<div class="input-group">
			<span class="input-group-addon">@</span>
			<input type="text" class="form-control" placeholder="twitterhandle">
		</div>  
        </div>	
          <!--------------图表----------------->		
        <div class="table_con">
            <div id="chartDiv" style="width:100%;height:220px;text-align:center;padding-top:20px;"></div>
        </div> 
        <!--------------更新时间----------------->
    </div>
</main>
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
					&times;
				</button>
            </div>
            <div class="modal-body">
                <form class="bs-example bs-example-form" role="form">
				     <p>请选择起始时间</p> 
		           <div class="input-group"> 
			         <input type="datetime-local" class="form-control" id="timestart">
		           </div>
				   <br>
				     <p>请选择终止时间</p>
                   <div class="input-group">		    			        
			         <input type="datetime-local" class="form-control" id="timeend">
		           </div>
	           </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" id="time_ok" style="text-align:right;">确定</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<script>
　　//自定义时间数据接口接口
    $('#time_ok').click(function(){
           $('#myModal').modal('hide');
           //计算开始和结束时间戳
		   start=new Date(document.getElementById("timestart").value).getTime()-28800000;
           end=new Date(document.getElementById("timeend").value).getTime()-28800000;
           //调用获取历史数据函数接口
           showLine(4);
    });
</script>
</body>
</html>
