<?php if (!defined('THINK_PATH')) exit();?>﻿ <!DOCTYPE html PUBLIC "-//W3C//Dtd html 4.0 Transitional//EN">
<html>
<head>
<title>中铁国恒的后台查询系统<?php echo '_'.$_SERVER['SERVER_ADDR']; ?></title>
<meta http-equiv=Content-Type content="text/html; charset=utf-8">

<link rel="stylesheet" type="text/css" href="__PUBLIC__/Css/admin.css" />

<script type="text/javascript" src="__PUBLIC__/Js/jquery.min.js"></script>
<script type="text/javascript" src="__PUBLIC__/Js/themain.js"></script>
<script type="text/javascript">


</script>

</head><!--  bgcolor="#DAF1F4" -->
<body>
<table height="100%" cellspacing="0" cellpadding="0" width="100%" background="__PUBLIC__/Images/bgtu.jpg" border="0">
	<tr>
		<td align="middle">
			<table cellspacing="20" cellpadding="0" width="360" border="0" bgcolor="#D8EFA3" style="border-radius:15px;
			-moz-border-radius: 15px; -webkit-border-radius: 15px;">
			

				<form name="form1" action="__APP__/Login/login_x" method="post">
					<tr height="5">
						<td width="100"></td><td width="260"></td>
					</tr>
						<tr height="36">
						<td align="center" colspan="2"><font size="+2"><b>中铁国恒的后台查询系统</b></font></td>
					</tr>
						<tr height="30">
						<td align="right">用户名:</td>
						<td><input style="BORDER-RIGHT: #000000 1px solid; BORDER-TOP: #000000 1px solid; BORDER-LEFT: #000000 1px solid; BORDER-BOTTOM: #000000 1px solid" type="text" maxlength="30" size="24" name="username" onkeydown="if(event.keyCode=='32'){return false;}" autocomplete="off" ></td>
					</tr>
					<tr height="30">
						<td align="right">密  码:</td>
						<td><input style="BORDER-RIGHT: #000000 1px solid; BORDER-TOP: #000000 1px solid; BORDER-LEFT: #000000 1px solid; BORDER-BOTTOM: #000000 1px solid" type="password" maxlength="30" size="24" name="passwd" onkeydown="if(event.keyCode=='32'){return false;}" ></td>
					</tr>
					<tr>
						<td>&nbsp;</td>
						<td><input type="submit" name="submit" value="  登  陆  " style="width:120px;height:36px;" /></td>
					</tr>
				</form>
			</table>
		
		</td>
	</tr>
</table>
</body>
</html>