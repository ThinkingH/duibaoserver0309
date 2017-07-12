<?php if (!defined('THINK_PATH')) exit();?>﻿<!DOCTYPE html PUBLIC "-//W3C//Dtd html 4.0 Transitional//EN">
<html>
<head>
<meta http-equiv=Content-Type content="text/html; charset=utf-8">

<link rel="stylesheet" type="text/css" href="__PUBLIC__/Css/admin.css" />
<script type="text/javascript" src="__PUBLIC__/Js/jquery.min.js"></script>
<script type="text/javascript" src="__PUBLIC__/Js/themain.js"></script>
<script type="text/javascript">
$("document").ready(function(){
	
	//刷新该页面12秒后执行通知检测
	setTimeout(retry, 12000);
	
	//刷新该页面120秒后执行在线状态检测
	setTimeout(updatetimeip, 12000);
	
	//每6分钟更新一次通知
	setInterval(retry, 360000);
	
	//每11分钟更新一次在线状态
	setInterval(updatetimeip, 660000);
	
	//每3秒钟检测一次进度条，并调用进度条隐藏函数
	setInterval(hidetiao, 3000);
	
	$("#gonggao_show").click(function() {
		
		$.post("__URL__/ajax", {'gonggao':'gonggao'},
			function(data){
				alert(data);
		});
		
	});
	
	//弱密码弹出提示框
	<?php echo ($tishi); ?>
	
	//对管理公告标题进行变色操作
	/*<?php echo ($spectrum); ?>*/
	setInterval(spectrum, 800);
	
	
});

function retry(){
	//同时会触发更新在线时间和ip地址
	$.post("__URL__/ajax", {'gonggao_show':'gonggao_show'},
		function(data){
			$("#gonggao_show").empty();
			$("#gonggao_show").append(data);
	});
}

function updatetimeip(){
	//同时会触发更新指令长号码等缓存文件
	$.post("__URL__/ajax", {'updatetimeip':'updatetimeip'},function(data){
		if(data!=''){
			if(confirm(data)){
				parent.main_x.location.href='/admin.php/Passwdrewrite';
			}else {
				return false;
			}
			//alert(data);
		}
	});
}

function spectrum(){
	var xx = $("#welcome").attr('yuflag');
	if (xx == 'no') {
		var hue = 'rgb(' + (Math.floor(Math.random() * 256)) + ',' 
						 + (Math.floor(Math.random() * 256)) + ',' 
						 + (Math.floor(Math.random() * 256)) + ')';
		$('#welcome').css('color',hue);
	}
	
}

//增加进度条长度
function addtiao(i) {
	if(i<0) {
		i = 0;
	}
	if(i>100) {
		i=100;
	}
	var wid = i *6;
	$("#hh_tiao").show();
	$("#hh_ntiao").width(wid);
	$("#hh_per").empty();
	$("#hh_per").append(i+'%');
}


function hidetiao() {
	var per = $("#hh_per").text();
	if(per=='100%') {
		$("#hh_per").append(' ');
		$("#hh_tiao").show(1).delay(3000).hide(1);
	}
	
}


</script>

</head>

<body>
	
<div id="hh_tiao" style="display:none;width:660px;height:16px;float:left;margin:0px;padding:0px;position:absolute;">
	<div id="hh_wtiao" style="width:600px;height:16px;background-color:black;margin:0px;padding:0px;float:left;">
		<div id="hh_ntiao" style="width:0px;height:16px;background-color:red;margin:0px;padding:0px;float:left;"></div>
	</div>
	<div id="hh_per" style="float:left;width:40px;height:16px;padding-left:3px;padding-top:0px;background-color:#E8F2FE;">0%</div>
</div>

	<table cellspacing="0" cellpadding="0" width="100%" background="__PUBLIC__/Images/header_bg.jpg" border="0">
		<tr height="56">
			<td width="260"><font color="#ffffff" size="+2"><b>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo HY_SYSTEM_NAME; ?></b></font><!-- <img height="56" src="__PUBLIC__/Images/header_left.jpg" width="260"> --></td>
			<td style="FONT-WEIGHT: bold; COLOR: #fff; PaDDING-TOP: 20px" align="middle">
				姓名：<?php echo ($xingming); ?>
				&nbsp;&nbsp;&nbsp;
				用户名：
				<a style="COLOR: #fff" href="__APP__/Main/index?<?php echo ($ruser); ?>" target="main_x"><?php echo ($username); ?></a>
				&nbsp;&nbsp;&nbsp;
				<a style="COLOR: #fff" href="__APP__/Passwdrewrite/index?<?php echo ($ruser); ?>" target="main_x">修改密码</a>
				&nbsp;&nbsp; 
				<a style="COLOR: #fff" onclick="if (confirm('确定要退出吗？')) return true; else return false;" href="__APP__/Login/logout?<?php echo ($ruser); ?>" target="_top">退出系统</a>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<span id="gonggao_show"></span>
			</td>
			<td align="right" width="268"><img height="56" src="__PUBLIC__/Images/header_right.jpg" width="268"></td>
		</tr>
	</table>
	<table cellspacing="0" cellpadding="0" width="100%" border="0">
		<tr bgcolor="#1c5db6" height="1"><td></td></tr>
	</table>
	
</body>
</html>