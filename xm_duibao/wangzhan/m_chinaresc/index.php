
<?php 
session_start();
?>




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
    <title>中铁国恒网-中铁国恒够简单</title>
    <link href="/content/images/ico/72.png" sizes="72x72" rel="apple-touch-icon-precomposed">
    <link href="/content/images/ico/144.png" sizes="144x144" rel="apple-touch-icon">
    <link href="/content/images/ico/57.png" sizes="57x57" rel="apple-touch-icon">
    <link href="/content/images/ico/114.png" sizes="114x114" rel="apple-touch-icon-precomposed">
    <link href="css/home.css" rel="stylesheet">
    <link href="css/swiper.min.css" rel="stylesheet" />
    <script src="js/jquery-1.10.2.min.js"></script>
    <script src="js/jquery.unobtrusive-ajax.min.js"></script>
    <script src="js/swiper.js"></script>
    <script> document.addEventListener("touchstart", function () { }, true);</script>
    
    
</head>

<body class="home">

<!-- 搜索框 -->
<header>
    <!-- <span class="left"><a href="javascript:void(0);" id="popregion"><span class="location">北京</span> </a></span> -->
    <span class="title"><a class="search-btn" style="width:130%;" href="javascript:void(0);">输入品名/热值等搜索现货</a></span>
    <span class="right"><a href="javascript:void(0)" id="popmenu"><i class="iconfont icon-menu" style="background: url('./images/home-icon-menu-1.png') no-repeat center;"></i></a></span>
</header>
<div class="popup-search">
    <div class="hd">
        <form>
            <input class="search-input" id="search_key" type="search" value="" />
        </form>
        <div class="keys">
            <a href="javascript:void(0);" class="autocomp-close"><i class="iconfont icon-del"></i></a>
            <a href="javascript:void(0);" class="s-btn">搜索</a>
        </div>
        
        <a href="javascript:void(0);" class="search-hide">取消</a>
    </div>
    <div class="bd">
        <div class="suggest-group">
            <ul class="list row words"></ul>
            <a href="javascript:void(0);" class="suggest-opt">清除历史记录</a>
        </div>
        <div class="column keyword">
            <div class="column-head">
                <h2>热门搜索</h2>
            </div><!-- ./interface/xianhuoziyuaninit.php -->
            <div class="column-body"><!--./interface/xianhuoziyuaninit.php?datacategory=1&search_key=动力煤  -->
                <ul class="list row-three"><!-- http://127.0.0.1:8001/newzhongtie/m_chinaresc/xianhuoziyuan.php -->
                                <li class="list-item">
                                    <a href="./xianhuoziyuan.php?datacategory=1&search_key=动力煤">动力煤</a>
                                </li>
                                <li class="list-item">
                                    <a href="./xianhuoziyuan.php?datacategory=1&search_key=炼焦煤">炼焦煤</a>
                                </li>
                                <li class="list-item">
                                    <a href="./xianhuoziyuan.php?datacategory=1&search_key=块煤">块煤</a>
                                </li>
                               

                </ul>
            </div>
        </div>
    </div>
