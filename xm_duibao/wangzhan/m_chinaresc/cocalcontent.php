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
    <title>商品详情</title>
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
    
    <style type="text/css">
        .morepage {
            text-align: center;
        }

            .morepage:hover {
                background-color: rgba(55, 69, 144, 0.86);
                color: #fff;
            }

        .nomaiindextag {
            display: none;
        }

        .maiindextag {
            display: block;
        }
    </style>

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
    <a class="title" href="index.php">
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
    <?php include('./interface/coalstore_init.php'); ?> 
</article>


    



    <script type="text/javascript">



        $(function () {
            GetOrderList(1);

            //隐藏掉非主指标
            //$(".nomaiindextag").hide();

            $(".morepage").on("click", function () {
                var currpage = $(".morepage").attr("data-currentpage");
                var page = currpage + 1;
                GetOrderList(page);

            });

        });

        function GetOrderList(page) {

            $.ajax({
                url: '/selfmall/GetOrderList',
                data: { id: $("#supplyId").val(), pageIndex: page },
                type: 'get',
                dataType: 'json',
                success: function (data) {
                    var html = "";
                    if (data.attr != null && data.attr.list.length > 0) {

                        var list = data.attr.list;
                        console.log(list);
                        for (var i = 0; i < list.length; i++) {

                            html += "<tr>";
                            html += "            <td>" + StringFormat(list[i].CompanyNo) + "</td>";
                            html += "            <td>" + StrName(StringFormat(list[i].MemberName)) + "</td>";
                            html += "            <td>" + StringFormat(list[i].CaigouCount) + "</td>";
                            html += "            <td>" + ChangeDateFormat(list[i].CreatedTime, '.') + "</td>";
                            html += "        </tr>";
                        }
                        $(".morepage").attr("data-totalpage", data.attr.totalpage);
                        $(".morepage").attr("data-currentpage", page);

                    } else {
                        html += "<tr>";
                        html += "            <td colspan='4'>暂无数据</td>";
                        html += "        </tr>";

                    }
                    $("#historyOrderList tbody").html(html);
                    SetBtnMorePageStatus();
                }


            });
        }
        function SetBtnMorePageStatus() {
            var totalpage = $(".morepage").attr("data-totalpage");
            var currpage = $(".morepage").attr("data-currentpage");
            if (currpage >= totalpage) {
                $(".morepage").hide();
            } else {
                $(".morepage").show();
            }

        }
        function StringFormat(val) {
            if (val == undefined || val == null) {
                return "";
            } else {
                return val;
            }
        }

        function StrName(Name) {
            if (Name != null && Name != "") {
                if (Name.length == 2)
                    return Name.substring(0, 1) + ("*");
                else if (Name.length == 3)
                    return Name.substring(0, 1) + ("*") + Name.substring(1, 2);
                else if (Name.length == 4)
                    return Name.substring(0, 1) + ("**") + Name.substring(2, 3);
                else if (Name.length == 5)
                    return Name.substring(0, 1) + ("***") + Name.substring(3, 4);
                else
                    return Name.substring(0, 2) + ("*******") + Name.Substring(6, Name.length);
            }
            else
                return "";
        }

        function ChangeDateFormat(val, format) {
            if (val != null) {
                var date = new Date(parseInt(val.replace("/Date(", "").replace(")/", ""), 10));
                //月份为0-11，所以+1，月份小于10时补个0
                var month = date.getMonth() + 1 < 10 ? "0" + (date.getMonth() + 1) : date.getMonth() + 1;
                var currentDate = date.getDate() < 10 ? "0" + date.getDate() : date.getDate();
                return date.getFullYear() + format + month + format + currentDate;
            }

            return "";
        }

    </script>


  
</body>
</html>
