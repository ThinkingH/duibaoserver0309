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
		<font size="-1" ><b>当前位置&nbsp;#&nbsp;配置信息&nbsp;#&nbsp;任务列表信息查询</b></font></td></tr>
  	<tr><td bgcolor="#b1ceef" height="1"></td></tr>
	<tr height="20"><td background="__PUBLIC__/Images/shadow_bg.jpg"></td></tr>
</table>

<a href="__APP__/Tasklist/addtaskshow<?php echo ($yuurl); ?>" class="yubutton yuwhite">添加任务信息</a>
<br/><br/>

<form action="__APP__/Tasklist/index" method="get" >
<table class="mainTabled" >

<tr>
	<td align="center">是否启用</td>
	<td align="center">任务名称</td>
	<td align="center">任务积分值</td>
	<td align="center">到期时间</td>
	<td align="center"></td>
</tr>

<tr>

	<td>
		<select name="flag_s">
			<?php echo ($optionflag); ?>
		</select>
	</td>
	
	<td>
		<input type="text" name="taskname" id="taskname" value="<?php echo ($taskname); ?>" size="20" maxlength="20" />
	</td>
	
	<td>
		<input type="text" name="score" id="score" value="<?php echo ($score); ?>" size="10" maxlength="10" />
	</td>
	
	
	<td>
		<input type="text" size="12" maxlength="10" name="date_s" value="<?php echo ($date_s); ?>" onclick="WdatePicker()" />--
		<input type="text" size="12" maxlength="10" name="date_e" value="<?php echo ($date_e); ?>" onclick="WdatePicker()" />
	</td>
	
	
	
	<td>
		<input type="submit" class="yubuttons yuwhite" name="submit_select" id="submit_select" value="查询指定匹配内容" />
	</td>
</tr>
</table>
</form>
<br/>

<table class="mainTables" width="100%">
<thead>
	<tr>
		<td width="40">&nbsp;</td>
		<td width="85">&nbsp;</td>
		<td width="65">&nbsp;</td>
		<td width="40">&nbsp;</td>
		<td width="50"><b>自增编号<br/></b></td>
		<td width="50"><b>是否启用<br/></b></td>
		<td width="50"><b>任务类型<br/></b></td>
		<td width="130"><b>到期时间</b></td>
		<td width="80"><b>任务积分值</b></td>
		<td width="80"><b>下载次数<br/></b></td>
		<td width="80"><b>积分领取次数<br/></b></td>
		<td width="100"><b>任务名称<br/></b></td>
		<td width="120"><b>任务说明<br/></b></td>
		
		<td width="120"><b>任务展示主图<br/></b></td>
		<td width="100"><b>流程图片1<br/></b></td>
		<td width="100"><b>流程图片2<br/></b></td>
		<td width="100"><b>流程图片3<br/></b></td>
		<td width="100"><b>流程图片4<br/></b></td>
		<td width="100"><b>流程图片5<br/></b></td>
		
		<td width="150"><b>活动概述<br/></b></td>
		<td width="150"><b>活动规则<br/></b></td>
		
		<td width="150"><b>安卓下载地址<br/></b></td>
		<td width="150"><b>ios下载地址<br/></b></td>
		<td width="150"><b>网页浏览地址<br/></b></td>
		
		<!-- <td width="130"><b>流程图片标题1</b></td>
		<td width="150"><b>流程图片描述1</b></td>
		
		
		<td width="130"><b>流程图片标题2</b></td>
		<td width="150"><b>流程图片描述2</b></td>
		
		<td width="80"><b>任务名称<br/></b></td>
		
		<td width="130"><b>流程图片标题3</b></td>
		<td width="150"><b>流程图片描述3</b></td>
		
		
		<td width="130"><b>流程图片标题4</b></td>
		<td width="150"><b>流程图片描述4</b></td>
		
		
		<td width="130"><b>流程图片标题5</b></td>
		<td width="150"><b>流程图片描述5</b></td> -->
		
		
		
		<td width="130"><b>创建时间</b></td>
		
	</tr>
</thead>

