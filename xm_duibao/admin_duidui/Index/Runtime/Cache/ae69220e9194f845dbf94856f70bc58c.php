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
	
	$(".delete_submit").click(function(){
		
		if(confirm("您确认要删除此条数据吗？")) {
		}else {
			return false;
		}
	});
	
	
	$(".mainTables>tbody>tr>td").hover(function(){
		$(this).parent().children().addClass('yu_mourse_stop_change');
	},function(){
		$(this).parent().children().removeClass('yu_mourse_stop_change');
	});
	
	
	
});


</script>
</head>

<body>
<table cellspacing="0" cellpadding="0" width="100%" align="center" border="0">
	<tr height="28"><td background="__PUBLIC__/Images/title_bg1.jpg">
		<font size="-1" ><b>当前位置&nbsp;#&nbsp;商城数据的查询&nbsp;#&nbsp;兑换订单查询</b></font></td></tr>
  	<tr><td bgcolor="#b1ceef" height="1"></td></tr>
	<tr height="20"><td background="__PUBLIC__/Images/shadow_bg.jpg"></td></tr>
</table>


<form action="__APP__/Orderlist/dealorder" method="get" >
<table class="mainTabled" >

<tr>
	<td align="center">订单创建时间</td>
	<td align="center">用户手机号</td>
	<td align="center">订单号</td>
	<td align="center">商品名称</td>
	<td align="center">商品类型</td>
	<td align="center">订单状态</td>
	<td align="center">是否兑换</td>
	<td align="center"></td>
</tr>


<tr>
	
	<td>
		<input type="text" size="10" maxlength="10" name="date_s" value="<?php echo ($date_s); ?>" onclick="WdatePicker()" />--
		<input type="text" size="10" maxlength="10" name="date_e" value="<?php echo ($date_e); ?>"  onclick="WdatePicker()"/>
	</td>
	
	<td>
	<input type="text" name="phone" value="<?php echo ($phone); ?>" size="15" maxlength="15">
	</td>
	
	<td>
	<input type="text" name="orderno" value="<?php echo ($orderno); ?>" size="20" maxlength="20">
	</td>
	
	<td>
	<input type="text" name="name" value="<?php echo ($name); ?>" size="20" maxlength="20">
	</td>
	
	<td >
		<select name="miyao">
			<?php echo ($optiontype); ?>
		</select>
	</td>
	
	<td >
		<select name="zstatus">
			<?php echo ($optionstatus); ?>
		</select>
	</td>
	
	<td >
		<select name="duihuan">
			<?php echo ($optionduihuan); ?>
		</select>
	</td>
	
	
	<td>
		<input type="submit" class="yubuttons yuwhite" name="submit_select" id="submit_select" value="查询指定匹配内容" />
	</td>
</tr>
</table>
</form>
<br/>

<table class="mainTables" width="2000">
<thead>
	<tr>
		<td width="50">&nbsp;</td>
		<td width="50"><b>自增编号<br/></b></td>
		<td width="100"><b>用户手机号<br/></b></td>
		<td width="80"><b>标识用户id<br/></b></td>
		<td width="120"><b>渠道编号</b></td>
		<td width="100"><b>商品类型</b></td>
		<td width="200"><b>商品名称</b></td>
		<td width="80"><b>商品数量</b></td>
		<td width="80"><b>花费金额(元)<br/></b></td>
		<td width="80"><b>花费总积分<br/></b></td>
		<td width="80"><b>商品编号<br/></b></td>
		<td width="100"><b>商品状态<br/></b></td>
		<td width="150"><b>订单号<br/></b></td>
		<td width="150"><b>兑换码/账号<br/></b></td>
		<td width="150"><b>密码<br/></b></td>
		<td width="150"><b>订单创建时间<br/></b></td>
		<td width="150"><b>订单领取时间<br/></b></td>
	</tr>
</thead>

<tbody>
	<?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "没有对应数据" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
		<td>
			<form action="__APP__/Orderlist/updateshow<?php echo ($yuurl); ?>" method="post" style="margin:0px">
			<input type="hidden" name="id" value="<?php echo ($vo["id"]); ?>" />
			<input type="submit" class="yubuttonss yuwhite" name="update_submit" value="兑换" />
			</form>
		</td>
	
		<td><?php echo ($vo["id"]); ?></td>
		<td><?php echo ($vo["phone"]); ?></td>
		<td><?php echo ($vo["userid"]); ?></td>
		<td><?php echo ($vo["siteid"]); ?></td>
		<td><?php echo ($vo["typeid"]); ?></td>
		<td><?php echo ($vo["name"]); ?></td>
		<td><?php echo ($vo["productnum"]); ?></td>
		<td><?php echo ($vo["price"]); ?></td>
		<td><?php echo ($vo["score"]); ?></td>
		<td><?php echo ($vo["productid"]); ?></td>
		<td><?php echo ($vo["status"]); ?></td>
		<td><?php echo ($vo["orderno"]); ?></td>
		<td><?php echo ($vo["keystr"]); ?></td>
		<td><?php echo ($vo["passwd"]); ?></td>
		<td><?php echo ($vo["order_createtime"]); ?></td>
		<td><?php echo ($vo["fh_shouhuoren"]); ?></td>
	</tr><?php endforeach; endif; else: echo "没有对应数据" ;endif; ?>
</tbody>

</table>

<br/><br/>

<center><?php echo ($page); ?></center>

<br/><br/><br/><br/><br/><br/>
End!

</body>

</html>