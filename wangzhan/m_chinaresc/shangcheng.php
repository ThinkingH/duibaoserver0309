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
    <title>中铁国恒商城-商城说明</title>
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
    
</head>
<body class="home">
            <header>
                <span class="left">
                            <a href="index.php"><i class="iconfont icon-home" style="background: url('./images/12.jpg') no-repeat center;"></i></a>

                </span>
                <span class="title">商城说明</span>
                <span class="right">
                         <a href="javascript:void(0)" id="popmenu" class="iconfont icon-menu" style="background: url('./images/home-icon-menu-1.png') no-repeat center;"></a>
                </span>

            </header>

<div class="popup-menu">
    <a href="javascript:void(0)" class="poptit" id="poptit"><i class="iconfont icon-del"></i></a>
    <a class="title" href="index.php">
        <h2>
            中铁国恒网
        </h2>
    </a>
    <div class="content">
    <a href="login.php"><i class="iconfont icon-user"></i>登录</a>
                <a href="register.php"><i class="iconfont icon-iconfontzhuce"></i>注册</a>


<!-- 
                <a href="javascript:void(0)" class="disabled"><i class="iconfont icon-bianji"></i>我要采购</a>

                <a href="javascript:void(0)" class="disabled"><i class="iconfont icon-arrowswap"></i>我要供货</a>
 -->

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
    


<article>
    <form action="">
        <div class="selfmall">
            <div class="explain">
                <div class="hd">
                    <div class="tit">
                        <i class="Icon1"></i>安全保障
                    </div>
                    <div class="con">
                        <div class="row">
                            <div class="row3">
                                <div class="item">
                                    <i class="Hicon Hicon1"></i>
                                    优质煤源
                                </div>
                            </div>
                            <div class="row3">
                                <div class="item">
                                    <i class="Hicon Hicon2"></i>
                                    实地考察
                                </div>
                            </div>
                            <div class="row3">
                                <div class="item">
                                    <i class="Hicon Hicon3"></i>
                                    专业检验
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bd">
                    <div class="tit">
                        <i class="Icon1"></i>下单流程
                    </div>
                    <div class="con">
                        <img src="picture/selfmall-explain-2.png" alt="" />
                    </div>
                </div>
            </div>
        </div>
    </form>
</article>
    

    


</body>
</html>
