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
<table cellspacing="0" cellpadding="0" width="100%" align="center" border="0" >
	<tr height="28"><td background="__PUBLIC__/Images/title_bg1.jpg">
		<font size="-1" ><b>当前位置&nbsp;#&nbsp;配置信息&nbsp;#&nbsp;&nbsp;#&nbsp;参数的配置</b></font></td></tr>
	<tr><td bgcolor="#b1ceef" height="1"></td></tr>
	<tr height="20"><td background="__PUBLIC__/Images/shadow_bg.jpg"></td></tr>
</table>

<br/>

<a href="__APP__/Versionlist/index<?php echo ($yuurl); ?>" class="yubutton yuwhite">返回参数信息管理页面</a>
<br/><br/><br/>


<form action="__APP__/Versionlist/updateconfigshowdata<?php echo ($yuurl); ?>" method="post" enctype="multipart/form-data">
	
<table class="mainTabled">
<input type="hidden" name="id" value="<?php echo ($list['id']); ?>" />

	<tr>
		<td width="200" align="right">编号：</td>
		<td width="800" name="id">
			<?php echo ($list['id']); ?>
		</td>
	</tr>
	
	
	<tr>
		<td width="200" align="right">qq：</td>
		<td width="800" name="qq">
			<input id="qq" name="qq" size="15" maxlength="15" value="<?php echo ($list['qq']); ?>">
		</td>
	</tr>
	
	
	<tr>
		<td width="200" align="right">版本号:</td>
		<td width="800">
			<input id="version" name="version" size="10" maxlength="10" value="<?php echo ($list['version']); ?>">
		</td>
	</tr>
	
	<tr>
		<td width="200" align="right">公司信息：</td>
		<td width="800">
			<input id="companyinfo" name="companyinfo" size="50" maxlength="50" value="<?php echo ($list['content']); ?>">
		</td>
	</tr>
	
	<tr>
		<td width="200" align="right">正常用户最大下载次数:</td>
		<td width="800">
			<input id="normalusernum" name="normalusernum"  size="10" maxlength="10" value="<?php echo ($list['normalusernum']); ?>">
			
		</td>
	</tr>
	
	<tr>
		<td width="200" align="right">正常用户获取最大积分:</td>
		<td width="800">
			<input id="normaluserscore" name="normaluserscore"  size="10" maxlength="10" value="<?php echo ($list['normaluserscore']); ?>">
		</td>
		
	</tr>
	
	
	<tr>
		<td width="200" align="right">临时用户最大下载次数：</td>
		<td width="800">
			<input id="unnormalusernum" name="unnormalusernum"  size="10" maxlength="10" value="<?php echo ($list['unnormalusernum']); ?>">
		</td>
	</tr>
	
	<tr>
		<td width="200" align="right">临时用户获取最大积分：</td>
		<td width="800">
			<input id="unnormaluserscore" name="unnormaluserscore"  size="10" maxlength="10" value="<?php echo ($list['unnormaluserscore']); ?>">
		</td>
	</tr>
	
	
</table>
<br/><br/>

<input type="submit" id="update_submit" class="yubutton yuwhite" name="update_submit" style="margin:15px 0px 0px 10px;" value="确认修改" />
<br/><br/>

</form>

<br/><br/><br/><br/><br/><br/>
End!

</body>

</html>