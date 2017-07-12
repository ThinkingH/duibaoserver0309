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
	//数据添加确认
	$("#update_submit").click(function() {
		
		
		
		if(confirm("您确认要修改数据吗？")) {
			//alert('ok');
		}else {
			return false;
		}
		
	});
	
});


</script>
</head>

<body>
<table cellspacing="0" cellpadding="0" width="100%" align="center" border="0">
	<tr height="28"><td background="__PUBLIC__/Images/title_bg1.jpg">
		<font size="-1" ><b>当前位置&nbsp;#&nbsp;配置信息&nbsp;#&nbsp;订单兑换</b></font></td></tr>
  	<tr><td bgcolor="#b1ceef" height="1"></td></tr>
	<tr height="20"><td background="__PUBLIC__/Images/shadow_bg.jpg"></td></tr>
</table>

<br/>

<a href="__APP__/Orderlist/dealorder<?php echo ($yuurl); ?>" class="yubutton yuwhite">返回订单信息查询页面</a>
<br/><br/><br/>


<form action="__APP__/Orderlist/updatedata<?php echo ($yuurl); ?>" method="post" enctype="multipart/form-data">
	
<table class="mainTabled">
<input type="hidden" name="id" value="<?php echo ($list['id']); ?>" />

	<tr>
		<td width="200" align="right">自增编号：</td>
		<td width="800">
			<?php echo ($list['id']); ?>
		</td>
	</tr>
	
	<tr>
		<td width="200" align="right">商品名称：</td>
		<td>
			<?php echo ($list['name']); ?>
		</td>
	</tr>
	
	<tr>
		<td width="200" align="right">商品数量：</td>
		<td>
			<?php echo ($list['productnum']); ?>
		</td>
	</tr>
	
	<tr>
		<td width="200" align="right">订单号:</td>
		<td>
			<?php echo ($list['orderno']); ?>
		</td>
	</tr>
	
	<tr>
		<td width="200" align="right">兑换码/账号:</td>
		<td>
			<input type="text" name="keystr" id="keystr" size="50" maxlength="60" value="<?php echo ($list['keystr']); ?>">
		</td>
	</tr>
	
	<tr>
		<td width="200" align="right">密码:</td>
		<td>
			<input type="text" name="passwd" id="passwd" size="50" maxlength="60" value="<?php echo ($list['passwd']); ?>">
		</td>
	</tr>
	
	
</table>
<br/><br/>

<input type="submit" id="update_submit" class="yubutton yuwhite" name="update_submit" style="margin:15px 0px 0px 10px;" value="确认兑换" />
<br/><br/>

</form>

<br/><br/><br/><br/><br/><br/>
End!

</body>

</html>