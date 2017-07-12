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
		<font size="-1" ><b>当前位置&nbsp;#&nbsp;配置信息&nbsp;#&nbsp;兑换码管理</b></font></td></tr>
  	<tr><td bgcolor="#b1ceef" height="1"></td></tr>
	<tr height="20"><td background="__PUBLIC__/Images/shadow_bg.jpg"></td></tr>
</table>

<a href="__APP__/Duihuanma/addduihuanma<?php echo ($yuurl); ?>" class="yubutton yuwhite">添加兑换码信息</a>
<br/><br/>

<form action="__APP__/Duihuanma/index" method="get" >
<table class="mainTabled" >

<tr>
	<td align="center">是否使用</td>
	<td align="center">兑换码类型</td>
	<td align="center"></td>
</tr>

<tr>

	<td>
		<select name="flag_s">
			<?php echo ($optionflag); ?>
		</select>
	</td>
	
	<td>
		<select name="type_s">
			<?php echo ($optionchildtype); ?>
		</select>
	</td>
	
	<td>
		<input type="submit" class="yubuttons yuwhite" name="submit_select" id="submit_select" value="查询指定匹配内容" />
	</td>
</tr>
</table>
</form>
<br/>

<table class="mainTables" width="1000px">
<thead>
	<tr>
		
		<td width="30"><b>自增编号<br/></b></td>
		<!-- <td width="30"><b>商品id<br/></b></td> -->
		<td width="100"><b>商品名称<br/></b></td>
		<td width="30"><b>兑换码主类型<br/></b></td>
		<td width="30"><b>兑换码类型<br/></b></td>
		<td width="30"><b>渠道编号<br/></b></td>
		<td width="30"><b>使用状态</b></td>
		<td width="50"><b>兑换码<br/></b></td>
		
		<!-- <td width="30">&nbsp;</td> -->
		<td width="30">&nbsp;</td>
		
	</tr>
</thead>

<tbody>
	<?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "没有对应数据" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
		
		<td><?php echo ($vo["id"]); ?></td>
		<!-- <td><?php echo ($vo["goods_id"]); ?></td> -->
		<td><?php echo ($vo["goods_name"]); ?></td>
		<td><?php echo ($vo["maintype"]); ?></td>
		<td><?php echo ($vo["type"]); ?></td>
		<td><?php echo ($vo["siteid"]); ?></td>
		<td><?php echo ($vo["flag"]); ?></td>
		<td><?php echo ($vo["duihuanma"]); ?></td>
		
	<!-- 	<td>
			<form action="__APP__/Duihuanma/updatedhshow<?php echo ($yuurl); ?>" method="post" style="margin:0px">
			<input type="hidden" name="id" value="<?php echo ($vo["id"]); ?>" />
			<input type="submit" class="yubuttonss yuwhite" name="update_submit" value="修改" />
			</form>
		</td> -->
		
		<td>
			<form action="__APP__/Duihuanma/deletedhdata<?php echo ($yuurl); ?>" method="post" style="margin:0px">
			<input type="hidden" name="id" value="<?php echo ($vo["id"]); ?>" />
			<input type="submit" class="yubuttonss yuwhite delete_submit" name="delete_submit" value="删除" />
			</form>
	</td>
	
	</tr><?php endforeach; endif; else: echo "没有对应数据" ;endif; ?>
</tbody>

</table>

<br/><br/>

<center><?php echo ($page); ?></center>

<br/><br/><br/><br/><br/><br/>
End!

</body>

</html>