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
	$(".add_submit").click(function() {
		
		var versioncode= $("#versioncode").val();
		
		
		if(versioncode==''){
			alert('版本号不能为空！');
		}
		
		if(!!isNaN(versioncode)){
			alert('版本号必须为数字！');
		}
		
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
		<font size="-1" ><b>当前位置&nbsp;#&nbsp;配置信息&nbsp;#&nbsp;&nbsp;#&nbsp;版本信息的修改</b></font></td></tr>
	<tr><td bgcolor="#b1ceef" height="1"></td></tr>
	<tr height="20"><td background="__PUBLIC__/Images/shadow_bg.jpg"></td></tr>
</table>

<br/>
<a href="__APP__/Versionlist/index<?php echo ($yuurl); ?>" class="yubutton yuwhite">返回版本信息管理页面</a>
<br/><br/><br/>

<form action="__APP__/Versionlist/updateverdata<?php echo ($yuurl); ?>" method="post" enctype="multipart/form-data">

<table class="mainTabled">
<input type="hidden" name="id" value="<?php echo ($list['id']); ?>" />

	<tr>
		<td width="200" align="right">自增编号：</td>
		<td width="800" name="flag">
			<?php echo ($list['id']); ?>
		</td>
	</tr>
	
	<tr>
		<td width="200" align="right">是否启用:</td>
		<td width="800">
		<select name="flag" id="flag" >
			<?php echo ($optionflag); ?>
		</select>
		&nbsp;&nbsp;<font color="red" size="-1"></font>
		</td>
	</tr>
	
	<tr>
		<td width="200" align="right">系统:</td>
		<td width="800">
		<select name="systemtype" id="systemtype" >
			<?php echo ($optionsystemtype); ?>
		</select>
		&nbsp;&nbsp;<font color="red" size="-1"></font>
		</td>
	</tr>
	
	<tr>
		<td width="200" align="right">版本号:</td>
		<td width="800">
			<input type="text" name="versioncode" id="versioncode" size="30" maxlength="100" value="<?php echo ($list['versioncode']); ?>"/>
			&nbsp;&nbsp;<font color="red" size="-1">*必须为数字</font>
		</td>
	</tr>
	
	
	
	<tr>
		<td width="200" align="right">app安装包:</td>
		<td width="800">
		<input type='file' name='apkurl' id='apkurl'>
		</td>
	</tr>
	
	<tr>
		<td width="200" align="right">下载链接:</td>
		<td width="800">
			<textarea rows="5" cols="60" name="apk_url"><?php echo ($list['apk_url']); ?></textarea>
		</td>
	</tr>
	
	<tr>
		<td width="200" align="right">版本描述:</td>
		<td width="800">
		<textarea rows="5" cols="60" name="updescription"><?php echo ($list['updescription']); ?></textarea>
		</td>
		
	</tr>
	
	
	<tr>
		<td width="200" align="right">类型:</td>
		<td width="800">
		<select name="uptype" id="uptype" >
			<?php echo ($optionuptype); ?>
		</select>
		&nbsp;&nbsp;<font color="red" size="-1"></font>
		</td>
	</tr>
	
	
</table>
<br/><br/>

<input type="submit" id="add_submit" class="yubutton yuwhite add_submit " name="add_submit" style="margin:15px 0px 0px 10px;" value="确认修改" />
<br/><br/>

</form>

<br/><br/><br/><br/><br/><br/>
End!

</body>

</html>