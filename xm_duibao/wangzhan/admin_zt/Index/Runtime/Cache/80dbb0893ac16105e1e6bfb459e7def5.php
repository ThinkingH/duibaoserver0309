<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//Dtd html 4.0 Transitional//EN">
<html>
<head>
<meta http-equiv=Content-Type content="text/html; charset=utf-8">
<link rel="stylesheet" type="text/css" href="__PUBLIC__/Css/admin.css" />

<script type="text/javascript" src="__PUBLIC__/Js/jquery.min.js"></script>
<script type="text/javascript" src="__PUBLIC__/Js/themain.js"></script>
<script type="text/javascript">
$("document").ready(function(){
	
	 //数据添加确认
	$("#update_submit").click(function() {
		
		if(confirm("您确认要审核通过该条数据吗？")) {
			//alert('ok');
		}else {
			return false;
		}
	}); 
	 
	$("#zuofei_submit").click(function() {zuofei_submit
			
			if(confirm("您确认要作废该条数据吗？")) {
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
	<tr height="28"><td>
		<font size="-1" ><b>当前位置&nbsp;#&nbsp;中铁数据查询&nbsp;#&nbsp;订单信息审核&nbsp;#&nbsp;订单的初审</b></font></td></tr>
	<tr><td bgcolor="#b1ceef" height="1"></td></tr>
	<tr height="20"><td></td></tr>
</table>

<br/>

<a href="__APP__/Reportdata/index<?php echo ($yuurl); ?>" class="yubutton yuwhite">返回主页面</a>
<br/><br/><br/>



<table class="mainTabled">
	
	<tr>
		<td width="200" align="right">编号:</td>
		<td width="800">
			<b><?php echo ($list['id']); ?></b>
		</td>
	</tr>
	
	<tr>
		<td width="200" align="right">审核状态:</td>
		<td width="800">
			<b><?php echo ($list['flag']); ?></b>
		</td>
	</tr>
	<tr>
		<td width="200" align="right">公司:</td>
		<td width="800">
		<b>
		<?php echo ($list['company']); ?>
		</b>
		</td>
	</tr>
	
	<tr>
		<td width="200" align="right">电话:</td>
		<td width="800">
		<b>
		<?php echo ($list['phone']); ?>
		</b>
		</td>
	</tr>
	
	<tr>
		<td width="200" align="right">上家类型:</td>
		<td width="800">
		<b>
			<?php echo ($list['shangjiatype']); ?>
		</b>
		</td>
		</td>
	</tr>
	
	<tr>
		<td width="200" align="right">产品名称:</td>
		<td width="800">
		<b>
			<?php echo ($list['name']); ?>
		</b>
		</td>
	</tr>
	
	<tr>
		<td width="200" align="right">价格:</td>
		<td width="800">
		<b>
			<?php echo ($list['price']); ?>
		</b>
		</td>
	</tr>
	
	<tr>
		<td width="200" align="right">数量:</td>
		<td width="800">
		<b>
			<?php echo ($list['shuliang']); ?>
		</b>
		</td>
	</tr>
	
	<tr>
		<td width="200" align="right">交货地:</td>
		<td width="800">
		<b>
			<?php echo ($list['address']); ?>
		</b>
		</td>
	</tr>
	
	
	
	<tr>
		<td width="200" align="right">配送方式:</td>
		<td width="800">
		<b>
			<?php echo ($list['sendtype']); ?>
		</b>
		</td>
	</tr>
	
	<tr>
		<td width="200" align="right">提交时间:</td>
		<td width="800">
		<b>
			<b><?php echo ($list['tijiao_time']); ?></b>
		</b>
		</td>
	</tr>
	
	<tr>
		<td width="200" align="right">审核发布时间:</td>
		<td width="800">
		<b>
			<b><?php echo ($list['createtime']); ?></b>
		</b>
		</td>
	</tr>
	
	<tr>
		<td width="200" align="right">备注:</td>
		<td width="800">
		<b>
			<?php echo ($list['comment']); ?>
		</b>
		</td>
	</tr>
	
</table>
<br/><br/>

<table>
<tr>

<td>
<form action="__APP__/Reportdata/chushendata<?php echo ($yuurl); ?>" method="post">
<input type="hidden" name="id" value="<?php echo ($list['id']); ?>" />
<input type="submit" id="update_submit" class="yubutton yuwhite" name="update_submit"  value="确认初审" />
</form>
</td>


<td>
<form action="__APP__/Reportdata/zuofeidata<?php echo ($yuurl); ?>" method="post">
<input type="hidden" name="id" value="<?php echo ($list['id']); ?>" />
<input type="submit" id="zuofei_submit" class="yubutton yuwhite" name="zuofei_submit" value="作废" />
</form>
</td>

</tr>
</table>
<!-- </form> -->

<br/><br/><br/><br/><br/><br/>
End!

</body>

</html>