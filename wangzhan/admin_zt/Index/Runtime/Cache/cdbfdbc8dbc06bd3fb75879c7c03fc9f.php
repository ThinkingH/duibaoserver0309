<?php if (!defined('THINK_PATH')) exit();?>﻿<!-- <!DOCTYPE html PUBLIC "-//W3C//Dtd html 4.0 Transitional//EN">
<html>
<head>
<meta http-equiv=Content-Type content="text/html; charset=utf-8">

<link rel="stylesheet" type="text/css" href="__PUBLIC__/Css/admin.css" />

<script type="text/javascript" src="__PUBLIC__/Js/jquery.min.js"></script>
<script type="text/javascript" src="__PUBLIC__/Js/themain.js"></script>
<script type="text/javascript">
$("document").ready(function(){
	
	
});

</script>

</head>
<body>

	<table cellspacing="0" cellpadding="0" width="100%" align="center" border="0" >
		<tr height="28">
			<td background="__PUBLIC__/Images/title_bg1.jpg" width="1292px;">
				<b>当前位置&nbsp;#&nbsp;用户管理&nbsp;#&nbsp;当前用户信息</b>
			</td>
		</tr>
		<tr><td bgColor="#b1ceef" height="1"></td></tr>
		<tr height="20"></td></tr>
	</table>
	
	
	
	<br/>
	<br/>
	
	<h2>&nbsp;&nbsp;&nbsp;&nbsp;你好：<?php echo ($username); ?>，欢迎来到中铁国恒网站后台数据查询系统</h2>
	
</body>
</html> -->

<!DOCTYPE html PUBLIC "-//W3C//Dtd html 4.0 Transitional//EN">
<html>
<head>
<meta http-equiv=Content-Type content="text/html; charset=utf-8">

<link rel="stylesheet" type="text/css" href="__PUBLIC__/Css/admin.css" />

<script type="text/javascript" src="__PUBLIC__/Js/jquery.min.js"></script>
<script type="text/javascript" src="__PUBLIC__/Js/themain.js"></script>
<script type="text/javascript">
$("document").ready(function(){
	
	$("#submit_qq").click(function() {
		var qq = $("#qq").val();
		
		$.post("__APP__/Main/qqchange", {'qq':qq},
			function(data){
				alert(data);
			});
		
	});
	
});

</script>

</head>
<body>

	<table cellspacing="0" cellpadding="0" width="100%" align="center" border="0" >
		<tr height="28">
			<td>
				<b>当前位置：用户管理&nbsp;#&nbsp;当前用户信息</b>
			</td>
		</tr>
		<tr><td bgColor="#b1ceef" height="1"></td></tr>
		<tr height="20"><td></td></tr>
	</table>
	
	
	<table cellspacing="0" cellpadding="0" width="90%" align="center" border="0">
		<tr height="100">
			<td align="middle" width="100">
				<img height="100" src="__PUBLIC__/Images/admin_p.gif" width="90">
			</td>
    		<td width="60">&nbsp;</td>
    		<td>
				<table height="100" cellspacing="0" cellpadding="0" width="100%" border="0">
					<tr>
						<td style="font-weight: bold; font-size: 16px">
							<?php echo ($xingming); ?>
						</td>
					</tr>
					<tr><td>欢迎进入中铁国恒平台</td></tr>
				</table>
			</td>
		</tr>
		<tr><td colSpan="3" height="10"></td></tr>
	</table>
	
	
	<table cellspacing="0" cellpadding="0" width="95%" border="0">
		<tr height="20"><td></td></tr>
		<tr height="22">
			<td style="padding-left: 20px; font-weight: bold; color: #ffffff" align="middle" background="__PUBLIC__/Images/he.gif" height="22">
				您的相关信息
			</td>
		</tr>
		<tr bgColor="" height="12"><td></td></tr>
		<tr height="20"><td></td></tr>
	</table>
	
	<br/>
	<table cellspacing="0" cellpadding="2" width="1100" align="center" border="0" >
		<tr >
			<td align="right" width="150">姓名：</td>
			<td style="color: #880000" width="950"><?php echo ($xingming); ?></td>
		</tr>
		<tr>
			<td align="right">登陆帐号：</td>
			<td style="color: #880000"><?php echo ($username); ?></td>
		</tr>
		<tr>
			<td align="right">最近在线时间：</td>
			<td style="color: #880000"><?php echo ($lastLoginTime); ?></td>
		</tr>
		<tr>
			<td align="right">最近在线ip地址：</td>
			<td style="color: #880000">
				<?php echo ($lastLoginIp); ?>
			</td>
		</tr>
		<tr>
			<td align="right">&nbsp;</td>
			<td style="color: #880000">
				&nbsp;
			</td>
		</tr>
	</table>
	
	<!-- <table align="right">
		<tr>
			<td width="140" ></td>
			<td>
				qq信息：
				<input type="text" value="<?php echo ($qq); ?>" id="qq" name="qq" size="20" maxlength="13"  onkeydown="if(event.keyCode=='32'){return false;}" />
				<input type="button" id="submit_qq" value="修改qq信息" />
			</td>
		</tr>
	</table> -->
	
</body>
</html>