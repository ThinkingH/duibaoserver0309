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
	
	/* $(".delete_submit").click(function(){
		
		if(confirm("您确认要删除此条数据吗？")) {
		}else {
			return false;
		}
	}); */
	
	
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
		<font size="-1" ><b>当前位置&nbsp;#&nbsp;数据查询&nbsp;#&nbsp;正常用户信息推送查询</b></font></td></tr>
  	<tr><td bgcolor="#b1ceef" height="1"></td></tr>
	<tr height="20"><td background="__PUBLIC__/Images/shadow_bg.jpg"></td></tr>
</table>

<!-- <a href="__APP__/Basicsite/addsiteshow<?php echo ($yuurl); ?>" class="yubutton yuwhite">添加渠道信息</a>
<br/><br/> -->

<form action="__APP__/Scoredata/usertuisong" method="get" >
<table class="mainTabled" >

<tr>
	<td align="center">创建时间</td>
	<td align="center">用户标识id</td>
	<td align="center">手机号</td>
	<td align="center">推送类型</td>
	<!-- <td align="center">信息读取状态</td> -->
	<td align="center"></td>
</tr>


<tr>
	
	<td>
		<input type="text" size="10" maxlength="10" name="date_s" value="<?php echo ($date_s); ?>" onclick="WdatePicker()" />--
		<input type="text" size="10" maxlength="10" name="date_e" value="<?php echo ($date_e); ?>"  onclick="WdatePicker()"/>
	</td>
	
	<td>
	<input type="text" name="userid" value="<?php echo ($userid); ?>" size="10" maxlength="10">
	</td>
	
	<td>
	<input type="text" name="phone" value="<?php echo ($phone); ?>" size="15" maxlength="15">
	</td>
	
	<td >
		<select name="type">
			<?php echo ($optiontype); ?>
		</select>
	</td>
	
	<!-- <td >
		<select name="maintype">
			<?php echo ($optionmaintype); ?>
		</select>
	</td>
	 -->
	<td>
		<input type="submit" class="yubuttons yuwhite" name="submit_select" id="submit_select" value="查询指定匹配内容" />
	</td>
</tr>
</table>
</form>
<br/>

<table class="mainTables" width="1500">
<thead>
	<tr>
		<td width="30"><b>自增编号<br/></b></td>
		<td width="30"><b>标识用户id<br/></b></td>
		<td width="50"><b>用户手机号<br/></b></td>
		<td width="30"><b>推送类型</b></td>
		<!-- <td width="30"><b>信息读取状态</b></td> -->
		<td width="30"><b>任务id</b></td>
		<td width="100"><b>推送信息<br/></b></td>
		<td width="80"><b>推送时间<br/></b></td>
	</tr>
</thead>

<tbody>
	<?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "没有对应数据" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
		<td><?php echo ($vo["id"]); ?></td>
		<td><?php echo ($vo["userid"]); ?></td>
		<td><?php echo ($vo["phone"]); ?></td>
		<td><?php echo ($vo["type"]); ?></td>
		<!-- <td><?php echo ($vo["status"]); ?></td> -->
		<td><?php echo ($vo["taskid"]); ?></td>
		<td><?php echo ($vo["message"]); ?></td>
		<td><?php echo ($vo["create_inttime"]); ?></td>
	</tr><?php endforeach; endif; else: echo "没有对应数据" ;endif; ?>
</tbody>

</table>

<br/><br/>

<center><?php echo ($page); ?></center>

<br/><br/><br/><br/><br/><br/>
End!

</body>

</html>