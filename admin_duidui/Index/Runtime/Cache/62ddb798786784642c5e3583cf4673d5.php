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
	$("#uupdate_submit").click(function() {
		
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
<table cellspacing="0" cellpadding="0" width="100%" align="center" border="0" >
	<tr height="28"><td background="__PUBLIC__/Images/title_bg1.jpg">
		<font size="-1" ><b>当前位置&nbsp;#&nbsp;配置信息&nbsp;#&nbsp;&nbsp;#&nbsp;商户开户管理修改</b></font></td></tr>
	<tr><td bgcolor="#b1ceef" height="1"></td></tr>
	<tr height="20"><td background="__PUBLIC__/Images/shadow_bg.jpg"></td></tr>
</table>

<br/>

<a href="__APP__/Shanghu/index<?php echo ($yuurl); ?>" class="yubutton yuwhite">返回商户开户管理查询页面</a>
<br/><br/><br/>


<form action="__APP__/Shanghu/updatedata<?php echo ($yuurl); ?>" method="post" enctype="multipart/form-data">
	
<table class="mainTabled">
<input type="hidden" name="id" value="<?php echo ($list['id']); ?>" />

	<tr>
		<td width="200" align="right">是否启用：</td>
		<td width="800">
			<select name="flag">
				<?php echo ($optionflag); ?>
			</select>
		</td>
	</tr>
	
	<tr>
		<td width="200" align="right">审核状态：</td>
		<td>
			<!-- <select name="shstatus"> -->
		<!-- 	<?php echo ($optioncheck); ?> -->
		<?php echo ($list['checkstatus']); ?>
			<!-- </select> -->
		</td>
	</tr>
	
	
	<tr>
		<td width="200" align="right">联系人：</td>
		<td>
			<input type="text" name="lianxiren" id="lianxiren" size="20" maxlength="30" value="<?php echo ($list['lianxiren']); ?>">
		</td>
	</tr>
	
	<tr>
		<td width="200" align="right">联系方式:</td>
		<td>
			<input type="text" name="phone" id="phone" size="20" maxlength="20" value="<?php echo ($list['phone']); ?>">
		</td>
	</tr>
	
	<tr>
		<td width="200" align="right">邮箱:</td>
		<td>
			<input type="text" name="email" id="email" size="20" maxlength="20" value="<?php echo ($list['email']); ?>">
		</td>
	</tr>
	
	<tr>
		<td width="200" align="right">公司名称:</td>
		<td>
		<input type="text" name="company" id="company" size="50" maxlength="60" value="<?php echo ($list['company']); ?>">
		</td>
	</tr>
	
	<tr>
		<td align="right">公司地址:</td>
		<td>
			<input type="text" name="address" id="address" size="100" maxlength="100" value="<?php echo ($list['address']); ?>">
		</td>
	</tr>
	
	<tr>
		<td align="right"></td>
		<td>
		<font color="red">商户审核通过后完善的店铺信息</font>
		</td>
	</tr>
	
	<tr>
		<td align="right">店铺名称:</td>
		<td>
			<input type="text" name="storename" id="storename" size="50" maxlength="50" value="<?php echo ($list['storename']); ?>">
		</td>
	</tr>
	
	<tr>
		<td align="right">上家类型:</td>
		<td>
			<input type="text" name="shangjiatype" id="shangjiatype" size="30" maxlength="30" value="<?php echo ($list['shangjiatype']); ?>">
		</td>
	</tr>
	
	<tr>
		<td align="right">qq:</td>
		<td>
			<input type="text" name="qq" id="qq" size="30" maxlength="30" value="<?php echo ($list['qq']); ?>">
		</td>
	</tr>
	
	
	<tr>
		<td width="200" align="right">店铺logo:</td>
		<td name="touxiang">
			<img id="touxiang" alt="" src="<?php echo ($list['touxiang']); ?>" width="60">
		</td>
	</tr>
	
	<tr>
		<td width="200" align="right">营业执照副本1:</td>
		<td>
			<img id="bussinelicence1" alt="" src="<?php echo ($list['bussinelicence1']); ?>" width="200">
		</td>
	</tr>
	
	<tr>
		<td width="200" align="right">营业执照副本2:</td>
		<td>
			<img id="bussinelicence2" alt="" src="<?php echo ($list['bussinelicence2']); ?>" width="200">
		</td>
	</tr>
	<tr>
		<td width="200" align="right">备注:</td>
		<td>
			<input type="text" name="remark" id="remark" size="30" maxlength="30" value="<?php echo ($list['remark']); ?>">
		</td>
	</tr>
	
	
</table>
<br/><br/>

<input type="submit" id="uupdate_submit" class="yubutton yuwhite" name="uupdate_submit" style="margin:15px 0px 0px 10px;" value="确认修改" />
<br/><br/>

</form>

<br/><br/><br/><br/><br/><br/>
End!

</body>

</html>