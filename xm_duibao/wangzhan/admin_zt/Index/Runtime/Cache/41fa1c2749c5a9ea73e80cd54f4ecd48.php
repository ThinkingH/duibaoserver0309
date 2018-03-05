<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//Dtd html 4.0 Transitional//EN">
<html>
<head>
<meta http-equiv=Content-Type content="text/html; charset=utf-8">
<link rel="stylesheet" type="text/css" href="__PUBLIC__/Css/admin.css" />

<script type="text/javascript" src="__PUBLIC__/Js/jquery.min.js"></script>
<script type="text/javascript" src="__PUBLIC__/Js/themain.js"></script>
<script language="javascript" type="text/javascript" src="__PUBLIC__/Js/jquery.jqprint.js"></script>
<script type="text/javascript">
$("document").ready(function(){
	
	 $("#update_submit").click(function(){
		$("#dayincontent_two").jqprint({
	     debug: false, //如果是true则可以显示iframe查看效果（iframe默认高和宽都很小，可以再源码中调大），默认是false
	     importCSS: true, //true表示引进原来的页面的css，默认是true。（如果是true，先会找$("link[media=print]")，若没有会去找$("link")中的css文件）
	     printContainer: true, //表示如果原来选择的对象必须被纳入打印（注意：设置为false可能会打破你的CSS规则）。
	     operaSupport: true//表示如果插件也必须支持歌opera浏览器，在这种情况下，它提供了建立一个临时的打印选项卡。默认是true
	    }); 
	}); 
	 
	 $("#fabu").click(function() {
			
			if(confirm("您确认要发布这条数据吗？")) {
				//alert('ok');
			}else {
				return false;
			}
		});  
		
});


</script>
</head>

<body>
<table cellspacing="0" cellpadding="0" width="100%" align="center" border="0" >
	<tr height="28"><td>
		<font size="-1" ><b>当前位置&nbsp;#&nbsp;中铁数据查询&nbsp;#&nbsp;数据查询&nbsp;#&nbsp;数据的查看</b></font></td></tr>
	<tr><td bgcolor="#b1ceef" height="1"></td></tr>
	
</table>

<br/>

<a href="__APP__/Reportdata/index<?php echo ($yuurl); ?>" class="yubutton yuwhite">返回主页面</a>
<br/><br/><br/>


<div id="dayincontent_two">

<form action="__APP__/Reportdata/fabuclick<?php echo ($yuurl); ?>" method="post">
<input type="hidden" name="id" value="<?php echo ($list['id']); ?>" />

<table class="mainTabled">
	
	<tr>
		<td width="200" align="right">编号:</td>
		<td width="800">
			<b><?php echo ($list['id']); ?></b>
		</td>
	</tr>
	
	<tr>
		<td width="200" align="right">审核状态:</td>
		<td width="800">
			<b><?php echo ($list['flag']); ?></b>
		</td>
	</tr>
	<tr>
		<td width="200" align="right">公司:</td>
		<td width="800">
		<b>
			<?php echo ($list['company']); ?>
		</b>
		</td>
	</tr>
	
	<tr>
		<td width="200" align="right">电话:</td>
		<td width="800">
		<b>
			<?php echo ($list['phone']); ?>
		</b>
		</td>
	</tr>
	
	<tr>
		<td width="200" align="right">上家类型:</td>
		<td width="800">
		<b>
			<?php echo ($list['shangjiatype']); ?>
		</b>
		</td>
		</td>
	</tr>
	
	<tr>
		<td width="200" align="right">产品名称:</td>
		<td width="800">
		<b>
			<?php echo ($list['name']); ?>
		</b>
		</td>
	</tr>
	
	<tr>
		<td width="200" align="right">价格:</td>
		<td width="800">
		<b>
			<?php echo ($list['price']); ?>
		</b>
		</td>
	</tr>
	
	<tr>
		<td width="200" align="right">数量:</td>
		<td width="800">
		<b>
			<?php echo ($list['shuliang']); ?>
		</b>
		</td>
	</tr>
	
	<tr>
		<td width="200" align="right">交货地:</td>
		<td width="800">
		<b>
			<?php echo ($list['address']); ?>
		</b>
		</td>
	</tr>
	
	
	
	<tr>
		<td width="200" align="right">配送方式:</td>
		<td width="800">
		<b>
			<?php echo ($list['sendtype']); ?>
		</b>
		</td>
	</tr>
	
	<tr>
		<td width="200" align="right">提交时间:</td>
		<td width="800">
		<b>
			<b><?php echo ($list['tijiao_time']); ?></b>
		</b>
		</td>
	</tr>
	
	<tr>
		<td width="200" align="right">审核发布时间:</td>
		<td width="800">
		<b>
			<b><?php echo ($list['createtime']); ?></b>
		</b>
		</td>
	</tr>
	
	<tr>
		<td width="200" align="right">备注:</td>
		<td width="800">
		<b>
			<?php echo ($list['comment']); ?>
		</b>
		</td>
	</tr>
	
</table>

<br><br>
<!-- <input type="submit" id="fabu" class="yubutton yuwhite" name="fabu"  value="点击发布" /> -->
</form>

</div>


<input type="submit" id="update_submit" class="yubutton yuwhite" name="update_submit" style="margin:15px 0px 0px 10px;" value="打印" />
<br/><br/>



<br/><br/><br/><br/><br/><br/>
End!

</body>

</html>