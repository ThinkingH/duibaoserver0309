<?php

//当前微信用户红包记录


//引入主文件
require_once("../lib/c.core.php");


//获取用户openid，并将获取到的openid写入session，防止多次访问造成的获取不到openid
$HySession = new HySession();
$sess_user_openid = $HySession->get('user_openid');
if($sess_user_openid=='') {
	//获取用户openid
	$tools = new JsApiPay();
	$openId = $tools->GetOpenid();
	if($openId!='') {
		$HySession->set('user_openid',$openId);
	}
}else {
	$openId = $sess_user_openid;
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0">
	<title>红包记录</title>
	<link rel="stylesheet" href="../public/css/weui.min.css"/>
	
</head>
<div id='wx_logo' style='margin:0 auto;display:none;'>
<img src='../public/pic/fenxiang_1.jpg' />
</div>
<body>
<?php


if($openId=='') {
	echo '<br/><h2>&nbsp;您在本平台没有对应红包记录wx</h2>';
	
}else {
	
	//数据库初始化
	$HyDb = new HyDb();
	
	
	//查询订单数据
	$sql_gethongbaodata = "select id,flag,tval,phone,create_datetime,orderid
						from hongbaolist
						where wxname='".$openId."'
						and create_datetime>='".date('Y-m-d H:i:s',(time()-(30*24*60*60)))."'
						order by tval desc,create_datetime";
	
	$list_gethongbaodata = $HyDb->get_all($sql_gethongbaodata);
	
	
	if(count($list_gethongbaodata)>0) {
		
		echo '<table>';
		
		
		foreach($list_gethongbaodata as $keyg => $valg) {
			?>
			<tr>
				<td colspan="2">&nbsp;</td>
			</tr>
			
			<tr>
				<td width="80px" align="right">标识编号&nbsp;&nbsp;</td>
				<td><?php echo $valg['id'];?></td>
			</tr>
			<tr>
				<td width="80px" align="right">红包金额&nbsp;&nbsp;</td>
				<td><?php echo ($valg['tval']/100).'元';?></td>
			</tr>
			<tr>
				<td width="80px" align="right">领取时间&nbsp;&nbsp;</td>
				<td><?php echo $valg['create_datetime'];?></td>
			</tr>
			<tr>
				<td align="right">使用状态&nbsp;&nbsp;</td>
				<td><?php
					if($valg['flag']=='9') {
						echo '<b>未使用</b>';
					}else if($valg['flag']=='1') {
						echo '<b>使用成功</b>';
					}else if($valg['flag']=='6') {
						echo '充值中';
					}else if($valg['flag']=='4') {
						//该状态已经取消
						echo '使用锁定';
					}else {
						echo '未知-'.$valg['hy_flag'];
					}
				?></td>
			</tr>
			<?php if($valg['flag']=='9') { ?>
			<tr>
				<td align="right">有效期&nbsp;&nbsp;</td>
				<td><?php echo '约'.floor((strtotime($valg['create_datetime'])+(15*24*60*60)-time())/(24*60*60)).'天'.floor(((strtotime($valg['create_datetime'])+(15*24*60*60)-time())%(24*60*60))/(60*60)).'小时';?></td>
			</tr>
			<?php
			}
			if($valg['flag']=='1'||$valg['flag']=='4'||$valg['flag']=='6') { ?>
			
			<tr>
				<td align="right">使用手机&nbsp;&nbsp;</td>
				<td><?php echo $valg['phone'];?></td>
			</tr>
			<tr>
				<td align="right">使用订单&nbsp;&nbsp;</td>
				<td><?php echo $valg['orderid'];?></td>
			</tr>
			<?php }
		}
		
		echo '</table>';
		
		
	}else {
		echo '<br/><h2>&nbsp;您在本平台没有对应红包记录</h2>';
		
	}
	
	
	
	echo '<div style="padding:60px 10px;">';
	echo '<font color="red">';
	echo '<b>【红包使用规则】</b><br/>';
	echo '1、红包自领取后15天内有效，超过15天未使用，红包自动回收清理。<br/>';
	echo '2、本页面查询仅显示用户在15天内的红包数据信息，超过15天的红包数据信息不做显示，如需要查询详细使用记录请前往充值记录页面查询。<br/>';
	echo '3、红包在用户支付时会自动抵扣使用，同时优先使用金额最大的红包。<br/>';
	echo '4、红包使用的最终解释权归【送流量吧】所有。<br/>';
	
	echo '</font>';
	echo '<div>';
	
	
	
}


?>


<br/><br/><br/><br/>

</body>
</html>
