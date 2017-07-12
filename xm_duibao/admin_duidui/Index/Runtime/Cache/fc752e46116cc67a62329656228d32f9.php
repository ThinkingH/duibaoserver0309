<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//Dtd html 4.0 Transitional//EN"><html><head><meta http-equiv=Content-Type content="text/html; charset=utf-8"><link rel="stylesheet" type="text/css" href="__PUBLIC__/Css/admin.css" /><script type="text/javascript" src="__PUBLIC__/Js/jquery.min.js"></script><script language="javascript" type="text/javascript" src="__PUBLIC__/Js/datexxx/WdatePicker.js"></script><script type="text/javascript" src="__PUBLIC__/Js/themain.js"></script><script type="text/javascript">
$("document").ready(function(){
	
	$(".delete_submit").click(function(){
		
		if(confirm("您确认要删除此条数据吗？")) {
			//alert('ok');
			/* if(confirm("您确认要删除此条数据吗？数据删除后可能会导致数据错乱，请确认该数据下是否存在关联数据")) {
				//alert('ok');
			}else {
				return false;
			} */
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


</script></head><body><table cellspacing="0" cellpadding="0" width="100%" align="center" border="0"><tr height="28"><td background="__PUBLIC__/Images/title_bg1.jpg"><font size="-1" ><b>当前位置&nbsp;#&nbsp;配置信息&nbsp;#&nbsp;临时用户信息&nbsp;#&nbsp;临时用户信息查询</b></font></td></tr><tr><td bgcolor="#b1ceef" height="1"></td></tr><tr height="20"><td background="__PUBLIC__/Images/shadow_bg.jpg"></td></tr></table><!-- <a href="__APP__/Basicsite/addsiteshow<?php echo ($yuurl); ?>" class="yubutton yuwhite">添加渠道信息</a><br/><br/> --><form action="__APP__/Userlist/tempuser" method="get" ><table class="mainTabled" ><tr><td align="center">临时id</td><td align="center">可用积分</td><td align="center">创建时间</td><td align="center"></td></tr><tr><td><input type="text" size="20" maxlength="20" name="id" value="<?php echo ($id); ?>" /></td><td><input type="text" name="score" id="score" value="<?php echo ($score); ?>" size="15" maxlength="20" /></td><td><input type="text" size="10" maxlength="10" name="date_s" value="<?php echo ($date_s); ?>" onclick="WdatePicker()" />--
		<input type="text" size="10" maxlength="10" name="date_e" value="<?php echo ($date_e); ?>" onclick="WdatePicker()" /></td><td><input type="submit" class="yubuttons yuwhite" name="submit_select" id="submit_select" value="查询指定匹配内容" /></td></tr></table></form><br/><table class="mainTables" width="1500"><thead><tr><!-- <td width="50">&nbsp;</td> --><td width="50">&nbsp;</td><td width="50">&nbsp;</td><td width="200"><b>自增编号<br/></b></td><!-- <td width="150"><b>通讯秘钥<br/></b></td> --><td width="200"><b>可用积分</b></td><!-- <td width="80"><b>冻结积分</b></td> --><td width="200"><b>创建时间<br/></b></td><td width="300"><b>备注<br/></b></td></tr></thead><tbody><?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "没有对应数据" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr><!-- <td><form action="__APP__/Userlist/tempjifenchangshow<?php echo ($yuurl); ?>" method="post" style="margin:0px"><input type="hidden" name="id" value="<?php echo ($vo["id"]); ?>" /><input type="hidden" name="tablename" value="2" /><input type="submit" class="yubuttonss yuwhite" name="jifen_submit" value="积分变动" /></form></td> --><!-- 	<td><form action="__APP__/Userlist/moneychangshow<?php echo ($yuurl); ?>" method="post" style="margin:0px"><input type="hidden" name="id" value="<?php echo ($vo["id"]); ?>" /><input type="hidden" name="tablename" value="1" /><input type="submit" class="yubuttonss yuwhite" name="money_submit" value="金额变动" /></form></td> --><td><form action="__APP__/Userlist/tempupdateusershow<?php echo ($yuurl); ?>" method="post" style="margin:0px"><input type="hidden" name="id" value="<?php echo ($vo["id"]); ?>" /><input type="hidden" name="tablename" value="2" /><input type="submit" class="yubuttonss yuwhite" name="update_submit" value="修改" /></form></td><td><form action="__APP__/Userlist/deleteuserdata<?php echo ($yuurl); ?>" method="post" style="margin:0px"><input type="hidden" name="id" value="<?php echo ($vo["id"]); ?>" /><input type="hidden" name="tablename" value="2" /><input type="submit" class="yubuttonss yuwhite delete_submit" name="delete_submit" value="删除" /></form></td><td><?php echo ($vo["id"]); ?></td><!-- <td><?php echo ($vo["tokenkey"]); ?></td> --><td><?php echo ($vo["keyong_jifen"]); ?></td><!-- <td><?php echo ($vo["dongjie_jifen"]); ?></td> --><td><?php echo ($vo["create_datetime"]); ?></td><td><?php echo ($vo["remark"]); ?></td></tr><?php endforeach; endif; else: echo "没有对应数据" ;endif; ?></tbody></table><br/><br/><center><?php echo ($page); ?></center><br/><br/><br/><br/><br/><br/>
End!

</body></html>