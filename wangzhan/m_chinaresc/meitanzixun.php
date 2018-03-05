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
    <title>中铁国恒-煤炭资讯</title>
    <link href="/content/images/ico/72.png" sizes="72x72" rel="apple-touch-icon-precomposed">
    <link href="/content/images/ico/144.png" sizes="144x144" rel="apple-touch-icon">
    <link href="/content/images/ico/57.png" sizes="57x57" rel="apple-touch-icon">
    <link href="/content/images/ico/114.png" sizes="114x114" rel="apple-touch-icon-precomposed">
    <link href="css/home.css" rel="stylesheet">
    <link href="css/swiper.min.css" rel="stylesheet" />
    <script src="js/jquery-1.10.2.js"></script>
     <script src="js/jquery-1.10.2.min.js"></script>
    <script src="js/jquery.unobtrusive-ajax.min.js"></script>
    <script src="js/swiper.js"></script>
    <script> document.addEventListener("touchstart", function () { }, true);</script>
    <!--CurrentSiteInfo:120.26.200.169-->
    
</head>
<body class="home">
            <header>
                <span class="left">
                  <a href="index.php">
                   <i class="iconfont icon-home" style="background: url('./images/12.jpg') no-repeat center;"></i> 
                  </a>

                </span>
                <span class="title">煤炭资讯</span>
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

    

<article class="no-menu">
    <div class="news">
        <div class="list-wrap ">
            <div class="empty loading"></div>
        </div>
    </div>
</article>

    
    <script>
        $(function () {
            PostNewsList(1);
        });
        function PostNewsList(page,obj)
        {
            GetLoading(obj);
            $.ajax({
                url: "./interface/zxinit.php",
                data: { page: page },
                type: "Post",
                success: function (json) {
                       if (json.success) {
                        console.log(json.attr);
                        SetNewsList(json.attr);
                    }else {
                         alert(json);
                         $("#a1").append(json);
                         //alert('jj'); 
                    }   
                }
            });
        }

        
        /* "<a href=\"http://127.0.0.1:8001/newzhongtie/m_chinaresc/zixuncontent.php?id=" + data.news[i].id + "\">" */
        function SetNewsList(data)
        {
            var html = "";
            for (var i = 0; i < data.news.length; i++){
            
                html += "<a href=\"./zixuncontent.php?id=" + data.news[i].id + "\">";
                html += "<h2>" + data.news[i].name + "</h2>";
                html += "<span class=\"time\">发布时间：" + data.news[i].date + "</span>";
                html += "</a>";
            }
            if (data.page < data.pageCount){
                html += "<a class=\"btn-all\" href=\"javascript:void(0)\" onclick=\"PostNewsList(" + (data.page + 1) + ",this)\">更多</a>";
            }

             $(".list-wrap .loading").remove();

             if (data.page == 1) {
                $(".list-wrap").html(html);
            }else {
                $(".list-wrap").append(html);
            }
        }
        
        function GetLoading(obj) {
            var html = "<div class=\"empty loading\"></div>";
            if (obj != "") {
                $(obj).parent().append(html);
                $(obj).remove();
            }
        }
    </script>
    


</body>
</html>