</div>
<script>
    var dataCategory = '1';
    //搜索按钮
    $(function () {
        var name = "Keyword";
        var day = 365;

        //setCookie(name, "slsdfkj||&&sdkfjls||&&skdfj||&&sdkfj||&&ksdjf", day);
        $(".search-btn").click(function () {
            $("header,article,footer").hide();
            $(".popup-search").show();
        })
        $(".search-input").focusin(function () {
            var list = getCookie(name);
            if (list != null) {
                setliList(list);
            }
            $(".suggest-group").show();
            $(".autocomp-close").show();
        })
        $(".search-hide").click(function () {
            $("header,article,footer").show();
            $(".popup-search").hide();
        })
        $(".s-btn").click(function () {
            JumpHref(name, day, dataCategory)
        });
        $(".search-input").keydown(function (e) {
            if (!e) e = window.event;//火狐中是 window.event
            if ((e.keyCode || e.which) == 13) {
                JumpHref(name, day, dataCategory);
            }
        });
        $(".autocomp-close").click(function () {
            $(".search-input").val("");
        });
        $(".suggest-opt").click(function () {
            var value = "";
            setCookie(name, value, day);
            setliList(value);
        });
    });
    function JumpHref(name, day, dataCategory)
    {
        var value = $(".search-input").val();
        PushCooike(name, day, value);
        location.href = "./interface/xianhuoziyuan.php?datacategory=" + dataCategory + "&search_key=" + value;
        return false;
    }
    function setliList(list) {
        var array = list.split("||&&");
        var html = "";
        for (var i = 0; i < array.length; i++) {
            html += "<li class=\"list-item\"><a href=\"./interface/xianhuoziyuan.php?datacategory=" + dataCategory + "&search_key=" + array[i] + "\">" + array[i] + "</a></li>";
        }
        $(".words").html(html);
    }

    function PushCooike(name, day, value) {
        var list = getCookie(name);
        if (list != null) {
            var array = list.split("||&&");
            array = GetIndexs(array, value);
            list = value + "||&&" + array.join("||&&");
        }
        else {
            list = value;
        }
        setCookie(name, list, day);
    }

    function GetIndexs(array, val)
    {
        var n_array = [];
        for (var i = 0; i < array.length; i++)
        {
            if (array[i] != val)
            {
                n_array.push(array[i]);
            }
        }
        if (n_array.length >= 5)
            n_array.pop();
        return n_array;
    }


    function setCookie(CKname, CKvalue, duration, CKpath, CKdomain) {
        var NewDate = new Date();
        NewDate.setTime(NewDate.getTime() + duration * 24 * 60 * 60 * 1000);
        document.cookie = CKname + "=" + escape(CKvalue) + (duration ? ";expires=" + NewDate.toGMTString() : "") + (CKpath ? ";path=" + CKpath : "") + (CKdomain ? ";domain=" + CKdomain : "");
    }
    function getCookie(CKname) {
        var arrCookie = document.cookie.match(new RegExp("(^| )" + CKname + "=([^;]*)(;|$)"));
        if (arrCookie != null)
            return unescape(arrCookie[2]);
        else
            return null;
    }
    function deleteCookie(CKname) {
        document.cookie = CKname + "=;expires=" + (new Date(0)).toGMTString();
    }
</script>


<!-- 搜索框结束 -->

 
<article>
    
<div class="fullSlide">
    <div class="swiper-wrapper">
            <div class="swiper-slide">
                <a>

                    <img src="picture/b4.jpg" alt="煤矿代理" />
                </a>
            </div>
            <div class="swiper-slide">
                <a>

                    <img src="picture/b1.jpg" alt="物流强强联手" />
                </a>
            </div>
            <div class="swiper-slide">
                <a>

                    <img src="picture/b3.jpg" alt="中铁国恒网APP广告图" />
                </a>
            </div>
            <div class="swiper-slide">
                <a>

                    <img src="picture/b2.jpg" alt="微信首页-金融" />
                </a>
            </div>
    </div>
    <div class="swiper-pagination" id="full-pagination"></div>
</div>

  
    <div class="menu">
    <a href="xianhuoziyuan.php" class="item">
        <i class="icon-xhsc"></i>
        现货资源
    </a>

    <a href="coalstore.php" class="item ">
        <i class="icon-gkzy"></i>
        中铁国恒商城
        <i class="hot"></i>
    </a>
  
    <a href="company.php" class="item nrb">
        <i class="icon-kfzy"></i>
        名企专区
    </a>
    <a href="caigouxinxi.php" class="item nbb">
        <i class="icon-cgbj"></i>
        采购信息
    </a>
    <a href="jinrihangqing.php" class="item nbb">
        <i class="icon-schq"></i>
        今日行情
    </a>
    <a href="meitanzixun.php" class="item nrb nbb">
        <i class="icon-rmzx"></i>
        煤炭资讯
    </a>
