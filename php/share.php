<?php 
  $id = $_GET["id"];
 // echo $id;
?>
<!DOCTYPE>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ko" lang="ko">
<head>
    <title>请扫描二维码</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=no" />
    <script type="text/javascript" src="http://cdn.bootcss.com/jquery/2.1.1/jquery.min.js"></script>
    <script type="text/javascript" src="http://static.runoob.com/assets/qrcode/qrcode.min.js"></script>
    <link rel="stylesheet" href="/css/bootstrap.min.css">  
    <script src="/js/bootstrap.min.js"></script>
</head>
<body>
<div class="panel panel-primary" style="border-radius: 0; margin-bottom: 0;">
    <div class="panel-heading" style="text-align: center; padding: 10px 5px;">
        <div class="panel-title " style="font-size: 1.6em;">
            共享设备二维码
        </div>
    </div>
</div>
<div id="qrcode" style="width:200px; height:200px;position:absolute;top:50%;left:50%;margin:-100px 0 0 -100px"></div>

<script type="text/javascript">
    var qrcode = new QRCode(document.getElementById("qrcode"), {
        width : 200,
        height : 200
    });
    function makeCode () {
        var elText = '<?php echo $id;?>' ;
        if (!elText) {
            alert("Input a text");
            elText.focus();
            return;
        }
        qrcode.makeCode(elText);
    }

    makeCode();
    $("#text").
    on("blur", function () {
        makeCode();
    }).
    on("keydown", function (e) {
        if (e.keyCode == 13) {
            makeCode();
        }
    });
</script>

</body>
</html>