<tbody>
	<?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "没有对应数据" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
		<td>
			<form action="__APP__/Tasklist/updatetaskshow<?php echo ($yuurl); ?>" method="post" style="margin:0px">
			<input type="hidden" name="id" value="<?php echo ($vo["id"]); ?>" />
			<input type="submit" class="yubuttonss yuwhite" name="update_submit" value="修改" />
			</form>
		</td>
		
		<td>
			<form action="__APP__/Tasklist/uploadimg<?php echo ($yuurl); ?>" method="post" style="margin:0px">
			<input type="hidden" name="id" value="<?php echo ($vo["id"]); ?>" />
			<input type="submit" class="yubuttonss yuwhite" name="upimg_submit" value="上传图片修改" />
			</form>
		</td>
		
		<td>
            <form action="__APP__/Tasklist/apkupload<?php echo ($yuurl); ?>" method="post" style="margin:0px">
            <input type="hidden" name="id" value="<?php echo ($vo["id"]); ?>" />
            <input type="submit" class="yubuttonss yuwhite" name="upapk_submit" value="上传apk" />
            </form>
        </td>
		
		<td>
			<form action="__APP__/Tasklist/deletetaskdata<?php echo ($yuurl); ?>" method="post" style="margin:0px">
			<input type="hidden" name="id" value="<?php echo ($vo["id"]); ?>" />
			<input type="submit" class="yubuttonss yuwhite delete_submit" name="delete_submit" value="删除" />
			</form>
	</td>
		<td><?php echo ($vo["id"]); ?></td>
		<td><?php echo ($vo["flag"]); ?></td>
		<td><?php echo ($vo["type"]); ?></td>
		<td><?php echo ($vo["over_inttime"]); ?></td>
		<td><?php echo ($vo["score"]); ?></td>
		<td><?php echo ($vo["downtimes"]); ?></td>
		<td><?php echo ($vo["scoretimes"]); ?></td>
		<td><?php echo ($vo["name"]); ?></td>
		<td><?php echo ($vo["shuoming"]); ?></td>
		
		<td>
		<img src="<?php echo ($vo["mainimage"]); ?>" height="100" />
		</td>
		<td>
		<img src="<?php echo ($vo["liucheng_1_img"]); ?>" height="100" />
		<br><?php echo ($vo["liucheng_1_title"]); ?>
		<br><?php echo ($vo["liucheng_1_miaoshu"]); ?>
		</td>
		<td><img src="<?php echo ($vo["liucheng_2_img"]); ?>" height="100"/>
		<br><?php echo ($vo["liucheng_2_title"]); ?>
		<br><?php echo ($vo["liucheng_2_miaoshu"]); ?>
		</td>
		<td><img src="<?php echo ($vo["liucheng_3_img"]); ?>" height="100" />
		<br><?php echo ($vo["liucheng_3_title"]); ?>
		<br><?php echo ($vo["liucheng_3_miaoshu"]); ?>
		</td>
		
		<td><img src="<?php echo ($vo["liucheng_4_img"]); ?>" height="100" />
		<br><?php echo ($vo["liucheng_4_title"]); ?>
		<br><?php echo ($vo["liucheng_4_miaoshu"]); ?>
		</td>
		<td><img src="<?php echo ($vo["liucheng_5_img"]); ?>" height="100"/>
		<br><?php echo ($vo["liucheng_5_title"]); ?>
		<br><?php echo ($vo["liucheng_5_miaoshu"]); ?>
		</td>
		
		<td><?php echo ($vo["huodonggaishu"]); ?></td>
		<td><?php echo ($vo["huodongguize"]); ?></td>
		
		<td><?php echo ($vo["downurl"]); ?></td>
		<td><?php echo ($vo["iosdownurl"]); ?></td>
		<td><?php echo ($vo["showurl"]); ?></td>
		
		<!-- <td><?php echo ($vo["liucheng_1_title"]); ?></td>
		<td><?php echo ($vo["liucheng_1_miaoshu"]); ?></td>
		
		
		<td><?php echo ($vo["liucheng_2_title"]); ?></td>
		<td><?php echo ($vo["liucheng_2_miaoshu"]); ?></td>
		
		<td><?php echo ($vo["name"]); ?></td>
		
		
		<td><?php echo ($vo["liucheng_3_title"]); ?></td>
		<td><?php echo ($vo["liucheng_3_miaoshu"]); ?></td>
		
		
		<td><?php echo ($vo["liucheng_4_title"]); ?></td>
		<td><?php echo ($vo["liucheng_4_miaoshu"]); ?></td>
		
		
		
		<td><?php echo ($vo["liucheng_5_title"]); ?></td>
		<td><?php echo ($vo["liucheng_5_miaoshu"]); ?></td> -->
		
		
		
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