</div>

    <div class="banner">
        <a href="wuliu.php" title="中铁国恒物流">
            <img src="picture/banner1.jpg" alt="" />
        </a>
    </div>
    <div class="piece">
    <div class="hd"><h2>电厂库存</h2></div>
    <div class="bd">
        <table class="n-tb">
            <thead>
                <tr>
                    <th class="n-1">电厂名称</th>
                    <th class="n-2">库存量(万吨)</th>
                    <th class="n-3">日耗(万吨)</th>
                    <th class="n-4">可用天数</th>
                </tr>
            </thead>
            <tbody>
                            <tr>
                                <td class="n-t1">上电</td>
                                <td class="n-t2">27.50</td>
                                <td class="n-t3 C-up">2.80</td>
                                <td class="n-t4">9.8</td>
                            </tr>
                            <tr>
                                <td class="n-t1">浙电</td>
                                <td class="n-t2">164.00</td>
                                <td class="n-t3 C-up">15.40</td>
                                <td class="n-t4">10.6</td>
                            </tr>
                            <tr>
                                <td class="n-t1">粤电</td>
                                <td class="n-t2">275.00</td>
                                <td class="n-t3 C-up">10.40</td>
                                <td class="n-t4">26.4</td>
                            </tr>
                            <tr>
                                <td class="n-t1">国电</td>
                                <td class="n-t2">186.70</td>
                                <td class="n-t3 C-up">15.30</td>
                                <td class="n-t4">12.2</td>
                            </tr>
                            <tr>
                                <td class="n-t1">大唐</td>
                                <td class="n-t2">53.00</td>
                                <td class="n-t3 C-up">6.60</td>
                                <td class="n-t4">8.0</td>
                            </tr>
                            <tr>
                                <td class="n-t1">华能</td>
                                <td class="n-t2">309.00</td>
                                <td class="n-t3 C-up">22.00</td>
                                <td class="n-t4">14.0</td>
                            </tr>


            </tbody>
        </table>

    </div>
</div>
<div class="piece piece3">
    <div class="hd"><h2>港口库存</h2></div>
    <div class="bd">
        <table class="n-tb">
            <thead>
                <tr>
                    <th class="n-1">港口名称</th>
                    <th class="n-2">库存量</th>
                    <th class="n-3">增减</th>
                    <th class="n-4">单位</th>
                </tr>
            </thead>
            <tbody>
                            <tr>
                                <td class="n-t1">秦皇岛</td>
                                <td class="n-t2">298.50</td>
                                <td class="n-t3 C-up">10.50</td>
                                <td class="n-t4">万吨</td>
                            </tr>
                            <tr>
                                <td class="n-t1">曹妃甸</td>
                                <td class="n-t2">170.00</td>
                                <td class="n-t3 C-up">3.00</td>
                                <td class="n-t4">万吨</td>
                            </tr>
                            <tr>
                                <td class="n-t1">天津港</td>
                                <td class="n-t2">268.20</td>
                                <td class="n-t3 C-up">2.60</td>
                                <td class="n-t4">万吨</td>
                            </tr>
                            <tr>
                                <td class="n-t1">黄骅港</td>
                                <td class="n-t2">168.80</td>
                                <td class="n-t3 C-down">-20.30</td>
                                <td class="n-t4">万吨</td>
                            </tr>
                            <tr>
                                <td class="n-t1">广州港</td>
                                <td class="n-t2">188.60</td>
                                <td class="n-t3 C-up">0.90</td>
                                <td class="n-t4">万吨</td>
                            </tr>

            </tbody>
        </table>

    </div>
