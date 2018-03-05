<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//Dtd html 4.0 Transitional//EN">
<html>
<head>
<meta http-equiv=Content-Type content="text/html; charset=utf-8">
<link rel="stylesheet" type="text/css" href="__PUBLIC__/Css/admin.css" />

<script type="text/javascript" src="__PUBLIC__/Js/jquery.min.js"></script>
<script type="text/javascript" src="__PUBLIC__/Js/themain.js"></script>
<script type="text/javascript">
$("document").ready(function(){
	
	 //数据添加确认
	$("#update_submit").click(function() {
		
		if(confirm("您确认要审核通过该条数据吗？")) {
			//alert('ok');
		}else {
			return false;
		}
	});  
	 
	 //数据添加确认
	$("#zuofei").click(function() {
		
		if(confirm("您确认要作废该条数据吗？")) {
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
		<font size="-1" ><b>当前位置&nbsp;#&nbsp;中铁数据查询&nbsp;#&nbsp;订单信息审核&nbsp;#&nbsp;订单信息的终审</b></font></td></tr>
	<tr><td bgcolor="#b1ceef" height="1"></td></tr>
	<tr height="20"><td></td></tr>
</table>

<br/>

<a href="__APP__/Reportdata/index<?php echo ($yuurl); ?>" class="yubutton yuwhite">返回主页面</a>
<br/><br/><br/>



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
<br/><br/>



<!--***************************  -->
<?php if($list['filecheck']=='9'){ ?>
<p>没有证件上传！<p>
<?php }else{ ?>
<div>
<h2>上传的文件</h2>

	<?php if($list['businesslicence']!=''){ ?>
		<div style="float:left;">
		<a><img src="<?php echo ($list['businesslicence0']); ?>" style="width:300px;height:300px;"></a><br><br>
		<span style="font-size:16px;line-height:26px;color:#333;font-weight:300;padding-top:30px;clear: none;">营业执照或银行开户证明</span>
			<form action="__APP__/Reportdata/picfile_download" method="post" style="margin:0px">
			<input type="hidden" name="id" value="$list['id']" />
			<input type="hidden" name="lurl" value="<?php echo ($list['businesslicence']); ?>" />
			<input type="submit" class="yubuttonss yuwhite" name="first_check" value="下载" />
			</form>
		</div>
	<?php } ?>
	
	
	<?php if($list['buybill']!=''){ ?>
	<div style="float:left;margin-left:10px;">
	<a><img src="<?php echo ($list['buybill0']); ?>" style="width:300px;height:300px;"></a><br><br>
	<span style="font-size:16px;line-height:26px;color:#333;font-weight:300;padding-top:30px;clear: none;">购货发票</span>
		<form action="__APP__/Reportdata/picfile_download" method="post" style="margin:0px">
		<input type="hidden" name="id" value="$list['id']" />
		<input type="hidden" name="lurl" value="<?php echo ($list['buybill']); ?>" />
		<input type="submit" class="yubuttonss yuwhite" name="first_check" value="下载" />
		</form>
	</div>
	<?php } ?>
	
	<?php if($list['coalzhengming']!=''){ ?>
	<div style="float:left;margin-left:10px;">
	<a><img src="<?php echo ($list['coalzhengming0']); ?>" style="width:300px;height:300px;"></a><br><br>
	<span style="font-size:16px;line-height:26px;color:#333;font-weight:300;padding-top:30px;clear: none;">煤炭产地证明</span>
		<form action="__APP__/Reportdata/picfile_download" method="post" style="margin:0px">
		<input type="hidden" name="id" value="$list['id']" />
		<input type="hidden" name="lurl" value="<?php echo ($list['coalzhengming']); ?>" />
		<input type="submit" class="yubuttonss yuwhite" name="first_check" value="下载" />
		</form>
	</div>
	<?php } ?>
	
	<?php if($list['qualityreporter']!=''){ ?>
	<div style="clear:both; float:left;margin-right:20px;margin-top:20px;">
	<a><img src="<?php echo ($list['qualityreporter0']); ?>" style="width:300px;height:300px;"></a><br><br>
	<span style="font-size:16px;line-height:26px;color:#333;font-weight:300;padding-top:30px;clear: none;">质量检测报告</span>
		<form action="__APP__/Reportdata/picfile_download" method="post" style="margin:0px">
		<input type="hidden" name="id" value="$list['id']" />
		<input type="hidden" name="lurl" value="<?php echo ($list['qualityreporter']); ?>" />
		<input type="submit" class="yubuttonss yuwhite" name="first_check" value="下载" />
		</form>
	</div>
	<?php } ?>
	
	<?php if($list['tihuodan']!=''){ ?>
	<div style="float:left;margin-top:20px;margin-right:20px;">
	<a><img src="<?php echo ($list['tihuodan0']); ?>"  style="width:300px;height:300px;"></a><br><br>
	<span style="font-size:16px;line-height:26px;color:#333;font-weight:300;padding-top:30px;clear: none;">库存证明(入库单或提货单)</span>
		<form action="__APP__/Reportdata/picfile_download" method="post" style="margin:0px">
		<input type="hidden" name="id" value="$list['id']" />
		<input type="hidden" name="lurl" value="<?php echo ($list['tihuodan']); ?>" />
		<input type="submit" class="yubuttonss yuwhite" name="first_check" value="下载" />
		</form>
	</div>
	<?php } ?>
	
	<?php if($list['danzi']!=''){ ?>
	<div style="float:left;margin-top:20px;">
	<a><img src="<?php echo ($list['danzi0']); ?>"  style="width:300px;height:300px;"></a><br><br>
	<span style="font-size:16px;line-height:26px;color:#333;font-weight:300;padding-top:30px;clear: none;">关单</span>
		<form action="__APP__/Reportdata/picfile_download" method="post" style="margin:0px">
		<input type="hidden" name="id" value="$list['id']" />
		<input type="hidden" name="lurl" value="<?php echo ($list['danzi']); ?>" />
		<input type="submit" class="yubuttonss yuwhite" name="first_check" value="下载" />
		</form>
	</div>
	<?php } ?>


</div>
<!-- ********************** -->
<?php } ?>

<div style="clear:both;"></div>
<br><br>

<table>
<tr>

<td>
<form action="__APP__/Reportdata/fabudata<?php echo ($yuurl); ?>" method="post">
<input type="hidden" name="id" value="<?php echo ($list['id']); ?>" />
<input type="submit" id="update_submit" class="yubutton yuwhite" name="update_submit"  value="确认终审" />
</form>
</td>


<td>
<form action="__APP__/Reportdata/zuofeidata<?php echo ($yuurl); ?>" method="post">
<input type="hidden" name="id" value="<?php echo ($list['id']); ?>" />
<input type="submit" id="zuofei" class="yubutton yuwhite" name="zuofei" value="作废" />
</form>
</td>

</tr>
</table>
<!-- </form> -->

<br/><br/><br/><br/><br/><br/>
End!

</body>

</html>