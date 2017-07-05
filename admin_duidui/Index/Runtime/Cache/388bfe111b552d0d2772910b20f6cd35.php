<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//Dtd html 4.0 Transitional//EN">
<html>
<head>
<meta http-equiv=Content-Type content="text/html; charset=utf-8">
<link rel="stylesheet" type="text/css" href="__PUBLIC__/Css/admin.css" />

<script type="text/javascript" src="__PUBLIC__/Js/jquery.min.js"></script>
<script type="text/javascript" src="__PUBLIC__/Js/themain.js"></script>
<script type="text/javascript">
$("document").ready(function(){
	
	$(".delete_submit").click(function(){
		
		if(confirm("您确认要删除此条数据吗？")) {
			//alert('ok');
			if(confirm("您确认要删除此条数据吗？数据删除后可能会导致数据错乱，请确认该数据下是否存在关联数据")) {
				//alert('ok');
			}else {
				return false;
			}
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
		<font size="-1" ><b>当前位置&nbsp;#&nbsp;商城配置信息&nbsp;#&nbsp;商品类型管理&nbsp;#&nbsp;商品类型查询</b></font></td></tr>
  	<tr><td bgcolor="#b1ceef" height="1"></td></tr>
	<tr height="20"><td background="__PUBLIC__/Images/shadow_bg.jpg"></td></tr>
</table>

<a href="__APP__/Shoptype/addshow<?php echo ($yuurl); ?>" class="yubutton yuwhite">添加商品类型</a>
<br/><br/>

<form action="__APP__/Shoptype/index" method="get" >
<table class="mainTabled" >
<tr>
	<td>
		商品分类：
		<select name="xushi">
			<?php echo ($optionxushi); ?>
		</select>
	</td>
	
	<td>
		商品类型：
		<select name="type">
			<?php echo ($optiontype); ?>
		</select>
	</td>
	
	<td>
		商品类型名称：
		<input type="text" name="name" value="<?php echo ($name); ?>" size="30" maxlength="45" />
	</td>
	
	
	
	<td>
		<input type="submit" class="yubuttons yuwhite" name="submit_select" id="submit_select" value="查询指定匹配内容" />
	</td>
</tr>
</table>
</form>
<br/>

<table class="mainTables" width="80%">
<thead>
	<tr>
		<td width="60"><b>商品编号</b></td>
		<td width="60"><b>商品分类</b></td>
		<td width="70"><b>商品类型</b></td>
		<td width="70"><b>商品子类型<br/></b></td>
		<td width="80"><b>商品名称<br/></b></td>
		<td width="70"><b>备注<br/></b></td>
		<!-- <td width="55">&nbsp;</td> -->
		<td width="45">&nbsp;</td>
	</tr>
</thead>

<tbody>
	<?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "没有对应数据" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
		<td><?php echo ($vo["id"]); ?></td>
		<td><?php echo ($vo["xushi"]); ?></td>
		<td><?php echo ($vo["type"]); ?></td>
		<td><?php echo ($vo["childtype"]); ?></td>
		<td><?php echo ($vo["name"]); ?></td>
		<td><?php echo ($vo["remark"]); ?></td>
		<!-- <td>
			<form action="__APP__/Shoptype/updateshow<?php echo ($yuurl); ?>" method="post" style="margin:0px">
			<input type="hidden" name="id" value="<?php echo ($vo["id"]); ?>" />
			<input type="submit" class="yubuttonss yuwhite" name="update_submit" value="修改" />
			</form>
		</td> -->
		<td>
			<form action="__APP__/Shoptype/deletedata<?php echo ($yuurl); ?>" method="post" style="margin:0px">
			<input type="hidden" name="id" value="<?php echo ($vo["id"]); ?>" />
			<input type="hidden" name="type" value="1" />
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