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
	
	
	
	/* //数据添加确认
	$("#submit").click(function() {
		
		var wtype = $("#wtype").val();
		var ftype = $("#ftype").val();
		
		
		$.ajax({
			type: "POST",
			async: false,
			url: "__APP__/Maintype/ajax_wangzhan",
			data: "wtype="+wtype+"ftype="+ftype,
			success: function(data){
				if (data == 'no') {
					$("#sucflag").val('no');
				}else {
					$("#sucflag").val('ok');
				}
			}
		});
		
	}); */
	
	
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
		<font size="-1" ><b>当前位置&nbsp;#&nbsp;配置信息&nbsp;#&nbsp;首页类型管理</b></font></td></tr>
  	<tr><td bgcolor="#b1ceef" height="1"></td></tr>
	<tr height="20"><td background="__PUBLIC__/Images/shadow_bg.jpg"></td></tr>
</table>

<br/><br/>

<h2>网站类型设置,现在主要分1或2两种</h2>
<table class="mainTables" width="900">
<thead>
	<tr>
		<td width="60">&nbsp;</td>
		<td width="130"><b>网站类型</b></td>
	</tr>
</thead>

<tbody>
	
	<tr>
		<td>
			<form action="__APP__/Maintype/wwupdatetypeshow<?php echo ($yuurl); ?>" method="post" style="margin:0px">
			<input type="hidden" name="type" value="11" />
			<input type="submit" class="yubuttonss yuwhite" name="update_submit" value="首页优惠信息修改" />
			</form>
		</td>
		<td><?php echo ($wtype); ?></td>
	</tr>
	
</tbody>

<tbody>
	
	<tr>
		<td>
			<form action="__APP__/Maintype/wwupdatetypeshow<?php echo ($yuurl); ?>" method="post" style="margin:0px">
			<input type="hidden" name="type" value="22" />
			<input type="submit" class="yubuttonss yuwhite" name="update_submit" value="发现数据信息修改" />
			</form>
		</td>
		<td><?php echo ($ftype); ?></td>
	</tr>
	
</tbody>

</table>

<br/><br/>


<h2>首页主分类</h2>
<table class="mainTables" width="900">
<thead>
	<tr>
		<td width="60">&nbsp;</td>
		<td width="60">&nbsp;</td>
		<td width="130"><b>是否启用</b></td>
		<td width="100"><b>优惠券类型<br/></b></td>
		<td width="150"><b>优惠券名称<br/></b></td>
		<td width="280"><b>小图片logo<br/></b></td>
		<td width="130"><b>上传时间<br/></b></td>
	</tr>
</thead>

<tbody>
	<?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "没有对应数据" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
	
		<td>
			<form action="__APP__/Maintype/updatetypeshow<?php echo ($yuurl); ?>" method="post" style="margin:0px">
			<input type="hidden" name="kindtype" value="<?php echo ($vo["kindtype"]); ?>" />
			<input type="submit" class="yubuttonss yuwhite" name="update_submit" value="修改" />
			</form>
		</td>
		
		<td>
			<form action="__APP__/Maintype/deletetypedata<?php echo ($yuurl); ?>" method="post" style="margin:0px">
			<input type="hidden" name="kindtype" value="<?php echo ($vo["kindtype"]); ?>" />
			<input type="submit" class="yubuttonss yuwhite delete_submit" name="delete_submit" value="删除" />
			</form>
		</td>
		<td><?php echo ($vo["flag"]); ?></td>
		<td><?php echo ($vo["kindtype"]); ?></td>
		<td><?php echo ($vo["kindname"]); ?></td>
		<td><img src="<?php echo ($vo["smallpic"]); ?>" /></td>
		<td><?php echo ($vo["create_date"]); ?></td>
	</tr><?php endforeach; endif; else: echo "没有对应数据" ;endif; ?>
</tbody>

</table>

<br/><br/>

<h2>子分类</h2>
<a href="__APP__/Maintype/addtypeshow<?php echo ($yuurl); ?>" class="yubutton yuwhite">添加子类型</a>
<table class="mainTables" width="900">
<thead>
	<tr>
		<td width="60">&nbsp;</td>
		<td width="60">&nbsp;</td>
		<td width="130"><b>是否启用</b></td>
		<td width="100"><b>主类型<br/></b></td>
		<td width="150"><b>子名称<br/></b></td>
		<td width="280"><b>图片<br/></b></td>
		<td width="130"><b>上传时间<br/></b></td>
	</tr>
</thead>

<tbody>
	<?php if(is_array($lists)): $i = 0; $__LIST__ = $lists;if( count($__LIST__)==0 ) : echo "没有对应数据" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
	
		<td>
			<form action="__APP__/Maintype/chupdatetypeshow<?php echo ($yuurl); ?>" method="post" style="margin:0px">
			<input type="hidden" name="kindtype" value="<?php echo ($vo["id"]); ?>" />
			<input type="submit" class="yubuttonss yuwhite" name="update_submit" value="修改" />
			</form>
		</td>
		
		<td>
			<form action="__APP__/Maintype/deletetypedata<?php echo ($yuurl); ?>" method="post" style="margin:0px">
			<input type="hidden" name="kindtype" value="<?php echo ($vo["id"]); ?>" />
			<input type="submit" class="yubuttonss yuwhite delete_submit" name="delete_submit" value="删除" />
			</form>
		</td>
		<td><?php echo ($vo["flag"]); ?></td>
		<td><?php echo ($vo["type"]); ?></td>
		<td><?php echo ($vo["childtype"]); ?></td>
		<td><img src="<?php echo ($vo["smallpic"]); ?>" /></td>
		<td><?php echo ($vo["createtime"]); ?></td>
	</tr><?php endforeach; endif; else: echo "没有对应数据" ;endif; ?>
</tbody>

</table>

<br/><br/>

<center><?php echo ($page); ?></center>



<center><?php echo ($page); ?></center>

<br/><br/><br/><br/><br/><br/>
End!

</body>

</html>