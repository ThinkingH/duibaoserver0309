





<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta content="yes" name="apple-mobile-web-app-capable">
    <meta name="format-detection" content="telephone=no">
    <meta http-equiv="x-rim-auto-match" content="none">
    <title>中铁国恒-需求发布</title>
    <link href="/content/images/ico/72.png" sizes="72x72" rel="apple-touch-icon-precomposed">
    <link href="/content/images/ico/144.png" sizes="144x144" rel="apple-touch-icon">
    <link href="/content/images/ico/57.png" sizes="57x57" rel="apple-touch-icon">
    <link href="/content/images/ico/114.png" sizes="114x114" rel="apple-touch-icon-precomposed">
    <link href="css/home.css" rel="stylesheet">
    <link href="css/swiper.min.css" rel="stylesheet" />
    <script src="js/jquery-1.10.2.js"></script>
    <script src="js/jquery.unobtrusive-ajax.min.js"></script>
    <script src="js/swiper.js"></script>
    <script> document.addEventListener("touchstart", function () { }, true);</script>
    <!--CurrentSiteInfo:120.26.200.169-->
    
    <script src="js/common.popup.js"></script>
</head>
<body class="home">
            <header>
                <span class="left">
                 <a href="index.php"><i class="iconfont icon-home"></i></a>
                </span>
                <span class="title">需求发布</span>
                <span class="right">
                </span>

            </header>
<article>
<form action="userfabu.php" id="form0" method="post"> 
    <div style="margin-left:22px;">
      <textarea rows="5" cols="41" id="fabucontent" name="fabucontent" style="border:2px solid #fff;border-radius:25px;padding-right:40px;padding-top:10px;padding-left:10px;" placeholder="请填写你的需求信息，我们将尽快给你解答"></textarea>
    </div> 
        <div class="inputbox">
            <div class="item">
                <input id="UserTrueName" name="UserTrueName" placeholder="请输入您的姓名" type="text" value="" />
            </div>
            
             <div class="item">
                <input id="phone" name="phone" placeholder="请输入联系电话" type="text" value="" />
            </div>
            
            <div class="item">
                <input id="CompanyName" name="CompanyName" placeholder="请输入所属公司" type="text" value="" />
            </div>
            
            <div class="item">
                <input id="btnregister" name="btnregister" class="btn btn-primary" type="submit" value="立即需求发布">
            </div>
            
        </div>
        
        <input type="submit" id="submitLogin" style="width:0px; height:0px; border:0px;" />
</form>



</article>

<script type="text/javascript">
    var popup = new commonpopup();
    var wait = 90;

   

    
    $("#btnregister").click(function () {
        if (!$(this).hasClass("btn-primary"))//已经点击需求发布，在服务端没有返回之前不能再次点击需求发布
        {
            return;
        }

        if (validate()) {
            $("#btnregister").removeClass("btn-primary");
            $("#submitLogin").click();
        }
    });

    function validate() {
        if ($.trim($("#phone").val()) == "") {
            popup.alert("错误", "请输入您的手机号码", function () { $("#phone").focus(); });
            return false;
        }

        if ($.trim($("#CompanyName").val()) == "") {
            popup.alert("错误", "请输入公司名称", function () { $("#CompanyName").focus(); });
            return false;
        }
        
        if ($.trim($("#UserTrueName").val()) == "") {
            popup.alert("错误", "请输入联系人", function () { $("#UserTrueName").focus(); });
            return false;
        }

        return true;
    }

   
</script>

    

   


</body>
</html>
