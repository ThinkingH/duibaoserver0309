<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" type="text/css" href="__PUBLIC__/Css/admin.css" />

<script type="text/javascript" src="__PUBLIC__/Js/jquery.min.js"></script>
<script type="text/javascript" src="__PUBLIC__/Js/themain.js"></script>
<script type="text/javascript">
	
	
</script>

</head>

<table cellspacing=0 cellpadding=0 width="100%" align=center border=0 style="margin-left:0px;margin-top:0px;">
	<tr height="28"><td>
		<font size="-1" ><b>当前位置&nbsp;#&nbsp;系统管理&nbsp;#&nbsp;管理员操作文档</b></font></td></tr>
	<tr><td bgcolor="#b1ceef" height="1"></td></tr>
	<tr height="20"><td></td></tr>
</table>

<body>
	

<h1>&nbsp;操作文档读取写入页面</h1>

<form action="__APP__/Root/roottext" method="post" >
<textarea id="textaa" name="textaa" cols="160" rows="24" style="margin-left:6px;"><?php echo ($rootdocument); ?></textarea>
<br/>
<input type="hidden" name="flag_t" value="flag_t" />
<input type="submit" id="submit_t" name="submit_t" class="yubutton yuwhite" style="margin-left:10px;margin-top:10px;" value="修改文档记录信息" />

</form>



<br/><br/><br/><br/><br/><br/>
End!

</body>
</html>