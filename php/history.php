<?php 
  $id = $_GET["id"];
  //$id = '5CCF7F83C402';
  //$url= 'http://'.$_SERVER['SERVER_NAME'].'/history.php?id='.$id;
  $url= '/history1.php?id='.$id;
  $url_name='yckz003.top';	//域名
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>查看设备 </title>
    <link href="/css/weui.min.css" rel="stylesheet" />
    <link href="/css/styles.css" rel="stylesheet" />
</head>
<body>
<style>
    li.cur {
        font-weight: bold;
        background-color: #2D6FB3;
    }
</style>
<script src="/js/jquery-2.1.4.js"></script>
<script src="/js/highcharts.js"></script>
<script src="/js/browserMqtt.js"></script>
<script>
    var dev_id = '<?php echo $id;?>';
    var url = '<?php echo $url;?>';
	var server_name='<?php echo $url_name;?>';
    var time=0;
    var time_flag=0;
    var chart_type=0;
    var my_chart;
    var start,end;
    //var dev_id = '5CCF7F83C533';
    $(document).ready(function(){ 
       var client = mqtt.connect("ws://121.40.189.126:3000");
       client.on("connect", function(topic, payload) {
	    // alert('connec')
         console.log("connect")
         client.subscribe(dev_id);
       });
       client.on("message", function(topic, payload) {
       console.log(payload+'');
	   
       var json=JSON.parse(payload+'');
       if(time==0 && chart_type ==0){
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
					    my_chart=create_chart('chartDiv',"",['温度','湿度'],['°C','%RH'],1);
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
      
    });
     // my_chart=create_chart('chartDiv',"",['温度'],['°C','%RH'],1); 
     //var my_chart=create_chart('chartDiv',"ffff",['温度','湿度','光照强度','速度'],['°C','%RH'],1); 
    });
    var history_load=function(data){
if(chart_type == 0){
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
                       default:{name = ["温度","湿度"];symbl =['%RH','°C'];}
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
       my_chart=create_chart('chartDiv','历史数据',name,symbl,2,series);
}else if(chart_type == 1){
/*
   var series = new Array();
   var flag=0;
   var name;
   for(a in data){
      if(data[a]){
        flag++;
        var c =data[a];
        for(b in c){
           if(flag==1 && b ==0){
             switch(c[b].lx){
                case 1:{
                   name = ["温度","湿度"];break;
                      }
                   default:{name = ["温度","湿度"];}
              }
              for(item in name){
                  series[item]= {
                     name: name[item],
                     data: [0,0,0,0,0,0,0,0,0,0]
                 }
              }
           }else{
              var i=0;
              if(c[b].w){
                 series[i++].data[compare(c[b].w)]++;
                // i++;
              }else if(c[b].s){
                 series[i++].data[compare(c[b].s)]++;
              // i++;
              }else{}
           }
        }
      }
   }    
  create_zhu('chartDiv',series); 
  */
}else{}
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
       }else{
           $("#chartDiv").empty();
       }
       if(i !==0){
            //   end=
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
        if(i == 0){
          location.href='/share.php?id='+'http://php.'+server_name+url;
        }else if(i == 1){
            chart_type=1;
            //my_chart=null;
            $("#chartDiv").empty();
/*
   var series= [{
        name: 'Tokyo',
            data: [49.9, 71.5, 106.4, 129.2, 144.0, 176.0, 135.6, 148.5, 216.4, 194.1]
        }, {
            name: 'New York',
            data: [83.6, 78.8, 98.5, 93.4, 106.0, 84.5, 105.0, 104.3, 91.2, 83.5]
        }, {
            name: 'London',
            data: [48.9, 38.8, 39.3, 41.4, 47.0, 48.3, 59.0, 59.6, 52.4, 65.2]
        }, {
            name: 'Berlin',
            data: [42.4, 33.2, 34.5, 39.7, 52.6, 75.5, 57.4, 60.4, 47.6, 39.1]
   }];  
create_zhu('chartDiv',series);*/
            //$("#time0A").text('柱形图');
        }else if(i == 3){
            chart_type=0;
            $("#time0A").text('实时');
        }else{}
    }
    var chart_refresh=function(chart,data_array,time){
            var i=0;
            for(a in data_array) {
                chart.series[i].addPoint([time, data_array[i]], true, true);
                i++;
            }
    };
var compare= function(num){
  var i=0;
  if(num>0 && num<10){
     i=0;
  }else if(num>10 && num<20){
     i=1;
  }else if(num>20 && num<25){
     i=2;
  }else if(num>25 && num<30){
     i=3;
  }else if(num>30 && num<35){
     i=4;
  }else if(num>35 && num<40){
     i=5;
  }else if(num>40 && num<80){
     i=6;
  }else if(num>80 && num<100){
     i=7;
  }else if(num>100 && num<120){
     i=8;
  }else if(num>120 && num<200){
     i=9;
  }else{
    return;
  }
  return i;
}
var create_zhu=function(container,serie){
     var chart = {
      type: 'column'
   };
   var title = {
      text: ''   
   };
   var subtitle = {
      text: ''  
   };
   var xAxis = {
      categories: ['>0<10','>10<20','>20<25','>25<30','>30<35','>35<40','>40<80','>80<100','>100<120','>120<200'],
      crosshair: true
   };
   var yAxis = {
      min: 0,
      title: {
         text: ''         
      }      
   };
   var tooltip = {
      headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
      pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
         '<td style="padding:0"><b>{point.y:.1f} </b></td></tr>',
      footerFormat: '</table>',
      shared: true,
      useHTML: true
   };
   var plotOptions = {
      column: {
         pointPadding: 0.2,
         borderWidth: 0
      }
   };  
   var credits = {
      enabled: false
   };
   
   
      
   var json = {};   
   json.chart = chart; 
   json.title = title;   
   json.subtitle = subtitle; 
   json.tooltip = tooltip;
   json.xAxis = xAxis;
   json.yAxis = yAxis;  
   json.series = serie;
   json.plotOptions = plotOptions;  
   json.credits = credits;
   $('#'+container).highcharts(json);    
}
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
     var goBackToIndex=function(){
            location.href='/list.php';
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
                <li><a class="cur" id="time0A" href="javascript:showLine(0)">自定义</a></li>
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
      
        <!--------------更新时间----------------->
        <div class="update_time" >
           <!------ <p style="color:#74aac8">上次更新：2018-05-28 13:46</p>---------------->
        </div>
    </div>
</main>
</body>
</html>
