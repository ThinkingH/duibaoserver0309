﻿<!DOCTYPE html>
<html lang="zh-CN">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="keywords" content="" />
<meta name="description" content="" />
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title></title>
<link rel="stylesheet" href="css/bootstrap.css">
<link rel="stylesheet" href="css/jquery.fullpage.css">
<script src="js/jquery-1.10.1.min.js"></script>
<script src="js/jquery.fullpage.min.js"></script>
<script>$(function(){$('.tel_bj').next().css('cursor','pointer').click(function(){window.open("")
})
 
$(".an_btn1").click(function(){
	var system ={win:true,mac:true,xll:true,ipad:true };
	var p =navigator.platform;system.win =p.indexOf("Win") ==0;system.mac =p.indexOf("Mac") ==0;system.x11 =(p =="X11") ||(p.indexOf("Linux") ==0);
	system.ipad =(navigator.userAgent.match(/iPad/i) !=null)?true:false;
	if (system.win ||system.mac ||system.xll||system.ipad) {window.location.href="http://xbapp.xinyouxingkong.com/admin_shop/admin_y.php";} else {alert("请您用pc浏览器访问登录")
} 
});

	$(".an_btn2").click(function(){window.location.href="./register/register.php";});
	$(".an_btn2").hover(function(){$(this).css('cursor','pointer')
})
});window.onload =function(){var screenWd =$(window).width();$('#dowebok').fullpage({continuousVertical:true,afterLoad:function(anchorLink,index){


},onLeave:function(index,direction){

}
});}
</script>
<style type="text/css">
.section1 { background:url(../images/1.jpg) 30% center; background-size:cover}
</style>
</head>
<body>
<div id="dowebok" style="background-image:url(./images/background.jpg)";>
<div class="section section1">
<div class="top">
<div class="top_left"><img src="images/icon.png" width="60" height="60"></div>
<div class="top_right">
<div class="lianx pull-left"><!-- <span class="tel_bj"></span><span>联系我们</span> --></div>
<span class="an_btn2">申请开通</span><span class="an_btn1">登录</span></div>
</div>
 <div class="bottomd"><span class="tel_bj">400-6501208</span></div> 
<div class="col-xs-12 col-sm-12">
<div class="logo_pc"><img style="max-width:80%;" src="images/11.png"></div>
<div class="logo_m"><img style="max-width:80%;" src="images/11.png"></div>
<p class="text-center applyfw">
<!-- <button type="button" class="btn btn-default an_btn2" onclick="window.location.href='./enter/register.php'">申请开通</button> -->
<button type="button" class="btn btn-default an_btn2" onclick="window.location.href='./register/register.php'">申请开通</button>
</div>
</div>

</div>
</body>
</html>