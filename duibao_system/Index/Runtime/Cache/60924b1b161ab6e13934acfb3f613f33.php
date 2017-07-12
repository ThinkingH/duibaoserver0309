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
		
		if(confirm("您确认要进行此操作吗？")) {
			
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
		<font size="-1" ><b>当前位置&nbsp;#&nbsp;配置信息&nbsp;#&nbsp;优惠券信息管理</b></font></td></tr>
  	<tr><td bgcolor="#b1ceef" height="1"></td></tr>
	<tr height="20"><td background="__PUBLIC__/Images/shadow_bg.jpg"></td></tr>
</table>

<a href="__APP__/Youhuiquan/addquanshow<?php echo ($yuurl); ?>" class="yubutton yuwhite">添加优惠券信息</a>
<br/><br/>

<form action="__APP__/Youhuiquan/index" method="get" >
<table class="mainTabled" >

<tr>
	<td align="center">是否使用</td>
	<td align="center">是否过期</td>
	<td align="center">优惠券类型</td>
	<td align="center"></td>
</tr>

<tr>

	<td>
		<select name="flag">
			<?php echo ($optionflag); ?>
		</select>
	</td>
	
	<td>
		<select name="youxiao">
			<?php echo ($optionok); ?>
		</select>
	</td>
	
	<!-- <td>
		<select name="tuijian">
			<?php echo ($optiontuijian); ?>
		</select>
	</td> -->
	
	<td>
		<input type="text" name="type" id="type" size="10" maxlength="20"  value="<?php echo ($type); ?>" />
	</td>
	
	
	
	<td>
		<input type="submit" class="yubuttons yuwhite" name="submit_select" id="submit_select" value="查询指定匹配内容" />
	</td>
</tr>
</table>
</form>
<br/>

<table class="mainTables" width="1800px">
<thead>
	<tr>
		<td width="50">&nbsp;</td>
		<td width="50"><b>自增编号</b></td>
		<td width="70"><b>是否上架</b></td>
		<!-- <td width="70"><b>是否推荐</b></td> -->
		<td width="70"><b>商品类型</b></td>
		<td width="70"><b>是否有效</b></td>
		<td width="140"><b>标题</b></td>
		<td width="70"><b>价格(元)</b></td>
		<td width="140"><b>有效期</b></td>
		<td width="400"><b>优惠说明</b></td>
		<td width="400"><b>优惠券图片</b></td>
		<td width="70"><b>抓取时间</b></td>
		
		<td width="80"><b>原链接</b></td>
	</tr>
</thead>

<tbody>
	<?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "没有对应数据" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
		
		<td>
			<form action="__APP__/Youhuiquan/updateyhqshow<?php echo ($yuurl); ?>" method="post" style="margin:0px">
			<input type="hidden" name="id" value="<?php echo ($vo["id"]); ?>" />
			<input type="submit" class="yubuttonss yuwhite" name="update_submit" value="修改" />
			</form>
			<!-- <br>
			<form action="__APP__/Youhuiquan/deleteyhqdata<?php echo ($yuurl); ?>" method="post" style="margin:0px">
			<input type="hidden" name="id" value="<?php echo ($vo["id"]); ?>" />
			<input type="submit" class="yubuttonss yuwhite delete_submit" name="delete_submit" value="删除" />
			</form> -->
			
			<br>
			<form action="__APP__/Youhuiquan/updateflagdata<?php echo ($yuurl); ?>" method="post" style="margin:0px">
			<input type="hidden" name="id" value="<?php echo ($vo["id"]); ?>" />
			<input type="hidden" name="flag" value="2" />
			<input type="submit" class="yubuttonss yuwhite delete_submit" name="delete_submit" value="作废" />
			</form>
			
			<br>
			<form action="__APP__/Youhuiquan/updateflagdata<?php echo ($yuurl); ?>" method="post" style="margin:0px">
			<input type="hidden" name="id" value="<?php echo ($vo["id"]); ?>" />
			<input type="hidden" name="flag" value="1" />
			<input type="submit" class="yubuttonss yuwhite delete_submit" name="delete_submit" value="上架" />
			</form>
			
			<br>
			<form action="__APP__/Youhuiquan/updateflagdata<?php echo ($yuurl); ?>" method="post" style="margin:0px">
			<input type="hidden" name="id" value="<?php echo ($vo["id"]); ?>" />
			<input type="hidden" name="flag" value="9" />
			<input type="submit" class="yubuttonss yuwhite delete_submit" name="delete_submit" value="下架" />
			</form>
		</td>
		
		<td><?php echo ($vo["id"]); ?></td>
		<td><?php echo ($vo["flag"]); ?></td>
		<!-- <td><?php echo ($vo["tuijian"]); ?></td> -->
		<td><?php echo ($vo["type"]); ?></td>
		<td><?php echo ($vo["youxiao"]); ?></td>
		<td><?php echo ($vo["title"]); ?></td>
		<td><?php echo ($vo["jiage"]); ?></td>
		<td><?php echo ($vo["youxiaoqi"]); ?></td>
		<td><?php echo ($vo["content"]); ?></td>
		 <td>
		<img src="<?php echo ($vo["imgurl"]); ?>" width="360"/>
		</td>
		<td><?php echo ($vo["zhuaqutimes"]); ?></td>
	<td>
	<a href="<?php echo ($vo["theurl"]); ?>" target="__blank" class="yubuttons yuwhite">原链接</a>
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