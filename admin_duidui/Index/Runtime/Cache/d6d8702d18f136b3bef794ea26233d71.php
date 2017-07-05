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
		<font size="-1" ><b>当前位置&nbsp;#&nbsp;配置信息&nbsp;#&nbsp;首页类型管理</b></font></td></tr>
  	<tr><td bgcolor="#b1ceef" height="1"></td></tr>
	<tr height="20"><td background="__PUBLIC__/Images/shadow_bg.jpg"></td></tr>
</table>

<a href="__APP__/Firsttype/addfirsttypeshow<?php echo ($yuurl); ?>" class="yubutton yuwhite">添加首页类型</a>
<br/><br/>

<form action="__APP__/Firsttype/index" method="get" >
<table class="mainTabled" >

<tr>
	<td align="center">是否上架</td>
	<td align="center">是否过期</td>
	<td align="center">优惠券类型</td>
	<td align="center">商品标题</td>
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
	
	<td>
		<select name="type">
			<?php echo ($optiontype); ?>
		</select>
	</td>
	
	<td>
		<input type="text" name="title" id="title" size="20" maxlength="20"  value="<?php echo ($title); ?>" />
	</td>
	
	<td>
		<input type="submit" class="yubuttons yuwhite" name="submit_select" id="submit_select" value="查询指定匹配内容" />
	</td>
</tr>
</table>
</form>
<br/>

<table class="mainTables" width="2100">
<thead>
	<tr>
		<td width="60">&nbsp;</td>
		<td width="50"><b>编号<br/></b></td>
		<td width="50"><b>是否上架<br/></b></td>
		<td width="50"><b>是否有效<br/></b></td>
		<td width="80"><b>类型名称</b></td>
		<td width="80"><b>商品子类型<br/></b></td>
		<td width="80"><b>查看人数<br/></b></td>
		<td width="120"><b>商品标题<br/></b></td>
		
		<td width="60"><b>原价<br/></b></td>
		<td width="60"><b>折扣价<br/></b></td>
		<td width="60"><b>券价<br/></b></td>
		
		<td width="100"><b>商品简称<br/></b></td>
		<td width="120"><b>商品描述<br/></b></td>
		<td width="120"><b>有效期<br/></b></td>
		<td width="200"><b>图片展示<br/></b></td>
		<td width="200"><b>跳转链接<br/></b></td>
		<td width="120"><b>创建时间<br/></b></td>
	</tr>
</thead>

<tbody>
	<?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "没有对应数据" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
	
		<td>
			<form action="__APP__/Firsttype/updatefirsttypeshow<?php echo ($yuurl); ?>" method="post" style="margin:0px">
			<input type="hidden" name="id" value="<?php echo ($vo["id"]); ?>" />
			<input type="submit" class="yubuttonss yuwhite" name="update_submit" value="修改" />
			</form>
			
			<br>
			<form action="__APP__/Firsttype/updateflagdata<?php echo ($yuurl); ?>" method="post" style="margin:0px">
			<input type="hidden" name="id" value="<?php echo ($vo["id"]); ?>" />
			<input type="hidden" name="flag" value="2" />
			<input type="submit" class="yubuttonss yuwhite delete_submit" name="delete_submit" value="作废" />
			</form>
			
			<br>
			<form action="__APP__/Firsttype/updateflagdata<?php echo ($yuurl); ?>" method="post" style="margin:0px">
			<input type="hidden" name="id" value="<?php echo ($vo["id"]); ?>" />
			<input type="hidden" name="flag" value="1" />
			<input type="submit" class="yubuttonss yuwhite delete_submit" name="delete_submit" value="上架" />
			</form>
			
			<br>
			<form action="__APP__/Firsttype/updateflagdata<?php echo ($yuurl); ?>" method="post" style="margin:0px">
			<input type="hidden" name="id" value="<?php echo ($vo["id"]); ?>" />
			<input type="hidden" name="flag" value="9" />
			<input type="submit" class="yubuttonss yuwhite delete_submit" name="delete_submit" value="下架" />
			</form>
			
		</td>
		
		
		<td><?php echo ($vo["id"]); ?></td>
		<td><?php echo ($vo["flag"]); ?></td>
		<td><?php echo ($vo["youxiao"]); ?></td>
		<td><?php echo ($vo["type"]); ?></td>
		<td><?php echo ($vo["childtype"]); ?></td>
		<td><?php echo ($vo["scoretimes"]); ?></td>
		<td><?php echo ($vo["title"]); ?></td>
		
		<td><?php echo ($vo["yuanjia"]); ?></td>
		<td><?php echo ($vo["zhehoujia"]); ?></td>
		<td><?php echo ($vo["quanjia"]); ?></td>
		
		<td><?php echo ($vo["name"]); ?></td>
		<td><?php echo ($vo["content"]); ?></td>
		<td><?php echo ($vo["youxiaoqi"]); ?></td>
		<td><img src="<?php echo ($vo["picurl"]); ?>" width="200"></td>
		<td><?php echo ($vo["tiaozhuanurl"]); ?></td>
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