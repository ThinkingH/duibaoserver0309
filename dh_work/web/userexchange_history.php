<?php

//当前微信用户购买记录


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
	<title></title>
	<link rel="stylesheet" href="../public/css/weui.min.css"/>
	
</head>
<div id='wx_logo' style='margin:0 auto;display:none;'>
<img src='../public/pic/fenxiang_1.jpg' />
</div>
<body>
<!-- ------------------------------------ -->
<!-- 顶部标题栏------------------------------------ -->
<div style="background-color:#4AC7E0;height:35px;">
<div style="height:33px;width:33px;float:left;margin:0px;padding:2px;">
<a data-ajax="false" href="#"><image src="../public/pic/dh_w_l.png" style="width:30px;height:30px;" onclick="javascript:history.go(-1);"/></a>
</div>
<div style="height:32px;width:70%;float:left;margin-top:7px;text-align:center;">
<span>兑换记录</span>
</div>
<div style="height:33px;width:33px;float:right;margin:0px;padding:2px;">
<a data-ajax="false" href="http://wx.51faba.cn/wx_work/wx/wxshow/gerenzhongxin.php"><image src="../public/pic/dh_w_r.png" style="width:30px;height:30px;" /></a>
</div>
</div>
<!-- ------------------------------------ -->


<?php


if($openId=='') {
	echo '<br/><h2>&nbsp;您在本平台没有对应兑换记录wx</h2>';
	
}else {
	
	//数据库初始化
	$HyDb = new HyDb();
	
	
	//查询订单数据
	$sql_getorderdata = "select id,hy_flag,only_orderid,have_price,phone,liuliangcode_id,create_datetime,over_datetime,remark
						from order_data
						where wxopenid='".$openId."'
						and c_typeid='1201'
						and hy_flag in(21,25,26)
						order by id desc
						limit 30";
	
	$list_getorderdata = $HyDb->get_all($sql_getorderdata);
		
		
	if(count($list_getorderdata)>0) {
		//定义列表查询数组变量
		$x_liucodelistarr = array();
		
		//查询产品列表，生成对应数组
		$sql_getliuliangcodelist = "select id,name from liuliang_code";
		$list_getliuliangcodelist = $HyDb->get_all($sql_getliuliangcodelist);
		if(count($list_getliuliangcodelist)>0) {
			foreach($list_getliuliangcodelist as $keyc => $valc) {
				$x_liucodelistarr[$valc['id']] = $valc;
			}
		}
		
		
		
		
		foreach($list_getorderdata as $keyg => $valg) {
			echo '<div class="yuanjiaodiv"><table>';
			?>
			<tr>
				<td width="80px" align="right">产品名称&nbsp;&nbsp;</td>
				<td><?php
				if(isset($x_liucodelistarr[$valg['liuliangcode_id']])) {
					echo $x_liucodelistarr[$valg['liuliangcode_id']]['name'];
				}else {
					echo $valg['liuliangcode_id'];
				}
				?></td>
			</tr>
			<tr>
				<td width="80px" align="right">订单号&nbsp;&nbsp;</td>
				<td><?php echo $valg['only_orderid'];?></td>
			</tr>
			<tr>
				<td width="80px" align="right">手机号码&nbsp;&nbsp;</td>
				<td><?php echo $valg['phone'];?></td>
			</tr>
			<tr>
				<td align="right">充值状态&nbsp;&nbsp;</td>
				<td><?php
					if($valg['hy_flag']=='21') {
						echo '充值中';
					}else if($valg['hy_flag']=='25') {
						echo '充值成功';
					}else if($valg['hy_flag']=='26') {
						echo '充值失败';
					}else {
						echo '未知-'.$valg['hy_flag'];
					}
				?></td>
			</tr>
			<tr>
				<td align="right">创建时间&nbsp;&nbsp;</td>
				<td><?php echo $valg['create_datetime'];?></td>
			</tr>
			<tr>
				<td align="right">完成时间&nbsp;&nbsp;</td>
				<td><?php echo $valg['over_datetime'];?></td>
			</tr>
			<tr>
				<td align="right">备注&nbsp;&nbsp;</td>
				<td><?php echo $valg['remark'];?></td>
			</tr>
			<?php
			echo '</table></div>';
		}
		
		
		
		
	}else {
		echo '<br/><h2>&nbsp;您在本平台没有对应兑换记录</h2>';
		
	}
	
	
}


?>

<br/><br/><br/>
<font color="red">&nbsp;&nbsp;本平台仅支持查看最近30条充值记录</font>
<br/><br/><br/>


<style type="text/css">
.yuanjiaodiv{ 
font-family: Arial; 
border: 1px solid #00D046; 
border-radius: 15px; 
padding: 1%; 
width: 94%;
margin:15px 1%;
background-color:#F5FFF5;
}</style> 


</body>
</html>
