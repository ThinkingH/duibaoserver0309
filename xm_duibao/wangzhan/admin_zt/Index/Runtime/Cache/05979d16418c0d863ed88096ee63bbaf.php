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
		<font size="-1" ><b>当前位置&nbsp;#&nbsp;系统管理&nbsp;#&nbsp;编辑操作用户&nbsp;#&nbsp;用户信息浏览页面</b></font></td></tr>
	<tr><td bgcolor="#b1ceef" height="1"></td></tr>
	<tr height="20"><td></td></tr>
</table>

<body>
	

<br/>&nbsp;
<a href="__APP__/Root/adduser" class="yubutton yuwhite">添加操作用户</a>
<br/><br/>


<table class="mainTables" >
<thead>
<tr>
	<td width="120"><b>用户名</b></td>
	<td width="100"><b>姓名</b></td>
	<td width="160"><b>最近一次检测---在线时间</b></td>
	<td width="160"><b>最近一次检测---ip地址</b></td>
	<td width="120"><b>用户权限</b></td>
	<td width="100"><b>是否被禁用</b></td>
	<td width="100"><b>在线状态</b></td>
	<td width="100"></td>
</tr>
</thead>

<tbody>
<?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "没有对应数据" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
		<td><?php echo ($vo["username"]); ?></td>
		<td><?php echo ($vo["xingming"]); ?></td>
		<td><?php echo ($vo["lastLoginTime"]); ?></td>
		<td><?php echo ($vo["lastLoginIp"]); ?></td>
		<td><?php echo ($vo["rootflag"]); ?></td>
		<td><?php echo ($vo["lockflag"]); ?></td>
		<td><?php echo ($vo["state"]); ?></td>
		<td>
			<form action="__APP__/Root/editoruser_x" method="post" style="margin:0px" >
				<input type="hidden" name="edit_username_val" value="<?php echo ($vo["username"]); ?>" />
				<input type="submit" class="yubuttons yuwhite" name="edituserbutton" value="编辑" />
			</form>
		</td>
	</tr><?php endforeach; endif; else: echo "没有对应数据" ;endif; ?>
</tbody>

</table>

<br/><br/><br/><br/><br/><br/>
End!

</body>
</html>