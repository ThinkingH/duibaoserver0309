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
    <title>中铁国恒-今日行情</title>
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
                <span class="title">今日行情</span>
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



                <a href="javascript:void(0)" class="disabled"><i class="iconfont icon-bianji"></i>我要采购</a>

                <a href="javascript:void(0)" class="disabled"><i class="iconfont icon-arrowswap"></i>我要供货</a>


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
    <div class="hangqing">
        <div class="list-wrap">
            <ul>
                            <li>
                                <div class="hd">
                                    <h2><span class="a1">环渤海</span><span class="a2">8.10指数</span></h2>
                                </div>
                                <div class="bd">
                                    <div class="item">
                                        <span class="name">动力煤-BSPI</span>
                                        <span class="price">452</span>

                                        <span class="arrow C-up">16</span>
                                    </div>
                                    <div class="item">
                                        <span class="name">秦港5800kcal</span>
                                        <span class="price">475</span>

                                        <span class="arrow C-up">15</span>
                                    </div>
                                    <div class="item">
                                        <span class="name">秦港5500kcal</span>
                                        <span class="price">450</span>

                                        <span class="arrow C-up">15</span>
                                    </div>
                                    <div class="item">
                                        <span class="name">秦港5000kcal</span>
                                        <span class="price">410</span>

                                        <span class="arrow C-up">10</span>
                                    </div>
                                    <div class="item">
                                        <span class="name">秦港4500kcal</span>
                                        <span class="price">370</span>

                                        <span class="arrow C-up">10</span>
                                    </div>

                                </div>
                            </li>
                            <li>
                                <div class="hd">
                                    <h2><span class="a1">神华</span><span class="a2">8月销售价</span></h2>
                                </div>
                                <div class="bd">
                                    <div class="item">
                                        <span class="name">5500kcal神混1</span>
                                        <span class="price">435</span>

                                        <span class="arrow C-up">18</span>
                                    </div>
                                    <div class="item">
                                        <span class="name">5200kcal神混2</span>
                                        <span class="price">411</span>

                                        <span class="arrow C-up">17</span>
                                    </div>
                                    <div class="item">
                                        <span class="name">5000kcal神混</span>
                                        <span class="price">395</span>

                                        <span class="arrow C-up">16</span>
                                    </div>
                                    <div class="item">
                                        <span class="name">4800kcal神混3</span>
                                        <span class="price">380</span>

                                        <span class="arrow C-up">16</span>
                                    </div>
                                    <div class="item">
                                        <span class="name">4500kcal神混4</span>
                                        <span class="price">356</span>

                                        <span class="arrow C-up">15</span>
                                    </div>
                                    <div class="item">
                                        <span class="name">4300kcal准混5</span>
                                        <span class="price">340</span>

                                        <span class="arrow C-up">11</span>
                                    </div>

                                </div>
                            </li>
                            <li>
                                <div class="hd">
                                    <h2><span class="a1">中煤</span><span class="a2">8月销售价</span></h2>
                                </div>
                                <div class="bd">
                                    <div class="item">
                                        <span class="name">平一混5800kcal</span>
                                        <span class="price">473</span>

                                        <span class="arrow C-up">18</span>
                                    </div>
                                    <div class="item">
                                        <span class="name">平二混5500kcal</span>
                                        <span class="price">435</span>

                                        <span class="arrow C-up">18</span>
                                    </div>
                                    <div class="item">
                                        <span class="name">平三混5000kcal</span>
                                        <span class="price">395</span>

                                        <span class="arrow C-up">16</span>
                                    </div>
                                    <div class="item">
                                        <span class="name">平六混5200kcal</span>
                                        <span class="price">418</span>

                                        <span class="arrow C-up">17</span>
                                    </div>
                                    <div class="item">
                                        <span class="name">平七混5200kcal</span>
                                        <span class="price">411</span>

                                        <span class="arrow C-up">17</span>
                                    </div>
                                    <div class="item">
                                        <span class="name">平八混5500kcal</span>
                                        <span class="price">443</span>

                                        <span class="arrow C-up">18</span>
                                    </div>

                                </div>
                            </li>
                            <li>
                                <div class="hd">
                                    <h2><span class="a1">伊泰</span><span class="a2">8月销售价</span></h2>
                                </div>
                                <div class="bd">
                                    <div class="item">
                                        <span class="name">伊泰3号5500kcal</span>
                                        <span class="price">435</span>

                                        <span class="arrow C-up">18</span>
                                    </div>
                                    <div class="item">
                                        <span class="name">伊泰4号5000kcal</span>
                                        <span class="price">395</span>

                                        <span class="arrow C-up">16</span>
                                    </div>
                                    <div class="item">
                                        <span class="name">伊泰5号5200kcal</span>
                                        <span class="price">411</span>

                                        <span class="arrow C-up">17</span>
                                    </div>

                                </div>
                            </li>
                            <li>
                                <div class="hd">
                                    <h2><span class="a1">同煤</span><span class="a2">8月销售价</span></h2>
                                </div>
                                <div class="bd">
                                    <div class="item">
                                        <span class="name">5800K</span>
                                        <span class="price">459</span>

                                        <span class="arrow C-up">19</span>
                                    </div>
                                    <div class="item">
                                        <span class="name">5500K</span>
                                        <span class="price">435</span>

                                        <span class="arrow C-up">18</span>
                                    </div>
                                    <div class="item">
                                        <span class="name">5000K</span>
                                        <span class="price">395</span>

                                        <span class="arrow C-up">16</span>
                                    </div>
                                    <div class="item">
                                        <span class="name">4500K</span>
                                        <span class="price">356</span>

                                        <span class="arrow C-up">15</span>
                                    </div>

                                </div>
                            </li>

            </ul>
        </div>
    </div>
