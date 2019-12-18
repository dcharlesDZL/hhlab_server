<?php 
//$url= 'http://'.$_SERVER['SERVER_NAME'].'/history.php?id=';
/*
    session_start(); // 初始化session
    //echo $_SESSION['id']; //保存某个session信息
    $url= 'http://'.$_SERVER['SERVER_NAME'].'/history.php?id=';
    $manager = new MongoDB\Driver\Manager("mongodb://localhost:27017");
    $filter = ['_id' => $_SESSION['id']];
    $options = [];
    $query = new MongoDB\Driver\Query($filter, $optsions);
    $cursor = $manager->executeQuery('test.user', $query);
    $dev_array;
    foreach ($cursor as $document) {
      $dev_array = json_encode($document->data);
    }   */
	session_start(); // 初始化session
	$url= 'http://'.$_SERVER['SERVER_NAME'].'/history.php?id=';
	$url_name='yckz003.top';	//域名	
	$m = new MongoClient();    // 连接到mongodb
    $db = $m->Hh;            // 选择一个数据库
    $collection = $db->user; // 选择集合
	$cursor = $collection->find( array('_id' => $_SESSION['id']));
// 迭代显示文档标题
    $dev_array=[];
    foreach ($cursor as $document) {
		 $dev_array = json_encode($document['data']);
    }
	if(!$dev_array)
	    $dev_array='[]';
   // echo $dev_array;
   //$dev_array='[]';
?>
<!DOCTYPE html>
<html>
<head>
    <title>物理网设备地图模式</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta content="telephone=no" name="format-detection">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no">
    <script src="/js/jquery.min.js"></script>
    <script src="http://cdn.amazeui.org/amazeui/2.7.2/js/amazeui.min.js"></script>
    <script src="/js/highcharts.js" type="text/javascript"></script>
    <script src="/js/browserMqtt.js"></script>
    <link href="http://cdn.amazeui.org/amazeui/2.7.2/css/amazeui.css" rel="stylesheet" />
    <link href="/css/bootstrap.min.css" rel="stylesheet" />
    <link href="/css/weui.min.css" rel="stylesheet" />
    <link href="/css/styles.css" rel="stylesheet" />
</head>
<body>
<script charset="utf-8" src="//map.qq.com/api/js?v=2.exp&key=JGEBZ-ZSZ66-XPPSR-E7S4F-Q7I3T-NEBNX"></script>
<script>
var dev_info=<?php echo $dev_array;?>;
var map;
var time=0;
var time_flag=0;
var dev_id;
var client;
var url = '<?php echo $url;?>';
var server_name='<?php echo $url_name;?>';
var start,end;
    $(document).ready(function() {
      //  alert(dev_info[0].sn);
        initMap();
       addMarker();
        
       client = mqtt.connect("ws://121.40.189.126:3000");
       client.on("connect", function(topic, payload) {
         console.log("connect")
        // client.subscribe(dev_id);
       });
       client.on("message", function(topic, payload) {
       //console.log(payload+'');
       if(''+topic == dev_id){
       var json=JSON.parse(payload+'');
       if(time==0){
            if(time_flag){
                 var b=new Array();
                 if(json.w){b.push(json.w)}
                 if(json.s){b.push(json.s)} 
                 if(json.g){b.push(json.g)}
                 if(json.n){b.push(json.n)}
                 chart_refresh(my_chart,b,new Date().getTime());
            }else{
              switch(json.lx){
                    case 0: {//
                        my_chart=create_chart('chartDiv',"",['温度','湿度'],['°C','%RH'],1); 
                        break;
                    }
                    case 1: {//
                        my_chart=create_chart('chartDiv',"",['温度','湿度'],['°C','%RH'],1);
                        break;
                    }
                    case 2: {//      
                        break;
                    }
                    case 3: {//
                        break;
                    }
                    case 4: {//
                        break;
                    }
                    
                    default:{
                        return;
                    }
                }
               time_flag=1;
           }
       }
      }
    });

    });
    var initMap = function () {
    //    $("#qq-map").height($(window).height() - 50);
        map = new qq.maps.Map(document.getElementById("qq-map"), { zoom: 8, mapTypeControl: false,center: new qq.maps.LatLng(39.916527,116.397128)});
        var citylocation = new qq.maps.CityService({
            complete: function (result) {
                map.setCenter(result.detail.latLng);
            }
        });
        citylocation.searchLocalCity();
    };
