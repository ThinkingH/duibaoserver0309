<?php if (!defined('THINK_PATH')) exit();?>﻿<!DOCTYPE html PUBLIC "-//W3C//Dtd html 4.0 Transitional//EN">
<html>
<head>
<meta http-equiv=Content-Type content="text/html; charset=utf-8">

<link rel="stylesheet" type="text/css" href="__PUBLIC__/Css/admin.css" />
<script type="text/javascript" src="__PUBLIC__/Js/jquery.min.js"></script>
<script type="text/javascript" src="__PUBLIC__/Js/themain.js"></script>
<script type="text/javascript">
$("document").ready(function(){
	
	//刷新该页面120秒后执行在线状态检测
	setTimeout(updatetimeip, 12800);
	
	//每11分钟更新一次在线状态
	setInterval(updatetimeip, 660000);
	
	
});


function updatetimeip(){
	//同时会触发更新指令长号码等缓存文件
	$.post("__URL__/ajax", {'updatetimeip':'updatetimeip'},function(data){
		if(data!=''){
			if(confirm(data)){
				parent.main_x.location.href='/admin.php/Passwdrewrite';
			}else {
				return false;
			}
		}
	});
}



</script>
<!-- bgcolor="#A0D3FF" -->
</head>
<body>
	<table cellspacing="0" cellpadding="0" width="100%"  background="__PUBLIC__/Images/bgq.gif" border="0">
		<tr height="56">
			<td width="220"><b style="font-size:16px;">&nbsp;&nbsp;&nbsp;中铁国恒后台数据查询系统</b></td>
			<td style="FONT-WEIGHT: bold; COLOR: #fff; PaDDING-TOP: 20px" align="middle">
				用户名：<?php echo ($user); ?>
				&nbsp;&nbsp;&nbsp;
				
				<a style="COLOR: #fff" href="__APP__/Passwdrewrite/index?<?php echo ($ruser); ?>" target="main_x">修改密码</a>
				&nbsp;&nbsp; 
				<a style="COLOR: #fff" onclick="if (confirm('你确定要退出平台系统吗？')) return true; else return false;" href="__APP__/Login/logout?<?php echo ($ruser); ?>" target="_top">退出系统</a>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<span id="gonggao_show"></span>
			</td>
			<td align="right" width="268" ></td>
		</tr>
	</table><!-- #1c5db6 -->
	<table cellspacing="0" cellpadding="0" width="100%" border="0">
		<tr bgcolor="#57760D" height="4"><td></td></tr>
	</table>
	
</body>
</html>