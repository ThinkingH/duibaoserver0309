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
	$("#tongguo_submit").click(function() {
		
		if(confirm("您确认要审核通过该数据吗？")) {
			//alert('ok');
		}else {
			return false;
		}
		
	});
	
	//数据添加确认
	$("#bohui_submit").click(function() {
		
		if(confirm("您确认要驳回该数据吗？")) {
			//alert('ok');
		}else {
			return false;
		}
		
	});
	
});


</script>
</head>

<body>
<table cellspacing="0" cellpadding="0" width="100%" align="center" border="0" >
	<tr height="28"><td background="__PUBLIC__/Images/title_bg1.jpg">
		<font size="-1" ><b>当前位置&nbsp;#&nbsp;商城配置信息&nbsp;#&nbsp;&nbsp;#&nbsp;商城店铺管理查看</b></font></td></tr>
	<tr><td bgcolor="#b1ceef" height="1"></td></tr>
	<tr height="20"><td background="__PUBLIC__/Images/shadow_bg.jpg"></td></tr>
</table>

<br/>

<a href="__APP__/Shanghu/store<?php echo ($yuurl); ?>" class="yubutton yuwhite">返回商户店铺管理查询页面</a>
<br/><br/><br/>

<form action="__APP__/Shanghu/storeupdatedata<?php echo ($yuurl); ?>" method="post" enctype="multipart/form-data">	
<table class="mainTabled">
<input type="hidden" name="id" value="<?php echo ($list['id']); ?>" />

	<tr>
		<td width="200" align="right">是否注销：</td>
		<td width="800">
			
<?php echo ($list['flag']); ?>
		</td>
	</tr>
	
	<tr>
		<td width="200" align="right">审核状态：</td>
		<td>
		<select name="storestatus">
				<?php echo ($optioncheck); ?>
			</select>
		</td>
	</tr>
	
	
	<tr>
		<td width="200" align="right">联系人：</td>
		<td>
			<?php echo ($list['lianxiren']); ?>
		</td>
	</tr>
	
	<tr>
		<td width="200" align="right">联系方式:</td>
		<td>
			<?php echo ($list['phone']); ?>
		</td>
	</tr>
	
	<tr>
		<td width="200" align="right">店铺名:</td>
		<td>
			<?php echo ($list['storename']); ?>
		</td>
	</tr>
	
	<tr>
		<td width="200" align="right">经营类型:</td>
		<td>
		<?php echo ($list['shangjiatype']); ?>
		</td>
	</tr>
	
	<tr>
		<td align="right">QQ:</td>
		<td>
			<?php echo ($list['qq']); ?>
		</td>
	</tr>
	
	
	<tr>
		<td width="200" align="right">评论:</td>
		<td>
			<?php echo ($list['comment']); ?>
		</td>
	</tr>
	
	<tr>
		<td width="200" align="right">人气:</td>
		<td>
			<?php echo ($list['renqi']); ?>
		</td>
	</tr>
	
	 <tr>
		<td width="200" align="right">店铺logo:</td>
		<td>
			<img id="touxiang" alt="" src="<?php echo ($list['touxiang']); ?>" width="100">
		</td>
	</tr> 
	<tr>
		<td width="200" align="right">备注:</td>
		<td>
			<textarea  name="storemark" id="storemark" rows="5" cols="50"><?php echo ($list['storemark']); ?></textarea>
		</td>
	</tr>
	
</table>

<br/><br/>

<input type="submit" class="yubutton yuwhite" id="tongguo_submit" name="tijiao_submit" style="margin:15px 0px 0px 10px;" value="确认审核" />

</form>
<br/><br/>







<br/><br/><br/><br/><br/><br/>
End!

</body>

</html>