var addMarker = function () {
    for(a in dev_info){
          var item = dev_info[a];
          item.num=a;
          if (!item.marker) {
              item.marker = new qq.maps.Marker({
                position: new qq.maps.LatLng(item.lat, item.lng),
               // icon: icon,
                map: map
            });
            qq.maps.event.addListener(item.marker, "click", function () {
                //alert(item.sn);
                show_info(item.num);
            });
          }          
     }
};
var show_info=function(index){
    //alert(index);
    var item= dev_info[index];
    //client.Unubscribe(dev_id);
    dev_id= item.sn;
    client.subscribe(dev_id);
    $("#devname_h1").text(item.name);
    $("#chartDiv").empty();
    $('#my-popup').modal('open');  
};
    var history_load=function(data){
      var series = new Array();
      var flag=0;
      var name;
      var symbl;
      for(a in data){
         if(data[a]){
            flag++;
            var c =data[a];
            for(b in c){
                if(flag==1 && b ==0){
                   switch(c[b].lx){
                      case 1:{
                         name = ["温度","湿度"];
                         symbl =['%RH','°C'];
                         break;
                         }
                       default:{name = ["温度","w"];symbl =['%RH','°C'];}
                      }
                      for(item in name){
                         series[item]= {
                         name: name[item],
                         yAxis: 1,
                         data: []
                      }
               }
                 }else{
                      var time = c[b].time*1000;
                      var i=0;
                      if(c[b].w){
                          series[i++].data.push({
                             x: new Date(time),
                             y: c[b].w
                           });
                       }if(c[b].s){
                            series[i++].data.push({
                             x: new Date(time),
                             y: c[b].s
                           });
                       } 
            
                } 
                // console.log(c[b].lx)
            }
         }
      }
       create_chart('chartDiv','历史数据',name,symbl,2,series);
    }
    var showLine = function (i) {
       time=i;
       time_flag=0;
       $("#timeSelect li a").removeClass('cur');
       $("#timeSelect li:eq(" + i + ") a").addClass('cur');
       end=new Date().getTime();
       if(i==1){
           start=end-1000*60*60*24;
       }else if(i==2){
           start=end-1000*60*60*24*7;
       }else if(i==3){
           start=end-1000*60*60*24*29;
       }else{}
       if(i !==0){
               end=
               $.ajax({
                url: 'http://data.'+server_name+'/v1/get?'+'start='+parseInt(start/43200000)+'&end='+parseInt(end/43200000)+'&id='+dev_id,
                type: 'get',
                data: {},
                success: function(data,status){
                   // console.log(data)
                    history_load(JSON.parse(data));
                },
                error: function(data,err){
                    alert("erro");
                }
            });
       }
    }
     var deviceoperato = function (i) {
        if(i == 3){
          location.href='/share.php?id='+url+''+dev_id;
        }
    }
    var chart_refresh=function(chart,data_array,time){
            var i=0;
            for(a in data_array) {
                chart.series[i].addPoint([time, data_array[i]], true, true);
                i++;
            }
    };
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
</script>

<div id="qq-map" style="height:100%;width:100%;"></div>
<!------弹出层------>
<div class="am-popup" id="my-popup">
  <div class="am-popup-inner">
    <div class="am-popup-hd">
      <h4 class="am-popup-title" id="devname_h1"></h4>
      <span data-am-modal-close
            class="am-close">&times;</span>
    </div>

   <main id="main" class="panel">
    <!------------------图表数据--------------->
    <div id="table_date">
        <div class="toolbar">
        </div>
        <!--------------图表导航----------------->
        <div class="table_nav">
            <ul id="timeSelect">
                <li><a class="cur" id="time0A" href="javascript:showLine(0)">实时</a></li>
                <li><a href="javascript:showLine(1)">一天</a></li>
                <li><a href="javascript:showLine(2)">一周</a></li>
                <li><a href="javascript:showLine(3)">一月</a></li>
                <div class="clear"></div>
            </ul>
        </div>
        <!--------------图表----------------->
        <div class="table_con">
            <div id="chartDiv" style="width:100%;height:220px;text-align:center;padding-top:20px;"></div>

        </div>
        
    </div>
</main>
  </div>
</div>

</body>
</html>
