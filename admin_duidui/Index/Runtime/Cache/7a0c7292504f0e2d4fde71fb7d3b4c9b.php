<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//Dtd html 4.0 Transitional//EN">
<html>
<head>
<meta http-equiv=Content-Type content="text/html; charset=utf-8">
<link rel="stylesheet" type="text/css" href="__PUBLIC__/Css/admin.css" />

<script type="text/javascript" src="__PUBLIC__/Js/jquery.min.js"></script>
<script language="javascript" type="text/javascript" src="__PUBLIC__/Js/datexxx/WdatePicker.js"></script>
<script type="text/javascript" src="__PUBLIC__/Js/themain.js"></script>
<!-- <script type="text/javascript" src="../../Xheditor/xheditor-1.1.14-zh-cn.min.js"></script> -->

<!-- <script type="text/javascript">
$(pageInit);
function pageInit()
{
	$('#changecontent').xheditor({tools:'Source,|,Cut,Copy,Paste,Pastetext,|,Align,List,Outdent,Indent,|,Blocktag,Fontface,FontSize,|,Bold,Italic,Underline,Strikethrough,|,FontColor,BackColor,|,Emot,Table,Removeformat,Link,Unlink,|,Source,Preview,SelectAll,Hr,|,|,Img,|,|',upImgUrl:"../../Xheditor/demos/upload.php",upImgExt:"jpg,jpeg,gif,png"});
}
function submitForm(){$('#frmDemo').submit();}
</script> -->

<script type="text/javascript">
$("document").ready(function(){
	//数据添加确认
	$("#add_submit").click(function() {
		
		if(confirm("您确认要添加数据吗？")) {
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
		<font size="-1" ><b>当前位置&nbsp;#&nbsp;配置信息&nbsp;#&nbsp;&nbsp;#&nbsp;广告的上传</b></font></td></tr>
	<tr><td bgcolor="#b1ceef" height="1"></td></tr>
	<tr height="20"><td background="__PUBLIC__/Images/shadow_bg.jpg"></td></tr>
</table>

<br/>

<a href="__APP__/Adverlist/index<?php echo ($yuurl); ?>" class="yubutton yuwhite">返回广告信息查询页面</a>
<br/><br/><br/>


<form action="__APP__/Adverlist/adddata<?php echo ($yuurl); ?>" method="post" enctype="multipart/form-data">
	
<table class="mainTabled">


	<tr>
		<td width="200" align="right">是否启用：</td>
		<td width="800">
			<select name="flag">
				<?php echo ($optionflag); ?>
			</select>
		</td>
	</tr>
	
	<tr>
		<td width="200" align="right">广告跳转类型：</td>
		<td>
			<select name="gflag">
			<?php echo ($optiontiaozhuan); ?>
			</select>
		</td>
	</tr>
	
	<!-- <tr>
		<td width="200" align="right">广告主类型:</td>
		<td width="800">
			<textarea id="changecontent" name="content" rows="32" cols="120"></textarea>
		</td>
	</tr> -->
	
	<tr>
		<td width="200" align="right">广告展示类型：</td>
		<td>
			<select name="type">
			<?php echo ($optionshowtype); ?>
			</select>
		</td>
	</tr>
	
	<tr>
		<td width="200" align="right">广告标题:</td>
		<td>
			<input type="text" name="adtitle" id="adtitle" size="50" maxlength="60">
		</td>
	</tr>
	
	<tr>
		<td width="200" align="right">广告描述:</td>
		<td>
			<textarea  name="adcontent" rows="5" cols="60"></textarea>
		</td>
	</tr>
	
	<tr>
		<td width="200" align="right">广告展示图片:</td>
		<td>
			<input type='file' name='picurl' id='picurl'>
		</td>
	</tr>
	
	<tr>
		<td width="200" align="right">广告跳转链接:</td>
		<td>
		<textarea rows="5" cols="60" name="tzurl" id="tzurl"></textarea>
		</td>
	</tr>
	
	
	<tr>
		<td width="200" align="right">任务编号:</td>
		<td>
			<input type="type" name="taskid" id="taskid" size="30" maxlength="30">
		</td>
	</tr>
	
	
	<!-- <tr>
		<td width="200" align="right">广告跳转链接:</td>
		<td>
			<textarea id="changecontent" name="adurl" rows="32" cols="120"></textarea>
		</td>
	</tr> -->
	
	
	
</table>
<br/><br/>

<input type="submit" id="add_submit" class="yubutton yuwhite" name="add_submit" style="margin:15px 0px 0px 10px;" value="确认添加" />
<br/><br/>

</form>

<br/><br/><br/><br/><br/><br/>
End!

</body>

</html>