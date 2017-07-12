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
		<font size="-1" ><b>当前位置&nbsp;#&nbsp;配置信息&nbsp;#&nbsp;产品信息管理&nbsp;#&nbsp;产品信息管理查询</b></font></td></tr>
  	<tr><td bgcolor="#b1ceef" height="1"></td></tr>
	<tr height="20"><td background="__PUBLIC__/Images/shadow_bg.jpg"></td></tr>
</table>

<a href="__APP__/Codelist/addcodelistshow<?php echo ($yuurl); ?>" class="yubutton yuwhite">添加产品信息</a>
<br/><br/>

<form action="__APP__/Codelist/index" method="get" >
<table class="mainTabled" >
<tr>
	<td>
		是否启用：
		<select name="flag_s">
			<?php echo ($optionflag); ?>
		</select>
	</td>
	<td>
		运营商：
		<select name="gateway_s">
			<?php echo ($optiongateway); ?>
		</select>
	</td>
	<td>
		商品名称：
		<input type="text" name="name_s" id="name_s" value="<?php echo ($name_s); ?>" size="16" maxlength="30" />
	</td>
	<td>
		流量兆数：
		<select name="mbps_s">
			<?php echo ($optionmbps); ?>
		</select>
	</td>
	<td>
		使用范围：
		<select name="ttype_s">
			<?php echo ($optionttype); ?>
		</select>
	</td>
	<td>
		上家产品编号：
		<input type="text" name="productid_s" id="productid_s" value="<?php echo ($productid_s); ?>" size="10" maxlength="10" />
	</td>
	<td>
		备注：
		<input type="text" name="remark_s" value="<?php echo ($remark_s); ?>" size="30" maxlength="45" />
	</td>
	<td>
		<input type="submit" class="yubuttons yuwhite" name="submit_select" id="submit_select" value="查询指定匹配内容" />
	</td>
</tr>
</table>
</form>
<br/>

<table class="mainTables" width="1500px">
<thead>
	<tr>
		<td width="50"><b>是否启用</b></td>
		<td width="60"><b>标识编号</b></td>
		<td width="200"><b>商品名称</b></td>
		<td width="60"><b>运营商</b></td>
		<td width="60"><b>流量兆数</b></td>
		<td width="70"><b>使用范围(1全国，2省内)</b></td>
		<td width="70"><b>充值省份(空时为全国)</b></td>
		<td width="130"><b>上家分配产品编号</b></td>
		<td width="60"><b>成本价</b></td>
		<td width="60"><b>原价</b></td>
		<td width="60"><b>成本折扣</b></td>
		<td width="170"><b>备注</b></td>
		<td width="50">&nbsp;</td>
		<td width="50">&nbsp;</td>
	</tr>
</thead>

<tbody>
	<?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "没有对应数据" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
		<td><?php echo ($vo["flag"]); ?></td>
		<td><?php echo ($vo["id"]); ?></td>
		<td><?php echo ($vo["name"]); ?></td>
		<td><?php echo ($vo["gateway"]); ?></td>
		<td><?php echo ($vo["mbps"]); ?></td>
		<td><?php echo ($vo["ttype"]); ?></td>
		<td><?php echo ($vo["province"]); ?></td>
		<td><?php echo ($vo["productid"]); ?></td>
		<td><?php echo ($vo["now_price"]); ?></td>
		<td><?php echo ($vo["yuan_price"]); ?></td>
		<td><font color="red"><?php echo round($vo['now_price']/$vo['yuan_price'],2); ?></font></td>
		<td><?php echo ($vo["remark"]); ?></td>
		<td>
			<form action="__APP__/Codelist/updatecodelistshow<?php echo ($yuurl); ?>" method="post" style="margin:0px">
			<input type="hidden" name="codelist_id" value="<?php echo ($vo["id"]); ?>" />
			<input type="submit" class="yubuttonss yuwhite" name="update_submit" value="修改" />
			</form>
		</td>
		<td>
			<form action="__APP__/Codelist/deletecodelistdata<?php echo ($yuurl); ?>" method="post" style="margin:0px">
			<input type="hidden" name="codelist_id" value="<?php echo ($vo["id"]); ?>" />
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