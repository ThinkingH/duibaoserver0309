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
	
	
	$(".mainTables>tbody>tr>td").hover(function(){
		$(this).parent().children().addClass('yu_mourse_stop_change');
	},function(){
		$(this).parent().children().removeClass('yu_mourse_stop_change');
	});
	
	/* //数据添加确认
	$("#fabu_check").click(function() {
		
		if(confirm("您确认要发布该条数据吗？")) {
			//alert('ok');
		}else {
			return false;
		}
	});  */
	
	//数据添加确认
	$("#del").click(function() {
		
		if(confirm("您确认要删除这条数据吗？")) {
			//alert('ok');
		}else {
			return false;
		}
	});  
	
});


</script>
</head>

<body>
<table cellspacing="0" cellpadding="0" width="100%" align="center" border="0">
	<tr height="28"><td>
		<font size="-1" ><b>当前位置&nbsp;#&nbsp;中铁数据查询&nbsp;#&nbsp;订单信息录入</b></font></td></tr>
  	<tr><td bgcolor="#b1ceef" height="1"></td></tr>
	<tr height="20"><td></td></tr>
</table>


<form action="__APP__/Reportdata/xiugaishow" method="get" >
<table class="mainTabled" >
<tr>
	<td align="center" width="110">开始日期</td>
	<td align="center" width="110">结束日期</td>
	<td align="center" width="120"></td>
</tr>
<tr>
	<td>
		<input type="text" size="12" maxlength="10" name="date_s" value="<?php echo ($date_s); ?>" onclick="WdatePicker()" />
	</td>
	<td>
		<input type="text" size="12" maxlength="10" name="date_e" value="<?php echo ($date_e); ?>" onclick="WdatePicker()" />
	</td>
	
	<td>
		<input type="submit" class="yubuttons yuwhite" name="submit_select" value="查询指定匹配内容" />
	</td>
</tr>
</table>
</form>
<br/>

<table class="mainTables" width="1500">
<thead>
	<tr>
		<td width="25"><b></b></td>
		<td width="25"><b></b></td>
		<td width="40"><b></b></td>
		<td width="25"><b>编号</b></td>
		<td width="40"><b>审核状态</b></td>
		<td width="90"><b>公司</b></td>
		<td width="60"><b>电话</b></td>
		<!-- <td width="40"><b>上家类型</b></td> -->
		<td width="90"><b>产品名称</b></td>
		<td width="40"><b>价格(元)</b></td>
		<td width="50"><b>数量(吨)</b></td>
		
		
		<!-- <td width="150"><b><font color="purple">交货地</font></b></td> -->
		<!-- <td width="90"><b><font color="purple">配送方式</font></b></td> -->
		<td width="80"><b><font color="purple">提交时间</font></b></td>
		<td width="80"><b><font color="purple">审核发布时间</font></b></td>
		<td width="150"><b><font color="purple">备注</font></b></td>
		
		
	</tr>
</thead>

<tbody>
	<?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "没有对应数据" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
	<?php if($vo['flag']=='10'){ ?>
	
		<td>
		<form action="__APP__/Reportdata/xiugaichakan<?php echo ($yuurl); ?>" method="post" style="margin:0px">
		<input type="hidden" name="id" value="<?php echo ($vo["id"]); ?>" />
		<input type="hidden" name="flag" value="<?php echo ($vo["flag"]); ?>" />
		<input type="submit" class="yubuttonss yuwhite" id= "chakan" name="chakan" value="查看" />
		</form>
		</td>
		
		<td>
		<form action="__APP__/Reportdata/editshow<?php echo ($yuurl); ?>" method="post" style="margin:0px">
		<input type="hidden" name="id" value="<?php echo ($vo["id"]); ?>" />
		<input type="hidden" name="flag" value="<?php echo ($vo["flag"]); ?>" />
		<input type="submit" class="yubuttonss yuwhite" name="first_check" value="修改" />
		</form>
		</td>
		
		<td>
		<form action="__APP__/Reportdata/deldata<?php echo ($yuurl); ?>" method="post" style="margin:0px">
		<input type="hidden" name="id" value="<?php echo ($vo["id"]); ?>" />
		<input type="hidden" name="flag" value="<?php echo ($vo["flag"]); ?>" />
		<input type="submit" class="yubuttonss yuwhite" id="del" name="del" value="删除" />
		</form>
		</td>
		<!-- <td></td> -->
		
	<?php } ?>
		
		
		<td><?php echo ($vo["id"]); ?></td>
		<td><?php echo ($vo["flags"]); ?></td>
		<td><?php echo ($vo["company"]); ?></td>
		<td><?php echo ($vo["phone"]); ?></td>
		<!-- <td><?php echo ($vo["shangjiatype"]); ?></td> -->
		<td><?php echo ($vo["name"]); ?></td>
		
		<td><?php echo ($vo["price"]); ?></td>
		<td><?php echo ($vo["shuliang"]); ?></td>
		<!-- <td><?php echo ($vo["address"]); ?></td> -->
		<!-- <td><?php echo ($vo["sendtype"]); ?></td> -->
		<td><?php echo ($vo["tijiao_time"]); ?></td>
		<td><?php echo ($vo["createtime"]); ?></td>
		<td><?php echo ($vo["comment"]); ?></td>
		
	</tr><?php endforeach; endif; else: echo "没有对应数据" ;endif; ?>
	
	
</tbody>

</table>
<br/><br/>

<center><?php echo ($page); ?></center>

<br/><br/><br/><br/><br/><br/>

</body>

</html>