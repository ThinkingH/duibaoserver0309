<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//Dtd html 4.0 Transitional//EN">
<html>
<head>
<meta http-equiv=Content-Type content="text/html; charset=utf-8">
<link rel="stylesheet" type="text/css" href="__PUBLIC__/Css/admin.css" />

<script type="text/javascript" src="__PUBLIC__/Js/jquery.min.js"></script>
<script language="javascript" type="text/javascript" src="__PUBLIC__/Js/datexxx/WdatePicker.js"></script>
<script type="text/javascript" src="__PUBLIC__/Js/themain.js"></script>
<script type="text/javascript">
$("document").ready(function(){
		
	$(".mainTables>tbody>tr>td").hover(function(){
		$(this).parent().children().addClass('yu_mourse_stop_change');
	},function(){
		$(this).parent().children().removeClass('yu_mourse_stop_change');
	});
	
	
	
});


</script>
</head>

<body>
<table cellspacing="0" cellpadding="0" width="100%" align="center" border="0">
	<tr height="28"><td background="__PUBLIC__/Images/title_bg1.jpg">
		<font size="-1" ><b>当前位置&nbsp;#&nbsp;配置信息&nbsp;#&nbsp;后台限制信息</b></font></td></tr>
  	<tr><td bgcolor="#b1ceef" height="1"></td></tr>
	<tr height="20"><td background="__PUBLIC__/Images/shadow_bg.jpg"></td></tr>
</table>

<!-- <a href="__APP__/Lunbotu/addlunbotushow<?php echo ($yuurl); ?>" class="yubutton yuwhite">添加轮播图</a> -->
<br/><br/>

<table class="mainTables" width="1500">
<thead>
	<tr>
		<td width="20">&nbsp;</td>
		<td width="20"><b>编号<br/></b></td>
		<td width="40"><b>qq<br/></b></td>
		<td width="40"><b>版本号</b></td>
		<td width="90"><b>公司信息<br/></b></td>
		<td width="50"><b>正常用户下载次数</b></td>
		<td width="50"><b>正常用户获取最大积分<br/></b></td>
		<td width="50"><b>临时用户最大下载次数<br/></b></td>
		<td width="50"><b>临时用户获取最大积分<br/></b></td>
		<td width="80"><b>更新时间<br/></b></td>
	</tr>
</thead>

<tbody>
	<?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "没有对应数据" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
	
		<td>
			<form action="__APP__/Lunbotu/updateconfigshow<?php echo ($yuurl); ?>" method="post" style="margin:0px">
			<input type="hidden" name="id" value="<?php echo ($vo["id"]); ?>" />
			<input type="submit" class="yubuttonss yuwhite" name="update_submit" value="修改" />
			</form>
		</td>
		
		<td><?php echo ($vo["id"]); ?></td>
		<td><?php echo ($vo["qq"]); ?></td>
		<td><?php echo ($vo["version"]); ?></td>
		<td><?php echo ($vo["content"]); ?></td>
		<td><?php echo ($vo["normalusernum"]); ?></td>
		<td><?php echo ($vo["normaluserscore"]); ?></td>
		<td><?php echo ($vo["unnormalusernum"]); ?></td>
		<td><?php echo ($vo["unnormaluserscore"]); ?></td>
		<td><?php echo ($vo["createtime"]); ?></td>
	</tr><?php endforeach; endif; else: echo "没有对应数据" ;endif; ?>
</tbody>

</table>

<br/><br/>

<center><?php echo ($page); ?></center>

<br/><br/><br/><br/><br/><br/>
End!

</body>

</html>