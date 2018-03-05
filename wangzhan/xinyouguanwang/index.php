<?php
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>信游星空文化传媒</title>
<link href="css/reset_base.css" rel="stylesheet">
<link href="css/style.css" rel="stylesheet">
  <link rel="icon" href="/favicon.ico" type="image/x-icon">  
  <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
  <script src="js/jquery.min.js"></script>
  <script src="js/slider.js"></script>
  
</head>
<body style="width:100%;margin:0px;padding:0px;">
<?php  include("header.php");  ?>
<!-- end header-->


<script type="text/javascript">
$(function() {
	var wid = $(window).width();
	if(wid<1000) {
		$("body").css('width',1450);
	}
});
</script>

<style>

.aixuexi img{width:100%;height:100%;}

.flexslider {
	margin: 0px auto 6px;
	position: relative;
	width: 100%;
	height: 520px;
	overflow: hidden;
	zoom: 1;
}

.flexslider .slides li {
	width: 100%;
	height: 100%;
}

.flex-direction-nav a {
	width: 70px;
	height: 70px;
	line-height: 99em;
	overflow: hidden;
	margin: -35px 0 0;
	display: block;
	background: url(images/ad_ctr.png) no-repeat;
	position: absolute;
	top: 50%;
	z-index: 10;
	cursor: pointer;
	opacity: 0;
	filter: alpha(opacity=0);
	-webkit-transition: all .3s ease;
	border-radius: 35px;
}

.flex-direction-nav .flex-next {
	background-position: 0 -70px;
	right: 0;
}

.flex-direction-nav .flex-prev {
	left: 0;
}

.flexslider:hover .flex-next {
	opacity: 0.8;
	filter: alpha(opacity=25);
}

.flexslider:hover .flex-prev {
	opacity: 0.8;
	filter: alpha(opacity=25);
}

.flexslider:hover .flex-next:hover,
.flexslider:hover .flex-prev:hover {
	opacity: 1;
	filter: alpha(opacity=50);
}

.flex-control-nav {
	width: 100%;
	position: absolute;
	bottom: 10px;
	text-align: center;
}

.flex-control-nav li {
	margin: 0 2px;
	display: inline-block;
	zoom: 1;
	*display: inline;
}

.flex-control-paging li a {
	background: url(images/dot.png) no-repeat 0 -16px;
	display: block;
	height: 16px;
	overflow: hidden;
	text-indent: -99em;
	width: 16px;
	cursor: pointer;
}

.flex-control-paging li a.flex-active,
.flex-control-paging li.active a {
	background-position: 0 0;
}

.flexslider .slides a img {
	width: 100%;
	height: 520px;
	display: block;
}



</style>



<div id="banner_tabs" class="flexslider">
	<ul class="slides">
		<li>
			<a title="" href="#">
				<img width="1920" height="520" alt="" style="background: url(images/newpic/banner1.png) no-repeat center;" src="images/alpha.png">
			</a>
		</li>
		<li>
			<a title="" href="#">
				<img width="1920" height="520" alt="" style="background: url(images/newpic/banner2.png) no-repeat center;" src="images/alpha.png">
			</a>
		</li>
<!-- 		<li> -->
<!-- 			<a title="" href="#"> 
				<img width="1920" height="520" alt="" style="background: url(images/newpic/banner3.png) no-repeat center;" src="images/alpha.png">
<!-- 			</a> -->
<!-- 		</li> -->
	</ul>
	<ul class="flex-direction-nav">
		<li><a class="flex-prev" href="javascript:;">Previous</a></li>
		<li><a class="flex-next" href="javascript:;">Next</a></li>
	</ul>
	<ol id="bannerCtrl" class="flex-control-nav flex-control-paging">
		<li><a>1</a></li>
		<li><a>2</a></li>
<!-- 		<li><a>2</a></li> -->
	</ol>
</div>

