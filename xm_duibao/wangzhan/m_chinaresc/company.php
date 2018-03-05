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
    <title>中铁国恒-名企专区</title>
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
                <span class="title">名企专区</span>
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
               <!--  <a href="javascript:void(0)" class="disabled"><i class="iconfont icon-bianji"></i>我要采购</a>
                <a href="javascript:void(0)" class="disabled"><i class="iconfont icon-arrowswap"></i>我要供货</a> -->

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
    <div class="kfzybox">
        <div class="banner"><img src="picture/banner3-1.jpg" alt="" /></div>
        <div class="bigV">
            <div class="tit">
                <h2><i class="l"></i>入驻名企商家 <i class="r"></i></h2>
                <div class="hr"></div>
            </div>
            
            <div class="Shops-container">
                <div class="swiper-wrapper" id="companyList">
                                <div class="swiper-slide">
                                    <a  class="item " companyid="111">
                                        <img src="picture/201510231914537087.png" title="中煤能源" />
                                        <div class="cont">中煤能源</div>
                                    </a>
                                </div>
                                <div class="swiper-slide">
                                  
                                    <a  class="item " companyid="147">
                                        <img src="picture/201510231915141172.png" title="国电燃料" />
                                        <div class="cont">国电燃料</div>
                                    </a>

                                </div>
                                <div class="swiper-slide">
                                  
                                    <a  class="item " companyid="12567">
                                        <img src="picture/201512231523253269.jpg" title="华电煤业" />
                                        <div class="cont">华电煤业</div>
                                    </a>

                                </div>
                </div>
            </div>
        </div>
        
        <div class="supply">
            <div class="tit"><h2>现货资源</h2></div>
            
<div class="inner-list supplyContainer">

</div>
<a class="btn-all supplyContainerMore">更多</a>




<div class="popup-tips animated bounceIn">
    <div class="tit">提示</div>
    <div class="content">
        <div class="error" id="tipsContent">

        </div>
    </div>
</div>
        </div>
        <div class="tooltipp"></div>
    </div>
</article>



<footer>
    <div class="row fix-menu">
        <a href="index.php" class="row3 item"><i class="iconfont icon-home" style="background: url('./images/s.jpg') no-repeat center;"></i>首页</a>
        <a href="javascript:void(0)" onclick="location.reload();" class="row3 item on"><i class="iconfont icon-wenjian" style="background: url('./images/x.jpg') no-repeat center;"></i>名企专区</a>
         <a href="http://www.chinaresc.com" class="row3 item"><i class="iconfont icon-iconfontzhuce" style="background: url('./images/d.jpg') no-repeat center;"></i>电脑版</a>

    </div>
</footer>


<script src="js/supplycommon.js"></script>
<script src="js/vipcompany.js"></script>
<script type="text/javascript">
</script>
</body>
</html>
