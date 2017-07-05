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
	
	$(".fabu_submit").click(function(){
		
		if(confirm("确定发布该信息嘛？")) {
			
		}else {
			return false;
		}
	});
	
	$("#bohui_submit").click(function(){
		
		if(confirm("确定驳回该信息嘛？")) {
			
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
		<font size="-1" ><b>当前位置&nbsp;#&nbsp;配置信息&nbsp;#&nbsp;商户管理&nbsp;#&nbsp;商户开户管理</b></font></td></tr>
  	<tr><td bgcolor="#b1ceef" height="1"></td></tr>
	<tr height="20"><td background="__PUBLIC__/Images/shadow_bg.jpg"></td></tr>
</table>

<!-- <a href="__APP__/Shanghu/addshow<?php echo ($yuurl); ?>" class="yubutton yuwhite">发布信息</a> -->
<br/><br/>

<form action="__APP__/Shanghu/index" method="get" >
<table class="mainTabled" >

<tr>
	<td align="center">是否注销</td>
	<td align="center">发布时间</td>
	<td align="center">审核状态</td>
	<td align="center"></td>
</tr>

<tr>

	<td>
		<select name="flag_s">
			<?php echo ($optionflag); ?>
		</select>
	</td>
	
	<td>
		<input type="text" size="12" maxlength="10" name="date_s" value="<?php echo ($date_s); ?>" onclick="WdatePicker()" />--
		<input type="text" size="12" maxlength="10" name="date_e" value="<?php echo ($date_e); ?>" onclick="WdatePicker()" />
	</td>
	
	<td>
		<select name="status">
			<?php echo ($optionstatus); ?>
		</select>
	</td>
	
	<td>
		<input type="submit" class="yubuttons yuwhite" name="submit_select" id="submit_select" value="查询指定匹配内容" />
	</td>
</tr>

</table>
</form>
<br/>

<table class="mainTables" width="1600">
<thead>
	<tr>
		<td width="4%">&nbsp;</td>
		<td width="4%">&nbsp;</td>
		<td width="5%"><b>商户编号<br/></b></td>
		<td width="5%"><b>审核状态<br/></b></td>
		<td width="5%"><b>是否开启<br/></b></td>
		<td width="5%"><b>联系人<br/></b></td>
		<td width="6%"><b>联系方式</b></td>
		<td width="10%"><b>邮箱</b></td>
		<td width="15%"><b>公司名称</b></td>
		<td width="20%"><b>公司地址</b></td>
		<td width="15%"><b>账户信息</b></td>
		<!-- <td width="10%"><b>营业执照副本1</b></td>
		<td width="10%"><b>营业执照副本2</b></td> -->
		<td width="8%"><b>开户时间</b></td>
	</tr>
</thead>

<tbody>
	<?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "没有对应数据" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
			<td align="center">
			
			<!-- <form action="__APP__/Shanghu/updateshow<?php echo ($yuurl); ?>" method="post" style="margin:0px">
			<input type="hidden" name="id" value="<?php echo ($vo["id"]); ?>" />
			<input type="submit" class="yubuttonss yuwhite" name="update_submit" value="修改" />
			</form> -->
			
			<form action="__APP__/Shanghu/chakanshow<?php echo ($yuurl); ?>" method="post" style="margin:0px">
			<input type="hidden" name="id" value="<?php echo ($vo["id"]); ?>" />
			<input type="submit" class="yubuttonss yuwhite" name="update_submit" value="查看" />
			</form>
			
			</td>
			
			<td>
			<form action="__APP__/Shanghu/deletedata<?php echo ($yuurl); ?>" method="post" style="margin:0px">
			<input type="hidden" name="id" value="<?php echo ($vo["id"]); ?>" />
			<?php if($vo['flag']=='1'){ ?>
			<input type="submit" class="yubuttonss yuwhite delete_submit" name="delete_submit" value="注销" />
			<?php }else if($vo['flag']=='9'){ ?>
			<input type="submit" class="yubuttonss yuwhite delete_submit" name="delete_submit" value="注销" disabled="disabled"/>
			<?php } ?>
			</form>
			
			<!-- <br><br>
		
			<form action="__APP__/Shanghu/checkdata<?php echo ($yuurl); ?>" method="post" style="margin:0px">
			<input type="hidden" name="id" value="<?php echo ($vo["id"]); ?>" />
			<input type="hidden" name="userid" value="<?php echo ($vo["userid"]); ?>" />
			<input type="hidden" name="discount" value="<?php echo ($vo["reamrk"]); ?>" />
			<input type="hidden" name="flag" value="1" />
			<input type="submit" class="yubuttonss yuwhite" name="check_submit" id="fabu_submit"  value="发布" />
			</form>
			
			
			<form action="__APP__/Shanghu/checkdata<?php echo ($yuurl); ?>" method="post" style="margin:0px">
			<input type="hidden" name="id" value="<?php echo ($vo["id"]); ?>" />
			<input type="hidden" name="userid" value="<?php echo ($vo["userid"]); ?>" />
			<input type="hidden" name="discount" value="<?php echo ($vo["reamrk"]); ?>" />
			<input type="hidden" name="flag" value="2" />
			<input type="submit" class="yubuttonss yuwhite" name="check_submit" id="bohui_submit" value="驳回" />
			</form> -->
		
		</td>
		
		
		
		<td align="center"><?php echo ($vo["id"]); ?></td>
		<td align="center"><?php echo ($vo["checkstatus"]); ?></td>
		<td align="center"><?php echo ($vo["flags"]); ?></td>
		<td><?php echo ($vo["lianxiren"]); ?></td>
		<td><?php echo ($vo["phone"]); ?></td>
		<td><?php echo ($vo["email"]); ?></td>
		<td><?php echo ($vo["company"]); ?></td>
		<td><?php echo ($vo["address"]); ?></td>
		<td><?php echo ($vo["username"]); ?></td>
		<!-- <td><img alt="" src="<?php echo ($vo["bussinelicence1"]); ?>" width="150"></td>
		<td><img alt="" src="<?php echo ($vo["bussinelicence2"]); ?>" width="150"></td> -->
		<td><?php echo ($vo["create_datetime"]); ?></td>
	</tr><?php endforeach; endif; else: echo "没有对应数据" ;endif; ?>
</tbody>

</table>

<br/><br/>

<center><?php echo ($page); ?></center>

<br/><br/><br/><br/><br/><br/>
End!

</body>

</html>