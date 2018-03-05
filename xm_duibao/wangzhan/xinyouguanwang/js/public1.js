//header
var header = [];
header.push('<div class="header_top">');
header.push('<div class="header_center">');
header.push('<div class="header_top_left">');
header.push('<a href="http://www.xinyouxingkong.com" class="header_top_left_color" target="_blank">www.xinyouxingkong.com</a>');
header.push('</div>');
/*header.push('<div class="header_top_right">');
header.push('<a href="https://bm.baofoo.com/" target="_blank">登录</a> |   ');
header.push('<a href="https://reg.baofoo.com/" target="_blank">注册</a> |');
header.push('<a href="https://bm.baofoo.com/html/help/selfservice.jsp" target="_blank">订单查询</a> |');
header.push('<a href="http://help.baofoo.com">帮助中心</a> |');
header.push('<a href="about/lx.html">联系我们</a> |');
header.push('<a href="http://hr.baofoo.com" style="color:red;font-weight:bold;">加入我们</a> ');

header.push('</div>');*/
header.push('</div>');
header.push('</div>');
header.push('<div class="nav_box">');
header.push('<div class="nav_center">');
header.push(/*'<div class="logo"><a href="index.html"></a></div>'*/);
header.push('<ul class="nav">');
header.push(/*'<li class="channel"><a href="../index.php">首页</a></li>'*/);
header.push('<li class="channel">');
header.push('<a href="companyproduct.html" style="font-weight:bold;">馅饼支付</a>');
/*header.push('<div class="downlist">');
header.push('<a href="list_shoukuan.html">收款类产品</a>');
header.push('<a href="list_fukuan.html">付款类产品</a>');
header.push('<a href="list_jiesuan.html">结算类产品</a>');
header.push('<a href="list_pingtai.html">平台类产品</a>');
header.push('<a href="list_zengzhi.html">增值类产品</a>');
header.push('<a href="http://tiyan.baofoo.com/">产品体验中心</a>');
header.push('</div>');*/
header.push('</li>');
header.push('<li class="channel">');
//header.push('<a href="javascript:;">个人应用中心</a>');
header.push('<div class="downlist">');
header.push('<a>个人账户平台</a>');
header.push('</div>');
header.push('</li>');
header.push('<li class="channel">');
header.push('<a href="shanghujieru.html" style="font-weight:bold;">商户接入</a>');
/*header.push('<div class="downlist">');
header.push('<a href="list_shkt.html">商户开通</a>');
header.push('<a href="list_yxzx.html">营销中心</a>');
header.push('<a href="list_jjfa.html">解决方案</a>');
header.push('<a href="">开放平台</a>');
header.push('</div>');*/
header.push('</li>');
header.push('<li class="channel">');
header.push('<a href="jiejuefangan.html" style="font-weight:bold;">解决方案</a>');
/*header.push('<div class="downlist">');
header.push('<a href="article_aqbz_jgrz.html">权威机构认证</a>');
header.push('<a href="list_zhaq.html">账户安全策略</a>');
header.push('<a href="article_aqbz_zjaq.html">资金安全保障</a>');
header.push('</div>');*/
header.push('</li>');
header.push('<li class="channel">');
header.push('<a href="help.html" style="font-weight:bold;">帮助中心</a>');
header.push('</li>     ');
header.push('</ul>');
header.push('</div>');
header.push('</div>');

$(".header").append(header.join(''));


//footer
var footer = [];

footer.push(' <div class="beian">');

footer.push('<p>Copyright 1996 - 2015 北京信游星空文化传媒有限公司 网络文化经营许可证：京网文【2015】0001-001号 京ICP备14045072号-5 </p>');
footer.push('</div>');

$(".footer").append(footer.join(''));

// nav
$(".nav li").hover(function () {
    $(this).addClass("hover");
}, function () {
    $(this).removeClass("hover");
});


//
$("#qiye").click(function () {
    $("#qiye_box").show();
    $("#geren_box").hide();
    $(this).css("color", "#00bbee");
    $("#geren").css("color", "#fff");
});

$("#geren").click(function () {
    $("#geren_box").show();
    $("#qiye_box").hide();
    $(this).css("color", "#00bbee");
    $("#qiye").css("color", "#fff");
});

function resize_height() {
    var win_height = $(window).height();
    if (win_height >= 900) {
        $(".fbl").css('width', '600px');
    } else if (win_height >= 800 && win_height < 900) {
        $(".fbl").css('width', '500px');
    } else if (win_height >= 700 && win_height < 800) {
        $(".fbl").css('width', '400px');
    } else if (win_height < 700) {
        $(".fbl").css('width', '300px');
    } else {
        $(".fbl").css('width', 'auto');
    }
}
/*resize_height();*/

/*$(window).resize(function () {
    resize_height();
});*/
//统计IP、PV等网站流量相关内容
/*var cnzz_protocol = (("https:" == document.location.protocol) ? " https://" : " http://");document.write(unescape("%3Cspan id='cnzz_stat_icon_1259258375'%3E%3C/span%3E%3Cscript src='" + cnzz_protocol + "s11.cnzz.com/z_stat.php%3Fid%3D1259258375' type='text/javascript'%3E%3C/script%3E"));
//link
$("a").click(function (e) {
    var $link = $(this).attr("href");
    if ($link == '') {
        e.preventDefault();
    }
});*/



/*var _hmt = _hmt || [];
(function() {
    var hm = document.createElement("script");
    hm.src = "//hm.baidu.com/hm.js?98a85c3d3bfdda28aacf5acf991875be";
    var s = document.getElementsByTagName("script")[0];
    s.parentNode.insertBefore(hm, s);
})();
*/

