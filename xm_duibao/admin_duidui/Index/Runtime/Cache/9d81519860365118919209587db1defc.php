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
	
	$("#submit_select").click(function(){
		
		var site_id = $("#site_s").val();
		if(site_id=='') {
			
		}else {
			var reg = /^[1-9][0-9]{3}$/;
			if(!reg.test(site_id)) {
				alert('渠道编号必须介于1000到9999之间');
				return false;
			}
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
		<font size="-1" ><b>当前位置&nbsp;#&nbsp;配置信息&nbsp;#&nbsp;渠道信息管理&nbsp;#&nbsp;渠道信息查询</b></font></td></tr>
  	<tr><td bgcolor="#b1ceef" height="1"></td></tr>
	<tr height="20"><td background="__PUBLIC__/Images/shadow_bg.jpg"></td></tr>
</table>

<a href="__APP__/Basicsite/addsiteshow<?php echo ($yuurl); ?>" class="yubutton yuwhite">添加渠道信息</a>
<br/><br/>

<form action="__APP__/Basicsite/index" method="get" >
<table class="mainTabled" >
<tr>
	<td>
		渠道编号：
		<input type="text" name="site_s" id="site_s" value="<?php echo ($site_s); ?>" size="6" maxlength="4" />
	</td>
	<td>
		渠道名称：
		<input type="text" name="name_s" value="<?php echo ($name_s); ?>" size="30" maxlength="45" />
	</td>
	<td>
		是否启用：
		<select name="flag_s">
			<?php echo ($optionflag); ?>
		</select>
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
		<td width="60"><b>渠道编号</b></td>
		<td width="120"><b>渠道名称</b></td>
		<td width="70"><b>是否启用<br/></b></td>
		<td width="140"><b>备注</b></td>
		<td width="55">&nbsp;</td>
		<td width="45">&nbsp;</td>
	</tr>
</thead>

<tbody>
	<?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "没有对应数据" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
		<td><?php echo ($vo["id"]); ?></td>
		<td><?php echo ($vo["name"]); ?></td>
		<td><?php echo ($vo["flag"]); ?></td>
		<td><?php echo ($vo["remark"]); ?></td>
		<td>
			<form action="__APP__/Basicsite/updatesiteshow<?php echo ($yuurl); ?>" method="post" style="margin:0px">
			<input type="hidden" name="site_id" value="<?php echo ($vo["id"]); ?>" />
			<input type="submit" class="yubuttonss yuwhite" name="update_submit" value="修改" />
			</form>
		</td>
		<td>
			<form action="__APP__/Basicsite/deletesitedata<?php echo ($yuurl); ?>" method="post" style="margin:0px">
			<input type="hidden" name="site_id" value="<?php echo ($vo["id"]); ?>" />
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