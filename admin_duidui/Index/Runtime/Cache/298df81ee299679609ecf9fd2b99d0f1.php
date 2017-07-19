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
		<font size="-1" ><b>当前位置&nbsp;#&nbsp;配置信息&nbsp;#&nbsp;轮播图上传管理</b></font></td></tr>
  	<tr><td bgcolor="#b1ceef" height="1"></td></tr>
	<tr height="20"><td background="__PUBLIC__/Images/shadow_bg.jpg"></td></tr>
</table>

<div style="text-align:center"><h1>首页轮播图下方广告图</h1></div>
<!-- <a href="__APP__/Lunbotu/addlunbotushow<?php echo ($yuurl); ?>" class="yubutton yuwhite">添加轮播图</a> -->
<br/><br/>

<table class="mainTables" width="1650">
<thead>
	<tr>
		<td width="20">&nbsp;</td>
		<td width="20">&nbsp;</td>
		<td width="25"><b>状态<br/></b></td>
		<td width="150"><b>轮播图地址</b></td>
		<td width="150"><b>轮播图展示</b></td>
		<td width="20"><b>跳转类型<br/></b></td>
		<td width="150"><b>跳转链接</b></td>
		<td width="30"><b>是否可以点击<br/></b></td>
		<td width="150"><b>跳转链接内容<br/></b></td>
		<td width="80"><b>上传时间<br/></b></td>
		<td width="60"><b>跳转链接（自己内部的跳转链接）<br/></b></td>
	</tr>
</thead>

<tbody>
	<?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "没有对应数据" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
	
		<td>
			<form action="__APP__/Guanggao/updatelunbotushow<?php echo ($yuurl); ?>" method="post" style="margin:0px">
			<input type="hidden" name="id" value="<?php echo ($vo["id"]); ?>" />
			<input type="submit" class="yubuttonss yuwhite" name="update_submit" value="修改" />
			</form>
		</td>
		
		<td>
			<form action="__APP__/Guanggao/deletelunbodata<?php echo ($yuurl); ?>" method="post" style="margin:0px">
			<input type="hidden" name="id" value="<?php echo ($vo["id"]); ?>" />
			<input type="submit" class="yubuttonss yuwhite delete_submit" name="delete_submit" value="删除" />
			</form>
		</td>
		
		<td><?php echo ($vo["flag"]); ?></td>
		<td><?php echo ($vo["img"]); ?></td>
		<td><img alt="" src="<?php echo ($vo["img"]); ?>" width="200"></td>
		<td><?php echo ($vo["action"]); ?></td>
		<td><?php echo ($vo["imgurl"]); ?></td>
		<td><?php echo ($vo["isused"]); ?></td>
		<td><?php echo ($vo["content"]); ?></td>
		<td><?php echo ($vo["createdatetime"]); ?></td>
		<td><?php echo ($vo["value"]); ?></td>
	</tr><?php endforeach; endif; else: echo "没有对应数据" ;endif; ?>
</tbody>

</table>

<br/><br/>


<div style="text-align:center"><h1>附近广告</h1></div>
<!-- <a href="__APP__/Lunbotu/addlunbotushow<?php echo ($yuurl); ?>" class="yubutton yuwhite">添加轮播图</a> -->
<br/><br/>

<table class="mainTables" width="1650">
<thead>
	<tr>
		<td width="4%">&nbsp;</td>
		<td width="4%">&nbsp;</td>
		<td width="4%"><b>状态<br/></b></td>
		<td width="5%"><b>广告跳转类型<br/></b></td>
		<td width="5%"><b>广告主类型</b></td>
		<td width="5%"><b>广告展示类型</b></td>
		<td width="15%"><b>广告展示图片</b></td>
		<td width="15%"><b>广告跳转链接</b></td>
		<td width="5%"><b>广告任务编号</b></td>
		<td width="10%"><b>广告标题<br/></b></td>
		<td width="15%"><b>广告描述<br/></b></td>
		<td width="8%"><b>广告上传时间<br/></b></td>
	</tr>
</thead>

<tbody>
	<?php if(is_array($blist)): $i = 0; $__LIST__ = $blist;if( count($__LIST__)==0 ) : echo "没有对应数据" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
	
		<td>
			<form action="__APP__/Guanggao/updateshow<?php echo ($yuurl); ?>" method="post" style="margin:0px">
			<input type="hidden" name="id" value="<?php echo ($vo["id"]); ?>" />
			<input type="submit" class="yubuttonss yuwhite" name="update_submit" value="修改" />
			</form>
		</td>
	
		<td>
			<form action="__APP__/Guanggao/deletedata<?php echo ($yuurl); ?>" method="post" style="margin:0px">
			<input type="hidden" name="id" value="<?php echo ($vo["id"]); ?>" />
			<input type="submit" class="yubuttonss yuwhite delete_submit" name="delete_submit" value="删除" />
			</form>
		</td>
		
		<td><?php echo ($vo["maintype"]); ?></td>
		<td><?php echo ($vo["gflag"]); ?></td>
		<td><?php echo ($vo["maintype"]); ?></td>
		<td><?php echo ($vo["gtype"]); ?></td>
		<td><img alt="" src="<?php echo ($vo["picurl"]); ?>" width="200"></td>
		
		<td><?php echo ($vo["adurl"]); ?></td>
		<td><?php echo ($vo["taskid"]); ?></td>
		<td><?php echo ($vo["adtitle"]); ?></td>
		<td><?php echo ($vo["adcontent"]); ?></td>
		<td><?php echo ($vo["createtime"]); ?></td>
	</tr><?php endforeach; endif; else: echo "没有对应数据" ;endif; ?>
</tbody>

</table>

<br/><br/>

<center><?php echo ($page); ?></center>

<br/><br/><br/><br/><br/><br/>
End!

</body>

</html>