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
    <title>中铁国恒-煤炭采购信息</title>
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
    
    <script src="js/goodscaigou.js"></script>

</head>
<body class="home">
            <header><!-- icon-left.jpg -->
                <span class="left">
                            <a href="index.php"><i class="iconfont icon-home" style="background: url('./images/12.jpg') no-repeat center;"></i></a>

                </span>
                <span class="title">采购信息</span>
                <span class="right">
                        <a href="javascript:void(0)" id="popmenu" class="iconfont icon-menu" style="background: url('./images/home-icon-menu-1.png') no-repeat center;"></a>
                </span>

            </header>

<div class="popup-menu">
    <a href="javascript:void(0)" class="poptit" id="poptit"><i class="iconfont icon-del"></i></a>
    <a class="title" href="index.php">
        <h2>
            中铁国恒
        </h2>
    </a>
    <div class="content">
    <a href="login.php"><i class="iconfont icon-user"></i>登录</a>
                <a href="register.php"><i class="iconfont icon-iconfontzhuce"></i>注册</a>



              

               


    </div>
</div>
<div class="popup"></div>
<script>
    $(function () {
        $("#popmenu").click(function () {
            $(".popup-menu").animate({
                left: "0"
            }, 600);
            $(".popup").show();
        })
        $(".popup,#poptit").click(function () {
            $(".popup-menu").animate({
                left: "-100%"
            }, 600);
            $(".popup").animate({
                opacity: 'toggle'
            }, 600);
        })
    })
</script>
    
<article class="no-menu">
    <div class="caigou_list">
        <div class="empty loading">
        </div>
    </div>
    <div class="popup-tips animated bounceIn">
    <div class="tit"><i class="icon-look"></i><span id="indexs-title">采购指标</span></div>
    <div class="content">
        <table>
            <thead>
                <tr>
                    <th>热值</th>
                    <th>灰份</th>
                    <th>全硫份</th>
                    <th>挥发份</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>>=4500</td>
                    <td>>=20</td>
                    <td><=0.8</td>
                    <td>>=20</td>
                </tr>
                <tr>
                    <td>－</td>
                    <td>－</td>
                    <td>-</td>
                    <td>-</td>
                </tr>
            </tbody>
        </table>
        
    </div>
</div>
<div class="popup-white"></div>
<script>

    $(function () {
        $('.popup-white').bind("click", function () {
            $('.popup-tips').hide()
            $('.popup-white').hide();
        })
    });
    function ShowIndexs(obj, str, type) {
        event.stopImmediatePropagation();//取消事件冒泡；
        var table = $(obj).parent().parent().find("table");
        if (type == 1)
        {
            table = $(obj).parents(".pro-wrap").find("table");
        }
        console.log(table.html());
        ShowIndexsHtml(table);//显示指标
        $("#indexs-title").html(str);
        $('.popup-tips').show();
        $('.popup-white').show();

    }

    function ShowIndexsHtml(obj) {
        var tips = $(".popup-tips .content table");
        tips.html(obj.html());
    }
</script>
</article>

</body>
</html>