</div>
<div class="piece piece2">
    <div class="hd"><h2>海运价格</h2></div>
    <div class="bd">
        <div class="haiyunslide">
            <div class="swiper-wrapper">
                            <div class="swiper-slide">
                                <table class="n-tb">
                                    <thead>
                                        <tr>
                                            <th class="n-1">起始港</th>
                                            <th class="n-2">目的港</th>
                                            <th class="n-3">船型</th>
                                            <th class="n-4">运费(元/吨)</th>
                                            <th class="n-5">变化</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                                    <tr>
                                                        <td class="n-t1">天津港</td>
                                                        <td class="n-t2">镇江</td>
                                                        <td class="n-t3">1-1.5万吨</td>
                                                        <td class="n-t4">43.40</td>
                                                        <td class="n-t4 C-down">
                                                                -0.30
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="n-t1">秦皇岛</td>
                                                        <td class="n-t2">南京</td>
                                                        <td class="n-t3">3-4万吨</td>
                                                        <td class="n-t4">43.90</td>
                                                        <td class="n-t4 C-down">
                                                                -0.20
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="n-t1">黄骅港</td>
                                                        <td class="n-t2">上海</td>
                                                        <td class="n-t3">3-4万吨</td>
                                                        <td class="n-t4">37.20</td>
                                                        <td class="n-t4 C-down">
                                                                -0.20
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="n-t1">曹妃甸</td>
                                                        <td class="n-t2">宁波</td>
                                                        <td class="n-t3">4-5万吨</td>
                                                        <td class="n-t4">36.80</td>
                                                        <td class="n-t4 C-down">
                                                                -0.10
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="n-t1">天津港</td>
                                                        <td class="n-t2">上海</td>
                                                        <td class="n-t3">2-3万吨</td>
                                                        <td class="n-t4">37.90</td>
                                                        <td class="n-t4 C-down">
                                                                -0.10
                                                        </td>
                                                    </tr>

                                    </tbody>
                                </table>
                            </div>
                            <div class="swiper-slide">
                                <table class="n-tb">
                                    <thead>
                                        <tr>
                                            <th class="n-1">起始港</th>
                                            <th class="n-2">目的港</th>
                                            <th class="n-3">船型</th>
                                            <th class="n-4">运费(元/吨)</th>
                                            <th class="n-5">变化</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                                    <tr>
                                                        <td class="n-t1">秦皇岛</td>
                                                        <td class="n-t2">上海</td>
                                                        <td class="n-t3">4-5万吨</td>
                                                        <td class="n-t4">35.90</td>
                                                        <td class="n-t4 C-down">
                                                                -0.30
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="n-t1">秦皇岛</td>
                                                        <td class="n-t2">张家港</td>
                                                        <td class="n-t3">2-3万吨</td>
                                                        <td class="n-t4">40.40</td>
                                                        <td class="n-t4 C-down">
                                                                -0.10
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="n-t1">秦皇岛</td>
                                                        <td class="n-t2">宁波</td>
                                                        <td class="n-t3">1.5-2万吨</td>
                                                        <td class="n-t4">37.30</td>
                                                        <td class="n-t4 C-down">
                                                                -0.20
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="n-t1">秦皇岛</td>
                                                        <td class="n-t2">福州</td>
                                                        <td class="n-t3">3-4万吨</td>
                                                        <td class="n-t4">41.00</td>
                                                        <td class="n-t4 C-down">
                                                                -0.10
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="n-t1">秦皇岛</td>
                                                        <td class="n-t2">广州港</td>
                                                        <td class="n-t3">5-6万吨</td>
                                                        <td class="n-t4">45.30</td>
                                                        <td class="n-t4 C-down">
                                                                -0.40
                                                        </td>
                                                    </tr>

                                    </tbody>
                                </table>
                            </div>

            </div>
            <div class="swiper-pagination" id="haiyun-pagination"></div>
        </div>

    </div>
</div>

    <div class="popup-menu">
    <a href="javascript:void(0)" class="poptit" id="poptit"><i class="iconfont icon-del"></i></a>
    <a class="title" href="index.php">
        <h2>
            中铁国恒网
        </h2>
    </a>
    <div class="content"><!-- iconfont icon-iconfontzhuce -->
    			<a href="login.php"><i class=""></i>登录</a>
               <!--  <a href="http://www.chinaresc.com/index.php/Login/register"><i class=""></i>注册</a> -->
               <a href="register.php"><i class=""></i>注册</a>
				<a href="fabu.php"><i class=""></i>发布需求</a>
                 
             


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
</article>

    <!-- <div id="IsWxBrowser" style="">-->
        
       <!--  <div class="popup-attention-bottom"> -->
           <!--  <div class="content">
                <div class="img"><img src="picture/attention-1.png" alt="中铁国恒网APP" /></div>
                <h2>中铁国恒网APP-立即下载</h2>
                <p>煤炭交易必备神器</p>
            </div> -->
            
           <!--  <div class="close">
                <i class="iconfont">×</i>
            </div> -->
       <!--  </div> -->
    <!-- </div>-->

<footer>
    <div class="row fix-menu">
        <div class="wrap flex">
            <a href="index.php" class="row3 item on"><i class="iconfont icon-home" style="background: url('./images/s1.jpg') no-repeat center;"></i>首页</a>
            <a href="xianhuoziyuan.php" class="row3 item"><i class="iconfont icon-home" style="background: url('./images/x1.jpg') no-repeat center;"></i>现货资源</a>
                    <a href="http://www.chinaresc.com?type=kk" class="row3 item"><i class="iconfont icon-iconfontzhuce" style="background: url('./images/d.jpg') no-repeat center;"></i>电脑版</a>

        </div>
    </div>
</footer>

    
 <script src="js/index.js"></script>


    
</body>
</html>
