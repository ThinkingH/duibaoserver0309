<?php if (!defined('THINK_PATH')) exit();?>﻿<!DOCTYPE html PUBLIC "-//W3C//Dtd html 4.0 Transitional//EN">
<html><head>
<meta http-equiv=Content-Type content="text/html; charset=utf-8">

<link rel="stylesheet" type="text/css" href="__PUBLIC__/Css/admin.css" />

<script type="text/javascript" src="__PUBLIC__/Js/jquery.min.js"></script>
<script type="text/javascript" src="__PUBLIC__/Js/themain.js"></script>
<script type="text/javascript">
	
function expand(el) {
	childObj = document.getElementById("child" + el);

	if(childObj.style.display == 'none') {
		childObj.style.display = 'block';
	}else {
		childObj.style.display = 'none';
	}
	return;
}


</script>

</head>
<body><!--__PUBLIC__/Images/menu_bg.jpg leftq.gif -->
	
	<table height="100%" cellspacing="0" cellpadding="0" width="150" background="__PUBLIC__/Images/22.gif" border="0" >
		<tr>
			<td valign="top" align="middle">
			
			
				<table cellspacing="0" cellpadding="0" width="100%" border="0">
					<tr><td height="10"></td></tr>
				</table>
				
				
				
				<!-- --------------------------------------------------------------------- -->
				<?php if(is_array($urlarr)): $i = 0; $__LIST__ = $urlarr;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$data): $mod = ($i % 2 );++$i;?><table cellspacing="0" cellpadding="0" width="140" border="0">
						<tr height="22">
							<!-- <td style="padding-left:30px;" background="__PUBLIC__/Images/menu_bt.jpg"> -->
							<td style="padding-left:30px;" background="__PUBLIC__/Images/menu_bt.jpg">
								<a class="menuParent" onclick=expand(<?php echo ($i); ?>) href="javascript:void(0);"><?php echo ($data["murl_name"]); ?></a>
							</td>
						</tr>
						<tr height="4"><td></td></tr>
					</table>
					<table id="child<?php echo ($i); ?>" style="display: none" cellspacing="0" cellpadding="0" width="150" border="0">
						<?php if(is_array($data["curl_name"])): foreach($data["curl_name"] as $key=>$vo): ?><tr height="20">
								<td align="middle" width="30"><img height="9" src="__PUBLIC__/Images/menu_icon.gif" width="9"></td>
								<td><a class="menuChild" href="<?php echo ($vo[2]); ?>" target="main_x" <?php if($vo[0] == 't'): ?>onclick="if (confirm('您确定要退出吗？')) return true; else return false;" <?php elseif($vo[0] == 'q'): ?> onclick="return false;" <?php else: endif; ?> ><?php echo ($vo[1]); ?></a></td>

							</tr><?php endforeach; endif; ?>
						<tr height="4"><td colSpan="2"></td></tr>
					</table><?php endforeach; endif; else: echo "" ;endif; ?>
				<!-- --------------------------------------------------------------------- -->
				
				
				
			</td>
			
			<td width="1" bgColor="#d1e6f7"></td>
			
		</tr>
		
		<tr>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
		</tr>
		
	</table>
</body>
</html>