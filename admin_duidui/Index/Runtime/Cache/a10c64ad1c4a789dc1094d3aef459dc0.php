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
		<font size="-1" ><b>当前位置&nbsp;#&nbsp;配置信息&nbsp;#&nbsp;&nbsp;#&nbsp;发布广告修改审核</b></font></td></tr>
	<tr><td bgcolor="#b1ceef" height="1"></td></tr>
	<tr height="20"><td background="__PUBLIC__/Images/shadow_bg.jpg"></td></tr>
</table>

<br/>

<a href="__APP__/Fabulist/index<?php echo ($yuurl); ?>" class="yubutton yuwhite">返回发布信息查询页面</a>
<br/><br/><br/>


<form action="__APP__/Fabulist/updatedata<?php echo ($yuurl); ?>" method="post" enctype="multipart/form-data">
	
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
			<select name="shstatus">
			<?php echo ($optioncheck); ?>
			</select>
		</td>
	</tr>
	
	
	<tr>
		<td width="200" align="right">平台类型：</td>
		<td>
			<input type="text" name="type" id="type" size="20" maxlength="30" value="<?php echo ($list['type']); ?>">
		</td>
	</tr>
	
	<tr>
		<td width="200" align="right">所在主分类:</td>
		<td>
			<select name="maintype">
			<?php echo ($optionmaintype); ?>
			</select>
		</td>
	</tr>
	
	<tr>
		<td width="200" align="right">所在子分类:</td>
		<td>
		<input type="text" name="childtype" id="childtype" size="50" maxlength="60" value="<?php echo ($list['childtype']); ?>">
		</td>
	</tr>
	
	<tr>
		<td align="right">标题:</td>
		<td>
			<input type="text" name="title" id="title" size="50" maxlength="60" value="<?php echo ($list['title']); ?>">
		</td>
	</tr>
	
	<tr>
		<td width="200" align="right">图片预览:</td>
		<td name="picurl">
			<img id="aaa" alt="" src="<?php echo ($list['picurl']); ?>" width="200">
		</td>
	</tr>
	
	<tr>
		<td width="200" align="right">发布图片:</td>
		<td>
			<input type='file' name='picurl' id='picurl'>
		</td>
	</tr>
	
	<tr>
		<td width="200" align="right">原价(元):</td>
		<td>
		<input type="type" name="yuanprice" id="yuanprice" size="15" maxlength="15" value="<?php echo ($list['yuanprice']); ?>">
		</td>
	</tr>
	
	<tr>
		<td width="200" align="right">现价(元):</td>
		<td>
		<input type="type" name="nowprice" id="nowprice" size="15" maxlength="15" value="<?php echo ($list['nowprice']); ?>">
		</td>
	</tr>
	
	<tr>
		<td width="200" align="right">折扣:</td>
		<td>
		<input type="type" name="reamrk" id="reamrk" size="10" maxlength="10" value="<?php echo ($list['reamrk']); ?>">
		<font color="red" size="-1">折扣取值为整数，例如1，2，3</font>
		</td>
	</tr>
	
	<tr>
		<td width="200" align="right">已领取人数:</td>
		<td>
		<input type="type" name="yilingcon" id="yilingcon" size="15" maxlength="15" value="<?php echo ($list['yilingcon']); ?>">
		</td>
	</tr>
	
	
	<tr>
		<td width="200" align="right">店铺地址:</td>
		<td>
			<input type="type" name="address" id="address" size="80" maxlength="100" value="<?php echo ($list['address']); ?>">
		</td>
	</tr>
	
	<tr>
		<td width="200" align="right">手机号:</td>
		<td>
			<input type="type" name="phone" id="phone" size="15" maxlength="15" value="<?php echo ($list['phone']); ?>">
		</td>
	</tr>
	
	<tr>
		<td width="200" align="right">商店名称:</td>
		<td>
			<input type="type" name="shopname" id="shopname" size="30" maxlength="30" value="<?php echo ($list['shopname']); ?>">
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