<script type="text/javascript">
$(function() {
	var bannerSlider = new Slider($('#banner_tabs'), {
		time: 5000,
		delay: 400,
		event: 'hover',
		auto: true,
		mode: 'fade',
		controller: $('#bannerCtrl'),
		activeControllerCls: 'active'
	});
	$('#banner_tabs .flex-prev').click(function() {
		bannerSlider.prev()
	});
	$('#banner_tabs .flex-next').click(function() {
		bannerSlider.next()
	});

})
</script>





<!-- end banber-->
<div class="iMa clearfix" style="width:100%;height: 1036px;">

  <div class="iTit">
    <div class="tittop tc"><span style="font-weight:bold; font-size:30px;">兑宝</span><span>我们的兑宝产品简介</span></div>
  </div>
  <div class="clear"></div>
  <center>
  	<br><br>
  	<table width="80%">
  	<tr><td align="center"><div class="pic"><img src="images/f11.png"></div></td><td align="center"><div class="pic"><img src="images/f12.png"></div></td><td align="center"><div class="pic"><img src="images/f14.png"></div></td></tr>
  	<tr><td align="center"><span>我们正在研发</span></td><td align="center"><span>我们正在代理</span></td><td align="center"><span>我们的目标</span></td></tr>
  	<tr><td align="center"><strong>一款帮你省钱的生活服务类APP</strong></td><td align="center"><strong>为用户寻找免费、打折的商品</strong></td><td align="center"><strong>尽可能的给用户带来无限的惊喜</strong></td></tr>
  	</table>
  	
  	</center>
  	<br><br><br><br><br><br><br>
  	<div class="iTit bm_51">
   <div class="tittop tc"><span style="font-weight:bold; font-size:30px;">移动互联智慧营销</span><span>我们的移动互联智慧营销简介</span></div>
  </div>
  <div class="clear"></div>
  	
  	<center>
  	<br>
  	<table width="80%">
  	<tr><td align="center"><div class="pic"><img src="images/f21.png"></div></td><td align="center"><div class="pic"><img src="images/f22.png"></div></td><td align="center"><div class="pic"><img src="images/f23.png"></div></td></tr>
  	<tr><td align="center"><strong>多种多样的营销决策数据支撑展现</strong></td><td align="center"><strong>灵活自如的营销智慧投放</strong></td><td align="center"><strong>实时跟踪数据分析营销智慧模式</strong></td></tr>
  	<tr><td align="center"></td><td align="center"></td><td align="center"></td></tr>
  	</table>
  	
  	</center>
  	<br><br><br><br><br><br><br><br><br>
<!--   	<div class="iTit bm_1f">
    <div class="tittop tc"><span style="font-weight:bold; font-size:30px;">馅饼支付</span><span>我们的馅饼支付介绍</span></div>
<!--   </div> -->
<!--   <div class="clear"></div> -->
  
<!--   	<center> -->
<!--   	<br> -->
<!--   	<table width="80%"> -->
<!--   	<tr><td align="center"><div class="pic"><img src="images/h1.png"></td><td align="center"><div class="pic"><img src="images/h2.png"></td><td align="center"><div class="pic"><img src="images/h3.png"></td></tr> -->
<!--   	<tr><td align="center"><strong>H5页面支付</strong></td><td align="center"><strong>SDK支付</strong></td><td align="center"><strong>短消息支付</strong></td></tr> -->
<!--   	<tr><td align="center"></td><td align="center"></td><td align="center"></td></tr> -->
  	</table>
  	
  	
  	
  	
  	
  	
</div>
<!-- end iMa-->
<div class="foot">
  <div class="footer tc">
    <p class="pt50">Copyright © 1996 - 2015  北京信游星空文化传媒股份有限公司  增值电信业务经营许可证：B2-20100119  京ICP备14045072号-5</p>
	<br/>
	<p class="pt50">
	北京信游星空文化传媒股份有限公司全资子公司北京指脉无限科技有限公司声明：&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br>
	北京指脉无限科技有限公司与北京位智天下技术有限公司授权终止(2017-02-23)<a href="./gaozhihan.jpg" target="_blank">告知函文件下载</a>。
	</p>
  </div>
</div>
<!-- end foot-->
</body>
</html>