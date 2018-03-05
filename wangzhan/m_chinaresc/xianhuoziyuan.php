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
    <title>中铁国恒-现货资源待售</title>
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
                <span class="title">现货资源</span>
                <span class="right">
                        <a href="javascript:void(0)" id="popmenu" class="iconfont icon-menu" style="background: url('./images/home-icon-menu-1.png') no-repeat center;"></a>
                </span>

            </header>

    <div class="popup-menu">
    <a href="javascript:void(0)" class="poptit" id="poptit"><i class="iconfont icon-del"></i></a>
    <a class="title">
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
<div class="supply">
<!-- <div class="new-array-wrap">
    <a class="item sort on" data-sortkey="none">
        默认
    </a>
    <a class="item sort ">
        

    </a>
    <a id="popupfiltrate" class="item">
     
    </a>
</div> -->

<script>
    var swiper = new Swiper('.Shops-container', {
        slidesPerView: 3,
        paginationClickable: true,
        spaceBetween: 30
    });

    var sorter = {};
    sorter.key = '';
    sorter.type = '';
    var searchKey = '';

    var coalCateToMajorIndexes = {};
    coalCateToMajorIndexes = JSON.parse('{"1":["dwfrlar","qlfar","hffar","qsf"],"2":["njzs","jzchd","qlfar","hffar"],"3":["dwfrlar","qlfar","gdtar","hfar"],"5":["ksqdm40","ksqdm25","nmqd","hfar","jtfyx","fyhqd"],"6":["dwfrlar","qlfar","hfar","qsf","ns","hffar","gdtar","jztz","midu"]}');

    $(function () {

        $("#popupfiltrate").on("click",function(){
            if($(this).hasClass("show")){
                $(this).removeClass("show");
                $(".popup-filtrate").fadeOut();
                $("header,.banner,.inner-list,footer").show();
            }else{
                $(this).addClass("show");
                $t = $(".popup-filtrate");
                $t.animate(10).fadeIn();

                $wh = $(window).height();
                $hh = $(".new-array-wrap").height();






                
                $bh = $wh - $hh - 75;
                $(".bd").height(+$bh+"px");
                $("header,.banner,.inner-list,footer").hide();
            }
        });
        $("#hide-filtrate,.submit").click(function(){
            $(".popup-filtrate").fadeOut();
            $("#popupfiltrate").removeClass("show");
            $("header,.banner,.inner-list,footer").show();
        })

        $(".new-array-wrap>.sort").click(function () {
            var sortKey = $(this).data("sortkey");

            if(sortKey=="price" || sortKey=="weight")
            {
                if(sorter.key==sortKey)
                {
                    sorter.type = sorter.type == "Asc" ? "Desc" : "Asc";
                }
                else{
                    sorter.type="Asc";
                    sorter.key= sortKey;
                }
            }
            else{
                sorter.key='';
            }

            search();
        });

        $("a.reset").click(function () {
            $(".searchitem").removeClass("on");
        });

        $("a.sure").click(function () {
            $(".popup-filtrate").fadeOut();
            searchKey="";
            sorter.key="";
            search();
        });

        var urlParameters = getUrlParameters();
        if (searchKey != ""|| searchKey!=null)
        {
            urlParameters += "&search_key=" + searchKey;
        }

       LoadSupply("./interface/xianhuoziyuaninit.php?" + urlParameters, 10,0);
       // LoadSupply("http://wxservice.zhaomei.com/supply/getgoodssupply?" + urlParameters, 10,0);
    })

    function search() {
        var url = "./interface/xianhuoziyuaninit.php?" + getUrlParameters();
        if(searchKey!="" || searchKey!=null)
        {
            url+="&search_key=" + searchKey;
        }
        window.location.href = url;
    }

    function getUrlParameters() {
        var url = "sort_key=" + sorter.key + "&sort_type=" + sorter.type + "&datacategory=" + "0";

        $(".con").each(function () {
            var itemKeys = "";
            $(this).find(".on").each(function () {
                var itemKey = $(this).data("searchitemkey");
                itemKeys += "," + itemKey;
            });

            if ($.trim(itemKeys) != "") {
                var groupKey = $(this).data("searchgroupkey");
                url += "&" + groupKey + "=" + itemKeys.substr(1, itemKeys.length - 1);
            }
        });

        return url;
    }

    function onSearchItemSelected(selfElement) {
        if ($(selfElement).hasClass("on")) {
            $(selfElement).removeClass("on");
        }
        else {
            $(selfElement).addClass("on");
        }

        if ($(selfElement).hasClass("coalcate")) {//点击煤炭分类的操作
            $(selfElement).siblings().removeClass("on");

            if ($(selfElement).hasClass("on")) {
                var majorIndexes = coalCateToMajorIndexes[$(selfElement).data("searchitemkey")];

                var sortedGroups = $(".indexsearchgroup").sort(function (a, b) {
                    var aGroupKey = $(a).data("searchgroupkey");
                    var bGroupKey = $(b).data("searchgroupkey");

                    var aGroupKeyIndex = majorIndexes.indexOf(aGroupKey);
                    var bGroupKeyIndex = majorIndexes.indexOf(bGroupKey);

                    if (aGroupKeyIndex == bGroupKeyIndex) {
                        return 0;
                    } else if (aGroupKeyIndex == -1) {
                        return 1;
                    } else if (bGroupKeyIndex == -1) {
                        return -1;
                    }
                    else {
                        return aGroupKeyIndex > bGroupKeyIndex ? 1 : -1;
                    }
                });

                sortedGroups.find(".searchitem").removeClass("on");

                $(".indexsearchgroup").remove();
                $(".searchgroupcontainer").append(sortedGroups);
            }
        }
    }

</script>
<!-- 文件链接的加载 -->
<script src="js/supplycommon1.js"></script>

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
</article>
<footer>
    <div class="row fix-menu">
         <a href="index.php" class="row3 item"><i class="iconfont icon-home" style="background: url('./images/s.jpg') no-repeat center;"></i>首页</a>
        <a href="javascript:void(0)" onclick="location.reload();" class="row3 item on"><i class="iconfont icon-wenjian" style="background: url('./images/x.jpg') no-repeat center;"></i>现货资源</a>
                <a href="http://www.chinaresc.com" class="row3 item"><i class="iconfont icon-iconfontzhuce" style="background: url('./images/d.jpg') no-repeat center;"></i>登录注册</a>

    </div>
</footer>



    

  


</body>
</html>
