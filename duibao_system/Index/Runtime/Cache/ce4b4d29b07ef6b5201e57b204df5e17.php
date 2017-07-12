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
	
	
	
});


</script>
</head>

<body>
<table cellspacing="0" cellpadding="0" width="100%" align="center" border="0">
	<tr height="28"><td background="__PUBLIC__/Images/title_bg1.jpg">
		<font size="-1" ><b>当前位置&nbsp;#&nbsp;配置信息&nbsp;#&nbsp;每月礼包说明</b></font></td></tr>
  	<tr><td bgcolor="#b1ceef" height="1"></td></tr>
	<tr height="20"><td background="__PUBLIC__/Images/shadow_bg.jpg"></td></tr>
</table>

<h2 style="padding-left:40%">每月礼包说明</h2>
<br/>
<table class="mainTables" width="1500">
<thead>
	<tr>
		<td width="40">&nbsp;</td>
		<td width="80"><b>标题一<br/></b></td>
		<td width="200"><b>内容<br/></b></td>
		<td width="80"><b>标题二<br/></b></td>
		<td width="200"><b>内容<br/></b></td>
		<td width="80"><b>标题三</b></td>
		<td width="200"><b>内容</b></td>
		<td width="80"><b>标题四</b></td>
		<td width="200"><b>内容</b></td>
		<td width="200"><b>展示图片</b></td>
	</tr>
</thead>

<tbody>
	<?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "没有对应数据" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
	
		<td>
			<form action="__APP__/Monthprize/updateprizeshow<?php echo ($yuurl); ?>" method="post" style="margin:0px">
			<input type="hidden" name="flag" value="<?php echo ($vo["flag"]); ?>" />
			<input type="submit" class="yubuttonss yuwhite" name="update_submit" value="修改" />
			</form>
		</td>
		
		<td><?php echo ($vo["title1"]); ?></td>
		<td><?php echo ($vo["shengming"]); ?></td>
		<td><?php echo ($vo["title2"]); ?></td>
		<td><?php echo ($vo["shuoming"]); ?></td>
		<td><?php echo ($vo["title3"]); ?></td>
		<td><?php echo ($vo["guize"]); ?></td>
		<td><?php echo ($vo["title4"]); ?></td>
		<td><?php echo ($vo["fangfa"]); ?></td>
		<td><img alt="" src="<?php echo ($vo["picurl"]); ?>" width='200'></td>
	</tr><?php endforeach; endif; else: echo "没有对应数据" ;endif; ?>
</tbody>

</table>

<br/><br/>


<h2 style="padding-left:40%">每月礼包兑换码</h2>

<a href="__APP__/Monthprize/addduihuanma<?php echo ($yuurl); ?>" class="yubutton yuwhite">添加兑换码信息</a>
<br/><br/>

<form action="__APP__/Monthprize/index" method="get" >
<table class="mainTabled" >

<tr>
	<td align="center">是否使用</td>
	<td align="center">兑换码类型</td>
	<td align="center">兑换码添加时间</td>
	<td align="center"></td>
</tr>

<tr>

	<td>
		<select name="flag_s">
			<?php echo ($optionflag); ?>
		</select>
	</td>
	
	<td>
		<input type="text" name="type_s" id="type_s" size="50" maxlength="50" value="<?php echo ($type_s); ?>"/>
	</td>
	
	<td>
		<input type="text" name="addtime" id="addtime" size="30" maxlength="30" value="<?php echo ($addtime); ?>"/>
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
		<td width="30"><b>兑换码输入时间<br/></b></td>
		<td width="30"><b>兑换码类型<br/></b></td>
		<td width="30"><b>使用状态</b></td>
		<td width="50"><b>兑换码<br/></b></td>
		<td width="30">&nbsp;</td>
		
	</tr>
</thead>

<tbody>
	<?php if(is_array($list1)): $i = 0; $__LIST__ = $list1;if( count($__LIST__)==0 ) : echo "没有对应数据" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
		
		<td><?php echo ($vo["id"]); ?></td>
		<td><?php echo ($vo["addtime"]); ?></td>
		<td><?php echo ($vo["type"]); ?></td>
		<td><?php echo ($vo["flag"]); ?></td>
		<td><?php echo ($vo["duihuanma"]); ?></td>
		
		<td>
			<form action="__APP__/Monthprize/deletedhdata<?php echo ($yuurl); ?>" method="post" style="margin:0px">
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