</article>
    

    <script src="js/jweixin-1.0.0.js"></script>
    <script type="text/javascript">
        //微信JS API 部分代码
        $(document).ready(function () {
            wx.config({
                debug: false,
                appId: 'wx6bcdccdd8918f357',
                timestamp: '1471404263',
                nonceStr: 'zmw981x',
                signature: '684C84980B2DADE938CF74F0FDFECC99AAD06D25',
                jsApiList: [
                'checkJsApi',
                'onMenuShareTimeline',
                'onMenuShareAppMessage',
                'onMenuShareQQ',
                'onMenuShareWeibo',
                'hideMenuItems',
                'showMenuItems',
                'hideAllNonBaseMenuItem',
                'showAllNonBaseMenuItem',
                'translateVoice'
                ]
            });

            wx.ready(function () {
                //var shareImgUrl = "http://www.zhaomei.com/contentv2/images/logo.png";
                //var title = '中铁国恒,找煤更简单 客服热线：400-065-6868';
                //var sharUrl = 'http://www.zhaomei.com';
                //var desc = "服务号时间 工作日:8:30--17:30";

                var shareImgUrl = 'http://wxservice.zhaomei.com//content/images/ico/144.png';
                var title = '各大煤企当月煤炭价格政策--中铁国恒';
                var sharUrl = 'http://wxservice.zhaomei.com/coalprice';
                var desc = '服务时间 周一到周五 9:00-17:30';
                //分享到朋友圈
                wx.onMenuShareTimeline({
                    title: title,
                    link: sharUrl,
                    imgUrl: shareImgUrl,
                    success: function () {
                        // 用户确认分享后执行的回调函数
                        addShare(1);
                    },
                    cancel: function () {
                        // 用户取消分享后执行的回调函数
                    }
                });

                ////分享给朋友
                wx.onMenuShareAppMessage({
                    title: title,
                    desc: desc,
                    link: sharUrl,
                    imgUrl: shareImgUrl,
                    success: function () {
                        // 用户确认分享后执行的回调函数
                        addShare(2);
                    },
                    cancel: function () {
                        // 用户取消分享后执行的回调函数

                    }
                });

                ////分享到QQ
                wx.onMenuShareQQ({
                    title: title,
                    desc: desc,
                    link: sharUrl,
                    imgUrl: shareImgUrl,
                    success: function () {
                        // 用户确认分享后执行的回调函数
                        addShare(2);
                    },
                    cancel: function () {
                        // 用户取消分享后执行的回调函数

                    }
                });

            }); //wx.ready

            //增加分享
            function addShare(type) {
                /* $.ajax({
                     url: "/Tools/ZmAjaxRequst.ashx",
                     dataType: "json",
                     data: {
                         action: "LotteryShareCount",
                         id: mid,
                         openId: openId,
                         webchatId: wechatId,
                         type: type,
                         t: Math.random()
                     },
                     success: function (data) {
                         if (data.success)
                             window.location.href = window.location.href;
                     },
                     error: function () {

                     },
                     timeout: 5000
                 }); */

            } //addShare

        }); //ready
    </script>
    <script>
        var _hmt = _hmt || [];
        (function () {
            var hm = document.createElement("script");
            hm.src = "//hm.baidu.com/hm.js?9b154faf144129c5b8cb08a1f98dbb74";
            var s = document.getElementsByTagName("script")[0];
            s.parentNode.insertBefore(hm, s);
        })();
    </script>
    <script type="text/javascript">
        var _mvq = _mvq || [];
        _mvq.push(['$setAccount', 'm-197294-0']);

        _mvq.push(['$logConversion']);
        (function () {
            var mvl = document.createElement('script');
            mvl.type = 'text/javascript'; mvl.async = true;
            mvl.src = ('https:' == document.location.protocol ? 'https://static-ssl.mediav.com/mvl.js' : 'http://static.mediav.com/mvl.js');
            var s = document.getElementsByTagName('script')[0];
            s.parentNode.insertBefore(mvl, s);
        })();

    </script>
    <script type="text/javascript">
        var cnzz_protocol = (("https:" == document.location.protocol) ? " https://" : " http://");
        document.write(unescape("%3Cspan style='display:none' id='cnzz_stat_icon_1256843001'%3E%3C/span%3E%3Cscript src='" + cnzz_protocol + "s95.cnzz.com/z_stat.php%3Fid%3D1256843001' type='text/javascript'%3E%3C/script%3E"));
    </script>


</body>
</html>
