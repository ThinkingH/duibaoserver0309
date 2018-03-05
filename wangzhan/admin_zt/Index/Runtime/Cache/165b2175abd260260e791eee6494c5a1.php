<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//Dtd html 4.0 Transitional//EN">
<html>
<head>
<meta http-equiv=Content-Type content="text/html; charset=utf-8">
<link rel="stylesheet" type="text/css" href="__PUBLIC__/Css/admin.css" />

<script type="text/javascript" src="__PUBLIC__/Js/jquery.min.js"></script>
<script type="text/javascript" src="__PUBLIC__/Js/themain.js"></script>
<script type="text/javascript">
$("document").ready(function(){
		
});


</script>
</head>

<body>
<table cellspacing="0" cellpadding="0" width="100%" align="center" border="0" >
	<tr height="28"><td>
		<font size="-1" ><b>当前位置&nbsp;#&nbsp;用户信息&nbsp;#&nbsp;用户信息的修改</b></font></td></tr>
	<tr><td bgcolor="#b1ceef" height="1"></td></tr>
	<tr height="20"><td></td></tr>
</table>

<br/>

<a href="__APP__/Userdata/index<?php echo ($yuurl); ?>" class="yubutton yuwhite">返回主页面</a>
<br/><br/><br/>


<form action="__APP__/Userdata/updatedata<?php echo ($yuurl); ?>" method="post">

<input type="hidden" name="id" value="<?php echo ($list['id']); ?>" />

<table class="mainTabled">
	
	<tr>
		<td width="200" align="right">编号:</td>
		<td width="800">
			<b><?php echo ($list['id']); ?></b>
		</td>
	</tr>
	
	<tr>
		<td width="200" align="right">用户账号:</td>
		<td width="800">
			<b><?php echo ($list['phone']); ?></b>
		</td>
	</tr>
	
	<tr>
		<td width="200" align="right">会员等级:</td>
			<td width="800">
			<select id="flag" name="flag">
					<?php echo ($optiontype); ?>
			</select>
			</td>
		</td>
	</tr>
	
	<tr>
		<td width="200" align="right">会员有效期:</td>
			<td width="800">
			<input type="text" size="5" maxlength="10" name="year" value="<?php echo ($list['hymonth']); ?>" />月
			</td>
			
		</td>
	</tr>
	
	<tr>
		<td width="200" align="right">会员有效期增减:</td>
			<td width="800">
			<input type="text" size="5" maxlength="10" name="zj_month"/>月
			<font color="red">*会员有效期可以是正数或负数，如果为负数，则是减少会员的有效期，如果为正数，则是增加会员的有效期</font>
			</td>
			
		</td>
	</tr>
	
	
	<tr>
		<td width="200" align="right">公司:</td>
		<td width="800">
		<b><?php echo ($list['company']); ?></b>
		</td>
	</tr>
	<tr>
		<td width="200" align="right">联系人:</td>
		<td width="800">
		<b><?php echo ($list['lianxiren']); ?></b>
		</td>
	</tr>
	<tr>
		<td width="200" align="right">电话:</td>
		<td width="800">
		<b><?php echo ($list['phone']); ?></b>
		</td>
	</tr>
	
	<tr>
		<td width="200" align="right">qq:</td>
		<td width="800">
		<b>
			<?php echo ($list['email']); ?>
		</b>
		</td>
		</td>
	</tr>
	
	<tr>
		<td width="200" align="right">公司地址:</td>
		<td width="800">
		<b>
			<?php echo ($list['address']); ?>
		</b>
		</td>
	</tr>
	
	<tr>
		<td width="200" align="right">会员购买时间:</td>
		<td width="800">
		<b>
			<?php echo ($list['buytime']); ?>
		</b>
		</td>
	</tr>
	
	<tr>
		<td width="200" align="right">会员到期时间:</td>
		<td width="800">
		<b>
			<?php echo ($list['overtime']); ?>
		</b>
		</td>
	</tr>
	
	<tr>
		<td width="200" align="right">注册时间:</td>
		<td width="800">
		<b>
			<?php echo ($list['createtime']); ?>
		</b>
		</td>
	</tr>
	
	
	
	<tr>
		<td width="200" align="right">备注:</td>
		<td width="800">
		<b>
		<input type="text" size="20" maxlength="20" name="remark" value="<?php echo ($list['remark']); ?>" />
		</b>
		</td>
	</tr>
	
</table>
<br/><br/>
<input type="submit" id="update_submit" class="yubutton yuwhite" name="update_submit" style="margin:15px 0px 0px 10px;" value="确认修改" />

 </form> 

<br/><br/><br/><br/><br/><br/>
End!

</body>

</html>