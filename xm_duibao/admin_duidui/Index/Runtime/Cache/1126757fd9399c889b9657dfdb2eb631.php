<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//Dtd html 4.0 Transitional//EN"><html><head><meta http-equiv=Content-Type content="text/html; charset=utf-8"><link rel="stylesheet" type="text/css" href="__PUBLIC__/Css/admin.css" /><script type="text/javascript" src="__PUBLIC__/Js/jquery.min.js"></script><script type="text/javascript" src="__PUBLIC__/Js/themain.js"></script><script type="text/javascript">
$("document").ready(function(){
	
	$(".mainTables>tbody>tr>td").hover(function(){
		$(this).parent().children().addClass('yu_mourse_stop_change');
	},function(){
		$(this).parent().children().removeClass('yu_mourse_stop_change');
	});
	
});
</script></head><body><table cellspacing="0" cellpadding="0" width="100%" align="center" border="0"><tr height="28"><td background="__PUBLIC__/Images/title_bg1.jpg"><font size="-1" ><b>当前位置&nbsp;#&nbsp;用户状态&nbsp;#&nbsp;用户在线状态 </b></font></td></tr><tr><td bgcolor="#b1ceef" height="1"></td></tr><tr height="20"><td background="__PUBLIC__/Images/shadow_bg.jpg"></td></tr></table><br/><font color="red"><b>
	&nbsp;注：此模块主要用于让用户了解其他用户的在线状态，以便通知其他用户及时查看对应信息<br/>
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	用户登录时间和ip地址每11分钟进行一次检测更新。  用户在线状态仅供参考。
</b></font><br/><br/><br/><table class="mainTables" ><thead><tr><td width="110"><b>姓名</b></td><td width="160"><b>最近一次检测---在线时间</b></td><td width="160"><b>最近一次检测---ip地址</b></td><td width="160"><b>在线状态（不一定准确）</b></td><td width="90"><b>联系qq</b></td></tr></thead><tbody><?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "没有对应数据" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr><td>&nbsp;<?php echo ($vo["xingming"]); ?></td><td><?php echo ($vo["lastLoginTime"]); ?></td><td><?php echo ($vo["lastLoginIp"]); ?></td><td><?php echo ($vo["state"]); ?></td><td><?php echo ($vo["qq"]); ?></td></tr><?php endforeach; endif; else: echo "没有对应数据" ;endif; ?></tbody></table><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/>
End!

</body></html>