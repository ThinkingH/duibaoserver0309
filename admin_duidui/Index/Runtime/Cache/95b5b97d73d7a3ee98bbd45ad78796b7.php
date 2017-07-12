<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//Dtd html 4.0 Transitional//EN">
<html>
<head>
<meta http-equiv=Content-Type content="text/html; charset=utf-8">
<link rel="stylesheet" type="text/css" href="__PUBLIC__/Css/admin.css" />

<script type="text/javascript" src="__PUBLIC__/Js/jquery.min.js"></script>
<script language="javascript" type="text/javascript" src="__PUBLIC__/Js/datexxx/WdatePicker.js"></script>
<script type="text/javascript" src="__PUBLIC__/Js/themain.js"></script>
<script type="text/javascript" src="../../Xheditor/xheditor-1.1.14-zh-cn.min.js"></script>

<script type="text/javascript">
$(pageInit);
function pageInit()
{
	$('#changecontent').xheditor({tools:'Source,|,Cut,Copy,Paste,Pastetext,|,Align,List,Outdent,Indent,|,Blocktag,Fontface,FontSize,|,Bold,Italic,Underline,Strikethrough,|,FontColor,BackColor,|,Emot,Table,Removeformat,Link,Unlink,|,Source,Preview,SelectAll,Hr,|,|,Img,|,|',upImgUrl:"../../Xheditor/demos/upload.php",upImgExt:"jpg,jpeg,gif,png"});
}
function submitForm(){$('#frmDemo').submit();}
</script>

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
		<font size="-1" ><b>当前位置&nbsp;#&nbsp;配置信息&nbsp;#&nbsp;&nbsp;#&nbsp;轮播图的修改页面</b></font></td></tr>
	<tr><td bgcolor="#b1ceef" height="1"></td></tr>
	<tr height="20"><td background="__PUBLIC__/Images/shadow_bg.jpg"></td></tr>
</table>

<br/>

<a href="__APP__/Kaipingtu/index<?php echo ($yuurl); ?>" class="yubutton yuwhite">返回轮播图信息查询页面</a>
<br/><br/><br/>


<form action="__APP__/Kaipingtu/updatelunbotudata<?php echo ($yuurl); ?>" method="post" enctype="multipart/form-data">
	
<table class="mainTabled">
<input type="hidden" name="id" value="<?php echo ($list['id']); ?>" />

	<tr>
		<td width="200" align="right">是否启用：</td>
		<td width="800" name="flag">
			<select name="flag" id="flag" >
			<?php echo ($optionflag); ?>
			</select>
		</td>
	</tr>
	
	
	<!-- <tr>
		<td width="200" align="right">名称：</td>
		<td width="800">
			<input type="text" name="shopname" size="30" maxlength="30" value="<?php echo ($list['shopname']); ?>">
		</td>
	</tr> -->
	
	<tr>
		<td width="200" align="right">轮播图上传:</td>
		<td width="800">
			<input type='file' name='picurl' id='picurl'>
		</td>
	</tr>
	
	<tr>
		<td width="200" align="right">跳转类型：</td>
		<td width="800" name="tztype">
			<select name="tztype" id="tztype" >
			<?php echo ($optiontype); ?>
			</select>
		</td>
	</tr>
	
	<tr>
		<td width="200" align="right">跳转链接内容:</td>
		<td width="800">
			<textarea id="changecontent" name="content" rows="32" cols="120"><?php echo ($list['content']); ?></textarea>
			
		</td>
	</tr>
	
	<tr>
		<td width="200" align="right">跳转链接:</td>
		<td width="800">
		<textarea rows="5" cols="60" name="tzurl" id="tzurl"><?php echo ($list['value']); ?></textarea>
		</td>
		
	</tr>
	
	
	<tr>
		<td width="200" align="right">是否可点击：</td>
		<td width="800" name="isused">
			<select name="isused" id="isused" >
			<?php echo ($optionclick); ?>
			</select>
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