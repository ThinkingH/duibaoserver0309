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
    <title>中铁国恒商城-商品列表</title>
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
    <!--CurrentSiteInfo:121.40.224.55-->
    
</head>
<body class="home">
            <header>
                <span class="left">
                            <a href="index.php"><i class="iconfont icon-home" style="background: url('./images/12.jpg') no-repeat center;"></i></a>

                </span>
                <span class="title">中铁国恒商城</span>
                <span class="right">
                         <a href="javascript:void(0)" id="popmenu" class="iconfont icon-menu" style="background: url('./images/home-icon-menu-1.png') no-repeat center;"></a>
                </span>

            </header>

<div class="popup-menu">
    <a href="javascript:void(0)" class="poptit" id="poptit"><i class="iconfont icon-del"></i></a>
    <a class="title">
        <h2>
            中铁国恒网
        </h2>
    </a>
    <div class="content">
    <a href="login.php"><i class="iconfont icon-user"></i>登录</a>
                <a href="register.php"><i class="iconfont icon-iconfontzhuce"></i>注册</a>
                <!-- <a href="javascript:void(0)" class="disabled"><i class="iconfont icon-bianji"></i>我要采购</a>
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
    <div class="selfmall">
        <div class="list">
            <div class="hd">
                <h2>在售产品<span id="span_count">-</span>个　<span id="span_weight">-</span>吨</h2>
                <div class="pr">
                    <a href="shangcheng.php"><i class="iconfont icon-help"></i>商城说明</a>
                </div>
            </div>


            <div class="bd">
                <ul class="supplyContainer"></ul>
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
    </div>
</article>

    
    <script>
        var firstUrl;
        var totalPage = 1;
        var pageIndex = 0;
        ///初始化
        $(function () {

            ///点击更多的时候触发加载事件
            $(".supplyContainerMore").click(function () {
                LoadSupply(firstUrl, 10);
            });

            LoadSupply("./interface/coalstoreinit.php", 10);
        });

        ///载入现货资源数据
        function LoadSupply(goUrl, pageSize) {
            if (goUrl != firstUrl) {
                $(".supplyContainer").html("");
                firstUrl = goUrl;
                totalPage = 1;
                pageIndex = 0;
            }

            if (totalPage > pageIndex) {
                pageIndex = pageIndex + 1;
                $.ajax({
                    url: firstUrl,
                    data: { pageIndex: pageIndex },
                    type: 'get',
                    dataType: 'json',
                    async: true,
                    beforeSend: function () {
                        $(".supplyContainerMore").html("<div class=\"empty loading\"></div>").show();

                    },
                    success: function (data) {

                        if (data.success) {

                            $("#span_count").text(data.attr.TotalItem);
                            $("#span_weight").text(data.attr.OnlineTotalWeight);
                            totalPage = parseInt(data.attr.TotalItem / pageSize + (data.attr.TotalItem % pageSize == 0 ? 0 : 1));
                            if (data.attr != undefined && data.attr.GoodsSupplyList.length > 0) {
                                for (var i = 0; i < data.attr.GoodsSupplyList.length; i++) {
                                    var item = data.attr.GoodsSupplyList[i];
                                    var html = "<li>"

                                                    + "<a href='./cocalcontent.php?id=" + item.Id + "'>"

                                                    + "<p>"
                                                        + "<i class='I_type'>自营</i>"
                                                        + "<span class='tit'>" + item.CargoName + " " + item.JgWeight + "吨</span>"
                                                        + "<span class='row-right price'><span>" + item.Price + "</span>元/吨</span>"
                                                    + "</p>"
                                                    + "<p>"
                                                        + "<span class='name'>" + item.CoalCateName + "</span>"
                                                        + "<span class='line'>|</span>"
                                                        + "<span class='kcal'>" + item.MainIndex + "</span>"
                                                        + "<span class='row-right time'>" + item.ExpireDate + "结束</span>"
                                                    + "</p>"
                                                    + "<p>"
                                                        + "<span class='addr'>"
                                                        + "   <i class='iconfont icon-weizhi'></i>"
                                                        + "堆存地：" + item.JgAddress
                                                        + "</span>"
                                                        + " <span class='row-right'>"
                                                        + (item.HasPreferentialPrice ? "<i class='I_very'>量大从优</i>" : "")
                                                        + "</span>"
                                                    + "</p>"
                                                    + "</a>"
                                                    + "</li>";


                                    $(".supplyContainer").append(html);
                                    $(".supplyContainerMore").empty().html("更多").show();
                                    if (totalPage == pageIndex) {
                                        $(".supplyContainerMore").html("<span >更新中</span>").show();
                                    }
                                }

                            } else {
                                var tip = "<span>更新中</span>";
                                if ($(".supplyContainer a").length == 0) {
                                    tip = "<div class=\"empty\"><div class=\"con\"> 抱歉！没有找到您需要的内容！</div></div>";
                                }
                                $(".supplyContainer").append(tip);
                                $(".supplyContainerMore").hide();

                            }
                        }
                        else {
                            $("#tipsContent").html(data.message);
                            $(".popup-tips").show().delay(2000).hide(0);
                        }
                    }
                });
            } else {
                $("#supplyContainerMore").html("已经到底了！");

            }
        }



        function ChangeDateFormat(cellval) {
            if (cellval == undefined || cellval == null || cellval == "") {
                return "";
            }
            var date = new Date(parseInt(cellval.replace("/Date(", "").replace(")/", ""), 10));
            var month = date.getMonth() + 1 < 10 ? "0" + (date.getMonth() + 1) : date.getMonth() + 1;
            var currentDate = date.getDate() < 10 ? "0" + date.getDate() : date.getDate();
            return date.getFullYear() + "-" + month + "-" + currentDate;
        }


        function formatNumber(num, cent, isThousand) {
            num = num.toString().replace(/\$|\,/g, '');

            // 检查传入数值为数值类型
            if (isNaN(num))
                num = "0";

            // 获取符号(正/负数)
            sign = (num == (num = Math.abs(num)));

            num = Math.floor(num * Math.pow(10, cent) + 0.50000000001);  // 把指定的小数位先转换成整数.多余的小数位四舍五入
            cents = num % Math.pow(10, cent);              // 求出小数位数值
            num = Math.floor(num / Math.pow(10, cent)).toString();   // 求出整数位数值
            cents = cents.toString();               // 把小数位转换成字符串,以便求小数位长度

            // 补足小数位到指定的位数
            while (cents.length < cent)
                cents = "0" + cents;

            if (isThousand) {
                // 对整数部分进行千分位格式化.
                for (var i = 0; i < Math.floor((num.length - (1 + i)) / 3) ; i++)
                    num = num.substring(0, num.length - (4 * i + 3)) + ',' + num.substring(num.length - (4 * i + 3));
            }

            if (cent > 0)
                return (((sign) ? '' : '-') + num + '.' + cents);
            else
                return (((sign) ? '' : '-') + num);
        }
    </script>

</body>
</html>
